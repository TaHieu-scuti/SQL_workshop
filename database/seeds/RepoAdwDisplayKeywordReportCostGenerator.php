<?php

use Illuminate\Database\Seeder;
use App\Model\RepoAdwKeywordReportCost;
use App\Model\RepoAdwDisplayKeywordReportCost;

// @codingStandardsIgnoreLine
class RepoAdwDisplayKeywordReportCostGenerator extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adwKeywords = RepoAdwKeywordReportCost::where('day', '>=', '2017-07-01')->get();
        foreach ($adwKeywords as $adwKeyword) {
            $adwSearchQuery = new RepoAdwDisplayKeywordReportCost;
            $adwSearchQuery->exeDate = $adwKeyword->exeDate;
            $adwSearchQuery->startDate = $adwKeyword->startDate;
            $adwSearchQuery->endDate = $adwKeyword->endDate;
            $adwSearchQuery->account_id = $adwKeyword->account_id;
            $adwSearchQuery->campaign_id = $adwKeyword->campaign_id;
            $adwSearchQuery->currency = $adwKeyword->currency;
            $adwSearchQuery->account = $adwKeyword->account;
            $adwSearchQuery->timeZone = $adwKeyword->timezone;
            $adwSearchQuery->activeViewAvgCPM = $adwKeyword->activeViewAvgCPM;
            $adwSearchQuery->activeViewViewableCTR = $adwKeyword->activeViewViewableCTR;
            $adwSearchQuery->activeViewViewableImpressions = $adwKeyword->activeViewViewableImpressions;
            $adwSearchQuery->activeViewMeasurableImprImpr = $adwKeyword->activeViewMeasurableImprImpr;
            $adwSearchQuery->activeViewMeasurableCost = $adwKeyword->activeViewMeasurableCost;
            $adwSearchQuery->activeViewMeasurableImpr = $adwKeyword->activeViewMeasurableImpr;
            $adwSearchQuery->activeViewViewableImprMeasurableImpr = $adwKeyword->activeViewViewableImprMeasurableImpr;
            $adwSearchQuery->adGroupID = $adwKeyword->adGroupID;
            $adwSearchQuery->adGroup = $adwKeyword->adGroup;
            $adwSearchQuery->adGroupState = $adwKeyword->adGroupState;
            $adwSearchQuery->network = $adwKeyword->network;
            $adwSearchQuery->networkWithSearchPartners = $adwKeyword->networkWithSearchPartners;
            $adwSearchQuery->allConvRate = $adwKeyword->allConvRate;
            $adwSearchQuery->allConv = $adwKeyword->allConv;
            $adwSearchQuery->allConvValue = $adwKeyword->allConvValue;
            $adwSearchQuery->avgCost = $adwKeyword->avgCost;
            $adwSearchQuery->avgCPC = $adwKeyword->avgCPC;
            $adwSearchQuery->avgCPM = $adwKeyword->avgCPM;
            $adwSearchQuery->baseAdGroupID = $adwKeyword->baseAdGroupID;
            $adwSearchQuery->baseCampaignID = $adwKeyword->baseCampaignID;
            $adwSearchQuery->conversionOptimizerBidType = $adwKeyword->conversionOptimizerBidType;
            $adwSearchQuery->campaignID = $adwKeyword->campaignID;
            $adwSearchQuery->campaign = $adwKeyword->campaign;
            $adwSearchQuery->campaignState = $adwKeyword->campaignState;
            $adwSearchQuery->clicks = $adwKeyword->clicks;
            $adwSearchQuery->convRate = $adwKeyword->convRate;
            $adwSearchQuery->conversions = $adwKeyword->conversions;
            $adwSearchQuery->totalConvValue = $adwKeyword->totalConvValue;
            $adwSearchQuery->cost = $adwKeyword->cost;
            $adwSearchQuery->costAllConv = $adwKeyword->costAllConv;
            $adwSearchQuery->costConv = $adwKeyword->costConv;
            $adwSearchQuery->maxCPC = $adwKeyword->maxCPC;
            $adwSearchQuery->maxCPCSource = $adwKeyword->maxCPCSource;
            $adwSearchQuery->maxCPM = $adwKeyword->maxCPM;
            $adwSearchQuery->maxCPMSource = $adwKeyword->maxCPCSource;
            $adwSearchQuery->maxCPV = $adwKeyword->maxCPC;
            $adwSearchQuery->maxCPVSource = $adwKeyword->maxCPCSource;
            $adwSearchQuery->keyword = $adwKeyword->keyword;
            $adwSearchQuery->destinationURL = $adwKeyword->destinationURL;
            $adwSearchQuery->crossDeviceConv = $adwKeyword->crossDeviceConv;
            $adwSearchQuery->ctr = $adwKeyword->ctr;
            $adwSearchQuery->clientName = $adwKeyword->clientName;
            $adwSearchQuery->day = $adwKeyword->day;
            $adwSearchQuery->dayOfWeek = $adwKeyword->dayOfWeek;
            $adwSearchQuery->device = $adwKeyword->device;
            $adwSearchQuery->customerID = $adwKeyword->customerID;
            $adwSearchQuery->appFinalURL = $adwKeyword->appFinalURL;
            $adwSearchQuery->mobileFinalURL = $adwKeyword->mobileFinalURL;
            $adwSearchQuery->finalURL = $adwKeyword->finalURL;
            $adwSearchQuery->gmailForwards = $adwKeyword->gmailForwards;
            $adwSearchQuery->gmailSaves = $adwKeyword->gmailSaves;
            $adwSearchQuery->gmailClicksToWebsite = $adwKeyword->gmailClicksToWebsite;
            $adwSearchQuery->keywordID = $adwKeyword->keywordID;
            $adwSearchQuery->impressions = $adwKeyword->impressions;
            $adwSearchQuery->interactionRate = $adwKeyword->interactionRate;
            $adwSearchQuery->interactions = $adwKeyword->interactions;
            $adwSearchQuery->interactionTypes = $adwKeyword->interactionTypes;
            $adwSearchQuery->isNegative = $adwKeyword->isNegative;
            $adwSearchQuery->month = $adwKeyword->month;
            $adwSearchQuery->monthOfYear = $adwKeyword->monthOfYear;
            $adwSearchQuery->quarter = $adwKeyword->quarter;
            $adwSearchQuery->keywordState = $adwKeyword->keywordState;
            $adwSearchQuery->trackingTemplate = $adwKeyword->trackingTemplate;
            $adwSearchQuery->customParameter = $adwKeyword->customParameter;
            $adwSearchQuery->valueAllConv = $adwKeyword->valueAllConv;
            $adwSearchQuery->valueConv = $adwKeyword->valueConv;
            $adwSearchQuery->week = $adwKeyword->week;
            $adwSearchQuery->year = $adwKeyword->year;
            $adwSearchQuery->accountid = $adwKeyword->accountid;
            $adwSearchQuery->saveOrFail();
        }
    }
}
