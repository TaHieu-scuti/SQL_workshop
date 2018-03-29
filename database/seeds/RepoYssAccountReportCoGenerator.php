<?php

use Illuminate\Database\Seeder;

use App\Model\RepoYssAccountReportCost;
use App\Model\RepoYssAccountReportConv;

// @codingStandardsIgnoreLine
class RepoYssAccountReportCoGenerator extends Seeder
{
    const START_DATE = '2017-12-01 00:00:00';
    const INTERVAL = 'P1D';
    const END_DATE = '2018-05-01 00:00:00';
    const NUMBER_OF_ACCOUNTS = 8;
    const NUMBER_OF_MEDIA_ACCOUNTS = 1;
    const MIN_NUMBER_OF_CAMPAIGNS = 1;
    const MAX_NUMBER_OF_CAMPAIGNS = 3;
    const MIN_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN = 1;
    const MAX_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN = 1;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 0;
    const MAX_IMPRESSIONS = 4096;
    const MIN_CLICKS = 0;
    const MIN_AVERAGE_POSITION = 1;
    const MAX_AVERAGE_POSITION = 20;
    const MIN_IMPRESSION_SHARE = 1000000;
    const MAX_IMPRESSION_SHARE = 9999999;
    const MIN_EXACT_MATCH_IMPRESSION_SHARE = 1000000;
    const MAX_EXACT_MATCH_IMPRESSION_SHARE = 9999999;
    const MIN_BUDGET_LOST_IMPRESSION_SHARE = 1000000;
    const MAX_BUDGET_LOST_IMPRESSION_SHARE = 9999999;
    const MIN_QUALITY_LOST_IMPRESSION_SHARE = 1000000;
    const MAX_QUALITY_LOST_IMPRESSION_SHARE = 9999999;
    const TRACKING_URL = 'http://we.track.people/';
    const MIN_CONVERSIONS = 0;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 9999999;
    const MIN_COST_PER_CONV = 1000000;
    const MAX_COST_PER_CONV = 9999999;
    const MIN_VALUE_PER_CONV = 1000000;
    const MAX_VALUE_PER_CONV = 9999999;
    const MIN_ALL_CONV = 1000000;
    const MAX_ALL_CONV = 9999999;
    const MIN_ALL_CONV_RATE = 1000000;
    const MAX_ALL_CONV_RATE = 9999999;
    const MIN_ALL_CONV_VALUE = 1000000;
    const MAX_ALL_CONV_VALUE = 9999999;
    const MIN_COST_PER_ALL_CONV = 1000000;
    const MAX_COST_PER_ALL_CONV = 9999999;
    const MIN_VALUE_PER_ALL_CONV = 1000000;
    const MAX_VALUE_PER_ALL_CONV = 9999999;
    const NETWORKS = ['network1', 'network2', 'network3'];
    const DEVICES = ['DESKTOP', 'SMART_PHONE', 'NONE'];

    private function processDay(DateTime $day)
    {
        for ($i = 0; $i < self::NUMBER_OF_ACCOUNTS; ++$i) {
            $this->processAGAccount($day, $i);
        }
    }

    private function processAGAccount(DateTime $day, $agAccountNumber)
    {
        $numberOfMediaAccounts = 1;

        for ($i = 0; $i < $numberOfMediaAccounts; $i++) {
            $this->processMediaAccount($day, $agAccountNumber, (($agAccountNumber + 1) * 10) + $i);
        }
    }

    private function processMediaAccount(DateTime $day, $agAccountNumber, $mediaAccountNumber)
    {
        $numberOfCampaigns = rand(
            self::MIN_NUMBER_OF_CAMPAIGNS,
            self::MAX_NUMBER_OF_CAMPAIGNS
        );

        for ($i = 0; $i < $numberOfCampaigns + 1; ++$i) {
            $this->processCampaign($day, $agAccountNumber, $mediaAccountNumber, $i);
        }
    }

    private function processCampaign(DateTime $day, $agAccountNumber, $mediaAccountNumber, $campaignNumber)
    {
        $numberOfReports = rand(
            self::MIN_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN,
            self::MAX_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN
        );

        for ($i = 0; $i < $numberOfReports + 1; ++$i) {
            $this->createReport($day, $agAccountNumber, $mediaAccountNumber, $campaignNumber);
        }
    }

