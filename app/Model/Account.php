<?php

namespace App\Model;

use App\AbstractReportModel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use DateTime;
use Exception;

class Account extends AbstractReportModel
{
    const TABLE_ALIASES = [
        'adw',
        'ydn',
        'yss'
    ];

    const NUMBER_ADMIN = 1;

    const SUBQUERY_FIELDS = [
        'account_id',
        'clicks',
        'cost',
        'impressions',
        'ctr',
        'averageCpc',
        'averagePosition',
        'call_cv',
        'call_cvr',
        'call_cpa',
        'web_cv',
        'web_cvr',
        'web_cpa',
        'total_cv',
        'total_cvr',
        'total_cpa'
    ];

    protected $casts = [
        'call_cv' => 'integer',
        'web_cv' => 'integer',
        'total_cv' => 'integer',
        'ydn_web_cv' => 'integer',
        'yss_web_cv' => 'integer',
        'adw_web_cv' => 'integer',
        'ydn_call_cv' => 'integer',
        'yss_call_cv' => 'integer',
        'adw_call_cv' => 'integer'
    ];

    /** @var bool */
    public $timestamps = false;

    private function getSummedFieldNamesForTableAliases($fieldName)
    {
        $rawExpression = '';
        foreach (self::TABLE_ALIASES as $i => $tableAlias) {
            $rawExpression .= $tableAlias . '.' . $fieldName;
            if ($i < count(self::TABLE_ALIASES) - 1) {
                $rawExpression .= ' + ';
            }
        }

        return $rawExpression;
    }

