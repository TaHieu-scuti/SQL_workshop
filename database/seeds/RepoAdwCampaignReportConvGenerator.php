<?php

use Illuminate\Database\Seeder;

use App\Model\RepoAdwCampaignReportConv;
use App\Model\RepoAdwCampaignReportCost;

class RepoAdwCampaignReportConvGenerator extends Seeder
{
    const NUMBER_OF_CONVERSION_POINTS = 3;
    const CONVERSION_NAME = 'Conversion name';
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
        $campaignCostReports = RepoAdwCampaignReportCost::all();
        foreach ($campaignCostReports as $campaignCostReport) {
            for ($i = 0; $i < self::NUMBER_OF_CONVERSION_POINTS; $i++) {
                $campaignConvReport = new RepoAdwCampaignReportConv;

                $campaignConvReport->exeDate = $campaignCostReport->exeDate;
                $campaignConvReport->startDate = $campaignCostReport->startDate;
                $campaignConvReport->endDate = $campaignCostReport->endDate;
                $campaignConvReport->account_id = $campaignCostReport->account_id;
                $campaignConvReport->campaign_id = $campaignCostReport->campaign_id;
                $campaignConvReport->currency = $campaignCostReport->currency;
                $campaignConvReport->account = $campaignCostReport->account;
                $campaignConvReport->timeZone = $campaignCostReport->timeZone;
                $campaignConvReport->network = $campaignCostReport->network;
                $campaignConvReport->networkWithSearchPartners = $campaignCostReport->networkWithSearchPartners;
                $campaignConvReport->advertisingSubChannel = $campaignCostReport->advertisingSubChannel;
                $campaignConvReport->advertisingChannel = $campaignCostReport->advertisingChannel;
                $campaignConvReport->budget = $campaignCostReport->budget;
                $campaignConvReport->baseCampaignID = $campaignCostReport->baseCampaignID;
                $campaignConvReport->bidStrategyID = $campaignCostReport->bidStrategyID;
                $campaignConvReport->bidStrategyName = $campaignCostReport->bidStrategyName;
                $campaignConvReport->bidStrategyType = $campaignCostReport->bidStrategyType;
                $campaignConvReport->conversionOptimizerBidType = $campaignCostReport->conversionOptimizerBidType;
                $campaignConvReport->budgetID = $campaignCostReport->budgetID;
                $campaignConvReport->desktopBidAdj = $campaignCostReport->desktopBidAdj;
                $campaignConvReport->campaignGroupID = $campaignCostReport->campaignGroupID;
                $campaignConvReport->campaignID = $campaignCostReport->campaignID;
                $campaignConvReport->mobileBidAdj = $campaignCostReport->mobileBidAdj;
                $campaignConvReport->campaign = $campaignCostReport->campaign;
                $campaignConvReport->campaignState = $campaignCostReport->campaignState;
                $campaignConvReport->tabletBidAdj = $campaignCostReport->tabletBidAdj;
                $campaignConvReport->campaignTrialType = $campaignCostReport->campaignTrialType;
                $campaignConvReport->clickType = $campaignCostReport->clickType;
                $campaignConvReport->conversionCategory = self::CONVERSION_CATEGORY[rand(0, count(self::CONVERSION_CATEGORY) -1)];
                $campaignConvReport->convRate = $campaignCostReport->convRate;
                $campaignConvReport->conversions = $campaignCostReport->conversions / self::NUMBER_OF_CONVERSION_POINTS;
                $campaignConvReport->conversionName = self::CONVERSION_NAME . ($i + 1);
                $campaignConvReport->convValueCurrentModel = $campaignCostReport->convValueCurrentModel;
                $campaignConvReport->clientName = $campaignCostReport->clientName;
                $campaignConvReport->day = $campaignCostReport->day;
                $campaignConvReport->dayOfWeek = $campaignCostReport->dayOfWeek;
                $campaignConvReport->device = $campaignCostReport->device;
                $campaignConvReport->campaignEndDate = $campaignCostReport->campaignEndDate;
                $campaignConvReport->enhancedCPCEnabled = $campaignCostReport->enhancedCPCEnabled;
                $campaignConvReport->enhancedCPVEnabled = $campaignCostReport->enhancedCPVEnabled;
                $campaignConvReport->customerID = $campaignCostReport->customerID;
                $campaignConvReport->budgetExplicitlyShared = $campaignCostReport->budgetExplicitlyShared;
                $campaignConvReport->labelIDs = $campaignCostReport->labelIDs;
                $campaignConvReport->labels = $campaignCostReport->labels;
                $campaignConvReport->month = $campaignCostReport->month;
                $campaignConvReport->monthOfYear = $campaignCostReport->monthOfYear;
                $campaignConvReport->budgetPeriod = $campaignCostReport->budgetPeriod;
                $campaignConvReport->quarter = $campaignCostReport->quarter;
                $campaignConvReport->campaignServingStatus = $campaignCostReport->campaignServingStatus;
                $campaignConvReport->campaignStartDate = $campaignCostReport->campaignStartDate;
                $campaignConvReport->trackingTemplate = $campaignCostReport->trackingTemplate;
                $campaignConvReport->customParameter = $campaignCostReport->customParameter;
                $campaignConvReport->valueConv = $campaignCostReport->valueConv;
                $campaignConvReport->valueConvCurrentModel = $campaignCostReport->valueConvCurrentModel;
                $campaignConvReport->week = $campaignCostReport->week;
                $campaignConvReport->year = $campaignCostReport->year;
                $campaignConvReport->saveOrFail();
            }
        }
    }
}
