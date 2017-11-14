<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToAdwAccountReportCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repo_adw_account_report_cost', function (Blueprint $table) {
            $table->bigInteger('accountId')->nullable()->comment('アカウントID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repo_adw_account_report_cost', function (Blueprint $table) {
            $table->dropColumn('accountId');
        });
    }
}
