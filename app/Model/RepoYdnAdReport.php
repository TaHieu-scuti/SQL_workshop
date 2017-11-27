<?php

namespace App\Model;

use App\AbstractReportModel;
use Auth;

class RepoYdnAdReport extends AbstractReportModel
{
    const GROUPED_BY_FIELD_NAME = 'adName';
    const PAGE_ID = 'adID';

    protected $table = 'repo_ydn_reports';
    public $timestamps = false;
}
