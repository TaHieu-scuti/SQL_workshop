<?php

namespace App;

use DB;

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
        //unset column 'account_id' ( need to be more specific about table name )
        if (($key = array_search('account_id', $fieldNames)) !== false) {
            unset($fieldNames[$key]);
        }

        $query = self::select($fieldNames)
                    ->join(
                        'repo_yss_accounts',
                        'repo_yss_account_report.account_id',
                        '=',
                        'repo_yss_accounts.account_id'
                    )->where(
                        function ($query) use ($startDay, $endDay) {
                            if ($startDay === $endDay) {
                                $query->whereDate('day', '=', $endDay);
                            } else {
                                $query->whereDate('day', '>=', $endDay)
                                    ->whereDate('day', '<', $startDay);
                            }
                        }
                    )
                    ->where('repo_yss_accounts.accountStatus', 'like', '%'.$accountStatus)
                    ->orderBy($columnSort, $sort);

        return $query->addSelect('repo_yss_account_report.account_id')->paginate($pagination);
    }

    /**
     * @param string[] $columnsLiveSearch
     * @param string[] $names
     * @return string[]
     */
    public function unsetColumns($columnsLiveSearch, array $names)
    {
        foreach ($names as $name) {
            if (($key = array_search($name, $columnsLiveSearch)) !== false) {
                unset($columnsLiveSearch[$key]);
            }
        }
        return $columnsLiveSearch;
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
                        $query->whereDate('day', '>=', $endDay)
                            ->whereDate('day', '<', $startDay);
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

        $result = array();
        foreach ($searchColumns as $searchColumn) {
            foreach ($searchColumn as $value) {
                array_push($result, $value);
            }
        }
        // remove column id, campaign_id ....
        $unsetColumns = array('id', 'campaign_id', 'account_id', 'network',
                             'device', 'day', 'dayOfWeek', 'week', 'month', 'quarter');
        
        return $this->unsetColumns($result, $unsetColumns);
    }
}
