<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\AbstractReportModel;

use DateTime;
use Exception;
use Auth;

class RepoYssKeywordReportCost extends AbstractReportModel
{
    const FIELDS = [
        'keywordID',
        'keyword'
    ];
    const KEY_ID = "keywordID";

    const GROUPED_BY_FIELD_NAME = 'keyword';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'repo_yss_keyword_report_cost';

    /** @var array */
    private $averageFieldArray = [
        'averageCpc',
        'averagePosition'
    ];

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
        $groupedByField,
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $arrayCalculate = $this->getAggregated($fieldNames);
        $paginatedData = $this->select($arrayCalculate)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )
                ->where(
                    function ($query) use ($adgainerId, $accountId, $campaignId, $adGroupId, $keywordId) {
                        $this->addQueryConditions($query, $adgainerId, $accountId, $campaignId, $adGroupId, $keywordId);
                    }
                )
                ->groupBy($groupedByField)
                ->orderBy($columnSort, $sort);
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $paginatedData = $paginatedData->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->paginate($pagination);
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $paginatedData = $paginatedData->paginate($pagination);
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
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
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
        ->where(
            function ($query) use ($adgainerId, $accountId, $campaignId, $adGroupId, $keywordId) {
                $this->addQueryConditions($query, $adgainerId, $accountId, $campaignId, $adGroupId, $keywordId);
            }
        )
        ->groupBy('day');
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->get();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->get();
        }
        return $data;
    }

    public function calculateData(
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $groupedByField,
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $fieldNames = $this->unsetColumns($fieldNames, [$groupedByField]);
        $arrayCalculate = $this->getAggregated($fieldNames);
        if (empty($arrayCalculate)) {
            return $arrayCalculate;
        }

        $data = $this->select($arrayCalculate)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )
                ->where(
                    function ($query) use ($adgainerId, $accountId, $campaignId, $adGroupId, $keywordId) {
                        $this->addQueryConditions($query, $adgainerId, $accountId, $campaignId, $adGroupId, $keywordId);
                    }
                );
        // get aggregated value
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->first();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->first();
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
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $arrayCalculate = $this->getAggregated($fieldNames);
        $data = $this->select($arrayCalculate)
                    ->where(
                        function (Builder $query) use ($startDay, $endDay) {
                            $this->addTimeRangeCondition($startDay, $endDay, $query);
                        }
                    )
                    ->where(
                        function ($query) use ($adgainerId, $accountId, $campaignId, $adGroupId, $keywordId) {
                            $this->addQueryConditions($query, $adgainerId, $accountId, $campaignId, $adGroupId, $keywordId);
                        }
                    );
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->first();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->first();
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
            'campaignStartDate', 'campaignEndDate', 'kwEditorialStatus', 'keywordDistributionSettings',
            'mobileBidAdj', 'tabletBidAdj', 'desktopBidAdj', 'exeDate',
            'startDate', 'endDate', 'campaignID', 'campaignName',
            'trackingURL', 'customParameters', 'ctr', 'campaignType', 'adgroupID', 'keywordID',
            'adgroupName', 'customURL', 'adGroupBid', 'bid',
            'negativeKeywords', 'qualityIndex', 'firstPageBidEstimate', 'keywordMatchType',
            'topOfPageBidEstimate', 'landingPageURL', 'landingPageURLSmartphone'
        ];

        return $this->unsetColumns($result, $unsetColumns);
    }

    public function getAllKeyword($accountId = null, $campaignId = null, $adgroupId = null, $keywordId = null)
    {
        $arrKeywords = [];

        $arrKeywords['all'] = 'All Keywords';

        $keywords = self::select('keywordID', 'keyword')->where(
            function ($query) use ($accountId, $campaignId, $adgroupId, $keywordId) {
                self::addQueryConditions($query, Auth::user()->account_id, $accountId, $campaignId, $adgroupId, $keywordId);
            }
        )->get();

        if ($keywords) {
            foreach ($keywords as $key => $keyword) {
                $arrKeywords[$keyword->keywordID] = $keyword->keyword;
            }
        }

        return $arrKeywords;
    }
}
