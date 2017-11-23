<?php

namespace App;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Model\RepoYssAccount;

use DateTime;
use Exception;

abstract class AbstractReportModel extends Model
{
    const HIDE_ZERO_STATUS = 'hideZero';
    const SHOW_ZERO_STATUS = 'showZero';
    const SUM_IMPRESSIONS_EQUAL_ZERO = 'SUM(impressions) = 0';
    const SUM_IMPRESSIONS_NOT_EQUAL_ZERO = 'SUM(impressions) != 0';
    // Please override these constants in the derived report models when necessary
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'id';
    const PAGE_ID = 'pageId';
    const DEVICE = "device";
    const DAY_OF_WEEK = "dayOfWeek";
    const PREFECTURE ="prefecture";
    const HOUR_OF_DAY = "hourofday";

    const FOREIGN_KEY_YSS_ACCOUNTS = 'account_id';

    const FIELDS = [
    ];

    const AVERAGE_FIELDS = [
        'averageCpc',
        'averagePosition'
    ];

    const AVERAGE_FIELDS_ADW = [
        'avgCPC',
        'avgPosition'
    ];

    const SUM_FIELDS = [
        'clicks',
        'impressions',
        'cost',
        'ctr'
    ];

    const SUMMARY_FIELDS = [
        'impressions',
        'clicks',
        'ctr',
        'cost'
    ];

    const YSS_FIELDS_MAP = [
//      'columns' => 'alias'
        'impressions' => 'impressions',
        'clicks' => 'clicks',
        'cost' => 'cost',
        'ctr' => 'ctr',
        'averageCpc' => 'averageCpc',
        'averagePosition' => 'averagePosition'
    ];

    const ADW_FIELDS_MAP = [
//      'columns' => 'alias'
        'impressions' => 'impressions',
        'clicks' => 'clicks',
        'cost' => 'cost',
        'ctr' => 'ctr',
        'avgCPC' => 'averageCpc',
        'avgPosition' => 'averagePosition',
        'campaign' => 'campaignName',
        'adGroup' => 'adgroupName'
    ];

    protected $groupByFieldName = [
        'device',
        'hourofday',
        'dayOfWeek',
        'prefecture',
    ];

