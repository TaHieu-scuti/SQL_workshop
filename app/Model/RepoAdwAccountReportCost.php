<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RepoAdwAccountReportCost extends AbstractAccountReportModel
{
    protected $table = 'repo_adw_account_report_cost';

    /**
     * @var bool
     */
    public $timestamps = false;

    // constant
    const GROUPED_BY_FIELD_NAME = 'account';
    const PAGE_ID = 'accountid';
    const ARR_FIELDS = [
        self::CLICKS => self::CLICKS,
        self::CONVERSIONS => self::CONVERSIONS,
        self::COST => self::COST,
        self::IMPRESSIONS => self::IMPRESSIONS,
        self::CTR => self::CTR,
        self::AVERAGE_POSITION => self::ADW_AVERAGE_POSITION,
        self::AVERAGE_CPC => self::ADW_AVERAGE_CPC
    ];

    protected function getPhoneTimeUseSourceValue()
    {
        return 'adw';
    }

    public function getAdwAccountAgency(array $fieldNames, $startDay, $endDay)
    {
        $getAggregatedAdwAccounts = $this->getAggregatedAgency($fieldNames);

        $accounts = self::select($getAggregatedAdwAccounts)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy(self::FOREIGN_KEY_YSS_ACCOUNTS);

        $this->addJoinOnPhoneTimeUse($accounts);

        return $accounts;
    }

    public function getDataForGraphAdw($column)
    {
        $arrSelect = [];
        $tableName = $this->getTable();
        $arrSelect[] = DB::raw('DATE(day) as day');
        if (in_array($column, static::AVERAGE_FIELDS)) {
            $arrSelect[] = DB::raw(
                'ROUND(AVG('. self::ARR_FIELDS[$column] .'), 2) AS data'
            );
        } elseif (in_array($column, static::SUM_FIELDS)) {
            if (DB::connection()->getDoctrineColumn($tableName, $column)
                    ->getType()
                    ->getName()
                === self::FIELD_TYPE) {
                $arrSelect[] = DB::raw(
                    'ROUND(SUM(' . self::ARR_FIELDS[$column] . '), 2) AS data'
                );
            } else {
                $arrSelect[] = DB::raw(
                    'SUM( ' . self::ARR_FIELDS[$column] . ' ) AS data'
                );
            }
        }
        return $arrSelect;
    }

    public function adwAccountDataForGraphOfAgencyList($column, $startDay, $endDay)
    {
        $aggreations = $this->getDataForGraphAdw($column);
        return self::select($aggreations)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy('day');
    }
    
    public function getDataGraphForAdw($column, $startDay, $endDay, $arrAccountsAgency)
    {
        $getAggregatedAdwAccounts = $this->getDataForGraphAdw($column);

        return self::select($getAggregatedAdwAccounts)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->whereIn('account_id', $arrAccountsAgency)
            ->groupBy('day');
    }
}
