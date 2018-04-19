<?php

namespace App\Model;

use App\Model\AbstractAdwSubReportModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoAdwAdTimezone extends AbstractAdwSubReportModel
{
    const PAGE_ID = "adID";

    public $timestamps = false;

    protected $table = 'repo_adw_ad_report_cost';

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
        $fieldNames = $this->unsetColumns($fieldNames, ['adType']);
        $this->conversionPoints = $this->getAllDistinctConversionNames(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            static::PAGE_ID
        );
        $campaignIDs = array_unique($this->conversionPoints->pluck('campaignID')->toArray());
        $adIDs = array_unique($this->conversionPoints->pluck('adID')->toArray());
        $campaignModel = new Campaign;
        $this->adGainerCampaigns = $campaignModel->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'adw',
            $campaignIDs,
            static::PAGE_ID,
            $adIDs
        );
        $fieldNames = $this->checkConditionFieldName($fieldNames);
        $builder = parent::getBuilderForGetDataForTable(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $columnSort,
            $sort,
            $groupedByField,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );
        if ($this->isConv || $this->isCallTracking) {
            $this->createTemporaryTable(
                $fieldNames,
                $this->isConv,
                $this->isCallTracking,
                $this->conversionPoints,
                $this->adGainerCampaigns
            );
            $columns = $this->unsetColumns(
                $fieldNames,
                array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING, ['campaign', 'adGrop', 'ad'])
            );
            $columns = array_keys($this->updateFieldNames($columns));
            DB::insert('INSERT into '.self::TABLE_TEMPORARY.' ('.implode(', ', $columns).') '
                . $this->getBindingSql($builder));
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
                    $endDay
                );
            }
            $aggregated = $this->processGetAggregated(
                $fieldNames,
                $groupedByField,
                $campaignId,
                $adGroupId
            );
            $builder = DB::table(self::TABLE_TEMPORARY)
                ->select($aggregated)
                ->groupby($groupedByField)
                ->orderBy($columnSort, $sort);
        }
        return $builder;
    }

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $adwAdConvModel = new RepoAdwAdReportConv;
        $aggregation = $this->getAggregatedConversionName($column);
        $aggregation[] = DB::raw('HOUR(day) as hourOfDay');

        return $adwAdConvModel->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->where('network', 'CONTENT')
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
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoAdwAdReportConv();
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_adw_ad_report_conv.conversions) AS conversions,
                    hour(repo_adw_ad_report_conv.day) as hourOfDay')
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
                )->groupBy($groupedByField);
            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetConversion).')AS tbl set conversions'.$key.' = tbl.conversions where '
                .self::TABLE_TEMPORARY.'.'.$groupedByField.' = tbl.hourOfDay'
            );
        }
    }

    protected function updateTemporaryTableWithCallTracking(
        $adGainerCampaigns,
        $groupedByField,
        $startDay,
        $endDay
    ) {
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());
        $phoneList = array_unique($adGainerCampaigns->pluck('phone_number')->toArray());

        foreach ($phoneList as $i => $phoneNumber) {
            $phoneTimeUseModel = new PhoneTimeUse();
            $tableName = $phoneTimeUseModel->getTable();
            $queryGetCallTracking = $phoneTimeUseModel->select(
                DB::raw("HOUR(`time_of_call`) AS hourOfDay, COUNT(`id`) AS id")
            )->where('phone_number', $phoneNumber)
                ->where('source', 'adw')
                ->where(
                    function (EloquentBuilder $query) use ($startDay, $tableName, $endDay) {
                        $this->addConditionForDate($query, $tableName, $startDay, $endDay);
                    }
                )->whereIn('utm_campaign', $utmCampaignList)
                ->groupBy($groupedByField);

            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetCallTracking).') AS tbl set call'.$i.' = tbl.id where '
                .self::TABLE_TEMPORARY.'.hourOfDay = tbl.hourOfDay'
            );
        }
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
        $fieldNames = $this->unsetColumns($fieldNames, ['adType']);
        $fieldNames = $this->checkConditionFieldName($fieldNames);

        $builder = parent::getBuilderForCalculateData(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $groupedByField,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );

        if ($this->isConv || $this->isCallTracking) {
            $aggregated = $this->processGetAggregated(
                $fieldNames,
                $groupedByField,
                $campaignId,
                $adGroupId
            );

            $builder = DB::table(self::TABLE_TEMPORARY)->select(
                $aggregated
            );
        }

        return $builder;
    }
}
