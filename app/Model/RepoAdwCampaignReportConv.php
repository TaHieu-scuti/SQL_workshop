<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Model\RepoAdwCampaignReportConv;
use App\Model\RepoAdwCampaignReportCost;

class RepoAdwCampaignReportConv extends Model
{
    protected $table = 'repo_adw_campaign_report_conv';

    public $timestamps = false;
}
