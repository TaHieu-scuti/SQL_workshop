<?php

use Illuminate\Database\Seeder;

use App\Model\RepoYssAccountReportCost;
use App\Model\RepoYssCampaignReportConv;
use App\Model\RepoYssCampaignReportCost;
use App\Model\RepoYssAdgroupReportCost;
use App\Model\RepoYssAdgroupReportConv;

// @codingStandardsIgnoreLine
class RepoYssAdgroupReportGenerator extends Seeder
{
    const MIN_NUMBER_OF_ADGROUP = 1;
    const MAX_NUMBER_OF_ADGROUP = 3;
    const MIN_DAILY_SPENDING_LIMIT = 1;
    const MAX_DAILY_SPENDING_LIMIT = 1004;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_ADGROUP_BID = 1;
    const MAX_ADGROUP_BID = 1004;
    const MIN_IMPRESSIONS = 0;
    const MIN_CLICKS = 0;
    const MIN_CONV_RATE = 1000000;
    const MAX_CONV_RATE = 89489437437880;
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
        $campaignReports = RepoYssCampaignReportCost::all();
        foreach ($campaignReports as $campaignReport) {
            $ammountOfAdgroup = rand(
                self::MIN_NUMBER_OF_ADGROUP,
                self::MAX_NUMBER_OF_ADGROUP
            );
            for ($i = 0; $i < $ammountOfAdgroup + 1; $i++) {
                $adgroupReportCost = new RepoYssAdgroupReportCost;
                $adgroupReportConv = new RepoYssAdgroupReportConv;
                $adgroupReportCost->exeDate = $campaignReport->exeDate;
                $adgroupReportConv->exeDate = $campaignReport->exeDate;
                $adgroupReportCost->startDate = $campaignReport->startDate;
                $adgroupReportConv->startDate = $campaignReport->startDate;
                $adgroupReportCost->endDate = $campaignReport->endDate;
                $adgroupReportConv->endDate = $campaignReport->endDate;
                $adgroupReportCost->account_id = $campaignReport->account_id;
                $adgroupReportConv->account_id = $campaignReport->account_id;
                $adgroupReportCost->campaign_id = $campaignReport->campaign_id;
                $adgroupReportConv->campaign_id = $campaignReport->campaign_id;
                $adgroupReportCost->campaignID = $campaignReport->campaignID;
                $adgroupReportConv->campaignID = $campaignReport->campaignID;
                $adgroupReportCost->adgroupID = $i;
                $adgroupReportConv->adgroupID = $i;
                $adgroupReportCost->campaignName = $campaignReport->campaignName;
                $adgroupReportConv->campaignName = $campaignReport->campaignName;
                $adgroupReportCost->adgroupName = 'YSS Adgroup Name ' . $i;
                $adgroupReportConv->adgroupName = 'YSS Adgroup Name ' . $i;
                $adgroupReportCost->adgroupDistributionSettings = 'Adgroup Distribution setting';
                $adgroupReportConv->adgroupDistributionSettings = $adgroupReportCost->adgroupDistributionSettings;
                $adgroupReportCost->adGroupBid = mt_rand(
                    self::MIN_ADGROUP_BID,
                    self::MAX_ADGROUP_BID
                );

                $adgroupReportConv->adGroupBid = $adgroupReportCost->adGroupBid;
                $adgroupReportCost->cost = mt_rand(
                    self::MIN_COST,
                    self::MAX_COST
                );
                $adgroupReportCost->impressions = mt_rand(
                    self::MIN_IMPRESSIONS,
                    $campaignReport->impressions
                );
                $adgroupReportCost->clicks = mt_rand(
                    self::MIN_CLICKS,
                    $adgroupReportCost->impressions
                );
                $adgroupReportCost->ctr = ($adgroupReportCost->clicks / $adgroupReportCost->impressions) * 100;
                $adgroupReportCost->averageCpc = mt_rand(
                    self::MIN_AVERAGE_CPC,
                    self::MAX_AVERAGE_CPC
                ) / mt_getrandmax();
                $adgroupReportCost->averagePosition = mt_rand(
                    self::MIN_AVERAGE_POSITION,
                    self::MAX_AVERAGE_POSITION
                ) / mt_getrandmax();
                $adgroupReportCost->impressionShare = mt_rand(
                    self::MIN_IMPRESSION_SHARE,
                    self::MAX_IMPRESSION_SHARE
                ) / mt_getrandmax();
                $adgroupReportCost->exactMatchImpressionShare = mt_rand(
                    self::MIN_EXACT_MATCH_IMPRESSION_SHARE,
                    self::MAX_EXACT_MATCH_IMPRESSION_SHARE
                ) / mt_getrandmax();
                $adgroupReportCost->qualityLostImpressionShare = mt_rand(
                    self::MIN_BUDGET_LOST_IMPRESSION_SHARE,
                    self::MAX_BUDGET_LOST_IMPRESSION_SHARE
                ) / mt_getrandmax();
                $adgroupReportCost->trackingURL = self::TRACKING_URL;
                $adgroupReportConv->trackingURL = self::TRACKING_URL;
                $adgroupReportCost->customParameters = self::CUSTOM_PARAMETERS . ' ' . $i;
                $adgroupReportCost->conversions = mt_rand(
                    self::MIN_CONVERSIONS,
                    self::MAX_CONVERSIONS
                ) / mt_getrandmax();
                $adgroupReportConv->conversions = $adgroupReportCost->conversions;
                $adgroupReportCost->convRate = mt_rand(
                    self::MIN_CONV_RATE,
                    self::MAX_CONV_RATE
                ) / mt_getrandmax();
                $adgroupReportCost->convValue = mt_rand(
                    self::MIN_CONV_VALUE,
                    self::MAX_CONV_VALUE
                ) / mt_getrandmax();
                $adgroupReportConv->convValue = $adgroupReportConv->convValue;
                $adgroupReportCost->costPerConv = mt_rand(
                    self::MIN_COST_PER_CONV,
                    self::MAX_COST_PER_CONV
                ) / mt_getrandmax();
                $adgroupReportCost->valuePerConv = mt_rand(
                    self::MIN_VALUE_PER_CONV,
                    self::MAX_VALUE_PER_CONV
                ) / mt_getrandmax();
                $adgroupReportConv->valuePerConv = $adgroupReportCost->valuePerConv;
                $adgroupReportCost->mobileBidAdj = mt_rand(
                    self::MIN_MOBILE_BID_ADJ,
                    self::MAX_MOBILE_BID_ADJ
                ) / mt_getrandmax();
                $adgroupReportConv->mobileBidAdj = $adgroupReportCost->mobileBidAdj;
                $adgroupReportCost->desktopBidAdj = mt_rand(
                    self::MIN_DESKTOP_BID_ADJ,
                    self::MAX_DESKTOP_BID_ADJ
                ) / mt_getrandmax();
                $adgroupReportConv->desktopBidAdj = $adgroupReportCost->desktopBidAdj;
                $adgroupReportCost->tabletBidAdj = mt_rand(
                    self::MIN_TABLET_BID_ADJ,
                    self::MAX_TABLET_BID_ADJ
                ) / mt_getrandmax();
                $adgroupReportConv->tabletBidAdj = $adgroupReportCost->tabletBidAdj;
                $adgroupReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                $adgroupReportConv->network = $adgroupReportCost->network;
                $adgroupReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                $adgroupReportConv->device = $adgroupReportCost->device;
                $adgroupReportCost->day = $campaignReport->day;
                $adgroupReportConv->day = $campaignReport->day;
                $adgroupReportCost->dayOfWeek = $campaignReport->dayOfWeek;
                $adgroupReportConv->dayOfWeek = $campaignReport->dayOfWeek;
                $adgroupReportCost->quarter = $campaignReport->quarter;
                $adgroupReportConv->quarter = $campaignReport->quarter;
                $adgroupReportCost->month = $campaignReport->month;
                $adgroupReportConv->month = $campaignReport->month;
                $adgroupReportCost->week = $campaignReport->week;
                $adgroupReportConv->week = $campaignReport->week;
                $adgroupReportCost->hourofday = $campaignReport->hourofday;
                $adgroupReportConv->customParameters = self::CUSTOM_PARAMETERS . ' ' . $i;
                $adgroupReportConv->allConv = mt_rand(
                    self::MIN_ALL_CONV,
                    self::MAX_ALL_CONV
                ) / mt_getrandmax();
                $adgroupReportConv->allConvValue = mt_rand(
                    self::MIN_ALL_CONV_VALUE,
                    self::MAX_ALL_CONV_VALUE
                ) / mt_getrandmax();
                $adgroupReportConv->convValue = mt_rand(
                    self::MIN_CONV_VALUE,
                    self::MAX_CONV_VALUE
                ) / mt_getrandmax();
                $adgroupReportConv->valuePerAllConv = mt_rand(
                    self::MIN_VALUE_PER_ALL_CONV,
                    self::MAX_VALUE_PER_ALL_CONV
                ) / mt_getrandmax();
                $adgroupReportConv->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
                $adgroupReportConv->objectiveOfConversionTracking = self::OBJECTIVE_OF_CONVERSION_TRACKING;
                $adgroupReportConv->conversionName = self::CONVERSION_NAME[
                    mt_rand(0, count(self::CONVERSION_NAME) - 1)
                ];
                $adgroupReportConv->accountid = $campaignReport->accountid;
                $adgroupReportCost->accountid = $campaignReport->accountid;

                $adgroupReportCost->saveOrFail();
                $adgroupReportConv->saveOrFail();
            }
        }
    }
}
