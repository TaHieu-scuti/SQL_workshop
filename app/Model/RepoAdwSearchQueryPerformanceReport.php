<?php

namespace App\Model;

use App\Model\AbstractAdwModel;
use App\Model\RepoAdwSearchQueryPerformanceReportConv;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoAdwSearchQueryPerformanceReport extends AbstractAdwModel
{
    const PAGE_ID = 'keywordID';
    const GROUPED_BY_FIELD_NAME = 'keyword';
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaign',
            'tableJoin' => 'repo_adw_search_query_performance_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ],
        [
            'columnName' => 'adGroup',
            'tableJoin' => 'repo_adw_search_query_performance_report_cost',
            'columnId' => 'adGroupID',
            'aliasId' => 'adgroupID',
            'aliasName' => 'adgroupName',
        ]
    ];

    protected $table = 'repo_adw_search_query_performance_report_cost';

    public $timestamps = false;

    protected $isSearchQueryReport = true;

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $adw_search_query_conv_model = new RepoAdwSearchQueryPerformanceReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        return $adw_search_query_conv_model->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
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
        $conversionNames = array_values(array_unique($conversionPoints->pluck('conversionName')->toArray()));
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoAdwSearchQueryPerformanceReportConv;
            $queryGetConversion = $convModel->select(
                array_merge(
                    [DB::raw('SUM(repo_adw_search_query_performance_report_conv.conversions) AS conversions')],
                    $this->groupBy
                )
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
                )->groupBy($this->groupBy);
            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetConversion).')AS tbl set conversions'.$key.' = tbl.conversions where '
                .self::TABLE_TEMPORARY.'.'.$groupedByField.' = tbl.'.$groupedByField. ' AND '
                .self::TABLE_TEMPORARY.'.adGroupID = tbl.adGroupID'
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
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());
        $campaignIdAdgainer = $this->getCampaignIdAdgainer(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            $utmCampaignList
        );
        $phoneNumbers = array_values(array_unique($adGainerCampaigns->pluck('phone_number')->toArray()));
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
            ->where('phone_number', $phoneNumbers[$i])
            ->where('utm_campaign', $utmCampaignList)
            ->where(
                function (EloquentBuilder $query) use ($startDay, $endDay, $phoneTimeUseTableName) {
                    $this->addConditionForDate($query, $phoneTimeUseTableName, $startDay, $endDay);
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

    public function getCampaignIdAdgainer($account_id, $accountId, $campaignId, $adGroupId, $utmCampaignList)
    {
        return $this->select('campaign_id')
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->whereIn('campaignID', $utmCampaignList)
            ->get();
    }
}
