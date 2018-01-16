<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use App\Model\AbstractYssReportModel;

use Auth;

class RepoYssKeywordReportCost extends AbstractYssReportModel
{
    const PAGE_ID = "keywordID";
    const GROUPED_BY_FIELD_NAME = 'keyword';
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaignName',
            'tableJoin' => 'repo_yss_campaign_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ],
        [
            'columnName' => 'adgroupName',
            'tableJoin' => 'repo_yss_adgroup_report_cost',
            'columnId' => 'adgroupID',
            'aliasId' => 'adgroupID',
            'aliasName' => 'adgroupName',
        ]
    ];
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'repo_yss_keyword_report_cost';

    protected function addJoin(EloquentBuilder $builder, $conversionPoints = null, $adGainerCampaigns = null)
    {
        $this->addJoinsForConversionPoints($builder, $conversionPoints);
        $this->addJoinsForCallConversions($builder, $adGainerCampaigns, $conversionPoints);
    }

    private function addJoinsForConversionPoints(
        EloquentBuilder $builder,
        $conversionPoints
    ) {
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        $keywordReportConvTableName = (new RepoYssKeywordReportConv)->getTable();
        foreach ($conversionNames as $i => $conversionName) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $keywordReportConvTableName . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $conversionName) {
                    $join->where(
                        $joinAlias . '.conversionName',
                        '=',
                        $conversionName
                    )
                        ->on(
                            $this->table . '.day',
                            '=',
                            $joinAlias . '.day'
                        )->on(
                            $this->table . '.keywordID',
                            '=',
                            $joinAlias . '.keywordID'
                        )->on(
                            $this->table . '.adgroupID',
                            '=',
                            $joinAlias . '.adgroupID'
                        )->on(
                            $this->table . '.campaignID',
                            '=',
                            $joinAlias . '.campaignID'
                        )->on(
                            $this->table . '.accountid',
                            '=',
                            $joinAlias . '.accountid'
                        );
                }
            );
        }
    }

    private function addJoinsForCallConversions(EloquentBuilder $builder, $adGainerCampaigns, $conversionPoints)
    {
        foreach ($adGainerCampaigns as $i => $campaign) {
            $joinAlias = 'call' . $i;
            $builder->leftJoin(
                DB::raw('(`phone_time_use` AS '.$joinAlias.', `campaigns` AS '.$joinAlias.'_campaigns)'),
                function (JoinClause $join) use ($joinAlias) {
                    $this->addJoinConditions($join, $joinAlias);
                }
            );
        }
    }

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yss_keyword_conv_model = new RepoYssKeywordReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        $conversionPoints = $yss_keyword_conv_model->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
        return $conversionPoints;
    }
}
