<?php

namespace App\Model;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

use App\Model\AbstractYdnReportModel;

class RepoYdnDayOfWeek extends AbstractYdnReportModel
{
    protected $table = 'repo_ydn_reports';

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
