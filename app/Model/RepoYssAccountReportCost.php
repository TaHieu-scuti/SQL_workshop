<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use App\AbstractReportModel;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\Event;

use DateTime;
use Exception;
use Auth;
use PDO;

class RepoYssAccountReportCost extends AbstractReportModel
{
    protected $table = 'repo_yss_account_report_cost';
    const GROUPED_BY_FIELD_NAME = 'accountName';
    const GROUPED_BY_FIELD_NAME_ADW = 'account';
    const PAGE_ID = 'accountid';

    /**
     * @var bool
     */
    public $timestamps = false;


    /**
     * @var array
     */
    private $averageFieldArray = [
        'ctr',
        'averageCpc',
        'averagePosition',
        'impressionShare',
        'exactMatchImpressionShare',
        'budgetLostImpressionShare',
        'qualityLostImpressionShare',
        'conversions',
        'convRate',
        'convValue',
        'costPerConv',
        'valuePerConv',
        'allConvRate',
        'costPerAllConv',
        'valuePerAllConv'
    ];

    // constant
    const FOREIGN_KEY_YSS_ACCOUNTS = 'account_id';
    const FIELD_TYPE = 'float';
    const HIDE_ZERO_STATUS = 'hideZero';
    const SHOW_ZERO_STATUS = 'showZero';

    protected function addQueryConditionsForGoogle(Builder $query, $adgainerId, $accountId = null)
    {
        $tableName = (new RepoAdwAccountReportCost)->getTable();
        if ($accountId !== null) {
            $query->where($tableName.'.accountid', '=', $accountId);
        }
        if ($accountId === null) {
            $query->where($tableName.'.account_id', '=', $adgainerId);
        }
    }

