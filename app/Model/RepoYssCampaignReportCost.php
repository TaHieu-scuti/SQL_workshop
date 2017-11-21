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

    public static function getAllCampaign()
    {
        $modelAdwCampaign = new RepoAdwCampaignReportCost();
        $arrCampaigns = [];
//        dd(session('engine'));
        $arrCampaigns['all'] = 'All Campaigns';
        if (session('engine') === 'yss') {
            $campaigns = self::select('campaignID', 'campaignName')
                ->where('account_id', '=', Auth::user()->account_id)
                ->get();
        } elseif (session('engine') === 'adw') {
            $campaigns = $modelAdwCampaign->getAllAdwCampaign();
        }
        if ($campaigns) {
            foreach ($campaigns as $key => $campaign) {
                $arrCampaigns[$campaign->campaignID] = $campaign->campaignName;
            }
        }

        return $arrCampaigns;
    }
}
