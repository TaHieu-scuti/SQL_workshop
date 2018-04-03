<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class RepoYdnAdDevice extends AbstractYdnDevice
{
    protected $table = 'repo_ydn_reports';

    public $timestamps = false;

    const PAGE_ID = 'adID';

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
        $adIDs = array_unique($conversionPoints->pluck('adID')->toArray());
        foreach ($conversionNames as $key => $conversionName) {
            $queryGetConversion = $this->select(
                DB::raw('SUM(repo_ydn_reports.conversions) AS conversions, '.$groupedByField)
            )->where('conversionName', $conversionName)
                ->whereIn('adID', $adIDs)
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
        $device
    ) {
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());
        $phoneList = array_values(array_unique($adGainerCampaigns->pluck('phone_number')->toArray()));
        foreach ($phoneList as $i => $phoneNumber) {
            $phoneTimeUseModel = new PhoneTimeUse();
            $tableName = $phoneTimeUseModel->getTable();
            $queryGetCallTracking = $phoneTimeUseModel->select(
                DB::raw("'".$device."' AS device,COUNT(`id`) AS id")
            )->where('phone_number', $phoneNumber)
                ->where('source', 'ydn')
                ->where('traffic_type', 'AD')
                ->where(
                    function (EloquentBuilder $builder) use ($tableName, $device) {
                        if ($device === "PC") {
                            $this->addConditionForDevicePC($builder, $tableName);
                        } elseif ($device === "SmartPhone") {
                            $this->addConditionForDeviceSmartPhone($builder, $tableName);
                        } elseif ($device === "Tablet") {
                            $this->addConditionForDeviceTablet($builder, $tableName);
                        } else {
                            $builder->whereRaw($tableName.'.platform LIKE "Unknown Platform%"');
                        }
                    }
                )
                ->where(
                    function (EloquentBuilder $query) use ($tableName, $device) {
                        if ($device === "PC" || $device === "Tablet") {
                            $query->where($tableName.'.mobile', '=', 'No');
                        } elseif ($device === "SmartPhone") {
                            $query->whereRaw($tableName.'.mobile LIKE "Yes%"');
                        }
                    }
                )
                ->where(
                    function (EloquentBuilder $query) use ($startDay, $tableName, $endDay) {
                        $this->addConditionForDate($query, $tableName, $startDay, $endDay);
                    }
                )->whereIn('utm_campaign', $utmCampaignList);

            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetCallTracking).') AS tbl set call'.$i.' = tbl.id where '
                .self::TABLE_TEMPORARY.'.device = tbl.device'
            );
        }
    }
}
