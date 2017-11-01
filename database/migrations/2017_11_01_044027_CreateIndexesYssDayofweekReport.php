<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateIndexesYssDayofweekReport extends Migration
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
                $table->index('account_id', 'repo_yss_dayofweek_report_idx1');
                $table->index('campaign_id', 'repo_yss_dayofweek_report_idx2');
                $table->index('campaignID', 'repo_yss_dayofweek_report_idx3');
                $table->index('day', 'repo_yss_dayofweek_report_idx4');
                $table->index('quarter', 'repo_yss_dayofweek_report_idx5');
                $table->index('month', 'repo_yss_dayofweek_report_idx6');
                $table->index('week', 'repo_yss_dayofweek_report_idx7');
                $table->index('exeDate', 'repo_yss_dayofweek_report_idx8');
                $table->index('startDate', 'repo_yss_dayofweek_report_idx9');
                $table->index('endDate', 'repo_yss_dayofweek_report_idx10');
                $table->index('accountid', 'repo_yss_dayofweek_report_idx11');
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
                $table->dropIndex('repo_yss_dayofweek_report_idx1');
                $table->dropIndex('repo_yss_dayofweek_report_idx2');
                $table->dropIndex('repo_yss_dayofweek_report_idx3');
                $table->dropIndex('repo_yss_dayofweek_report_idx4');
                $table->dropIndex('repo_yss_dayofweek_report_idx5');
                $table->dropIndex('repo_yss_dayofweek_report_idx6');
                $table->dropIndex('repo_yss_dayofweek_report_idx7');
                $table->dropIndex('repo_yss_dayofweek_report_idx8');
                $table->dropIndex('repo_yss_dayofweek_report_idx9');
                $table->dropIndex('repo_yss_dayofweek_report_idx10');
                $table->dropIndex('repo_yss_dayofweek_report_idx11');
            }
        );
    }
}
