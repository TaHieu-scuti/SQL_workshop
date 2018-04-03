<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder as QueryBuilder;

use App\Model\RepoYssAccountReportCost;

class RepoAccountPrefecture extends RepoYssAccountReportCost
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
            `visitor_city_state` AS prefecture'))
        ->where('account_id', $account_id)
        ->where('traffic_type', $traffic_type)
        ->where('source', $source)
        ->where(function (QueryBuilder $query) use ($startDay, $endDay) {
            $this->conditionForDate($query, 'phone_time_use', $startDay, $endDay);
        })->groupBy('prefecture');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($query).')AS tbl set totalPhoneTimeUse = tbl.id where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.account_id = tbl.account_id AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "'.$source.'" AND 
            tbl.prefecture LIKE CONCAT("%", '.self::TEMPORARY_ACCOUNT_TABLE.'.prefecture, " (Japan)%")'
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
                DB::raw('visitor_city_state AS `prefecture`')
            ]
        )
        ->where('source', 'ydn')
        ->whereRaw('traffic_type = "AD"')
        ->where('utm_campaign', $utmCampaignList)
        ->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addConditionForDate($query, 'phone_time_use', $startDay, $endDay);
            }
        )
        ->groupBy('prefecture');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($builder).') AS tbl set totalPhoneTimeUse = tbl.id where 
            tbl.prefecture LIKE CONCAT("%", '.self::TEMPORARY_ACCOUNT_TABLE.'.prefecture, " (Japan)%") AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "ydn"'
        );
    }
}
