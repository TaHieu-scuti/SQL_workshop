<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class UpdateColumnForRepoAdwAdgroupReportCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_adw_adgroup_report_cost',
            function (Blueprint $table) {
                $table->dropColumn('enhancedCPVEnabled');
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
            'repo_adw_adgroup_report_cost',
            function (Blueprint $table) {
                $table->boolean('enhancedCPVEnabled')
                    ->nullable()
                    ->comment('入札戦略でエンハンストCPVが有効になっているかどうかを示します。')
                    ->index('repo_adw_adgroup_report_cost33');
            }
        );
    }
}
