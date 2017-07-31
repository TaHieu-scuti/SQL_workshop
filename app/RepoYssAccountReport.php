<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;

class RepoYssAccountReport extends Model
{
    public $timestamps = false;
    protected $table = 'repo_yss_account_report';
    protected $fillable = [
        'account_id',    //                 Account ID
        'campaign_id',    //                Campaign ID of ADgainer system
        'cost',    //                       cost
        'impressions',    //                impressions
        'clicks',    //                     clicks
        'ctr',    //                        click-through rate
        'averageCpc',    //                 average cost per click
        'averagePosition',    //            average position
        'invalidClicks',    //              Invalid clicks
        'invalidClickRate',    //           Invalid click rate
        'impressionShare',     //           Impression Share
        'exactMatchImpressionShare',    //  Exact match impression share
        'budgetLostImpressionShare',    //  Impression loss rate (budget)
        'qualityLostImpressionShare',    // Impression loss rate (ranking)
        'trackingURL',    //                Tracking URL
        'conversions',    //                Conversions
        'convRate',    //                   Conversion rate
        'convValue',    //                  Conversion Value
        'costPerConv',    //                Cost / conversions
        'valuePerConv',    //               Value / Conv.
        'allConv',    //                    The number of all conversions
        'allConvRate',    //                All of the conversion rate
        'allConvValue',    //               Value of all conversions
        'costPerAllConv',    //             Cost / number of all conversions
        'valuePerAllConv',    //            Value / number of all conversions
        'network',    //                    Specify advertising system
        'device',    //                     device
        'day',    /*                        The record of the object Date: year (year), month (monthofYear), 
                                            day (day).*/
        'dayOfWeek',    //                  Day of the week
        'quarter',    //                    quarter
        'month',    //                      monthly
        'week',    //                       Every week
    ];

    public function getAllData()
    {
        return self::all();
    }

    public function getDataByFilter($fieldName, $resultPerPage)
    {
        return self::paginate($resultPerPage, $fieldName);
    }

    public function getColumnNames()
    {
        $columns = Schema::getColumnListing($this->getTable());
        // unset "id" and "campaign_id" from array cause we dont need it for filter
        if(($key = array_search('id', $columns)) !== false) {
            unset($columns[$key]);
        }

        if(($key = array_search('campaign_id', $columns)) !== false) {
            unset($columns[$key]);
        }

        return $columns;
    }
}
