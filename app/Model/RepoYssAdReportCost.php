<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;

use App\AbstractReportModel;

use DateTime;
use Exception;

class RepoYssAdReportCost extends AbstractReportModel
{
    // constant
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'adName';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'repo_yss_ad_report_cost';

    /** @var array */
    private $averageFieldArray = [
        'averageCpc',
        'averagePosition',
        'conversions',
        'convRate',
        'convValue',
        'costPerConv',
        'valuePerConv',
        'allConv',
        'allConvRate',
        'allConvValue',
        'costPerAllConv',
        'valuePerAllConv',
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

    private function getAggregated(array $fieldNames, $tableName)
    {
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
        $sort
    ) {
        $arrayCalculate = [];
        $tableName = $this->getTable();
        $arrayCalculate = $this->getAggregated($fieldNames, $tableName);
        return self::select($arrayCalculate)
                ->where(
                    function ($query) use ($startDay, $endDay) {
                        if ($startDay === $endDay) {
                            $query->whereDate('day', '=', $endDay);
                        } else {
                            $query->whereDate('day', '>=', $startDay)
                                ->whereDate('day', '<=', $endDay);
                        }
                    }
                )
                ->groupBy(self::GROUPED_BY_FIELD_NAME)
                ->orderBy($columnSort, $sort)
                ->paginate($pagination);
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
        $endDay
    ) {
        try {
            new DateTime($startDay); //NOSONAR
            new DateTime($endDay); //NOSONAR
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }

        return self::select(
            DB::raw('SUM('.$column.') as data'),
            DB::raw(
                'DATE(day) as day'
            )
        )
        ->where(
            function ($query) use ($startDay, $endDay) {
                if ($startDay === $endDay) {
                    $query->whereDate('day', '=', $endDay);
                } else {
                    $query->whereDate('day', '>=', $startDay)
                        ->whereDate('day', '<=', $endDay);
                }
            }
        )
        ->groupBy('day')
        ->get();
    }

    public function calculateData($fieldNames, $accountStatus, $startDay, $endDay)
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

        return self::select($arrayCalculate)
                ->where(
                    function ($query) use ($startDay, $endDay) {
                        if ($startDay === $endDay) {
                            $query->whereDate('day', '=', $endDay);
                        } else {
                            $query->whereDate('day', '>=', $startDay)
                                ->whereDate('day', '<=', $endDay);
                        }
                    }
                )
                ->first()->toArray();
    }

    public function calculateSummaryData($fieldNames, $accountStatus, $startDay, $endDay)
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
        $data = self::select($arrayCalculate)
                    ->where(
                        function ($query) use ($startDay, $endDay) {
                            if ($startDay === $endDay) {
                                $query->whereDate('day', '=', $endDay);
                            } else {
                                $query->whereDate('day', '>=', $startDay)
                                    ->whereDate('day', '<=', $endDay);
                            }
                        }
                    )
                    ->first()->toArray();
        foreach ($data as $key => $value) {
            if ($value === null) {
                $data[$key] = 0;
            }
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
        $arrayCalculate = [];
        $tableName = $this->getTable();
        $arrayCalculate = $this->getAggregated($fieldNames, $tableName);
        return self::select($arrayCalculate)
                ->where(
                    function ($query) use ($startDay, $endDay) {
                        if ($startDay === $endDay) {
                            $query->whereDate('day', '=', $endDay);
                        } else {
                            $query->whereDate('day', '>=', $startDay)
                                ->whereDate('day', '<=', $endDay);
                        }
                    }
                )
                ->groupBy(self::GROUPED_BY_FIELD_NAME)
                ->orderBy($columnSort, $sort)
                ->get();
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
            'exeDate', 'startDate', 'endDate', 'account_id',
            'campaign_id', 'campaignID', 'adgroupID', 'adID',
            'campaignName', 'adgroupName', 'adName', 'title',
            'description1', 'displayURL', 'destinationURL', 'adType',
            'adDistributionSettings', 'adEditorialStatus', 'description2',
            'focusDevice', 'trackingURL', 'customParameters', 'landingPageURL',
            'landingPageURLSmartphone', 'network', 'clickType', 'device',
            'day', 'dayOfWeek', 'quarter', 'month',
            'week', 'title1', 'title2', 'description',
            'directory1', 'directory2', 'adKeywordID', 'adTrackingID',
        ];
        
        return $this->unsetColumns($result, $unsetColumns);
    }
}
