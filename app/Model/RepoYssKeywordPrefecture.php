<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class RepoYssKeywordPrefecture extends AbstractYssPrefecture
{
    protected $table = 'repo_yss_prefecture_report_cost';
    const PAGE_ID = 'keywordID';

    public $timestamps = false;

    protected function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yssPrefectureConvModel = new RepoYssPrefectureReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        return $yssPrefectureConvModel->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }

    protected function getAggregatedConversionName($column)
    {
        $arraySelect = ['conversionName'];

        array_unshift($arraySelect, 'campaignID', 'adgroupID');

        return $arraySelect;
    }
}
