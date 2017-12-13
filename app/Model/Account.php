<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;
use Illuminate\Database\Eloquent\Builder;
use App\Model\RepoYssAccountReportCost;
use App\Model\RepoYdnReport;
use App\Model\RepoAdwAccountReportCost;
use Illuminate\Support\Facades\Event;
use DateTime;
use Exception;
use DB;

class Account extends AbstractReportModel
{
    /** @var bool */
    public $timestamps = false;

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
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    )
    {
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
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $this->unsetColumns($fieldNames, [$groupedByField]);
        return $this->calculateAllData($fieldNames, $startDay, $endDay, $accountStatus);
    }

    public function calculateSummaryData(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        array_unshift($fieldNames, self::FOREIGN_KEY_YSS_ACCOUNTS);
        $datas = $this->calculateAllData($fieldNames, $startDay, $endDay, $accountStatus);
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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
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
        $accountId = null,
        $adgainerId = null,
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
        $modelYssAccount = new RepoYssAccountReportCost();
        $modelYdnAccount = new RepoYdnReport();
        $modelAdwAccount = new RepoAdwAccountReportCost();
        $getAggregatedYssAccounts = $modelYssAccount->getAggregatedAgency($fieldNames);
        $getAggregatedYdnAccounts = $modelYdnAccount->getAggregatedAgency($fieldNames);
        $getAggregatedAdwAccounts = $modelAdwAccount->getAggregatedAgency($fieldNames);
        $getAgreatedAgency = $this->getAgreatedAgency($fieldNames);
        $arrAccountsAgency = DB::query()->select($getAgreatedAgency)
            ->from(
                DB::raw('accounts,' .
                    '(Select '. implode(',', $getAggregatedYssAccounts) .' from repo_yss_account_report_cost 
                        WHERE `repo_yss_account_report_cost`.`day` >= "'.$startDay.'"
                        AND `repo_yss_account_report_cost`.`day` <= "'.$endDay.'"
                        GROUP BY `repo_yss_account_report_cost`.`account_id`
                    ) AS yss,
                    (Select '. implode(',',$getAggregatedYdnAccounts) .' from repo_ydn_reports 
                        WHERE `repo_ydn_reports`.`day` >= "'.$startDay.'"
                        AND `repo_ydn_reports`.`day` <= "'.$endDay.'"
                        GROUP BY `repo_ydn_reports`.`account_id`
                    ) AS ydn,
                    (Select '. implode(',',$getAggregatedAdwAccounts) .' from repo_adw_account_report_cost 
                        WHERE `repo_adw_account_report_cost`.`day` >= "'.$startDay.'"
                        AND `repo_adw_account_report_cost`.`day` <= "'.$endDay.'"
                        GROUP BY `repo_adw_account_report_cost`.`account_id`
                    ) AS adw'
                )
            )
            ->where('level', '=', 3)
            ->where('agent_id', '!=', '')
            ->whereRaw('accounts.account_id = yss.account_id')
            ->whereRaw('accounts.account_id = ydn.account_id')
            ->whereRaw('accounts.account_id = adw.account_id')
            ->orderBy($columnSort, $sort);

        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $arrAccountsAgency = $arrAccountsAgency->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }
//        var_dump($arrAccountsAgency->toSql());
        $datas = [];
        foreach ($arrAccountsAgency->get() as $report) {
            $datas[] = (array)$report;
        }
        return $datas;
    }

    public function calculateAllData(array $fieldNames, $startDay, $endDay, $accountStatus)
    {
        $modelYssAccount = new RepoYssAccountReportCost();
        $modelYdnAccount = new RepoYdnReport();
        $modelAdwAccount = new RepoAdwAccountReportCost();

        $yssAccountAgency = $modelYssAccount->getYssAccountAgency($fieldNames, $startDay, $endDay);
        $ydnAccountAgency = $modelYdnAccount->getYdnAccountAgency($fieldNames, $startDay, $endDay);
        $adwAccountAgency = $modelAdwAccount->getAdwAccountAgency($fieldNames, $startDay, $endDay);

        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $yssAccountAgency = $yssAccountAgency->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $ydnAccountAgency = $ydnAccountAgency->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $adwAccountAgency = $adwAccountAgency->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }

        $datas = $yssAccountAgency->union($ydnAccountAgency)->union($adwAccountAgency);

        Event::listen(StatementPrepared::class, function ($event) {
            $event->statement->setFetchMode(PDO::FETCH_ASSOC);
        });
        $fieldNames = $this->unsetColumns($fieldNames, ['account_id']);
        $sql = $this->getBindingSql($datas);
        $rawExpressions = $this->getRawExpressions($fieldNames);

        $datas = DB::table(DB::raw("accounts ,({$sql}) as tbl"))
            ->select(
                $rawExpressions
            )
            ->where('level', '=', 3)
            ->where('agent_id', '!=', '')
            ->whereRaw('accounts.account_id = tbl.account_id');

        $datas = $datas->first();
        if ($datas === null) {
            $datas = [
                'clicks' => 0,
                'impressions' => 0,
                'cost' => 0,
                'averageCpc' => 0,
                'averagePosition' => 0
            ];
        }

        return $datas;
    }

    public function getAgreatedAgency(array $fieldNames)
    {
        $arrayCalculate = [];
        $tableName = $this->getTable();
        foreach ($fieldNames as  $fieldName) {
            if ($fieldName === 'accountName') {
                $arrayCalculate[] = DB::raw($tableName.'.'.$fieldName .' AS '.$fieldName);
            }
            if ($fieldName === self::FOREIGN_KEY_YSS_ACCOUNTS) {
                $arrayCalculate[] = DB::raw('accounts.account_id AS account_id');
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw('(adw.'. $fieldName .' +  ydn.'.$fieldName.' + yss.'.$fieldName.')/3 AS '.$fieldName);
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                $arrayCalculate[] = DB::raw('adw.'. $fieldName .' +  ydn.'.$fieldName.' + yss.'.$fieldName.' AS '.$fieldName);
            }
        }
        return $arrayCalculate;
    }
}
