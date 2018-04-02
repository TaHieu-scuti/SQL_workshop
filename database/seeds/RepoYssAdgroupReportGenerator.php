<?php

use Illuminate\Database\Seeder;

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
    const MIN_AVERAGE_POSITION = 1;
    const MAX_AVERAGE_POSITION = 20;
    const MIN_IMPRESSION_SHARE = 100;
    const MAX_IMPRESSION_SHARE = 89489437437880;
    const MIN_EXACT_MATCH_IMPRESSION_SHARE = 100;
    const MAX_EXACT_MATCH_IMPRESSION_SHARE = 89489437437880;
    const MIN_BUDGET_LOST_IMPRESSION_SHARE = 100;
    const MAX_BUDGET_LOST_IMPRESSION_SHARE = 89489437437880;
    const MIN_QUALITY_LOST_IMPRESSION_SHARE = 100;
    const MAX_QUALITY_LOST_IMPRESSION_SHARE = 89489437437880;
    const TRACKING_URL = 'http://we.track.people/';
    const CUSTOM_PARAMETERS = 'Custom Parameters';
    const MIN_CONVERSIONS = 0;
    const MIN_CONV_VALUE = 100;
    const MAX_CONV_VALUE = 89489437437880;
    const MIN_COST_PER_CONV = 100;
    const MAX_COST_PER_CONV = 89489437437880;
    const MIN_VALUE_PER_CONV = 100;
    const MAX_VALUE_PER_CONV = 89489437437880;
    const MIN_MOBILE_BID_ADJ = 100;
    const MAX_MOBILE_BID_ADJ = 89489437437880;
    const MIN_DESKTOP_BID_ADJ = 100;
    const MAX_DESKTOP_BID_ADJ = 89489437437880;
    const MIN_TABLET_BID_ADJ = 100;
    const MAX_TABLET_BID_ADJ = 89489437437880;
    const MIN_COST_PER_ALL_CONV = 100;
    const MAX_COST_PER_ALL_CONV = 89489437437880;
    const MIN_VALUE_PER_ALL_CONV = 100;
    const MAX_VALUE_PER_ALL_CONV = 89489437437880;
    const MIN_ALL_CONV = 100;
    const MAX_ALL_CONV = 89489437437880;
    const MIN_ALL_CONV_VALUE = 100;
    const MAX_ALL_CONV_VALUE = 89489437437880;
    const NETWORKS = ['network1', 'network2', 'network3'];
    const DEVICES = ['DESKTOP', 'SMART_PHONE', 'NONE'];
    const CAMPAIGN_TYPE = [
        'Campaign Type 1', 'Campaign Type 2',
        'Campaign Type 3', 'Campaign Type 4'
    ];
    const CLICK_TYPE = [
        'Click Type 1', 'Click Type 2',
        'Click Type 3', 'Click Type 4'
    ];
    const OBJECTIVE_OF_CONVERSION_TRACKING = 'Objective of conversion tracking';
    const NUMBER_OF_CONVERSIONS = RepoYssCampaignReportGenerator::NUMBER_OF_CONV_POINTS;

    private function seedConv($adgroupReportCost)
    {
        for ($i = 0; $i < self::NUMBER_OF_CONVERSIONS; $i++) {
            $adgroupReportConv = new RepoYssAdgroupReportConv;
            $adgroupReportConv->exeDate = $adgroupReportCost->exeDate;
            $adgroupReportConv->startDate = $adgroupReportCost->startDate;
            $adgroupReportConv->endDate = $adgroupReportCost->endDate;
            $adgroupReportConv->account_id = $adgroupReportCost->account_id;
            $adgroupReportConv->campaign_id = $adgroupReportCost->campaign_id;
            $adgroupReportConv->campaignID = $adgroupReportCost->campaignID;
            $adgroupReportConv->adgroupID = $adgroupReportCost->adgroupID;
            $adgroupReportConv->campaignName = $adgroupReportCost->campaignName;
            $adgroupReportConv->adgroupName = $adgroupReportCost->adgroupName;
            $adgroupReportConv->adgroupDistributionSettings = $adgroupReportCost->adgroupDistributionSettings;
            $adgroupReportConv->adGroupBid = $adgroupReportCost->adGroupBid;
            $adgroupReportConv->trackingURL = self::TRACKING_URL;
            $adgroupReportConv->conversions = $adgroupReportCost->conversions / self::NUMBER_OF_CONVERSIONS;
            $adgroupReportConv->convValue = $adgroupReportConv->convValue;
            $adgroupReportConv->valuePerConv = $adgroupReportCost->valuePerConv;
            $adgroupReportConv->mobileBidAdj = $adgroupReportCost->mobileBidAdj;
            $adgroupReportConv->desktopBidAdj = $adgroupReportCost->desktopBidAdj;
            $adgroupReportConv->tabletBidAdj = $adgroupReportCost->tabletBidAdj;
            $adgroupReportConv->network = $adgroupReportCost->network;
            $adgroupReportConv->device = $adgroupReportCost->device;
            $adgroupReportConv->day = $adgroupReportCost->day;
            $adgroupReportConv->dayOfWeek = $adgroupReportCost->dayOfWeek;
            $adgroupReportConv->quarter = $adgroupReportCost->quarter;
            $adgroupReportConv->month = $adgroupReportCost->month;
            $adgroupReportConv->week = $adgroupReportCost->week;
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
            $adgroupReportConv->conversionName = 'YSS conversion '
                . $adgroupReportCost->account_id
                . $adgroupReportCost->campaign_id
                . $adgroupReportCost->accountid
                . $i;
            $adgroupReportConv->accountid = $adgroupReportCost->accountid;
            $adgroupReportConv->saveOrFail();
        }
    }
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
                $adgroupReportCost->exeDate = $campaignReport->exeDate;
                $adgroupReportCost->startDate = $campaignReport->startDate;
                $adgroupReportCost->endDate = $campaignReport->endDate;
                $adgroupReportCost->account_id = $campaignReport->account_id;
                $adgroupReportCost->campaign_id = $campaignReport->campaign_id;
                $adgroupReportCost->campaignID = $campaignReport->campaignID;
                if ($campaignReport->account_id === 'dbc087db3467fabd8d46cb04667f5eaa') {
                    $adgroupReportCost->adgroupID = (string) round($campaignReport->campaignID / 10)
                    . (string) $campaignReport->campaign_id
                    . (string) $campaignReport->accountid
                    . (string) $campaignReport->campaignID
                    . ($i + 1);
                } else {
                    $adgroupReportCost->adgroupID = (string) $campaignReport->account_id
                    . (string) $campaignReport->campaign_id
                    . (string) $campaignReport->accountid
                    . (string) $campaignReport->campaignID
                    . ($i + 1);
                }
                $adgroupReportCost->campaignName = $campaignReport->campaignName;
                $adgroupReportCost->adgroupName = 'YSS Adgroup Name ' . $adgroupReportCost->adgroupID;
                $adgroupReportCost->adgroupDistributionSettings = 'Adgroup Distribution setting';
                $adgroupReportCost->adGroupBid = mt_rand(
                    self::MIN_ADGROUP_BID,
                    self::MAX_ADGROUP_BID
                );

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

                if ($adgroupReportCost->impressions === 0) {
                    $adgroupReportCost->ctr = 0;
                } else {
                    $adgroupReportCost->ctr = ($adgroupReportCost->clicks / $adgroupReportCost->impressions) * 100;
                }

                if ($adgroupReportCost->clicks === 0) {
                    $adgroupReportCost->averageCpc = 0;
                } else {
                    $adgroupReportCost->averageCpc = $adgroupReportCost->cost / $adgroupReportCost->clicks;
                }

                $adgroupReportCost->averagePosition = mt_rand(
                    self::MIN_AVERAGE_POSITION * 100000,
                    self::MAX_AVERAGE_POSITION * 100000
                ) / 100000;

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
                $adgroupReportCost->customParameters = self::CUSTOM_PARAMETERS . ' ' . $i;

                $adgroupReportCost->conversions = mt_rand(
                    self::MIN_CONVERSIONS,
                    $adgroupReportCost->clicks
                );

                if ($adgroupReportCost->clicks === 0) {
                    $adgroupReportCost->convRate = 0;
                } else {
                    $adgroupReportCost->convRate = ($adgroupReportCost->conversions / $adgroupReportCost->clicks) * 100;
                }

                $adgroupReportCost->convValue = mt_rand(
                    self::MIN_CONV_VALUE,
                    self::MAX_CONV_VALUE
                ) / mt_getrandmax();
                $adgroupReportCost->costPerConv = mt_rand(
                    self::MIN_COST_PER_CONV,
                    self::MAX_COST_PER_CONV
                ) / mt_getrandmax();
                $adgroupReportCost->valuePerConv = mt_rand(
                    self::MIN_VALUE_PER_CONV,
                    self::MAX_VALUE_PER_CONV
                ) / mt_getrandmax();
                $adgroupReportCost->mobileBidAdj = mt_rand(
                    self::MIN_MOBILE_BID_ADJ,
                    self::MAX_MOBILE_BID_ADJ
                ) / mt_getrandmax();
                $adgroupReportCost->desktopBidAdj = mt_rand(
                    self::MIN_DESKTOP_BID_ADJ,
                    self::MAX_DESKTOP_BID_ADJ
                ) / mt_getrandmax();
                $adgroupReportCost->tabletBidAdj = mt_rand(
                    self::MIN_TABLET_BID_ADJ,
                    self::MAX_TABLET_BID_ADJ
                ) / mt_getrandmax();
                $adgroupReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                $adgroupReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                $adgroupReportCost->day = $campaignReport->day;
                $adgroupReportCost->dayOfWeek = $campaignReport->dayOfWeek;
                $adgroupReportCost->quarter = $campaignReport->quarter;
                $adgroupReportCost->month = $campaignReport->month;
                $adgroupReportCost->week = $campaignReport->week;
                $adgroupReportCost->hourofday = $campaignReport->hourofday;
                $adgroupReportCost->accountid = $campaignReport->accountid;

                $adgroupReportCost->saveOrFail();
                $this->seedConv($adgroupReportCost);
            }
        }
    }
}
