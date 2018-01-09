<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddIndexesPhoneTimeUse extends Migration
{
    const TABLE_NAME = 'phone_time_use';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            self::TABLE_NAME,
            function (Blueprint $table) {
                $table->index('account_id', 'account_id');
                $table->index('campaign_id', 'campaign_id');
                $table->index('phone_number', 'PhoneNumber');
                $table->index('ip', 'IP_INDEX');
                $table->index('unique_call_id', 'unique_call_id');
                $table->index('time_stamp', 'time');
                $table->index('session_id', 'SESSIONID');
                $table->index('time_of_call', 'time_of_call_index');
                $table->index('update_key', 'update_key');
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
            self::TABLE_NAME,
            function (Blueprint $table) {
                $table->dropIndex([
                    'account_id',
                    'campaign_id',
                    'PhoneNumber',
                    'IP_INDEX',
                    'unique_call_id',
                    'time',
                    'SESSIONID',
                    'time_of_call_index',
                    'update_key'
                ]);
            }
        );
    }
}
