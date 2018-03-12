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
            $queryGetConversion = $this->select(
                DB::raw('SUM('.$this->getTable().'.conversions) AS conversions, '.$groupedByField)
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
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());
        $campaignIds = $this->getCampaignIds(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $utmCampaignList
        );
        $phoneNumbers = array_unique($adGainerCampaigns->pluck('phone_number')->toArray());
        $campaignModel = new Campaign;
        $phoneTimeUseModel = new PhoneTimeUse();
        $phoneTimeUseTableName = $phoneTimeUseModel->getTable();
        $campaignForPhoneTimeUse = $campaignModel->getCustomForPhoneTimeUse($campaignIds);
        foreach ($campaignForPhoneTimeUse as $i => $campaign) {
            $customField = $this->getFieldName($campaign, 'creative');

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
                .self::TABLE_TEMPORARY.'.adID = tbl.'.$customField
            );
        }
    }

    private function getCampaignIds(
        $clientId = null,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $utmCampaignList = null
    ) {
        return $this->select('campaign_id')
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($clientId, $accountId, $campaignId, $adGroupId, $adReportId) {
                    $this->addConditonForConversionName(
                        $query,
                        $clientId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId
                    );
                }
            )
            ->whereIn('campaignID', $utmCampaignList)
            ->get();
    }
}
