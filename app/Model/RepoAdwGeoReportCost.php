<?php

namespace App\Model;

use App\AbstractReportModel;

class RepoAdwGeoReportCost extends AbstractReportModel
{
    protected $table = 'repo_adw_geo_report_cost';
    public $timestamps = false;

    public function getDataForTable(
        $engine,
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $pagination,
        $columnSort,
        $sort,
        $groupedByField,
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        
    }
}
