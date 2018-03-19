<?php

namespace App\Model;

use App\Model\AbstractAdwPrefecture;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoAdwKeywordPrefecture extends AbstractAdwPrefecture
{
    const PAGE_ID = 'keywordID';

    protected $table = 'repo_adw_geo_report_cost';

    public $timestamps = false;

    protected function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $adwPrefectureConvModel = new RepoAdwGeoReportConv;
        $aggregation = $this->getAggregatedConversionName($column);
        $aggregation[] = 'region';
        return $adwPrefectureConvModel->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->where('network', '=', "SEARCH")
            ->get();
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
            $customFIeld = $this->getFieldName($campaign, 'adgroupid');

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
                    $this->addConditonForDate($query, $phoneTimeUseTableName, $startDay, $endDay);
                }
            )
            ->groupBy($customField);
        }
    }
}
