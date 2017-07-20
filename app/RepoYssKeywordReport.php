<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepoYssKeywordReport extends Model
{
    protected $table = 'repo_yss_keyword_report';
    protected $fillable = [
        'account_id',
        'campaign_id',
        'campaignID',
        'adgroupID',
        'keywordID',
        'campaignName',
        'adgroupName',
        'customURL',
        'keyword',
        'keywordDistributionSettings',
        'kwEditorialStatus',
        'adGroupBid',
        'bid',
        'negativeKeywords',
        'qualityIndex',
        'firstPageBidEstimate',
        'keywordMatchType',
        'cost',
        'impressions',
        'clicks',
        'ctr',
        'averageCpc',
        'averagePosition',
        'impressionShare',
        'exactMatchImpressionShare',
        'qualityLostImpressionShare',
        'topOfPageBidEstimate',
        'trackingURL',
        'customParameters',
        'landingPageURL',
        'landingPageURLSmartphone',
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
        'device',
        'day',
        'dayOfWeek',
        'quarter',
        'month',
        'week',
    ];
}
