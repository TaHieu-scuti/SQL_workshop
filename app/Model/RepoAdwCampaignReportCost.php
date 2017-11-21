<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RepoAdwCampaignReportCost extends Model
{
    protected $table = "repo_adw_campaign_report_cost";

    /**
     * @var boolean
     **/
    public $timestamps = false;

    public function getAllAdwCampaign()
    {
        return self::select('campaignID', 'campaign as campaignName')
            ->where('account_id', '=', Auth::user()->account_id)
            ->get();
    }
}
