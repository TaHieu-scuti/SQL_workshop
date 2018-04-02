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
        $this->call(AccountGenerator::class);
        $this->call(CriteriaTableSeeder::class);
        $this->call(SeedAdgainerAccount::class);

        $this->call(RepoYssAccountReportCoGenerator::class);
        $this->call(RepoYssAccounts::class);
        $this->call(RepoYssCampaignReportGenerator::class);
        $this->call(RepoYssAdgroupReportGenerator::class);
        $this->call(RepoYssKeywordReportGenerator::class);
        $this->call(RepoYssPrefectureReportGenerator::class);
        $this->call(RepoYssPrefectureReportConvGenerator::class);
        $this->call(DayOfWeeksGenerator::class);
        $this->call(RepoYssSearchQueryPostCostGenerator::class);

        $this->call(RepoAdwAccountReportGenerator::class);
        $this->call(RepoAdwAccountConvTableGenerator::class);
        $this->call(RepoAdwCampaignReportCostGenerator::class);
        $this->call(RepoAdwCampaignReportConvGenerator::class);
        $this->call(RepoAdwAdgroupReportGenerator::class);
        $this->call(RepoAdwAdgroupConvTableGenerator::class);
        $this->call(RepoAdwAdReportGenerator::class);
        $this->call(RepoAdwAdReportConvTableGenerator::class);
        $this->call(RepoAdwKeywordReportGenerator::class);
        $this->call(RepoAdwKeywordReportConvGenerator::class);
        $this->call(RepoAdwGeoReportGenerator::class);
        $this->call(RepoAdwGeoReportConvTableGenerator::class);
        $this->call(RepoAdwDisplayKeywordReportCostGenerator::class);
        $this->call(RepoAdwSearchQueryPerformanceReportGenerator::class);
        $this->call(RepoAdwSearchQueryPerformanceReportConvGenerator::class);


        $this->call(RepoYdnAccountGenerator::class);
        $this->call(RepoYdnReportGenerator::class);

        $this->call(CampaignsGenerator::class);
        $this->call(PhoneTimeUseGenerator::class);
        $this->call(RepoPhoneTimeUseGenerator::class);
    }
}
