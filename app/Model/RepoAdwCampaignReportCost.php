<?php

namespace App\Model;

use Auth;
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

    public function getAllAdwCampaign(
        $accountId = null
    ) {
        $engine = session(static::SESSION_KEY_ENGINE);
        return self::select('campaignID', 'campaign as campaignName')
            ->where(
                function ($query) use ($accountId, $engine) {
                    $this->addQueryConditions(
                        $query,
                        Auth::user()->account_id,
                        $engine,
                        $accountId
                    );
                }
            )
            ->groupBy('campaignID', 'campaignName')->get();
    }
}
