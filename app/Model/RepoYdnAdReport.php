<?php

namespace App\Model;

use App\AbstractReportModel;
use Auth;

class RepoYdnAdReport extends AbstractReportModel
{
    const GROUPED_BY_FIELD_NAME = 'adName';
    const PAGE_ID = 'adID';
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaignName',
            'tableJoin' => 'repo_ydn_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ],
        [
            'columnName' => 'adgroupName',
            'tableJoin' => 'repo_ydn_report_cost',
            'columnId' => 'adgroupID',
            'aliasId' => 'adgroupID',
            'aliasName' => 'adgroupName',
        ]
    ];

    protected $table = 'repo_ydn_reports';
    public $timestamps = false;
}
