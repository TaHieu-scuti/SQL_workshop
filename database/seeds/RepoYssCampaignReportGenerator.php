<?php

use Illuminate\Database\Seeder;

use App\Model\RepoYssAccountReportCost;
use App\Model\RepoYssCampaignReportConv;
use App\Model\RepoYssCampaignReportCost;

class RepoYssCampaignReportGenerator extends Seeder
{
    const MIN_DAILY_SPENDING_LIMIT = 1;
    const MAX_DAILY_SPENDING_LIMIT = 1004;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_CTR = 1000000;
    const MAX_CTR = 7344032456345;
    const MIN_AVERAGE_CPC = 1000000;
    const MAX_AVERAGE_CPC = 89489437437880;
    const MIN_AVERAGE_POSITION = 1000000;
    const MAX_AVERAGE_POSITION = 89489437437880;
    const MIN_IMPRESSION_SHARE = 1000000;
    const MAX_IMPRESSION_SHARE = 89489437437880;
    const MIN_EXACT_MATCH_IMPRESSION_SHARE = 1000000;
    const MAX_EXACT_MATCH_IMPRESSION_SHARE = 89489437437880;
    const MIN_BUDGET_LOST_IMPRESSION_SHARE = 1000000;
    const MAX_BUDGET_LOST_IMPRESSION_SHARE = 89489437437880;
    const MIN_QUALITY_LOST_IMPRESSION_SHARE = 1000000;
    const MAX_QUALITY_LOST_IMPRESSION_SHARE = 89489437437880;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountReports = RepoYssAccountReportCost::all();
        foreach ($accountReports as $accountReport) {
            $campaignReport = new RepoYssCampaignReportCost;
            $campaignReport->exeDate = $accountReport->exeDate;
            $campaignReport->startDate = $accountReport->startDate;
            $campaignReport->endDate = $accountReport->endDate;
            $campaignReport->account_id = $accountReport->account_id;
            $campaignReport->campaign_id = $accountReport->campaign_id;
            $campaignReport->campaignID = $accountReport->campaign_id;
            $campaignReport->campaignName = 'Campaign Name ' . $accountReport->campaign_id;
            $campaignReport->campaignDistributionSettings = 'Distribution Settings ' . $accountReport->campaign_id;
            $campaignReport->campaignDistributionStatus = 'Distribution Status' . $accountReport->campaign_id;
            $campaignReport->dailySpendingLimit = mt_rand(
                self::MIN_DAILY_SPENDING_LIMIT,
                self::MAX_DAILY_SPENDING_LIMIT
            );
            $campaignReport->cost = mt_rand(
                self::MIN_COST,
                self::MAX_COST
            );
            $report->impressions = mt_rand(
                self::MIN_IMPRESSIONS,
                self::MAX_IMPRESSIONS
            );
            $report->clicks = mt_rand(
                self::MIN_CLICKS,
                self::MAX_CLICKS
            );
            $report->ctr = mt_rand(self::MIN_CTR, self::MAX_CTR) / mt_getrandmax();
            $report->averageCpc = mt_rand(
                self::MIN_AVERAGE_CPC,
                self::MAX_AVERAGE_CPC
            ) / mt_getrandmax();
            $report->averagePosition = mt_rand(
                self::MIN_AVERAGE_POSITION,
                self::MAX_AVERAGE_POSITION
            ) / mt_getrandmax();
            $report->impressionShare = mt_rand(
                self::MIN_IMPRESSION_SHARE,
                self::MAX_IMPRESSION_SHARE
            ) / mt_getrandmax();
            $report->exactMatchImpressionShare = mt_rand(
                self::MIN_EXACT_MATCH_IMPRESSION_SHARE,
                self::MAX_EXACT_MATCH_IMPRESSION_SHARE
            ) / mt_getrandmax();
            $report->budgetLostImpressionShare = mt_rand(
                self::MIN_BUDGET_LOST_IMPRESSION_SHARE,
                self::MAX_BUDGET_LOST_IMPRESSION_SHARE
            ) / mt_getrandmax();
            $report->qualityLostImpressionShare = mt_rand(
                self::MIN_QUALITY_LOST_IMPRESSION_SHARE,
                self::MAX_QUALITY_LOST_IMPRESSION_SHARE
            ) / mt_getrandmax();
        }
    }

    private function createReport()
    {

    }
}
