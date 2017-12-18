<?php

namespace App\Model;

use App\AbstractReportModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoAdwAdReportCost extends AbstractReportModel
{
    const GROUPED_BY_FIELD_NAME = 'ad';
    const PAGE_ID = 'adID';
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaign',
            'tableJoin' => 'repo_adw_ad_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ],
        [
            'columnName' => 'adGroup',
            'tableJoin' => 'repo_adw_ad_report_cost',
            'columnId' => 'adGroupID',
            'aliasId' => 'adgroupID',
            'aliasName' => 'adgroupName',
        ]
    ];

    const FIELDS = [
        'displayURL',
        'description'
    ];

    protected $table = "repo_adw_ad_report_cost";

    /**
     * @var bool
     */
    public $timestamps = false;

    private function addJoin(EloquentBuilder $builder)
    {
        $builder->leftJoin(
            DB::raw('(`phone_time_use`, `campaigns`)'),
            function (JoinClause $join) {
                $this->addJoinConditions($join);
            }
        );
    }

    protected function addJoinConditions(JoinClause $join)
    {
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
            ->where('phone_time_use.source', '=', 'adw')
            ->where('phone_time_use.traffic_type', '=', 'AD');
    }

    /**
     * @return Expression[]
     */
    protected function getAggregatedForTable()
    {
        return [
            DB::raw('COUNT(`phone_time_use`.`id`) AS call_tracking'),
            DB::raw(
                "((SUM(`{$this->table}`.`conversions`) + COUNT(`phone_time_use`.`id`)) "
                . "/ SUM(`{$this->table}`.`clicks`)) * 100 AS call_cvr"
            ),
            DB::raw(
                "SUM(`{$this->table}`.`cost`) / (SUM(`{$this->table}`.`conversions`) "
                . '+ COUNT(`phone_time_use`.`id`)) AS call_cpa'
            )
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
