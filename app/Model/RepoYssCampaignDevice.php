<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoYssCampaignDevice extends AbstractYssRawExpressions
{
    protected $table = 'repo_yss_campaign_report_cost';

    const PAGE_ID = 'campaignID';
    const GROUPED_BY_FIELD_NAME = 'device';

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
        $keyPrefix,
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
            'adw',
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

        $deviceDesktop = $this->getDataDeviceYssCampaign(
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

        $deviceSmartPhone = $this->getDataDeviceYssCampaign(
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

        $deviceNone = $this->getDataDeviceYssCampaign(
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
        $allColumns = $this->getAllColumns(DB::table(self::TABLE_TEMPORARY)->select($aggregated)->columns);
        $columnSort = $this->getSortColumn($keyPrefix, $allColumns, $columnSort);

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
        $yssCampaignConvModel = new RepoYssCampaignReportConv();
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

    protected function getDataDeviceYssCampaign(
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
            array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING, ['campaignName'])
        );

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
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoYssCampaignReportConv();
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_yss_campaign_report_conv.conversions) AS conversions, '.$groupedByField)
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
