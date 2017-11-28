<?php

use Illuminate\Database\Seeder;

use App\Model\RepoAdwAccountReportCost;
use App\Model\RepoAdwCampaignReportCost;
use App\Model\RepoAdwAdgroupReportCost;

// @codingStandardsIgnoreLine
class RepoAdwAdgroupReportGenerator extends Seeder
{
    const MIN_NUMBER_OF_ADGROUP = 1;
    const MAX_NUMBER_OF_ADGROUP = 2;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 1;
    const MAX_IMPRESSIONS = 4096;
    const MIN_CLICKS = 1;
    const MAX_CLICKS = 9001;
    const MIN_CONV_RATE = 1000000;
    const MAX_CONV_RATE = 894894374;
    const MIN_AVERAGE_POSITION = 1000000;
    const MAX_AVERAGE_POSITION = 894894374;
    const MIN_CONVERSIONS = 1000000;
    const MAX_CONVERSIONS = 894894374;
    const MIN_ALL_CONV_VALUE = 1000000;
    const MAX_ALL_CONV_VALUE = 894894374;
    const NETWORKS = ['network1', 'network2', 'network3'];
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaignReports = RepoAdwCampaignReportCost::all();
        foreach ($campaignReports as $campaignReport) {
            $ammountOfAdgroup = rand(
                self::MIN_NUMBER_OF_ADGROUP,
                self::MAX_NUMBER_OF_ADGROUP
            );
            for ($i = 0; $i < $ammountOfAdgroup + 1; $i++) {
                $adgroupReportCost = new RepoAdwAdgroupReportCost;
                $adgroupReportCost->exeDate = $campaignReport->exeDate;
                $adgroupReportCost->startDate = $campaignReport->startDate;
                $adgroupReportCost->endDate = $campaignReport->endDate;
                $adgroupReportCost->account_id = $campaignReport->account_id;
                $adgroupReportCost->campaign_id = $campaignReport->campaign_id;
                $adgroupReportCost->account = $campaignReport->account;
                $adgroupReportCost->adGroupID = $i;
                $adgroupReportCost->adGroup = 'ADW Adgroup Name ' . $i;
                $adgroupReportCost->campaignID = $campaignReport->campaignID;
                $adgroupReportCost->campaign = $campaignReport->campaign;
                $adgroupReportCost->cost = mt_rand(
                    self::MIN_COST,
                    self::MAX_COST
                );
                $adgroupReportCost->impressions = mt_rand(
                    self::MIN_IMPRESSIONS,
                    self::MAX_IMPRESSIONS
                );
                $adgroupReportCost->clicks = mt_rand(
                    self::MIN_CLICKS,
                    self::MAX_CLICKS
                );
                $adgroupReportCost->ctr = ($adgroupReportCost->clicks / $adgroupReportCost->impressions) * 100;
                $adgroupReportCost->avgCPC = $adgroupReportCost->cost / $adgroupReportCost->clicks;
                $adgroupReportCost->avgPosition = mt_rand(
                    self::MIN_AVERAGE_POSITION,
                    self::MAX_AVERAGE_POSITION
                ) / mt_getrandmax();
                $adgroupReportCost->conversions = mt_rand(
                    self::MIN_CONVERSIONS,
                    self::MAX_CONVERSIONS
                ) / mt_getrandmax();
                $adgroupReportCost->convRate = mt_rand(
                    self::MIN_CONV_RATE,
                    self::MAX_CONV_RATE
                ) / mt_getrandmax();
                $adgroupReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                $adgroupReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                $adgroupReportCost->day = $campaignReport->day;
                $adgroupReportCost->dayOfWeek = $campaignReport->dayOfWeek;
                $adgroupReportCost->quarter = $campaignReport->quarter;
                $adgroupReportCost->month = $campaignReport->month;
                $adgroupReportCost->week = $campaignReport->week;
                $adgroupReportCost->hourofday = $campaignReport->hourofday;
                $adgroupReportCost->totalConvValue = mt_rand(
                    self::MIN_ALL_CONV_VALUE,
                    self::MAX_ALL_CONV_VALUE
                );
                $adgroupReportCost->accountid = $campaignReport->accountid;
                $adgroupReportCost->saveOrFail();
            }
        }
    }
}
