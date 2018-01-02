<?php

namespace App\Model;

use App\AbstractReportModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

abstract class AbstractAdwModel extends AbstractReportModel
{
    protected function getAggregated(array $fieldNames, array $higherLayerSelections = null)
    {
        $expressions = parent::getAggregated($fieldNames, $higherLayerSelections);
        foreach ($fieldNames as $fieldName) {
            switch ($fieldName) {
                case 'call_cv':
                    $expressions[] = DB::raw("COUNT(`phone_time_use`.`id`) as call_cv");
                    break;
                case 'call_cvr':
                    $expressions[] = DB::raw("(COUNT(`phone_time_use`.`id`) /
                    SUM(`{$this->table}`.`clicks`)) * 100 as call_cvr");
                    break;
                case 'call_cpa':
                    $expressions[] = DB::raw("IFNULL(SUM(`{$this->table}`.`cost`) /
                    COUNT(`phone_time_use`.`id`), 0) as call_cpa");
                    break;
                case 'web_cv':
                    $expressions[] = DB::raw("SUM(`{$this->table}`.`conversions`) as web_cv");
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw("(SUM(`{$this->table}`.`conversions`) /
                    SUM(`{$this->table}`.`clicks`)) * 100 as web_cvr");
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw("SUM(`{$this->table}`.`cost`) /
                    SUM(`{$this->table}`.`conversions`) as web_cpa");
                    break;
                case 'total_cv':
                    $expressions[] = DB::raw("SUM(`{$this->table}`.`conversions`) +
                    COUNT(`phone_time_use`.`id`) as total_cv");
                    break;
                case 'total_cvr':
                    $expressions[] = DB::raw("
                    ((COUNT(`phone_time_use`.`id`) / SUM(`{$this->table}`.`clicks`)) * 100
                    +
                    (SUM(`{$this->table}`.`conversions`) / SUM(`{$this->table}`.`clicks`)) * 100)
                    / 2 as total_cvr");
                    break;
                case 'total_cpa':
                    $expressions[] = DB::raw("
                    IFNULL(SUM(`{$this->table}`.`cost`) / COUNT(`phone_time_use`.`id`), 0)
                    +
                    IFNULL(SUM(`{$this->table}`.`cost`) / SUM(`{$this->table}`.`conversions`), 0) as total_cpa");
                    break;
            }
        }
        return $expressions;
    }

    protected function addJoin(EloquentBuilder $builder)
    {
        $builder->leftJoin(
            'phone_time_use',
            function (JoinClause $join) {
                $this->addJoinConditions($join);
            }
        );
    }

    protected function getBuilderForGetDataForTable(
        $engine,
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $columnSort,
        $sort,
        $groupedByField,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $builder = parent::getBuilderForGetDataForTable(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $columnSort,
            $sort,
            $groupedByField,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );

        $this->addJoin($builder);

        return $builder;
    }

    protected function getBuilderForCalculateData(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $groupedByField,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $builder = parent::getBuilderForCalculateData(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $groupedByField,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );

        $this->addJoin($builder);

        return $builder;
    }
}
