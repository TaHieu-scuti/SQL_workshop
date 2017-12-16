<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\AbstractReportModel;
use App\Http\Controllers\AbstractReportController;

class RepoAdwAdgroupReportCost extends AbstractReportModel
{
    const GROUPED_BY_FIELD_NAME = 'adGroup';
    const PAGE_ID = "adgroupID";
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaign',
            'tableJoin' => 'repo_adw_adgroup_report_cost',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ]
    ];

    protected $table = 'repo_adw_adgroup_report_cost';
    public $timestamps = false;

    public function getAllAdwAdgroup(
        $accountId = null,
        $campaignId = null
    ) {
        $engine = session(static::SESSION_KEY_ENGINE);
        return self::select('adGroupID as adgroupID', 'adGroup as adgroupName')
            ->where(
                function ($query) use ($accountId, $campaignId, $engine) {
                    $this->addQueryConditions(
                        $query,
                        session(AbstractReportController::SESSION_KEY_ADGAINER_ID),
                        $engine,
                        $accountId,
                        $campaignId
                    );
                }
            )
            ->groupBy('adgroupID', 'adgroupName')->get();
    }
}
