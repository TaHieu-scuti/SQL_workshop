<?php

namespace App\Model;

use App\Model\AbstractAdwSubReportModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoAdwKeywordTimezone extends AbstractAdwSubReportModel
{
    const PAGE_ID = "keywordID";

    public $timestamps = false;

    protected $table = 'repo_adw_keywords_report_cost';

    const FIELDS_NEED_UNSET_FOR_ADW_KEYWORD_TIME_ZONE = [
        'adGroup',
        'adgroupID',
        'campaignID',
        'campaign',
        'keyword',
        'keywordID'
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
        $keyPrefix,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $fieldNames = $this->unsetColumns($fieldNames, ['matchType']);
        $this->conversionPoints = $this->getAllDistinctConversionNames(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            static::PAGE_ID
        );
        $campaignIDs = array_unique($this->conversionPoints->pluck('campaignID')->toArray());
        $adGroupIds = array_unique($this->conversionPoints->pluck('adgroupID')->toArray());
        $campaignModel = new Campaign;
        $this->adGainerCampaigns = $campaignModel->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'adw',
            $campaignIDs,
            static::PAGE_ID,
            null,
            $adGroupIds
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
            $keyPrefix,
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
                array_merge(
                    self::UNSET_COLUMNS,
                    self::FIELDS_CALL_TRACKING,
                    self::FIELDS_NEED_UNSET_FOR_ADW_KEYWORD_TIME_ZONE
                )
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
            $allColumns = $this->getAllColumns(
                DB::table(self::TABLE_TEMPORARY)->select(array_merge($aggregated, $arr))->columns
            );
            $columnSort = $this->getSortColumn($keyPrefix, $allColumns, $columnSort);

            $builder = DB::table(self::TABLE_TEMPORARY)
                ->select(array_merge($aggregated, $arr))
                ->groupby($groupedByField)
                ->orderBy($columnSort, $sort);
        }
        return $builder;
    }

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $adwKeywordConvModel = new RepoAdwKeywordReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        $aggregation[] = DB::raw('HOUR(day) as hourOfDay');

        return $adwKeywordConvModel->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->where('network', 'SEARCH')
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
            $convModel = new RepoAdwKeywordReportConv();
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_adw_keywords_report_conv.conversions) AS conversions, HOUR(day) AS ' .$groupedByField)
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
        $phoneNumbers = array_values(array_unique($adGainerCampaigns->pluck('phone_number')->toArray()));
        $phoneTimeUseModel = new PhoneTimeUse();
        $tableName = $phoneTimeUseModel->getTable();

        foreach ($phoneNumbers as $i => $phoneNumber) {
            $queryGetCallTracking = $phoneTimeUseModel->select(
                DB::raw("HOUR(`time_of_call`) AS hourOfDay, COUNT(`id`) AS id")
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
        $fieldNames = $this->unsetColumns($fieldNames, ['matchType']);
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

            $builder = DB::table(self::TABLE_TEMPORARY)->select($aggregated);
        }

        return $builder;
    }
}
