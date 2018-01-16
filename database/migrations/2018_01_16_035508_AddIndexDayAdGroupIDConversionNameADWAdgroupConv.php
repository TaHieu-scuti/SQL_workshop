<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddIndexDayAdGroupIDConversionNameADWAdgroupConv extends Migration
{
    const TABLE_NAME = 'repo_adw_adgroup_report_conv';
    const INDEX_NAME = 'adw_adgroup_conv_day_adGroupID_conversionName';
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
                $table->index(['day', 'campaignID', 'conversionName'], self::INDEX_NAME);
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
