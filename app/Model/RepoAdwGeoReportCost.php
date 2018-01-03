<?php

namespace App\Model;

use App\Model\AbstractAdwModel;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use DB;

class RepoAdwGeoReportCost extends AbstractAdwModel
{
    // const GROUPED_BY_FIELD_NAME = 'prefecture';
    // const ADW_JOIN_TABLE_NAME = 'criteria';
    // const ADW_FIELDS_MAP = [
    //     //'alias' => 'columns'
    //     'impressions' => 'impressions',
    //     'clicks' => 'clicks',
    //     'cost' => 'cost',
    //     'ctr' => 'ctr',
    //     'averageCpc' => 'avgCPC',
    //     'averagePosition' => 'avgPosition',
    //     'campaignName' => 'campaign',
    //     'adgroupName' => 'adGroup'
    // ];

    protected $table = 'repo_adw_geo_report_cost';
    public $timestamps = false;

    protected function addJoin(EloquentBuilder $builder)
    {
        parent::addJoin($builder);
        $builder->join('criteria',
            function (JoinClause $join) {
                $this->addCriteriaJoinConditions($join);
            }
        );
    }

    protected function addJoinConditions(JoinClause $join)
    {
        $join->on('phone_time_use.account_id', '=', $this->table . '.account_id')
            ->on('phone_time_use.campaign_id', '=', $this->table . '.campaign_id')
            ->on('phone_time_use.utm_campaign', '=', $this->table . '.campaignID')
            ->where('phone_time_use.source', '=', 'adw')
            ->where('phone_time_use.traffic_type', '=', 'AD')
            ->where('phone_time_use.visitor_city_state', 'like',
                DB::raw("CONCAT('%', 'criteria.Name', ' (Japan)')"));
    }

    private function addCriteriaJoinConditions(JoinClause $join)
    {
        $join->on('criteria.CriteriaID', '=', $this->table. '.region');
    }

    // public function getDataForTable(
    //     $engine,
    //     array $fieldNames,
    //     $accountStatus,
    //     $startDay,
    //     $endDay,
    //     $pagination,
    //     $columnSort,
    //     $sort,
    //     $groupedByField,
    //     $agencyId = null,
    //     $accountId = null,
    //     $clientId = null,
    //     $campaignId = null,
    //     $adGroupId = null,
    //     $adReportId = null,
    //     $keywordId = null
    // ) {
    //     $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare']);
    //     $adwAggregations = $this->getBuilderForGetDataForTable($fieldNames);
    //     dd('hellooo');
        // $paginatedData = RepoAdwGeoReportCost::select($adwAggregations)
        //     ->join(
        //         self::ADW_JOIN_TABLE_NAME,
        //         'repo_adw_geo_report_cost.region',
        //         '=',
        //         self::ADW_JOIN_TABLE_NAME . '.CriteriaID'
        //     )
        //     ->where(
        //         function (Builder $query) use ($startDay, $endDay) {
        //             $this->addTimeRangeCondition($startDay, $endDay, $query);
        //         }
        //     )->where(
        //         function (Builder $query) use ($clientId) {
        //             $query->where('repo_adw_geo_report_cost.account_id', '=', $clientId);
        //         }
        //     )
        //     ->groupBy('criteria.Name')
        //     ->orderBy($columnSort, $sort);
        // if ($accountStatus == self::HIDE_ZERO_STATUS) {
        //     $paginatedData = $paginatedData->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
        //         ->paginate($pagination);
        // } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
        //     $paginatedData = $paginatedData->paginate($pagination);
        // }
        // return $paginatedData;
    // }

    // public function calculateData(
    //     $engine,
    //     $fieldNames,
    //     $accountStatus,
    //     $startDay,
    //     $endDay,
    //     $groupedByField,
    //     $agencyId = null,
    //     $accountId = null,
    //     $clientId = null,
    //     $campaignId = null,
    //     $adGroupId = null,
    //     $adReportId = null,
    //     $keywordId = null
    // ) {
    //     $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare']);
    //     $fieldNames = $this->unsetColumns($fieldNames, [$groupedByField]);
    //     $adwAggregations = $this->getAggregatedForPrefectureGoogle($fieldNames);
    //     return RepoAdwGeoReportCost::select($adwAggregations)
    //         ->join(
    //             self::ADW_JOIN_TABLE_NAME,
    //             'repo_adw_geo_report_cost.region',
    //             '=',
    //             self::ADW_JOIN_TABLE_NAME . '.CriteriaID'
    //         )
    //         ->where(
    //             function (Builder $query) use ($startDay, $endDay) {
    //                 $this->addTimeRangeCondition($startDay, $endDay, $query);
    //             }
    //         )->where(
    //             function (Builder $query) use ($clientId) {
    //                 $query->where('repo_adw_geo_report_cost.account_id', '=', $clientId);
    //             }
    //         )
    //         ->first();
    // }

    // public function calculateSummaryData(
    //     $engine,
    //     $fieldNames,
    //     $accountStatus,
    //     $startDay,
    //     $endDay,
    //     $agencyId = null,
    //     $accountId = null,
    //     $clientId = null,
    //     $campaignId = null,
    //     $adGroupId = null,
    //     $adReportId = null,
    //     $keywordId = null
    // ) {
    //     $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare']);
    //     $adwAggregations = $this->getAggregatedForPrefectureGoogle($fieldNames);
    //     return RepoAdwGeoReportCost::select($adwAggregations)
    //         ->join(
    //             self::ADW_JOIN_TABLE_NAME,
    //             'repo_adw_geo_report_cost.region',
    //             '=',
    //             self::ADW_JOIN_TABLE_NAME . '.CriteriaID'
    //         )
    //         ->where(
    //             function (Builder $query) use ($startDay, $endDay) {
    //                 $this->addTimeRangeCondition($startDay, $endDay, $query);
    //             }
    //         )->where(
    //             function (Builder $query) use ($clientId) {
    //                 $query->where('repo_adw_geo_report_cost.account_id', '=', $clientId);
    //             }
    //         )
    //         ->first();
    // }

}
