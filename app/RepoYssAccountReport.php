<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepoYssAccountReport extends Model
{
    protected $table = 'repo_yss_account_report';
    protected $fillable = [
        'account_id',    // Account ID
        'campaign_id',    // Campaign ID of ADgainer system
        'cost',    // cost
        'impressions',    // impressions
        'clicks',    // clicks
        'ctr',    // click-through rate
        'averageCpc',    // average cost per click
        'averagePosition',    // average position
        'invalidClicks',
        'invalidClickRate',
        'impressionShare',
        'exactMatchImpressionShare',
        'budgetLostImpressionShare',
        'qualityLostImpressionShare',
        'trackingURL',
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
