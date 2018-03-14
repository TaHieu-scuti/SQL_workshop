<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RepoYdnReport extends AbstractAccountReportModel
{
    protected $table = 'repo_ydn_reports';
    const PAGE_ID = 'accountid';
    const GROUPED_BY_FIELD_NAME = 'accountName';
    const ARR_FIELDS = [
        self::CLICKS => self::CLICKS,
        self::CONVERSIONS => self::CONVERSIONS,
        self::COST => self::COST,
        self::IMPRESSIONS => self::IMPRESSIONS,
        self::CTR => self::CTR,
        self::AVERAGE_POSITION => self::AVERAGE_POSITION,
        self::AVERAGE_CPC => self::AVERAGE_CPC
    ];

    public $timestamps = false;

    private function getAggregatedOfYdn(array $fieldNames)
    {
        if (array_search('accountName', $fieldNames) === false) {
            $keyPageId = array_search(static::PAGE_ID, $fieldNames);
            if ($keyPageId !== false) {
                unset($fieldNames[$keyPageId]);
            }
        }
        $tableName = $this->getTable();
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === self::DEVICE
                || $fieldName === self::HOUR_OF_DAY
                || $fieldName === self::DAY_OF_WEEK
                || $fieldName === self::PREFECTURE
            ) {
                $key = array_search(static::PAGE_ID, $fieldNames);
                if ($key !== false) {
                    unset($fieldNames[$key]);
                }
            }
        }

        $arrayCalculate = [];
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === self::GROUPED_BY_FIELD_NAME
                ||$fieldName === self::DEVICE
                || $fieldName === self::HOUR_OF_DAY
                || $fieldName === self::PREFECTURE
            ) {
                $arrayCalculate[] = DB::raw($tableName.'.'.$fieldName.' AS '.$fieldName);
                continue;
            }
            if ($fieldName === self::DAY_OF_WEEK) {
                $arrayCalculate[] = DB::raw('DAYNAME(day) AS ' . $fieldName);
                continue;
            }
            if ($fieldName === self::PAGE_ID) {
                $arrayCalculate[] = DB::raw('accountId AS ' . $fieldName);
                continue;
            }
            if ($fieldName === self::DAILY_SPENDING_LIMIT) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(SUM( ' .$fieldName. ' ), 0) AS ' . $fieldName
                );
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ROUND(AVG('. $tableName. '.' .$fieldName . '), 2), 0) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                        ->getType()
                        ->getName()
                    === self::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'IFNULL(ROUND(SUM(' . $tableName. '.' .$fieldName . '), 2), 0) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw(
                        'IFNULL(SUM( ' . $tableName. '.' .$fieldName . ' ), 0) AS ' . $fieldName
                    );
                }
            }
        }

        return $arrayCalculate;
    }

    protected function getPhoneTimeUseSourceValue()
    {
        return 'ydn';
    }

    public function getAggregatedGraphOfYdn($column)
    {
        $arrSelect = [];
        $tableName = $this->getTable();
        $arrSelect[] = DB::raw('DATE(day) as day');

        if (in_array($column, static::AVERAGE_FIELDS)) {
            $arrSelect[] = DB::raw(
                'ROUND(AVG('. $column .'), 2) AS data'
            );
        } elseif (in_array($column, static::SUM_FIELDS)) {
            if (DB::connection()->getDoctrineColumn($tableName, $column)
                    ->getType()
                    ->getName()
                === self::FIELD_TYPE) {
                $arrSelect[] = DB::raw(
                    'ROUND(SUM(' . $column . '), 2) AS data'
                );
            } else {
                $arrSelect[] = DB::raw(
                    'SUM( ' . $column . ' ) AS data'
                );
            }
        }

        return $arrSelect;
    }

    public function getAllAccountYdn(
        array $fieldNames,
        $groupedByField,
        $columnSort,
        $sort,
        $startDay,
        $endDay,
        $clientId = null,
        $accountId = null
    ) {
        $aggregations = $this->getAggregatedOfYdn($fieldNames);
        $arraySelection = array_merge(
            [DB::raw("repo_ydn_reports.adID, 'ydn' as engine, repo_ydn_reports.account_id as account_id")],
            $aggregations
        );
        $ydnAccountReport = self::select(
            array_merge(
                $arraySelection,
                [DB::raw('SUM(dailySpendingLimit) AS dailySpendingLimit,
                SUM(repo_ydn_reports.conversions) AS sumConversions')]
            )
        )->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query);
            }
        )->where(
            function (Builder $query) use ($clientId) {
                $query->where('repo_ydn_reports.account_id', '=', $clientId);
            }
        )
        ->groupBy($groupedByField);

        if (!in_array($groupedByField, $this->groupByFieldName)) {
            $ydnAccountReport = $ydnAccountReport->groupBy('accountid');
        }
        return $ydnAccountReport;
    }

    public function calculateSummaryDataYdn(array $fieldNames, $startDay, $endDay, $clientId)
    {
        $aggreations = $this->getAggregatedOfYdn($fieldNames);
        return self::select(array_merge($aggreations))
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )->where(
                function (Builder $query) use ($clientId) {
                    $query->where('repo_ydn_reports.account_id', '=', $clientId);
                }
            );
    }

    public function ydnAccountCalculate($fieldNames, $startDay, $endDay, $clientId)
    {
        $aggregations = $this->getAggregatedOfYdn($fieldNames);
        $aggregations = array_merge($this->getAggregatedForAccounts($fieldNames), $aggregations);
        return self::select(array_merge($aggregations))
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )->where(
                function (Builder $query) use ($clientId) {
                    $query->where('repo_ydn_reports.account_id', '=', $clientId);
                }
            );
    }

    public function ydnAccountDataForGraph($column, $startDay, $endDay, $clientId)
    {
        $aggreations = $this->getAggregatedGraphOfYdn($column);
        return self::select($aggreations)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
                function ($query) use ($clientId) {
                    $query->where('account_id', '=', $clientId);
                }
            )
            ->groupBy('day');
    }

    public function ydnAccountDataForGraphOfAgencyList($column, $startDay, $endDay)
    {
        $aggreations = $this->getAggregatedGraphOfYdn($column);
        return self::select($aggreations)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy('day');
    }

    public function getYdnAccountAgency(array $fieldNames, $startDay, $endDay)
    {
        $getAggregatedYdnAccounts = $this->getAggregatedAgency($fieldNames);

        $accounts = self::select($getAggregatedYdnAccounts)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy(self::FOREIGN_KEY_YSS_ACCOUNTS);

        $this->addJoinOnPhoneTimeUse($accounts);

        return $accounts;
    }

    public function getGraphForAgencyYdn($column, $startDay, $endDay, $arrAccountsAgency)
    {
        $getAggregatedYdnAccounts = $this->getAggregatedGraphOfYdn($column);

        return self::select($getAggregatedYdnAccounts)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->whereIn('account_id', $arrAccountsAgency)
            ->groupBy('day');
    }

    private function getAggregatedForAccounts(array $fieldNames)
    {
        $expressions = [];
        foreach ($fieldNames as $fieldName) {
            switch ($fieldName) {
                case 'call_cv':
                    $expressions[] = DB::raw('COUNT(`phone_time_use`.`id`) AS call_cv');
                    break;
                case 'call_cvr':
                    $expressions[] = DB::raw(
                        "((SUM(`{$this->table}`.`conversions`) + COUNT(`phone_time_use`.`id`)) "
                        . "/ SUM(`{$this->table}`.`clicks`)) * 100 AS call_cvr"
                    );
                    break;
                case 'call_cpa':
                    $expressions[] = DB::raw(
                        "SUM(`{$this->table}`.`cost`) / (SUM(`{$this->table}`.`conversions`) "
                        . "+ COUNT(`phone_time_use`.`id`)) AS call_cpa"
                    );
                    break;
                case 'web_cv':
                    $expressions[] = DB::raw(
                        "SUM(`{$this->table}`.conversions) AS Web_CV"
                    );
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw(
                        "(SUM(`{$this->table}`.conversions) / SUM(`{$this->table}`.clicks) * 100) AS Web_CVR"
                    );
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw(
                        "(SUM(`{$this->table}`.cost) / SUM(`{$this->table}`.conversions)) AS Web_CPA"
                    );
                    break;
                case 'total_cv':
                    $expressions[] = DB::raw(
                        "(SUM(`{$this->table}`.`conversions`) + COUNT(`phone_time_use`.`id`)) as total_cv"
                    );
                    break;
                case 'total_cvr':
                    $expressions[] = DB::raw(
                        "((COUNT(`phone_time_use`.`id`) / SUM(`{$this->table}`.`clicks`)) * 100
                    +
                    (SUM(`{$this->table}`.`conversions`) / SUM(`{$this->table}`.`clicks`)) * 100)
                    / 2 as total_cvr"
                    );
                    break;
                case 'total_cpa':
                    $expressions[] = DB::raw(
                        "SUM(`{$this->table}`.`cost`) / COUNT(`phone_time_use`.`id`) +
                        SUM(`{$this->table}`.`cost`) / SUM(`{$this->table}`.`conversions`) as total_cpa"
                    );
                    break;
            }
        }
        return $expressions;
    }
}
