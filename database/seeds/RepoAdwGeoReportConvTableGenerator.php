<?php

use Illuminate\Database\Seeder;

use App\Model\RepoAdwGeoReportCost;
use App\Model\RepoAdwGeoReportConv;

class RepoAdwGeoReportConvTableGenerator extends Seeder
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
        $adwGeoCostRecords = RepoAdwGeoReportCost::all();
        foreach ($adwGeoCostRecords as $adwGeoCostRecord) {
            for ($i=0; $i < self::NUMBER_OF_CONVERSION_POINTS; $i++) {
                $adwGeoConvRecord = new RepoAdwGeoReportConv;
                $adwGeoConvRecord->exeDate = $adwGeoCostRecord->exeDate;
                $adwGeoConvRecord->startDate = $adwGeoCostRecord->startDate;
                $adwGeoConvRecord->endDate = $adwGeoCostRecord->endDate;
                $adwGeoConvRecord->account_id = $adwGeoCostRecord->account_id;
                $adwGeoConvRecord->campaign_id = $adwGeoCostRecord->campaign_id;
                $adwGeoConvRecord->account = $adwGeoCostRecord->account;
                $adwGeoConvRecord->timeZone = $adwGeoCostRecord->timeZone;
                $adwGeoConvRecord->adGroupID = $adwGeoCostRecord->adGroupID;
                $adwGeoConvRecord->adGroup = $adwGeoCostRecord->adGroup;
                $adwGeoConvRecord->network = $adwGeoCostRecord->network;
                $adwGeoConvRecord->networkWithSearchPartners = $adwGeoCostRecord->networkWithSearchPartners;
                $adwGeoConvRecord->campaignID = $adwGeoCostRecord->campaignID;
                $adwGeoConvRecord->campaign = $adwGeoCostRecord->campaign;
                $adwGeoConvRecord->city = $adwGeoCostRecord->city;
                $adwGeoConvRecord->conversionCategory = self::CONVERSION_CATEGORY . ($i + 1);
                $adwGeoConvRecord->conversions = $adwGeoCostRecord->conversions / self::NUMBER_OF_CONVERSION_POINTS;
                $adwGeoConvRecord->conversionTrackerId = $i + 1;
                $adwGeoConvRecord->conversionName = self::CONVERSION_NAME . ($i + 1);
                $adwGeoConvRecord->clientName = $adwGeoCostRecord->clientName;
                $adwGeoConvRecord->day = $adwGeoCostRecord->day;
                $adwGeoConvRecord->dayOfWeek = $adwGeoCostRecord->dayOfWeek;
                $adwGeoConvRecord->device = $adwGeoCostRecord->device;
                $adwGeoConvRecord->customerID = $adwGeoCostRecord->customerID;
                $adwGeoConvRecord->month = $adwGeoCostRecord->month;
                $adwGeoConvRecord->monthOfYear = $adwGeoCostRecord->monthOfYear;
                $adwGeoConvRecord->quarter = $adwGeoCostRecord->quarter;
                $adwGeoConvRecord->region = $adwGeoCostRecord->region;
                $adwGeoConvRecord->week = $adwGeoCostRecord->week;
                $adwGeoConvRecord->year = $adwGeoCostRecord->year;
                $adwGeoConvRecord->saveOrFail();
            }
        }
    }
}
