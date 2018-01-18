<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddIndexCampaignIDDayADWAdGroupCost extends Migration
{
    const TABLE_NAME = 'repo_adw_adgroup_report_cost';
    const INDEX_NAME = 'repo_adw_adgroup_report_cost_campaignID_day';

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
                $table->index(['campaignID', 'day'], self::INDEX_NAME);
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
