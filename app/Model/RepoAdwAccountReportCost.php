<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use App\AbstractReportModel;

class RepoAdwAccountReportCost extends AbstractReportModel
{
    protected $table = 'repo_adw_account_report_cost';

    /**
     * @var bool
     */
    public $timestamps = false;

    // constant
    const GROUPED_BY_FIELD_NAME = 'account';
    const PAGE_ID = 'accountid';
    const ADW_FIELDS = [
        'clicks' => 'clicks',
        'cost' => 'cost',
        'impressions' => 'impressions',
        'ctr' => 'ctr',
        'averagePosition' => 'avgPosition',
        'averageCpc' => 'avgCPC'
    ];

    private function getAggregatedGraphOfGoogle($column)
    {
        $arrSelect = [];
        $tableName = (new RepoAdwAccountReportCost)->getTable();
        $arrSelect[] = DB::raw('DATE(day) as day');
        if (in_array($column, static::AVERAGE_FIELDS)) {
                $arrSelect[] = DB::raw(
                    'ROUND(AVG( '.self::ADW_FIELDS[$column].' ), 2) AS data'
                );
        } elseif (in_array($column, static::SUM_FIELDS)) {
            if (DB::connection()->getDoctrineColumn($tableName, $column)
                    ->getType()
                    ->getName()
                === self::FIELD_TYPE) {
                $arrSelect[] = DB::raw(
                    'ROUND(SUM('.self::ADW_FIELDS[$column]. '), 2) AS data'
                );
            } else {
                $arrSelect[] = DB::raw(
                    'SUM( ' . self::ADW_FIELDS[$column] . ' ) AS data'
                );
            }
        }
        return $arrSelect;
    }

    public function adwAccountDataForGraphOfAgencyList($column, $startDay, $endDay)
    {
        $aggreations = $this->getAggregatedGraphOfGoogle($column);
        return self::select($aggreations)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy('day');
    }
}
