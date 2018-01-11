<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoYdnAdReport extends AbstractYdnReportModel
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

    private function addJoinsForConversionPoints(
        EloquentBuilder $builder,
        $conversionPoints
    ) {
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        $adIDs = array_unique($conversionPoints->pluck('adID')->toArray());
        foreach ($conversionNames as $i => $conversionName) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $this->table . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $conversionName, $adIDs) {
                    $join->on(
                        $this->table . '.account_id',
                        '=',
                        $joinAlias . '.account_id'
                    )
                        ->on(
                            $this->table . '.accountId',
                            '=',
                            $joinAlias . '.accountId'
                        )->on(
                            $this->table . '.day',
                            '=',
                            $joinAlias . '.day'
                        )->on(
                            $this->table . '.campaignID',
                            '=',
                            $joinAlias . '.campaignID'
                        )->on(
                            $this->table . '.adgroupID',
                            '=',
                            $joinAlias . '.adgroupID'
                        )->whereIn(
                            $this->table . '.adID',
                            $adIDs
                        )->where(
                            $joinAlias . '.conversionName',
                            '=',
                            $conversionName
                        );
                }
            );
        }
    }

    private function addJoinsForCallConversions(EloquentBuilder $builder, $adGainerCampaigns, $conversionPoints)
    {
        $adIDs = array_unique($conversionPoints->pluck('adID')->toArray());
        foreach ($adGainerCampaigns as $i => $campaign) {
            $joinAlias = 'call' . $i;
            $builder->leftJoin(
                DB::raw('(`phone_time_use` AS '.$joinAlias.', `campaigns` AS '.$joinAlias.'_campaigns)'),
                function (JoinClause $join) use ($campaign, $joinAlias, $adIDs) {
                    $join->on($joinAlias.'_campaigns.account_id', '=', $this->table . '.account_id')
                        ->on($joinAlias.'_campaigns.campaign_id', '=', $this->table . '.campaign_id')
                        ->on(
                            function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                $builder->where(
                                    function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom1 = "creative"')
                                            ->whereIn($joinAlias.".custom1", $adIDs);
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom2 = "creative"')
                                            ->whereIn($joinAlias.".custom2", $adIDs);
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom3 = "creative"')
                                            ->whereIn($joinAlias.".custom3", $adIDs);
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom4 = "creative"')
                                            ->whereIn($joinAlias.".custom4", $adIDs);
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom5 = "creative"')
                                            ->whereIn($joinAlias.".custom5", $adIDs);
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom6 = "creative"')
                                            ->whereIn($joinAlias.".custom6", $adIDs);
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom7 = "creative"')
                                            ->whereIn($joinAlias.".custom7", $adIDs);
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom8 = "creative"')
                                            ->whereIn($joinAlias.".custom8", $adIDs);
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom9 = "creative"')
                                            ->whereIn($joinAlias.".custom9", $adIDs);
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias, $adIDs) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom10 = "creative"')
                                            ->whereIn($joinAlias.".custom10", $adIDs);
                                    }
                                );
                            }
                        )
                        ->on($joinAlias.'.account_id', '=', $this->table . '.account_id')
                        ->on($joinAlias.'.campaign_id', '=', $this->table . '.campaign_id')
                        ->on($joinAlias.'.utm_campaign', '=', $this->table . '.campaignID')
                        ->on(
                            DB::raw("STR_TO_DATE(`$joinAlias`.`time_of_call`, '%Y-%m-%d')"),
                            '=',
                            $this->table . '.day'
                        )
                        ->where($joinAlias.'.phone_number', '=', $campaign->phone_number)
                        ->where($joinAlias.'.source', '=', 'ydn')
                        ->where($joinAlias.'.traffic_type', '=', 'AD');
                }
            );
        }
    }

    protected function addJoin(EloquentBuilder $builder, $conversionPoints = null, $adGainerCampaigns = null)
    {
        $this->addJoinsForConversionPoints($builder, $conversionPoints);
        $this->addJoinsForCallConversions($builder, $adGainerCampaigns, $conversionPoints);
    }
}
