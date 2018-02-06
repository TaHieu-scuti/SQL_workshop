<?php

namespace App\Model;

use App\Http\Controllers\AbstractReportController;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;

class RepoYdnCampaignReport extends AbstractYdnReportModel
{
    const GROUPED_BY_FIELD_NAME = 'campaignName';
    const PAGE_ID = 'campaignID';

    protected $table = 'repo_ydn_reports';
    public $timestamps = false;

    private function addJoinsForConversionPoints(
        EloquentBuilder $builder,
        $conversionPoints
    ) {
        $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
        $campaignIDs = array_unique($conversionPoints->pluck('campaignID')->toArray());
        foreach ($conversionNames as $i => $conversionName) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $this->table . ' AS ' . $joinAlias,
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
                        'ydn'
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

    public function getAllYdnCampaign(
        $accountId = null
    ) {
        $engine = session(static::SESSION_KEY_ENGINE);
        return self::select('campaignID', 'campaignName')
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
        $campaignIDs = array_unique($conversionPoints->pluck('campaignID')->toArray());
        foreach ($conversionNames as $key => $conversionName) {
            $convModel = new RepoYdnCampaignReport;
            $queryGetConversion = $convModel->select(
                DB::raw('SUM(repo_ydn_reports.conversions) AS conversions, '.$groupedByField)
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
            $repoPhoneTimeUseModel = new RepoPhoneTimeUse;
            $tableName = $repoPhoneTimeUseModel->getTable();
            $queryGetCallTracking = $repoPhoneTimeUseModel->select(
                DB::raw($groupedByField .", COUNT(`id`) AS id")
            )->where('phone_number', $phoneNumber)
            ->where('source', 'yss')
            ->where(
                function (EloquentBuilder $query) use ($startDay, $tableName, $endDay) {
                    $this->addConditonForDate($query, $tableName, $startDay, $endDay);
                }
            )->whereIn('utm_campaign', $utmCampaignList)
            ->groupBy($groupedByField);

            DB::update(
                'update '.self::TABLE_TEMPORARY.', ('
                .$this->getBindingSql($queryGetCallTracking).') AS tbl set call'.$i.' = tbl.id where '
                .self::TABLE_TEMPORARY.'.campaignID = tbl.'.$groupedByField
            );
        }
    }
}
