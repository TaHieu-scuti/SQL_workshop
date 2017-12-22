<?php

namespace App\Model;

// use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;

use DB;

abstract class AbstractYssReportModel extends AbstractReportModel
{
    protected function getAggregated(array $fieldNames, array $higherLayerSelections = null)
    {
        $expressions = parent::getAggregated($fieldNames);
        foreach($fieldNames as $fieldName) {
            switch ($fieldName) {
                case 'call_cv':
                    $expressions[] = DB::raw("COUNT(`phone_time_use`.`id`) as call_cv");
                    break;
                case 'call_cvr':
                    $expressions[] = DB::raw("(COUNT(`phone_time_use`.`id`) / SUM(`{$this->table}`.`clicks`)) * 100 as call_cvr");
                    break;
                case 'call_cpa':
                    $expressions[] = DB::raw("SUM(`{$this->table}`.`cost`) / COUNT(`phone_time_use`.`id`) as call_cpa");
                    break;
                case 'web_cv':
                    $expressions[] = DB::raw("SUM(`{$this->table}`.`conversions`) as web_cv");
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw("(SUM(`{$this->table}`.`conversions`) / SUM(`{$this->table}`.`clicks`)) * 100 as web_cvr");
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw("SUM(`{$this->table}`.`cost`) / SUM(`{$this->table}`.`conversions`) as web_cpa");
                    break;
            }
        }
        return $expressions;
    }
}
