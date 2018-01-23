<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class ChangeDateTimeTypeOfRepoYssAdgroupReportCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repo_yss_adgroup_report_cost', function (Blueprint $table) {
            $table->dateTime('day')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repo_yss_adgroup_report_cost', function (Blueprint $table) {
            $table->date('day')->nullable()->change();
        });
    }
}
