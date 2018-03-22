<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class AbstractYdnDevice extends AbstractYdnRawExpressions
{
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
        $campaigns = new Campaign;
        $this->adGainerCampaigns = $campaigns->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'ydn',
            $campaignIDs
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
            array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING, ['campaignName'], ['device'])
        );

        $this->insertDataToTemporaryOfEngines(
            $columns,
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
            $keywordId
        );

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
            $this->updateDataForCallTrackingOfYdn(
                $this->adGainerCampaigns,
                $groupedByField,
                $startDay,
                $endDay
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
        $aggregation = $this->getAggregatedConversionName($column);
        $aggregation[] = 'device';
        return $this->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }

    private function insertDataToTemporaryOfEngines(
        $columns,
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
        $keywordId
    ) {
        $deviceDesktop = $this->getDataDeviceYdn(
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
            'PC'
        );

        $this->insertDataToTemporary($deviceDesktop, $fieldNames);

        $deviceSmartPhone = $this->getDataDeviceYdn(
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
            'Tablet'
        );
        $this->insertDataToTemporary($deviceSmartPhone, $fieldNames);

        $deviceNone = $this->getDataDeviceYdn(
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
            'SmartPhone'
        );
        $this->insertDataToTemporary($deviceNone, $fieldNames);
        $deviceOther = $this->getDataDeviceYdn(
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
            'Other'
        );
        $this->insertDataToTemporary($deviceOther, $fieldNames);
    }

    private function updateDataForCallTrackingOfYdn(
        $adGainerCampaigns,
        $groupedByField,
        $startDay,
        $endDay
    ) {
        $this->updateTemporaryTableWithCallTracking(
            $adGainerCampaigns,
            $groupedByField,
            $startDay,
            $endDay,
            'PC'
        );

        $this->updateTemporaryTableWithCallTracking(
            $adGainerCampaigns,
            $groupedByField,
            $startDay,
            $endDay,
            'Tablet'
        );

        $this->updateTemporaryTableWithCallTracking(
            $adGainerCampaigns,
            $groupedByField,
            $startDay,
            $endDay,
            'SmartPhone'
        );

        $this->updateTemporaryTableWithCallTracking(
            $adGainerCampaigns,
            $groupedByField,
            $startDay,
            $endDay,
            'Other'
        );
    }

    protected function getDataDeviceYdn(
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
            array_merge(self::UNSET_COLUMNS,
                self::FIELDS_CALL_TRACKING,
                ['campaignName', 'adgroupName', 'adName']
            )
        );
        DB::insert('INSERT into '.self::TABLE_TEMPORARY.' ('.implode(', ', $columns).') '
            . $this->getBindingSql($builder));
    }

    protected function addConditionForDevicePC($builder, $joinTableName)
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
        $builder->WhereRaw($joinTableName.'.platform LIKE "iOS%"')
            ->orWhereRaw($joinTableName.'.platform LIKE "Android%"')
            ->orWhereRaw($joinTableName.'.platform LIKE "Symbian%"')
            ->orWhereRaw($joinTableName.'.platform LIKE "Blackberry%"');
    }

    protected function addConditionForDeviceTablet($builder, $joinTableName)
    {
        $builder->WhereRaw($joinTableName.'.platform LIKE "iOS%"')
            ->orWhereRaw($joinTableName.'.platform LIKE "Android%"')
            ->orWhereRaw($joinTableName.'.platform LIKE "Blackberry%"');
    }
}
