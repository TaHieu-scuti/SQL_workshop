<?php

namespace App\Model;

use App\AbstractReportModel;

class RepoAdwAdReportCost extends AbstractReportModel
{
    const GROUPED_BY_FIELD_NAME = 'ad';
    const PAGE_ID = 'adID';
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaign',
            'tableJoin' => 'repo_adw_ad_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ],
        [
            'columnName' => 'adGroup',
            'tableJoin' => 'repo_adw_ad_report_cost',
            'columnId' => 'adGroupID',
            'aliasId' => 'adgroupID',
            'aliasName' => 'adgroupName',
        ]
    ];

    const FIELDS = [
        'displayURL',
        'description'
    ];

    protected $table = "repo_adw_ad_report_cost";

    /**
     * @var bool
     */
    public $timestamps = false;
}
