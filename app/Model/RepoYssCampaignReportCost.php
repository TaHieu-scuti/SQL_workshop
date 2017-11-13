<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\AbstractReportModel;

use DateTime;
use Exception;
use Auth;

class RepoYssCampaignReportCost extends AbstractReportModel
{
    // constant
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'campaignName';
    const PAGE_ID = 'campaignID';

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $table = 'repo_yss_campaign_report_cost';

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
            'campaignStartDate', 'campaignEndDate', 'campaignDistributionStatus', 'campaignDistributionSettings',
            'mobileBidAdj', 'tabletBidAdj', 'desktopBidAdj', 'exeDate',
            'startDate', 'endDate', 'campaignID', 'campaignName',
            'trackingURL', 'customParameters', 'ctr', 'campaignType'
        ];

        return $this->unsetColumns($result, $unsetColumns);
    }

    public static function getAllCampaign()
    {
        $arrCampaigns = [];

        $arrCampaigns['all'] = 'All Campaigns';

        $campaigns = self::select('campaignID', 'campaignName')
            ->where('account_id', '=', Auth::user()->account_id)
            ->get();

        if ($campaigns) {
            foreach ($campaigns as $key => $campaign) {
                $arrCampaigns[$campaign->campaignID] = $campaign->campaignName;
            }
        }

        return $arrCampaigns;
    }
}
