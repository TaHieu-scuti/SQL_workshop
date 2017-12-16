<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class UpdateColumnForRepoAdwKeywordsReportCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_adw_keywords_report_cost',
            function (Blueprint $table) {
                $table->double('engagementRate')->nullable()->comment(
                    '広告が表示された後、ユーザーが広告にどのくらいの頻度で関与するか。
                    広告の表示回数を広告の表示回数で割ったものです。'
                );
                $table->bigInteger('engagements')->nullable()->comment(
                    '約束の数。視聴者がライトボックス広告を展開するとエンゲージメントが発生します。
                    また、今後、他の広告タイプがエンゲージメント指標をサポートする場合もあります。'
                );
                $table->double('searchExactMatchIS')->nullable()->comment(
                    '受け取ったインプレッションを、キーワードマッチタイプに関係なく、キーワードと正確に一致する検索語
                    （またはキーワードの類似したもの）で、検索ネットワークで表示される見込みインプレッション数で割ったものです。'
                );
                $table->double('searchImprShare')->nullable()->comment(
                    '検索ネットワークで受け取ったインプレッションを、表示された推定インプレッション数で割ったものです。'
                );
                $table->double('searchLostISRank')->nullable()->comment(
                    '広告ランクが低いために広告が表示されなかった検索ネットワークのインプレッションの推定パーセンテージ。 '
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
            'repo_adw_keywords_report_cost',
            function (Blueprint $table) {
                $table->dropColumn('engagementRate');
                $table->dropColumn('engagements');
                $table->dropColumn('searchExactMatchIS');
                $table->dropColumn('searchImprShare');
                $table->dropColumn('searchLostISRank');
            }
        );
    }
}
