<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class UpdateColumnForRepoAdwAdReportCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_adw_ad_report_cost',
            function (Blueprint $table) {
                $table->double('engagementRate')->nullable()->comment(
                    '広告が表示された後、ユーザーが広告にどのくらいの頻度で関与するか。
                    広告の表示回数を広告の表示回数で割ったものです。'
                );
                $table->bigInteger('engagements')->nullable()->comment(
                    '約束の数。視聴者がライトボックス広告を展開するとエンゲージメントが発生します。
                    また、今後、他の広告タイプがエンゲージメント指標をサポートする場合もあります。'
                );
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
            'repo_adw_ad_report_cost',
            function (Blueprint $table) {
                $table->dropColumn('engagementRate');
                $table->dropColumn('engagements');
            }
        );
    }
}