    /**
     * @param string[] $fieldNames
     * @return Expression[]
     */
    protected function getAggregated(array $fieldNames)
    {
        $fieldNames = $this->updateFieldNames($fieldNames);
        $tableName = $this->getTable();
        $joinTableName = (new RepoYssAccount)->getTable();
        if (isset($fieldNames[0]) && $fieldNames[0] === self::PREFECTURE) {
            $tableName = 'repo_yss_prefecture_report_cost';
        }
        if (array_search(static::GROUPED_BY_FIELD_NAME, $fieldNames) === false) {
            $key = array_search(static::PAGE_ID, $fieldNames);
            if ($key !== false) {
                unset($fieldNames[$key]);
            }
        }
        $arrayCalculate = [];
        foreach ($fieldNames as $key => $fieldName) {
            if ($fieldName === static::GROUPED_BY_FIELD_NAME
                || $fieldName === self::DEVICE
                || $fieldName === self::HOUR_OF_DAY
                || $fieldName === self::DAY_OF_WEEK
                || $fieldName === self::PREFECTURE
            ) {
                $arrayCalculate[] = $fieldName;
                continue;
            }

            if ($fieldName === 'accountid') {
                $arrayCalculate[] = DB::raw($joinTableName . '.' . $fieldName);
            }

            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'ROUND(AVG(' . $tableName . '.' . $key . '), 2) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                                    ->getType()
                                    ->getName()
                    === self::FIELD_TYPE
                ) {
                    $arrayCalculate[] = DB::raw(
                        'ROUND(SUM(' . $tableName . '.' . $key . '), 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw(
                        'SUM( ' . $tableName . '.' . $key . ' ) AS ' . $fieldName
                    );
                }
            }
        }
        return $arrayCalculate;
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

    protected function addQueryConditions(
        Builder $query,
        $adgainerId,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        if ($accountId !== null && $campaignId === null && $adGroupId === null && $adReportId === null) {
            $query->where($this->getTable().'.accountid', '=', $accountId);
        }
        if ($campaignId !== null && $adGroupId === null && $adReportId === null) {
            $query->where($this->getTable().'.campaignID', '=', $campaignId);
        }
        if ($adGroupId !== null && $adReportId === null) {
            $query->where('adgroupID', '=', $adGroupId);
        }
        if ($adReportId !== null) {
            $query->where($this->getTable().'.adID', '=', $adReportId);
        }
        if ($keywordId !== null) {
            $query->where($this->getTable().'.keywordID', '=', $keywordId);
        }
        if ($accountId === null && $campaignId === null && $adGroupId === null && $adReportId === null) {
            $query->where($this->getTable().'.account_id', '=', $adgainerId);
        }
    }

    /**
     * @param string  $startDay
     * @param string  $endDay
     * @param Builder $query
     */
    protected function addTimeRangeCondition($startDay, $endDay, Builder $query)
    {
        if ($startDay === $endDay) {
            $query->whereDate('day', '=', $endDay);
        } else {
            $query->whereDate('day', '>=', $startDay)
                ->whereDate('day', '<=', $endDay);
        }
    }

    protected function getAverageExpression($fieldName)
    {
        return DB::raw(
            'ROUND('.'AVG(' . $fieldName . '),2'.') AS ' . $fieldName
        );
    }

    protected function getTrimmedSumExpression($fieldName)
    {
        return DB::raw(
            'ROUND( SUM(' . $fieldName . '), 2) AS ' . $fieldName
        );
    }

    protected function getSumExpression($fieldName)
    {
        return DB::raw('SUM( ' . $fieldName . ' ) AS ' . $fieldName);
    }

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
        $fieldNames = $this->unsetColumns($fieldNames, [$groupedByField]);

        $aggregations = $this->getAggregated($fieldNames);
        $data = self::select($aggregations)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
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
            );
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                ->first();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->first();
        }
        if ($data === null) {
            $data = [];
        }
        return $data;
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
        $arrayCalculate = $this->getAggregated($fieldNames);
        $data = self::select($arrayCalculate)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
                function (Builder $query) use (
                    $adgainerId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId
                ) {
                    $this->addQueryConditions(
                        $query,
                        $adgainerId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId
                    );
                }
            );
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                ->first();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->first();
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

    /**
     * @param string $fieldName
     * @param int    $resultPerPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDataByFilter($fieldName, $resultPerPage)
    {
        return $this->paginate($resultPerPage, $fieldName);
    }

    /**
     * @return array
     */
    public function getColumnNames()
    {
        $columns = $this->getAllColumnNames();

        // unset "id" and "campaign_id" from array cause we dont need it for filter
        return $this->unsetColumns($columns, ['id', 'campaign_id']);
    }

    /**
     * @return array
     */
    public function getAllColumnNames()
    {
        return Schema::getColumnListing($this->getTable());
    }

    /**
     * @param string[] $columns
     * @param string[] $columnsToUnset
     * @return string[]
     */
    public function unsetColumns(array $columns, array $columnsToUnset)
    {
        foreach ($columnsToUnset as $name) {
            if (($key = array_search($name, $columns)) !== false) {
                unset($columns[$key]);
            }
        }

        return $columns;
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
        $aggregations = $this->getAggregated($fieldNames);
        $paginatedData = $this->select(array_merge(static::FIELDS, $aggregations))
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

        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $paginatedData = $paginatedData->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                ->paginate($pagination);
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $paginatedData = $paginatedData->paginate($pagination);
        }
        return $paginatedData;
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
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $column = $this->updateColumnForGraph($column);
        try {
            new DateTime($startDay); //NOSONAR
            new DateTime($endDay); //NOSONAR
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }

        return $this->select(
            DB::raw('SUM(' . $column . ') as data'),
            DB::raw(
                'DATE(day) as day'
            )
        )
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
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
            ->groupBy('day')
            ->get();
    }

    /**
     * @param string $startDay
     * @param string $endDay
     * @return array
     */
    public function getSummaryData($startDay, $endDay)
    {
        $expressions = $this->getAggregated(static::SUMMARY_FIELDS);

        if (empty($expressions)) {
            return $expressions;
        }

        return $this->select($expressions)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->first()
            ->toArray();
    }

    /**
     * @param string $startDay
     * @param string $endDay
     * @return array
     */
    public function getTotalsRow($startDay, $endDay)
    {
        $expressions = $this->getAggregated(array_merge(static::AVERAGE_FIELDS, static::SUM_FIELDS));

        $fields = $this->unsetColumns(static::FIELDS, [static::GROUPED_BY_FIELD_NAME]);

        return $this->select(array_merge($fields, $expressions))
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->first()
            ->toArray();
    }

    /**
     * @return string[]
     */
    public function getColumnNamesForSearch($keyword)
    {
        $allFieldNames = array_merge(static::AVERAGE_FIELDS, static::SUM_FIELDS);
        $matchingFieldNames = [];
        foreach ($allFieldNames as $fieldName) {
            if (strpos($allFieldNames, $keyword) !== false) {
                $matchingFieldNames[] = $fieldName;
            }
        }

        return $matchingFieldNames;
    }

    public function updateFieldNames(array $fieldNames)
    {
        $resultFieldNames = [];
        $engine = session('engine');
        if ($engine === 'yss' || $engine === null) {
            $resultFieldNames = $this->setKeyFieldNames($fieldNames, self::YSS_FIELDS_MAP);
        } elseif ($engine === 'adw') {
            $resultFieldNames = $this->setKeyFieldNames($fieldNames, self::ADW_FIELDS_MAP);
        }
        return $resultFieldNames;
    }

    public function setKeyFieldNames(array $fieldNames, array $fieldsMap)
    {
        $result = [];
        foreach ($fieldNames as $fieldName) {
            $includedInFieldsMap = false;
            //check fieldName is included in the fieldsMap
            foreach ($fieldsMap as $key => $value) {
                if ($fieldName === $value) {
                    $result[$key] = $value;
                    $includedInFieldsMap = true;
                    break;
                }
            }
            if (!$includedInFieldsMap) {
                $result[$fieldName] = $fieldName;
            }
        }
        return $result;
    }

    public function updateColumnForGraph($column)
    {
        $engine = session('engine');
        $arrayMapping = [];
        if ($engine === null || $engine === 'yss') {
            $arrayMapping = self::YSS_FIELDS_MAP;
        } elseif ($engine === 'adw') {
            $arrayMapping = self::ADW_FIELDS_MAP;
        }
        foreach ($arrayMapping as $key => $value) {
            if ($column === $value) {
                return $key;
            }
        }
        return $column;
    }
}
