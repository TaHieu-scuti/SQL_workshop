<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder as QueryBuilder;

use App\Model\RepoYssAccountReportCost;

class RepoAccountDayOfWeek extends RepoYssAccountReportCost
{
    protected function addJoinPhoneTimeUseDayOfWeek($account_id, $traffic_type, $source, $startDay, $endDay)
    {
        $query = DB::table('phone_time_use')
        ->select(DB::raw('
            COUNT(id) as id,
            account_id,
            `source`,
            DAYNAME(phone_time_use.time_of_call) AS `dayOfWeek`'))
        ->where('account_id', $account_id)
        ->where('traffic_type', $traffic_type)
        ->where('source', $source)
        ->where(function (QueryBuilder $query) use ($startDay, $endDay) {
            $this->conditionForDate($query, 'phone_time_use', $startDay, $endDay);
        })->groupBy('dayOfWeek');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($query).')AS tbl set totalPhoneTimeUse = tbl.id where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.account_id = tbl.account_id AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "'.$source.'" AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.dayOfWeek = tbl.dayOfWeek'
        );
    }

    protected function addJoinPhoneTimeUseDayOfWeekForYdn($clientId, $startDay, $endDay)
    {
        $phoneTimeUseModel = new PhoneTimeUse();
        $campaignIdAdgainer = $this->getCampaignIdAdgainer($clientId);
        $campaignModel = new Campaign();
        $campaignForPhoneTimeUse = $campaignModel->getCustomForPhoneTimeUse($campaignIdAdgainer);
        $utmCampaignList = array_unique($campaignIdAdgainer->pluck('campaignID')->toArray());

        $builder = $phoneTimeUseModel->select(
            [
                DB::raw('count(id) AS id'),
                DB::raw('DAYNAME(phone_time_use.`time_of_call`) AS `dayofweek`')
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
        ->groupBy('dayofweek');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($builder).') AS tbl set totalPhoneTimeUse = tbl.id where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.dayOfWeek = tbl.dayOfWeek AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "ydn"'
        );
    }

    protected function updateDailySpendingLimitDayOfWeekYss($clientId, $startDay, $endDay)
    {
        $yssCampaignModel = new RepoYssCampaignReportCost;
        $query = $yssCampaignModel
            ->select(DB::raw('SUM(dailySpendingLimit) AS dailySpendingLimit, `dayOfWeek`'))
            ->where('account_id', $clientId)
            ->where(function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_yss_campaign_report_cost');
            })->groupBy('dayOfWeek');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($query).')AS tbl set '
            .self::TEMPORARY_ACCOUNT_TABLE.'.dailySpendingLimit = tbl.dailySpendingLimit where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.dayOfWeek = tbl.dayOfWeek AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "yss"'
        );
    }

    protected function updateDailySpendingLimitDayOfWeekAdw($clientId, $startDay, $endDay)
    {
        $adwCampaignModel = new RepoAdwCampaignReportCost;
        $query = $adwCampaignModel
            ->select(DB::raw('SUM(`budget`) AS dailySpendingLimit, `dayOfWeek`'))
            ->where('account_id', $clientId)
            ->where(function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_campaign_report_cost');
            })->groupBy('dayOfWeek');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($query).')AS tbl set '
            .self::TEMPORARY_ACCOUNT_TABLE.'.dailySpendingLimit = tbl.dailySpendingLimit where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.dayOfWeek = tbl.dayOfWeek AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "adw"'
        );
    }

    private function conditionForDate(QueryBuilder $query, $tableName, $startDay, $endDay)
    {
        if ($startDay === $endDay) {
            $query->whereRaw('STR_TO_DATE('.$tableName.
                '.time_of_call, "%Y-%m-%d %H:%i:%s") LIKE "'.$endDay.'%"');
        } else {
            $query->whereRaw('STR_TO_DATE('.$tableName.
                '.time_of_call, "%Y-%m-%d %H:%i:%s") >= "'.$startDay.'"')
                ->whereRaw('STR_TO_DATE('.$tableName.
                    '.time_of_call, "%Y-%m-%d %H:%i:%s") <= "'.$endDay.'"');
        }
    }

    private function getCampaignIdAdgainer($clientId)
    {
        $ydnModel = new RepoYdnReport;
        return $ydnModel->select('campaign_id', 'campaignID')
            ->distinct()
            ->where(
                function (Builder $query) use ($clientId) {
                    $query->where('account_id', '=', $clientId);
                }
            )
            ->get();
    }
}
