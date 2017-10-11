<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnFromRepoYssAdgroupReportCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repo_yss_adgroup_report_cost', function (Blueprint $table) {
             $table->dropColumn('impression');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repo_yss_adgroup_report_cost', function (Blueprint $table) {
           $table->bigInteger('impression')->nullable()->comment('インプレッション数');
        });
    }
}
