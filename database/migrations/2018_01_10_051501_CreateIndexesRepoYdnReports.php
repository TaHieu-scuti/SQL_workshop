<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexesRepoYdnReports extends Migration
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
                $table->index('campaignID', 'repo_ydn_report_campaignID_idx1');
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
                $table->dropIndex('repo_ydn_report_campaignID_idx1');
            }
        );
    }
}
