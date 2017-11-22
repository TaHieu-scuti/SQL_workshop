<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;

class RepoAdwAdgroupReportCost extends AbstractReportModel
{
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'adGroup';
    const PAGE_ID = "adgroupID";

    protected $table = 'repo_adw_adgroup_report_cost';
    public $timestamps = false;
}
