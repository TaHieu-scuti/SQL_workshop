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

    public function getDataOnGraph($column, $status, $start, $end)
    {
        return self::select(DB::raw('SUM('.$column.') as data'), DB::raw('DATE(day) as day'))
                    ->join('repo_yss_accounts', 'repo_yss_account_report.account_id', '=', 'repo_yss_accounts.account_id')
                    ->where(function($query) use ($start, $end) {
                        if ($start === $end) {
                            $query->whereDate('day', '=', $end);
                        } else {
                            $query->whereDate('day', '>=', $end)
                                ->whereDate('day', '<', $start);
                        }
                    })
                    ->where('repo_yss_accounts.accountStatus', 'like', '%'.$status)
                    ->groupBy('day')
                    ->get();
    }

    public function getDataOnTable($column, $status, $start, $end, $resultPerPage)
    {
        //unset column 'account_id' ( need to be more specific about table name )
        if (($key = array_search('account_id', $column)) !== false) {
            unset($column[$key]);
        }
        $query = self::select($column)
                    ->join('repo_yss_accounts', 'repo_yss_account_report.account_id', '=', 'repo_yss_accounts.account_id')
                    ->where(function($query) use ($start, $end) {
                        if ($start === $end) {
                            $query->whereDate('day', '=', $end);
                        } else {
                            $query->whereDate('day', '>=', $end)
                                ->whereDate('day', '<', $start);
                        }
                    })
                    ->where('repo_yss_accounts.accountStatus', 'like', '%'.$status);
        return $query->addSelect('repo_yss_account_report.account_id')->paginate($resultPerPage);
    }
}
