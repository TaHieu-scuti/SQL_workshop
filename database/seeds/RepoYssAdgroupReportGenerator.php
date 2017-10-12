<?php

use Illuminate\Database\Seeder;

use App\Model\RepoYssAccountReportCost;
use App\Model\RepoYssCampaignReportConv;
use App\Model\RepoYssCampaignReportCost;

// @codingStandardsIgnoreLine
class RepoYssAdgroupReportGenerator extends Seeder
{
    const MIN_NUMBER_OF_ADGROUP = 1;
    const MAX_NUMBER_OF_ADGROUP = 3;
    const MIN_DAILY_SPENDING_LIMIT = 1;
    const MAX_DAILY_SPENDING_LIMIT = 1004;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 0;
    const MAX_IMPRESSIONS = 4096;
    const MIN_CLICKS = 0;
    const MAX_CLICKS = 9001;
    const MIN_CTR = 1000000;
    const MAX_CTR = 7344032456345;
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
            $ammountOfAdgroup = random(
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
                $adgroupReportCost->adgroupName = 'Adgroup Name ' . $i;
                $adgroupReportConv->adgroupName = 'Adgroup Name ' . $i;
                $adgroupReportCost->adgroupDistributionSettings = 'Adgroup Distribution setting';
                $adgroupReportConv->adgroupDistributionSettings = $adgroupReportCost->adgroupDistributionSettings;
                $adgroupReportCost->adGroupBid = ;
                $adgroupReportConvadgroupReportConv->adGroupBid = ;
                $adgroupReportCost->cost = ;
                $adgroupReportCost->impressions = ;
                $adgroupReportCost->click = ;
                $adgroupReportCost->ctr = ;
                $adgroupReportCost->averageCpc = ;
                $adgroupReportCost->averagePosition = ;
                $adgroupReportCost->impressionShare = ;
                $adgroupReportCost->exactMatchImpressionShare = ;
                $adgroupReportCost->qualityLostImpressionShare = ;
                $adgroupReportCost->trackingURL = ;
                $adgroupReportConv->trackingURL = ;
                $adgroupReportCost->customParameters = ;
                $adgroupReportCost->conversions = ;
                $adgroupReportConv->conversions = ;
                $adgroupReportCost->convRate = ;
                $adgroupReportCost->convValue = ;
                $adgroupReportConv->convValue = ;
                $adgroupReportCost->costPerConv = ;
                $adgroupReportCost->valuePerConv = ;
                $adgroupReportConv->valuePerConv = ;
                $adgroupReportCost->mobileBidAdj = ;
                $adgroupReportConv->mobileBidAdj = ;
                $adgroupReportCost->desktopBidAdj = ;
                $adgroupReportConv->desktopBidAdj = ;
                $adgroupReportCost->tabletBidAdj = ;
                $adgroupReportConv->tabletBidAdj = ;
                $adgroupReportCost->network = ;
                $adgroupReportConv->network = ;
                $adgroupReportCost->device = ;
                $adgroupReportConv->device = ;
                $adgroupReportCost->day = ;
                $adgroupReportConv->day = ;
                $adgroupReportCost->dayOfWeek = ;
                $adgroupReportConv->dayOfWeek = ;
                $adgroupReportCost->quater = ;
                $adgroupReportConv->quater = ;
                $adgroupReportCost->month = ;
                $adgroupReportConv->month = ;
                $adgroupReportCost->week = ;
                $adgroupReportConv->week = ;
                $adgroupReportCost->hourofday = ;
                $adgroupReportConv->customParameters = ;
                $adgroupReportConv->allConv = ;
                $adgroupReportConv->allConvValue = ;
                $adgroupReportConv->valuePerAllConv = ;
                $adgroupReportConv->clickType = ;
                $adgroupReportConv->objectOfConversionTracking = ;
                $adgroupReportConv->conversionName = self::CONVERSION_NAME[mt_rand(0, count(self::NETWORKS) - 1)];
            }
        }
    }
}
