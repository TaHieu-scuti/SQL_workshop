<?php

namespace App\Model;

use App\Model\AbstractAdwModel;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use DB;

class RepoAdwGeoReportCost extends AbstractAdwModel
{
    protected $table = 'repo_adw_geo_report_cost';
    public $timestamps = false;

    protected function addJoin(EloquentBuilder $builder)
    {
        parent::addJoin($builder);
        $builder->join('criteria', function (JoinClause $join) {
                $this->addCriteriaJoinConditions($join);
        });
    }

    private function addCriteriaJoinConditions(JoinClause $join)
    {
        $join->on('criteria.CriteriaID', '=', $this->table. '.region');
    }
}
