<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddDateColumnsYssDayofweekReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_yss_dayofweek_report',
            function (Blueprint $table) {
                $table->date('exeDate');
                $table->date('startDate');
                $table->date('endDate');
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
            'repo_yss_dayofweek_report',
            function (Blueprint $table) {
                $table->dropIfExists('exeDate');
                $table->dropIfExists('startDate');
                $table->dropIfExists('endDate');
            }
        );
    }
}