    protected function getBinddingSql($data)
    {
        $sql = $data->toSql();
        foreach ($data->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'".$binding."'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }

    protected function getAggregatedGrabphOfGoogle($column)
    {
        $arrSelect = [];
        $tableName = (new RepoAdwAccountReportCost)->getTable();
        $arrSelect[] = DB::raw('DATE(day) as day');
        if (in_array($column, static::AVERAGE_FIELDS)) {
            if ($column === 'averageCpc') {
                $arrSelect[] = DB::raw(
                    'ROUND(AVG( avgCPC ), 2) AS data'
                );
            }
            if ($column === 'averagePosition') {
                $arrSelect[] = DB::raw(
                    'ROUND(AVG( avgPosition ), 2) AS data'
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

    protected function getAggregatedGraph($column)
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

    protected function getAggregatedOfGoogle(array $fieldNames)
    {
        array_unshift($fieldNames, self::GROUPED_BY_FIELD_NAME_ADW);
        array_push($fieldNames, 'avgCPC', 'avgPosition');
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
        if (isset($fieldNames[0]) && $fieldNames[0] === 'prefecture') {
            $tableName = 'repo_yss_prefecture_report_cost';
        }
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === 'device'
                || $fieldName === 'hourofday'
                || $fieldName === "dayOfWeek"
                || $fieldName === 'prefecture'
            ) {
                $key = array_search(static::PAGE_ID, $fieldNames);
                if ($key !== false) {
                    unset($fieldNames[$key]);
                }
            }
        }

        $arrayCalculate = [];
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === 'device'
                || $fieldName === 'hourofday'
                || $fieldName === "dayOfWeek"
                || $fieldName === 'prefecture'
                || $fieldName === self::PAGE_ID
            ) {
                $arrayCalculate[] = $fieldName;
                continue;
            }
            if ($fieldName === static::GROUPED_BY_FIELD_NAME_ADW) {
                $arrayCalculate[] = $fieldName .' AS accountName';
                continue;
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS_ADW)) {
                if ($fieldName === 'avgCPC') {
                    $arrayCalculate[] = DB::raw(
                        'ROUND(AVG('. $fieldName . '), 2) AS averageCpc'
                    );
                }
                if ($fieldName === 'avgPosition') {
                    $arrayCalculate[] = DB::raw(
                        'ROUND(AVG('. $fieldName . '), 2) AS averagePosition'
                    );
                }
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                        ->getType()
                        ->getName()
                    === self::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'ROUND(SUM(' . $fieldName . '), 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw(
                        'SUM( ' . $fieldName . ' ) AS ' . $fieldName
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
    ) {
        try {
            new DateTime($startDay);
            new DateTime($endDay);
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }
        $arrSelect = $this->getAggregatedGraph($column);
        $arrSelectGoogle = $this->getAggregatedGrabphOfGoogle($column);

        $dataForGoogle = \App\Model\RepoAdwAccountReportCost::select($arrSelectGoogle)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
                function ($query) use ($accountId, $adgainerId) {
                    $this->addQueryConditionsForGoogle($query, $adgainerId, $accountId);
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
                function ($query) use ($accountId, $adgainerId) {
                    if ($accountId !== null) {
                        $query->where('repo_yss_accounts.accountid', '=', $accountId);
                    } else {
                        $query->where('repo_yss_account_report_cost.account_id', '=', $adgainerId);
                    }
                }
            )
            ->groupBy('day');
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $dataForGoogle = $dataForGoogle->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }

        $data = $data->union($dataForGoogle);
        $sql = $this->getBinddingSql($data);
        $data = DB::table(DB::raw("({$sql}) as tbl"))
            ->select(DB::raw('day, sum(data) as data'))
            ->groupBy('day');

        $data = $data->get();

        return $data;
    }

    /**
     * @param string $keywords
     * @return string[]
     */
    public function getColumnLiveSearch($keywords)
    {
        /* TODO: the columns should be retrieved in a unified way,
        if it cannot be done with AbstractReportModel::getColumnNames
        we should make something that works for both cases */
        $searchColumns = DB::select(
            'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = "'. DB::connection()->getDatabaseName() .'" AND TABLE_NAME = "'. $this->table .'"
            AND COLUMN_NAME LIKE '. '"%' . $keywords . '%"'
        );

        $result = [];
        foreach ($searchColumns as $searchColumn) {
            foreach ($searchColumn as $value) {
                $result[] = $value;
            }
        }

        // remove column id, campaign_id ....
        $unsetColumns = ['id', 'campaign_id', 'account_id', 'network',
                             'device', 'day', 'dayOfWeek', 'week', 'month', 'quarter'];
        return $this->unsetColumns($result, $unsetColumns);
    }

    /**
     * @param $fieldNames
     * @param $accountStatus
     * @param $startDay
     * @param $endDay
     * @return array
     */
    public function calculateData(
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
        $tableName = $this->getTable();
        $fieldNames = $this->unsetColumns($fieldNames, [$groupedByField, self::PAGE_ID]);
        $arrayCalculate = $this->getAggregated($fieldNames);
        $joinTableName = (new RepoYssAccount)->getTable();
        if (empty($arrayCalculate)) {
            return $arrayCalculate;
        }
        $adwAccountReport = $this->getDatasAccountOfGoogle($fieldNames, $startDay, $endDay, $adgainerId, $accountId);
        $data = $this->select($arrayCalculate)
            ->join(
                $joinTableName,
                $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
                '=',
                $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
            )->where(
                function (Builder $query) use ($startDay, $endDay) {
                                $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
                        ->where(
                            function ($query) use ($accountId, $adgainerId) {
                                if ($accountId !== null) {
                                    $query->where('repo_yss_accounts.accountid', '=', $accountId);
                                } else {
                                    $query->where('repo_yss_account_report_cost.account_id', '=', $adgainerId);
                                }
                            }
                        );

        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $adwAccountReport = $adwAccountReport->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }
        $data = $data->union($adwAccountReport);

        $sql = $this->getBinddingSql($data);
        $data = DB::table(DB::raw("({$sql}) as tbl"))
            ->select(DB::raw('
                sum(clicks) as clicks,
                sum(cost) as cost,
                sum(impressions) as impressions,
                sum(ctr) as ctr,
                avg(averageCpc) as averageCpc,
                avg(averagePosition) as averagePosition
            '));

        $data = $data->first();

        if ($data === null) {
            $data = [];
        }

        return $data;
    }

    public function repoYssAccounts()
    {
        return $this->hasOne('App\Model\RepoYssAccount', 'account_id', 'account_id');
    }

    public function calculateSummaryData(
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
        $tableName = $this->getTable();
        $arrayCalculate = $this->getAggregated($fieldNames);
        $joinTableName = (new RepoYssAccount)->getTable();
        $adwAccountReport = $this->getDatasAccountOfGoogle($fieldNames, $startDay, $endDay, $adgainerId, $accountId);
        $data = self::select($arrayCalculate)
                ->join(
                    $joinTableName,
                    $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
                    '=',
                    $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
                )->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )
                ->where(
                    function ($query) use ($accountId, $adgainerId) {
                        if ($accountId !== null) {
                            $query->where('repo_yss_accounts.accountid', '=', $accountId);
                        } else {
                            $query->where('repo_yss_account_report_cost.account_id', '=', $adgainerId);
                        }
                    }
                );
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $adwAccountReport = $adwAccountReport->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }

        $data->union($adwAccountReport);

        $sql = $this->getBinddingSql($data);
        Event::listen(StatementPrepared::class, function ($event) {
            $event->statement->setFetchMode(PDO::FETCH_ASSOC);
        });
        $data = DB::table(DB::raw("({$sql}) as tbl"))
            ->select(DB::raw('
                sum(clicks) as clicks,
                sum(cost) as cost,
                sum(impressions) as impressions,
                avg(averageCpc) as averageCpc,
                avg(averagePosition) as averagePosition
            '));

        $data = $data->first();
        if ($data === null) {
            $data = [
                'clicks' => 0,
                'impressions' => 0,
                'cost' => 0,
                'averageCpc' => 0,
                'averagePosition' => 0
            ];
        }

        return $data;
    }

    public function getDataForTable(
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
        $aggregations = $this->getAggregated($fieldNames);
        $joinTableName = (new RepoYssAccount)->getTable();

        $adwAggreations = $this->getAggregatedOfGoogle($fieldNames);
        $adwAccountReport = \App\Model\RepoAdwAccountReportCost::select(
            array_merge([DB::raw("'adw' as engine")], $adwAggreations)
        )
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )->where(
                function (Builder $query) use ($adgainerId, $accountId) {
                    $this->addQueryConditionsForGoogle($query, $adgainerId, $accountId);
                }
            )
            ->groupBy($groupedByField)
            ->orderBy($columnSort, $sort);

        if (!in_array($groupedByField, $this->groupByFieldName)) {
            $adwAccountReport = $adwAccountReport->groupBy('accountid');
        }

        $datas = $this->select(array_merge([DB::raw("'yss' as engine")], $aggregations))
            ->join(
                $joinTableName,
                $this->getTable(). '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
                '=',
                $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
            )
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )->where(
                function (Builder $query) use (
                    $adgainerId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId
                ) {
                    $this->addQueryConditions(
                        $query,
                        $adgainerId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    );
                }
            )
            ->groupBy($groupedByField)
            ->orderBy($columnSort, $sort);
        if (!in_array($groupedByField, $this->groupByFieldName)) {
            $datas = $datas->groupBy('repo_yss_accounts.accountid');
        }
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $datas = $datas->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $adwAccountReport = $adwAccountReport->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }
        $datas = $datas->union($adwAccountReport);
        if (in_array($groupedByField, $this->groupByFieldName)) {
            Event::listen(StatementPrepared::class, function ($event) {
                $event->statement->setFetchMode(PDO::FETCH_ASSOC);
            });
            $sql = $this->getBinddingSql($datas);
            $datas = DB::table(DB::raw("({$sql}) as tbl"))
                ->select(
                    DB::raw($groupedByField),
                    DB::raw('
                        sum(clicks) as clicks,
                        sum(cost) as cost,
                        sum(impressions) as impressions,
                        sum(ctr) as ctr,
                        avg(averageCpc) as averageCpc,
                        avg(averagePosition) as averagePosition
                    ')
                )
                ->groupBy($groupedByField);
        }

        $datas = $datas->get();

        return $datas;
    }

    protected function getDatasAccountOfGoogle(
        array $fieldNames,
        $startDay,
        $endDay,
        $adgainerId = null,
        $accountId = null
    ) {
        $adwAggreations = $this->getAggregatedOfGoogle($fieldNames);
        $adwAccountReport = \App\Model\RepoAdwAccountReportCost::select(array_merge($adwAggreations))
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )->where(
                function (Builder $query) use ($adgainerId, $accountId) {
                    $this->addQueryConditionsForGoogle($query, $adgainerId, $accountId);
                }
            );

        return $adwAccountReport;
    }
}
