<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\AbstractReportModel;

use DateTime;
use Exception;
use Auth;

class RepoYssCampaignReportCost extends AbstractReportModel
{
    // constant
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'campaignName';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'repo_yss_campaign_report_cost';

    /** @var array */
    private $averageFieldArray = [
        'averageCpc',
        'averagePosition',
        'impressionShare',
        'exactMatchImpressionShare',
        'budgetLostImpressionShare',
        'qualityLostImpressionShare',
        'conversions',
        'convRate',
        'convValue',
        'costPerConv',
        'valuePerConv',
        'mobileBidAdj',
        'desktopBidAdj',
        'tabletBidAdj'
    ];

        /** @var array */
    private $emptyCalculateFieldArray = [
        'quarter',
        'week',
        'network',
        'device',
        'day',
        'dayOfWeek',
        'month',
        'trackingURL',
        'campaignType',
    ];

    /**
     * @param string[] $fieldNames
     * @return Expression[]
     */
    protected function getAggregated(array $fieldNames)
    {
        $tableName = $this->getTable();
        $arrayCalculate = [];

        foreach ($fieldNames as $fieldName) {
            if ($fieldName === self::GROUPED_BY_FIELD_NAME) {
                $arrayCalculate[] = self::GROUPED_BY_FIELD_NAME;
                continue;
            }
            if (in_array($fieldName, $this->averageFieldArray)) {
                $arrayCalculate[] = DB::raw('format(trim(ROUND(AVG(' . $fieldName . '), 2)) + 0, 2) AS ' . $fieldName);
            } else {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                    ->getType()
                    ->getName()
                    === self::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'format(trim(ROUND( SUM(' . $fieldName . '), 2)) + 0, 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw('format(SUM( ' . $fieldName . ' ), 0) AS ' . $fieldName);
                }
            }
        }

