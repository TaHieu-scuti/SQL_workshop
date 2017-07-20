<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepoYssDayOfWeekReport extends Model
{
    protected $table = 'repo_yss_dayofweek_report';
    protected $fillable = [
        'account_id',
        'campaign_id',
        'campaignID',
        'campaignName',
        'cost',
        'impressions',
        'clicks',
        'ctr',
        'averageCpc',
        'averagePosition',
        'bidAdjustment',
        'targetScheduleID',
        'targetSchedule',
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
        'day',
        'quarter',
        'month',
        'week',
    ];
}
