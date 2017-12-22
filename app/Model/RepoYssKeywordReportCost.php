<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use App\Model\AbstractYssReportModel;

use Auth;

class RepoYssKeywordReportCost extends AbstractYssReportModel
{
    const PAGE_ID = "keywordID";
    const GROUPED_BY_FIELD_NAME = 'keyword';
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaignName',
            'tableJoin' => 'repo_yss_campaign_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ],
        [
            'columnName' => 'adgroupName',
            'tableJoin' => 'repo_yss_adgroup_report_cost',
            'columnId' => 'adgroupID',
            'aliasId' => 'adgroupID',
            'aliasName' => 'adgroupName',
        ]
    ];
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'repo_yss_keyword_report_cost';

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
                            $builder->whereRaw("`campaigns`.`camp_custom1` = 'adgroupid'")
                                ->whereRaw("`phone_time_use`.`custom1` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw("`campaigns`.`camp_custom2` = 'adgroupid'")
                                ->whereRaw("`phone_time_use`.`custom2` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw("`campaigns`.`camp_custom3` = 'adgroupid'")
                                ->whereRaw("`phone_time_use`.`custom3` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw("`campaigns`.`camp_custom4` = 'adgroupid'")
                                ->whereRaw("`phone_time_use`.`custom4` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw("`campaigns`.`camp_custom5` = 'adgroupid'")
                                ->whereRaw("`phone_time_use`.`custom5` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw("`campaigns`.`camp_custom6` = 'adgroupid'")
                                ->whereRaw("`phone_time_use`.`custom6` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw("`campaigns`.`camp_custom7` = 'adgroupid'")
                                ->whereRaw("`phone_time_use`.`custom7` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw("`campaigns`.`camp_custom8` = 'adgroupid'")
                                ->whereRaw("`phone_time_use`.`custom8` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw("`campaigns`.`camp_custom9` = 'adgroupid'")
                                ->whereRaw("`phone_time_use`.`custom9` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw("`campaigns`.`camp_custom10` = 'adgroupid'")
                                ->whereRaw("`phone_time_use`.`custom10` = `{$this->table}`.`adgroupID`");
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
            ->where('phone_time_use.source', '=', 'yss')
            ->on('phone_time_use.matchtype', '=', $this->table . '.keywordMatchType')
            ->on('phone_time_use.j_keyword', '=', $this->table . '.keyword')
            ->where('phone_time_use.traffic_type', '=', 'AD');
    }

    /**
     * @return Expression[]
     */
    // protected function getAggregatedForTable()
    // {
    //     return [
    //         DB::raw('COUNT(`phone_time_use`.`id`) AS call_cv'),
    //         DB::raw(
    //             "((SUM(`{$this->table}`.`conversions`) + COUNT(`phone_time_use`.`id`)) "
    //             . "/ SUM(`{$this->table}`.`clicks`)) * 100 AS call_cvr"
    //         ),
    //         DB::raw(
    //             "SUM(`{$this->table}`.`cost`) / (SUM(`{$this->table}`.`conversions`) "
    //             . "+ COUNT(`phone_time_use`.`id`)) AS call_cpa"
    //         ),
    //         DB::raw(
    //             "SUM(`{$this->table}`.conversions) AS web_cv"
    //         ),
    //         DB::raw(
    //             "(SUM(`{$this->table}`.conversions) / SUM(`{$this->table}`.clicks) * 100) AS web_cvr"
    //         ),
    //         DB::raw(
    //             "(SUM(`{$this->table}`.cost) / SUM(`{$this->table}`.conversions)) AS web_cpa"
    //         )
    //     ];
    // }

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
