<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoAdwAdgroupDayOfWeek extends AbstractAdwSubReportModel
{
    const PAGE_ID = "adgroupID";

    protected $table = 'repo_adw_adgroup_report_cost';

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
        $this->conversionPoints = $this->getAllDistinctConversionNames(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            static::PAGE_ID
        );

        $campaignIDs = array_unique($this->conversionPoints->pluck('campaignID')->toArray());
        $campaignModel = new Campaign;
        $this->adGainerCampaigns = $campaignModel->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'adw',
            $campaignIDs,,
            static::PAGE_ID
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
                array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING, ['adGroup'])
            );
            $columns = array_keys($this->updateFieldNames($columns));
            DB::insert('INSERT into '.self::TABLE_TEMPORARY.' ('.implode(', ', $columns).') '
                . $this->getBindingSql($builder));
            if ($this->isCallTracking) {
                $this->updateTemporaryTableWithCallTracking(
                    $this->adGainerCampaigns,
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
            $builder = DB::table(self::TABLE_TEMPORARY)
                ->select(array_merge($aggregated, $arr))
                ->groupby($groupedByField)
                ->orderBy($columnSort, $sort);
        }
        return $builder;
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
            $convModel = new RepoAdwAdgroupReportConv;
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_adw_adgroup_report_conv.conversions) AS conversions, '.$groupedByField)
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
        $endDay
    ) {
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());
        $phoneList = array_unique($adGainerCampaigns->pluck('phone_number')->toArray());

        foreach ($phoneList as $i => $phoneNumber) {
            $repoPhoneTimeUseModel = new RepoPhoneTimeUse();
            $tableName = $repoPhoneTimeUseModel->getTable();
            $queryGetCallTracking = $repoPhoneTimeUseModel->select(
                DB::raw("DAYNAME(`time_of_call`) AS dayOfWeek, COUNT(`id`) AS id")
            )->where('phone_number', $phoneNumber)
                ->where('source', 'adw')
                ->whereRaw('traffic_type = "AD"')
                ->where(
                    function (EloquentBuilder $query) use ($startDay, $tableName, $endDay) {
                        $this->addConditionForDate($query, $tableName, $startDay, $endDay);
                    }
                )->whereIn('utm_campaign', $utmCampaignList)
                ->groupBy($groupedByField);

            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetCallTracking).') AS tbl set call'.$i.' = tbl.id where '
                .self::TABLE_TEMPORARY.'.dayOfWeek = tbl.dayOfWeek'
            );
        }
    }

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $adwAdgroupConvModel = new RepoAdwAdgroupReportConv;
        $aggregation = $this->getAggregatedConversionName($column);
        $aggregation[] = 'dayOfWeek';
        return $adwAdgroupConvModel->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
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

            $builder = DB::table(self::TABLE_TEMPORARY)->select(
                array_merge(
                    $aggregated,
                    $arr
                )
            );
        }

        return $builder;
    }
}
