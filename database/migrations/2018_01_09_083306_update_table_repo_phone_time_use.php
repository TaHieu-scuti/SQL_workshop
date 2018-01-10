<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class UpdateTableRepoPhoneTimeUse extends Migration
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
                $table->string('traffic_type', 50);
                $table->string('mobile', 50);
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
                $table->dropColumn('traffic_type');
                $table->dropColumn('mobile');
            }
        );
    }
}
