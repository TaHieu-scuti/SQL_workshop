<?php

use Illuminate\Database\Seeder;
use App\Model\RepoAdwKeywordReportCost;
use App\Model\RepoAdwSearchQueryPerformanceReport;

class RepoAdwSearchQueryPerformanceReportGenerator extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adwKeywords = RepoAdwKeywordReportCost::where('day', '>=', '2017-07-01')->get();
        foreach ($adwKeywords as $keyword) {
            $adwSearchQuery = new RepoAdwSearchQueryPerformanceReport;
            $adwSearchQuery->exeDate = $keyword->exeDate;
            $adwSearchQuery->startDate = $keyword->startDate;
            $adwSearchQuery->endDate = $keyword->endDate;
            $adwSearchQuery->account_id = $keyword->account_id;
            $adwSearchQuery->campaign_id = $keyword->campaign_id;
            $adwSearchQuery->currency = $keyword->currency;
            $adwSearchQuery->account = $keyword->account;
            $adwSearchQuery->timeZone = $keyword->timeZone;
            $adwSearchQuery->adType = $keyword->adType;
            $adwSearchQuery->adGroupID = $keyword->adGroupID;
            $adwSearchQuery->adGroup = $keyword->adGroup;
            $adwSearchQuery->adGroupState = $keyword->adGroupState;
            $adwSearchQuery->network = $keyword->network;
            $adwSearchQuery->networkWithSearchPartners = $keyword->networkWithSearchPartners;
            $adwSearchQuery->allConvRate = $keyword->allConvRate;
            $adwSearchQuery->allConv = $keyword->allConv;
            $adwSearchQuery->allConvValue = $keyword->allConvValue;
            $adwSearchQuery->avgCost = $keyword->avgCost;
            $adwSearchQuery->avgCPC = $keyword->avgCPC;
            $adwSearchQuery->avgCPE = $keyword->avgCPE;
            $adwSearchQuery->avgCPM = $keyword->avgCPM;
            $adwSearchQuery->avgCPV = $keyword->avgCPV;
            $adwSearchQuery->avgPosition = $keyword->avgPosition;
            $adwSearchQuery->campaignID = $keyword->campaignID;
            $adwSearchQuery->campaign = $keyword->campaign;
            $adwSearchQuery->campaignState = $keyword->campaignState;
            $adwSearchQuery->clicks = $keyword->clicks;
            $adwSearchQuery->convRate = $keyword->convRate;
            $adwSearchQuery->conversions = $keyword->conversions;
            $adwSearchQuery->totalConvValue = $keyword->totalConvValue;
            $adwSearchQuery->cost = $keyword->cost;
            $adwSearchQuery->costAllConv = $keyword->costAllConv;
            $adwSearchQuery->costConv = $keyword->costConv;
            $adwSearchQuery->adId = $keyword->adId;
            $adwSearchQuery->ctr = $keyword->ctr;
            $adwSearchQuery->clientName = $keyword->clientName;
            $adwSearchQuery->day = $keyword->day;
            $adwSearchQuery->dayOfWeek = $keyword->dayOfWeek;
            $adwSearchQuery->destinationURL = $keyword->destinationURL;
            $adwSearchQuery->device = $keyword->device;
            $adwSearchQuery->customerID = $keyword->customerID;
            $adwSearchQuery->finalURL = $keyword->finalURL;
            $adwSearchQuery->impressions = $keyword->impressions;
            $adwSearchQuery->keywordID = $keyword->keywordID;
            $adwSearchQuery->keyword = $keyword->keyword;
            $adwSearchQuery->month = $keyword->month;
            $adwSearchQuery->monthOfYear = $keyword->monthOfYear;
            $adwSearchQuery->quarter = $keyword->quarter;
            $adwSearchQuery->matchType = $keyword->matchType;
            $adwSearchQuery->trackingTemplate = $keyword->trackingTemplate;
            $adwSearchQuery->valueAllConv = $keyword->valueAllConv;
            $adwSearchQuery->valueConv = $keyword->valueConv;
            $adwSearchQuery->week = $keyword->week;
            $adwSearchQuery->week = $keyword->year;
            $adwSearchQuery->saveOrFail();
        }
    }
}
