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
            'exeDate', 'startDate', 'endDate', 'account_id',
            'campaign_id', 'campaignID', 'adgroupID', 'adID',
            'campaignName', 'adgroupName', 'adName', 'title',
            'description1', 'displayURL', 'destinationURL', 'adType',
            'adDistributionSettings', 'adEditorialStatus', 'description2',
            'focusDevice', 'trackingURL', 'customParameters', 'landingPageURL',
            'landingPageURLSmartphone', 'network', 'clickType', 'device',
            'day', 'dayOfWeek', 'quarter', 'month',
            'week', 'title1', 'title2', 'description',
            'directory1', 'directory2', 'adKeywordID', 'adTrackingID',
        ];

        return $this->unsetColumns($result, $unsetColumns);
    }

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
