<?php

use Illuminate\Database\Seeder;

use App\Model\RepoAdwSearchQueryPerformanceReport;
use App\Model\RepoAdwSearchQueryPerformanceReportConv;

// @codingStandardsIgnoreLine
class RepoAdwSearchQueryPerformanceReportConvGenerator extends Seeder
{
    const NUMBER_OF_CONVERSION_POINTS = 2;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RepoAdwSearchQueryPerformanceReport::chunk(1000, function ($searchQueryPerformanceReports) {
            foreach ($searchQueryPerformanceReports as $searchQueryPerformanceReport) {
                for ($i = 0; $i < self::NUMBER_OF_CONVERSION_POINTS; $i++) {
                    $searchQueryPerformanceConvReport = new RepoAdwSearchQueryPerformanceReportConv;
                    $searchQueryPerformanceConvReport->exeDate = $searchQueryPerformanceReport->exeDate;
                    $searchQueryPerformanceConvReport->startDate = $searchQueryPerformanceReport->startDate;
                    $searchQueryPerformanceConvReport->endDate = $searchQueryPerformanceReport->endDate;
                    $searchQueryPerformanceConvReport->account_id = $searchQueryPerformanceReport->account_id;
                    $searchQueryPerformanceConvReport->campaign_id = $searchQueryPerformanceReport->campaign_id;
                    $searchQueryPerformanceConvReport->currency = $searchQueryPerformanceReport->currency;
                    $searchQueryPerformanceConvReport->account = $searchQueryPerformanceReport->account;
                    $searchQueryPerformanceConvReport->timeZone = $searchQueryPerformanceReport->timeZone;
                    $searchQueryPerformanceConvReport->adType = $searchQueryPerformanceReport->adType;
                    $searchQueryPerformanceConvReport->adGroupID = $searchQueryPerformanceReport->adGroupID;
                    $searchQueryPerformanceConvReport->adGroup = $searchQueryPerformanceReport->adGroup;
                    $searchQueryPerformanceConvReport->adGroupState = $searchQueryPerformanceReport->adGroupState;
                    $searchQueryPerformanceConvReport->network = $searchQueryPerformanceReport->network;
                    $searchQueryPerformanceConvReport->networkWithSearchPartners =
                        $searchQueryPerformanceReport->networkWithSearchPartners;
                    $searchQueryPerformanceConvReport->allConvRate = $searchQueryPerformanceReport->allConvRate;
                    $searchQueryPerformanceConvReport->allConv = $searchQueryPerformanceReport->allConv;
                    $searchQueryPerformanceConvReport->allConvValue = $searchQueryPerformanceReport->allConvValue;
                    $searchQueryPerformanceConvReport->campaignID = $searchQueryPerformanceReport->campaignID;
                    $searchQueryPerformanceConvReport->campaign = $searchQueryPerformanceReport->campaign;
                    $searchQueryPerformanceConvReport->campaignState = $searchQueryPerformanceReport->campaignState;
                    $searchQueryPerformanceConvReport->convRate = $searchQueryPerformanceReport->convRate;
                    $searchQueryPerformanceConvReport->conversions
                        = $searchQueryPerformanceReport->conversions / self::NUMBER_OF_CONVERSION_POINTS;
                    $searchQueryPerformanceConvReport->totalConvValue = $searchQueryPerformanceReport->totalConvValue;
                    $searchQueryPerformanceConvReport->costAllConv = $searchQueryPerformanceReport->costAllConv;
                    $searchQueryPerformanceConvReport->costConv = $searchQueryPerformanceReport->costConv;
                    $searchQueryPerformanceConvReport->adID = $searchQueryPerformanceReport->adID;
                    $searchQueryPerformanceConvReport->crossDeviceConv = $searchQueryPerformanceReport->crossDeviceConv;
                    $searchQueryPerformanceConvReport->clientName = $searchQueryPerformanceReport->clientName;
                    $searchQueryPerformanceConvReport->day = $searchQueryPerformanceReport->day;
                    $searchQueryPerformanceConvReport->dayOfWeek = $searchQueryPerformanceReport->dayOfWeek;
                    $searchQueryPerformanceConvReport->destinationURL = $searchQueryPerformanceReport->destinationURL;
                    $searchQueryPerformanceConvReport->device = $searchQueryPerformanceReport->device;
                    $searchQueryPerformanceConvReport->customerID = $searchQueryPerformanceReport->customerID;
                    $searchQueryPerformanceConvReport->finalURL = $searchQueryPerformanceReport->finalURL;
                    $searchQueryPerformanceConvReport->keywordID = $searchQueryPerformanceReport->keywordID;
                    $searchQueryPerformanceConvReport->keyword = $searchQueryPerformanceReport->keyword;
                    $searchQueryPerformanceConvReport->month = $searchQueryPerformanceReport->month;
                    $searchQueryPerformanceConvReport->monthOfYear = $searchQueryPerformanceReport->monthOfYear;
                    $searchQueryPerformanceConvReport->quarter = $searchQueryPerformanceReport->quarter;
                    $searchQueryPerformanceConvReport->searchTerm = $searchQueryPerformanceReport->searchTerm;
                    $searchQueryPerformanceConvReport->matchType = $searchQueryPerformanceReport->matchType;
                    $searchQueryPerformanceConvReport->addedExcluded = $searchQueryPerformanceReport->addedExcluded;
                    $searchQueryPerformanceConvReport->trackingTemplate =
                        $searchQueryPerformanceReport->trackingTemplate;
                    $searchQueryPerformanceConvReport->valueAllConv = $searchQueryPerformanceReport->valueAllConv;
                    $searchQueryPerformanceConvReport->valueConv = $searchQueryPerformanceReport->valueConv;
                    $searchQueryPerformanceConvReport->viewThroughConv = $searchQueryPerformanceReport->viewThroughConv;
                    $searchQueryPerformanceConvReport->week = $searchQueryPerformanceReport->week;
                    $searchQueryPerformanceConvReport->year = $searchQueryPerformanceReport->year;
                    $searchQueryPerformanceConvReport->saveOrFail();
                }
            }
        });
    }
}
