<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepoYssAdReport extends Model
{
    protected $table = 'repo_yss_ad_report';
    protected $fillable = [
        'account_id',    // Account ID
        'campaign_id',    // Campaign ID of ADgainer system
        'campaignID',    //
        'adgroupID',
        'adID',    //
        'campaignName',    //
        'adGroupName',    //
        'adName',    //
        'title',    //
        'description1',
        'displayURL',
        'destinationURL',
        'adType',
        'adDistributionSettings',
        'adEditorialStatus',
        'cost',
        'impressions',
        'clicks',
        'ctr',
        'averageCpc',
        'averagePosition',
        'description2',
        'focusDevice',
        'trackingURL',
        'customParameters',
        'landingPageURL',
        'landingPageURLSmartphone',
        'adTrackingID',
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
        'network',
        'clickType',
        'device',
        'day',
        'dayOfWeek',
        'quarter',
        'month',
        'week',
        'adKeywordID',
        'title1',
        'title2',
        'description',
        'directory1',
        'directory2',
    ];
}
