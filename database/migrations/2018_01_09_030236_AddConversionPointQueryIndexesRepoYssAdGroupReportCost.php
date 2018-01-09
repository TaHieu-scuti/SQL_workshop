<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddConversionPointQueryIndexesRepoYssAdGroupReportCost extends Migration
{
    const TABLE_NAME = 'repo_yss_adgroup_report_cost';
    const INDEX_NAME = 'yss_adgroup_report_cost_conversion_points_idx';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            self::TABLE_NAME,
            function (Blueprint $table) {
                $table->index(['campaignID', 'accountid', 'day'], self::INDEX_NAME);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            self::TABLE_NAME,
            function (Blueprint $table) {
                $table->dropIndex(self::INDEX_NAME);
            }
        );
    }
}
