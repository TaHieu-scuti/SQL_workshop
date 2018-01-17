<?php

namespace App\Model;

use App\Model\AbstractAdwModel;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

class RepoAdwKeywordReportCost extends AbstractAdwModel
{
    const GROUPED_BY_FIELD_NAME = 'keyword';
    const PAGE_ID = "keywordID";
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaign',
            'tableJoin' => 'repo_adw_keywords_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ],
        [
            'columnName' => 'adGroup',
            'tableJoin' => 'repo_adw_keywords_report_cost',
            'columnId' => 'adGroupID',
            'aliasId' => 'adgroupID',
            'aliasName' => 'adgroupName',
        ]
    ];

    protected $table = "repo_adw_keywords_report_cost";

    /**
     * @var bool
     */
    public $timestamps = false;

    protected function addJoin(EloquentBuilder $builder, $conversionPoints = null, $adGainerCampaigns = null)
    {
        $this->addJoinsForConversionPoints($builder, $conversionPoints);
        $this->addJoinsForCallConversions($builder, $adGainerCampaigns);
    }

    private function addJoinsForConversionPoints(
        EloquentBuilder $builder,
        $conversionPoints
    ) {
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        $keywordReportConvTableName = (new RepoAdwKeywordReportConv)->getTable();
        foreach ($conversionNames as $i => $conversionName) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $keywordReportConvTableName . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $conversionName) {
                    $join->where(
                        $joinAlias . '.conversionName',
                        '=',
                        $conversionName
                    )
                        ->on(
                            $this->table . '.day',
                            '=',
                            $joinAlias . '.day'
                        )->on(
                            $this->table . '.keywordID',
                            '=',
                            $joinAlias . '.keywordID'
                        )->on(
                            $this->table . '.adGroupID',
                            '=',
                            $joinAlias . '.adGroupID'
                        )->on(
                            $this->table . '.campaignID',
                            '=',
                            $joinAlias . '.campaignID'
                        )->on(
                            $this->table . '.customerId',
                            '=',
                            $joinAlias . '.customerID'
                        )->on(
                            $this->table . '.campaign_id',
                            '=',
                            $joinAlias . '.campaign_id'
                        )->on(
                            $this->table . '.account_id',
                            '=',
                            $joinAlias . '.account_id'
                        );
                }
            );
        }
    }

    private function addJoinsForCallConversions(EloquentBuilder $builder, $adGainerCampaigns)
    {
        foreach ($adGainerCampaigns as $i => $campaign) {
            $joinAlias = 'call' . $i;
            $builder->leftJoin(
                DB::raw('(`phone_time_use` AS '.$joinAlias.', `campaigns` AS '.$joinAlias.'_campaigns)'),
                function (JoinClause $join) use ($joinAlias, $campaign) {
                    $join->on($joinAlias.'_campaigns.account_id', '=', $joinAlias . '.account_id')
                        ->on($joinAlias.'_campaigns.campaign_id', '=', $joinAlias . '.campaign_id')
                        ->on(
                            function (Builder $builder) use ($joinAlias) {
                                $builder->where(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom1` = 'adgroupid'")
                                            ->whereRaw("`".$joinAlias."`.`custom1` = `{$this->table}`.`adGroupID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom2` = 'adgroupid'")
                                            ->whereRaw("`".$joinAlias."`.`custom2` = `{$this->table}`.`adGroupID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom3` = 'adgroupid'")
                                            ->whereRaw("`".$joinAlias."`.`custom3` = `{$this->table}`.`adGroupID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom4` = 'adgroupid'")
                                            ->whereRaw("`".$joinAlias."`.`custom4` = `{$this->table}`.`adGroupID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom5` = 'adgroupid'")
                                            ->whereRaw("`".$joinAlias."`.`custom5` = `{$this->table}`.`adGroupID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom6` = 'adgroupid'")
                                            ->whereRaw("`".$joinAlias."`.`custom6` = `{$this->table}`.`adGroupID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom7` = 'adgroupid'")
                                            ->whereRaw("`".$joinAlias."`.`custom7` = `{$this->table}`.`adGroupID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom8` = 'adgroupid'")
                                            ->whereRaw("`".$joinAlias."`.`custom8` = `{$this->table}`.`adGroupID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom9` = 'adgroupid'")
                                            ->whereRaw("`".$joinAlias."`.`custom9` = `{$this->table}`.`adGroupID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom10` = 'adgroupid'")
                                            ->whereRaw("`".$joinAlias."`.`custom10` = `{$this->table}`.`adGroupID`");
                                    }
                                );
                            }
                        )
                        ->on($joinAlias.'.account_id', '=', $this->table . '.account_id')
                        ->on($joinAlias.'.utm_campaign', '=', $this->table . '.campaignID')
                        ->on($joinAlias.'.phone_number', '=', $campaign->phone_number)
                        ->on($joinAlias.'.j_keyword', '=', $this->table . 'keyword')
                        ->on($joinAlias.'.matchtype', '=', $this->table . 'matchtype')
                        ->on(
                            DB::raw("STR_TO_DATE(`".$joinAlias."`.`time_of_call`, '%Y-%m-%d')"),
                            '=',
                            $this->table . '.day'
                        )
                        ->where($joinAlias.'.source', '=', 'adw')
                        ->where($joinAlias.'.traffic_type', '=', 'AD');
                }
            );
        }
    }

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $adw_keyword_conv_model = new RepoAdwKeywordReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        return $adw_keyword_conv_model->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }
}
