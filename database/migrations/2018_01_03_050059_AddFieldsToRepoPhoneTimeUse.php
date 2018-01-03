<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddFieldsToRepoPhoneTimeUse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_phone_time_use',
            function (Blueprint $table) {
                $table->string('account_id', 50);
                $table->string('campaign_id', 50);
                $table->mediumText('utm_campaign');
                $table->string('time_of_call', 255);
                $table->string('source', 255);
                $table->string('phone_number', 255);
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
            'repo_phone_time_use',
            function (Blueprint $table) {
                $table->dropColumn([
                    'account_id',
                    'campaign_id',
                    'utm_campaign',
                    'time_of_call',
                    'source',
                    'phone_number'
                ]);
            }
        );
    }
}
