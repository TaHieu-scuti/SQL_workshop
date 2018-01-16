<?php

namespace App\Model;

use App\Model\AbstractYssReportModel;
use App\Http\Controllers\AbstractReportController;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoYssCampaignReportCost extends AbstractYssReportModel
{
    // constant
    const GROUPED_BY_FIELD_NAME = 'campaignName';
    const PAGE_ID = 'campaignID';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'repo_yss_campaign_report_cost';

    protected $casts = [
        'call_cv' => 'integer',
        'call_cvr' => 'integer',
        'call_cpa' => 'integer',
        'web_cv' => 'integer',
        'web_cvr' => 'integer',
        'web_cpa' => 'integer'
    ];

    private function addJoinsForConversionPoints(
        EloquentBuilder $builder,
        $conversionPoints
    ) {
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        $campaignIDs = array_unique($conversionPoints->pluck('campaignID')->toArray());
        $campaignReportConvTableName = (new RepoYssCampaignReportConv)->getTable();
        foreach ($conversionNames as $i => $conversionName) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $campaignReportConvTableName . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $conversionName, $campaignIDs) {
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
                            $joinAlias . '.campaignID',
                            $campaignIDs
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
        $joinTableName = (new RepoPhoneTimeUse)->getTable();
        foreach ($adGainerCampaigns as $i => $campaign) {
            $joinAlias = 'call' . $i;
            $builder->leftJoin(
                $joinTableName . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $campaign) {
                    $join->on(
                        $this->table . '.account_id',
                        '=',
                        $joinAlias . '.account_id'
                    )->on(
                        $this->table . '.campaign_id',
                        '=',
                        $joinAlias . '.campaign_id'
                    )->on(
                        $this->table . '.campaignID',
                        '=',
                        $joinAlias . '.utm_campaign'
                    )->on(
                        $this->table . '.day',
                        '=',
                        DB::raw("STR_TO_DATE(`" . $joinAlias . "`.`time_of_call`, '%Y-%m-%d')")
                    )->where(
                        $joinAlias . '.utm_campaign',
                        '=',
                        $campaign->utm_campaign
                    )->whereRaw(
                        '`' . $joinAlias . "`.`phone_number` = '" . $campaign->phone_number . "'"
                    )->where(
                        $joinAlias . '.source',
                        '=',
                        'yss'
                    );
                }
            );
        }
    }

    protected function addJoin(EloquentBuilder $builder, $conversionPoints = null, $adGainerCampaigns = null)
    {
        $this->addJoinsForConversionPoints($builder, $conversionPoints);
        $this->addJoinsForCallConversions($builder, $adGainerCampaigns);
    }

    public function getAllCampaign(
        $accountId = null
    ) {
        $engine = session(static::SESSION_KEY_ENGINE);
        $arrCampaigns = [];
        $campaigns = null;
        $arrCampaigns['all'] = 'All Campaigns';
        if (session(AbstractReportController::SESSION_KEY_ENGINE) === 'yss') {
            $campaigns = self::select('campaignID', 'campaignName')
                ->where(
                    function ($query) use ($accountId, $engine) {
                        $this->addQueryConditions(
                            $query,
                            session(AbstractReportController::SESSION_KEY_CLIENT_ID),
                            $engine,
                            $accountId
                        );
                    }
                )
                ->groupBy('campaignID', 'campaignName')->get();
        } elseif (session(AbstractReportController::SESSION_KEY_ENGINE) === 'adw') {
            $modelAdwCampaign = new RepoAdwCampaignReportCost;
            $campaigns = $modelAdwCampaign->getAllAdwCampaign(
                $accountId = null
            );
        } elseif (session(AbstractReportController::SESSION_KEY_ENGINE) === 'ydn') {
            $modelYdnCampaign = new RepoYdnCampaignReport;
            $campaigns = $modelYdnCampaign->getAllYdnCampaign(
                $accountId = null
            );
        }
        if (!is_null($campaigns)) {
            foreach ($campaigns as $key => $campaign) {
                $arrCampaigns[$campaign->campaignID] = $campaign->campaignName;
            }
        }

        return $arrCampaigns;
    }

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yss_campaign_model = new RepoYssCampaignReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        $conversionPoints = $yss_campaign_model->select($aggregation)
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
