<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;

use DateTime;
use Exception;

class DirectClient extends Account
{
    const AVERAGE_FIELDS = [
        'averageCpc',
        'averagePosition',
        'ctr'
    ];

    const SUM_FIELDS = [
        'clicks',
        'impressions',
        'cost'
    ];

    const ADW_FIELDS = [
        'clicks' => 'clicks',
        'cost' => 'cost',
        'impressions' => 'impressions',
        'ctr' => 'ctr',
        'averageCpc' => 'avgCPC',
        'averagePosition' => 'avgPosition'
    ];

    protected $table = 'accounts';

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
            ->whereNotIn(
                'account_id',
                function ($query) {
                    $query->select('agent_id')
                        ->from('accounts')
                        ->where('agent_id', '!=', '');
                }
            )
            ->where('level', '=', '3')
            ->where('agent_id', '=', '')->get()->toArray();
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
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $this->createTemporaryAccountTable();
        $directClientAggregations = $this->getAggregatedForAgencyReportFromTemporaryTable(
            $fieldNames,
            'directClients'
        );
        $sql = $this->select('account_id', 'accountName')
            ->where('accounts.level', '=', 3)
            ->where('accounts.agent_id', '=', '')
            ->whereRaw(
                "(SELECT COUNT(b.`id`) FROM `accounts` AS b WHERE b.`agent_id` = `accounts`.account_id) = 0"
            );

        DB::insert('INSERT into '. self::TEMPORARY_ACCOUNT_TABLE .'(account_id, accountName)'
            . $this->getBindingSql($sql));

        $this->getAccountYss($startDay, $endDay);
        $this->getAccountYdn($startDay, $endDay);
        $this->getAccountAdw($startDay, $endDay);

        $builder = DB::table(self::TEMPORARY_ACCOUNT_TABLE)
            ->select($directClientAggregations)
            ->orderBy($columnSort, $sort)
            ->groupby('directClients');

        if ($accountStatus === self::HIDE_ZERO_STATUS) {
            $builder->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO_OF_CLIENT);
        }

        $results = $builder->get();

        return isset($results) ? $results->toArray() : [];
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
        $fieldNames = $this->unsetColumns($fieldNames, ['accountName', 'account_id']);

        $rawExpressions = $this->getRawExpressions($fieldNames, self::TEMPORARY_ACCOUNT_TABLE);

        $builder = DB::table(self::TEMPORARY_ACCOUNT_TABLE)
            ->select($rawExpressions);

        $result = $builder->first();

        if ($result === null) {
            return [];
        }

        return $result;
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
        $result = $this->calculateData(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $groupedByField = null,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );

        if (empty($result)) {
            return [
                'impressions' => 0,
                'clicks' => 0,
                'cost' => 0,
                'averageCpc' => 0,
                'averagePosition' => 0
            ];
        }

        return (array) $result;
    }
}
