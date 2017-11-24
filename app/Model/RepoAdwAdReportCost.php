<?php

namespace App\Model;

use App\AbstractReportModel;

class RepoAdwAdReportCost extends AbstractReportModel
{
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'ad';
    const PAGE_ID = 'adID';

    protected $table = "repo_adw_ad_report_cost";

    /**
     * @var bool
     */
    public $timestamps = false;
}
