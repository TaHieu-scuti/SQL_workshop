<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

abstract class AbstractReportModel extends Model
{
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
        $columns = Schema::getColumnListing($this->getTable());

        // unset "id" and "campaign_id" from array cause we dont need it for filter
        $columns = $this->unsetColumns($columns, ['id', 'campaign_id']);

        return $columns;
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
     * @return string[]
     */
    abstract public function getDataForTable(
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $pagination,
        $columnSort,
        $sort
    );

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
