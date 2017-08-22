<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class RepoYssAccountReport extends AbstractReportModel
{
    public $timestamps = false;
    protected $table = 'repo_yss_account_report';
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
    public function getDataForTable($fieldName, $acccountStatus, $startDay, $endDay, $pagination, $columnSort, $sort)
    {
        //unset column 'account_id' ( need to be more specific about table name )
        if (($key = array_search('account_id', $fieldName)) !== false) {
            unset($fieldName[$key]);
        }

        $query = self::select($fieldName)
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
                    ->where('repo_yss_accounts.accountStatus', 'like', '%'.$acccountStatus)
                    ->orderBy($columnSort, $sort);
        return $query->addSelect('repo_yss_account_report.account_id')->paginate($pagination);
    }

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
}
