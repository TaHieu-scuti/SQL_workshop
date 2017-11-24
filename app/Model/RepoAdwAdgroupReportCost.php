<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\AbstractReportModel;

class RepoAdwAdgroupReportCost extends AbstractReportModel
{
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'adGroup';
    const PAGE_ID = "adgroupID";

    protected $table = 'repo_adw_adgroup_report_cost';
    public $timestamps = false;

    public function getAllAdwAdgroup(
        $accountId = null,
        $campaignId = null,
        $adgroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        return self::select('adGroupID as adgroupID', 'adGroup')
            ->where(
                function ($query) use ($accountId, $campaignId, $adgroupId, $adReportId, $keywordId) {
                    $this->addQueryConditions(
                        $query,
                        Auth::user()->account_id,
                        $accountId,
                        $campaignId,
                        $adgroupId,
                        $adReportId,
                        $keywordId
                    );
                }
            )
            ->get();
    }
}
