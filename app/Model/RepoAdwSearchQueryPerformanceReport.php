<?php

namespace App\Model;

use App\Model\AbstractAdwModel;
use App\Model\RepoAdwSearchQueryPerformanceReportConv;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class RepoAdwSearchQueryPerformanceReport extends AbstractAdwModel
{
    const PAGE_ID = 'keywordID';
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
            );
            ->get();
    }
}
