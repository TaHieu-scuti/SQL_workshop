<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountidColumnYssCampaignReportConv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_yss_campaign_report_convs',
            function (Blueprint $table) {
                $table->bigInteger('accountid');
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
            'repo_yss_campaign_report_convs',
            function (Blueprint $table) {
                $table->dropColumn('accountid');
            }
        );
    }
}
