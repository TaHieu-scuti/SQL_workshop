<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\AbstractReportModel;

class RepoAdwCampaignReportCost extends AbstractReportModel
{
    protected $table = "repo_adw_campaign_report_cost";

    /**
     * @var boolean
     **/
    public $timestamps = false;

    public function getAllAdwCampaign(
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        return self::select('campaignID', 'campaign as campaignName')
            ->where(
                function ($query) use ($accountId, $campaignId, $adGroupId, $adReportId, $keywordId) {
                    $this->addQueryConditions(
                        $query,
                        Auth::user()->account_id,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    );
                }
            )
            ->get();
    }
}
