<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnForRepoAdwAccountReportConv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_adw_account_report_conv',
            function (Blueprint $table) {
                $table->dropColumn('viewThroughConv');
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
            'repo_adw_account_report_conv',
            function (Blueprint $table) {
                $table->bigInteger('viewThroughConv')->nullable()->comment(
                    'ビュースルーコンバージョンの合計数。これは、ディスプレイネットワーク広告が表示された後、
                    後で他の広告とやり取り（クリックなど）せずにサイトのコンバージョンを達成した場合に発生します。'
                );
            }
        );
    }
}
