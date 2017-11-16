<?php

use Illuminate\Database\Seeder;
use App\Model\RepoAdwAdgroupReportCost;
use App\Model\RepoAdwGeoReportCost;

// @codingStandardsIgnoreLine
class RepoAdwGeoReportGenerator extends Seeder
{
    const MIN_NUMBER_OF_PREFECTURE = 1;
    const MAX_NUMBER_OF_PREFECTURE = 2;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 1;
    const MAX_IMPRESSIONS = 4096;
    const MIN_CLICKS = 1;
    const MAX_CLICKS = 9001;
    const MIN_CONV_RATE = 10000;
    const MAX_CONV_RATE = 20374;
    const MIN_AVERAGE_POSITION = 1000000;
    const MAX_AVERAGE_POSITION = 2037400000;
    const MIN_CONVERSIONS = 10000;
    const MAX_CONVERSIONS = 20374;
    const MIN_ALL_CONV = 10000;
    const MAX_ALL_CONV = 20374;
    const MIN_ALL_CONV_VALUE = 10000;
    const MAX_ALL_CONV_VALUE = 20374;
    const MIN_ALL_CONV_RATE = 1000;
    const MAX_ALL_CONV_RATE = 14374;
    const NETWORKS = ['network1', 'network2', 'network3'];
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adgroupReports = RepoAdwAdgroupReportCost::all();
        foreach ($adgroupReports as $key => $adgroupReport) {
            $ammountOfAdgroup = rand(
                self::MIN_NUMBER_OF_PREFECTURE,
                self::MAX_NUMBER_OF_PREFECTURE
            );
            for ($i=0; $i < $ammountOfAdgroup; $i++) {
                $geoReportCost = new RepoAdwGeoReportCost;
                $geoReportCost->exeDate = $adgroupReport->exeDate;
                $geoReportCost->startDate = $adgroupReport->startDate;
                $geoReportCost->endDate = $adgroupReport->endDate;
                $geoReportCost->accountid = $adgroupReport->accountid;
                $geoReportCost->account = $adgroupReport->account;
                $geoReportCost->account_id = $adgroupReport->account_id;
                $geoReportCost->campaign_id = $adgroupReport->campaign_id;
                $geoReportCost->campaignID = $adgroupReport->campaignID;
                $geoReportCost->campaign = $adgroupReport->campaign;
                $geoReportCost->adGroupID = $adgroupReport->adGroupID;
                $geoReportCost->adGroup = $adgroupReport->adGroup;
                $geoReportCost->cost = mt_rand(
                    self::MIN_COST,
                    self::MAX_COST
                );
                $geoReportCost->impressions = mt_rand(
                    self::MIN_IMPRESSIONS,
                    self::MAX_IMPRESSIONS
                );
                $geoReportCost->clicks = mt_rand(
                    self::MIN_CLICKS,
                    self::MAX_CLICKS
                );
                $geoReportCost->ctr = ($geoReportCost->clicks / $geoReportCost->impressions) * 100;
                $geoReportCost->avgCPC = $geoReportCost->cost / $geoReportCost->clicks;
                $geoReportCost->avgPosition = mt_rand(
                    self::MIN_AVERAGE_POSITION,
                    self::MAX_AVERAGE_POSITION
                ) / mt_getrandmax();
                $geoReportCost->conversions = mt_rand(
                    self::MIN_CONVERSIONS,
                    self::MAX_CONVERSIONS
                ) / mt_getrandmax();
                $geoReportCost->convRate = mt_rand(
                    self::MIN_CONV_RATE,
                    self::MAX_CONV_RATE
                ) / mt_getrandmax();
                $geoReportCost->allConv = mt_rand(
                    self::MIN_ALL_CONV,
                    self::MAX_ALL_CONV
                ) / mt_getrandmax();
                $geoReportCost->allConvRate = mt_rand(
                    self::MIN_ALL_CONV_RATE,
                    self::MAX_ALL_CONV_RATE
                ) / mt_getrandmax();
                $geoReportCost->allConvValue = mt_rand(
                    self::MIN_ALL_CONV_VALUE,
                    self::MAX_ALL_CONV_VALUE
                ) / mt_getrandmax();
                $geoReportCost->totalConvValue = $geoReportCost->allConvValue;
                $geoReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                $geoReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                $geoReportCost->day = $adgroupReport->day;
                $geoReportCost->dayOfWeek = $adgroupReport->dayOfWeek;
                $geoReportCost->quarter = $adgroupReport->quarter;
                $geoReportCost->month = $adgroupReport->month;
                $geoReportCost->week = $adgroupReport->week;
                $geoReportCost->countryTerritory = rand(0, 10000);
                $geoReportCost->city = $geoReportCost->countryTerritory;
                $geoReportCost->saveOrFail();
            }
        }
    }
}
