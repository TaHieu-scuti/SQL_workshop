<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\AbstractReportModel;

use DateTime;
use Exception;
use Auth;

class RepoYssKeywordReportCost extends AbstractReportModel
{
    const PAGE_ID = "keywordID";
    const GROUPED_BY_FIELD_NAME = 'keyword';
    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'repo_yss_keyword_report_cost';

    /**
     * @param string $keywords
     * @return string[]
     */
    public function getColumnLiveSearch($keywords)
    {
        $searchColumns = DB::select('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = "'. DB::connection()->getDatabaseName() .'" AND TABLE_NAME = "'. $this->table .'"
            AND COLUMN_NAME LIKE '. '"%' . $keywords . '%"');
        $result = [];
        foreach ($searchColumns as $searchColumn) {
            foreach ($searchColumn as $value) {
                $result[] = $value;
            }
        }
        // remove column id, campaign_id ....
        $unsetColumns = [
            'id', 'campaign_id', 'account_id', 'network',
            'device', 'day', 'dayOfWeek', 'week',
            'month', 'quarter', 'hourofday', 'campaignTrackingID',
            'campaignStartDate', 'campaignEndDate', 'kwEditorialStatus', 'keywordDistributionSettings',
            'mobileBidAdj', 'tabletBidAdj', 'desktopBidAdj', 'exeDate',
            'startDate', 'endDate', 'campaignID', 'campaignName',
            'trackingURL', 'customParameters', 'ctr', 'campaignType', 'adgroupID', 'keywordID',
            'adgroupName', 'customURL', 'adGroupBid', 'bid',
            'negativeKeywords', 'qualityIndex', 'firstPageBidEstimate', 'keywordMatchType',
            'topOfPageBidEstimate', 'landingPageURL', 'landingPageURLSmartphone'
        ];

        return $this->unsetColumns($result, $unsetColumns);
    }

    public function getAllKeyword($accountId = null, $campaignId = null, $adgroupId = null, $keywordId = null)
    {
        $arrKeywords = [];

        $arrKeywords['all'] = 'All Keywords';

        $keywords = $this->select('keywordID', 'keyword')->where(
            function ($query) use ($accountId, $campaignId, $adgroupId, $keywordId) {
                $this->addQueryConditions(
                    $query,
                    Auth::user()->account_id,
                    $accountId,
                    $campaignId,
                    $adgroupId,
                    $keywordId
                );
            }
        )->get();

        if ($keywords) {
            foreach ($keywords as $key => $keyword) {
                $arrKeywords[$keyword->keywordID] = $keyword->keyword;
            }
        }

        return $arrKeywords;
    }
}
