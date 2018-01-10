<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddIndexDayCampaignIdOnRepoYdnReports extends Migration
{
    const INDEX_NAME = 'repo_ydn_reports_day_campaignID_idx';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "CREATE INDEX `" . self::INDEX_NAME . "` "
            . "ON `repo_ydn_reports` (day, campaignID) "
            . "COMMENT '' "
            . "ALGORITHM DEFAULT "
            . "LOCK DEFAULT;"
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
                $table->dropIndex(self::INDEX_NAME);
            }
        );
    }
}
