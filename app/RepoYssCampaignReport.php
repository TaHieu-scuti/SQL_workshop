<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepoYssCampaignReport extends Model
{
    protected $table = 'repo_yss_campaign_report';
    protected $fillable = [
        'account_id',
        'campaign_id',
        'campaignID',
        'campaignName',
        'campaignDistributionSettings',
        'campaignDistributionStatus',
        'dailySpendingLimit',
        'campaignStartDate',
        'campaignEndDate',
        'cost',
        'impressions',
        'clicks',
        'ctr',
        'averageCpc',
        'averagePosition',
        'invalidClicks',
        'invalidClickRate',
        'impressionShare',
        'exactMatchImpressionShare',
        'budgetLostImpressionShare',
        'qualityLostImpressionShare',
        'trackingURL',
        'customParameters',
        'campaignTrackingID',
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
        'week',
        'campaignType',
    ];
}
