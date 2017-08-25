<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;

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
        $this->unsetColumns(['id', 'campaign_id']);

        return $columns;
    }

    /**
     * @param string[] $columnsLiveSearch
     * @param string[] $names
     * @return string[]
     */
    public function unsetColumns(array $columnsLiveSearch, array $names)
    {
        foreach ($names as $name) {
            if (($key = array_search($name, $columnsLiveSearch)) !== false) {
                unset($columnsLiveSearch[$key]);
            }
        }
        return $columnsLiveSearch;
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
