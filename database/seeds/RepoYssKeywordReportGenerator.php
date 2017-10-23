<?php

use Illuminate\Database\Seeder;

use App\Model\RepoYssAdgroupReportCost;
use App\Model\RepoYssAdgroupReportConv;

class RepoYssKeywordReportGenerator extends Seeder
{
    const MIN_NUMBER_OF_KEYWORD = 1;
    const MAX_NUMBER_OF_KEYWORD = 2;
    const CUSTOM_URL = 'Custom URL ';
    const KEYWORD = 'Keyword ';
    const KEYWORD_DISTRIBUTION_SETTINGS = 'Keyword distribution settings';
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
            for($i = 0; $i < $ammountOfKeyword + 1; $i++) {
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
                $keywordReportCost->adGroupBid = ;
                $keywordReportConv->adGroupBid = $keywordReportCost->adGroupBid;
                $keywordReportCost->bid = ;
                $keywordReportConv->bid = $keywordReportCost->bid;
                $keywordReportCost->negativeKeywords = ;
                $keywordReportConv->negativeKeywords = ;
                $keywordReportCost->qualityIndex = ;
                $keywordReportConv->qualityIndex = ;
                $keywordReportCost->firstPageBidEstimate = ;
                $keywordReportConv->firstPageBidEstimate = ;
                $keywordReportCost->keywordMatchType = ;
                $keywordReportConv->keywordMatchType = ;
                $keywordReportCost->cost = ;
                $keywordReportCost->impressions = ;
                $keywordReportCost->clicks = ;
                $keywordReportCost->ctr = ;
                $keywordReportCost->averageCpc = ;
                $keywordReportCost->averagePosition = ;
                $keywordReportCost->impressionShare = ;
                $keywordReportCost->exactMatchImpressionShare = ;
                $keywordReportCost->qualityLostImpressionShare = ;
                $keywordReportCost->topOfPageBidEstimate = ;
                $keywordReportConv->topOfPageBidEstimate = ;
                $keywordReportCost->trackingURL = ;
                $keywordReportConv->trackingURL = ;
                $keywordReportCost->customParameters = ;
                $keywordReportConv->customParameters = ;
                $keywordReportCost->landingPageURL = ;
                $keywordReportConv->landingPageURL = ;
                $keywordReportCost->landingPageURLSmartphone = ;
                $keywordReportConv->landingPageURLSmartphone = ;
                $keywordReportCost->conversions = ;
                $keywordReportConv->conversions = ;
                $keywordReportCost->convRate = ;
                $keywordReportCost->convValue = ;
                $keywordReportConv->convValue = $keywordReportCost->convValue;
                $keywordReportCost->costPerConv = ;
                $keywordReportCost->valuePerConv = ;
                $keywordReportConv->valuePerConv = $keywordReportCost->valuePerConv;
                $keywordReportCost->allConv = ;
                $keywordReportConv->allConv = $keywordReportCost->allConv;
                $keywordReportCost->allConvRate = ;
                $keywordReportCost->allConvValue = ;
                $keywordReportConv->allConvValue = $keywordReportCost->allConvValue;
                $keywordReportCost->costPerAllConv = ;
                $keywordReportCost->valuePerAllConv = ;
                $keywordReportConv->valuePerAllConv = $keywordReportCost->valuePerAllConv;
                $keywordReportCost->network = ;
                $keywordReportConv->network = ;
                $keywordReportConv->clickType = ;
                $keywordReportCost->device = ;
                $keywordReportConv->device = ;
                $keywordReportCost->day = ;
                $keywordReportConv->day = ;
                $keywordReportCost->dayOfWeek = ;
                $keywordReportConv->dayOfWeek = ;
                $keywordReportCost->quarter = ;
                $keywordReportConv->quarter = ;
                $keywordReportCost->month = ;
                $keywordReportConv->month = ;
                $keywordReportCost->week = ;
                $keywordReportConv->week = ;
                $keywordReportConv->objectiveOfConversionTracking = ;
                $keywordReportConv->conversionName = ;
            }
        }
    }
}
