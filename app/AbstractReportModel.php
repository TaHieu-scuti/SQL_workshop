<?php

namespace App;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

abstract class AbstractReportModel extends Model
{
    // Please override these constants in the derived report models when necessary
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'id';

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

    /**
     * @param string $fieldName
     * @param int    $resultPerPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDataByFilter($fieldName, $resultPerPage)
    {
        return self::paginate($resultPerPage, $fieldName);
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
     * @return Expression[]
     */
    protected function getAggregated(array $fieldNames)
    {
        $tableName = $this->getTable();
        $arrayCalculate = [];

        foreach ($fieldNames as $fieldName) {
            if ($fieldName === static::GROUPED_BY_FIELD_NAME) {
                $arrayCalculate[] = static::GROUPED_BY_FIELD_NAME;
                continue;
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw('format(trim(ROUND(AVG(' . $fieldName . '), 2)) + 0, 2) AS ' . $fieldName);
            } else {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                        ->getType()
                        ->getName()
                    === static::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'format(trim(ROUND( SUM(' . $fieldName . '), 2)) + 0, 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw('format(SUM( ' . $fieldName . ' ), 0) AS ' . $fieldName);
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
     * @return LengthAwarePaginator
     */
    public function getDataForTable(
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $pagination,
        $columnSort,
        $sort
    ) {
        $aggregations = $this->getAggregated(static::AVERAGE_FIELDS + static::SUM_FIELDS);
        return self::select($aggregations)
            ->where(
                function ($query) use ($startDay, $endDay) {
                    if ($startDay === $endDay) {
                        $query->whereDate('day', '=', $endDay);
                    } else {
                        $query->whereDate('day', '>=', $startDay)
                            ->whereDate('day', '<=', $endDay);
                    }
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
     */
    abstract public function getDataForGraph(
        $column,
        $accountStatus,
        $startDay,
        $endDay
    );
}
