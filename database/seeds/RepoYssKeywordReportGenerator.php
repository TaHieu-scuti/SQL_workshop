<?php

use Illuminate\Database\Seeder;

use App\Model\RepoYssAdgroupReportCost;
use App\Model\RepoYssAdgroupReportConv;

class RepoYssKeywordReportGenerator extends Seeder
{
    const MIN_NUMBER_OF_KEYWORD = 1;
    const MAX_NUMBER_OF_KEYWORD = 2;
    const CUSTOM_URL = 'Custom URL ';
    const KEYWORD = 'Keyword ';
    const KEYWORD_DISTRIBUTION_SETTINGS = 'Keyword distribution settings';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adgroupReports = RepoYssAdgroupReportCost::all();
        foreach ($adgroupReports as $adgroupReport) {
            $ammountOfKeyword = rand(
                self::MIN_NUMBER_OF_KEYWORD,
                self::MAX_NUMBER_OF_KEYWORD
            );
            for($i = 0; $i < $ammountOfKeyword + 1; $i++) {
                $keywordReportCost = new RepoYssKeywordReportCost;
                $keywordReportConv = new RepoYssKeywordReportConv;
                $keywordReportCost->exeDate = $adgroupReport->exeDate;
                $keywordReportConv->exeDate = $adgroupReport->exeDate;
                $keywordReportCost->startDate = $adgroupReport->startDate;
                $keywordReportConv->startDate = $adgroupReport->startDate;
                $keywordReportCost->endDate = $adgroupReport->endDate;
                $keywordReportConv->endDate = $adgroupReport->endDate;

                $keywordReportCost->account_id = $adgroupReport->account_id;
                $keywordReportCost->campaign_id = $adgroupReport->campaign_id;
                $keywordReportCost->campaignID = $adgroupReport->campaignID;
                $keywordReportCost->adgroupID = $adgroupReport->adgroupID;
                $keywordReportCost->keywordID = $i;
                $keywordReportCost->campaignName = $adgroupReport->campaignName;
                $keywordReportCost->adgroupName = $adgroupReport->adgroupName;
                $keywordReportCost->customURL = self::CUSTOM_URL . $i;
                $keywordReportCost->keyword = self::KEYWORD . $i;
                $keywordReportCost->keywordDistributionSettings = self::KEYWORD_DISTRIBUTION_SETTINGS;
            }
        }
    }
}
