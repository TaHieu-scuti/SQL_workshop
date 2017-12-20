<?php

namespace App\Model;

use App\AbstractReportModel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

abstract class AbstractAccountReportModel extends AbstractReportModel
{
    abstract protected function getPhoneTimeUseSourceValue();

    protected function addJoinOnPhoneTimeUse(Builder $builder)
    {
        $builder->leftJoin(
            'phone_time_use',
            function (JoinClause $join) {
                $join->on(function (Builder $builder) {
                    $builder->where(
                        $this->getTable() . '.account_id',
                        '=',
                        'phone_time_use.account_id'
                    )->where(
                        $this->getTable() . '.campaign_id',
                        '=',
                        'phone_time_use.campaign_id'
                    )->where(
                        $this->getTable() . '.day',
                        '=',
                        DB::raw("STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d')")
                    )->where(
                        'phone_time_use.source',
                        '=',
                        $this->getPhoneTimeUseSourceValue()
                    )->where(
                        'phone_time_use.traffic_type',
                        '=',
                        'AD'
                    );
                });
            }
        );
    }
}
