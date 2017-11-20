<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use App\AbstractReportModel;

use DateTime;
use Exception;
use Auth;

class RepoYssAdReportCost extends AbstractReportModel
{
    // constant
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'adName';
    const PAGE_ID = 'adId';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'repo_yss_ad_report_cost';

    public function getAllAdReport($accountId = null, $campaignId = null, $adGroupId = null, $adReportId = null)
    {
        $arrAdReports = [];

        $arrAdReports['all'] = 'All Adreports';

        $adreports = $this->select('adID', 'adName')->where(
            function ($query) use ($accountId, $campaignId, $adGroupId, $adReportId) {
                $this->addQueryConditions(
                    $query,
                    Auth::user()->account_id,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId
                );
            }
        )->get();

        if ($adreports) {
            foreach ($adreports as $key => $adreport) {
                $arrAdReports[$adreport->adID] = $adreport->adName;
            }
        }

        return $arrAdReports;
    }
}
