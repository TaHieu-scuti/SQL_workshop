<?php

namespace App;

use Illuminate\Support\Facades\DB;

use DateTime;
use Exception;

class RepoYssAccountReport extends AbstractReportModel
{
    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'repo_yss_account_report';

    /** @var array */
    protected $fillable = [
        'account_id',                   //  Account ID
        'campaign_id',                  //  Campaign ID of ADgainer system
        'cost',                         //  cost
        'impressions',                  //  impressions
        'clicks',                       //  clicks
        'ctr',                          //  click-through rate
        'averageCpc',                   //  average cost per click
        'averagePosition',              //  average position
        'invalidClicks',                //  Invalid clicks
        'invalidClickRate',             //  Invalid click rate
        'impressionShare',              //  Impression Share
        'exactMatchImpressionShare',    //  Exact match impression share
        'budgetLostImpressionShare',    //  Impression loss rate (budget)
        'qualityLostImpressionShare',   //  Impression loss rate (ranking)
        'trackingURL',                  //  Tracking URL
        'conversions',                  //  Conversions
        'convRate',                     //  Conversion rate
        'convValue',                    //  Conversion Value
        'costPerConv',                  //  Cost / conversions
        'valuePerConv',                 //  Value / Conv.
        'allConv',                      //  The number of all conversions
        'allConvRate',                  //  All of the conversion rate
        'allConvValue',                 //  Value of all conversions
        'costPerAllConv',               //  Cost / number of all conversions
        'valuePerAllConv',              //  Value / number of all conversions
        'network',                      //  Specify advertising system
        'device',                       //  Device
        'day',                          /*  The record of the object Date: year (year), month (monthofYear),
                                            day (day).*/
        'dayOfWeek',                    //  Day of the week
        'quarter',                      //  quarter
        'month',                        //  monthly
        'week',                         //  Every week
    ];

    /** @var array */
    private $averageFieldArray = [
        'averagePosition',
        'averageCpc',
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
        'account_id',
    ];

    // constant
    const FOREIGN_KEY_YSS_ACCOUNTS = 'account_id';

    /**
     * @param string[] $fieldNames
     * @param string   $accountStatus
     * @param string   $startDay
     * @param string   $endDay
     * @param int      $pagination
     * @param string   $columnSort
     * @param string   $sort
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
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
        $joinTableName = (new RepoYssAccount)->getTable();
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === 'accountName') {
                $arrayCalculate[] = 'accountName';
                continue;
            }
            if (in_array($fieldName, $this->averageFieldArray)) {
                $arrayCalculate[] = DB::raw('ROUND(AVG(' . $fieldName . '), 2) AS ' . $fieldName);
            } else {
                $arrayCalculate[] = DB::raw('ROUND(SUM(' . $fieldName . '), 2) AS ' . $fieldName);
            }
        }
        array_unshift($arrayCalculate, $tableName.'.account_id');
        return self::select($arrayCalculate)
                ->join(
                    $joinTableName,
                    $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
                    '=',
                    $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
                )->where(
                    function ($query) use ($startDay, $endDay) {
                        if ($startDay === $endDay) {
                            $query->whereDate('day', '=', $endDay);
                        } else {
                            $query->whereDate('day', '>=', $startDay)
                                ->whereDate('day', '<', $endDay);
                        }
                    }
                )->whereHas('repoYssAccounts', function ($query) use ($accountStatus) {
                    $query->where('accountStatus', 'like', '%'.$accountStatus);
                })
                ->with('repoYssAccounts')
                ->groupBy($tableName.'.'.self::FOREIGN_KEY_YSS_ACCOUNTS)
                ->groupBy($joinTableName.'.accountName')
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
    public function getDataForGraph($column, $accountStatus, $startDay, $endDay)
    {
        try {
            new DateTime($startDay);
            new DateTime($endDay);
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }

        return self::select(
            DB::raw('SUM('.$column.') as data'),
            DB::raw(
                'DATE(day) as day'
            )
        )
            ->join(
                'repo_yss_accounts',
                'repo_yss_account_report.account_id',
                '=',
                'repo_yss_accounts.account_id'
            )
            ->where(
                function ($query) use ($startDay, $endDay) {
                    if ($startDay === $endDay) {
                        $query->whereDate('day', '=', $endDay);
                    } else {
                        $query->whereDate('day', '>=', $startDay)
                            ->whereDate('day', '<', $endDay);
                    }
                }
            )
            ->where('repo_yss_accounts.accountStatus', 'like', '%'.$accountStatus)
            ->groupBy('day')
            ->get();
    }

    /**
     * @param string $keywords
     * @return string[]
     */
    public function getColumnLiveSearch($keywords)
    {
        /* TODO: the columns should be retrieved in a unified way,
        if it cannot be done with AbstractReportModel::getColumnNames
        we should make something that works for both cases */
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
        $unsetColumns = ['id', 'campaign_id', 'account_id', 'network',
                             'device', 'day', 'dayOfWeek', 'week', 'month', 'quarter'];
        
        return $this->unsetColumns($result, $unsetColumns);
    }

