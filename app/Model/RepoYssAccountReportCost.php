<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

use App\AbstractReportModel;
use App\Model\RepoYssAccount;

use DateTime;
use Exception;
use Auth;
use App\User;

class RepoYssAccountReportCost extends AbstractReportModel
{
    protected $table = 'repo_yss_account_report_cost';

    /** @var bool */
    public $timestamps = false;


    /** @var array */
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

    /** @var array */
    private $emptyCalculateFieldArray = [
        'quarter',
        'week',
        'network',
        'device',
        'day',
        'dayOfWeek',
        'month',
        'trackingURL',
        'account_id',
        'accountid'
    ];

    // constant
    const FOREIGN_KEY_YSS_ACCOUNTS = 'account_id';
    const FIELD_TYPE = 'float';
    const HIDE_ZERO_STATUS = 'hideZero';
    const SHOW_ZERO_STATUS = 'showZero';
    const SUM_IMPRESSIONS_EQUAL_ZERO = 'SUM(impressions) = 0';
    const SUM_IMPRESSIONS_NOT_EQUAL_ZERO = 'SUM(impressions) != 0';

    /**
     * @param string[] $fieldNames
     * @return Expression[]
     */
    protected function getAggregated(array $fieldNames)
    {
        $tableName = $this->getTable();
        $arrayCalculate = [];

        foreach ($fieldNames as $fieldName) {
            if ($fieldName === 'accountName' || $fieldName === 'device' ||
                $fieldName === 'hourofday'
            ) {
                $arrayCalculate[] = $fieldName;
                continue;
            }
            if (in_array($fieldName, $this->averageFieldArray)) {
                $arrayCalculate[] = DB::raw(
                    'format(trim(ROUND(AVG(' . $tableName . '.' . $fieldName . '), 2))+0, 2) AS ' . $fieldName
                );
            } else {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                    ->getType()
                    ->getName()
                    === self::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'format(trim(ROUND(SUM(' . $tableName . '.' . $fieldName . '), 2))+0, 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw(
                        'format(SUM( ' . $tableName . '.' . $fieldName . ' ), 0) AS ' . $fieldName
                    );
                }
            }
        }

        return $arrayCalculate;
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
        $arrayCalculate = [];
        $tableName = $this->getTable();
        $joinTableName = (new RepoYssAccount)->getTable();
        $arrayCalculate = $this->getAggregated($fieldNames);
        $paginatedData  = self::select($arrayCalculate)
                ->join(
                    $joinTableName,
                    $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
                    '=',
                    $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
                )
                ->where(
                    function ($paginatedData) use ($startDay, $endDay) {
                        if ($startDay === $endDay) {
                            $paginatedData->whereDate('day', '=', $endDay);
                        } else {
                            $paginatedData->whereDate('day', '>=', $startDay)
                                ->whereDate('day', '<=', $endDay);
                        }
                    }
                )
                ->where(
                    function ($query) use ($accountId,  $adgainerId) {
                        if ($accountId !== null) {
                            $query->where('repo_yss_accounts.accountid', '=', $accountId);
                        } else {
                            $query->where('repo_yss_account_report_cost.account_id', '=', $adgainerId);
                        }
                    }
                )
                ->groupBy($groupedByField)
                ->orderBy($columnSort, $sort);
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $paginatedData = $paginatedData->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->paginate($pagination);
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $paginatedData = $paginatedData->havingRaw(self::SUM_IMPRESSIONS_EQUAL_ZERO)
                            ->paginate($pagination);
        }
        return $paginatedData;
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
    )
    {
        try {
            new DateTime($startDay);
            new DateTime($endDay);
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }
        $data = self::select(
            DB::raw('SUM('.$column.') as data'),
            DB::raw(
                'DATE(day) as day'
            )
        )
            ->join(
                'repo_yss_accounts',
                'repo_yss_account_report_cost.account_id',
                '=',
                'repo_yss_accounts.account_id'
            )
            ->where(
                function ($data) use ($startDay, $endDay) {
                    if ($startDay === $endDay) {
                        $data->whereDate('day', '=', $endDay);
                    } else {
                        $data->whereDate('day', '>=', $startDay)
                            ->whereDate('day', '<=', $endDay);
                    }
                }
            )
            ->where(
                function ($query) use ($accountId,  $adgainerId) {
                    if ($accountId !== null) {
                        $query->where('repo_yss_accounts.accountid', '=', $accountId);
                    } else {
                        $query->where('repo_yss_account_report_cost.account_id', '=', $adgainerId);
                    }
                }
            )
            ->groupBy('day');
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->get();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_EQUAL_ZERO)
                            ->get();
        }
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
        $searchColumns = DB::select('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = "'. DB::connection()->getDatabaseName() .'" AND TABLE_NAME = "'. $this->table .'"
            AND COLUMN_NAME LIKE '. '"%' . $keywords . '%"');

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
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    )
    {
        $arrayCalculate = [];
        $tableName = $this->getTable();
        foreach ($fieldNames as $fieldName) {
            if ($fieldName !== 'account_id') {
                if ($fieldName === 'accountName') {
                    continue;
                }
                if (in_array($fieldName, $this->averageFieldArray)) {
                    $arrayCalculate[] = DB::raw(
                        'format(trim(ROUND('.'AVG(' . $tableName . '.' . $fieldName . '),2'.'))+0, 2) AS ' . $fieldName
                    );
                } elseif (!in_array($fieldName, $this->emptyCalculateFieldArray)) {
                    if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                        ->getType()
                        ->getName()
                        === self::FIELD_TYPE) {
                        $arrayCalculate[] = DB::raw(
                            'format(trim(ROUND(SUM(' . $tableName . '.' . $fieldName . '), 2))+0, 2) AS ' . $fieldName
                        );
                    } else {
                        $arrayCalculate[] = DB::raw(
                            'format(SUM(' . $tableName . '.' . $fieldName . '), 0) AS ' . $fieldName
                        );
                    }
                }
            }
        }
        $joinTableName = (new RepoYssAccount)->getTable();
        if (empty($arrayCalculate)) {
            return $arrayCalculate;
        }
        $data = self::select($arrayCalculate)
                    ->join(
                        $joinTableName,
                        $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
                        '=',
                        $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
                    )->where(
                        function ($data) use ($startDay, $endDay) {
                            if ($startDay === $endDay) {
                                $data->whereDate('day', '=', $endDay);
                            } else {
                                $data->whereDate('day', '>=', $startDay)
                                    ->whereDate('day', '<=', $endDay);
                            }
                        }
                    )
                    ->where(
                        function ($query) use ($accountId,  $adgainerId) {
                            if ($accountId !== null) {
                                $query->where('repo_yss_accounts.accountid', '=', $accountId);
                            } else {
                                $query->where('repo_yss_account_report_cost.account_id', '=', $adgainerId);
                            }
                        }
                    );
        // get aggregated value
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->first();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_EQUAL_ZERO)
                            ->first();
        }
        if ($data === null) {
            $data = [];
        } else {
            $data = $data->toArray();
        }
        return $data;
    }

    public function repoYssAccounts()
    {
        return $this->hasOne('App\Model\RepoYssAccount', 'account_id', 'account_id');
    }

    public function getDataForExport(
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $columnSort,
        $sort
    ) {
        $tableName = $this->getTable();
        $joinTableName = (new RepoYssAccount)->getTable();
        $arrayCalculate = $this->getAggregated($fieldNames);
        $data = self::select($arrayCalculate)
                ->join(
                    $joinTableName,
                    $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
                    '=',
                    $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
                )->where(
                    function ($data) use ($startDay, $endDay) {
                        if ($startDay === $endDay) {
                            $data->whereDate('day', '=', $endDay);
                        } else {
                            $data->whereDate('day', '>=', $startDay)
                                ->whereDate('day', '<=', $endDay);
                        }
                    }
                )->groupBy($joinTableName.'.accountName')
                ->orderBy($columnSort, $sort);
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->get();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_EQUAL_ZERO)
                            ->get();
        }
        return $data;
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
    )
    {
        $arrayCalculate = [];
        $tableName = $this->getTable();
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, $this->averageFieldArray)) {
                $arrayCalculate[] = DB::raw(
                    'format(trim(ROUND('.'AVG(' . $fieldName . '),2'.'))+0, 2) AS ' . $fieldName
                );
            } elseif (!in_array($fieldName, $this->emptyCalculateFieldArray)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                    ->getType()
                    ->getName()
                    === self::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'format(trim(ROUND(SUM(' . $fieldName . '), 2))+0, 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw('format(SUM(' . $fieldName . '), 0) AS ' . $fieldName);
                }
            }
        }
        $joinTableName = (new RepoYssAccount)->getTable();
        $data = self::select($arrayCalculate)
                ->join(
                    $joinTableName,
                    $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
                    '=',
                    $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
                )->where(
                    function ($data) use ($startDay, $endDay) {
                        if ($startDay === $endDay) {
                            $data->whereDate('day', '=', $endDay);
                        } else {
                            $data->whereDate('day', '>=', $startDay)
                                ->whereDate('day', '<=', $endDay);
                        }
                    }
                )
                ->where(
                    function($query) use ($accountId,  $adgainerId) {
                        if ($accountId !== null) {
                            $query->where('repo_yss_accounts.accountid', '=', $accountId);
                        } else {
                            $query->where('repo_yss_account_report_cost.account_id', '=', $adgainerId);
                        }
                    }
                );
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->first();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_EQUAL_ZERO)
                            ->first();
        }
        if ($data === null) {
            $data = [
                'clicks' => 0,
                'impressions' => 0,
                'cost' => 0,
                'averageCpc' => 0,
                'averagePosition' => 0
            ];
        } else {
            $data = $data->toArray();
        }
        return $data;
    }
}
