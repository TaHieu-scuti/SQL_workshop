<?php

namespace App\Model;

use App\Model\AbstractAdwSubReportModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoAdwCampaignDevice extends AbstractAdwSubReportModel
{
    const PAGE_ID = "campaignID";

    public $timestamps = false;

    protected $table = 'repo_adw_campaign_report_cost';

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
            array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING, ['campaign'], ['device'])
        );

        $unknowDevice = $this->getDataForAdwCampaignDevice(
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
        $this->insertDataToTemporary($unknowDevice, $fieldNames);

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
                'UNKNOW'
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
        $arr[] = DB::raw("IFNULL(ROUND(impressionShare, 2), 0) AS impressionShare");
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
            array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING, ['campaign'])
        );
        $columns = array_keys($this->updateFieldNames($columns));

        DB::insert('INSERT into '.self::TABLE_TEMPORARY.' ('.implode(', ', $columns).') '
            . $this->getBindingSql($builder));
    }

    private function updateTemporaryTableWithConversion(
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

    private function updateTemporaryTableWithCallTracking(
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
                DB::raw("'".$device."' AS device,COUNT(`id`) AS id")
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
                    if ($device === 'DESKTOP') {
                        $this->addConditionForDesktopDevice($builder, $tableName);
                    }

                    if ($device === 'TABLET') {
                        $this->addConditionForTabletDevice($builder, $tableName);
                    }

                    if ($device === 'UNKNOW') {
                        $builder->whereRaw($tableName.'.platform LIKE "Unknow Platform%"');
                    }
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
                .self::TABLE_TEMPORARY.'.device = tbl.device'
            );
        }
    }

    private function addConditionForDesktopDevice(EloquentBuilder $builder, $tableName) {
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

    private function addConditionForTabletDevice(EloquentBuilder $builder, $tableName) {
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
        $arr[] = DB::raw("IFNULL(ROUND(impressionShare, 2), 0) AS impressionShare");
        $fields = $this->unsetColumns($fieldNames, ['impressionShare']);
        $aggregated = $this->processGetAggregated(
            $fields,
            $groupedByField,
            $campaignId,
            $adGroupId
        );
        return DB::table(self::TABLE_TEMPORARY)->select(array_merge($aggregated, $arr));
    }
}
