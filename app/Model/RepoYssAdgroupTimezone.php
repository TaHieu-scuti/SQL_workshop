<?php

namespace App\Model;

use App\Model\AbstractYssReportModel;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoYssAdgroupTimezone extends AbstractYssReportModel
{
    protected $table = 'repo_yss_adgroup_report_cost';

    public $timestamps = false;

    protected function addJoinConditions(JoinClause $join)
    {
        parent::addJoinConditions($join);
        $join->on(
            DB::raw("HOUR(`phone_time_use`.`time_of_call`)"),
            '=',
            $this->table . '.hourofday'
        );
    }
}
