<?php

use Illuminate\Database\Seeder;

use App\Model\RepoYssAdgroupReportCost;
use App\Model\RepoYssKeywordReportCost;
use App\Model\RepoYssKeywordReportConv;

class RepoYssKeywordReportGenerator extends Seeder
{
    const MIN_NUMBER_OF_KEYWORD = 1;
    const MAX_NUMBER_OF_KEYWORD = 2;
    const CUSTOM_URL = 'Custom URL ';
    const KEYWORD = 'Keyword ';
    const KEYWORD_DISTRIBUTION_SETTINGS = 'Keyword distribution settings';
    const KEYWORD_EDITORIAL_STATUS = 'Keyword editorial status';
    const NEGATIVE_KEY_WORDS = 'Negative key words';
    const KEYWORD_MATCH_TYPE = 'Keyword match type';
    const MIN_ADGROUP_BID = 1;
    const MAX_ADGROUP_BID = 1004;
    const MIN_BID = 1;
    const MAX_BID = 1004;
    const MIN_QUALITY_INDEX = 1;
    const MAX_QUALITY_INDEX = 10;
    const MIN_FIRST_PAGE_BID_ESTIMATE = 1;
    const MAX_FIRST_PAGE_BID_ESTIMATE = 20;
    const MIN_TOP_OF_PAGE_BID_ESTIMATE = 1;
    const MAX_TOP_OF_PAGE_BID_ESTIMATE = 100;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 0;
    const MAX_IMPRESSIONS = 1004;
    const MIN_CLICKS = 0;
    const MAX_CLICKS = 1004;
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
    const MIN_CONV_RATE = 100;
    const MAX_CONV_RATE = 89489437;
    const TRACKING_URL = 'http://we.track.people/';
    const LANDING_PAGE_URL = 'http://lading.page/';
    const LANDING_PAGE_URL_SMART_PHONE = 'http://lading.page.smartphone/';
    const CUSTOM_PARAMETERS = 'Custom Parameters';
    const MIN_CONVERSIONS = 1000000;
    const MAX_CONVERSIONS = 89489437437880;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 89489437437880;
    const MIN_COST_PER_CONV = 1000000;
    const MAX_COST_PER_CONV = 89489437437880;
    const MIN_ALL_CONV_RATE = 100;
    const MAX_ALL_CONV_RATE = 89489437;
    const MIN_VALUE_PER_CONV = 1000000;
    const MAX_VALUE_PER_CONV = 89489437437880;
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
        $adgroupReports = RepoYssAdgroupReportCost::all();
        foreach ($adgroupReports as $adgroupReport) {
            $ammountOfKeyword = rand(
                self::MIN_NUMBER_OF_KEYWORD,
                self::MAX_NUMBER_OF_KEYWORD
            );
            for ($i = 0; $i < $ammountOfKeyword + 1; $i++) {
                $keywordReportCost = new RepoYssKeywordReportCost;
                $keywordReportConv = new RepoYssKeywordReportConv;
                $keywordReportCost->exeDate = $adgroupReport->exeDate;
                $keywordReportConv->exeDate = $adgroupReport->exeDate;
                $keywordReportCost->startDate = $adgroupReport->startDate;
                $keywordReportConv->startDate = $adgroupReport->startDate;
                $keywordReportCost->endDate = $adgroupReport->endDate;
                $keywordReportConv->endDate = $adgroupReport->endDate;
                $keywordReportCost->account_id = $adgroupReport->account_id;
                $keywordReportConv->account_id = $adgroupReport->account_id;
                $keywordReportCost->campaign_id = $adgroupReport->campaign_id;
                $keywordReportConv->campaignID = $adgroupReport->campaignID;
                $keywordReportCost->adgroupID = $adgroupReport->adgroupID;
                $keywordReportConv->adgroupID = $adgroupReport->adgroupID;
                $keywordReportCost->keywordID = $i;
                $keywordReportConv->keywordID = $i;
                $keywordReportCost->campaignName = $adgroupReport->campaignName;
                $keywordReportConv->campaignName = $adgroupReport->campaignName;
                $keywordReportCost->adgroupName = $adgroupReport->adgroupName;
                $keywordReportConv->adgroupName = $adgroupReport->adgroupName;
                $keywordReportCost->customURL = self::CUSTOM_URL . $i;
                $keywordReportConv->customURL = self::CUSTOM_URL . $i;
                $keywordReportCost->keyword = self::KEYWORD . $i;
                $keywordReportConv->keyword = self::KEYWORD . $i;
                $keywordReportCost->keywordDistributionSettings = self::KEYWORD_DISTRIBUTION_SETTINGS;
                $keywordReportConv->keywordDistributionSettings = self::KEYWORD_DISTRIBUTION_SETTINGS;
                $keywordReportCost->kwEditorialStatus = self::KEYWORD_EDITORIAL_STATUS;
                $keywordReportConv->kwEditorialStatus = self::KEYWORD_EDITORIAL_STATUS;
                $keywordReportCost->adGroupBid = mt_rand(
                    self::MIN_ADGROUP_BID,
                    self::MAX_ADGROUP_BID
                );
                $keywordReportConv->adGroupBid = $keywordReportCost->adGroupBid;
                $keywordReportCost->bid = mt_rand(
                    self::MIN_BID,
                    self::MAX_BID
                );
                $keywordReportConv->bid = $keywordReportCost->bid;
                $keywordReportCost->negativeKeywords = self::NEGATIVE_KEY_WORDS;
                $keywordReportConv->negativeKeywords = self::NEGATIVE_KEY_WORDS;
                $keywordReportCost->qualityIndex = mt_rand(
                    self::MIN_QUALITY_INDEX,
                    self::MAX_QUALITY_INDEX
                );
                $keywordReportConv->qualityIndex = $keywordReportCost->qualityIndex;
                $keywordReportCost->firstPageBidEstimate = mt_rand(
                    self::MIN_FIRST_PAGE_BID_ESTIMATE,
                    self::MAX_FIRST_PAGE_BID_ESTIMATE
                );
                $keywordReportConv->firstPageBidEstimate = $keywordReportCost->firstPageBidEstimate;
                $keywordReportCost->keywordMatchType = self::KEYWORD_MATCH_TYPE;
                $keywordReportConv->keywordMatchType = self::KEYWORD_MATCH_TYPE;
                $keywordReportCost->cost = mt_rand(
                    self::MIN_COST,
                    self::MAX_COST
                );
                $keywordReportCost->impressions = mt_rand(
                    self::MIN_IMPRESSIONS,
                    self::MAX_IMPRESSIONS
                );
                $keywordReportCost->clicks = mt_rand(
                    self::MIN_CLICKS,
                    self::MAX_CLICKS
                );
                $keywordReportCost->ctr = mt_rand(
                    self::MIN_CTR,
                    self::MAX_CTR
                ) / mt_getrandmax();
                $keywordReportCost->averageCpc = mt_rand(
                    self::MIN_AVERAGE_CPC,
                    self::MAX_AVERAGE_CPC
                ) / mt_getrandmax();
                $keywordReportCost->averagePosition = mt_rand(
                    self::MIN_AVERAGE_POSITION,
                    self::MAX_AVERAGE_POSITION
                ) / mt_getrandmax();
                $keywordReportCost->impressionShare = mt_rand(
                    self::MIN_IMPRESSION_SHARE,
                    self::MAX_IMPRESSION_SHARE
                ) / mt_getrandmax();
                $keywordReportCost->exactMatchImpressionShare = mt_rand(
                    self::MIN_EXACT_MATCH_IMPRESSION_SHARE,
                    self::MAX_EXACT_MATCH_IMPRESSION_SHARE
                ) / mt_getrandmax();
                $keywordReportCost->qualityLostImpressionShare = mt_rand(
                    self::MIN_BUDGET_LOST_IMPRESSION_SHARE,
                    self::MAX_BUDGET_LOST_IMPRESSION_SHARE
                ) / mt_getrandmax();
                $keywordReportCost->topOfPageBidEstimate = mt_rand(
                    self::MIN_TOP_OF_PAGE_BID_ESTIMATE,
                    self::MAX_TOP_OF_PAGE_BID_ESTIMATE
                );
                $keywordReportConv->topOfPageBidEstimate = $keywordReportCost->topOfPageBidEstimate;
                $keywordReportCost->trackingURL = self::TRACKING_URL;
                $keywordReportConv->trackingURL = self::TRACKING_URL;
                $keywordReportCost->customParameters = self::CUSTOM_PARAMETERS;
                $keywordReportConv->customParameters = self::CUSTOM_PARAMETERS;
                $keywordReportCost->landingPageURL = self::LANDING_PAGE_URL;
                $keywordReportConv->landingPageURL = self::LANDING_PAGE_URL;
                $keywordReportCost->landingPageURLSmartphone = self::LANDING_PAGE_URL_SMART_PHONE;
                $keywordReportConv->landingPageURLSmartphone = self::LANDING_PAGE_URL_SMART_PHONE;
                $keywordReportCost->conversions = mt_rand(
                    self::MIN_CONVERSIONS,
                    self::MAX_CONVERSIONS
                ) / mt_getrandmax();
                $keywordReportConv->conversions = $keywordReportCost->conversions;
                $keywordReportCost->convRate = mt_rand(
                    self::MIN_CONV_RATE,
                    self::MAX_CONV_RATE
                ) / mt_getrandmax();
                $keywordReportCost->convValue = mt_rand(
                    self::MIN_CONV_VALUE,
                    self::MAX_CONV_VALUE
                ) / mt_getrandmax();
                $keywordReportConv->convValue = $keywordReportCost->convValue;
                $keywordReportCost->costPerConv = mt_rand(
                    self::MIN_COST_PER_CONV,
                    self::MAX_COST_PER_CONV
                ) / mt_getrandmax();

                $keywordReportCost->valuePerConv = mt_rand(
                    self::MIN_VALUE_PER_CONV,
                    self::MAX_VALUE_PER_CONV
                ) / mt_getrandmax();
                $keywordReportConv->valuePerConv = $keywordReportCost->valuePerConv;
                $keywordReportCost->allConv = mt_rand(
                    self::MIN_ALL_CONV,
                    self::MAX_ALL_CONV
                ) / mt_getrandmax();
                $keywordReportConv->allConv = $keywordReportCost->allConv;
                $keywordReportCost->allConvRate = mt_rand(
                    self::MIN_ALL_CONV_RATE,
                    self::MAX_ALL_CONV_RATE
                ) / mt_getrandmax();
                $keywordReportCost->allConvValue = mt_rand(
                    self::MIN_ALL_CONV_VALUE,
                    self::MAX_ALL_CONV_VALUE
                ) / mt_getrandmax();
                $keywordReportConv->allConvValue = $keywordReportCost->allConvValue;
                $keywordReportCost->costPerAllConv = mt_rand(
                    self::MIN_COST_PER_ALL_CONV,
                    self::MAX_COST_PER_ALL_CONV
                ) / mt_getrandmax();
                $keywordReportCost->valuePerAllConv = mt_rand(
                    self::MIN_VALUE_PER_ALL_CONV,
                    self::MAX_VALUE_PER_ALL_CONV
                ) / mt_getrandmax();
                $keywordReportConv->valuePerAllConv = $keywordReportCost->valuePerAllConv;
                $keywordReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                $keywordReportConv->network = $keywordReportCost->network;
                $keywordReportConv->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
                $keywordReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                $keywordReportConv->device = $keywordReportCost->device;
                $keywordReportCost->day = $adgroupReport->day;
                $keywordReportConv->day = $adgroupReport->day;
                $keywordReportCost->dayOfWeek = $adgroupReport->dayOfWeek;
                $keywordReportConv->dayOfWeek = $adgroupReport->dayOfWeek;
                $keywordReportCost->quarter = $adgroupReport->quarter;
                $keywordReportConv->quarter = $adgroupReport->quarter;
                $keywordReportCost->month = $adgroupReport->month;
                $keywordReportConv->month = $adgroupReport->month;
                $keywordReportCost->week = $adgroupReport->week;
                $keywordReportConv->week = $adgroupReport->week;
                $keywordReportConv->objectiveOfConversionTracking = self::OBJECTIVE_OF_CONVERSION_TRACKING;
                $keywordReportConv->conversionName = self::CONVERSION_NAME[mt_rand(0, count(self::CONVERSION_NAME) - 1)];
                $keywordReportCost->saveOrFail();
                $keywordReportConv->saveOrFail();
            }
        }
    }
}
