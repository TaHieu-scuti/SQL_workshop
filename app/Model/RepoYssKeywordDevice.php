<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoYssKeywordDevice extends AbstractYssRawExpressions
{
    protected $table = 'repo_yss_keyword_report_cost';
    const PAGE_ID = 'keywordID';

    public $timestamps = false;

    protected function getBuilderForGetDataForTable(
        $engine,
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $columnSort,
        $sort,
        $groupedByField,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $fieldNames = $this->checkConditionFieldName($fieldNames);
        $this->conversionPoints = $this->getAllDistinctConversionNames(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            static::PAGE_ID
        );
        $campaignIDs = array_unique($this->conversionPoints->pluck('campaignID')->toArray());
        $adgroupIDs = array_unique($this->conversionPoints->pluck('adgroupID')->toArray());
        $campaigns = new Campaign;
        $this->adGainerCampaigns = $campaigns->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'yss',
            $campaignIDs,
            static::PAGE_ID,
            null,
            $adgroupIDs
        );

        $this->createTemporaryTable(
            $fieldNames,
            $this->isConv,
            $this->isCallTracking,
            $this->conversionPoints,
            $this->adGainerCampaigns
        );

        $columns = $this->unsetColumns(
            $fieldNames,
            array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING, ['device'])
        );

        $deviceDesktop = $this->getDataDeviceYssKeyword(
            $columns,
            $groupedByField,
            $startDay,
            $endDay,
            $engine,
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId,
            'DESKTOP'
        );
        $this->insertDataToTemporary($deviceDesktop, $fieldNames);

        $deviceSmartPhone = $this->getDataDeviceYssKeyword(
            $columns,
            $groupedByField,
            $startDay,
            $endDay,
            $engine,
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId,
            'SMART_PHONE'
        );
        $this->insertDataToTemporary($deviceSmartPhone, $fieldNames);

        $deviceNone = $this->getDataDeviceYssKeyword(
            $columns,
            $groupedByField,
            $startDay,
            $endDay,
            $engine,
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId,
            'NONE'
        );
        $this->insertDataToTemporary($deviceNone, $fieldNames);

        if ($this->isConv) {
            $this->updateTemporaryTableWithConversion(
                $this->conversionPoints,
                $groupedByField,
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

        if ($this->isCallTracking) {
            $this->updateTemporaryTableWithCallTracking(
                $this->adGainerCampaigns,
                $groupedByField,
                $startDay,
                $endDay,
                'DESKTOP'
            );

            $this->updateTemporaryTableWithCallTracking(
                $this->adGainerCampaigns,
                $groupedByField,
                $startDay,
                $endDay,
                'SMART_PHONE'
            );

            $this->updateTemporaryTableWithCallTracking(
                $this->adGainerCampaigns,
                $groupedByField,
                $startDay,
                $endDay,
                'NONE'
            );
        }
        $aggregated = $this->processGetAggregated(
            $fieldNames,
            $groupedByField,
            $campaignId,
            $adGroupId
        );
        return DB::table(self::TABLE_TEMPORARY)
            ->select($aggregated)
            ->groupby($groupedByField)
            ->orderBy($columnSort, $sort);
    }

    protected function getBuilderForCalculateData(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $groupedByField,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $aggregated = $this->processGetAggregated(
            $fieldNames,
            $groupedByField,
            $campaignId,
            $adGroupId
        );
        return DB::table(self::TABLE_TEMPORARY)->select($aggregated);
    }

    protected function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yssKeywordonvModel = new RepoYssKeywordReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        return $yssKeywordonvModel->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }

    protected function getDataDeviceYssKeyword(
        $fieldNames,
        $groupedByField,
        $startDay,
        $endDay,
        $engine,
        $clientId,
        $accountId,
        $campaignId,
        $adGroupId,
        $adReportId,
        $keywordId,
        $deviceName
    ) {
        $aggregations = $this->getAggregated($fieldNames);

        return $this->select(array_merge([DB::raw('"'.$deviceName. '" AS device')], $aggregations))
            ->where('device', '=', $deviceName)
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
            )
            ->groupBy($groupedByField);
    }

    private function insertDataToTemporary($builder, $columns)
    {
        $columns = $this->unsetColumns(
            $columns,
            array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING, self::FIELDS_NEED_UNSET)
        );
        if ($key = array_search('matchType', $columns)) {
            $columns[$key] = 'keywordMatchType';
        }
        DB::insert('INSERT into '.self::TABLE_TEMPORARY.' ('.implode(', ', $columns).') '
            . $this->getBindingSql($builder));
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
        $adgroupIDs = array_unique($conversionPoints->pluck('adgroupID')->toArray());
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoYssKeywordReportConv();
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_yss_keyword_report_conv.conversions) AS conversions, '.$groupedByField)
            )->where('conversionName', $conversionName)
                ->whereIn('adGroupID', $adgroupIDs)
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
            $repoPhoneTimeUseModel = new RepoPhoneTimeUse();
            $tableName = $repoPhoneTimeUseModel->getTable();
            $queryGetCallTracking = $repoPhoneTimeUseModel->select(
                DB::raw("'".$device."' AS device,COUNT(`id`) AS id")
            )->where('phone_number', $phoneNumber)
                ->where('source', 'yss')
                ->where('traffic_type', 'AD')
                ->where(
                    function (EloquentBuilder $builder) use ($tableName, $device) {
                        if ($device === "DESKTOP") {
                            $this->addConditionForDeviceDesktop($builder, $tableName);
                        } elseif ($device === "SMART_PHONE") {
                            $this->addConditionForDeviceSmartPhone($builder, $tableName);
                        } else {
                            $builder->whereRaw($tableName.'.platform LIKE "Unknown Platform%"');
                        }
                    }
                )
                ->where(
                    function (EloquentBuilder $query) use ($tableName, $device) {
                        if ($device === "DESKTOP") {
                            $query->where($tableName.'.mobile', '=', 'No');
                        } elseif ($device === "SMART_PHONE") {
                            $query->whereRaw($tableName.'.mobile LIKE "Yes%"');
                        }
                    }
                )
                ->where(
                    function (EloquentBuilder $query) use ($startDay, $tableName, $endDay) {
                        $this->addConditonForDate($query, $tableName, $startDay, $endDay);
                    }
                )->whereIn('utm_campaign', $utmCampaignList);
            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetCallTracking).') AS tbl set call'.$i.' = tbl.id where '
                .self::TABLE_TEMPORARY.'.device = tbl.device'
            );
        }
    }

    protected function addConditionForDeviceDesktop($builder, $joinTableName)
    {
        $builder->whereRaw($joinTableName.'.platform NOT LIKE "Windows Phone%"')
            ->where(
                function ($builder) use ($joinTableName) {
                    $builder->whereRaw($joinTableName.'.platform LIKE "Windows%"')
                        ->orWhereRaw($joinTableName.'.platform LIKE "Linux%"')
                        ->orWhereRaw($joinTableName.'.platform LIKE "Mac OS%"')
                        ->orWhereRaw($joinTableName.'.platform LIKE "FreeBSD%"')
                        ->orWhereRaw($joinTableName.'.platform LIKE "Unknown Windows OS%"')
                        ->orWhereRaw($joinTableName.'.platform LIKE "NetBSD%"')
                        ->orWhereRaw($joinTableName.'.platform LIKE "iOS%"')
                        ->orWhereRaw($joinTableName.'.platform LIKE "Android%"')
                        ->orWhereRaw($joinTableName.'.platform LIKE "Blackberry%"');
                }
            );
    }

    protected function addConditionForDeviceSmartPhone($builder, $joinTableName)
    {
        $builder->whereRaw($joinTableName.'.platform LIKE "Windows Phone%"')
            ->orWhereRaw($joinTableName.'.platform LIKE "iOS%"')
            ->orWhereRaw($joinTableName.'.platform LIKE "Android%"')
            ->orWhereRaw($joinTableName.'.platform LIKE "Symbian%"')
            ->orWhereRaw($joinTableName.'.platform LIKE "Blackberry%"');
    }
}
