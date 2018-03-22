<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\Event;

use DateTime;
use Exception;
use PDO;

class RepoYssAccountReportCost extends AbstractAccountReportModel
{
    protected $table = 'repo_yss_account_report_cost';
    const GROUPED_BY_FIELD_NAME = 'accountName';
    const GROUPED_BY_FIELD_NAME_ADW = 'account';
    const PAGE_ID = 'accountid';
    const ADW_CUSTOMER_ID = 'customerID';
    /**
     * @var bool
     */
    public $timestamps = false;

    // constant
    const FOREIGN_KEY_YSS_ACCOUNTS = 'account_id';
    const HIDE_ZERO_STATUS = 'hideZero';
    const SHOW_ZERO_STATUS = 'showZero';

    const ADW_FIELDS = [
        self::CLICKS => self::CLICKS,
        self::CONVERSIONS => self::CONVERSIONS,
        self::COST => self::COST,
        self::IMPRESSIONS => self::IMPRESSIONS,
        self::CTR => self::CTR,
        self::AVERAGE_POSITION => self::ADW_AVERAGE_POSITION,
        self::AVERAGE_CPC => self::ADW_AVERAGE_CPC

    ];

    const ARR_FIELDS = [
        self::CLICKS => self::CLICKS,
        self::CONVERSIONS => self::CONVERSIONS,
        self::COST => self::COST,
        self::IMPRESSIONS => self::IMPRESSIONS,
        self::CTR => self::CTR,
        self::AVERAGE_POSITION => self::AVERAGE_POSITION,
        self::AVERAGE_CPC => self::AVERAGE_CPC
    ];

    const DEFAULT_COLUMNS = [
        'prefecture',
        'hourofday',
        'dayOfWeek',
        'accountName',
        'accountid',
        'impressions',
        'clicks',
        'cost',
        'ctr',
        'averageCpc',
        'averagePosition',
        'dailySpendingLimit',
        'web_cv',
        'web_cvr',
        'web_cpa',
        'call_cv',
        'call_cvr',
        'call_cpa',
        'total_cv',
        'total_cvr',
        'total_cpa'
    ];

    //required fields, need to display summary
    const REQUIRED_FIELDS = [
        'impressions',
        'clicks',
        'cost',
        'averageCpc',
        'averagePosition',
        'dailySpendingLimit'
    ];

    const RELATED_FIELDS = [
        'web_cvr' => ['web_cv', 'clicks'],
        'web_cpa' => ['cost', 'web_cv'],
        'call_cvr' => ['web_cv', 'call_cv', 'clicks'],
        'call_cpa' => ['cost', 'web_cv', 'call_cv'],
        'total_cv' => ['web_cv', 'call_cv'],
        'total_cvr' => ['web_cvr', 'call_cvr'],
        'total_cpa' => ['web_cpa', 'call_cpa']
    ];

