<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepoYssPrefectureReport extends Model
{
    protected $table = 'repo_yss_accounts';
    protected $fillable = [
        'account_id',
        'campaign_id',
        'campaignID',
        'adgroupID',
        'campaignName',
        'adgroupName',
        'cost',
        'impressions',
        'clicks',
        'ctr',
        'averageCpc',
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
        'countryTerritory',
        'prefecture',
        'city',
        'cityWardDistrict',
    ];
}
