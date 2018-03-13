<?php

namespace App\Model;

use App\AbstractReportModel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

abstract class AbstractAccountReportModel extends AbstractTemporaryAccountModel
{
    abstract protected function getPhoneTimeUseSourceValue();

    protected function addJoinOnPhoneTimeUse(Builder $builder)
    {
        $builder->leftJoin(
            'phone_time_use',
            function (JoinClause $join) {
                $join->on(function (JoinClause $join) {
                    $join->where(
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

    protected function getAggregatedAgency(array $fieldNames)
    {
        $expressions = parent::getAggregatedAgency($fieldNames);

        for ($i = count($expressions); $i < count($fieldNames); $i++) {
            switch ($fieldNames[$i]) {
                case 'call_cv':
                    $expressions[] = DB::raw('COUNT(`phone_time_use`.`id`) AS call_cv');
                    break;

                case 'call_cvr':
                    $expressions[] = DB::raw(
                        '(COUNT(`phone_time_use`.`id`) / SUM('
                        . $this->getTable()
                        . '.clicks)) * 100 AS call_cvr'
                    );
                    break;

                case 'call_cpa':
                    $expressions[] = DB::raw(
                        'SUM('
                        . $this->getTable()
                        . '.cost) / COUNT(`phone_time_use`.`id`) AS call_cpa'
                    );
                    break;

                case 'web_cv':
                    $expressions[] = DB::raw('SUM('
                        . $this->getTable()
                        . '.conversions) AS web_cv');
                    break;

                case 'web_cvr':
                    $expressions[] = DB::raw(
                        '(SUM('
                        . $this->getTable()
                        . '.conversions) / SUM('
                        . $this->getTable()
                        . '.clicks)) * 100 AS web_cvr'
                    );
                    break;

                case 'web_cpa':
                    $expressions[] = DB::raw(
                        'SUM('
                        . $this->getTable()
                        . '.cost) / SUM('
                        . $this->getTable()
                        . '.conversions) AS web_cpa'
                    );
                    break;

                case 'total_cv':
                    $expressions[] = DB::raw(
                        'SUM('
                        . $this->getTable()
                        . '.conversions) + COUNT(`phone_time_use`.`id`) AS total_cv'
                    );
                    break;

                case 'total_cvr':
                    $expressions[] = DB::raw(
                        '(SUM('
                        . $this->getTable()
                        . '.conversions) + COUNT(`phone_time_use`.`id`)) / SUM('
                        . $this->getTable()
                        . '.clicks) '
                        . 'AS total_cvr'
                    );
                    break;

                case 'total_cpa':
                    $expressions[] = DB::raw(
                        'SUM('
                        . $this->getTable()
                        . '.cost) / (SUM('
                        . $this->getTable()
                        . '.conversions) + COUNT(`phone_time_use`.`id`)) AS total_cpa'
                    );
                    break;
            }
        }

        return $expressions;
    }
}
