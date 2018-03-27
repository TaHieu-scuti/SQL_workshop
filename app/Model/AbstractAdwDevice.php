<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

abstract class AbstractAdwDevice extends AbstractAdwSubReportModel
{
    const ARRAY_ADW_NEED_UNSET = [
        'campaign',
        'adGroup'
    ];
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
        $adIDs = array_unique($this->conversionPoints->pluck('adID')->toArray());
        $campaigns = new Campaign;
        $this->adGainerCampaigns = $campaigns->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'adw',
            $campaignIDs,
            static::PAGE_ID,
            $adIDs
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
            array_merge(
                self::UNSET_COLUMNS,
                self::FIELDS_CALL_TRACKING,
                self::ARRAY_ADW_NEED_UNSET,
                ['device']
            )
        );

        $this->insertAllDeviceDataIntoTemporaryTable(
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
            $fieldNames
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
            $this->updateTemporaryTableWithCallTracking(
                $this->adGainerCampaigns,
                $groupedByField,
                $startDay,
                $endDay,
                'UNKNOWN'
            );

            $this->updateTemporaryTableWithCallTracking(
                $this->adGainerCampaigns,
                $groupedByField,
                $startDay,
                $endDay,
                'TABLET'
            );

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
                'HIGH_END_MOBILE'
            );
        }
        $arr = [];
        if (in_array('impressionShare', $fieldNames)) {
            $arr[] = DB::raw("IFNULL(ROUND(impressionShare, 2), 0) AS impressionShare");
        }
        $fields = $this->unsetColumns($fieldNames, ['impressionShare']);
        $aggregated = $this->processGetAggregated(
            $fields,
            $groupedByField,
            $campaignId,
            $adGroupId
        );
        return DB::table(self::TABLE_TEMPORARY)
            ->select(array_merge($aggregated, $arr))
            ->groupby($groupedByField)
            ->orderBy($columnSort, $sort);
    }

    private function getDataForAdwCampaignDevice(
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
                function (EloquentBuilder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
                function (EloquentBuilder $query) use (
                    $clientId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId,
                    $engine
                ) {
                    $this->addQueryConditions(
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
                function (EloquentBuilder $query) {
                    $this->addConditionNetworkQuery($query);
                }
            )
            ->groupBy($groupedByField);
    }

    private function insertDataToTemporary($builder, $columns)
    {
        $columns = $this->unsetColumns(
            $columns,
            array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING, self::ARRAY_ADW_NEED_UNSET)
        );
        $columns = array_keys($this->updateFieldNames($columns));

        DB::insert('INSERT into '.self::TABLE_TEMPORARY.' ('.implode(', ', $columns).') '
            . $this->getBindingSql($builder));
    }

    private function addConditionForDesktopDevice(EloquentBuilder $builder, $tableName)
    {
        $builder->whereRaw($tableName.'.mobile = "No"')
            ->whereRaw($tableName.'.platform NOT LIKE "Window Phone%"')
            ->where(
                function ($builder) use ($tableName) {
                    $builder->whereRaw($tableName.'.platform LIKE "Windows%"')
                        ->orWhereRaw($tableName.'.platform LIKE "Linux%"')
                        ->orWhereRaw($tableName.'.platform LIKE "Mac OS%"')
                        ->orWhereRaw($tableName.'.platform LIKE "FreeBSD%"')
                        ->orWhereRaw($tableName.'.platform LIKE "NetBSD%"')
                        ->orWhereRaw($tableName.'.platform LIKE "Unknown Windows OS%"');
                }
            );
    }

    private function addConditionForTabletDevice(EloquentBuilder $builder, $tableName)
    {
        $builder->whereRaw($tableName.'.mobile = "No"')
            ->whereRaw($tableName.'.platform LIKE "Android%"')
            ->where(
                function ($builder) use ($tableName) {
                    $builder->whereRaw($tableName.'.platform LIKE "iOS%"')
                        ->orWhereRaw($tableName.'.platform LIKE "Android%"')
                        ->orWhereRaw($tableName.'.platform LIKE "Blackberry%"');
                }
            );
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
        $arr = [];
        if (in_array('impressionShare', $fieldNames)) {
            $arr[] = DB::raw("IFNULL(ROUND(impressionShare, 2), 0) AS impressionShare");
        }
        $fields = $this->unsetColumns($fieldNames, ['impressionShare']);
        $aggregated = $this->processGetAggregated(
            $fields,
            $groupedByField,
            $campaignId,
            $adGroupId
        );
        return DB::table(self::TABLE_TEMPORARY)->select(array_merge($aggregated, $arr));
    }

    protected function checkingConditionForDevice(
        EloquentBuilder $builder,
        $tableName,
        $device
    ) {
        // we dont need to add platform condition for HIGH_END_MOBILE cause `mobile = yes` is enough
        if ($device === 'DESKTOP') {
            $this->addConditionForDesktopDevice($builder, $tableName);
        } elseif ($device === 'TABLET') {
            $this->addConditionForTabletDevice($builder, $tableName);
        } elseif ($device === 'UNKNOWN') {
            $builder->whereRaw($tableName.'.platform LIKE "Unknown Platform%"');
        }
    }

    private function insertAllDeviceDataIntoTemporaryTable(
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
        $fieldNames
    ) {
        $unknownDevice = $this->getDataForAdwCampaignDevice(
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
            'UNKNOWN'
        );
        $this->insertDataToTemporary($unknownDevice, $fieldNames);

        $tabletDevice = $this->getDataForAdwCampaignDevice(
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
            'TABLET'
        );
        $this->insertDataToTemporary($tabletDevice, $fieldNames);

        $desktopDevice = $this->getDataForAdwCampaignDevice(
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
        $this->insertDataToTemporary($desktopDevice, $fieldNames);

        $highEndMobileDevice = $this->getDataForAdwCampaignDevice(
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
            'HIGH_END_MOBILE'
        );
        $this->insertDataToTemporary($highEndMobileDevice, $fieldNames);
    }
}
