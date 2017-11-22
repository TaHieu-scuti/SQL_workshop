<?php

use Illuminate\Database\Seeder;
use App\Model\RepoYdnReport;
use App\Model\RepoYdnAccount;

// @codingStandardsIgnoreLine
class RepoYdwAccountReportGenerator extends Seeder
{
    const START_DATE = '2017-01-01 00:00:00';
    const INTERVAL = 'P1D';
    const END_DATE = '2018-02-03 00:00:00';
    const NUMBER_OF_ACCOUNTS = 2;
    const NUMBER_OF_MEDIA_ACCOUNTS = [
        2,
        4,
        5,
        1,
        3
    ];
    const MIN_NUMBER_OF_CAMPAIGNS = 1;
    const MAX_NUMBER_OF_CAMPAIGNS = 12;
    const MIN_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN = 0;
    const MAX_NUMBER_OF_REPORTS_PER_DAY_PER_CAMPAIGN = 5;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 1;
    const MAX_IMPRESSIONS = 4096;
    const MIN_CLICKS = 1;
    const MAX_CLICKS = 9001;
    const MIN_AVERAGE_POSITION = 1000000;
    const MAX_AVERAGE_POSITION = 89489437437880;
    const MIN_CONVERSIONS = 1000000;
    const MAX_CONVERSIONS = 89489437437880;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 89489437437880;
    const NETWORKS = ['network1', 'network2', 'network3'];
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];

    private function processDay(DateTime $day)
    {
        for ($i = 0; $i < self::NUMBER_OF_ACCOUNTS; ++$i) {
            $this->processAGAccount($day, $i);
        }
    }

    private function processAGAccount(DateTime $day, $agAccountNumber)
    {
        $numberOfMediaAccounts = self::NUMBER_OF_MEDIA_ACCOUNTS[$agAccountNumber];

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
        $costReport = new RepoAdwAccountReportCost;

        $costReport->account_id = $agAccountNumber + 1;

        $costReport->account = 'Account'.($mediaAccountNumber + 1);

        $costReport->cost = mt_rand(
            self::MIN_COST,
            self::MAX_COST
        );

        $costReport->clicks = mt_rand(
            self::MIN_CLICKS,
            self::MAX_CLICKS
        );

        $costReport->avgCPC = $costReport->cost / $costReport->clicks;

        $costReport->avgPosition = mt_rand(
                self::MIN_AVERAGE_POSITION,
                self::MAX_AVERAGE_POSITION
            ) / mt_getrandmax();

        $costReport->conversions = mt_rand(
                self::MIN_CONVERSIONS,
                self::MAX_CONVERSIONS
            ) / mt_getrandmax();

        $costReport->impressions = mt_rand(
            self::MIN_IMPRESSIONS,
            self::MAX_IMPRESSIONS
        );

        $costReport->ctr = ($costReport->clicks / $costReport->impressions) * 100;

        $costReport->valueConv = mt_rand(
                self::MIN_CONV_VALUE,
                self::MAX_CONV_VALUE
            ) / mt_getrandmax();

        $costReport->accountid = $mediaAccountNumber + 1;

        $costReport->campaign_id = $campaignNumber + 1;

        $costReport->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];

        $costReport->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];

        $costReport->day = $day;

        $costReport->dayOfWeek = $day->format('l');

        $costReport->quarter = $day->format('Y-m-d');

        $costReport->month = $day->format('Y-m-d');

        $costReport->week = $day->format('W');

        $costReport->exeDate = $day->format('Y-m-d');

        $costReport->startDate = $day->format('Y-m-d');

        $costReport->endDate = $day->format('Y-m-d');

        $costReport->saveOrFail();
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
