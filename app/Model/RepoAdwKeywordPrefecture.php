<?php

namespace App\Model;

use App\Model\AbstractAdwPrefecture;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoAdwKeywordPrefecture extends AbstractAdwSubReportModel
{
    const PAGE_ID = 'keywordID';

    protected $table = 'repo_adw_geo_report_cost';

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
        $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare', 'matchType']);

        $fieldNames = $this->checkConditionFieldName($fieldNames);
        $this->conversionPoints = $this->getAllDistinctConversionNames(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            static::PAGE_ID
        );

        $campaignIDs = array_unique($this->conversionPoints->pluck('campaignID')->toArray());
        $adGroupIds = array_unique($this->conversionPoints->pluck('adgroupID')->toArray());
        $campaigns = new Campaign;
        $this->adGainerCampaigns = $campaigns->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'adw',
            $campaignIDs,
            static::PAGE_ID,
            null,
            $adGroupIds
        );
        array_unshift($fieldNames, 'region');

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
                ['prefecture', 'adgroupID', 'keyword']
            )
        );
        $columns = array_keys($this->updateFieldNames($columns));
        DB::insert('INSERT into '.self::TABLE_TEMPORARY.' ('.implode(', ', $columns).') '
            . $this->getBindingSql($builder));

        DB::update('update '.self::TABLE_TEMPORARY.', (SELECT Name, CriteriaID from criteria) as 
            `criteria` SET prefecture = criteria.name WHERE temporary_table.region = criteria.CriteriaID');

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
                $engine,
                $clientId,
                $accountId,
                $campaignId,
                $adGroupId,
                $adReportId,
                $keywordId
            );
        }

        $fields = $this->unsetColumns($fieldNames, ['region']);
        $aggregated = $this->processGetAggregated(
            $fields,
            $groupedByField,
            $campaignId,
            $adGroupId
        );

        return DB::table(self::TABLE_TEMPORARY)
            ->select($aggregated)
            ->groupby($groupedByField)
            ->orderBy($columnSort, $sort);
    }

    protected function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $adwPrefectureConvModel = new RepoAdwGeoReportConv;
        $aggregation = $this->getAggregatedConversionName($column);
        $aggregation[] = 'region';
        return $adwPrefectureConvModel->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->where('network', '=', "SEARCH")
            ->get();
    }

    protected function updateTemporaryTableWithCallTracking(
        $adGainerCampaigns,
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
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());
        $phoneNumbers = array_values(array_unique($adGainerCampaigns->pluck('phone_number')->toArray()));

        $phoneTimeUseModel = new PhoneTimeUse();
        $phoneTimeUseTableName = $phoneTimeUseModel->getTable();

        foreach ($phoneNumbers as $i => $phoneNumber) {
            $builder = $phoneTimeUseModel->select(
                [
                    DB::raw('count(id) AS id'),
                    'visitor_city_state'
                ]
            )
            ->where('source', '=', $engine)
            ->whereRaw('traffic_type = "AD"')
            ->where('phone_number', $phoneNumber)
            ->whereIn('utm_campaign', $utmCampaignList)
            ->where(
                function (EloquentBuilder $query) use ($startDay, $endDay, $phoneTimeUseTableName) {
                    $this->addConditionForDate($query, $phoneTimeUseTableName, $startDay, $endDay);
                }
            )
            ->groupBy('visitor_city_state');

            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($builder).') AS tbl set call'.$i.' = tbl.id where '
                . 'tbl.visitor_city_state LIKE CONCAT("%",'.self::TABLE_TEMPORARY.'.prefecture," (Japan)")'
            );
        }
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
        $adGroupIds = array_unique($conversionPoints->pluck('adgroupID')->toArray());
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoAdwGeoReportConv;
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_adw_geo_report_conv.conversions) AS conversions, region')
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
                )->whereIn('adGroupId', $adGroupIds)
                ->groupBy('region');
            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetConversion).')AS tbl set conversions'.$key.' = tbl.conversions where '
                .self::TABLE_TEMPORARY.'.region'.' = tbl.region'
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
        $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare', 'matchType']);

        $aggregated = $this->processGetAggregated(
            $fieldNames,
            $groupedByField,
            $campaignId,
            $adGroupId
        );

        return DB::table(self::TABLE_TEMPORARY)->select($aggregated);
    }
}
