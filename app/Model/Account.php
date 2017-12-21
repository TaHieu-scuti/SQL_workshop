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

    /** @var bool */
    public $timestamps = false;
    const NUMBER_ADMIN = 1;

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
        if (in_array($fieldName, static::SUM_FIELDS)) {
            $rawExpression = 'sum('
                . $this->getSummedFieldNamesForTableAliases($fieldName)
                . ') as ' . $fieldName;
        } elseif (in_array($fieldName, static::AVERAGE_FIELDS)) {
            $rawExpression = 'avg('
                . $this->getSummedFieldNamesForTableAliases($fieldName)
                . ') as ' . $fieldName;
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
     * @todo use the same way to build the query as in calculateAllData
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

        $modelYssAccount = new RepoYssAccountReportCost;
        $modelYdnAccount = new RepoYdnReport;
        $modelAdwAccount = new RepoAdwAccountReportCost;

        $yssAccountAgency = $modelYssAccount->getYssAccountAgency($fieldNames, $startDay, $endDay);
        $ydnAccountAgency = $modelYdnAccount->getYdnAccountAgency($fieldNames, $startDay, $endDay);
        $adwAccountAgency = $modelAdwAccount->getAdwAccountAgency($fieldNames, $startDay, $endDay);

        $getAgreatedAgency = $this->getAggregatedAgency($fieldNames);

        $query = $this->select($getAgreatedAgency)
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

    public function getAggregatedAgency(array $fieldNames)
    {
        $arrayCalculate = [];
        $tableName = $this->getTable();
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === 'accountName') {
                $arrayCalculate[] = DB::raw($tableName.'.'.$fieldName .' AS clientName');
            }
            if ($fieldName === self::FOREIGN_KEY_YSS_ACCOUNTS) {
                $arrayCalculate[] = DB::raw('accounts.account_id AS account_id');
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    '(adw.'. $fieldName .' +  ydn.'.$fieldName.' + yss.'.$fieldName.')/3 AS '.$fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'adw.'. $fieldName .' +  ydn.'.$fieldName.' + yss.'.$fieldName.' AS '.$fieldName
                );
            }
        }
        return $arrayCalculate;
    }

    public function addConditionAgency(Builder $query, $agencyId)
    {
        if ($agencyId !== null) {
            $query->where('accounts.agent_id', '', $agencyId);
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
