<?php

use Illuminate\Database\Seeder;
use App\Model\RepoYssAdgroupReportConv;
use App\Model\RepoYssAdgroupReportCost;
use App\Model\RepoYssAdReportCost;
use App\Model\RepoYssAdReportConv;

// @codingStandardsIgnoreLine
class RepoYssAdReportGenerator extends Seeder
{
    const MIN_NUMBER_OF_AD = 1;
    const MAX_NUMBER_OF_AD = 2;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 0;
    const MIN_CLICKS = 0;
    const MIN_CTR = 1000;
    const MAX_CTR = 1032456345;
    const MIN_CONV_RATE = 1000;
    const MAX_CONV_RATE = 1437437880;
    const MIN_AVERAGE_CPC = 1000;
    const MAX_AVERAGE_CPC = 1437437880;
    const MIN_AVERAGE_POSITION = 1000;
    const MAX_AVERAGE_POSITION = 1437437880;
    const TRACKING_URL = 'http://we.track.people/';
    const DISPLAY_URL = 'http://we.track.displayURL/';
    const DESTINATION_URL = 'http://we.track.destinationURL/';
    const LOADING_PAGE_URL = 'http://we.track.landingPageURL/';
    const LOADING_PAGE_URL_SMART_PHONE = 'http://we.track.landingPageURLSmartphone/';
    const MIN_CONVERSIONS = 1000;
    const MAX_CONVERSIONS = 1437437880;
    const MIN_CONV_VALUE = 1000;
    const MAX_CONV_VALUE = 1437437880;
    const MIN_COST_PER_CONV = 1000;
    const MAX_COST_PER_CONV = 1437437880;
    const MIN_VALUE_PER_CONV = 1000;
    const MAX_VALUE_PER_CONV = 1437437880;
    const MIN_COST_PER_ALL_CONV = 1000;
    const MAX_COST_PER_ALL_CONV = 1437437880;
    const MIN_VALUE_PER_ALL_CONV = 1000;
    const MAX_VALUE_PER_ALL_CONV = 1437437880;
    const MIN_ALL_CONV = 1000;
    const MAX_ALL_CONV = 1437437880;
    const MIN_ALL_CONV_VALUE = 1000;
    const MAX_ALL_CONV_VALUE = 1437437880;
    const MIN_ALL_CONV_RATE = 1000;
    const MAX_ALL_CONV_RATE = 1437437880;
    const AD_TYPE = [
        'Ad Report Type 1', 'Ad Report Type 2',
        'Ad Report Type 3', 'Ad Report Type 4'
    ];
    const CLICK_TYPE = [
        'Click Type 1', 'Click Type 2',
        'Click Type 3', 'Click Type 4'
    ];
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
        $adGroupReports = RepoYssAdgroupReportCost::all();
        foreach ($adGroupReports as $key => $adGroupReport) {
            $ammountOfAdReport = rand(
                self::MIN_NUMBER_OF_AD,
                self::MAX_NUMBER_OF_AD
            );
            for ($i=0; $i < $ammountOfAdReport; $i++) {
                $adReportCost = new RepoYssAdReportCost;
                $adReportConv = new RepoYssAdReportConv;
                $adReportCost->exeDate = $adGroupReport->exeDate;
                $adReportConv->exeDate = $adGroupReport->exeDate;
                $adReportCost->startDate = $adGroupReport->startDate;
                $adReportConv->startDate = $adGroupReport->startDate;
                $adReportCost->endDate = $adGroupReport->endDate;
                $adReportConv->endDate = $adGroupReport->endDate;
                $adReportCost->account_id = $adGroupReport->account_id;
                $adReportConv->account_id = $adGroupReport->account_id;
                $adReportCost->campaign_id = $adGroupReport->campaign_id;
                $adReportConv->campaign_id = $adGroupReport->campaign_id;
                $adReportCost->campaignID = $adGroupReport->campaignID;
                $adReportConv->campaignID = $adGroupReport->campaignID;
                $adReportCost->adgroupID = $adGroupReport->adgroupID;
                $adReportConv->adgroupID = $adGroupReport->adgroupID;
                $adReportCost->adID = $i;
                $adReportConv->adID = $i;
                $adReportCost->campaignName = $adGroupReport->campaignName;
                $adReportConv->campaignName = $adGroupReport->campaignName;
                $adReportCost->adgroupName = $adGroupReport->adgroupName;
                $adReportConv->adgroupName = $adGroupReport->adgroupName;
                $adReportCost->adName = 'Ad Name'. $i;
                $adReportConv->adName = 'Ad Name'. $i;
                $adReportCost->title = str_random(10);
                $adReportConv->title = $adReportCost->title;
                $adReportCost->description1 = str_random(10);
                $adReportConv->description1 = $adReportCost->description1;
                $adReportCost->displayURL = self::DISPLAY_URL;
                $adReportConv->displayURL = $adReportCost->displayURL;
                $adReportCost->destinationURL = self::DESTINATION_URL;
                $adReportConv->destinationURL = $adReportCost->destinationURL;
                $adReportCost->adType = self::AD_TYPE[mt_rand(0, count(self::AD_TYPE) -1)];
                $adReportConv->adType = $adReportCost->adType;
                $adReportCost->adDistributionSettings = str_random(10);
                $adReportConv->adDistributionSettings = $adReportCost->adDistributionSettings;
                $adReportCost->adEditorialStatus = str_random(10);
                $adReportConv->adEditorialStatus = $adReportCost->adEditorialStatus;
                $adReportCost->cost = mt_rand(
                    self::MIN_COST,
                    self::MAX_COST
                );
                $adReportConv->description2 = str_random(10);
                $adReportCost->impressions = mt_rand(
                    self::MIN_IMPRESSIONS,
                    $adGroupReport->impressions
                );
                $adReportConv->focusDevice = str_random(10);
                $adReportCost->clicks = mt_rand(
                    self::MIN_CLICKS,
                    $adReportCost->impressions
                );
                $adReportConv->trackingURL = self::TRACKING_URL;
                $adReportCost->ctr = mt_rand(
                    self::MIN_CTR,
                    self::MAX_CTR
                ) / mt_getrandmax();
                $adReportConv->customParameters = str_random(10);
                $adReportCost->averageCpc = mt_rand(
                    self::MIN_AVERAGE_CPC,
                    self::MAX_AVERAGE_CPC
                ) / mt_getrandmax();
                $adReportConv->landingPageURL = self::LOADING_PAGE_URL;
                $adReportCost->averagePosition = mt_rand(
                    self::MIN_AVERAGE_POSITION,
                    self::MAX_AVERAGE_POSITION
                ) / mt_getrandmax();

                $adReportConv->landingPageURLSmartphone = self::LOADING_PAGE_URL_SMART_PHONE;
                $adReportCost->description2 = $adReportConv->description2;
                $adReportConv->adTrackingID = $i;
                $adReportCost->focusDevice = $adReportConv->focusDevice;
                $adReportConv->conversions =  mt_rand(
                    self::MIN_CONVERSIONS,
                    self::MAX_CONVERSIONS
                ) / mt_getrandmax();
                $adReportCost->trackingURL = $adReportConv->trackingURL;
                $adReportConv->convValue = mt_rand(
                    self::MIN_CONV_VALUE,
                    self::MAX_CONV_VALUE
                ) / mt_getrandmax();
                $adReportCost->customParameters = $adReportConv->customParameters;
                $adReportConv->valuePerConv = mt_rand(
                    self::MIN_VALUE_PER_CONV,
                    self::MAX_VALUE_PER_CONV
                ) / mt_getrandmax();
                $adReportCost->landingPageURL = $adReportConv->landingPageURL;
                $adReportConv->allConv = mt_rand(
                    self::MIN_ALL_CONV,
                    self::MAX_ALL_CONV
                ) / mt_getrandmax();
                $adReportCost->landingPageURLSmartphone = $adReportConv->landingPageURLSmartphone;
                $adReportConv->allConvValue = mt_rand(
                    self::MIN_ALL_CONV_VALUE,
                    self::MAX_ALL_CONV_VALUE
                ) / mt_getrandmax();
                $adReportCost->adTrackingID = $adReportConv->adTrackingID;
                $adReportConv->valuePerAllConv = mt_rand(
                    self::MIN_VALUE_PER_ALL_CONV,
                    self::MAX_VALUE_PER_ALL_CONV
                ) / mt_getrandmax();
                $adReportCost->conversions = $adReportConv->conversions;
                $adReportConv->network = $adGroupReport->network;
                $adReportCost->convRate = mt_rand(
                    self::MIN_CONV_RATE,
                    self::MAX_CONV_RATE
                ) / mt_getrandmax();
                $adReportConv->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
                $adReportCost->convValue = $adReportConv->convValue;
                $adReportConv->device = $adGroupReport->device;
                $adReportCost->costPerConv = mt_rand(
                    self::MIN_COST_PER_CONV,
                    self::MAX_COST_PER_CONV
                ) / mt_getrandmax();
                $adReportConv->day = $adGroupReport->day;
                $adReportCost->valuePerConv = $adReportConv->valuePerConv;
                $adReportConv->dayOfWeek = $adGroupReport->dayOfWeek;
                $adReportCost->allConv = $adReportConv->allConv;
                $adReportConv->quarter = $adGroupReport->quarter;
                $adReportCost->allConvRate = mt_rand(
                    self::MIN_ALL_CONV_RATE,
                    self::MAX_ALL_CONV_RATE
                ) / mt_getrandmax();
                $adReportConv->month = $adGroupReport->month;
                $adReportCost->allConvValue = $adReportConv->allConvValue;
                $adReportConv->week = $adGroupReport->week;
                $adReportCost->costPerAllConv = mt_rand(
                    self::MIN_COST_PER_ALL_CONV,
                    self::MAX_COST_PER_ALL_CONV
                ) / mt_getrandmax();
                $adReportConv->objectiveOfConversionTracking = str_random(10);
                $adReportCost->valuePerAllConv = $adReportConv->valuePerAllConv;
                $adReportConv->conversionName = self::CONVERSION_NAME[
                    mt_rand(0, count(self::CONVERSION_NAME) - 1)
                ];
                $adReportCost->network = $adGroupReport->network;
                $adReportConv->adKeywordID = $i;
                $adReportCost->clickType = $adReportConv->clickType;
                $adReportConv->title1 = str_random(10);
                $adReportCost->device = $adGroupReport->device;
                $adReportConv->title2 = str_random(10);
                $adReportCost->day = $adGroupReport->day;
                $adReportConv->description = str_random(10);
                $adReportCost->dayOfWeek = $adGroupReport->dayOfWeek;
                $adReportConv->directory1 = str_random(10);
                $adReportCost->quarter = $adGroupReport->quarter;
                $adReportConv->directory2 = str_random(10);
                $adReportCost->month = $adGroupReport->month;
                $adReportCost->week = $adGroupReport->week;
                $adReportCost->adKeywordID = $adReportConv->adKeywordID;
                $adReportCost->title1 = $adReportConv->title1;
                $adReportCost->title2 = $adReportConv->title2;
                $adReportCost->description = $adReportConv->description;
                $adReportCost->directory1 = $adReportConv->directory1;
                $adReportCost->directory2 = $adReportConv->directory2;
                $adReportConv->accountid = $adGroupReport->accountid;
                $adReportCost->accountid = $adGroupReport->accountid;

                $adReportCost->saveOrFail();
                $adReportConv->saveOrFail();
            }
        }
    }
}
