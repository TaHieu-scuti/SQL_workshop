<?php

namespace App\Model;

use Auth;
use App\Http\Controllers\AbstractReportController;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;

class RepoYdnAdgroupReport extends AbstractYdnReportModel
{
    const GROUPED_BY_FIELD_NAME = 'adgroupName';
    const PAGE_ID = 'adgroupID';
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaignName',
            'tableJoin' => 'repo_ydn_reports',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ]
    ];

    protected $table = 'repo_ydn_reports';
    public $timestamps = false;

    private function addJoinsForConversionPoints(
        EloquentBuilder $builder,
        $conversionPoints
    ) {
        $arrAdgroupID = [];
        foreach ($conversionPoints as $val) {
            if (!in_array($val->adgroupID, $arrAdgroupID)) {
                $arrAdgroupID[] = $val->adgroupID;
            }
        }
        foreach ($conversionPoints as $i => $point) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $this->table . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $point, $arrAdgroupID) {
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
                        )->whereIn(
                            $joinAlias . '.adgroupID',
                            $arrAdgroupID
                        )->where(
                            $joinAlias . '.conversionName',
                            '=',
                            $point->conversionName
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

    public function getAllYdnAdgroup(
        $accountId = null,
        $campaignId = null
    ) {
        $engine = session(static::SESSION_KEY_ENGINE);
        return self::select('adgroupID', 'adgroupName')
            ->where(
                function ($query) use ($accountId, $campaignId, $engine) {
                    $this->addQueryConditions(
                        $query,
                        session(AbstractReportController::SESSION_KEY_CLIENT_ID),
                        $engine,
                        $accountId,
                        $campaignId
                    );
                }
            )
            ->groupBy('adgroupID', 'adgroupName')->get();
    }
}
