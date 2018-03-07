<?php

namespace App\Model;

use App\AbstractReportModel;
use App\Http\Controllers\AbstractReportController;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class RepoAdwAdgroupReportCost extends AbstractAdwModel
{
    const GROUPED_BY_FIELD_NAME = 'adGroup';
    const PAGE_ID = "adgroupID";
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaign',
            'tableJoin' => 'repo_adw_adgroup_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ]
    ];

    protected $table = 'repo_adw_adgroup_report_cost';
    public $timestamps = false;

    public function getAllAdwAdgroup(
        $accountId = null,
        $campaignId = null
    ) {
        $engine = session(static::SESSION_KEY_ENGINE);
        return self::select('adGroupID as adgroupID', 'adGroup as adgroupName')
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
            $convModel = new RepoAdwAdgroupReportConv();
            $queryGetConversion = $convModel->select(
                DB::raw('SUM('.$convModel->getTable().'.conversions) AS conversions, '.$groupedByField)
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
                )->groupBy($groupedByField);
            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetConversion).')AS tbl set conversions'.$key.' = tbl.conversions where '
                .self::TABLE_TEMPORARY.'.'.$groupedByField.' = tbl.'.$groupedByField
            );
        }
    }

    protected function updateTemporaryTableWithCallTracking(
        $adGainerCampaigns,
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
        $campaignIdAdgainer = $this->getCampaignIdAdgainer($clientId, $accountId, $campaignId, $adGroupId);
        $phoneNumbers = array_values(array_unique($adGainerCampaigns->pluck('phone_number')->toArray()));
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());

        $phoneTimeUseModel = new PhoneTimeUse();
        $phoneTimeUseTableName = $phoneTimeUseModel->getTable();
        $campaignModel = new Campaign();
        $campaignForPhoneTimeUse = $campaignModel->getCustomForPhoneTimeUse($campaignIdAdgainer);

        foreach ($campaignForPhoneTimeUse as $i => $campaign) {
            $customField = $this->getFieldName($campaign, 'adgroupid');

            $builder = $phoneTimeUseModel->select(
                [
                    DB::raw('count(id) AS id'),
                    $customField
                ]
            )
                ->whereRaw($customField.' NOT LIKE ""')
                ->where('source', '=', $engine)
                ->whereRaw('traffic_type = "AD"')
                ->where('phone_number', $phoneNumbers[$i])
                ->where('utm_campaign', $utmCampaignList)
                ->where(
                    function (EloquentBuilder $query) use ($startDay, $endDay, $phoneTimeUseTableName) {
                        $this->addConditonForDate($query, $phoneTimeUseTableName, $startDay, $endDay);
                    }
                )
                ->groupBy($customField);
            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($builder).') AS tbl set call'.$i.' = tbl.id where '
                .self::TABLE_TEMPORARY.'.adgroupID = tbl.'.$customField
            );
        }
    }

    public function getCampaignIdAdgainer($account_id, $accountId, $campaignId, $adGroupId)
    {
        return $this->select('campaign_id')
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId) {
                    $this->addConditonForConversionName($query, $account_id, $accountId, $campaignId, $adGroupId);
                }
            )
            ->get();
    }

    public function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yss_campaign_model = new RepoAdwAdgroupReportConv();
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