    private function createReport(DateTime $day, $agAccountNumber, $mediaAccountNumber, $campaignNumber)
    {
        $costReport = new RepoYssAccountReportCost;
        $convReport = new RepoYssAccountReportConv;

        $costReport->account_id = $agAccountNumber + 1;
        $convReport->account_id = $costReport->account_id;

        $costReport->accountid = $mediaAccountNumber + 1;
        $convReport->accountid = $costReport->accountid;

        $costReport->campaign_id = ($costReport->account_id * 10) + $campaignNumber + 1;
        $convReport->campaign_id = $costReport->campaign_id;

        $costReport->cost = mt_rand(
            self::MIN_COST,
            self::MAX_COST
        );

        $costReport->impressions = mt_rand(
            self::MIN_IMPRESSIONS,
            self::MAX_IMPRESSIONS
        );

        $costReport->clicks = mt_rand(
            self::MIN_CLICKS,
            $costReport->impressions
        );

        if ($costReport->impressions === 0) {
            $costReport->ctr = 0;
        } else {
            $costReport->ctr = ($costReport->clicks / $costReport->impressions) * 100;
        }

        if ($costReport->clicks === 0) {
            $costReport->averageCpc = 0;
        } else {
            $costReport->averageCpc = $costReport->cost / $costReport->clicks;
        }

        $costReport->averagePosition = mt_rand(
            self::MIN_AVERAGE_POSITION * 100000,
            self::MAX_AVERAGE_POSITION * 100000
        ) / 100000;

        $costReport->impressionShare = mt_rand(
            self::MIN_IMPRESSION_SHARE,
            self::MAX_IMPRESSION_SHARE
        ) / mt_getrandmax();

        $costReport->exactMatchImpressionShare = mt_rand(
            self::MIN_EXACT_MATCH_IMPRESSION_SHARE,
            self::MAX_EXACT_MATCH_IMPRESSION_SHARE
        ) / mt_getrandmax();

        $costReport->budgetLostImpressionShare = mt_rand(
            self::MIN_BUDGET_LOST_IMPRESSION_SHARE,
            self::MAX_BUDGET_LOST_IMPRESSION_SHARE
        ) / mt_getrandmax();

        $costReport->qualityLostImpressionShare = mt_rand(
            self::MIN_QUALITY_LOST_IMPRESSION_SHARE,
            self::MAX_QUALITY_LOST_IMPRESSION_SHARE
        ) / mt_getrandmax();

        $costReport->trackingURL = self::TRACKING_URL;
        $convReport->trackingURL = $costReport->trackingURL;

        $costReport->conversions = mt_rand(
            self::MIN_CONVERSIONS,
            $costReport->clicks
        );

        $convReport->conversions = $costReport->conversions;

        if ($costReport->clicks === 0) {
            $costReport->convRate = 0;
        } else {
            $costReport->convRate = ($costReport->conversions / $costReport->clicks) * 100;
        }

        $costReport->convValue = mt_rand(
            self::MIN_CONV_VALUE,
            self::MAX_CONV_VALUE
        ) / mt_getrandmax();
        $convReport->convValue = $costReport->convValue;

        $costReport->costPerConv = mt_rand(
            self::MIN_COST_PER_CONV,
            self::MAX_COST_PER_CONV
        ) / mt_getrandmax();

        $costReport->valuePerConv = mt_rand(
            self::MIN_VALUE_PER_CONV,
            self::MAX_VALUE_PER_CONV
        ) / mt_getrandmax();
        $convReport->valuePerConv = $costReport->valuePerConv;

        $convReport->allConv = mt_rand(
            self::MIN_ALL_CONV,
            self::MAX_ALL_CONV
        ) / mt_getrandmax();

        $convReport->allConvValue = mt_rand(
            self::MIN_ALL_CONV_VALUE,
            self::MAX_ALL_CONV_VALUE
        ) / mt_getrandmax();

        $convReport->valuePerAllConv = mt_rand(
            self::MIN_VALUE_PER_ALL_CONV,
            self::MAX_VALUE_PER_ALL_CONV
        ) / mt_getrandmax();

        $convReport->clickType = 'random click';
        $convReport->objectiveOfConversionTracking = 'objective';
        $convReport->conversionName = 'name of this conversion';

        $costReport->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
        $convReport->network = $costReport->network;

        $costReport->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
        $convReport->device = $costReport->device;

        $costReport->day = $day;
        $convReport->day = $costReport->day;

        $costReport->dayOfWeek = $day->format('l');
        $convReport->dayOfWeek = $costReport->dayOfWeek;

        $costReport->quarter = (int)ceil((int)$day->format('n') / 3);
        $convReport->quarter = $costReport->quarter;

        $costReport->month = $day->format('F');
        $convReport->month = $costReport->month;

        $costReport->week = $day->format('W');
        $convReport->week = $costReport->week;

        $costReport->exeDate = $day->format('Y-m-d');
        $convReport->exeDate = $costReport->exeDate;

        $costReport->startDate = $day->format('Y-m-d');
        $convReport->startDate = $costReport->startDate;

        $costReport->endDate = $day->format('Y-m-d');
        $convReport->endDate = $costReport->endDate;

        $costReport->hourofday = $day->format('H');

        $costReport->saveOrFail();
        $convReport->saveOrFail();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = new DateTime(self::START_DATE);
        $interval = new DateInterval(self::INTERVAL);
        $end = new DateTime(self::END_DATE);

        $period = new DatePeriod($start, $interval, $end);

        foreach ($period as $date) {
            $hourRandom = mt_rand(0, 23);
            $date->modify('+'. $hourRandom .' hour');
            $this->processDay($date);
        }
    }
}
