<?php

namespace App\Model;

use App\AbstractReportModel;

class RepoYssKeywordReportConv extends AbstractReportModel
{
    /**
     * @var bool
     */
    public $timestamps = false;

    protected $table = 'repo_yss_keyword_report_conv';
}
