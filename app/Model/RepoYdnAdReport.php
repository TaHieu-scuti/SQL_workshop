<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

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
        $adgroupIDs = array_unique($conversionPoints->pluck('adgroupID')->toArray());
        foreach ($conversionNames as $i => $conversionName) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $this->table . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $conversionName, $adgroupIDs) {
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
                            $joinAlias . '.adgroupID',
                            $adgroupIDs
                        )->where(
                            $joinAlias . '.conversionName',
                            '=',
                            $conversionName
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
                function (JoinClause $join) use ($campaign, $joinAlias) {
                    $join->on($joinAlias.'_campaigns.account_id', '=', $this->table . '.account_id')
                        ->on($joinAlias.'_campaigns.campaign_id', '=', $this->table . '.campaign_id')
                        ->on(
                            function (JoinClause $builder) use ($joinAlias) {
                                $builder->where(
                                    function (JoinClause $builder) use ($joinAlias) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom1 = "creative"')
                                            ->whereRaw($joinAlias.".custom1 = {$this->table}.adID");
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom2 = "creative"')
                                            ->whereRaw($joinAlias.".custom2 = {$this->table}.adID");
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom3 = "creative"')
                                            ->whereRaw($joinAlias.".custom3 = {$this->table}.adID");
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom4 = "creative"')
                                            ->whereRaw($joinAlias.".custom4 = {$this->table}.adID");
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom5 = "creative"')
                                            ->whereRaw($joinAlias.".custom5 = {$this->table}.adID");
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom6 = "creative"')
                                            ->whereRaw($joinAlias.".custom6 = {$this->table}.adID");
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom7 = "creative"')
                                            ->whereRaw($joinAlias.".custom7 = {$this->table}.adID");
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom8 = "creative"')
                                            ->whereRaw($joinAlias.".custom8 = {$this->table}.adID");
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom9 = "creative"')
                                            ->whereRaw($joinAlias.".custom9 = {$this->table}.adID");
                                    }
                                )->orWhere(
                                    function (JoinClause $builder) use ($joinAlias) {
                                        $builder->whereRaw($joinAlias.'_campaigns.camp_custom10 = "creative"')
                                            ->whereRaw($joinAlias.".custom10 = {$this->table}.adID");
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
        $this->addJoinsForCallConversions($builder, $adGainerCampaigns);
    }
}
