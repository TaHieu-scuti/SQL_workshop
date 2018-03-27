<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoAdwKeywordDevice extends AbstractAdwDevice
{
    const PAGE_ID = "keywordID";

    public $timestamps = false;

    protected $table = 'repo_adw_keywords_report_cost';

    protected function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yssKeywordConvModel = new RepoAdwKeywordReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        return $yssKeywordConvModel->select($aggregation)
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
            $convModel = new RepoAdwKeywordReportConv;
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_adw_keywords_report_conv.conversions) AS conversions, '.$groupedByField)
            )->where('conversionName', $conversionName)
                ->where(
                    function (EloquentBuilder $query) use ($startDay, $endDay, $convModel) {
                        $convModel->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )
                ->where(
                    function (EloquentBuilder $query) use (
                        $convModel,
                        $clientId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId,
                        $engine
                    ) {
                        $convModel->addQueryConditions(
                            $query,
                            $clientId,
                            $engine,
                            $accountId,
                            $campaignId,
                            $adGroupId,
                            $adReportId,
                            $keywordId
                        );
                    }
                )
                ->where(
                    function (EloquentBuilder $query) use ($convModel) {
                        $convModel->addConditionNetworkQuery($query);
                    }
                )
                ->groupBy($groupedByField);
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
            $phoneTimeUseModel = new PhoneTimeUse;
            $tableName = $phoneTimeUseModel->getTable();
            $queryGetCallTracking = $phoneTimeUseModel->select(
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
                        $this->addConditonForDate($builder, $tableName, $startDay, $endDay);
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
