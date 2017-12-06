<?php

use Illuminate\Database\Seeder;
use App\Model\RepoAdwAdgroupReportCost;
use App\Model\RepoAdwAdReportCost;

// @codingStandardsIgnoreLine
class RepoAdwAdReportGenerator extends Seeder
{
    const MIN_NUMBER_OF_AD = 1;
    const MAX_NUMBER_OF_AD = 2;
    const MIN_COST = 0;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 0;
    const MIN_CLICKS = 0;
    const MIN_AVERAGE_POSITION = 1;
    const MAX_AVERAGE_POSITION = 20;
    const MIN_CONVERSIONS = 0;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 894894374;
    const MIN_ALL_CONV_RATE = 100;
    const MAX_ALL_CONV_RATE = 89489437;
    const MIN_ALL_CONV = 1000000;
    const MAX_ALL_CONV = 894894374;
    const MIN_ALL_CONV_VALUE = 1000000;
    const MAX_ALL_CONV_VALUE = 894894374;
    const MIN_VALUE_ALL_CONV = 1000000;
    const MAX_VALUE_ALL_CONV = 894894374;
    const MIN_TOTAL_CONV_VALUE = 1000000;
    const MAX_TOTAL_CONV_VALUE = 894894374;
    const NETWORKS = ['CONTENT'];
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];
    const CLICK_TYPE = [
        'Click Type 1', 'Click Type 2',
        'Click Type 3', 'Click Type 4'
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adgroupReports = RepoAdwAdgroupReportCost::all();
        foreach ($adgroupReports as $adgroupReport) {
            $ammountOfKeyword = rand(
                self::MIN_NUMBER_OF_AD,
                self::MAX_NUMBER_OF_AD
            );
            for ($i = 0; $i < $ammountOfKeyword + 1; $i++) {
                $adReportCost = new RepoAdwAdReportCost;
                $adReportCost->exeDate = $adgroupReport->exeDate;
                $adReportCost->startDate = $adgroupReport->startDate;
                $adReportCost->endDate = $adgroupReport->endDate;
                $adReportCost->account_id = $adgroupReport->account_id;
                $adReportCost->account = $adgroupReport->account;
                $adReportCost->campaign_id = $adgroupReport->campaign_id;
                $adReportCost->adGroupID = $adgroupReport->adGroupID;
                $adReportCost->adGroup = $adgroupReport->adGroup;
                $adReportCost->campaignID = $adgroupReport->campaignID;
                $adReportCost->campaign = $adgroupReport->campaign;

                $adReportCost->adID = $i + 1;

                if ($i % 2 === 0) {
                    $adReportCost->ad = 'Some text advertisement';
                } else {
                    $adReportCost->imageAdURL =
                        'https://flydigitalprint.com/wp/wp-content/uploads/2016/12/banner-sign.jpg';
                }

                $adReportCost->cost = mt_rand(
                    self::MIN_COST,
                    self::MAX_COST
                );
                $adReportCost->impressions = mt_rand(
                    self::MIN_IMPRESSIONS,
                    $adgroupReport->impressions
                );
                $adReportCost->clicks = mt_rand(
                    self::MIN_CLICKS,
                    $adReportCost->impressions
                );

                if ($adReportCost->impressions === 0) {
                    $adReportCost->ctr = 0;
                } else {
                    $adReportCost->ctr = ($adReportCost->clicks / $adReportCost->impressions) * 100;
                }

                if ($adReportCost->clicks === 0) {
                    $adReportCost->avgCPC = 0;
                } else {
                    $adReportCost->avgCPC = $adReportCost->cost / $adReportCost->clicks;
                }

                $adReportCost->avgPosition = mt_rand(
                    self::MIN_AVERAGE_POSITION * 100000,
                    self::MAX_AVERAGE_POSITION * 100000
                ) / 100000;

                $adReportCost->conversions = mt_rand(
                    self::MIN_CONVERSIONS,
                    $adReportCost->clicks
                );

                if ($adReportCost->clicks === 0) {
                    $adReportCost->convRate = 0;
                } else {
                    $adReportCost->convRate = ($adReportCost->conversions / $adReportCost->clicks) * 100;
                }

                $adReportCost->valueConv = mt_rand(
                    self::MIN_CONV_VALUE,
                    self::MAX_CONV_VALUE
                ) / mt_getrandmax();
                $adReportCost->allConv = mt_rand(
                    self::MIN_ALL_CONV,
                    self::MAX_ALL_CONV
                ) / mt_getrandmax();
                $adReportCost->allConvRate = mt_rand(
                    self::MIN_ALL_CONV_RATE,
                    self::MAX_ALL_CONV_RATE
                ) / mt_getrandmax();
                $adReportCost->allConvValue = mt_rand(
                    self::MIN_ALL_CONV_VALUE,
                    self::MAX_ALL_CONV_VALUE
                ) / mt_getrandmax();
                $adReportCost->valueAllConv = mt_rand(
                    self::MIN_VALUE_ALL_CONV,
                    self::MAX_VALUE_ALL_CONV
                ) / mt_getrandmax();
                $adReportCost->valueAllConv = mt_rand(
                    self::MIN_TOTAL_CONV_VALUE,
                    self::MAX_TOTAL_CONV_VALUE
                ) / mt_getrandmax();
                $adReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                $adReportCost->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
                $adReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                $adReportCost->day = $adgroupReport->day;
                $adReportCost->dayOfWeek = $adgroupReport->dayOfWeek;
                $adReportCost->quarter = $adgroupReport->quarter;
                $adReportCost->month = $adgroupReport->month;
                $adReportCost->week = $adgroupReport->week;
                $adReportCost->accountid = $adgroupReport->accountid;

                $adReportCost->saveOrFail();
            }
        }
    }
}
