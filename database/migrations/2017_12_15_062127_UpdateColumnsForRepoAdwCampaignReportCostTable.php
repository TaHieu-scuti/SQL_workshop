<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class UpdateColumnsForRepoAdwCampaignReportCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_adw_campaign_report_cost',
            function (Blueprint $table) {
                $table->double('contentLostISBudget')->nullable()->comment(
                    '広告がディスプレイネットワークに表示されていたものの、予算が低すぎたためではなかった推定回数。'
                );
                $table->double('contentImprShare')->nullable()->comment(
                    'ディスプレイネットワークで獲得したインプレッションを、表示された推定インプレッション数で割ったものです。'
                );
                $table->double('contentLostISRank')->nullable()->comment(
                    '広告ランクが低いために広告が表示されなかったディスプレイネットワークのインプレッションの推定割合。'
                );
                $table->double('engagementRate')->nullable()->comment(
                    '広告が表示された後、ユーザーが広告にどのくらいの頻度で関与するか。広告の表示回数を広告の表示回数で割ったものです。'
                );
                $table->bigInteger('engagements')->nullable()->comment(
                    'エンゲージメントの数。視聴者がライトボックス広告を展開するとエンゲージメントが発生します。
                    また、今後、他の広告タイプがエンゲージメント指標をサポートする場合もあります。'
                );
                $table->double('searchExactMatchIS')->nullable()->comment(
                    '受け取ったインプレッションを、キーワードマッチタイプに関係なく、キーワードと正確に一致する検索キーワード
                    （またはキーワードの類似したもの）で、検索ネットワークで表示される見込みインプレッション数で割ったものです。'
                );
                $table->double('searchImprShare')->nullable()->comment(
                    '検索ネットワークで受け取ったインプレッションを、表示された推定インプレッション数で割ったものです。'
                );
                $table->double('searchLostISRank')->nullable()->comment(
                    '広告ランクが低いために広告が表示されなかった検索ネットワークのインプレッションの推定パーセンテージ。'
                );
                $table->bigInteger('viewThroughConv')->nullable()->comment(
                    'ビュースルーコンバージョンの合計数。これは、ディスプレイネットワーク広告が表示された後、後で他の広告とやり取り
                    （クリックなど）せずにサイトのコンバージョンを達成した場合に発生します。'
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
            'repo_adw_campaign_report_cost',
            function (Blueprint $table) {
                $table->dropColumn('contentLostISBudget');
                $table->dropColumn('contentImprShare');
                $table->dropColumn('contentLostISRank');
                $table->dropColumn('engagementRate');
                $table->dropColumn('searchExactMatchIS');
                $table->dropColumn('searchImprShare');
                $table->dropColumn('searchLostISRank');
                $table->dropColumn('viewThroughConv');
                $table->dropColumn('engagements');
            }
        );
    }
}
