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
        $adReportConvTableName = (new RepoAdwAdReportConv)->getTable();
        foreach ($conversionNames as $i => $conversionName) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $adReportConvTableName . ' AS ' . $joinAlias,
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
                            $this->table . '.adID',
                            '=',
                            $joinAlias . '.adID'
                        )->on(
                            $this->table . '.adgroupID',
                            '=',
                            $joinAlias . '.adgroupID'
                        )->on(
                            $this->table . '.campaignId',
                            '=',
                            $joinAlias . '.campaignId'
                        )->on(
                            $this->table . '.customerId',
                            '=',
                            $joinAlias . '.customerId'
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
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom1` = 'creative'")
                                            ->whereRaw("`".$joinAlias."`.`custom1` = `{$this->table}`.`adID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom2` = 'creative'")
                                            ->whereRaw("`".$joinAlias."`.`custom2` = `{$this->table}`.`adID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom3` = 'creative'")
                                            ->whereRaw("`".$joinAlias."`.`custom3` = `{$this->table}`.`adID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom4` = 'creative'")
                                            ->whereRaw("`".$joinAlias."`.`custom4` = `{$this->table}`.`adID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom5` = 'creative'")
                                            ->whereRaw("`".$joinAlias."`.`custom5` = `{$this->table}`.`adID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom6` = 'creative'")
                                            ->whereRaw("`".$joinAlias."`.`custom6` = `{$this->table}`.`adID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom7` = 'creative'")
                                            ->whereRaw("`".$joinAlias."`.`custom7` = `{$this->table}`.`adID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom8` = 'creative'")
                                            ->whereRaw("`".$joinAlias."`.`custom8` = `{$this->table}`.`adID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom9` = 'creative'")
                                            ->whereRaw("`".$joinAlias."`.`custom9` = `{$this->table}`.`adID`");
                                    }
                                )->orWhere(
                                    function (Builder $builder) use ($joinAlias) {
                                        $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom10` = 'creative'")
                                            ->whereRaw("`".$joinAlias."`.`custom10` = `{$this->table}`.`adID`");
                                    }
                                );
                            }
                        )
                        ->on($joinAlias.'.account_id', '=', $this->table . '.account_id')
                        ->on($joinAlias.'.utm_campaign', '=', $this->table . '.campaignID')
                        ->on($joinAlias.'.phone_number', '=', $campaign->phone_number)
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
        $adw_ad_conv_model = new RepoAdwAdReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        return $adw_ad_conv_model->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }
}
