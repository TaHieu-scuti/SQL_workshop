<?php

namespace App\Model;

use Auth;
use App\AbstractReportModel;

class RepoAdwCampaignReportCost extends AbstractReportModel
{
    const GROUPED_BY_FIELD_NAME = 'campaign';
    const PAGE_ID = "campaignID";

    protected $table = "repo_adw_campaign_report_cost";

    /**
     * @var boolean
     **/
    public $timestamps = false;

    public function getAllAdwCampaign(
        $accountId = null
    ) {
        return self::select('campaignID', 'campaign as campaignName')
            ->where(
                function ($query) use ($accountId) {
                    $this->addQueryConditions(
                        $query,
                        Auth::user()->account_id,
                        $accountId
                    );
                }
            )
            ->groupBy('campaignID', 'campaignName')->get();
    }
}
