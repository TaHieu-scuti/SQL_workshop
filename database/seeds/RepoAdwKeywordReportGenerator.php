<?php

use Illuminate\Database\Seeder;
use App\Model\RepoAdwAdgroupReportCost;
use App\Model\RepoAdwKeywordReportCost;

// @codingStandardsIgnoreLine
class RepoAdwKeywordReportGenerator extends Seeder
{
    const MIN_NUMBER_OF_KEYWORD = 1;
    const MAX_NUMBER_OF_KEYWORD = 2;
    const KEYWORD = 'Keyword ';
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
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];
    const CLICK_TYPE = [
        'Click Type 1', 'Click Type 2',
        'Click Type 3', 'Click Type 4'
    ];
    const MATCH_TYPE = 'Match type';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adgroupReports = RepoAdwAdgroupReportCost::select()
            ->where('network', 'LIKE', 'SEARCH')
            ->get();

        foreach ($adgroupReports as $adgroupReport) {
            $ammountOfKeyword = rand(
                self::MIN_NUMBER_OF_KEYWORD,
                self::MAX_NUMBER_OF_KEYWORD
            );
            for ($i = 0; $i < $ammountOfKeyword + 1; $i++) {
                $keywordReportCost = new RepoAdwKeywordReportCost;
                $keywordReportCost->exeDate = $adgroupReport->exeDate;
                $keywordReportCost->startDate = $adgroupReport->startDate;
                $keywordReportCost->endDate = $adgroupReport->endDate;
                $keywordReportCost->account_id = $adgroupReport->account_id;
                $keywordReportCost->account = $adgroupReport->account;
                $keywordReportCost->campaign_id = $adgroupReport->campaign_id;
                $keywordReportCost->adGroupID = $adgroupReport->adGroupID;
                $keywordReportCost->adGroup = $adgroupReport->adGroup;
                $keywordReportCost->campaignID = $adgroupReport->campaignID;
                $keywordReportCost->campaign = $adgroupReport->campaign;
                $keywordReportCost->keywordID = $i + 1;
                $keywordReportCost->keyword = self::KEYWORD . ($i + 1);
                $keywordReportCost->cost = mt_rand(
                    self::MIN_COST,
                    self::MAX_COST
                );
                $keywordReportCost->impressions = mt_rand(
                    self::MIN_IMPRESSIONS,
                    $adgroupReport->impressions
                );
                $keywordReportCost->clicks = mt_rand(
                    self::MIN_CLICKS,
                    $keywordReportCost->impressions
                );

                if ($keywordReportCost->impressions === 0) {
                    $keywordReportCost->ctr = 0;
                } else {
                    $keywordReportCost->ctr = ($keywordReportCost->clicks / $keywordReportCost->impressions) * 100;
                }

                if ($keywordReportCost->clicks === 0) {
                    $keywordReportCost->avgCPC = 0;
                } else {
                    $keywordReportCost->avgCPC = $keywordReportCost->cost / $keywordReportCost->clicks;
                }

                $keywordReportCost->avgPosition = mt_rand(
                    self::MIN_AVERAGE_POSITION * 100000,
                    self::MAX_AVERAGE_POSITION * 100000
                ) / 100000;

                $keywordReportCost->conversions = mt_rand(
                    self::MIN_CONVERSIONS,
                    $keywordReportCost->clicks
                );

                if ($keywordReportCost->clicks === 0) {
                    $keywordReportCost->convRate = 0;
                } else {
                    $keywordReportCost->convRate = ($keywordReportCost->conversions / $keywordReportCost->clicks) * 100;
                }

                $keywordReportCost->valueConv = mt_rand(
                    self::MIN_CONV_VALUE,
                    self::MAX_CONV_VALUE
                ) / mt_getrandmax();
                $keywordReportCost->allConv = mt_rand(
                    self::MIN_ALL_CONV,
                    self::MAX_ALL_CONV
                ) / mt_getrandmax();
                $keywordReportCost->allConvRate = mt_rand(
                    self::MIN_ALL_CONV_RATE,
                    self::MAX_ALL_CONV_RATE
                ) / mt_getrandmax();
                $keywordReportCost->allConvValue = mt_rand(
                    self::MIN_ALL_CONV_VALUE,
                    self::MAX_ALL_CONV_VALUE
                ) / mt_getrandmax();
                $keywordReportCost->valueAllConv = mt_rand(
                    self::MIN_VALUE_ALL_CONV,
                    self::MAX_VALUE_ALL_CONV
                ) / mt_getrandmax();
                $keywordReportCost->valueAllConv = mt_rand(
                    self::MIN_TOTAL_CONV_VALUE,
                    self::MAX_TOTAL_CONV_VALUE
                ) / mt_getrandmax();
                $keywordReportCost->network = $adgroupReport->network;
                $keywordReportCost->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
                $keywordReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                $keywordReportCost->day = $adgroupReport->day;
                $keywordReportCost->dayOfWeek = $adgroupReport->dayOfWeek;
                $keywordReportCost->quarter = $adgroupReport->quarter;
                $keywordReportCost->month = $adgroupReport->month;
                $keywordReportCost->week = $adgroupReport->week;
                $keywordReportCost->accountid = $adgroupReport->accountid;
                $keywordReportCost->matchType = self::MATCH_TYPE;

                $keywordReportCost->saveOrFail();
            }
        }
    }
}
