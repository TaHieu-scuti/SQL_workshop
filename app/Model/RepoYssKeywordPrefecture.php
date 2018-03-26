<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoYssKeywordPrefecture extends AbstractYssPrefecture
{
    protected $table = 'repo_yss_prefecture_report_cost';

    const PAGE_ID = 'keywordID';

    public $timestamps = false;

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
        $adgroupIDs = array_unique($conversionPoints->pluck('adgroupID')->toArray());
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoYssPrefectureReportConv();
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_yss_prefecture_report_conv.conversions) AS conversions, '.$groupedByField)
            )->where('conversionName', $conversionName)
                ->where('adgroupID', $adgroupIDs)
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
