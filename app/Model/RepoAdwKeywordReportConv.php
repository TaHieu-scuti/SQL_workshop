<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\AbstractReportModel;

class RepoAdwKeywordReportConv extends AbstractReportModel
{
    protected $table = 'repo_adw_keywords_report_conv';

    public $timestamps = false;
}
