<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class RepoYssAdgroupPrefecture extends AbstractYssPrefecture
{
    protected $table = 'repo_yss_prefecture_report_cost';

    const PAGE_ID = 'adgroupID';

    public $timestamps = false;

    protected function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yssCampaignConvModel = new RepoYssPrefectureReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        return $yssCampaignConvModel->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }
}
