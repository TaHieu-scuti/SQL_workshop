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
