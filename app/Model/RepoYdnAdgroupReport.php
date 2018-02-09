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

    protected function updateTemporaryTableWithConversion(
        $conversionPoints,
        $groupedByField,
        $startDay,
        $endDay,
        $engine,
        $clientId = null,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoYdnAdgroupReport;
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_ydn_reports.conversions) AS conversions, '.$groupedByField)
            )->where('conversionName', $conversionName)
            ->where(
                function (EloquentBuilder $query) use (
                    $convModel,
                    $startDay,
                    $endDay,
                    $engine,
                    $clientId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId
                ) {
                    $convModel->getCondition(
                        $query,
                        $startDay,
                        $endDay,
                        $engine,
                        $clientId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    );
                }
            )->groupBy($groupedByField);

            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetConversion).')AS tbl set conversions'.$key.' = tbl.conversions where '
                .self::TABLE_TEMPORARY.'.'.$groupedByField.' = tbl.'.$groupedByField
            );
        }
    }

    protected function updateTemporaryTableWithCallTracking(
        $adGainerCampaigns,
        $groupedByField,
        $startDay,
        $endDay,
        $engine,
        $clientId = null,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $campaignIdAdgainer = $this->getCampaignIdAdgainer($clientId, $accountId, $campaignId, $adGroupId);
        $phoneNumbers = array_unique($adGainerCampaigns->pluck('phone_number')->toArray());
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());

        $phoneTimeUseModel = new PhoneTimeUse();
        $phoneTimeUseTableName = $phoneTimeUseModel->getTable();
        $campaignModel = new Campaign();
        $campaignForPhoneTimeUse = $campaignModel->getCustomForPhoneTimeUse($campaignIdAdgainer);

        foreach ($campaignForPhoneTimeUse as $i => $campaign) {
            $customField = $this->getFieldName($campaign, 'adgroupid');

            $builder = $phoneTimeUseModel->select(
                [
                    DB::raw('count(id) AS id'),
                    $customField
                ]
            )
                ->whereRaw($customField.' NOT LIKE ""')
                ->where('source', '=', $engine)
                ->whereRaw('traffic_type = "AD"')
                ->whereIn('phone_number', $phoneNumbers)
                ->where('utm_campaign', $utmCampaignList)
                ->where(
                    function (EloquentBuilder $query) use ($startDay, $endDay, $phoneTimeUseTableName) {
                        $this->addConditonForDate($query, $phoneTimeUseTableName, $startDay, $endDay);
                    }
                )
                ->groupBy($customField);
            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($builder).') AS tbl set call'.$i.' = tbl.id where '
                .self::TABLE_TEMPORARY.'.adgroupID = tbl.'.$customField
            );
        }
    }

    public function getCampaignIdAdgainer($account_id, $accountId, $campaignId, $adGroupId)
    {
        return $this->select('campaign_id')
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }
}
