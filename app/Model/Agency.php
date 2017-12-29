<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;

use DateTime;
use Exception;

class Agency extends Account
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
        array_unshift($fieldNames, 'account_id');
        $agencyAggregations = $this->getAggregatedAgency($fieldNames, 'agencyName', 'parentAccounts');
        $agencyClientQuery = $this->getQueryBuilderForTable($agencyAggregations, $startDay, $endDay)
            ->leftJoin(
                DB::raw(' accounts AS parentAccounts'),
                'accounts.agent_id',
                '=',
                'parentAccounts.account_id'
            )
            ->whereRaw(
                "`accounts`.`agent_id` = `parentAccounts`.`account_id`"
            );

        $directClientAggregations = $this->getAggregatedAgency(
            $fieldNames,
            'agencyName',
            "'directClients'"
        );
        $directClientQuery = $this->getQueryBuilderForTable($directClientAggregations, $startDay, $endDay)
            ->where('accounts.level', '=', 3)
            ->where('accounts.agent_id', '=', '')
            ->whereRaw(
                "(SELECT COUNT(b.`id`) FROM `accounts` AS b WHERE b.`agent_id` = `accounts`.account_id) = 0"
            );

        $unionQuery = $agencyClientQuery->union($directClientQuery);
        if ($accountStatus === self::HIDE_ZERO_STATUS) {
            $unionQuery->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO_OF_CLIENT);
        }
        $outerQuery = DB::query()
            ->from(DB::raw("({$this->getBindingSql($unionQuery)}) AS tbl"))
            ->orderBy($columnSort, $sort)
            ->groupBy('agencyName');
        $results = $outerQuery->get();
        return isset($results) ? $results->toArray() : [];
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
        $fieldNames = $this->unsetColumns($fieldNames, ['accountName', 'account_id']);

        $rawExpressions = $this->getRawExpressions($fieldNames);
        $agencyTotalsQuery = $this->getQueryBuilderForTable($rawExpressions, $startDay, $endDay)
            ->leftJoin(
                DB::raw(' accounts AS parentAccounts'),
                'accounts.agent_id',
                '=',
                'parentAccounts.account_id'
            );

        $result = $agencyTotalsQuery->first();

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

        return $result->toArray();
    }
    /**
     * @param string $column
     * @param string $accountStatus
     * @param string $startDay
     * @param string $endDay
     * @return \Illuminate\Support\Collection
     * @throws \InvalidArgumentException
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
        $modelYssReport = new RepoYssAccountReportCost();
        $modelYdnReport = new RepoYdnReport();
        $modelAdwReport = new RepoAdwAccountReportCost();
        $yssAccountDataForGraph = $modelYssReport->yssAccountDataForGraphOfAgencyList($column, $startDay, $endDay);
        $ydnAccountDataForGraph = $modelYdnReport->ydnAccountDataForGraphOfAgencyList($column, $startDay, $endDay);
        $adwAccountDataForGraph = $modelAdwReport->adwAccountDataForGraphOfAgencyList($column, $startDay, $endDay);

        $data = $ydnAccountDataForGraph->union($yssAccountDataForGraph)->union($adwAccountDataForGraph);
        $sql = $this->getBindingSql($data);
        $data = DB::table(DB::raw("({$sql}) as tbl"))
            ->select(DB::raw('day, sum(data) as data'))
            ->groupBy('day');

        $data = $data->get();
        return $data;
    }

    //get Agencies to display on breadcrumbs
    public function getAllAgencies()
    {
        $arrayOfDirectClientsAndAgencies = self::select('account_id', 'accountName')
            ->where('accounts.level', '=', 3)
            ->where('accounts.agent_id', '=', '')
            ->whereRaw(
                "(SELECT COUNT(b.`id`) FROM `accounts` AS b WHERE b.`agent_id` = `accounts`.account_id) > 0"
            )
            ->get()->toArray();

        $arr = ['all' => 'All Agencies'];

        return $arr + $arrayOfDirectClientsAndAgencies;
    }
}
