<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;

class RepoYssPrefectureReportConv extends AbstractReportModel
{
    protected $table = 'repo_yss_prefecture_report_conv';

    /**
     * @var bool
     */
    public $timestamps = false;
}