    private function getAggregatedGraphOfGoogle($column)
    {
        $arrSelect = [];
        $tableName = (new RepoAdwAccountReportCost)->getTable();
        $arrSelect[] = DB::raw('DATE(day) as day');
        if (in_array($column, static::AVERAGE_FIELDS)) {
            if ($column === self::AVERAGE_CPC) {
                $arrSelect[] = DB::raw(
                    'ROUND(AVG( avgCPC ), 2) AS data'
                );
            } elseif ($column === self::AVERAGE_POSITION) {
                $arrSelect[] = DB::raw(
                    'ROUND(AVG( avgPosition ), 2) AS data'
                );
            } else {
                $arrSelect[] = DB::raw(
                    'ROUND(AVG('. $column .'), 2) AS data'
                );
            }
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

    protected function getPhoneTimeUseSourceValue()
    {
        return 'yss';
    }

    public function getAggregatedGraph($column)
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

    private function getAggregatedOfGoogle(array $fieldNames)
    {
        array_unshift($fieldNames, self::GROUPED_BY_FIELD_NAME_ADW);
        if (array_search('accountName', $fieldNames) === false) {
            $key = array_search(static::GROUPED_BY_FIELD_NAME_ADW, $fieldNames);
            if ($key !== false) {
                unset($fieldNames[$key]);
            }
            $keyPageId = array_search(static::PAGE_ID, $fieldNames);
            if ($keyPageId !== false) {
                unset($fieldNames[$key]);
            }
        }
        $tableName = (new RepoAdwAccountReportCost)->getTable();
        if (isset($fieldNames[0]) && $fieldNames[0] === self::PREFECTURE) {
            $tableName = 'repo_yss_prefecture_report_cost';
        }
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
            if ($fieldName === self::PAGE_ID) {
                $arrayCalculate[] = $tableName.'.'.self::ADW_CUSTOMER_ID.' as accountid';
                continue;
            }
            if ($fieldName === self::DEVICE
                || $fieldName === self::HOUR_OF_DAY
                || $fieldName === self::DAY_OF_WEEK
                || $fieldName === self::PREFECTURE
                || $fieldName === self::PAGE_ID
            ) {
                $arrayCalculate[] = DB::raw($tableName.'.'.$fieldName.' AS '.$fieldName);
                continue;
            }
            if ($fieldName === static::GROUPED_BY_FIELD_NAME_ADW) {
                $arrayCalculate[] = $tableName.'.'.$fieldName .' AS accountName';
                continue;
            }
            if ($fieldName === 'dailySpendingLimit') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(SUM(repo_adw_campaign_report_cost.budget), 0) AS dailySpendingLimit'
                );
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ROUND(AVG('. $tableName. '.' . self::ADW_FIELDS[$fieldName] . '), 2), 0) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                        ->getType()
                        ->getName()
                    === self::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'IFNULL(ROUND(SUM(' . $tableName. '.' . self::ADW_FIELDS[$fieldName] . '), 2), 0) 
                        AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw(
                        'IFNULL(SUM( ' . $tableName. '.' . self::ADW_FIELDS[$fieldName] . ' ), 0) AS ' . $fieldName
                    );
                }
            }
        }
        return $arrayCalculate;
    }

    private function getAggregatedOfAccountAdwPrefecture($fieldNames)
    {
        $tableName = (new RepoAdwGeoReportCost)->getTable();
        $arrayCalculate = [];
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === self::PREFECTURE) {
                $arrayCalculate[] = DB::raw('`criteria`.`name` AS prefecture');
                continue;
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ROUND(AVG('. $tableName. '.' . self::ADW_FIELDS[$fieldName] . '), 2), 0) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                        ->getType()
                        ->getName()
                    === self::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'IFNULL(ROUND(SUM(' . $tableName. '.' . self::ADW_FIELDS[$fieldName] . '), 2), 0) 
                        AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw(
                        'IFNULL(SUM( ' . $tableName. '.' . self::ADW_FIELDS[$fieldName] . ' ), 0) AS ' . $fieldName
                    );
                }
            }
        }
        return $arrayCalculate;
    }

    /**
     * @param string $column
     * @param string $accountStatus
     * @param string $startDay
     * @param string $endDay
     * @return \Illuminate\Support\Collection
     */
    public function getDataForGraph(
        $engine,
        $column,
        $accountStatus,
        $startDay,
        $endDay,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        try {
            new DateTime($startDay);
            new DateTime($endDay);
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }
        $modelYdnReport = new RepoYdnReport();
        $arrSelect = $this->getAggregatedGraph($column);
        $arrSelectGoogle = $this->getAggregatedGraphOfGoogle($column);
        $ydnAccountDataForGraph = $modelYdnReport->ydnAccountDataForGraph($column, $startDay, $endDay, $clientId);
        $dataForGoogle = RepoAdwAccountReportCost::select($arrSelectGoogle)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_account_report_cost');
                }
            )
            ->where(
                function ($query) use ($clientId) {
                    $query->where('account_id', '=', $clientId);
                }
            )
            ->groupBy('day');

        $data = self::select($arrSelect)
            ->join(
                'repo_yss_accounts',
                'repo_yss_account_report_cost.account_id',
                '=',
                'repo_yss_accounts.account_id'
            )
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
                function ($query) use ($clientId) {
                    $query->where('repo_yss_account_report_cost.account_id', '=', $clientId);
                }
            )
            ->groupBy('day');
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $dataForGoogle = $dataForGoogle->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $ydnAccountDataForGraph = $ydnAccountDataForGraph->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }

        $data = $data->union($dataForGoogle)->union($ydnAccountDataForGraph);
        $sql = $this->getBindingSql($data);
        $data = DB::table(DB::raw("({$sql}) as tbl"))
            ->select(DB::raw('day, sum(data) as data'))
            ->groupBy('day');

        $data = $data->get();

        return $data;
    }

    public function calculateData(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $groupedByField,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $fieldNames = $this->handlerFields($fieldNames);
        $rawExpression = $this->getRawExpressions($fieldNames);
        $columns = $this->unsetColumns($fieldNames, array_merge(self::COLUMNS_NOT_MAKE, ['dailySpendingLimit']));
        array_unshift($columns, 'account_id');
        array_unshift($columns, 'engine');

        $selections = $this->getAggregatedForTemporaryAccount($columns, $fieldNames);
        $sql = DB::table(self::TEMPORARY_ACCOUNT_TABLE)->select($selections);
        return DB::table(DB::raw("(".$this->getBindingSql($sql).") as tbl"))
        ->select($rawExpression)->first();
    }

    public function calculateSummaryData(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $fieldNames = $this->handlerFields($fieldNames);
        $fieldNames = $this->unsetColumns($fieldNames, ['accountName', 'accountid', 'dailySpendingLimit']);
        $selections = $this->getRawExpressions($fieldNames);
        $data = DB::table(self::TEMPORARY_ACCOUNT_TABLE)->select($selections)->first();
        if ($data === null) {
            $data = [
                'clicks' => 0,
                'impressions' => 0,
                'cost' => 0,
                'averageCpc' => 0,
                'averagePosition' => 0
            ];
        } else {
            $data = get_object_vars($data);
        }
        return $data;
    }

    protected function getDatasAccountOfGoogle(
        array $fieldNames,
        $startDay,
        $endDay,
        $clientId = null
    ) {
        $adwAggregations = $this->getAggregatedOfGoogle($fieldNames);
        $adwAccountReport = RepoAdwAccountReportCost::select(array_merge($adwAggregations))
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_account_report_cost');
                }
            )->where(
                function (Builder $query) use ($clientId) {
                    $query->where('account_id', '=', $clientId);
                }
            );

        return $adwAccountReport;
    }

    public function yssAccountDataForGraphOfAgencyList($column, $startDay, $endDay)
    {
        $aggreations = $this->getAggregatedGraph($column);
        return self::select($aggreations)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy('day');
    }

    public function getYssAccountAgency(array $fieldNames, $startDay, $endDay)
    {
        $getAggregatedYssAccounts = $this->getAggregatedAgency($fieldNames);

        $accounts = self::select($getAggregatedYssAccounts)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy(self::FOREIGN_KEY_YSS_ACCOUNTS);

        $this->addJoinOnPhoneTimeUse($accounts);

        return $accounts;
    }

    public function getGraphForAgencyYss($column, $startDay, $endDay, $arrAccountsAgency)
    {
        $getAggregatedYssAccounts = $this->getAggregatedGraph($column);
        return self::select($getAggregatedYssAccounts)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->whereIn('account_id', $arrAccountsAgency)
            ->groupBy('day');
    }

    public function getDataForTable(
        $engine,
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $pagination,
        $columnSort,
        $sort,
        $groupedByField,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        // YSS
        $fieldNames = $this->handlerFields($fieldNames);
        $this->createTemporaryAccountReport($fieldNames);
        $joinTableName = 'repo_yss_accounts';
        $columns = $this->unsetColumns($fieldNames, array_merge(self::COLUMNS_NOT_MAKE, ['dailySpendingLimit']));
        array_unshift($columns, 'account_id');
        array_unshift($columns, 'engine');

        $yss = $this;
        if ($groupedByField === 'prefecture') {
            $yss = new RepoYssPrefectureReportCost();
        }
        $yssAggregations = $yss->getAggregated($columns);

        $yssData = $yss->select(
            array_merge(
                [DB::raw("'yss' as engine, ".$yss->getTable().".account_id as account_id")],
                $yssAggregations
            )
        )->addSelect(DB::raw('SUM('.$yss->getTable().'.`conversions`) AS sumConversions'))
        ->join(
            $joinTableName,
            $yss->getTable(). '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
            '=',
            $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
        )
        ->where(
            function (Builder $query) use ($startDay, $endDay, $yss) {
                if ($startDay === $endDay) {
                    $query->whereDate($yss->getTable().'.day', '=', $endDay);
                } else {
                    $query->whereDate($yss->getTable().'.day', '>=', $startDay)
                        ->whereDate($yss->getTable().'.day', '<=', $endDay);
                }
            }
        )
        ->where($yss->getTable().'.account_id', '=', $clientId)
        ->groupBy($groupedByField);

        if (!in_array($groupedByField, $this->groupByFieldName)) {
            $yssData = $yssData->groupBy('repo_yss_accounts.accountid');
        }

        DB::insert('INSERT into '.self::TEMPORARY_ACCOUNT_TABLE.' ('.implode(', ', $columns).', sumConversions) '
            . $this->getBindingSql($yssData));

        // YDN
        $accountYdnReport = new RepoYdnReport;
        $ydnData = $accountYdnReport->getAllAccountYdn(
            $columns,
            $groupedByField,
            $columnSort,
            $sort,
            $startDay,
            $endDay,
            $clientId,
            $accountId
        );
        DB::insert('INSERT into '.self::TEMPORARY_ACCOUNT_TABLE.'(adID, '.implode(', ', $columns)
            .', dailySpendingLimit, sumConversions) '
            . $this->getBindingSql($ydnData));

        //Adw
        $adwAggregations = [];
        $adwData = null;
        if ($groupedByField === 'prefecture') {
            $adwAggregations = $this->getAggregatedOfAccountAdwPrefecture($columns);
            $adwData = $this->getAdwDataInsertToTemporaryByPrefecture(
                $adwAggregations,
                $groupedByField,
                $clientId,
                $startDay,
                $endDay
            );
        } else {
            $adwAggregations = $this->getAggregatedOfGoogle($columns);
            $adwData = RepoAdwAccountReportCost::select(
                array_merge(
                    [DB::raw("'adw' as engine, repo_adw_account_report_cost.account_id as account_id")],
                    $adwAggregations
                )
            )->addSelect(DB::raw('SUM(repo_adw_account_report_cost.conversions) AS sumConversions'))
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_account_report_cost');
                }
            )
            ->where('repo_adw_account_report_cost.account_id', '=', $clientId)
            ->where(
                function (Builder $query) {
                    $query->whereRaw("`repo_adw_account_report_cost`.`network` = 'SEARCH'")
                    ->orWhereRaw("`repo_adw_account_report_cost`.`network` = 'CONTENT'");
                }
            )
            ->groupBy($groupedByField);
        }

        if (!in_array($groupedByField, $this->groupByFieldName)) {
            $adwData = $adwData->groupBy('repo_adw_account_report_cost.'.self::ADW_CUSTOMER_ID);
        }
        DB::insert('INSERT into '.self::TEMPORARY_ACCOUNT_TABLE.' ('.implode(', ', $columns).', sumConversions) '
            . $this->getBindingSql($adwData));

        //update total phone time use
        $this->updateTemporaryTableWithPhoneTimeUseForYssAdw($clientId, 'AD', 'yss', $startDay, $endDay);
        $this->updateTemporaryTableWithPhoneTimeUseForYssAdw($clientId, 'AD', 'adw', $startDay, $endDay);
        $this->updateTemporaryTableWithPhoneTimeUseForYdn($clientId, 'AD', 'adw', $startDay, $endDay);

        //update daily spending limit
        if ($groupedByField !== 'prefecture') {
            $this->updateTemporaryTableWithDailySpendingLimitForYss($clientId, $startDay, $endDay);
            $this->updateTemporaryTableWithDailySpendingLimitForAdw($clientId, $startDay, $endDay);
        }

        $selections = $this->getAggregatedForTemporaryAccount($columns, $fieldNames);

        return DB::table(self::TEMPORARY_ACCOUNT_TABLE)
        ->select($selections)
        ->groupBy($groupedByField)
        ->orderBy($columnSort, $sort)
        ->paginate($pagination);
    }

    private function getAdwDataInsertToTemporaryByPrefecture(
        $adwAggregations,
        $groupedByField,
        $clientId,
        $startDay,
        $endDay
    ) {
        return RepoAdwGeoReportCost::select(
            array_merge(
                [DB::raw("'adw' as engine, repo_adw_geo_report_cost.account_id as account_id")],
                $adwAggregations
            )
        )->addSelect(DB::raw('SUM(repo_adw_geo_report_cost.conversions) AS sumConversions'))
        ->join('criteria', 'criteria.CriteriaID', '=', 'repo_adw_geo_report_cost.region')
        ->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_geo_report_cost');
            }
        )
        ->where('repo_adw_geo_report_cost.account_id', '=', $clientId)
        ->where(
            function (Builder $query) {
                $query->whereRaw("`repo_adw_geo_report_cost`.`network` = 'SEARCH'")
                ->orWhereRaw("`repo_adw_geo_report_cost`.`network` = 'CONTENT'");
            }
        )
        ->groupBy($groupedByField);
    }

    private function getAggregatedForTemporaryAccount(array $columns, array $fieldNames)
    {
        $expressions = ['engine'];
        $expressions = array_merge($expressions, parent::getAggregated($columns, [], self::TEMPORARY_ACCOUNT_TABLE));
        $expressions[] = 'dailySpendingLimit';
        foreach ($fieldNames as $fieldName) {
            switch ($fieldName) {
                case 'web_cv':
                    $expressions[] = DB::raw("IFNULL(sumConversions, 0) as web_cv");
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw("IFNULL(
                    (sumConversions / SUM(`".self::TEMPORARY_ACCOUNT_TABLE."`.`clicks`)) * 100, 0) as web_cvr");
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw("IFNULL(
                    SUM(`".self::TEMPORARY_ACCOUNT_TABLE."`.`cost`) / sumConversions, 0) AS web_cpa");
                    break;
                case 'call_cv':
                    $expressions[] = DB::raw("totalPhoneTimeUse AS call_cv");
                    break;
                case 'call_cvr':
                    $expressions[] = DB::raw("IFNULL((
                    (sumConversions + totalPhoneTimeUse) /
                    SUM(`".self::TEMPORARY_ACCOUNT_TABLE."`.`clicks`)) * 100, 0) AS call_cvr");
                    break;
                case 'call_cpa':
                    $expressions[] = DB::raw("IFNULL(
                    SUM(`".self::TEMPORARY_ACCOUNT_TABLE."`.`cost`) /
                    (sumConversions + totalPhoneTimeUse), 0) AS call_cpa");
                    break;
                case 'total_cv':
                    $expressions[] = DB::raw('sumConversions + totalPhoneTimeUse AS total_cv');
                    break;
                case 'total_cvr':
                    $expressions[] = DB::raw('
                        ((totalPhoneTimeUse / SUM('.self::TEMPORARY_ACCOUNT_TABLE.'.clicks)) * 100 +
                        (sumConversions / SUM('.self::TEMPORARY_ACCOUNT_TABLE.'.clicks)) * 100) / 2 AS total_cvr');
                    break;
                case 'total_cpa':
                    $expressions[] = DB::raw('
                        SUM('.self::TEMPORARY_ACCOUNT_TABLE.'.cost) / totalPhoneTimeUse +
                        SUM('.self::TEMPORARY_ACCOUNT_TABLE.'.cost) / sumConversions
                        AS total_cpa');
                    break;
            }
        }
        return $expressions;
    }

    protected function updateTemporaryTableWithPhoneTimeUseForYdn($clientId, $startDay, $endDay)
    {
        $phoneTimeUseModel = new PhoneTimeUse();
        $campaignIdAdgainer = $this->getCampaignIdAdgainer($clientId);
        $campaignModel = new Campaign();
        $campaignForPhoneTimeUse = $campaignModel->getCustomForPhoneTimeUse($campaignIdAdgainer);
        $utmCampaignList = array_unique($campaignIdAdgainer->pluck('campaignID')->toArray());
        $customField = '';

        foreach ($campaignForPhoneTimeUse as $campaign) {
            $customField = $this->getFieldName($campaign, 'creative');
        }

        $builder = $phoneTimeUseModel->select(
            [
                DB::raw('count(id) AS id'),
                $customField
            ]
        )
        ->whereRaw($customField.' NOT LIKE ""')
        ->where('source', 'ydn')
        ->whereRaw('traffic_type = "AD"')
        ->where('utm_campaign', $utmCampaignList)
        ->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addConditonForDate($query, 'phone_time_use', $startDay, $endDay);
            }
        )
        ->groupBy($customField);

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($builder).') AS tbl set totalPhoneTimeUse = tbl.id where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.adID = tbl.'.$customField
        );
    }

    protected function updateTemporaryTableWithPhoneTimeUseForYssAdw(
        $account_id,
        $traffic_type,
        $source,
        $startDay,
        $endDay
    ) {
        $query = DB::table('phone_time_use')
        ->select(DB::raw('COUNT(id) as id, account_id, `source`'))
        ->where('account_id', $account_id)
        ->where('traffic_type', $traffic_type)
        ->where('source', $source)
        ->where(function (QueryBuilder $query) use ($startDay, $endDay) {
            $this->conditionForDate($query, 'phone_time_use', $startDay, $endDay);
        })->groupBy(['account_id', 'source']);

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($query).')AS tbl set totalPhoneTimeUse = tbl.id where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.account_id = tbl.account_id AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "'.$source.'"'
        );
    }

    protected function updateTemporaryTableWithDailySpendingLimitForYss($clientId, $startDay, $endDay)
    {
        $yssCampaignModel = new RepoYssCampaignReportCost;
        $query = $yssCampaignModel
            ->select(DB::raw('SUM(dailySpendingLimit) AS dailySpendingLimit, accountid'))
            ->where('account_id', $clientId)
            ->where(function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_yss_campaign_report_cost');
            })->groupBy('accountid');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($query).')AS tbl set '
            .self::TEMPORARY_ACCOUNT_TABLE.'.dailySpendingLimit = tbl.dailySpendingLimit where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.accountid = tbl.accountid AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "yss"'
        );
    }

    protected function updateTemporaryTableWithDailySpendingLimitForAdw($clientId, $startDay, $endDay)
    {
        $adwCampaignModel = new RepoAdwCampaignReportCost;
        $query = $adwCampaignModel
            ->select(DB::raw('SUM(`budget`) AS dailySpendingLimit, customerID'))
            ->where('account_id', $clientId)
            ->where(function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_campaign_report_cost');
            })->groupBy('customerID');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($query).')AS tbl set '
            .self::TEMPORARY_ACCOUNT_TABLE.'.dailySpendingLimit = tbl.dailySpendingLimit where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.accountid = tbl.customerID AND '
            .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "adw"'
        );
    }

    protected function getRawExpressions($fieldNames)
    {
        $expressions = parent::getRawExpressions($fieldNames);
        foreach ($fieldNames as $fieldName) {
            switch ($fieldName) {
                case 'call_cv':
                    $expressions[] = DB::raw('SUM(`call_cv`) AS call_cv');
                    break;
                case 'call_cvr':
                    $expressions[] = DB::raw(
                        "((SUM(`web_cv`) + COUNT(`call_cv`)) "
                        . "/ SUM(`clicks`)) * 100 AS call_cvr"
                    );
                    break;
                case 'call_cpa':
                    $expressions[] = DB::raw(
                        "SUM(`cost`) / (SUM(`web_cv`) "
                        . "+ SUM(`call_cv`)) AS call_cpa"
                    );
                    break;
                case 'web_cv':
                    $expressions[] = DB::raw(
                        "SUM(`web_cv`) AS web_cv"
                    );
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw(
                        "(SUM(`web_cv`) / SUM(`clicks`) * 100) AS web_cvr"
                    );
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw(
                        "(SUM(`cost`) / SUM(`web_cv`)) AS web_cpa"
                    );
                    break;
                case 'dailySpendingLimit':
                    $expressions[] = DB::raw("SUM(`dailySpendingLimit`) AS dailySpendingLimit");
                    break;
                case 'total_cv':
                    $expressions[] = DB::raw("(SUM(`web_cv`) + SUM(`call_cv`)) as total_cv");
                    break;
                case 'total_cvr':
                    $expressions[] = DB::raw("((SUM(`web_cvr`) + SUM(`call_cvr`))/2) as total_cvr");
                    break;
                case 'total_cpa':
                    $expressions[] = DB::raw("(SUM(`web_cpa`) + SUM(`call_cpa`)) as total_cpa");
                    break;
            }
        }
        return $expressions;
    }

    protected function conditionForDate(QueryBuilder $query, $tableName, $startDay, $endDay)
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

    protected function getCampaignIdAdgainer($clientId)
    {
        $ydnModel = new RepoYdnReport;
        return $ydnModel->select('campaign_id', 'campaignID')
            ->distinct()
            ->where('account_id', '=', $clientId)
            ->get();
    }

    private function handlerFields($fieldNames)
    {
        $fieldNames = array_unique(array_merge($fieldNames, self::REQUIRED_FIELDS));
        $arrayFields = [];
        $fieldNeeds = $this->getFieldNeeds($fieldNames);
        foreach (self::DEFAULT_COLUMNS as $column) {
            if (in_array($column, $fieldNeeds)) {
                $arrayFields[] = $column;
            }
        }
        return $arrayFields;
    }

    private function getFieldNeeds($fieldNames)
    {
        $fieldNeeds = [];
        $related_field_keys = array_keys(self::RELATED_FIELDS);
        foreach ($fieldNames as $field) {
            if (in_array($field, $fieldNeeds)) {
                continue;
            }
            $fieldNeeds[] = $field;
            $fieldNeeds = $this->checkRelatedField($fieldNeeds, $field, $related_field_keys);
        }
        return $fieldNeeds;
    }

    private function checkRelatedField($fieldNeeds, $field, $related_field_keys)
    {
        if (in_array($field, $related_field_keys)) {
            foreach (self::RELATED_FIELDS[$field] as $related_field) {
                if (in_array($related_field, $fieldNeeds)) {
                    continue;
                }
                $fieldNeeds[] = $related_field;
                if (in_array($related_field, $related_field_keys)) {
                    $fieldNeeds = array_unique(array_merge($fieldNeeds, self::RELATED_FIELDS[$related_field]));
                }
            }
        }
        return $fieldNeeds;
    }
}
