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
        $this->call(UsersTableSeeder::class);
        $this->call(CriteriaTableSeeder::class);

        $this->call(RepoYssAccountsTable::class);
        $this->call(RepoYssAccountReportsCostTable::class);
        $this->call(RepoYssAccountReportsConvTable::class);
        $this->call(RepoYssCampaignReportConvsTable::class);
        $this->call(RepoYssCampaignReportCostsTable::class);
        $this->call(RepoYssAdgroupReportCostTable::class);
        $this->call(RepoYssAdgroupReportConvTable::class);
        $this->call(RepoYssAdReportConvTable::class);
        $this->call(RepoYssAdReportCostTable::class);
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

        $this->call(CampaignsGenerator::class);
        $this->call(PhoneTimeUseTable::class);
    }
}
