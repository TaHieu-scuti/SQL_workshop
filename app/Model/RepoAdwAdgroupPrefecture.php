<?php

namespace App\Model;

use App\Model\AbstractAdwPrefecture;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoAdwAdgroupPrefecture extends AbstractAdwPrefecture
{
    const PAGE_ID = 'adgroupID';

    protected $table = 'repo_adw_geo_report_cost';

    public $timestamps = false;

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
            ->get();
    }
}
