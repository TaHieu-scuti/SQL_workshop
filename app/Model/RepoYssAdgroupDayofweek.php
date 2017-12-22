<?php

namespace App\Model;

use App\Model\AbstractYssReportModel;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoYssAdgroupDayofweek extends AbstractYssReportModel
{
    protected $table = 'repo_yss_adgroup_report_cost';

    public $timestamps = false;

    protected function addJoinConditions(JoinClause $join)
    {
        parent::addJoinConditions($join);
        $join->on(
            DB::raw("DAYNAME(`phone_time_use`.`time_of_call`)"),
            '=',
            DB::raw("DAYNAME(`" . $this->table . "`" . ".`day`)")
        );
    }
}
