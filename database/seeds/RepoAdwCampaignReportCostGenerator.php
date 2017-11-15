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
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 1;
    const MAX_IMPRESSIONS = 4096;
    const MIN_CLICKS = 1;
    const MAX_CLICKS = 9001;
    const MIN_AVERAGE_POSITION = 1000000;
    const MAX_AVERAGE_POSITION = 89489437437880;
    const MIN_CONVERSIONS = 1000000;
    const MAX_CONVERSIONS = 89489437437880;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 89489437437880;
    const NETWORKS = ['network1', 'network2', 'network3'];
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
                self::MIN_AVERAGE_POSITION,
                self::MAX_AVERAGE_POSITION
            ) / mt_getrandmax();
            $campaignReportCost->conversions = mt_rand(
                self::MIN_CONVERSIONS,
                self::MAX_CONVERSIONS
            ) / mt_getrandmax();
            $campaignReportCost->cost = mt_rand(
                self::MIN_COST,
                self::MAX_COST
            );
            $campaignReportCost->clicks = mt_rand(
                self::MIN_CLICKS,
                self::MAX_CLICKS
            );
            $campaignReportCost->impressions = mt_rand(
                self::MIN_IMPRESSIONS,
                self::MAX_IMPRESSIONS
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
            $campaignReportCost->campaignId = $accountReport->campaign_id;
            $campaignReportCost->campaign = 'Campaign Name ' . $accountReport->campaign_id;
            $campaignReportCost->account = $accountReport->account;
            $campaignReportCost->avgCPC = $campaignReportCost->cost / $campaignReportCost->clicks;
            $campaignReportCost->ctr = ($accountReport->clicks / $accountReport->impressions) * 100;
            $campaignReportCost->day = $accountReport->day;
            $campaignReportCost->dayOfWeek = $accountReport->dayOfWeek;
            $campaignReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
            $campaignReportCost->hourofday = $accountReport->hourofday;
            $campaignReportCost->timeZone = $accountReport->hourofday;
            $campaignReportCost->accountid = $accountReport->accountid;
            $campaignReportCost->saveOrFail();
        }
    }
}
