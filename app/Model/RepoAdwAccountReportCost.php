<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;

class RepoAdwAccountReportCost extends AbstractReportModel
{
    protected $table = 'repo_adw_account_report_cost';

    /**
     * @var bool
     */
    public $timestamps = false;

    // constant
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'account';
    const PAGE_ID = 'accountid';
}
