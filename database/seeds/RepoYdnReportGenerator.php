<?php

use Illuminate\Database\Seeder;
use App\Model\RepoYdnAccount;
use App\Model\RepoYdnReport;

// @codingStandardsIgnoreLine
class RepoYdnReportGenerator extends Seeder
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
    const MAX_NUMBER_OF_CAMPAIGNS = 5;
    const MIN_NUMBER_OF_ADGROUP = 1;
    const MAX_NUMBER_OF_ADGROUP = 5;
    const MIN_NUMBER_OF_AD_REPORT = 1;
    const MAX_NUMBER_OF_AD_REPORT = 5;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 1;
    const MAX_IMPRESSIONS = 4096;
    const MIN_CLICKS = 1;
    const MAX_CLICKS = 9001;
    const MIN_AVERAGE_POSITION = 1000000;
    const MAX_AVERAGE_POSITION = 89489437437880;
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];

    private function processDay(DateTime $day)
    {
        $numberOfCampaigns = rand(
            self::MIN_NUMBER_OF_CAMPAIGNS,
            self::MAX_NUMBER_OF_CAMPAIGNS
        );

        for ($i = 0; $i < $numberOfCampaigns + 1; ++$i) {
            $this->processCampaign($day, $i);
        }
    }

    private function processCampaign(DateTime $day, $campaignNumber)
    {
        $numberOfReports = rand(
            self::MIN_NUMBER_OF_ADGROUP,
            self::MAX_NUMBER_OF_ADGROUP
        );

        for ($i = 0; $i < $numberOfReports + 1; ++$i) {
            $this->processAdGroup($day, $campaignNumber, $i);
        }
    }

    private function processAdGroup(DateTime $day, $campaignNumber, $adGroupNumber)
    {
        $numberOfReports = rand(
            self::MIN_NUMBER_OF_AD_REPORT,
            self::MAX_NUMBER_OF_AD_REPORT
        );

        for ($i = 0; $i < $numberOfReports + 1; ++$i) {
            $this->createReport($day, $campaignNumber, $adGroupNumber, $i);
        }
    }

    private function createReport(DateTime $day, $campaignNumber, $adGroupNumber, $adReportNumber)
    {
        $repoYdnAccounts = RepoYdnAccount::select('account_id', 'accountId', 'accountName')->get();
        foreach ($repoYdnAccounts as $account) {
            $costReport = new RepoYdnReport();

            $costReport->account_id = $account->account_id;

            $costReport->accountName = $account->accountName;

            $costReport->cost = mt_rand(
                self::MIN_COST,
                self::MAX_COST
            );

            $costReport->clicks = mt_rand(
                self::MIN_CLICKS,
                self::MAX_CLICKS
            );

            $costReport->averageCpc = $costReport->cost / $costReport->clicks;

            $costReport->averagePosition = mt_rand(
                self::MIN_AVERAGE_POSITION,
                self::MAX_AVERAGE_POSITION
            ) / mt_getrandmax();

            $costReport->impressions = mt_rand(
                self::MIN_IMPRESSIONS,
                self::MAX_IMPRESSIONS
            );

            $costReport->ctr = ($costReport->clicks / $costReport->impressions) * 100;

            $costReport->accountid = $account->accountId;

            $costReport->mediaID = $account->accountId;

            $costReport->mediaName = 'YDN ' . str_random(10);

            $costReport->campaign_id = ($costReport->account_id * 10) + $campaignNumber + 1;

            $costReport->campaignID = $campaignNumber + 1;

            $costReport->campaignName = 'YDN Campaign ' . ($campaignNumber + 1);

            $costReport->adgroupID = $adGroupNumber + 1;

            $costReport->adgroupName = 'YDN AdGroup ' . ($adGroupNumber + 1);

            $costReport->adID = $adReportNumber + 1;

            $costReport->adName = 'YDN Ad Report ' . ($adReportNumber + 1);

            $costReport->prefectureID = $adReportNumber + 1;

            $costReport->prefecture = 'Prefecture ' . ($adReportNumber + 1);

            $costReport->hourofday = rand(0, 23);

            $costReport->searchKeywordID = $adReportNumber + 1;

            $costReport->searchKeyword = 'Keyword ' . ($adReportNumber + 1);

            $costReport->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];

            $costReport->day = $day;

            $costReport->exeDate = $day->format('Y-m-d');

            $costReport->startDate = $day->format('Y-m-d');

            $costReport->endDate = $day->format('Y-m-d');

            $costReport->saveOrFail();
        }
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
