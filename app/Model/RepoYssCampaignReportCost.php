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
     * @param string $column
     * @param string $accountStatus
     * @param string $startDay
     * @param string $endDay
     * @return \Illuminate\Support\Collection
     */
    public function getDataForGraph(
        $column,
        $accountStatus,
        $startDay,
        $endDay,
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        try {
            new DateTime($startDay); //NOSONAR
            new DateTime($endDay); //NOSONAR
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }

        $data = $this->select(
            DB::raw('SUM('.$column.') as data'),
            DB::raw(
                'DATE(day) as day'
            )
        )
        ->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query);
            }
        )
        ->where(
            function (Builder $query) use ($accountId, $adgainerId, $campaignId) {
                if ($campaignId !== null) {
                    $query->where('campaignID', '=', $campaignId);
                } elseif ($campaignId === null && $accountId !== null) {
                    $query->where('accountid', '=', $accountId);
                } elseif ($campaignId === null && $accountId === null) {
                    $query->where('account_id', '=', $adgainerId);
                }
            }
        )
        ->groupBy('day');
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                            ->get();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->get();
        }
        return $data;
    }

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
