<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\AbstractReportModel;

use DateTime;
use Exception;
use Auth;

class RepoYssCampaignReportCost extends AbstractReportModel
{
    // constant
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'campaignName';
    const PAGE_ID = 'campaignID';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'repo_yss_campaign_report_cost';

    public function getAllCampaign(
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $arrCampaigns = [];

        $arrCampaigns['all'] = 'All Campaigns';
        if (session('engine') === 'yss') {
            $campaigns = self::select('campaignID', 'campaignName')
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
        } elseif (session('engine') === 'adw') {
            $modelAdwCampaign = new RepoAdwCampaignReportCost();
            $campaigns = $modelAdwCampaign->getAllAdwCampaign(
                $accountId = null,
                $campaignId = null,
                $adGroupId = null,
                $adReportId = null,
                $keywordId = null
            );
        }
        if ($campaigns) {
            foreach ($campaigns as $key => $campaign) {
                $arrCampaigns[$campaign->campaignID] = $campaign->campaignName;
            }
        }

        return $arrCampaigns;
    }
}
