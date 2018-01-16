<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddIndexesCustomerIdNetworkOnRepoAdwCampaiginCost extends Migration
{
    const INDEX_NAME = 'repo_yss_Campaign_report_cost_network_customerID_idx';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "CREATE INDEX `" . self::INDEX_NAME . "` "
            . "ON `repo_adw_campaign_report_cost` (network, customerID) "
            . "COMMENT '' "
            . "ALGORITHM DEFAULT "
            . "LOCK DEFAULT;"
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
            'repo_adw_campaign_report_cost',
            function (Blueprint $table) {
                $table->dropIndex(self::INDEX_NAME);
            }
        );
    }
}
