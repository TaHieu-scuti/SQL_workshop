<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;

abstract class AbstractReportModel extends Model
{
    public function getDataByFilter($fieldName, $resultPerPage)
    {
        return self::paginate($resultPerPage, $fieldName);
    }

    //get model's table-attribute's name
    public function getTableName()
    {
        return Schema::getColumnListing($this->getTable());
    }

    //get all model's fields' name
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
