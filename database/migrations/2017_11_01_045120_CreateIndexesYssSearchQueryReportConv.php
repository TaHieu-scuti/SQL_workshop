<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateIndexesYssSearchQueryReportConv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_yss_searchquery_report_conv',
            function (Blueprint $table) {
                $table->index('account_id', 'repo_yss_searchquery_report_conv_idx1');
                $table->index('campaign_id', 'repo_yss_searchquery_report_conv_idx2');
                $table->index('campaignID', 'repo_yss_searchquery_report_conv_idx3');
                $table->index('adgroupID', 'repo_yss_searchquery_report_conv_idx4');
                $table->index('keywordID', 'repo_yss_searchquery_report_conv_idx5');
                $table->index('campaignTrackingID', 'repo_yss_searchquery_report_conv_idx6');
                $table->index('adgroupTrackingID', 'repo_yss_searchquery_report_conv_idx7');
                $table->index('keywordTrackingID', 'repo_yss_searchquery_report_conv_idx8');
                $table->index('network', 'repo_yss_searchquery_report_conv_idx9');
                $table->index('device', 'repo_yss_searchquery_report_conv_idx10');
                $table->index('day', 'repo_yss_searchquery_report_conv_idx11');
                $table->index('dayOfWeek', 'repo_yss_searchquery_report_conv_idx12');
                $table->index('quarter', 'repo_yss_searchquery_report_conv_idx13');
                $table->index('month', 'repo_yss_searchquery_report_conv_idx14');
                $table->index('week', 'repo_yss_searchquery_report_conv_idx15');
                $table->index('objectiveOfConversionTracking', 'repo_yss_searchquery_report_conv_idx16');
                $table->index('exeDate', 'repo_yss_searchquery_report_conv_idx17');
                $table->index('startDate', 'repo_yss_searchquery_report_conv_idx18');
                $table->index('endDate', 'repo_yss_searchquery_report_conv_idx19');
                $table->index('accountid', 'repo_yss_searchquery_report_conv_idx20');
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
            'repo_yss_searchquery_report_conv',
            function (Blueprint $table) {
                $table->dropIndex('repo_yss_searchquery_report_conv_idx1');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx2');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx3');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx4');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx5');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx6');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx7');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx8');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx9');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx10');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx11');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx12');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx13');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx14');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx15');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx16');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx17');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx18');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx19');
                $table->dropIndex('repo_yss_searchquery_report_conv_idx20');
            }
        );
    }
}
