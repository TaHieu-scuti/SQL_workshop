<?php

namespace App\Model;

use App\Model\AbstractYssReportModel;
use App\Http\Controllers\AbstractReportController;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoYssCampaignReportCost extends AbstractYssReportModel
{
    // constant
    const GROUPED_BY_FIELD_NAME = 'campaignName';
    const PAGE_ID = 'campaignID';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'repo_yss_campaign_report_cost';

    protected $casts = [
        'call_cv' => 'integer',
        'call_cvr' => 'integer',
        'call_cpa' => 'integer',
        'web_cv' => 'integer',
        'web_cvr' => 'integer',
        'web_cpa' => 'integer'
    ];

    const FIX_INSERT_FILEDS = [
        'campaignID',
        'campaignName',
        'impressions',
        'clicks',
        'cost',
        'ctr',
        'averageCpc',
        'averagePosition',
        'impressionShare'
    ];

    private function addJoinsForConversionPoints(
        EloquentBuilder $builder,
        $conversionPoints
    ) {
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        $campaignIDs = array_unique($conversionPoints->pluck('campaignID')->toArray());
        $campaignReportConvTableName = (new RepoYssCampaignReportConv)->getTable();
        foreach ($conversionNames as $i => $conversionName) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $campaignReportConvTableName . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $conversionName, $campaignIDs) {
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
                            $joinAlias . '.campaignID',
                            $campaignIDs
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
        $joinTableName = (new RepoPhoneTimeUse)->getTable();
        foreach ($adGainerCampaigns as $i => $campaign) {
            $joinAlias = 'call' . $i;
            $builder->leftJoin(
                $joinTableName . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $campaign) {
                    $join->on(
                        $this->table . '.account_id',
                        '=',
                        $joinAlias . '.account_id'
                    )->on(
                        $this->table . '.campaign_id',
                        '=',
                        $joinAlias . '.campaign_id'
                    )->on(
                        $this->table . '.campaignID',
                        '=',
                        $joinAlias . '.utm_campaign'
                    )->on(
                        $this->table . '.day',
                        '=',
                        DB::raw("STR_TO_DATE(`" . $joinAlias . "`.`time_of_call`, '%Y-%m-%d')")
                    )->where(
                        $joinAlias . '.utm_campaign',
                        '=',
                        $campaign->utm_campaign
                    )->whereRaw(
                        '`' . $joinAlias . "`.`phone_number` = '" . $campaign->phone_number . "'"
                    )->where(
                        $joinAlias . '.source',
                        '=',
                        'yss'
                    );
                }
            );
        }
    }

    protected function insertConversionToTemporaryTable(
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
        $campaignIDs = array_unique($conversionPoints->pluck('campaignID')->toArray());
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = DB::table('repo_yss_campaign_report_conv')
            ->select(
                DB::raw('SUM(repo_yss_campaign_report_conv.conversions) AS conversions, '.$groupedByField)
            )->where('conversionName', $conversionName)
            ->where(
                function (EloquentBuilder $query) use (
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
                    $this->getCondition(
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
        }
    }

    protected function getAllCallTrackingJoined(
        $adGainerCampaigns,
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
        $callTrackingArray = [];
        $tableJoin = (new RepoPhoneTimeUse)->getTable();
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());
        $phoneList = array_unique($adGainerCampaigns->pluck('phone_number')->toArray());
        foreach ($phoneList as $i => $phoneNumber) {
            $callTrackingArray[] = $this->select(
                DB::raw($this->table.".dayOfWeek,
                    COUNT(".$tableJoin.".`id`) as total, '".$phoneNumber."' as phoneNumber")
            )->leftJoin(
                $tableJoin,
                function (JoinClause $join) use ($tableJoin, $phoneNumber, $utmCampaignList) {
                    $join->on(
                        $this->table . '.account_id',
                        '=',
                        $tableJoin . '.account_id'
                    )->on(
                        $this->table . '.campaign_id',
                        '=',
                        $tableJoin . '.campaign_id'
                    )->whereIn(
                        $tableJoin . '.utm_campaign',
                        $utmCampaignList
                    )->on(
                        $this->table . '.dayOfWeek',
                        '=',
                        DB::raw('DAYNAME(' . $tableJoin . '.time_of_call)')
                    )->whereRaw($tableJoin . ".phone_number = '" . $phoneNumber . "'")
                    ->whereRaw($tableJoin . ".time_of_call LIKE CONCAT(".$this->table.".`day`, '%')")
                    ->where($tableJoin . '.source', '=', 'yss')
                    ->where($tableJoin . '.traffic_type', '=', 'AD');
                }
            )->where(
                function (EloquentBuilder $query) use (
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
                    $this->getCondition(
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
            )->groupBy($this->table . '.dayOfWeek')
            ->get();
        }
        return $callTrackingArray;
    }

    protected function addJoin(EloquentBuilder $builder, $conversionPoints = null, $adGainerCampaigns = null)
    {
        $this->addJoinsForConversionPoints($builder, $conversionPoints);
        $this->addJoinsForCallConversions($builder, $adGainerCampaigns);
    }

    public function getAllCampaign(
        $accountId = null
    ) {
        $engine = session(static::SESSION_KEY_ENGINE);
        $arrCampaigns = [];
        $campaigns = null;
        $arrCampaigns['all'] = 'All Campaigns';
        if (session(AbstractReportController::SESSION_KEY_ENGINE) === 'yss') {
            $campaigns = self::select('campaignID', 'campaignName')
                ->where(
                    function ($query) use ($accountId, $engine) {
                        $this->addQueryConditions(
                            $query,
                            session(AbstractReportController::SESSION_KEY_CLIENT_ID),
                            $engine,
                            $accountId
                        );
                    }
                )
                ->groupBy('campaignID', 'campaignName')->get();
        } elseif (session(AbstractReportController::SESSION_KEY_ENGINE) === 'adw') {
            $modelAdwCampaign = new RepoAdwCampaignReportCost;
            $campaigns = $modelAdwCampaign->getAllAdwCampaign(
                $accountId = null
            );
        } elseif (session(AbstractReportController::SESSION_KEY_ENGINE) === 'ydn') {
            $modelYdnCampaign = new RepoYdnCampaignReport;
            $campaigns = $modelYdnCampaign->getAllYdnCampaign(
                $accountId = null
            );
        }
        if (!is_null($campaigns)) {
            foreach ($campaigns as $key => $campaign) {
                $arrCampaigns[$campaign->campaignID] = $campaign->campaignName;
            }
        }

        return $arrCampaigns;
    }

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yss_campaign_model = new RepoYssCampaignReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        $conversionPoints = $yss_campaign_model->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
        return $conversionPoints;
    }
}
