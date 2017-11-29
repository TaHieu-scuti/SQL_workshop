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
    const MIN_CONV_RATE = 1000000;
    const MAX_CONV_RATE = 89489437437880;
    const MIN_AVERAGE_POSITION = 1;
    const MAX_AVERAGE_POSITION = 20;
    const MIN_IMPRESSION_SHARE = 1000000;
    const MAX_IMPRESSION_SHARE = 89489437437880;
    const MIN_EXACT_MATCH_IMPRESSION_SHARE = 1000000;
    const MAX_EXACT_MATCH_IMPRESSION_SHARE = 89489437437880;
    const MIN_BUDGET_LOST_IMPRESSION_SHARE = 1000000;
    const MAX_BUDGET_LOST_IMPRESSION_SHARE = 89489437437880;
    const MIN_QUALITY_LOST_IMPRESSION_SHARE = 1000000;
    const MAX_QUALITY_LOST_IMPRESSION_SHARE = 89489437437880;
    const TRACKING_URL = 'http://we.track.people/';
    const CUSTOM_PARAMETERS = 'Custom Parameters';
    const MIN_CONVERSIONS = 1000000;
    const MAX_CONVERSIONS = 89489437437880;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 89489437437880;
    const MIN_COST_PER_CONV = 1000000;
    const MAX_COST_PER_CONV = 89489437437880;
    const MIN_VALUE_PER_CONV = 1000000;
    const MAX_VALUE_PER_CONV = 89489437437880;
    const MIN_MOBILE_BID_ADJ = 1000000;
    const MAX_MOBILE_BID_ADJ = 89489437437880;
    const MIN_DESKTOP_BID_ADJ = 1000000;
    const MAX_DESKTOP_BID_ADJ = 89489437437880;
    const MIN_TABLET_BID_ADJ = 1000000;
    const MAX_TABLET_BID_ADJ = 89489437437880;
    const MIN_COST_PER_ALL_CONV = 1000000;
    const MAX_COST_PER_ALL_CONV = 89489437437880;
    const MIN_VALUE_PER_ALL_CONV = 1000000;
    const MAX_VALUE_PER_ALL_CONV = 89489437437880;
    const MIN_ALL_CONV = 1000000;
    const MAX_ALL_CONV = 89489437437880;
    const MIN_ALL_CONV_VALUE = 1000000;
    const MAX_ALL_CONV_VALUE = 89489437437880;
    const NETWORKS = ['network1', 'network2', 'network3'];
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];
    const CAMPAIGN_TYPE = [
        'Campaign Type 1', 'Campaign Type 2',
        'Campaign Type 3', 'Campaign Type 4'
    ];
    const CLICK_TYPE = [
        'Click Type 1', 'Click Type 2',
        'Click Type 3', 'Click Type 4'
    ];
    const OBJECTIVE_OF_CONVERSION_TRACKING = 'Objective of conversion tracking';
    const CONVERSION_NAME = [
        'Conversion Name 1', 'Conversion Name 2',
        'Conversion Name 3', 'Conversion Name 4'
    ];
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
            $campaignReportConv = new RepoYssCampaignReportConv;
            $campaignReportCost->exeDate = $accountReport->exeDate;
            $campaignReportConv->exeDate = $accountReport->exeDate;
            $campaignReportCost->startDate = $accountReport->startDate;
            $campaignReportConv->startDate = $accountReport->startDate;
            $campaignReportCost->endDate = $accountReport->endDate;
            $campaignReportConv->endDate = $accountReport->endDate;
            $campaignReportCost->account_id = $accountReport->account_id;
            $campaignReportConv->account_id = $accountReport->account_id;
            $campaignReportCost->campaign_id = $accountReport->campaign_id;
            $campaignReportConv->campaign_id = $accountReport->campaign_id;
            $campaignReportCost->campaignID = $accountReport->campaign_id;
            $campaignReportConv->campaignID = $accountReport->campaign_id;
            $campaignReportCost->campaignName = 'YSS Campaign Name ' . $accountReport->campaign_id;
            $campaignReportConv->campaignName = 'YSS Campaign Name ' . $accountReport->campaign_id;
            $campaignReportCost->campaignDistributionSettings = 'Distribution Settings ' . $accountReport->campaign_id;
            $campaignReportConv->campaignDistributionSettings = 'Distribution Settings ' . $accountReport->campaign_id;
            $campaignReportCost->campaignDistributionStatus = 'Distribution Status' . $accountReport->campaign_id;
            $campaignReportConv->campaignDistributionStatus = 'Distribution Status' . $accountReport->campaign_id;
            $campaignReportCost->dailySpendingLimit = mt_rand(
                self::MIN_DAILY_SPENDING_LIMIT,
                self::MAX_DAILY_SPENDING_LIMIT
            );
            $campaignReportConv->dailySpendingLimit = $campaignReportCost->dailySpendingLimit;
            $campaignReportCost->campaignStartDate = $accountReport->startDate;
            $campaignReportCost->campaignEndDate = $accountReport->endDate;
            $campaignReportConv->campaignStartDate = $accountReport->startDate;
            $campaignReportConv->campaignEndDate = $accountReport->endDate;
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
            $campaignReportConv->trackingURL = self::TRACKING_URL;
            $campaignReportCost->customParameters = self::CUSTOM_PARAMETERS . ' ' . $accountReport->campaign_id;
            $campaignReportConv->customParameters = self::CUSTOM_PARAMETERS . ' ' . $accountReport->campaign_id;
            $campaignReportCost->campaignTrackingID = $accountReport->campaign_id;
            $campaignReportConv->campaignTrackingID = $accountReport->campaign_id;
            $campaignReportCost->conversions = mt_rand(
                self::MIN_CONVERSIONS,
                self::MAX_CONVERSIONS
            ) / mt_getrandmax();
            $campaignReportConv->conversions = $campaignReportCost->conversions;
            $campaignReportCost->convRate = mt_rand(
                self::MIN_CONV_RATE,
                self::MAX_CONV_RATE
            ) / mt_getrandmax();
            $campaignReportCost->convValue = mt_rand(
                self::MIN_CONV_VALUE,
                self::MAX_CONV_VALUE
            ) / mt_getrandmax();
            $campaignReportConv->convValue = $campaignReportCost->convValue;
            $campaignReportCost->costPerConv = mt_rand(
                self::MIN_COST_PER_CONV,
                self::MAX_COST_PER_CONV
            ) / mt_getrandmax();
            $campaignReportCost->valuePerConv = mt_rand(
                self::MIN_VALUE_PER_CONV,
                self::MAX_VALUE_PER_CONV
            ) / mt_getrandmax();
            $campaignReportConv->valuePerConv = $campaignReportCost->valuePerConv;
            $campaignReportCost->mobileBidAdj = mt_rand(
                self::MIN_MOBILE_BID_ADJ,
                self::MAX_MOBILE_BID_ADJ
            ) / mt_getrandmax();
            $campaignReportConv->mobileBidAdj = $campaignReportCost->mobileBidAdj;
            $campaignReportCost->desktopBidAdj = mt_rand(
                self::MIN_DESKTOP_BID_ADJ,
                self::MAX_DESKTOP_BID_ADJ
            ) / mt_getrandmax();
            $campaignReportConv->desktopBidAdj = $campaignReportCost->desktopBidAdj;
            $campaignReportCost->tabletBidAdj = mt_rand(
                self::MIN_TABLET_BID_ADJ,
                self::MAX_TABLET_BID_ADJ
            ) / mt_getrandmax();
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
            $campaignReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
            $campaignReportConv->network = $campaignReportCost->network;
            $campaignReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
            $campaignReportConv->device = $campaignReportCost->device;
            $campaignReportCost->day = $accountReport->day;
            $campaignReportConv->day = $campaignReportCost->day;
            $campaignReportCost->dayOfWeek = $accountReport->dayOfWeek;
            $campaignReportConv->dayOfWeek = $campaignReportCost->dayOfWeek;
            $campaignReportCost->quarter = $accountReport->quarter;
            $campaignReportConv->quarter = $campaignReportCost->quarter;
            $campaignReportCost->month = $accountReport->month;
            $campaignReportConv->month = $campaignReportCost->month;
            $campaignReportCost->week = $accountReport->week;
            $campaignReportConv->week = $campaignReportCost->week;
            $campaignReportCost->hourofday = $accountReport->hourofday;
            $campaignReportCost->campaignType = self::CAMPAIGN_TYPE[mt_rand(0, count(self::CAMPAIGN_TYPE) - 1)];
            $campaignReportConv->campaignType = $campaignReportCost->campaignType;
            $campaignReportConv->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
            $campaignReportConv->objectiveOfConversionTracking = self::OBJECTIVE_OF_CONVERSION_TRACKING;
            $campaignReportConv->conversionName = self::CONVERSION_NAME[mt_rand(0, count(self::CONVERSION_NAME) - 1)];
            $campaignReportCost->accountid = $accountReport->accountid;
            $campaignReportConv->accountid = $accountReport->accountid;

            $campaignReportCost->saveOrFail();
            $campaignReportConv->saveOrFail();
        }
    }
}
