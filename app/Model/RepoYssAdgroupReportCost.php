<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\AbstractReportModel;
use DateTime;
use Exception;
use Auth;

class RepoYssAdgroupReportCost extends AbstractReportModel
{
    // constant
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'adgroupName';
    const KEY_ID = "adgroupID";

    /**
     * @var bool 
     */
    public $timestamps = false;

    /**
     * @var string 
     */
    protected $table = 'repo_yss_adgroup_report_cost';

     /**
      * @param string $keywords
      * @return string[]
      */
    public function getColumnLiveSearch($keywords)
    {
        $searchColumns = DB::select(
            'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = "'. DB::connection()->getDatabaseName() .'" AND TABLE_NAME = "'. $this->table .'"
            AND COLUMN_NAME LIKE '. '"%' . $keywords . '%"'
        );
        $result = [];
        foreach ($searchColumns as $searchColumn) {
            foreach ($searchColumn as $value) {
                $result[] = $value;
            }
        }
        // remove column id, campaign_id ....
        $unsetColumns = [
            'id', 'campaign_id', 'account_id', 'adgroupID', 'network',
            'device', 'day', 'dayOfWeek', 'week',
            'month', 'quarter', 'hourofday', 'adgroupDistributionSettings', 'exeDate',
            'startDate', 'endDate', 'campaignID', 'campaignName', 'adgroupName',
            'mobileBidAdj', 'tabletBidAdj', 'desktopBidAdj',
            'trackingURL', 'customParameters', 'ctr'
        ];

        return $this->unsetColumns($result, $unsetColumns);
    }

    public static function getAllAdgroup()
    {
        $arrAdgroups = [];

        $arrAdgroups['all'] = 'All Adgroup';

        $adgroups = self::select('adgroupID', 'adgroupName')->where('account_id', '=', Auth::user()->account_id)->get();
        if ($adgroups) {
            foreach ($adgroups as $key => $adgroup) {
                $arrAdgroups[$adgroup->adgroupID] = $adgroup->adgroupName;
            }
        }

        return $arrAdgroups;
    }
}