    /**
     * @param $fieldNames
     * @param $accountStatus
     * @param $startDay
     * @param $endDay
     * @return array
     */
    public function calculateData($fieldNames, $accountStatus, $startDay, $endDay)
    {
        $arrayCalculate = [];
        foreach ($fieldNames as $fieldName) {
            if ($fieldName !== 'account_id') {
                if ($fieldName === 'accountName') {
                    continue;
                }
                if (in_array($fieldName, $this->averageFieldArray)) {
                    $arrayCalculate[] = DB::raw('ROUND('.'AVG(' . $fieldName . '),2'.') AS ' . $fieldName);
                } elseif (!in_array($fieldName, $this->emptyCalculateFieldArray)) {
                    $arrayCalculate[] = DB::raw('ROUND('.'SUM(' . $fieldName . '),2'.') AS ' . $fieldName);
                }
            }
        }

        $tableName = $this->getTable();
        $joinTableName = (new RepoYssAccount)->getTable();
        if (empty($arrayCalculate)) {
            return $arrayCalculate;
        }
        return self::select($arrayCalculate)
                    ->join(
                        $joinTableName,
                        $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
                        '=',
                        $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
                    )->where( // TODO: this where condition is repeated 3 times throughout this file
                        function ($query) use ($startDay, $endDay) {
                            if ($startDay === $endDay) {
                                $query->whereDate('day', '=', $endDay);
                            } else {
                                $query->whereDate('day', '>=', $startDay)
                                    ->whereDate('day', '<', $endDay);
                            }
                        }
                    )
                    ->where($joinTableName . '.accountStatus', 'like', '%'.$accountStatus)
                   ->first()->toArray();
    }

    public function repoYssAccounts()
    {
        return $this->hasOne('App\RepoYssAccount', 'account_id', 'account_id');
    }

    public function getDataForExport(
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $columnSort,
        $sort)
    {
        $arrayCalculate = [];
        $tableName = $this->getTable();
        $joinTableName = (new RepoYssAccount)->getTable();
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === 'accountName') {
                $arrayCalculate[] = 'accountName';
                continue;
            }
            if (in_array($fieldName, $this->averageFieldArray)) {
                $arrayCalculate[] = DB::raw('ROUND(AVG(' . $fieldName . '), 2) AS ' . $fieldName);
            } else {
                $arrayCalculate[] = DB::raw('ROUND(SUM(' . $fieldName . '), 2) AS ' . $fieldName);
            }
        }
        return self::select($arrayCalculate)
                ->join(
                    $joinTableName,
                    $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
                    '=',
                    $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
                )->where(
                    function ($query) use ($startDay, $endDay) {
                        if ($startDay === $endDay) {
                            $query->whereDate('day', '=', $endDay);
                        } else {
                            $query->whereDate('day', '>=', $startDay)
                                ->whereDate('day', '<', $endDay);
                        }
                    }
                )->where($joinTableName.'.accountStatus', '=', $accountStatus)
                ->groupBy($joinTableName.'.accountName')
                ->orderBy($columnSort, $sort)
                ->get();
    }
}
