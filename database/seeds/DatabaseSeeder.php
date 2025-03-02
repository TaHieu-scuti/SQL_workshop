<?php

use Illuminate\Database\Seeder;

// @codingStandardsIgnoreLine
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AccountsTableSeeder::class);
        $this->call(CriteriaTableSeeder::class);

        $this->call(RepoYssAccountsTable::class);
        $this->call(RepoYssAccountReportsCostTable::class);
        $this->call(RepoYssAccountReportsConvTable::class);
        $this->call(RepoYssCampaignReportConvsTable::class);
        $this->call(RepoYssCampaignReportCostsTable::class);
        $this->call(RepoYssAdgroupReportCostTable::class);
        $this->call(RepoYssAdgroupReportConvTable::class);
        $this->call(RepoYssKeywordReportCostTable::class);
        $this->call(RepoYssKeywordReportConvTable::class);
        $this->call(RepoYssPrefectureReportCostTable::class);
        $this->call(RepoYssDayOfWeekReport::class);

        $this->call(RepoAdwAccountReportCostTable::class);
        $this->call(RepoAdwCampaignReportCostTable::class);
        $this->call(RepoAdwAdgroupReportCostTable::class);
        $this->call(RepoAdwKeywordReportCostTable::class);
        $this->call(RepoAdwAdReportTable::class);
        $this->call(RepoAdwGeoReportCostTable::class);

        $this->call(RepoYdnReportsTable::class);
        $this->call(RepoYdnAccountsTable::class);

        $this->call(CampaignsSeeder::class);
        $this->call(PhoneTimeUseTable::class);
        $this->call(RepoPhoneTimeUseGenerator::class);
        $this->call(RepoAdwDisplayKeywordReportCost::class);
        $this->call(RepoYssSearchQueryPostCost::class);
        $this->call(RepoYssSearchQueryConv::class);
        $this->call(RepoAdwSearchQueryPerformanceReportCost::class);
        $this->call(RepoAdwAccountConvTableSeeder::class);
        $this->call(RepoAdwCampaignReportConvTable::class);
        $this->call(RepoAdwAdgroupConvTableSeeder::class);
        $this->call(RepoAdwKeywordReportConvTable::class);
        $this->call(RepoAdwGeoReportConvTableSeeder::class);
        $this->call(RepoAdwAdReportConvTableSeeder::class);
        $this->call(RepoAdwSearchQueryPerformanceReportConvTable::class);
    }
}
