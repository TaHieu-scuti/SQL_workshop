<?php

use Illuminate\Database\Seeder;

use App\RepoYssAccountReport;

class RepoYssAccountReportGenerator extends Seeder
{
    const START_DATE = '2017-01-01 00:00:00';
    const INTERVAL = 'P1D';
    const END_DATE = '2018-02-03 00:00:00';
    const NUMBER_OF_ACCOUNTS = 5;
    const MIN_NUMBER_OF_CAMPAIGNS = 1;
    const MAX_NUMBER_OF_CAMPAIGNS = 12;
    const MIN_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN = 0;
    const MAX_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN = 5;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 0;
    const MAX_IMPRESSIONS = 4096;
    const MIN_CLICKS = 0;
    const MAX_CLICKS = 9001;
    const MIN_CTR = 0;
    const MAX_CTR = 73440;
    const MIN_AVERAGE_CPC = 1;
    const MAX_AVERAGE_CPC = 890;
    const MIN_AVERAGE_POSITION = 1;
    const MAX_AVERAGE_POSITION = 786778;
    const MIN_INVALID_CLICKS = 0;
    const MAX_INVALID_CLICKS = 65569;
    const MIN_INVALID_CLICK_RATE = 0;
    const MAX_INVALID_CLICK_RATE = 879879;
    const MIN_IMPRESSION_SHARE = 0;
    const MAX_IMPRESSION_SHARE = 9088398;
    const MIN_EXACT_MATCH_IMPRESSION_SHARE = 0;
    const MAX_EXACT_MATCH_IMPRESSION_SHARE = 980751;
    const MIN_BUDGET_LOST_IMPRESSION_SHARE = 0;
    const MAX_BUDGET_LOST_IMPRESSION_SHARE = 324554532;
    const MIN_QUALITY_LOST_IMPRESSION_SHARE = 0;
    const MAX_QUALITY_LOST_IMPRESSION_SHARE = 9832932;
    const TRACKING_URL = 'http://we.track.people/';
    const MIN_CONVERSIONS = 0;
    const MAX_CONVERSIONS = 99886764;
    const MIN_CONV_RATE = 0;
    const MAX_CONV_RATE = 32489012;
    const MIN_CONV_VALUE = 0;
    const MAX_CONV_VALUE = 8934903;
    const MIN_COST_PER_CONV = 1;
    const MAX_COST_PER_CONV = 3299843897;
    const MIN_VALUE_PER_CONV = 1;
    const MAX_VALUE_PER_CONV = 9834933093;
    const MIN_ALL_CONV = 1;
    const MAX_ALL_CONV = 32434243545;
    const MIN_ALL_CONV_RATE = 1;
    const MAX_ALL_CONV_RATE = 2319089213;
    const MIN_ALL_CONV_VALUE = 1;
    const MAX_ALL_CONV_VALUE = 345689765;
    const MIN_COST_PER_ALL_CONV = 1;
    const MAX_COST_PER_ALL_CONV = 454544598876;
    const MIN_VALUE_PER_ALL_CONV = 1;
    const MAX_VALUE_PER_ALL_CONV = 3247189432798;
    const NETWORKS = ['network1', 'network2', 'network3'];
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];

    private function processDay(DateTime $day)
    {
        for ($i = 0; $i < self::NUMBER_OF_ACCOUNTS; ++$i) {
            $this->processAccount($day, $i);
        }
    }

    private function processAccount(DateTime $day, $accountNumber)
    {
        $numberOfCampaigns = rand(
            self::MIN_NUMBER_OF_CAMPAIGNS,
            self::MAX_NUMBER_OF_CAMPAIGNS
        );

        for ($i = 0; $i < $numberOfCampaigns + 1; ++$i) {
            $this->processCampaign($day, $accountNumber, $i);
        }
    }

    private function processCampaign(DateTime $day, $accountNumber, $campaignNumber)
    {
        $numberOfReports = rand(
            self::MIN_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN,
            self::MAX_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN
        );

        for ($i = 0; $i < $numberOfReports + 1; ++$i) {
            $this->createReport($day, $accountNumber, $campaignNumber);
        }
    }

    private function createReport(DateTime $day, $accountNumber, $campaignNumber)
    {
        $report = new RepoYssAccountReport;
        $report->account_id = $accountNumber + 1;
        $report->campaign_id = $campaignNumber + 1;
        $report->cost = mt_rand(
            self::MIN_COST,
            self::MAX_COST
        );
        $report->impressions = mt_rand(
            self::MIN_IMPRESSIONS,
            self::MAX_IMPRESSIONS
        );
        $report->clicks = mt_rand(
            self::MIN_CLICKS,
            self::MAX_CLICKS
        );
        $report->ctr = mt_rand(self::MIN_CTR, self::MAX_CTR) / mt_getrandmax();
        $report->averageCpc = mt_rand(
            self::MIN_AVERAGE_CPC,
            self::MAX_AVERAGE_CPC
        ) / mt_getrandmax();
        $report->averagePosition = mt_rand(
            self::MIN_AVERAGE_POSITION,
            self::MAX_AVERAGE_POSITION
        ) / mt_getrandmax();
        $report->invalidClicks = mt_rand(
            self::MIN_INVALID_CLICKS,
            self::MAX_INVALID_CLICKS
        );
        $report->invalidClickRate = mt_rand(
            self::MIN_INVALID_CLICK_RATE,
            self::MAX_INVALID_CLICK_RATE
        ) / mt_getrandmax();
        $report->impressionShare = mt_rand(
            self::MIN_IMPRESSION_SHARE,
            self::MAX_IMPRESSION_SHARE
        ) / mt_getrandmax();
        $report->exactMatchImpressionShare = mt_rand(
            self::MIN_EXACT_MATCH_IMPRESSION_SHARE,
            self::MAX_EXACT_MATCH_IMPRESSION_SHARE
        ) / mt_getrandmax();
        $report->budgetLostImpressionShare = mt_rand(
            self::MIN_BUDGET_LOST_IMPRESSION_SHARE,
            self::MAX_BUDGET_LOST_IMPRESSION_SHARE
        ) / mt_getrandmax();
        $report->qualityLostImpressionShare = mt_rand(
            self::MIN_QUALITY_LOST_IMPRESSION_SHARE,
            self::MAX_QUALITY_LOST_IMPRESSION_SHARE
        ) / mt_getrandmax();
        $report->trackingURL = self::TRACKING_URL;
        $report->conversions = mt_rand(
            self::MIN_CONVERSIONS,
            self::MAX_CONVERSIONS
        ) / mt_getrandmax();
        $report->convRate = mt_rand(
            self::MIN_CONV_RATE,
            self::MAX_CONV_RATE
        ) / mt_getrandmax();
        $report->convValue = mt_rand(
            self::MIN_CONV_VALUE,
            self::MAX_CONV_VALUE
        ) / mt_getrandmax();
        $report->costPerConv = mt_rand(
            self::MIN_COST_PER_CONV,
            self::MAX_COST_PER_CONV
        ) / mt_getrandmax();
        $report->valuePerConv = mt_rand(
            self::MIN_VALUE_PER_CONV,
            self::MAX_VALUE_PER_CONV
        ) / mt_getrandmax();
        $report->allConv = mt_rand(
            self::MIN_ALL_CONV,
            self::MAX_ALL_CONV
        ) / mt_getrandmax();
        $report->allConvRate = mt_rand(
            self::MIN_ALL_CONV_RATE,
            self::MAX_ALL_CONV_RATE
        ) / mt_getrandmax();
        $report->allConvValue = mt_rand(
            self::MIN_ALL_CONV_VALUE,
            self::MAX_ALL_CONV_VALUE
        ) / mt_getrandmax();
        $report->costPerAllConv = mt_rand(
            self::MIN_COST_PER_ALL_CONV,
            self::MAX_COST_PER_ALL_CONV
        ) / mt_getrandmax();
        $report->valuePerAllConv = mt_rand(
            self::MIN_VALUE_PER_ALL_CONV,
            self::MAX_VALUE_PER_ALL_CONV
        ) / mt_getrandmax();
        $report->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
        $report->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
        $report->day = $day;
        $report->dayOfWeek = $day->format('l');
        $report->quarter = (int)ceil((int)$day->format('n') / 3);
        $report->month = $day->format('F');
        $report->week = $day->format('W');

        $report->saveOrFail();
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
            $this->processDay($date);
        }
    }
}
