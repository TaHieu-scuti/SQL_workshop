<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;

class RepoAdwDisplayKeywordReportCost extends AbstractReportModel
{
    protected $table = "repo_adw_display_keyword_report_cost";

    /**
     * @var boolean
     **/
    public $timestamps = false;
}
