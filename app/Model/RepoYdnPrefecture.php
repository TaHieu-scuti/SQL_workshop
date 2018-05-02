<?php
namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;

use App\Model\AbstractYdnReportModel;
use App\AbstractReportModel;

class RepoYdnPrefecture extends AbstractYdnReportModel
{
    protected $table = 'repo_ydn_reports';

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
        $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare']);
        $this->conversionPoints = $this->getAllDistinctConversionNames(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            $adReportId,
            static::PAGE_ID
        );
        $campaignIDs = array_unique($this->conversionPoints->pluck('campaignID')->toArray());
        $adIDs = array_unique($this->conversionPoints->pluck('adID')->toArray());
        $campaigns = new Campaign;
        $this->adGainerCampaigns = $campaigns->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'ydn',
            $campaignIDs,
            static::PAGE_ID,
            $adIDs
        );
        $fieldNames = $this->checkConditionFieldName($fieldNames);
        $builder = AbstractReportModel::getBuilderForGetDataForTable(
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
        if ($this->isConv === true || $this->isCallTracking === true) {
            $columns = $fieldNames;
            if (($key = array_search(self::PAGE_ID, $columns)) !== false) {
                unset($columns[$key]);
            }
            if (($key = array_search('campaignName', $columns)) !== false) {
                unset($columns[$key]);
            }

            $this->createTemporaryTable(
                $columns,
                $this->isConv,
                $this->isCallTracking,
                $this->conversionPoints,
                $this->adGainerCampaigns
            );

            $columns = $this->unsetColumns($columns, array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING));

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
            $aggregated = $this->processGetAggregated($fieldNames, $groupedByField, $campaignId, $adGroupId);
            $allColumns = $this->getAllColumns(DB::table(self::TABLE_TEMPORARY)->select($aggregated)->columns);
            $columnSort = $this->getSortColumn($keyPrefix, $allColumns, $columnSort);

            $builder = DB::table(self::TABLE_TEMPORARY)
            ->select($aggregated)
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
        $conversionNames = array_values(array_unique($conversionPoints->pluck('conversionName')->toArray()));
        foreach ($conversionNames as $key => $conversionName) {
            $queryGetConversion = $this->select(
                DB::raw('SUM(repo_ydn_reports.conversions) AS conversions, '.$groupedByField)
            )->where('conversionName', $conversionName)
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

        $phoneTimeUseModel = new PhoneTimeUse;
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
                ->whereIn('phone_number', $phoneNumber)
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

    public function getCampaignIdAdgainer($account_id, $accountId, $campaignId, $adGroupId, $utmCampaignList)
    {
        return $this->select('campaign_id')
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->whereIn('campaignID', $utmCampaignList)
            ->get();
    }
}
