<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\AbstractReportModel;

class RepoAdwSearchQueryPerformanceReportConv extends AbstractReportModel
{
    protected $table = 'repo_adw_search_query_performance_report_conv';

    public $timestamps = false;
}
