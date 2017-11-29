<?php

use Illuminate\Database\Seeder;
use App\Model\RepoYssCampaignReportCost;
use App\Model\RepoYssDayofweekReport;

// @codingStandardsIgnoreLine
class DayOfWeeksGenerator extends Seeder
{
    const MIN_NUMBER_OF_DAYOFWEEK = 1;
    const MAX_NUMBER_OF_DAYOFWEEK = 2;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 0;
    const MIN_BIDADJUSTMENT = 1;
    const MAX_BIDADJUSTMENT = 1000;
    const MIN_CLICKS = 0;
    const MIN_CONV_RATE = 1000000;
    const MAX_CONV_RATE = 2037437880;
    const MIN_AVERAGE_POSITION = 1;
    const MAX_AVERAGE_POSITION = 20;
    const MIN_CONVERSIONS = 1000000;
    const MAX_CONVERSIONS = 2037437880;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 2037437880;
    const MIN_COST_PER_CONV = 1000000;
    const MAX_COST_PER_CONV = 2037437880;
    const MIN_VALUE_PER_CONV = 1000000;
    const MAX_VALUE_PER_CONV = 2037437880;
    const MIN_MOBILE_BID_ADJ = 1000000;
    const MAX_MOBILE_BID_ADJ = 2037437880;
    const MIN_DESKTOP_BID_ADJ = 1000000;
    const MAX_DESKTOP_BID_ADJ = 2037437880;
    const MIN_TABLET_BID_ADJ = 1000000;
    const MAX_TABLET_BID_ADJ = 2037437880;
    const MIN_VALUE_PER_ALL_CONV = 1000000;
    const MAX_VALUE_PER_ALL_CONV = 2037437880;
    const MIN_ALL_CONV = 1000000;
    const MAX_ALL_CONV = 2037437880;
    const MIN_ALL_CONV_VALUE = 1000000;
    const MAX_ALL_CONV_VALUE = 2037437880;
    const MIN_ALL_CONV_RATE = 1000;
    const MAX_ALL_CONV_RATE = 1437437880;
    const MIN_COST_PER_ALL_CONV = 1000;
    const MAX_COST_PER_ALL_CONV = 1437437880;
    const TARGET_SCHEDULE = [
        '2017-04-02', '2017-06-02',
        '2017-05-02', '2017-07-02'
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaignReports = RepoYssCampaignReportCost::all();
        foreach ($campaignReports as $campaignReport) {
            $ammountOfCampaign = rand(
                self::MIN_NUMBER_OF_DAYOFWEEK,
                self::MAX_NUMBER_OF_DAYOFWEEK
            );
            for ($i=0; $i < $ammountOfCampaign; $i++) {
                $dayOfWeek = new RepoYssDayofweekReport;
                $dayOfWeek->exeDate = $campaignReport->exeDate;
                $dayOfWeek->startDate = $campaignReport->startDate;
                $dayOfWeek->endDate = $campaignReport->endDate;
                $dayOfWeek->accountid = $campaignReport->accountid;
                $dayOfWeek->account_id = $campaignReport->account_id;
                $dayOfWeek->campaign_id = $campaignReport->campaign_id;
                $dayOfWeek->campaignID = $campaignReport->campaignID;
                $dayOfWeek->campaignName = $campaignReport->campaignName;
                $dayOfWeek->cost = mt_rand(
                    self::MIN_COST,
                    self::MAX_COST
                );
                $dayOfWeek->impressions = mt_rand(
                    self::MIN_IMPRESSIONS,
                    $campaignReport->impressions
                );
                $dayOfWeek->clicks = mt_rand(
                    self::MIN_CLICKS,
                    $dayOfWeek->impressions
                );

                if ($dayOfWeek->impressions === 0) {
                    $dayOfWeek->ctr = 0;
                } else {
                    $dayOfWeek->ctr = ($dayOfWeek->clicks / $dayOfWeek->impressions) * 100;
                }

                if ($dayOfWeek->clicks === 0) {
                    $dayOfWeek->averageCpc = 0;
                } else {
                    $dayOfWeek->averageCpc = $dayOfWeek->cost / $dayOfWeek->clicks;
                }

                $dayOfWeek->averagePosition = mt_rand(
                    self::MIN_AVERAGE_POSITION * 100000,
                    self::MAX_AVERAGE_POSITION * 100000
                ) / 100000;

                $dayOfWeek->bidAdjustment = mt_rand(
                    self::MIN_BIDADJUSTMENT,
                    self::MAX_BIDADJUSTMENT
                );
                $dayOfWeek->targetScheduleID = $i + 1;
                $dayOfWeek->targetSchedule = self::TARGET_SCHEDULE[mt_rand(0, count(self::TARGET_SCHEDULE) - 1)];
                $dayOfWeek->conversions = mt_rand(
                    self::MIN_CONVERSIONS,
                    self::MAX_CONVERSIONS
                ) / mt_getrandmax();
                $dayOfWeek->convRate = mt_rand(
                    self::MIN_CONV_RATE,
                    self::MAX_CONV_RATE
                ) / mt_getrandmax();
                $dayOfWeek->convValue = mt_rand(
                    self::MIN_CONV_VALUE,
                    self::MAX_CONV_VALUE
                ) / mt_getrandmax();
                $dayOfWeek->costPerConv = mt_rand(
                    self::MIN_COST_PER_CONV,
                    self::MAX_COST_PER_CONV
                ) / mt_getrandmax();
                $dayOfWeek->valuePerConv = mt_rand(
                    self::MIN_VALUE_PER_CONV,
                    self::MAX_VALUE_PER_CONV
                ) / mt_getrandmax();
                $dayOfWeek->allConv = mt_rand(
                    self::MIN_ALL_CONV,
                    self::MAX_ALL_CONV
                ) / mt_getrandmax();
                $dayOfWeek->allConvRate = mt_rand(
                    self::MIN_ALL_CONV_RATE,
                    self::MAX_ALL_CONV_RATE
                ) / mt_getrandmax();
                $dayOfWeek->allConvValue = mt_rand(
                    self::MIN_ALL_CONV_VALUE,
                    self::MAX_ALL_CONV_VALUE
                ) / mt_getrandmax();
                $dayOfWeek->costPerAllConv =  mt_rand(
                    self::MIN_COST_PER_ALL_CONV,
                    self::MAX_COST_PER_ALL_CONV
                ) / mt_getrandmax();
                $dayOfWeek->valuePerAllConv = mt_rand(
                    self::MIN_VALUE_PER_ALL_CONV,
                    self::MAX_VALUE_PER_ALL_CONV
                ) / mt_getrandmax();
                $dayOfWeek->day = $campaignReport->day;
                $dayOfWeek->quarter = $campaignReport->quarter;
                $dayOfWeek->month = $campaignReport->month;
                $dayOfWeek->week = $campaignReport->week;
                $dayOfWeek->saveOrFail();
            }
        }
    }
}
