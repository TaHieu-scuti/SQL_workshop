<?php

namespace App\Model;

use App\AbstractReportModel;

class RepoYssPrefectureReportCost extends AbstractReportModel
{
    const GROUPED_BY_FIELD_NAME = 'prefecture';

    protected $table = 'repo_yss_prefecture_report_cost';

    /**
     * @var bool
     */
    public $timestamps = false;
}
