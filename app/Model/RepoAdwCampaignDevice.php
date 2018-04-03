<?php

namespace App\Model;

use App\Model\AbstractAdwSubReportModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoAdwCampaignDevice extends AbstractAdwDevice
{
    const PAGE_ID = "campaignID";

    public $timestamps = false;

    protected $table = 'repo_adw_campaign_report_cost';

    protected function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yssCampaignConvModel = new RepoAdwCampaignReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        $aggregation[] = 'device';
        return $yssCampaignConvModel->select($aggregation)
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
            $convModel = new RepoAdwCampaignReportConv;
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_adw_campaign_report_conv.conversions) AS conversions, '.$groupedByField)
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
        $device
    ) {
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());
        $phoneList = array_values(array_unique($adGainerCampaigns->pluck('phone_number')->toArray()));
        foreach ($phoneList as $i => $phoneNumber) {
            $repoPhoneTimeUseModel = new RepoPhoneTimeUse;
            $tableName = $repoPhoneTimeUseModel->getTable();
            $queryGetCallTracking = $repoPhoneTimeUseModel->select(
                DB::raw("'".$device."' AS device, COUNT(`id`) AS id")
            )->where('phone_number', $phoneNumber)
            ->where('source', 'adw')
            ->where('traffic_type', 'AD')
            ->where(
                function (EloquentBuilder $builder) use ($tableName, $device) {
                    if ($device === 'DESKTOP' || $device === 'TABLET') {
                        $builder->where($tableName.'.mobile', '=', 'No');
                    }

                    if ($device === 'HIGH_END_MOBILE') {
                        $builder->where($tableName.'.mobile', '=', 'Yes');
                    }
                }
            )->where(
                function (EloquentBuilder $builder) use ($tableName, $device) {
                    $this->checkingConditionForDevice($builder, $tableName, $device);
                }
            )
            ->where(
                function (EloquentBuilder $builder) use ($startDay, $tableName, $endDay) {
                    $this->addConditionForDate($builder, $tableName, $startDay, $endDay);
                }
            )->whereIn('utm_campaign', $utmCampaignList);
            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetCallTracking).') AS tbl set call'.$i.' = tbl.id where '
                .self::TABLE_TEMPORARY.'.'.$groupedByField.' = tbl.'.$groupedByField
            );
        }
    }
}
