<?php

namespace App\Model;

use App\AbstractReportModel;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoYdnAdReport extends AbstractReportModel
{
    const GROUPED_BY_FIELD_NAME = 'adName';
    const PAGE_ID = 'adID';
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaignName',
            'tableJoin' => 'repo_ydn_reports',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ],
        [
            'columnName' => 'adgroupName',
            'tableJoin' => 'repo_ydn_reports',
            'columnId' => 'adgroupID',
            'aliasId' => 'adgroupID',
            'aliasName' => 'adgroupName',
        ]
    ];

    const FIELDS = [
        'displayURL',
        'description1'
    ];

    protected $table = 'repo_ydn_reports';
    public $timestamps = false;

    private function addJoinCondition(EloquentBuilder $builder)
    {
        $builder->leftJoin(
            DB::raw('(`phone_time_use`, `campaigns`)'),
            function (JoinClause $join) {
                $join->on('campaigns.account_id', '=', $this->table . '.account_id')
                    ->on('campaigns.campaign_id', '=', $this->table . '.campaign_id')
                    ->on(
                        function (Builder $builder) {
                            $builder->where(
                                function (Builder $builder) {
                                    $builder->whereRaw('campaigns.camp_custom1 = "creative"')
                                        ->whereRaw("phone_time_use.custom1 = {$this->table}.adID");
                                }
                            )->orWhere(
                                function (Builder $builder) {
                                    $builder->whereRaw('campaigns.camp_custom2 = "creative"')
                                        ->whereRaw("phone_time_use.custom2 = {$this->table}.adID");
                                }
                            )->orWhere(
                                function (Builder $builder) {
                                    $builder->whereRaw('campaigns.camp_custom3 = "creative"')
                                        ->whereRaw("phone_time_use.custom3 = {$this->table}.adID");
                                }
                            )->orWhere(
                                function (Builder $builder) {
                                    $builder->whereRaw('campaigns.camp_custom4 = "creative"')
                                        ->whereRaw("phone_time_use.custom4 = {$this->table}.adID");
                                }
                            )->orWhere(
                                function (Builder $builder) {
                                    $builder->whereRaw('campaigns.camp_custom5 = "creative"')
                                        ->whereRaw("phone_time_use.custom5 = {$this->table}.adID");
                                }
                            )->orWhere(
                                function (Builder $builder) {
                                    $builder->whereRaw('campaigns.camp_custom6 = "creative"')
                                        ->whereRaw("phone_time_use.custom6 = {$this->table}.adID");
                                }
                            )->orWhere(
                                function (Builder $builder) {
                                    $builder->whereRaw('campaigns.camp_custom7 = "creative"')
                                        ->whereRaw("phone_time_use.custom7 = {$this->table}.adID");
                                }
                            )->orWhere(
                                function (Builder $builder) {
                                    $builder->whereRaw('campaigns.camp_custom8 = "creative"')
                                        ->whereRaw("phone_time_use.custom8 = {$this->table}.adID");
                                }
                            )->orWhere(
                                function (Builder $builder) {
                                    $builder->whereRaw('campaigns.camp_custom9 = "creative"')
                                        ->whereRaw("phone_time_use.custom9 = {$this->table}.adID");
                                }
                            )->orWhere(
                                function (Builder $builder) {
                                    $builder->whereRaw('campaigns.camp_custom10 = "creative"')
                                        ->whereRaw("phone_time_use.custom10 = {$this->table}.adID");
                                }
                            );
                        }
                    )
                    ->on('phone_time_use.account_id', '=', $this->table . '.account_id')
                    ->on('phone_time_use.campaign_id', '=', $this->table . '.campaign_id')
                    ->on('phone_time_use.utm_campaign', '=', $this->table . '.campaignID')
                    ->on(
                        DB::raw("STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d')"),
                        '=',
                        $this->table . '.day'
                    )
                    ->where('phone_time_use.source', '=', 'ydn')
                    ->where('phone_time_use.traffic_type', '=', 'AD');
            }
        );
    }

    /**
     * @return Expression[]
     */
    protected function getAggregatedForTable()
    {
        return [
            DB::raw('COUNT(`phone_time_use`.`id`) AS call_tracking')
        ];
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
        $accountId = null,
        $adgainerId = null,
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
            $accountId,
            $adgainerId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );

        $this->addJoinCondition($builder);

        return $builder;
    }

    protected function getBuilderForCalculateData(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $groupedByField,
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    )
    {
        $builder = parent::getBuilderForCalculateData(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $groupedByField,
            $accountId,
            $adgainerId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );

        $this->addJoinCondition($builder);

        return $builder;
    }
}
