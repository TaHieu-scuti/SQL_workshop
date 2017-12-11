<?php

use Illuminate\Database\Seeder;
use App\Model\RepoYssKeywordReportCost;
use App\Model\RepoYssSearchqueryReportCost;

class RepoYssSearchQueryPostCostGenerator extends Seeder
{
    const SEARCH_QUERY = [
        'SEARCH QUERY 1', 'SEARCH QUERY 2',
        'SEARCH QUERY 3', 'SEARCH QUERY 4'
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yssKeywords = RepoYssKeywordReportCost::where('day', '>=', '2017-07-01')->get();
        foreach ($yssKeywords as $yssKeyword) {
            $yssSearchQuery = new RepoYssSearchqueryReportCost;
            $yssSearchQuery->exeDate = $yssKeyword->exeDate;
            $yssSearchQuery->startDate = $yssKeyword->startDate;
            $yssSearchQuery->endDate = $yssKeyword->endDate;
            $yssSearchQuery->account_id = $yssKeyword->account_id;
            $yssSearchQuery->campaign_id = $yssKeyword->campaign_id;
            $yssSearchQuery->campaignID = $yssKeyword->campaignID;
            $yssSearchQuery->adgroupID = $yssKeyword->adgroupID;
            $yssSearchQuery->keywordID = $yssKeyword->keywordID;
            $yssSearchQuery->campaignName = $yssKeyword->campaignName;
            $yssSearchQuery->adgroupName = $yssKeyword->adgroupName;
            $yssSearchQuery->searchQuery = self::SEARCH_QUERY[mt_rand(0, count(self::SEARCH_QUERY) - 1)];
            $yssSearchQuery->keyword = $yssKeyword->keyword;
            $yssSearchQuery->cost = $yssKeyword->cost;
            $yssSearchQuery->impressions = $yssKeyword->impressions;
            $yssSearchQuery->clicks = $yssKeyword->clicks;
            $yssSearchQuery->ctr = $yssKeyword->ctr;
            $yssSearchQuery->averageCpc = $yssKeyword->averageCpc;
            $yssSearchQuery->averagePosition = $yssKeyword->averagePosition;
            $yssSearchQuery->conversions = $yssKeyword->conversions;
            $yssSearchQuery->convRate = $yssKeyword->convRate;
            $yssSearchQuery->convValue = $yssKeyword->convValue;
            $yssSearchQuery->costPerConv = $yssKeyword->costPerConv;
            $yssSearchQuery->valuePerConv = $yssKeyword->valuePerConv;
            $yssSearchQuery->allConv = $yssKeyword->allConv;
            $yssSearchQuery->allConvRate = $yssKeyword->allConvRate;
            $yssSearchQuery->allConvValue = $yssKeyword->allConvValue;
            $yssSearchQuery->costPerAllConv = $yssKeyword->costPerAllConv;
            $yssSearchQuery->valuePerAllConv = $yssKeyword->valuePerAllConv;
            $yssSearchQuery->device = $yssKeyword->device;
            $yssSearchQuery->day = $yssKeyword->day;
            $yssSearchQuery->dayOfWeek = $yssKeyword->dayOfWeek;
            $yssSearchQuery->quarter = $yssKeyword->quarter;
            $yssSearchQuery->month = $yssKeyword->month;
            $yssSearchQuery->week = $yssKeyword->week;
            $yssSearchQuery->accountid = $yssKeyword->accountid;
            $yssSearchQuery->saveOrFail();
        }
    }
}
