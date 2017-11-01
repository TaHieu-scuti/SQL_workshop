<?php

namespace App;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

    const FIELDS = [
    ];

    const AVERAGE_FIELDS = [
        'averageCpc',
        'averagePosition'
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

    /**
     * @param string[] $fieldNames
     * @return Expression[]
     */
    protected function getAggregated(array $fieldNames)
    {
        $tableName = $this->getTable();
        $expressions = [];

        foreach ($fieldNames as $fieldName) {
            if ($fieldName === static::GROUPED_BY_FIELD_NAME) {
                $expressions[] = static::GROUPED_BY_FIELD_NAME;
                continue;
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $expressions[] = $this->getAverageExpression($fieldName);
            } else {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                        ->getType()
                        ->getName()
                    === static::FIELD_TYPE) {
                    $expressions[] = $this->getTrimmedSumExpression($fieldName);
                } else {
                    $expressions[] = $this->getSumExpression($fieldName);
                }
            }
        }

        return $expressions;
    }

    /**
     * @param string $startDay
     * @param string $endDay
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
            'format(trim(ROUND('.'AVG(' . $fieldName . '),2'.'))+0, 2) AS ' . $fieldName
        );
    }

    protected function getTrimmedSumExpression($fieldName)
    {
        return DB::raw(
            'format(trim(ROUND( SUM(' . $fieldName . '), 2)) + 0, 2) AS ' . $fieldName
        );
    }

    protected function getSumExpression($fieldName)
    {
        return DB::raw('format(SUM( ' . $fieldName . ' ), 0) AS ' . $fieldName);
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
     * @return LengthAwarePaginator
     */
    public function getDataForTable(
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $pagination,
        $columnSort,
        $sort,
        $accountId,
        $adgainerId
    ) {
        $aggregations = $this->getAggregated(static::AVERAGE_FIELDS + static::SUM_FIELDS);
        return $this->select(static::FIELDS + $aggregations)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy(static::GROUPED_BY_FIELD_NAME)
            ->orderBy($columnSort, $sort)
            ->paginate($pagination);
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
        $column,
        $accountStatus,
        $startDay,
        $endDay,
        $accountId,
        $adgainerId
    ) {
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
        $expressions = $this->getAggregated(static::AVERAGE_FIELDS + static::SUM_FIELDS);

        $fields = $this->unsetColumns(static::FIELDS, [static::GROUPED_BY_FIELD_NAME]);

        return $this->select($fields + $expressions)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->first()
            ->toArray();
    }

    /**
     * @param array $fieldNames
     * @param $accountStatus
     * @param $startDay
     * @param $endDay
     * @param $columnSort
     * @param $sort
     * @return \Illuminate\Support\Collection
     */
    public function getDataForExport(
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $columnSort,
        $sort
    ) {
        $aggregations = $this->getAggregated(static::AVERAGE_FIELDS + static::SUM_FIELDS);
        return $this->select(static::FIELDS + $aggregations)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy(static::GROUPED_BY_FIELD_NAME)
            ->orderBy($columnSort, $sort)
            ->get();
    }

    /**
     * @return string[]
     */
    public function getColumnNamesForSearch($keyword)
    {
        $allFieldNames = static::AVERAGE_FIELDS + static::SUM_FIELDS;
        $matchingFieldNames = [];
        foreach ($allFieldNames as $fieldName) {
            if (strpos($allFieldNames, $keyword) !== false) {
                $matchingFieldNames[] = $fieldName;
            }
        }

        return $matchingFieldNames;
    }
}