    private function getRawExpression($fieldName)
    {
        if ($fieldName === 'ctr') {
            $rawExpression = DB::raw(
                'IFNULL((AVG(ydn.ctr) + AVG(yss.ctr) + AVG(adw.ctr)) / 3, 0) AS ctr'
            );
        } elseif ($fieldName === 'averageCpc') {
            $rawExpression = DB::raw(
                'IFNULL((AVG(ydn.averageCpc) + AVG(yss.averageCpc) + AVG(adw.averageCpc)) / 3, 0) '
                . 'AS averageCpc'
            );
        } elseif ($fieldName === 'averagePosition') {
            $rawExpression = DB::raw(
                'IFNULL((AVG(ydn.averagePosition) + AVG(yss.averagePosition) + '
                . 'AVG(adw.averagePosition)) / 3, 0) AS averagePosition'
            );
        } elseif ($fieldName === 'ydn_web_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn.web_cv), 0) AS ydn_web_cv'
            );
        } elseif ($fieldName === 'ydn_web_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(ydn.web_cvr), 0) AS ydn_web_cvr'
            );
        } elseif ($fieldName === 'ydn_web_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(ydn.web_cpa), 0) AS ydn_web_cpa'
            );
        } elseif ($fieldName === 'yss_web_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(yss.web_cv), 0) AS yss_web_cv'
            );
        } elseif ($fieldName === 'yss_web_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(yss.web_cvr), 0) AS yss_web_cvr'
            );
        } elseif ($fieldName === 'yss_web_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(yss.web_cpa), 0) AS yss_web_cpa'
            );
        } elseif ($fieldName === 'adw_web_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(adw.web_cv), 0) AS adw_web_cv'
            );
        } elseif ($fieldName === 'adw_web_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(adw.web_cvr), 0) AS adw_web_cvr'
            );
        } elseif ($fieldName === 'adw_web_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(adw.web_cpa), 0) AS adw_web_cpa'
            );
        } elseif ($fieldName === 'web_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn.web_cv) + SUM(yss.web_cv) + SUM(adw.web_cv), 0) AS web_cv'
            );
        } elseif ($fieldName === 'web_cvr') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn.web_cv) + SUM(yss.web_cv) + SUM(adw.web_cv)) / '
                . '(SUM(ydn.clicks) + SUM(yss.clicks) + SUM(adw.clicks)), 0) AS web_cvr'
            );
        } elseif ($fieldName === 'web_cpa') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn.cost) + SUM(yss.cost) + SUM(adw.cost)) / '
                . '(SUM(ydn.web_cv) + SUM(yss.web_cv) + SUM(adw.web_cv)), 0) AS web_cpa'
            );
        } elseif ($fieldName === 'ydn_call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn.call_cv), 0) AS ydn_call_cv'
            );
        } elseif ($fieldName === 'ydn_call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(ydn.call_cvr), 0) AS ydn_call_cvr'
            );
        } elseif ($fieldName === 'ydn_call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(ydn.call_cpa), 0) AS ydn_call_cpa'
            );
        } elseif ($fieldName === 'yss_call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(yss.call_cv), 0) AS yss_call_cv'
            );
        } elseif ($fieldName === 'yss_call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(yss.call_cvr), 0) AS yss_call_cvr'
            );
        } elseif ($fieldName === 'yss_call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(yss.call_cpa), 0) AS yss_call_cpa'
            );
        } elseif ($fieldName === 'adw_call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(adw.call_cv), 0) AS adw_call_cv'
            );
        } elseif ($fieldName === 'adw_call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(adw.call_cvr), 0) AS adw_call_cvr'
            );
        } elseif ($fieldName === 'adw_call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(adw.call_cpa), 0) AS adw_call_cpa'
            );
        } elseif ($fieldName === 'call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv), 0) AS call_cv'
            );
        } elseif ($fieldName === 'call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv)) / '
                . '(SUM(ydn.clicks) + SUM(yss.clicks) + SUM(adw.clicks)), 0) AS call_cvr'
            );
        } elseif ($fieldName === 'call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn.cost) + SUM(yss.cost) + SUM(adw.cost)) / '
                . '(SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv)), 0) AS call_cpa'
            );
        } elseif ($fieldName === 'total_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv) + SUM(ydn.web_cv) + '
                . 'SUM(yss.web_cv) + SUM(adw.web_cv), 0) AS total_cv'
            );
        } elseif ($fieldName === 'total_cvr') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv) + SUM(ydn.web_cv) + '
                . 'SUM(yss.web_cv) + SUM(adw.web_cv)) / '
                . '(SUM(ydn.clicks) + SUM(yss.clicks) + SUM(adw.clicks)), 0) AS total_cvr'
            );
        } elseif ($fieldName === 'total_cpa') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn.cost) + SUM(yss.cost) + SUM(adw.cost)) / '
                . '(SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv) + SUM(ydn.web_cv) + '
                . 'SUM(yss.web_cv) + SUM(adw.web_cv)), 0) AS total_cpa'
            );
        } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                $rawExpression = 'IFNULL(SUM('
                    . $this->getSummedFieldNamesForTableAliases($fieldName)
                    . '), 0) AS ' . $fieldName;
        } elseif (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $rawExpression = 'IFNULL(AVG('
                    . $this->getSummedFieldNamesForTableAliases($fieldName)
                    . '), 0) AS ' . $fieldName;
        } else {
            throw new \InvalidArgumentException('Unsupported field name provided: `' . $fieldName . '`!');
        }

        return DB::raw($rawExpression);
    }

    protected function getRawExpressions($fieldNames)
    {
        $rawExpression = [];
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, $this->groupByFieldName) || $fieldName === 'accountName') {
                $rawExpression[] = DB::raw($fieldName. ' AS agencyName');
            }

            $rawExpression[] = $this->getRawExpression($fieldName);
        }

        return $rawExpression;
    }

    public function getAllClient()
    {
        $yssClients = RepoYssAccountReportCost::select('account_id')->groupBy('account_id');
        $ydnClients = RepoYdnReport::select('account_id')->groupBy('account_id');
        $adwClients = RepoAdwAccountReportCost::select('account_id')->groupBy('account_id');
        $datas = $yssClients->union($ydnClients)->union($adwClients);
        $sql = $this->getBindingSql($datas);

        $datas = DB::table(DB::raw("accounts ,({$sql}) as tbl"))
            ->select(
                [
                    DB::raw('accounts.accountName'),
                    DB::raw('accounts.account_id')
                ]
            )
            ->where('level', '=', 3)
            ->where('agent_id', '!=', '')
            ->whereRaw('accounts.account_id = tbl.account_id');
        $clients = [];
        $clients['all'] = 'All Client';
        if ($datas) {
            foreach ($datas->get() as $key => $client) {
                $clients[] = (array)$client;
            }
        }
        return $clients;
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
        $arrAccountsAgency = self::select('account_id')
            ->where('level', '=', '3')
            ->where('agent_id', '!=', '')->get()->toArray();
        $modelYssAccount = new RepoYssAccountReportCost();
        $modelYdnAccount = new RepoYdnReport();
        $modelAdwAccount = new RepoAdwAccountReportCost();

        $dataGraphYss = $modelYssAccount->getGraphForAgencyYss($column, $startDay, $endDay, $arrAccountsAgency);
        $dataGraphYdn = $modelYdnAccount->getGraphForAgencyYdn($column, $startDay, $endDay, $arrAccountsAgency);
        $dataGraphAdw = $modelAdwAccount->getDataGraphForAdw($column, $startDay, $endDay, $arrAccountsAgency);

        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $dataGraphYss = $dataGraphYss->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $dataGraphYdn = $dataGraphYdn->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $dataGraphAdw = $dataGraphAdw->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }
        $datas = $dataGraphYss->union($dataGraphYdn)->union($dataGraphAdw);
        $sql = $this->getBindingSql($datas);
        $data = DB::table(DB::raw("accounts,({$sql}) as tbl"))
            ->select(DB::raw('day, sum(data) as data'))
            ->groupBy('day');
        if ($agencyId !== null) {
            $data->where('agent_id', '=', $agencyId);
        }
        return $data->get();
    }

    /**
     * @param $fieldNames
     * @param $accountStatus
     * @param $startDay
     * @param $endDay
     * @return array
     */
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
        $fieldNames = $this->unsetColumns($fieldNames, [$groupedByField]);
        return $this->calculateAllData($fieldNames, $startDay, $endDay, $accountStatus, $agencyId);
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
        array_unshift($fieldNames, self::FOREIGN_KEY_YSS_ACCOUNTS);
        $datas = $this->calculateAllData($fieldNames, $startDay, $endDay, $accountStatus, $agencyId);
        $data = [];
        foreach ($datas as $key => $val) {
            $data[$key] = $val;
        }
        return $data;
    }

    protected function getQueryBuilderForTable($select, $startDay, $endDay)
    {
        $modelYssAccount = new RepoYssAccountReportCost;
        $modelYdnAccount = new RepoYdnReport;
        $modelAdwAccount = new RepoAdwAccountReportCost;

        $yssAccountAgency = $modelYssAccount->getYssAccountAgency(self::SUBQUERY_FIELDS, $startDay, $endDay);
        $ydnAccountAgency = $modelYdnAccount->getYdnAccountAgency(self::SUBQUERY_FIELDS, $startDay, $endDay);
        $adwAccountAgency = $modelAdwAccount->getAdwAccountAgency(self::SUBQUERY_FIELDS, $startDay, $endDay);

        $query = $this->select($select)
            ->leftJoin(
                DB::raw('(' . $this->getBindingSql($yssAccountAgency) . ') AS yss'),
                'accounts.account_id',
                '=',
                'yss.account_id'
            )
            ->leftJoin(
                DB::raw('(' . $this->getBindingSql($ydnAccountAgency) . ') AS ydn'),
                'accounts.account_id',
                '=',
                'ydn.account_id'
            )
            ->leftJoin(
                DB::raw('(' . $this->getBindingSql($adwAccountAgency) . ') AS adw'),
                'accounts.account_id',
                '=',
                'adw.account_id'
            );

        return $query;
    }

    /**
     * @param string[] $fieldNames
     * @param string   $accountStatus
     * @param string   $startDay
     * @param string   $endDay
     * @param int      $pagination
     * @param string   $columnSort
     * @param string   $sort
     * @param string   $groupedByField
     * @param int|null $agencyId
     * @param int|null $accountId
     * @param int|null $clientId
     * @param int|null $campaignId
     * @param int|null $adGroupId
     * @param int|null $adReportId
     * @param int|null $keywordId
     * @return array
     */
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
        try {
            new DateTime($startDay); //NOSONAR
            new DateTime($endDay); //NOSONAR
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }

        $getAgreatedAgency = $this->getAggregatedAgency($fieldNames, 'clientName');

        $query = $this->getQueryBuilderForTable($getAgreatedAgency, $startDay, $endDay)
            ->where('level', '=', 3)
            ->where('agent_id', '!=', '')
            ->orderBy($columnSort, $sort);

        $this->addConditionAgency($query, $agencyId);

        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $query = $query->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO_OF_CLIENT);
        }

        $datas = [];
        foreach ($query->get() as $report) {
            $datas[] = $report->toArray();
        }

        return $datas;
    }

    /**
     * @param array   $fieldNames
     * @param string  $startDay
     * @param string  $endDay
     * @param string  $accountStatus
     * @param integer $agencyId
     * @return array
     */
    public function calculateAllData(array $fieldNames, $startDay, $endDay, $accountStatus, $agencyId)
    {
        $modelYssAccount = new RepoYssAccountReportCost;
        $modelYdnAccount = new RepoYdnReport;
        $modelAdwAccount = new RepoAdwAccountReportCost;

        $yssAccountAgency = $modelYssAccount->getYssAccountAgency($fieldNames, $startDay, $endDay);
        $ydnAccountAgency = $modelYdnAccount->getYdnAccountAgency($fieldNames, $startDay, $endDay);
        $adwAccountAgency = $modelAdwAccount->getAdwAccountAgency($fieldNames, $startDay, $endDay);

        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $yssAccountAgency = $yssAccountAgency->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $ydnAccountAgency = $ydnAccountAgency->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $adwAccountAgency = $adwAccountAgency->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }

        $fieldNames = $this->unsetColumns($fieldNames, ['account_id']);

        $rawExpressions = $this->getRawExpressions($fieldNames);

        $query = $this->select($rawExpressions)
            ->leftJoin(
                DB::raw('(' . $this->getBindingSql($yssAccountAgency) . ') AS yss'),
                'accounts.account_id',
                '=',
                'yss.account_id'
            )
            ->leftJoin(
                DB::raw('(' . $this->getBindingSql($ydnAccountAgency) . ') AS ydn'),
                'accounts.account_id',
                '=',
                'ydn.account_id'
            )
            ->leftJoin(
                DB::raw('(' . $this->getBindingSql($adwAccountAgency) . ') AS adw'),
                'accounts.account_id',
                '=',
                'adw.account_id'
            )
            ->where('level', '=', 3)
            ->where('agent_id', '!=', '');

        $this->addConditionAgency($query, $agencyId);

        $totals = $query->first();
        if ($totals === null) {
            return [
                'clicks' => 0,
                'impressions' => 0,
                'cost' => 0,
                'averageCpc' => 0,
                'averagePosition' => 0
            ];
        }

        return $totals->toArray();
    }

    public function getAggregatedAgency(
        array $fieldNames,
        $accountNameAlias = 'clientName',
        $accountNameValue = 'accountName'
    ) {
        $arrayCalculate = [];
        $tableName = $this->getTable();
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === 'accountName') {
                if ($accountNameValue === 'accountName') {
                    $arrayCalculate[] = DB::raw($tableName . '.' . $fieldName . ' AS ' . $accountNameAlias);
                } else {
                    $arrayCalculate[] = DB::raw($accountNameValue . ' AS ' . $accountNameAlias);
                }
            }
            if ($fieldName === self::FOREIGN_KEY_YSS_ACCOUNTS) {
                $arrayCalculate[] = DB::raw('accounts.account_id AS account_id');
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((adw.'. $fieldName .' +  ydn.'.$fieldName.' + yss.'.$fieldName.')/3, 0) AS '.$fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw.'. $fieldName . ' +  ydn.' . $fieldName . ' + yss.' . $fieldName . ', 0)'
                    . ' AS '. $fieldName
                );
            } elseif ($fieldName === 'ydn_web_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn.web_cv, 0) AS ydn_web_cv'
                );
            } elseif ($fieldName === 'ydn_web_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn.web_cvr, 0) AS ydn_web_cvr'
                );
            } elseif ($fieldName === 'ydn_web_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn.web_cpa, 0) AS ydn_web_cpa'
                );
            } elseif ($fieldName === 'yss_web_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss.web_cv, 0) AS yss_web_cv'
                );
            } elseif ($fieldName === 'yss_web_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss.web_cvr, 0) AS yss_web_cvr'
                );
            } elseif ($fieldName === 'yss_web_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss.web_cpa, 0) AS yss_web_cpa'
                );
            } elseif ($fieldName === 'adw_web_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw.web_cv, 0) AS adw_web_cv'
                );
            } elseif ($fieldName === 'adw_web_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw.web_cvr, 0) AS adw_web_cvr'
                );
            } elseif ($fieldName === 'adw_web_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw.web_cpa, 0) AS adw_web_cpa'
                );
            } elseif ($fieldName === 'web_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn.web_cv + yss.web_cv + adw.web_cv, 0) AS web_cv'
                );
            } elseif ($fieldName === 'web_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn.web_cv + yss.web_cv + adw.web_cv) / '
                    . '(ydn.clicks + yss.clicks + adw.clicks), 0) AS web_cvr'
                );
            } elseif ($fieldName === 'web_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn.cost + yss.cost + adw.cost) / '
                    . '(ydn.web_cv + yss.web_cv + adw.web_cv), 0) AS web_cpa'
                );
            } elseif ($fieldName === 'ydn_call_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn.call_cv, 0) AS ydn_call_cv'
                );
            } elseif ($fieldName === 'ydn_call_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn.call_cvr, 0) AS ydn_call_cvr'
                );
            } elseif ($fieldName === 'ydn_call_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn.call_cpa, 0) AS ydn_call_cpa'
                );
            } elseif ($fieldName === 'yss_call_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss.call_cv, 0) AS yss_call_cv'
                );
            } elseif ($fieldName === 'yss_call_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss.call_cvr, 0) AS yss_call_cvr'
                );
            } elseif ($fieldName === 'yss_call_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss.call_cpa, 0) AS yss_call_cpa'
                );
            } elseif ($fieldName === 'adw_call_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw.call_cv, 0) AS adw_call_cv'
                );
            } elseif ($fieldName === 'adw_call_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw.call_cvr, 0) AS adw_call_cvr'
                );
            } elseif ($fieldName === 'adw_call_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw.call_cpa, 0) AS adw_call_cpa'
                );
            } elseif ($fieldName === 'call_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn.call_cv + yss.call_cv + adw.call_cv, 0) AS call_cv'
                );
            } elseif ($fieldName === 'call_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn.call_cv + yss.call_cv + adw.call_cv) / '
                    . '(ydn.clicks + yss.clicks + adw.clicks), 0) AS call_cvr'
                );
            } elseif ($fieldName === 'call_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn.cost + yss.cost + adw.cost) / '
                    . '(ydn.call_cv + yss.call_cv + adw.call_cv), 0) AS call_cpa'
                );
            } elseif ($fieldName === 'total_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn.call_cv + yss.call_cv + adw.call_cv + ydn.web_cv + '
                    . 'yss.web_cv + adw.web_cv, 0) AS total_cv'
                );
            } elseif ($fieldName === 'total_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn.call_cv + yss.call_cv + adw.call_cv + ydn.web_cv + yss.web_cv + adw.web_cv) / '
                    . '(ydn.clicks + yss.clicks + adw.clicks), 0) AS total_cvr'
                );
            } elseif ($fieldName === 'total_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn.cost + yss.cost + adw.cost) / '
                    . '(ydn.call_cv + yss.call_cv + adw.call_cv + ydn.web_cv + yss.web_cv + '
                    . 'adw.web_cv), 0) AS total_cpa'
                );
            }
        }

        return $arrayCalculate;
    }

    public function addConditionAgency(Builder $query, $agencyId)
    {
        if ($agencyId !== null) {
            $query->where('accounts.agent_id', '=', $agencyId);
        }
    }

    public function checkConditonForBreadcumbs($title)
    {
        if (($title === 'Agency' && !$this->isAdmin(Auth::user()->account_id))
            || ($title === 'Client' && !$this->isAgency(Auth::user()->account_id)
            && !$this->isAdmin(Auth::user()->account_id))

        ) {
            return true;
        }

        return false;
    }

    public function isAdmin($account_id)
    {
        $admin = self::select('account_id')
            ->where('level', '=', self::NUMBER_ADMIN)
            ->where('account_id', '=', $account_id)
            ->get();

        if (!$admin->isEmpty()) {
            return true;
        }

        return false;
    }

    public function isAgency($account_id)
    {
        $agency = self::select('account_id')
            ->whereIn(
                'account_id',
                function ($query) {
                    $query->select(DB::raw('agent_id'))
                        ->from('accounts')
                        ->where('agent_id', '!=', '')
                        ->get();
                }
            )
            ->where('account_id', '=', $account_id)
            ->where('level', '=', 3)
            ->where('agent_id', '=', '')
            ->get();

        if (!$agency->isEmpty()) {
            return true;
        }

        return false;
    }
}
