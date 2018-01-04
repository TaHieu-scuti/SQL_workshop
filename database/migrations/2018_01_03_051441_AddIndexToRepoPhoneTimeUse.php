<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddIndexToRepoPhoneTimeUse extends Migration
{
    const INDEX_NAME = 'repo_phone_time_use_phone_number_utm_campaign_time_of_call_idx';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "CREATE INDEX `" . self::INDEX_NAME . "` "
            . "ON `repo_phone_time_use` (phone_number(15), utm_campaign(50), time_of_call(20)) "
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
            'repo_phone_time_use',
            function (Blueprint $table) {
                $table->dropIndex(self::INDEX_NAME);
            }
        );
    }
}
