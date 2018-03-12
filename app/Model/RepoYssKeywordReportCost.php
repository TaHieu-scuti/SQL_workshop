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
            $convModel = new RepoYssKeywordReportConv();
            $queryGetConversion = $convModel->select(
                array_merge(
                    [DB::raw('SUM(repo_yss_keyword_report_conv.conversions) AS conversions')],
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
                .self::TABLE_TEMPORARY.'.adgroupID = tbl.adgroupID'
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
        $campaignIds = $this->getCampaignIds(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            $keywordId,
            $utmCampaignList
        );
        $phoneNumbers = array_values(array_unique($adGainerCampaigns->pluck('phone_number')->toArray()));
        if ($groupedByField === 'keyword') {
            $groupedByField = 'adgroupID';
        }
        $campaignModel = new Campaign;
        $phoneTimeUseModel = new PhoneTimeUse();
        $phoneTimeUseTableName = $phoneTimeUseModel->getTable();
        $campaignForPhoneTimeUse = $campaignModel->getCustomForPhoneTimeUse($campaignIds);
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
            ->where('phone_number', '=', $phoneNumbers[$i])
            ->whereIn('utm_campaign', $utmCampaignList)
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

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yss_keyword_conv_model = new RepoYssKeywordReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        $conversionPoints = $yss_keyword_conv_model->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
        return $conversionPoints;
    }

    private function getCampaignIds(
        $clientId = null,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $keywordId = null,
        $utmCampaignList = null
    ) {
        return $this->select('campaign_id')
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($clientId, $accountId, $campaignId, $adGroupId, $keywordId) {
                    $this->addConditonForConversionName(
                        $query,
                        $clientId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $keywordId
                    );
                }
            )
            ->whereIn('campaignID', $utmCampaignList)
            ->get();
    }
}
