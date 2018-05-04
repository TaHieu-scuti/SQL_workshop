<?php

use Illuminate\Database\Seeder;

use App\Model\RepoYssSearchqueryReportCost;
use App\Model\RepoYssSearchqueryReportConv;

// @codingStandardsIgnoreLine
class RepoYssSearchQueryReportConvGenerator extends Seeder
{
    const NUMBER_OF_CONVERSION_POINTS = 2;
    const CONVERSION_NAME = 'Conversion Name ';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RepoYssSearchqueryReportCost::chunk(1000, function ($searchQueryReports) {
            foreach ($searchQueryReports as $searchQueryReport) {
                for ($i = 0; $i < self::NUMBER_OF_CONVERSION_POINTS; $i++) {
                    $searchQueryConvReport = new RepoYssSearchqueryReportConv;
                    $searchQueryConvReport->exeDate = $searchQueryReport->exeDate;
                    $searchQueryConvReport->startDate = $searchQueryReport->startDate;
                    $searchQueryConvReport->endDate = $searchQueryReport->endDate;
                    $searchQueryConvReport->account_id = $searchQueryReport->account_id;
                    $searchQueryConvReport->campaign_id = $searchQueryReport->campaign_id;
                    $searchQueryConvReport->campaignID = $searchQueryReport->campaignID;
                    $searchQueryConvReport->adgroupID = $searchQueryReport->adgroupID;
                    $searchQueryConvReport->keywordID = $searchQueryReport->keywordID;
                    $searchQueryConvReport->campaignName = $searchQueryReport->campaignName;
                    $searchQueryConvReport->adgroupName = $searchQueryReport->adgroupName;
                    $searchQueryConvReport->searchQuery = $searchQueryReport->searchQuery;
                    $searchQueryConvReport->conversionName = self::CONVERSION_NAME . ($i + 1);
                    $searchQueryConvReport->keyword = $searchQueryReport->keyword;
                    $searchQueryConvReport->conversions = $searchQueryReport->conversions;
                    $searchQueryConvReport->convValue = $searchQueryReport->convValue;
                    $searchQueryConvReport->valuePerConv = $searchQueryReport->valuePerConv;
                    $searchQueryConvReport->allConv = $searchQueryReport->allConv;
                    $searchQueryConvReport->allConvValue = $searchQueryReport->allConvValue;
                    $searchQueryConvReport->valuePerAllConv = $searchQueryReport->valuePerAllConv;
                    $searchQueryConvReport->device = $searchQueryReport->device;
                    $searchQueryConvReport->day = $searchQueryReport->day;
                    $searchQueryConvReport->dayOfWeek = $searchQueryReport->dayOfWeek;
                    $searchQueryConvReport->quarter = $searchQueryReport->quarter;
                    $searchQueryConvReport->month = $searchQueryReport->month;
                    $searchQueryConvReport->week = $searchQueryReport->week;
                    $searchQueryConvReport->accountid = $searchQueryReport->accountid;
                    $searchQueryConvReport->saveOrFail();
                }
            }
        });
    }
}
