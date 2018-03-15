<?php

use Illuminate\Database\Seeder;

use App\Model\RepoYssAccountReportCost;
use App\Model\RepoYssCampaignReportConv;
use App\Model\RepoYssCampaignReportCost;

// @codingStandardsIgnoreLine
class RepoYssCampaignReportGenerator extends Seeder
{
    const MIN_DAILY_SPENDING_LIMIT = 1;
    const MAX_DAILY_SPENDING_LIMIT = 1004;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 0;
    const MIN_CLICKS = 0;
    const MIN_AVERAGE_POSITION = 1;
    const MAX_AVERAGE_POSITION = 20;
    const MIN_IMPRESSION_SHARE = 1000000;
    const MAX_IMPRESSION_SHARE = 9999999;
    const MIN_EXACT_MATCH_IMPRESSION_SHARE = 1000000;
    const MAX_EXACT_MATCH_IMPRESSION_SHARE = 9999999;
    const MIN_BUDGET_LOST_IMPRESSION_SHARE = 1000000;
    const MAX_BUDGET_LOST_IMPRESSION_SHARE = 9999999;
    const MIN_QUALITY_LOST_IMPRESSION_SHARE = 1000000;
    const MAX_QUALITY_LOST_IMPRESSION_SHARE = 9999999;
    const TRACKING_URL = 'http://we.track.people/';
    const CUSTOM_PARAMETERS = 'Custom Parameters';
    const MIN_CONVERSIONS = 0;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 9999999;
    const MIN_COST_PER_CONV = 1000000;
    const MAX_COST_PER_CONV = 9999999;
    const MIN_VALUE_PER_CONV = 1000000;
    const MAX_VALUE_PER_CONV = 9999999;
    const MIN_MOBILE_BID_ADJ = 1000000;
    const MAX_MOBILE_BID_ADJ = 9999999;
    const MIN_DESKTOP_BID_ADJ = 1000000;
    const MAX_DESKTOP_BID_ADJ = 9999999;
    const MIN_TABLET_BID_ADJ = 1000000;
    const MAX_TABLET_BID_ADJ = 9999999;
    const MIN_COST_PER_ALL_CONV = 1000000;
    const MAX_COST_PER_ALL_CONV = 9999999;
    const MIN_VALUE_PER_ALL_CONV = 1000000;
    const MAX_VALUE_PER_ALL_CONV = 9999999;
    const MIN_ALL_CONV = 1000000;
    const MAX_ALL_CONV = 9999999;
    const MIN_ALL_CONV_VALUE = 1000000;
    const MAX_ALL_CONV_VALUE = 9999999;
    const NETWORKS = ['network1', 'network2', 'network3'];
    const DEVICES = ['SMART_PHONE', 'DESKTOP', 'NONE'];
    const CAMPAIGN_TYPE = [
        'Campaign Type 1', 'Campaign Type 2',
        'Campaign Type 3', 'Campaign Type 4'
    ];
    const CLICK_TYPE = [
        'Click Type 1', 'Click Type 2',
        'Click Type 3', 'Click Type 4'
    ];
    const OBJECTIVE_OF_CONVERSION_TRACKING = 'Objective of conversion tracking';
    const NUMBER_OF_CONV_POINTS = 3;

