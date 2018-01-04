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
        $builder->join('criteria',
            function (JoinClause $join) {
                $this->addCriteriaJoinConditions($join);
            }
        );
    }

    protected function addJoinConditions(JoinClause $join)
    {
        $join->on('phone_time_use.account_id', '=', $this->table . '.account_id')
            ->on('phone_time_use.campaign_id', '=', $this->table . '.campaign_id')
            ->on('phone_time_use.utm_campaign', '=', $this->table . '.campaignID')
            ->where('phone_time_use.source', '=', 'adw')
            ->where('phone_time_use.traffic_type', '=', 'AD')
            ->where('phone_time_use.visitor_city_state', 'like',
                DB::raw("CONCAT('%', 'criteria.Name', ' (Japan)')"));
    }

    private function addCriteriaJoinConditions(JoinClause $join)
    {
        $join->on('criteria.CriteriaID', '=', $this->table. '.region');
    }
}
