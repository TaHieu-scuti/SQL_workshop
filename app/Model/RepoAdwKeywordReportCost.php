<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;

class RepoAdwKeywordReportCost extends AbstractReportModel
{
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'keyword';
    const PAGE_ID = "keywordID";

    protected $table = "repo_adw_keywords_report_cost";

    /**
     * @var bool
     */
    public $timestamps = false;
}
