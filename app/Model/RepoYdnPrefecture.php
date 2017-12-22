<?php

namespace App\Model;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

use App\Model\AbstractYdnReportModel;

class RepoYdnPrefecture extends AbstractYdnReportModel
{
    protected $table = 'repo_ydn_reports';

    public $timestamps = false;

    protected function addJoinConditions(JoinClause $join)
    {
        parent::addJoinConditions($join);
    }
}
