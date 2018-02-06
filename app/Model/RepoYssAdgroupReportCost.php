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
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoYssAdgroupReportConv();
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_yss_adgroup_report_conv.conversions) AS conversions, '.$groupedByField)
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
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());
        $phoneList = array_unique($adGainerCampaigns->pluck('phone_number')->toArray());
        if ($groupedByField === 'campaignName') {
            $groupedByField = 'utm_campaign';
        }
        foreach ($phoneList as $i => $phoneNumber) {
            $campaignModel = new Campaign;
            $repoPhoneTimeUseModel = new RepoPhoneTimeUse;
            $joinAlias = 'call' . $i;
            $queryGetConversion = $campaignModel->select(DB::raw($groupedByField .", COUNT(`id`) AS id"))->leftJoin(
                $repoPhoneTimeUseModel->getTable() . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($phoneNumber) {
                    $join->where('phone_number', $phoneNumber)
                    ->where('source', 'yss')
                    ->where('traffic_type', 'AD')
                    ->where('camp_custom1', 'adgroupid')
                    ->orWhere('camp_custom2', 'adgroupid')
                    ->orWhere('camp_custom3', 'adgroupid')
                    ->orWhere('camp_custom4', 'adgroupid')
                    ->orWhere('camp_custom5', 'adgroupid')
                    ->orWhere('camp_custom6', 'adgroupid')
                    ->orWhere('camp_custom7', 'adgroupid')
                    ->orWhere('camp_custom8', 'adgroupid')
                    ->orWhere('camp_custom9', 'adgroupid')
                    ->orWhere('camp_custom10', 'adgroupid');
                }
            )->where(
                function (EloquentBuilder $query) use ($startDay, $tableName, $endDay) {
                    $this->addConditonForDate($query, $tableName, $startDay, $endDay);
                }
            )->whereIn('utm_campaign', $utmCampaignList)
            ->groupBy($groupedByField);;

            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetCallTracking).') AS tbl set call'.$i.' = tbl.id where '
                .self::TABLE_TEMPORARY.'.campaignID = tbl.'.$groupedByField
            );
        }
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
