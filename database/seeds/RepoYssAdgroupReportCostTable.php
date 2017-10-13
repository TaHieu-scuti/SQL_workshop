<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepoYssAdgroupReportCostTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(
            file_get_contents(__DIR__ . '/../../database/resources/repo_yss_adgroup_report_cost.sql')
        );
    }
}