        return $arrayCalculate;
    }
    /**
     * @param string[] $fieldNames
     * @param string   $accountStatus
     * @param string   $startDay
     * @param string   $endDay
     * @param int      $pagination
     * @param string   $columnSort
     * @param string   $sort
     * @return string[]
     */
    public function getDataForTable(
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $pagination,
        $columnSort,
        $sort,
        $accountId,
        $adgainerId,
        $campaignId,
        $adGroupId,
        $adReportId
    ) {
        $arrayCalculate = $this->getAggregated($fieldNames);
        $paginatedData = $this->select($arrayCalculate)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )
                ->groupBy(self::GROUPED_BY_FIELD_NAME)
                ->orderBy($columnSort, $sort);
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $paginatedData = $paginatedData->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->paginate($pagination);
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $paginatedData = $paginatedData->havingRaw(self::SUM_IMPRESSIONS_EQUAL_ZERO)
                            ->paginate($pagination);
        }
        return $paginatedData;
    }

    /**
     * @param string $column
     * @param string $accountStatus
     * @param string $startDay
     * @param string $endDay
     * @return \Illuminate\Support\Collection
     */
    public function getDataForGraph(
        $column,
        $accountStatus,
        $startDay,
        $endDay,
        $accountId,
        $adgainerId,
        $campaignId,
        $adGroupId,
        $adReportId
    ) {
        try {
            new DateTime($startDay); //NOSONAR
            new DateTime($endDay); //NOSONAR
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }

        $data = $this->select(
            DB::raw('SUM('.$column.') as data'),
            DB::raw(
                'DATE(day) as day'
            )
        )
        ->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query);
            }
        )
        ->groupBy('day');
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->get();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_EQUAL_ZERO)
                            ->get();
        }
        return $data;
    }

    public function calculateData(
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $accountId,
        $adgainerId,
        $campaignId,
        $adGroupId,
        $adReportId)
    {
        $arrayCalculate = [];
        $tableName = $this->getTable();
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === self::GROUPED_BY_FIELD_NAME) {
                continue;
            }
            if (in_array($fieldName, $this->averageFieldArray)) {
                $arrayCalculate[] = DB::raw(
                    'format(trim(ROUND('.'AVG(' . $fieldName . '),2'.'))+0, 2) AS ' . $fieldName
                );
            } elseif (!in_array($fieldName, $this->emptyCalculateFieldArray)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                    ->getType()
                    ->getName()
                    === self::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'format(trim(ROUND(SUM(' . $fieldName . '), 2))+0, 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw('format(SUM(' . $fieldName . '), 0) AS ' . $fieldName);
                }
            }
        }
        if (empty($arrayCalculate)) {
            return $arrayCalculate;
        }

        $data = $this->select($arrayCalculate)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                );
        // get aggregated value
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->first();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_EQUAL_ZERO)
                            ->first();
        }
        if ($data === null) {
            $data = [];
        } else {
            $data = $data->toArray();
        }
        return $data;
    }

    public function calculateSummaryData(
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $accountId,
        $adgainerId,
        $campaignId,
        $adGroupId,
        $adReportId
    )
    {
        $arrayCalculate = [];
        $tableName = $this->getTable();
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, $this->averageFieldArray)) {
                $arrayCalculate[] = DB::raw(
                    'format(trim(ROUND('.'AVG(' . $fieldName . '),2'.'))+0, 2) AS ' . $fieldName
                );
            } elseif (!in_array($fieldName, $this->emptyCalculateFieldArray)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                    ->getType()
                    ->getName()
                    === self::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'format(trim(ROUND(SUM(' . $fieldName . '), 2))+0, 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw('format(SUM(' . $fieldName . '), 0) AS ' . $fieldName);
                }
            }
        }
        $data = $this->select($arrayCalculate)
                    ->where(
                        function (Builder $query) use ($startDay, $endDay) {
                            $this->addTimeRangeCondition($startDay, $endDay, $query);
                        }
                    );
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->first();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_EQUAL_ZERO)
                            ->first();
        }
        if ($data === null) {
            $data = [
                'clicks' => 0,
                'impressions' => 0,
                'cost' => 0,
                'averageCpc' => 0,
                'averagePosition' => 0
            ];
        } else {
            $data = $data->toArray();
        }
        return $data;
    }

    public function getDataForExport(
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $columnSort,
        $sort
    ) {
        $arrayCalculate = $this->getAggregated($fieldNames);
        $data = $this->select($arrayCalculate)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )
                ->groupBy(self::GROUPED_BY_FIELD_NAME)
                ->orderBy($columnSort, $sort);
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->get();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_EQUAL_ZERO)
                            ->get();
        }
        return $data;
    }

    /**
     * @param string $keywords
     * @return string[]
     */
    public function getColumnLiveSearch($keywords)
    {
        $searchColumns = DB::select('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = "'. DB::connection()->getDatabaseName() .'" AND TABLE_NAME = "'. $this->table .'" 
            AND COLUMN_NAME LIKE '. '"%' . $keywords . '%"');
        $result = [];
        foreach ($searchColumns as $searchColumn) {
            foreach ($searchColumn as $value) {
                $result[] = $value;
            }
        }
        // remove column id, campaign_id ....
        $unsetColumns = [
            'id', 'campaign_id', 'account_id', 'network',
            'device', 'day', 'dayOfWeek', 'week',
            'month', 'quarter', 'hourofday', 'campaignTrackingID',
            'campaignStartDate', 'campaignEndDate', 'campaignDistributionStatus', 'campaignDistributionSettings',
            'mobileBidAdj', 'tabletBidAdj', 'desktopBidAdj', 'exeDate',
            'startDate', 'endDate', 'campaignID', 'campaignName',
            'trackingURL', 'customParameters', 'ctr', 'campaignType'
        ];
        
        return $this->unsetColumns($result, $unsetColumns);
    }

    public static function getAllCampaign()
    {
        $arrCampaigns = [];

        $arrCampaigns['all'] = 'All Campaigns';

        $campaigns = self::select('campaignID', 'campaignName')->where('account_id', '=', Auth::user()->account_id)->get();

        if ($campaigns) {
            foreach ($campaigns as $key => $campaign) {
                $arrCampaigns[$campaign->campaignID] = $campaign->campaignName;
            }
        }

        return $arrCampaigns;
    }
}
