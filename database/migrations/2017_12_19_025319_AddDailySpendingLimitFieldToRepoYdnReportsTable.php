<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailySpendingLimitFieldToRepoYdnReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_ydn_reports',
            function (Blueprint $table) {
                $table->integer('dailySpendingLimit')
                    ->nullable()
                    ->comment('1日の予算');
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
            'repo_ydn_reports',
            function (Blueprint $table) {
                $table->dropColumn('dailySpendingLimit');
            }
        );
    }
}
