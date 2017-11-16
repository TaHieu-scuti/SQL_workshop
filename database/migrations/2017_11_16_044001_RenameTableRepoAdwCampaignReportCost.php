<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class RenameTableRepoAdwCampaignReportCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_adw_campaign_report_costs',
            function (Blueprint $table) {
                $table->rename('repo_adw_campaign_report_cost');
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
            'repo_adw_campaign_report_cost',
            function (Blueprint $table) {
                $table->rename('repo_adw_campaign_report_costs');
            }
        );
    }
}