    private function seedConv($campaignReportCost)
    {
        for ($i = 0; $i < self::NUMBER_OF_CONV_POINTS; $i++) {
            $campaignReportConv = new RepoYssCampaignReportConv;
            $campaignReportConv->exeDate = $campaignReportCost->exeDate;
            $campaignReportConv->startDate = $campaignReportCost->startDate;
            $campaignReportConv->endDate = $campaignReportCost->endDate;
            $campaignReportConv->account_id = $campaignReportCost->account_id;
            $campaignReportConv->campaign_id = $campaignReportCost->campaign_id;
            $campaignReportConv->campaignID = $campaignReportCost->campaign_id;
            $campaignReportConv->campaignName = 'YSS Campaign Name ' . $campaignReportCost->campaign_id;
            $campaignReportConv->campaignDistributionSettings = 'Distribution Settings '
                . $campaignReportCost->campaign_id;
            $campaignReportConv->campaignDistributionStatus = 'Distribution Status' . $campaignReportCost->campaign_id;
            $campaignReportConv->dailySpendingLimit = $campaignReportCost->dailySpendingLimit;
            $campaignReportConv->campaignStartDate = $campaignReportCost->startDate;
            $campaignReportConv->campaignEndDate = $campaignReportCost->endDate;
            $campaignReportConv->trackingURL = self::TRACKING_URL;
            $campaignReportConv->customParameters = self::CUSTOM_PARAMETERS . ' ' . $campaignReportCost->campaign_id;
            $campaignReportConv->campaignTrackingID = $campaignReportCost->campaign_id;
            $campaignReportConv->conversions = $campaignReportCost->conversions / self::NUMBER_OF_CONV_POINTS;
            $campaignReportConv->convValue = $campaignReportCost->convValue;
            $campaignReportConv->valuePerConv = $campaignReportCost->valuePerConv;
            $campaignReportConv->mobileBidAdj = $campaignReportCost->mobileBidAdj;
            $campaignReportConv->desktopBidAdj = $campaignReportCost->desktopBidAdj;
            $campaignReportConv->tabletBidAdj = $campaignReportCost->tabletBidAdj;
            $campaignReportConv->valuePerAllConv = mt_rand(
                self::MIN_VALUE_PER_ALL_CONV,
                self::MAX_VALUE_PER_ALL_CONV
            ) / mt_getrandmax();
            $campaignReportConv->allConv = mt_rand(
                self::MIN_ALL_CONV,
                self::MAX_ALL_CONV
            ) / mt_getrandmax();
            $campaignReportConv->allConvValue = mt_rand(
                self::MIN_ALL_CONV_VALUE,
                self::MAX_ALL_CONV_VALUE
            ) / mt_getrandmax();
            $campaignReportConv->network = $campaignReportCost->network;
            $campaignReportConv->device = $campaignReportCost->device;
            $campaignReportConv->day = $campaignReportCost->day;
            $campaignReportConv->dayOfWeek = $campaignReportCost->dayOfWeek;
            $campaignReportConv->quarter = $campaignReportCost->quarter;
            $campaignReportConv->month = $campaignReportCost->month;
            $campaignReportConv->week = $campaignReportCost->week;
            $campaignReportConv->campaignType = $campaignReportCost->campaignType;
            $campaignReportConv->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
            $campaignReportConv->objectiveOfConversionTracking = self::OBJECTIVE_OF_CONVERSION_TRACKING;
            $campaignReportConv->conversionName = 'YSS conversion '
                . (string) $campaignReportCost->account_id
                . (string) $campaignReportCost->campaign_id
                . (string) $campaignReportCost->accountid
                . $i;
            $campaignReportConv->accountid = $campaignReportCost->accountid;
            $campaignReportConv->saveOrFail();
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountReports = RepoYssAccountReportCost::all();
        foreach ($accountReports as $accountReport) {
            $campaignReportCost = new RepoYssCampaignReportCost;
            $campaignReportCost->exeDate = $accountReport->exeDate;
            $campaignReportCost->startDate = $accountReport->startDate;
            $campaignReportCost->endDate = $accountReport->endDate;
            $campaignReportCost->account_id = $accountReport->account_id;
            $campaignReportCost->campaign_id = $accountReport->campaign_id;
            $campaignReportCost->campaignID = $accountReport->campaign_id;
            $campaignReportCost->campaignName = 'YSS Campaign Name ' . $accountReport->campaign_id;
            $campaignReportCost->campaignDistributionSettings = 'Distribution Settings ' . $accountReport->campaign_id;
            $campaignReportCost->campaignDistributionStatus = 'Distribution Status' . $accountReport->campaign_id;
            $campaignReportCost->dailySpendingLimit = mt_rand(
                self::MIN_DAILY_SPENDING_LIMIT,
                self::MAX_DAILY_SPENDING_LIMIT
            );
            $campaignReportCost->campaignStartDate = $accountReport->startDate;
            $campaignReportCost->campaignEndDate = $accountReport->endDate;
            $campaignReportCost->cost = mt_rand(
                self::MIN_COST,
                self::MAX_COST
            );
            $campaignReportCost->impressions = mt_rand(
                self::MIN_IMPRESSIONS,
                $accountReport->impressions
            );
            $campaignReportCost->clicks = mt_rand(
                self::MIN_CLICKS,
                $campaignReportCost->impressions
            );

            if ($campaignReportCost->impressions === 0) {
                $campaignReportCost->ctr = 0;
            } else {
                $campaignReportCost->ctr = ($campaignReportCost->clicks / $campaignReportCost->impressions) * 100;
            }

            if ($campaignReportCost->clicks === 0) {
                $campaignReportCost->averageCpc = 0;
            } else {
                $campaignReportCost->averageCpc = $campaignReportCost->cost / $campaignReportCost->clicks;
            }

            $campaignReportCost->averagePosition = mt_rand(
                self::MIN_AVERAGE_POSITION * 100000,
                self::MAX_AVERAGE_POSITION * 100000
            ) / 100000;

            $campaignReportCost->impressionShare = mt_rand(
                self::MIN_IMPRESSION_SHARE,
                self::MAX_IMPRESSION_SHARE
            ) / mt_getrandmax();
            $campaignReportCost->exactMatchImpressionShare = mt_rand(
                self::MIN_EXACT_MATCH_IMPRESSION_SHARE,
                self::MAX_EXACT_MATCH_IMPRESSION_SHARE
            ) / mt_getrandmax();

            $campaignReportCost->budgetLostImpressionShare = mt_rand(

                self::MIN_BUDGET_LOST_IMPRESSION_SHARE,
                self::MAX_BUDGET_LOST_IMPRESSION_SHARE
            ) / mt_getrandmax();

            $campaignReportCost->qualityLostImpressionShare = mt_rand(

                self::MIN_QUALITY_LOST_IMPRESSION_SHARE,
                self::MAX_QUALITY_LOST_IMPRESSION_SHARE
            ) / mt_getrandmax();

            $campaignReportCost->trackingURL = self::TRACKING_URL;

            $campaignReportCost->customParameters = self::CUSTOM_PARAMETERS . ' ' . $accountReport->campaign_id;

            $campaignReportCost->campaignTrackingID = $accountReport->campaign_id;

            $campaignReportCost->conversions = mt_rand(
                self::MIN_CONVERSIONS,
                $campaignReportCost->clicks
            );

            if ($campaignReportCost->clicks === 0) {
                $campaignReportCost->convRate = 0;
            } else {
                $campaignReportCost->convRate = ($campaignReportCost->conversions / $campaignReportCost->clicks) * 100;
            }

            $campaignReportCost->convValue = mt_rand(
                self::MIN_CONV_VALUE,
                self::MAX_CONV_VALUE
            ) / mt_getrandmax();
            $campaignReportCost->costPerConv = mt_rand(
                self::MIN_COST_PER_CONV,
                self::MAX_COST_PER_CONV
            ) / mt_getrandmax();
            $campaignReportCost->valuePerConv = mt_rand(
                self::MIN_VALUE_PER_CONV,
                self::MAX_VALUE_PER_CONV
            ) / mt_getrandmax();
            $campaignReportCost->mobileBidAdj = mt_rand(
                self::MIN_MOBILE_BID_ADJ,
                self::MAX_MOBILE_BID_ADJ
            ) / mt_getrandmax();
            $campaignReportCost->desktopBidAdj = mt_rand(
                self::MIN_DESKTOP_BID_ADJ,
                self::MAX_DESKTOP_BID_ADJ
            ) / mt_getrandmax();
            $campaignReportCost->tabletBidAdj = mt_rand(
                self::MIN_TABLET_BID_ADJ,
                self::MAX_TABLET_BID_ADJ
            ) / mt_getrandmax();

            $campaignReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
            $campaignReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
            $campaignReportCost->day = $accountReport->day;
            $campaignReportCost->dayOfWeek = $accountReport->dayOfWeek;
            $campaignReportCost->quarter = $accountReport->quarter;
            $campaignReportCost->month = $accountReport->month;
            $campaignReportCost->week = $accountReport->week;
            $campaignReportCost->hourofday = $accountReport->hourofday;
            $campaignReportCost->campaignType = self::CAMPAIGN_TYPE[mt_rand(0, count(self::CAMPAIGN_TYPE) - 1)];

            $campaignReportCost->accountid = $accountReport->accountid;

            $campaignReportCost->saveOrFail();

            $this->seedConv($campaignReportCost);
        }
    }
}
