<?php

namespace App\Model;

use App\Model\AbstractAdwModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoAdwCampaignDayOfWeek extends AbstractAdwModel
{
    protected $table = 'repo_adw_campaign_report_cost';

    public $timestamps = false;

    private function addJoin(EloquentBuilder $builder)
    {
        $builder->leftJoin(
            'phone_time_use',
            function (JoinClause $join) {
                $this->addJoinConditions($join);
            }
        );
    }

    protected function addJoinConditions(JoinClause $join)
    {
        $join->on('phone_time_use.account_id', '=', $this->table . '.account_id')
            ->on('phone_time_use.campaign_id', '=', $this->table . '.campaign_id')
            ->on('phone_time_use.utm_campaign', '=', $this->table . '.campaignID')
            ->on(
                DB::raw("DAYNAME(`phone_time_use`.`time_of_call`)"),
                '=',
                $this->table . '.dayOfWeek'
            )
            ->where('phone_time_use.source', '=', 'adw')
            ->where('phone_time_use.traffic_type', '=', 'AD');
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
