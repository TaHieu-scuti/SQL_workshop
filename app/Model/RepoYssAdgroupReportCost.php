<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\AbstractReportModel;
use App\Http\Controllers\AbstractReportController;
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

    public function getAllAdgroup(
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $arrAdgroups = [];

        $arrAdgroups['all'] = 'All Adgroup';
        if (session(AbstractReportController::SESSION_KEY_ENGINE) === 'yss') {
            $adgroups = self::select('adgroupID', 'adgroupName')
                ->where(
                    function ($query) use ($accountId, $campaignId, $adGroupId, $adReportId, $keywordId) {
                        $this->addQueryConditions(
                            $query,
                            Auth::user()->account_id,
                            $accountId,
                            $campaignId,
                            $adGroupId,
                            $adReportId,
                            $keywordId
                        );
                    }
                )
                ->get();
        } elseif (session(AbstractReportController::SESSION_KEY_ENGINE) === 'adw') {
            $modelAdwAdgroup = new RepoAdwAdgroupReportCost();
            $adgroups = $modelAdwAdgroup->getAllAdwAdgroup(
                $accountId = null,
                $campaignId = null,
                $adGroupId = null,
                $adReportId = null,
                $keywordId = null
            );
        }
        if ($adgroups) {
            foreach ($adgroups as $key => $adgroup) {
                $arrAdgroups[$adgroup->adgroupID] = $adgroup->adgroupName;
            }
        }

        return $arrAdgroups;
    }
}
