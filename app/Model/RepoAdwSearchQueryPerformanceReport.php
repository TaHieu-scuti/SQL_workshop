<?php

namespace App\Model;

use App\Model\AbstractAdwModel;
use App\Model\RepoAdwSearchQueryPerformanceReportConv;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoAdwSearchQueryPerformanceReport extends AbstractAdwModel
{
    const PAGE_ID = 'keywordID';
    const GROUPED_BY_FIELD_NAME = 'keyword';

    protected $table = 'repo_adw_search_query_performance_report_cost';

    public $timestamps = false;

    protected $isSearchQueryReport = true;

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $adw_search_query_conv_model = new RepoAdwSearchQueryPerformanceReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        return $adw_search_query_conv_model->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
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
        $conversionNames = array_values(array_unique($conversionPoints->pluck('conversionName')->toArray()));
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoAdwSearchQueryPerformanceReportConv;
            $queryGetConversion = $convModel->select(
                array_merge(
                    [DB::raw('SUM(repo_adw_search_query_performance_report_conv.conversions) AS conversions')],
                    $this->groupBy
                )
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
                )->groupBy($this->groupBy);
            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetConversion).')AS tbl set conversions'.$key.' = tbl.conversions where '
                .self::TABLE_TEMPORARY.'.'.$groupedByField.' = tbl.'.$groupedByField. ' AND '
                .self::TABLE_TEMPORARY.'.adGroupID = tbl.adGroupID'
            );
        }
    }
}
