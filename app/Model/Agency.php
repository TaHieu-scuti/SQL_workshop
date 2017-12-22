<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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
        $agencyAggregations = $this->getAggregatedAgency($fieldNames, 'agencyName');
        $agencyClientQuery = $this->getQueryBuilderForTable($agencyAggregations, $startDay, $endDay)
            ->where('accounts.level', '=', 3)
            ->where('accounts.agent_id', '=', '')
            ->whereRaw(
                "(SELECT COUNT(b.`id`) FROM `accounts` AS b WHERE b.`agent_id` = `accounts`.account_id) > 0"
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

        $outerQuery = DB::query()
            ->from(DB::raw("({$this->getBindingSql($unionQuery)}) AS tbl"))
            ->orderBy($columnSort, $sort)
            ->groupBy('agencyName');

        return $outerQuery->get();
    }

    public function getDataOfAgencies(
        array $fieldNames,
        $startDay,
        $endDay
    ) {
        $arrayOfDirectClientsAndAgencies = self::select('account_id')
                ->whereIn(
                    'agent_id',
                    function ($query) {
                        $query->select(DB::raw('account_id'))
                            ->from('accounts')
                            ->where('agent_id', '=', '');
                    }
                )
                ->where('level', '=', 3)
                ->get()->toArray();
        $yssTableName = (new RepoYssAccountReportCost)->getTable();
        $ydnTableName = (new RepoYdnReport)->getTable();
        $adwTableName = (new RepoAdwAccountReportCost)->getTable();
        $yssAccountAggregation = $this->getAggregatedOfAccountTable($fieldNames, $yssTableName);
        array_unshift($yssAccountAggregation, 'account_id');
        $ydnAccountAggregation = $this->getAggregatedOfAccountTable($fieldNames, $ydnTableName);
        array_unshift($ydnAccountAggregation, 'account_id');
        $adwAccountAggregation = $this->getAggregatedOfGoogleAccountTable($fieldNames, $adwTableName);
        array_unshift($adwAccountAggregation, 'account_id');
        $yssAccountData = RepoYssAccountReportCost::select($yssAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        )
                        ->whereIn('account_id', $arrayOfDirectClientsAndAgencies)
                        ->groupBy('account_id');
        $ydnAccountData = RepoYdnReport::select($ydnAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        )
                        ->whereIn('account_id', $arrayOfDirectClientsAndAgencies)
                        ->groupBy('account_id');
        $adwAccountData = RepoAdwAccountReportCost::select($adwAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        )
                        ->whereIn('account_id', $arrayOfDirectClientsAndAgencies)
                        ->groupBy('account_id');
        $data = $yssAccountData
            ->union($ydnAccountData)
            ->union($adwAccountData);

        $sql = $this->getBindingSql($data);
        $rawExpressions = $this->getRawExpressions($fieldNames);
        // array_unshift the account name into rawExpressions to get agency name
        $arrayOfAgencyData = DB::table(DB::raw("accounts,({$sql}) as tbl"))
                ->select(
                    $rawExpressions
                )
                ->where('level', '=', 3)
                ->where('agent_id', '=', '')
                ->whereIn(
                    'accounts.account_id',
                    function ($query) use ($arrayOfDirectClientsAndAgencies) {
                        $query->select('agent_id')
                            ->from('accounts')
                            ->where('agent_id', '!=', '')
                            ->whereIn('account_id', $arrayOfDirectClientsAndAgencies)
                            ->whereRaw('accounts.account_id = tbl.account_id');
                    }
                )
                ->groupBy('accountName');
        return $arrayOfAgencyData;
    }

    public function getDataOfDirectClients(
        array $fieldNames,
        $startDay,
        $endDay
    ) {
        $arrayOfDirectClientsAndAgencies = self::select('account_id')
                ->whereNotIn(
                    'account_id',
                    function ($query) {
                        $query->select(DB::raw('agent_id'))
                            ->from('accounts')
                            ->where('agent_id', '!=', '');
                    }
                )
                ->where('level', '=', 3)
                ->where('agent_id', '=', '')->get()->toArray();
        $yssTableName = (new RepoYssAccountReportCost)->getTable();
        $ydnTableName = (new RepoYdnReport)->getTable();
        $adwTableName = (new RepoAdwAccountReportCost)->getTable();
        $yssAccountAggregation = $this->getAggregatedOfAccountTable($fieldNames, $yssTableName);
        $ydnAccountAggregation = $this->getAggregatedOfAccountTable($fieldNames, $ydnTableName);
        $adwAccountAggregation = $this->getAggregatedOfGoogleAccountTable($fieldNames, $adwTableName);
        $yssAccountData = RepoYssAccountReportCost::select($yssAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        )
                        ->whereIn('account_id', $arrayOfDirectClientsAndAgencies);
        $ydnAccountData = RepoYdnReport::select($ydnAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        )
                        ->whereIn('account_id', $arrayOfDirectClientsAndAgencies);
        $adwAccountData = RepoAdwAccountReportCost::select($adwAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        )
                        ->whereIn('account_id', $arrayOfDirectClientsAndAgencies);
        $directClientsData = $yssAccountData
            ->union($ydnAccountData)
            ->union($adwAccountData);

        $sql = $this->getBindingSql($directClientsData);
        $fieldNames = $this->unsetColumns($fieldNames, ['accountName']);
        $rawExpressions = $this->getRawExpressions($fieldNames);
        array_unshift($rawExpressions, DB::raw("'directClient' AS agencyName"));
        $directClientsData = DB::table(DB::raw("accounts,({$sql}) as tbl"))
                ->select(
                    $rawExpressions
                )
                ->where('level', '=', 3)
                ->where('agent_id', '=', '');
        return $directClientsData;
    }

    protected function getAggregatedOfAccountTable(array $fieldNames, $tableName)
    {
        foreach ($fieldNames as $key => $fieldName) {
            if ($fieldName === 'account_id') {
                $arrayCalculate[] = $fieldName;
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'ROUND(AVG(' . $tableName . '.' . $fieldName . '), 2) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                                    ->getType()
                                    ->getName()
                    === self::FIELD_TYPE
                ) {
                    $arrayCalculate[] = DB::raw(
                        'ROUND(SUM(' . $tableName . '.' . $fieldName . '), 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw(
                        'SUM( ' . $tableName . '.' . $fieldName . ' ) AS ' . $fieldName
                    );
                }
            }
        }
        return $arrayCalculate;
    }

    protected function getAggregatedOfGoogleAccountTable(array $fieldNames, $tableName)
    {
        foreach ($fieldNames as $key => $fieldName) {
            if ($fieldName === 'account_id') {
                $arrayCalculate[] = $fieldName;
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'ROUND(AVG(' . $tableName . '.' . self::ADW_FIELDS[$fieldName] . '), 2) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                                    ->getType()
                                    ->getName()
                    === self::FIELD_TYPE
                ) {
                    $arrayCalculate[] = DB::raw(
                        'ROUND(SUM(' . $tableName . '.' . self::ADW_FIELDS[$fieldName] . '), 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw(
                        'SUM( ' . $tableName . '.' . self::ADW_FIELDS[$fieldName] . ' ) AS ' . $fieldName
                    );
                }
            }
        }
        return $arrayCalculate;
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
        $fieldNames = $this->unsetColumns($fieldNames, ['accountName']);
        $yssTableName = (new RepoYssAccountReportCost)->getTable();
        $ydnTableName = (new RepoYdnReport)->getTable();
        $adwTableName = (new RepoAdwAccountReportCost)->getTable();
        $yssAccountAggregation = $this->getAggregatedOfAccountTable($fieldNames, $yssTableName);
        $ydnAccountAggregation = $this->getAggregatedOfAccountTable($fieldNames, $ydnTableName);
        $adwAccountAggregation = $this->getAggregatedOfGoogleAccountTable($fieldNames, $adwTableName);
        $yssAccountData = RepoYssAccountReportCost::select($yssAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        );
        $ydnAccountData = RepoYdnReport::select($ydnAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        );
        $adwAccountData = RepoAdwAccountReportCost::select($adwAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        );
        $agenciesAndDirectClientsData = $yssAccountData
            ->union($ydnAccountData)
            ->union($adwAccountData);

        $sql = $this->getBindingSql($agenciesAndDirectClientsData);
        $rawExpressions = $this->getRawExpressions($fieldNames);

        $agenciesAndDirectClientsData = DB::table(DB::raw("accounts,({$sql}) as tbl"))
                ->select(
                    $rawExpressions
                )->first();
        if ($agenciesAndDirectClientsData === null) {
            $agenciesAndDirectClientsData = [];
        }
        return $agenciesAndDirectClientsData;
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
        $yssTableName = (new RepoYssAccountReportCost)->getTable();
        $ydnTableName = (new RepoYdnReport)->getTable();
        $adwTableName = (new RepoAdwAccountReportCost)->getTable();
        $yssAccountAggregation = $this->getAggregatedOfAccountTable($fieldNames, $yssTableName);
        $ydnAccountAggregation = $this->getAggregatedOfAccountTable($fieldNames, $ydnTableName);
        $adwAccountAggregation = $this->getAggregatedOfGoogleAccountTable($fieldNames, $adwTableName);
        $yssAccountData = RepoYssAccountReportCost::select($yssAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        );
        $ydnAccountData = RepoYdnReport::select($ydnAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        );
        $adwAccountData = RepoAdwAccountReportCost::select($adwAccountAggregation)
                        ->where(
                            function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                            }
                        );
        $agenciesAndDirectClientsData = $yssAccountData
            ->union($ydnAccountData)
            ->union($adwAccountData);

        $sql = $this->getBindingSql($agenciesAndDirectClientsData);
        $rawExpressions = $this->getRawExpressions($fieldNames);

        $agenciesAndDirectClientsData = DB::table(DB::raw("accounts,({$sql}) as tbl"))
                ->select(
                    $rawExpressions
                )->first();
        if ($agenciesAndDirectClientsData === null) {
            $agenciesAndDirectClientsData = [
                'impressions' => 0,
                'clicks' => 0,
                'cost' => 0,
                'averageCpc' => 0,
                'averagePosition' => 0
            ];
        } else {
            $agenciesAndDirectClientsData = (array)$agenciesAndDirectClientsData;
        }
        return $agenciesAndDirectClientsData;
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
        $arrayOfDirectClientsAndAgencies = self::select('account_id')
            ->whereIn(
                'agent_id',
                function ($query) {
                    $query->select(DB::raw('account_id'))
                        ->from('accounts')
                        ->where('agent_id', '=', '');
                }
            )
            ->where('level', '=', 3)
            ->get()->toArray();

        $yssClients = RepoYssAccountReportCost::select('account_id')
                    ->whereIn('account_id', $arrayOfDirectClientsAndAgencies)->groupBy('account_id');
        $ydnClients = RepoYdnReport::select('account_id')
                    ->whereIn('account_id', $arrayOfDirectClientsAndAgencies)->groupBy('account_id');
        $adwClients = RepoAdwAccountReportCost::select('account_id')
                    ->whereIn('account_id', $arrayOfDirectClientsAndAgencies)->groupBy('account_id');
        $arr = ['all' => 'All Agencies'];

        $clientUnionArray = $yssClients->union($ydnClients)->union($adwClients);
        $sql = $this->getBindingSql($clientUnionArray);
        $data = DB::table(DB::raw("accounts ,({$sql}) as tbl"))
            ->select(
                [
                    DB::raw('accounts.accountName'),
                    DB::raw('accounts.account_id')
                ]
            )
            ->where('level', '=', 3)
            ->where('agent_id', '=', '')
            ->whereIn(
                'accounts.account_id',
                function ($query) use ($arrayOfDirectClientsAndAgencies) {
                    $query->select('agent_id')
                        ->from('accounts')
                        ->where('agent_id', '!=', '')
                        ->whereIn('account_id', $arrayOfDirectClientsAndAgencies)
                        ->whereRaw('accounts.account_id = tbl.account_id');
                }
            )->get();
        foreach ($data as $key => $value) {
            $arr[] = (array)$value;
        }
        return $arr;
    }
}
