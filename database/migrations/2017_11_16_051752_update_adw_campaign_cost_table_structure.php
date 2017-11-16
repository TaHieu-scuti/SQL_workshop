<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAdwCampaignCostTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_adw_campaign_report_cost',
            function (Blueprint $table) {
                $table->string('timeZone', 50)
                    ->nullable()
                    ->change();
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
                $table->string('timeZone', 50)
                    ->change();
            }
        );
    }
}
