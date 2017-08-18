<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;
use DB;

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
}
