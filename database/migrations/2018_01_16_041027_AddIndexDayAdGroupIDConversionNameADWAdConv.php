<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddIndexDayAdGroupIDConversionNameADWAdConv extends Migration
{
    const TABLE_NAME = 'repo_adw_ad_report_conv';
    const INDEX_NAME = 'repo_adw_ad_report_conv_day_adGroupID_conversionName';

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
                $table->index(['day', 'adGroupID', 'conversionName'], self::INDEX_NAME);
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
