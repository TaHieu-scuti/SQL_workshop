<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTableYssAccountReportConv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_yss_account_report_convs',
            function (Blueprint $table) {
                $table->rename('repo_yss_account_report_conv');
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
            'repo_yss_account_report_conv',
            function (Blueprint $table) {
                $table->rename('repo_yss_account_report_convs');
            }
        );
    }
}
