<?php

use Illuminate\Database\Seeder;

use App\Model\RepoAdwCampaignReportCost;
use App\Model\RepoAdwAccountReportCost;

// @codingStandardsIgnoreLine
class RepoAdwCampaignReportCostGenerator extends Seeder
{
    const START_DATE = '2017-01-01 00:00:00';
    const INTERVAL = 'P1D';
    const END_DATE = '2018-02-03 00:00:00';
    const NUMBER_OF_ACCOUNTS = 2;
    const NUMBER_OF_MEDIA_ACCOUNTS = [
        2,
        4,
        5,
        1,
        3
    ];
    const MIN_NUMBER_OF_CAMPAIGNS = 1;
    const MAX_NUMBER_OF_CAMPAIGNS = 5;
    const MIN_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN = 0;
    const MAX_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN = 5;
    const MIN_COST = 0;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 0;
    const MIN_IMPRESSIONS_SHARE = 0;
    const MIN_CLICKS = 0;
    const MIN_AVERAGE_POSITION = 1;
    const MAX_AVERAGE_POSITION = 20;
    const MIN_CONVERSIONS = 0;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 89489437437880;
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountReports = RepoAdwAccountReportCost::all();
        foreach ($accountReports as $accountReport) {
            $campaignReportCost = new RepoAdwCampaignReportCost;
            $campaignReportCost->avgPosition = $accountReport->avgPosition = mt_rand(
                self::MIN_AVERAGE_POSITION * 100000,
                self::MAX_AVERAGE_POSITION * 100000
            ) / 100000;

            $campaignReportCost->cost = mt_rand(
                self::MIN_COST,
                self::MAX_COST
            );

            $campaignReportCost->impressions = mt_rand(
                self::MIN_IMPRESSIONS,
                $accountReport->impressions
            );

            $campaignReportCost->contentImprShare = mt_rand(
                self::MIN_IMPRESSIONS_SHARE,
                $accountReport->contentImprShare
            );

            $campaignReportCost->searchImprShare = mt_rand(
                self::MIN_IMPRESSIONS_SHARE,
                $accountReport->searchImprShare
            );

            $campaignReportCost->clicks = mt_rand(
                self::MIN_CLICKS,
                $campaignReportCost->impressions
            );

            $campaignReportCost->conversions = mt_rand(
                self::MIN_CONVERSIONS,
                $campaignReportCost->clicks
            );

            $campaignReportCost->month = $accountReport->month;
            $campaignReportCost->valueConv = mt_rand(
                self::MIN_CONV_VALUE,
                self::MAX_CONV_VALUE
            ) / mt_getrandmax();

            $campaignReportCost->exeDate = $accountReport->exeDate;
            $campaignReportCost->startDate = $accountReport->startDate;
            $campaignReportCost->endDate = $accountReport->endDate;
            $campaignReportCost->account_id = $accountReport->account_id;
            $campaignReportCost->campaign_id = $accountReport->campaign_id;
            $campaignReportCost->campaignID = $accountReport->campaign_id;
            $campaignReportCost->campaign = 'ADW Campaign Name ' . $accountReport->campaign_id;
            $campaignReportCost->account = $accountReport->account;

            if ($campaignReportCost->clicks === 0) {
                $campaignReportCost->avgCPC = 0;
            } else {
                $campaignReportCost->avgCPC = $campaignReportCost->cost / $campaignReportCost->clicks;
            }

            if ($accountReport->impressions === 0) {
                $campaignReportCost->ctr = 0;
            } else {
                $campaignReportCost->ctr = ($accountReport->clicks / $accountReport->impressions) * 100;
            }

            $campaignReportCost->network = $accountReport->network;

            $campaignReportCost->day = $accountReport->day;
            $campaignReportCost->dayOfWeek = $accountReport->dayOfWeek;
            $campaignReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
            $campaignReportCost->hourOfDay = $accountReport->hourOfDay;
            $campaignReportCost->timeZone = $accountReport->hourOfDay;
            $campaignReportCost->customerID = $accountReport->customerID;
            $campaignReportCost->saveOrFail();
        }
    }
}
