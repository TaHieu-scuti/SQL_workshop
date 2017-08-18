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
        if (($key = array_search('id', $columns)) !== false) {
            unset($columns[$key]);
        }

        if (($key = array_search('campaign_id', $columns)) !== false) {
            unset($columns[$key]);
        }

        return $columns;
    }
    abstract public function getDataForTable($fieldName, $acccountStatus, $startDay, $endDay, $pagination, $columnSort, $sort);
    abstract public function getDataForGraph($column, $accountStatus, $startDay, $endDay);
}
