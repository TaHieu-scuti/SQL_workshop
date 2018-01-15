<?php

namespace App\Model;

use App\Model\AbstractYssReportModel;
use App\Http\Controllers\AbstractReportController;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoYssAdgroupReportCost extends AbstractYssReportModel
{
    // constant
    const GROUPED_BY_FIELD_NAME = 'adgroupName';
    const PAGE_ID = "adgroupID";
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaignName',
            'tableJoin' => 'repo_yss_campaign_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ]
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'repo_yss_adgroup_report_cost';

    public function getAllAdgroup(
        $accountId = null,
        $campaignId = null
    ) {
        $engine = session(static::SESSION_KEY_ENGINE);
        $arrAdgroups = [];
        $adgroups = null;
        $arrAdgroups['all'] = 'All Adgroup';
        if (session(AbstractReportController::SESSION_KEY_ENGINE) === 'yss') {
            $adgroups = self::select('adgroupID', 'adgroupName')
                ->where(
                    function ($query) use ($accountId, $campaignId, $engine) {
                        $this->addQueryConditions(
                            $query,
                            session(AbstractReportController::SESSION_KEY_CLIENT_ID),
                            $engine,
                            $accountId,
                            $campaignId
                        );
                    }
                )
                ->groupBy('adgroupID', 'adgroupName')->get();
        } elseif (session(AbstractReportController::SESSION_KEY_ENGINE) === 'adw') {
            $modelAdwAdgroup = new RepoAdwAdgroupReportCost();
            $adgroups = $modelAdwAdgroup->getAllAdwAdgroup(
                $accountId = null,
                $campaignId = null
            );
        } elseif (session(AbstractReportController::SESSION_KEY_ENGINE) === 'ydn') {
            $modelYdnAdgroup = new RepoYdnAdgroupReport();
            $adgroups = $modelYdnAdgroup->getAllYdnAdgroup(
                $accountId = null,
                $campaignId = null
            );
        }

        if (!is_null($adgroups)) {
            foreach ($adgroups as $key => $adgroup) {
                $arrAdgroups[$adgroup->adgroupID] = $adgroup->adgroupName;
            }
        }

        return $arrAdgroups;
    }

    private function addJoinsForConversionPoints(
        EloquentBuilder $builder,
        $conversionPoints
    ) {
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        $adgroupIDs = array_unique($conversionPoints->pluck('adgroupID')->toArray());
        $campaignReportConvTableName = (new RepoYssAdgroupReportConv())->getTable();
        foreach ($conversionNames as $i => $conversionName) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $campaignReportConvTableName . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $conversionName, $adgroupIDs) {
                    $join->on(
                        $this->table . '.account_id',
                        '=',
                        $joinAlias . '.account_id'
                    )
                        ->on(
                            $this->table . '.accountId',
                            '=',
                            $joinAlias . '.accountId'
                        )->on(
                            $this->table . '.day',
                            '=',
                            $joinAlias . '.day'
                        )->on(
                            $this->table . '.campaignID',
                            '=',
                            $joinAlias . '.campaignID'
                        )->whereIn(
                            $joinAlias . '.adgroupID',
                            $adgroupIDs
                        )->where(
                            $joinAlias . '.conversionName',
                            '=',
                            $conversionName
                        );
                }
            );
        }
    }

    private function addJoinsForCallConversions(EloquentBuilder $builder, $adGainerCampaigns)
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

    protected function addJoin(EloquentBuilder $builder, $conversionPoints = null, $adGainerCampaigns = null)
    {
        $this->addJoinsForConversionPoints($builder, $conversionPoints);
        $this->addJoinsForCallConversions($builder, $adGainerCampaigns);
    }

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yss_campaign_model = new RepoYssAdgroupReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        return $yss_campaign_model->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }
}
