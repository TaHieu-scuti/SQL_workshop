<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddAveragePositionPrefectureCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_yss_prefecture_report_cost',
            function (Blueprint $table) {
                $table->double('averagePosition')->nullable()->comment('平均Position');
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
        if (Schema::hasColumn('repo_yss_prefecture_report_cost', 'averagePosition')) {
            Schema::table(
                'repo_yss_prefecture_report_cost',
                function (Blueprint $table) {
                    $table->dropColumn('averagePosition');
                }
            );
        }
    }
}
