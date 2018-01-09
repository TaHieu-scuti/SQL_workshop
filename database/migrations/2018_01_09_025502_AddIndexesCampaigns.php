<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddIndexesCampaigns extends Migration
{
    const TABLE_NAME = 'campaigns';

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
                $table->index('campaign_id', 'campaigns_campaign_id_idx');
                $table->index('account_id', 'campaigns_account_id_idx');
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
                $table->dropIndex(['campaigns_campaign_id_idx', 'campaigns_account_id_idx']);
            }
        );
    }
}
