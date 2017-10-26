<?php

namespace App\Model;

use App\AbstractReportModel;

class RepoYssKeywordReportCost extends AbstractReportModel
{
    const FIELDS = [
        'keywordID',
        'keyword'
    ];

    const GROUPED_BY_FIELD_NAME = 'keywordID';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'repo_yss_keyword_report_cost';
}
