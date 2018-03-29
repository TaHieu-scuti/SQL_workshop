<?php

use Illuminate\Database\Seeder;

// @codingStandardsIgnoreLine
class ResourceGenerator extends Seeder
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

        $this->call(RepoYssAccountReportCoGenerator::class);
        $this->call(RepoYssAccounts::class);
        $this->call(RepoYssCampaignReportGenerator::class);
        $this->call(RepoYssAdgroupReportGenerator::class);
        $this->call(RepoYssAdReportGenerator::class);
        $this->call(RepoYssKeywordReportGenerator::class);
        $this->call(RepoYssPrefectureReportGenerator::class);
        $this->call(DayOfWeeksGenerator::class);

        $this->call(RepoAdwAccountReportGenerator::class);
        $this->call(RepoAdwCampaignReportCostGenerator::class);
        $this->call(RepoAdwAdgroupReportGenerator::class);
        $this->call(RepoAdwAdReportGenerator::class);
        $this->call(RepoAdwKeywordReportGenerator::class);
        $this->call(RepoAdwGeoReportGenerator::class);

        $this->call(RepoYdnAccountGenerator::class);
        $this->call(RepoYdnReportGenerator::class);

        $this->call(CampaignsGenerator::class);
        $this->call(PhoneTimeUseGenerator::class);
        $this->call(AccountGenerator::class);
    }
}
