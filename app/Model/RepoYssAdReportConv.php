<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;

class RepoYssAdReportConv extends AbstractReportModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    
    protected $table = 'repo_yss_ad_report_conv';
}
