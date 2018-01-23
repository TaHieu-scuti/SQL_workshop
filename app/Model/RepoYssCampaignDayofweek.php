<?php

namespace App\Model;

use App\Model\AbstractYssReportModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class RepoYssCampaignDayofweek extends AbstractYssSpecificReportModel
{
    protected $table = 'repo_yss_campaign_report_cost';

    const PAGE_ID = 'campaignID';

    public $timestamps = false;

    protected function addJoin(EloquentBuilder $builder, $conversionPoints = null, $adGainerCampaigns = null)
    {
        $this->addJoinsForConversionPoints($builder, $conversionPoints);
        $this->addJoinsForCallConversions($builder, $adGainerCampaigns);
    }

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
                        $this->table . '.dayOfWeek',
                        '=',
                        $joinAlias . '.dayOfWeek'
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
        $utmCampaignList = array_unique($adGainerCampaigns->pluck('utm_campaign')->toArray());
        $phoneList = array_unique($adGainerCampaigns->pluck('phone_number')->toArray());
        foreach ($phoneList as $i => $phoneNumber) {
            $joinAlias = 'call' . $i;
            $builder->leftJoin(
                $joinTableName . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $phoneNumber, $utmCampaignList) {
                    $join->on(
                        $this->table . '.account_id',
                        '=',
                        $joinAlias . '.account_id'
                    )->on(
                        $this->table . '.campaign_id',
                        '=',
                        $joinAlias . '.campaign_id'
                    )->whereIn(
                        $joinAlias . '.utm_campaign',
                        $utmCampaignList
                    )->on(
                        $this->table . '.day',
                        '=',
                        DB::raw("STR_TO_DATE(`" . $joinAlias . "`.`time_of_call`, '%Y-%m-%d')")
                    )->whereRaw(
                        '`' . $joinAlias . "`.`phone_number` = '" . $phoneNumber . "'"
                    )->where(
                        $joinAlias . '.source',
                        '=',
                        'yss'
                    );
                }
            );
        }
    }

    protected function getAllDistinctConversionNames($account_id, $accountId, $campaignId, $adGroupId, $column)
    {
        $yssCampaignConvModel = new RepoYssCampaignReportConv();
        $aggregation = $this->getAggregatedConversionName($column);
        $aggregation[] = 'dayOfWeek';
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
