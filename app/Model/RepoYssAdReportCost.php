<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use App\AbstractReportModel;

use DateTime;
use Exception;
use Auth;

class RepoYssAdReportCost extends AbstractReportModel
{
    // constant
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'adName';
    const PAGE_ID = 'adId';
    const ALL_HIGHER_LAYERS =
    [
        [
            'name' => 'campaignName',
            'table' => 'repo_yss_campaign_report_cost',
            'id' => 'campaignID',
            'alias' => 'campaignID'
        ],
        [
            'name' => 'adgroupName',
            'table' => 'repo_yss_adgroup_report_cost',
            'id' => 'adgroupID',
            'alias' => 'adgroupID'
        ]
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'repo_yss_ad_report_cost';
}
