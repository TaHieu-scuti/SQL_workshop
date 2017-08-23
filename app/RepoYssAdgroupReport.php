<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepoYssAdgroupReport extends Model
{
    protected $table = 'repo_yss_adgroup_report';
    protected $fillable = [
        'account_id',    // Account ID
        'campaign_id',    // Campaign ID of ADgainer system
        'campaignID',    //
        'adgroupID',
        'campaignName',    //
        'adgroupName',    //
        'adgroupDistributionSettings',    //
        'adGroupBid',    //
        'cost',    //
        'impressions',
        'clicks',
        'ctr',
        'averageCpc',
        'averagePosition',
        'impressionShare',
        'exactMatchImpressionShare',
        'qualityLostImpressionShare',
        'trackingURL',
        'customParameters',
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
        'mobileBidAdj',
        'desktopBidAdj',
        'tabletBidAdj',
        'network',
        'device',
        'day',
        'dayOfWeek',
        'quarter',
        'month',
        'week'
    ];
}
