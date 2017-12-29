<?php

use Illuminate\Database\Seeder;
use App\Model\RepoAdwAdReportCost;
use App\Model\RepoAdwAdReportConv;

// @codingStandardsIgnoreLine
class RepoAdwAdReportConvTableGenerator extends Seeder
{
    const NUMBER_OF_CONVERSION_POINTS = 2;
    const CONVERSION_CATEGORY = 'Conversion category ';
    const CONVERSION_NAME = 'Conversion name ';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adwCostAds = RepoAdwAdReportCost::all();
        foreach ($adwCostAds as $key => $adwCostAd) {
            for ($i=0; $i < self::NUMBER_OF_CONVERSION_POINTS; $i++) {
                $adwConvAd = new RepoAdwAdReportConv;
                $adwConvAd->exeDate = $adwCostAd->exeDate;
                $adwConvAd->startDate = $adwCostAd->startDate;
                $adwConvAd->endDate = $adwCostAd->endDate;
                $adwConvAd->account_id = $adwCostAd->account_id;
                $adwConvAd->campaign_id = $adwCostAd->campaign_id;
                $adwConvAd->account = $adwCostAd->account;
                $adwConvAd->timeZone = $adwCostAd->timeZone;
                $adwConvAd->adGroupID = $adwCostAd->adGroupID;
                $adwConvAd->adGroup = $adwCostAd->adGroup;
                $adwConvAd->network = $adwCostAd->network;
                $adwConvAd->campaignID = $adwCostAd->campaignID;
                $adwConvAd->campaign = $adwCostAd->campaign;
                $adwConvAd->conversionCategory = $adwCostAd->city;
                $adwConvAd->conversions = $adwCostAd->conversions / self::NUMBER_OF_CONVERSION_POINTS;
                $adwConvAd->conversionTrackerId = $i + 1;
                $adwConvAd->conversionName = self::CONVERSION_NAME . ($i + 1);
                $adwConvAd->day = $adwCostAd->day;
                $adwConvAd->dayOfWeek = $adwCostAd->dayOfWeek;
                $adwConvAd->device = $adwCostAd->device;
                $adwConvAd->customerID = $adwCostAd->customerID;
                $adwConvAd->month = $adwCostAd->month;
                $adwConvAd->monthOfYear = $adwCostAd->monthOfYear;
                $adwConvAd->quarter = $adwCostAd->quarter;
                $adwConvAd->week = $adwCostAd->week;
                $adwConvAd->year = $adwCostAd->year;
                $adwConvAd->saveOrFail();
            }
        }
    }
}
