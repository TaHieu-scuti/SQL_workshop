<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder as QueryBuilder;

use App\Model\RepoYssAccountReportCost;

class RepoAccountTimezone extends RepoYssAccountReportCost
{
    protected function updateTemporaryTableWithPhoneTimeUseForYssAdw(
        $account_id,
        $traffic_type,
        $source,
        $startDay,
        $endDay
    ) {
        $query = DB::table('phone_time_use')
        ->select(DB::raw('
            COUNT(id) as id,
            account_id,
            `source`,
            HOUR(`phone_time_use`.`time_of_call`) AS `hourofday`'))
        ->where('account_id', $account_id)
        ->where('traffic_type', $traffic_type)
        ->where('source', $source)
        ->where(function (QueryBuilder $query) use ($startDay, $endDay) {
            $this->conditionForDate($query, 'phone_time_use', $startDay, $endDay);
        })->groupBy('hourofday');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($query).')AS tbl set totalPhoneTimeUse = tbl.id where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.account_id = tbl.account_id AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "'.$source.'" AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.hourofday = tbl.hourofday'
        );
    }

    protected function updateTemporaryTableWithPhoneTimeUseForYdn($clientId, $startDay, $endDay)
    {
        $phoneTimeUseModel = new PhoneTimeUse();
        $campaignIdAdgainer = $this->getCampaignIdAdgainer($clientId);
        $utmCampaignList = array_unique($campaignIdAdgainer->pluck('campaignID')->toArray());

        $builder = $phoneTimeUseModel->select(
            [
                DB::raw('count(id) AS id'),
                DB::raw('HOUR(`phone_time_use`.`time_of_call`) AS `hourofday`')
            ]
        )
        ->where('source', 'ydn')
        ->whereRaw('traffic_type = "AD"')
        ->where('utm_campaign', $utmCampaignList)
        ->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addConditonForDate($query, 'phone_time_use', $startDay, $endDay);
            }
        )
        ->groupBy('hourofday');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($builder).') AS tbl set totalPhoneTimeUse = tbl.id where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.hourofday = tbl.hourofday AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "ydn"'
        );
    }

    protected function updateTemporaryTableWithDailySpendingLimitForYss($clientId, $startDay, $endDay)
    {
        $yssCampaignModel = new RepoYssCampaignReportCost;
        $query = $yssCampaignModel
            ->select(DB::raw('SUM(dailySpendingLimit) AS dailySpendingLimit, `hourofday`'))
            ->where('account_id', $clientId)
            ->where(function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_yss_campaign_report_cost');
            })->groupBy('hourofday');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($query).')AS tbl set '
            .self::TEMPORARY_ACCOUNT_TABLE.'.dailySpendingLimit = tbl.dailySpendingLimit where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.hourofday = tbl.hourofday AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "yss"'
        );
    }

    protected function updateTemporaryTableWithDailySpendingLimitForAdw($clientId, $startDay, $endDay)
    {
        $adwCampaignModel = new RepoAdwCampaignReportCost;
        $query = $adwCampaignModel
            ->select(DB::raw('SUM(`budget`) AS dailySpendingLimit, `hourofday`'))
            ->where('account_id', $clientId)
            ->where(function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_campaign_report_cost');
            })->groupBy('hourofday');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($query).')AS tbl set '
            .self::TEMPORARY_ACCOUNT_TABLE.'.dailySpendingLimit = tbl.dailySpendingLimit where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.hourofday = tbl.hourofday AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "adw"'
        );
    }
}
