<?php

namespace App\Model;

use App\AbstractReportModel;

class RepoAdwCampaignReportCost extends AbstractReportModel
{
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'campaign';
    const PAGE_ID = "campaignID";

    protected $table = "repo_adw_campaign_report_cost";

    /**
     * @var boolean
     **/
    public $timestamps = false;
}
