<?php

use Illuminate\Database\Seeder;

use App\Model\RepoAdwKeywordReportConv;
use App\Model\RepoAdwKeywordReportCost;

// @codingStandardsIgnoreLine
class RepoAdwKeywordReportConvGenerator extends Seeder
{
    const NUMBER_OF_CONVERSION_POINTS = 3;
    const CONVERSION_NAME = 'Conversion Name ';
    const CONVERSION_CATEGORY = [
        'Conversion category 1',
        'Conversion category 2',
        'Conversion category 3',
        'Conversion category 4'
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keywordCostReports = RepoAdwKeywordReportCost::all();
        foreach ($keywordCostReports as $keywordCostReport) {
            for ($i = 0; $i < self::NUMBER_OF_CONVERSION_POINTS; $i++) {
                $keywordConvReport = new RepoAdwKeywordReportConv;

                $keywordConvReport->exeDate = $keywordCostReport->exeDate;
                $keywordConvReport->startDate = $keywordCostReport->startDate;
                $keywordConvReport->endDate = $keywordCostReport->endDate;
                $keywordConvReport->account_id = $keywordCostReport->account_id;
                $keywordConvReport->campaign_id = $keywordCostReport->campaign_id;
                $keywordConvReport->currency = $keywordCostReport->currency;
                $keywordConvReport->account = $keywordCostReport->account;
                $keywordConvReport->timeZone = $keywordCostReport->timeZone;
                $keywordConvReport->adGroupID = $keywordCostReport->adGroupID;
                $keywordConvReport->adGroup = $keywordCostReport->adGroup;
                $keywordConvReport->adGroupState = $keywordCostReport->adGroupState;
                $keywordConvReport->network = $keywordCostReport->network;
                $keywordConvReport->networkWithSearchPartners = $keywordCostReport->networkWithSearchPartners;
                $keywordConvReport->allConvRate = $keywordCostReport->allConvRate;
                $keywordConvReport->allConv = $keywordCostReport->allConv;
                $keywordConvReport->allConvValue = $keywordCostReport->allConvValue;
                $keywordConvReport->approvalStatus = $keywordCostReport->approvalStatus;
                $keywordConvReport->baseAdGroupID = $keywordCostReport->baseAdGroupID;
                $keywordConvReport->baseCampaignID = $keywordCostReport->baseCampaignID;
                $keywordConvReport->bidStrategyID = $keywordCostReport->bidStrategyID;
                $keywordConvReport->bidStrategyName = $keywordCostReport->bidStrategyName;
                $keywordConvReport->biddingStrategySource = $keywordCostReport->biddingStrategySource;
                $keywordConvReport->bidStrategyType = $keywordCostReport->bidStrategyType;
                $keywordConvReport->conversionOptimizerBidType = $keywordCostReport->conversionOptimizerBidType;
                $keywordConvReport->campaignID = $keywordCostReport->campaignID;
                $keywordConvReport->campaign = $keywordCostReport->campaign;
                $keywordConvReport->campaignState = $keywordCostReport->campaignState;
                $keywordConvReport->conversionCategory =
                     self::CONVERSION_CATEGORY[rand(0, count(self::CONVERSION_CATEGORY) -1)];
                $keywordConvReport->convRate = $keywordCostReport->convRate;
                $keywordConvReport->conversions = $keywordCostReport->conversions / self::NUMBER_OF_CONVERSION_POINTS;
                $keywordConvReport->conversionTrackerId = mt_rand(0, count(self::CONVERSION_NAME) -1);
                $keywordConvReport->conversionName = self::CONVERSION_NAME . ($i + 1);
                $keywordConvReport->totalConvValue = $keywordCostReport->totalConvValue;
                $keywordConvReport->costAllConv = $keywordCostReport->costAllConv;
                $keywordConvReport->costConv = $keywordCostReport->costConv;
                $keywordConvReport->costConvCurrentModel = $keywordCostReport->costConvCurrentModel;
                $keywordConvReport->maxCPC = $keywordCostReport->maxCPC;
                $keywordConvReport->maxCPCSource = $keywordCostReport->maxCPCSource;
                $keywordConvReport->maxCPM = $keywordCostReport->maxCPM;
                $keywordConvReport->adRelevance = $keywordCostReport->adRelevance;
                $keywordConvReport->keyword = $keywordCostReport->keyword;
                $keywordConvReport->destinationURL = $keywordCostReport->destinationURL;
                $keywordConvReport->crossDeviceConv = $keywordCostReport->crossDeviceConv;
                $keywordConvReport->conversionsCurrentModel = $keywordCostReport->conversionsCurrentModel;
                $keywordConvReport->convValueCurrentModel = $keywordCostReport->convValueCurrentModel;
                $keywordConvReport->clientName = $keywordCostReport->clientName;
                $keywordConvReport->day = $keywordCostReport->day;
                $keywordConvReport->dayOfWeek = $keywordCostReport->dayOfWeek;
                $keywordConvReport->device = $keywordCostReport->device;
                $keywordConvReport->enhancedCPCEnabled = $keywordCostReport->enhancedCPCEnabled;
                $keywordConvReport->estAddClicksWkFirstPositionBid = $keywordCostReport->estAddClicksWkFirstPositionBid;
                $keywordConvReport->estAddCostWkFirstPositionBid = $keywordCostReport->estAddCostWkFirstPositionBid;
                $keywordConvReport->customerID = $keywordCostReport->customerID;
                $keywordConvReport->appFinalURL = $keywordCostReport->appFinalURL;
                $keywordConvReport->mobileFinalURL = $keywordCostReport->mobileFinalURL;
                $keywordConvReport->finalURL = $keywordCostReport->finalURL;
                $keywordConvReport->firstPageCPC = $keywordCostReport->firstPageCPC;
                $keywordConvReport->firstPositionCPC = $keywordCostReport->firstPositionCPC;
                $keywordConvReport->hasQualityScore = $keywordCostReport->hasQualityScore;
                $keywordConvReport->keywordID = $keywordCostReport->keywordID;
                $keywordConvReport->isNegative = $keywordCostReport->isNegative;
                $keywordConvReport->matchType = $keywordCostReport->matchType;
                $keywordConvReport->labelIDs = $keywordCostReport->labelIDs;
                $keywordConvReport->labels = $keywordCostReport->labels;
                $keywordConvReport->month = $keywordCostReport->month;
                $keywordConvReport->monthOfYear = $keywordCostReport->monthOfYear;
                $keywordConvReport->landingPageExperience = $keywordCostReport->landingPageExperience;
                $keywordConvReport->qualityScore = $keywordCostReport->qualityScore;
                $keywordConvReport->quarter = $keywordCostReport->quarter;
                $keywordConvReport->expectedClickthroughRate = $keywordCostReport->expectedClickthroughRate;
                $keywordConvReport->keywordState = $keywordCostReport->keywordState;
                $keywordConvReport->criterionServingStatus = $keywordCostReport->criterionServingStatus;
                $keywordConvReport->topOfPageCPC = $keywordCostReport->topOfPageCPC;
                $keywordConvReport->trackingTemplate = $keywordCostReport->trackingTemplate;
                $keywordConvReport->customParameter = $keywordCostReport->customParameter;
                $keywordConvReport->valueAllConv = $keywordCostReport->valueAllConv;
                $keywordConvReport->valueConv = $keywordCostReport->valueConv;
                $keywordConvReport->valueConvCurrentModel = $keywordCostReport->valueConvCurrentModel;
                $keywordConvReport->verticalID = $keywordCostReport->verticalID;
                $keywordConvReport->week = $keywordCostReport->week;
                $keywordConvReport->year = $keywordCostReport->year;
                $keywordConvReport->saveOrFail();
            }
        }
    }
}
