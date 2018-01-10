<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddConversionPointIndexOnRepoAdwCampaignReportConv extends Migration
{
    const TABLE_NAME = 'repo_adw_campaign_report_conv';
    const INDEX_NAME = 'repo_adw_campaign_report_conv_conversion_points';

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
                $table->index(
                    ['day', 'customerID', 'campaignID', 'conversionName'],
                    self::INDEX_NAME
                );
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
