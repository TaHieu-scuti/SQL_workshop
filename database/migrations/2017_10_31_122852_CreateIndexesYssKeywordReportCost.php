<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexesYssKeywordReportCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_yss_keyword_report_cost',
            function (Blueprint $table) {
                $table->index('account_id', 'repo_yss_keyword_report_cost_idx1');
                $table->index('campaign_id', 'repo_yss_keyword_report_cost_idx2');
                $table->index('network', 'repo_yss_keyword_report_cost_idx3');
                $table->index('device', 'repo_yss_keyword_report_cost_idx4');
                $table->index('day', 'repo_yss_keyword_report_cost_idx5');
                $table->index('dayOfWeek', 'repo_yss_keyword_report_cost_idx6');
                $table->index('quarter', 'repo_yss_keyword_report_cost_idx7');
                $table->index('month', 'repo_yss_keyword_report_cost_idx8');
                $table->index('week', 'repo_yss_keyword_report_cost_idx9');
                $table->index('campaignID', 'repo_yss_keyword_report_cost_idx10');
                $table->index('adgroupID', 'repo_yss_keyword_report_cost_idx11');
                $table->index('keywordID', 'repo_yss_keyword_report_cost_idx12');
                $table->index('exeDate', 'repo_yss_keyword_report_cost_idx13');
                $table->index('startDate', 'repo_yss_keyword_report_cost_idx14');
                $table->index('endDate', 'repo_yss_keyword_report_cost_idx15');
                $table->index('accountid', 'repo_yss_keyword_report_cost_idx16');
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
            'repo_yss_keyword_report_cost',
            function (Blueprint $table) {
                $table->dropIndex('repo_yss_keyword_report_cost_idx1');
                $table->dropIndex('repo_yss_keyword_report_cost_idx2');
                $table->dropIndex('repo_yss_keyword_report_cost_idx3');
                $table->dropIndex('repo_yss_keyword_report_cost_idx4');
                $table->dropIndex('repo_yss_keyword_report_cost_idx5');
                $table->dropIndex('repo_yss_keyword_report_cost_idx6');
                $table->dropIndex('repo_yss_keyword_report_cost_idx7');
                $table->dropIndex('repo_yss_keyword_report_cost_idx8');
                $table->dropIndex('repo_yss_keyword_report_cost_idx9');
                $table->dropIndex('repo_yss_keyword_report_cost_idx10');
                $table->dropIndex('repo_yss_keyword_report_cost_idx11');
                $table->dropIndex('repo_yss_keyword_report_cost_idx12');
                $table->dropIndex('repo_yss_keyword_report_cost_idx13');
                $table->dropIndex('repo_yss_keyword_report_cost_idx14');
                $table->dropIndex('repo_yss_keyword_report_cost_idx15');
                $table->dropIndex('repo_yss_keyword_report_cost_idx16');
            }
        );
    }
}
