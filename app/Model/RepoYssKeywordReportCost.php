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

    protected $table = 'repo_yss_keyword_report_cost';

    /**
     * @param string $column
     * @param string $accountStatus
     * @param string $startDay
     * @param string $endDay
     * @return \Illuminate\Support\Collection
     */
    public function getDataForGraph(
        $column,
        $accountStatus,
        $startDay,
        $endDay
    ) {
    }
}
