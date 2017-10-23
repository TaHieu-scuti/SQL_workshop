<?php

use Illuminate\Database\Seeder;

// @codingStandardsIgnoreLine
class RepoYssAdReportConvTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(
            file_get_contents(__DIR__ . '/../../database/resources/repo_yss_ad_report_conv.sql')
        );
    }
}
