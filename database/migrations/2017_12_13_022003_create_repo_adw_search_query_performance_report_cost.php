<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepoAdwSearchQueryPerformanceReportCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'repo_adw_search_query_performance_report_cost',
            function (Blueprint $table) {
                $table->increments('id');
                $table->date('exeDate')->comment('レポートAPI実行日');
                $table->date('startDate')->comment('APIで指定したレポートの開始日');
                $table->date('endDate')->comment('APIで指定したレポートの終了日');
                $table->string('account_id', 50)->comment('ADgainerシステムのアカウントID');
                $table->string('campaign_id', 50)->comment(
                    'ADgainerシステムのキャンペーンID。destinationURLのクエリパラメータを分解して取得'
                );
                $table->string('currency', 50)->nullable()->comment('顧客口座の通貨。');
                $table->text('account')->nullable()->comment('カスタマーアカウントのわかりやすい名前。');
                $table->string('timeZone', 50)->nullable()->comment(
                    '顧客アカウント用に選択されたタイムゾーンの名前。 たとえば、「（GMT-05：00）東部時間」などです。
                    このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。'
                );
                $table->string('adType', 50)->nullable()->comment(
                    '広告の基礎となるメディア形式。 値は、テンプレート広告ページの書式、 またはMediaType 列挙型です。'
                );
                $table->bigInteger('adGroupID')->nullable()->comment(
                    '広告グループのID。'
                );
                $table->text('adGroup')->nullable()->comment('広告グループの名前。');
                $table->string('adGroupState', 50)->nullable()->comment('広告グループのステータス。');
                $table->string('network', 50)->nullable()->comment('第1レベルのネットワークタイプ。');
                $table->string('networkWithSearchPartners', 50)->nullable()->comment(
                    '第2レベルのネットワークタイプ（検索パートナーを含む）。'
                );
                $table->double('allConvRate')->nullable()->comment(
                    'AllConversionsをコンバージョントラッキング可能な合計クリック数で割った値です。
                    これは広告のクリックがコンバージョンにつながった頻度です。 "x.xx％" "として返されます。'
                );
                $table->double('allConv')->nullable()->comment(
                    'AdWordsが推進するコンバージョン数の最善の見積もり。
                    ウェブサイト、クロスデバイス、電話通話のコンバージョンが含まれます。'
                );
                $table->double('allConvValue')->nullable()->comment(
                    '推定されたものを含め、すべてのコンバージョンの合計値。'
                );
                $table->double('avgCost')->nullable()->comment(
                    'インタラクションごとに支払う平均金額。
                    この金額は、広告の合計費用を合計インタラクション数で割ったものです。'
                );
                $table->double('avgCPC')->nullable()->comment(
                    'すべてのクリックの総コストを、受け取った総クリック数で割った値。'
                );
                $table->double('avgCPE')->nullable()->comment(
                    '広告掲載に費やされた平均金額。'
                );
                $table->double('avgCPM')->nullable()->comment(
                    '平均インプレッション単価（CPM）。'
                );
                $table->double('avgCPV')->nullable()->comment(
                    'ユーザーが広告を表示するたびに支払う平均金額。
                    平均CPVは、すべての広告ビューの総コストをビュー数で割った値で定義されます。'
                );
                $table->double('avgPosition')->nullable()->comment(
                    '他の広告主様との相対的な広告の掲載順位'
                );
                $table->bigInteger('campaignID')->nullable()->comment(
                    'キャンペーンのID。'
                );
                $table->text('campaign')->nullable()->comment(
                    'キャンペーンの名前。'
                );
                $table->string('campaignState', 50)->nullable()->comment(
                    'キャンペーンのステータス。'
                );
                $table->bigInteger('clicks')->nullable()->comment(
                    'クリック数。'
                );
                $table->double('convRate')->nullable()->comment(
                    'コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったもの。'
                );
                $table->double('conversions')->nullable()->comment(
                    '最適化を選択したすべてのコンバージョンアクションのコンバージョン数。'
                );
                $table->double('totalConvValue')->nullable()->comment(
                    'すべてのコンバージョンのコンバージョン値の合計です。'
                );
                $table->double('cost')->nullable()->comment(
                    'この期間のクリック単価（CPC）とインプレッション単価（CPM）の合計。'
                );
                $table->double('costAllConv')->nullable()->comment(
                    '総費用をすべてのコンバージョンで割った値。'
                );
                $table->double('costConv')->nullable()->comment(
                    'コンバージョントラッキングクリック数に起因する費用をコンバージョン数で割った値'
                );
                $table->bigInteger('adId')->nullable()->comment(
                    '広告のID。'
                );
                $table->double('crossDeviceConv')->nullable()->comment(
                    'ユーザーが1つのデバイスでAdWords広告をクリックして別のデバイスやブラウザでコンバージョンを
                    達成したときのコンバージョンデバイス間のコンバージョンは既にAllConversions列に含まれています。'
                );
                $table->double('ctr')->nullable()->comment(
                    '広告がクリックされた回数（クリック数）を広告が表示された回数（インプレッション数）で割ったものです。'
                );
                $table->text('clientName')->nullable()->comment(
                    'カスタマーのわかりやすい名前。'
                );
                $table->date('day')->nullable()->comment(
                    '日付はyyyy-MM-ddの形式になります。'
                );
                $table->string('dayOfWeek', 50)->nullable()->comment(
                    '曜日の名前です（例：「月曜日」）。'
                );
                $table->text('destinationURL')->nullable()->comment(
                    'インプレッションのリンク先URL。'
                );
                $table->string('device', 50)->nullable()->comment(
                    'インプレッションが表示されたデバイスの種類。'
                );
                $table->double('engagementRate')->nullable()->comment(
                    '広告が表示された後に広告を表示する頻度。広告の表示回数を広告の表示回数で割ったものです。 '
                );
                $table->bigInteger('engagements')->nullable()->comment(
                    '約束の数。 視聴者がライトボックス広告を展開するとエンゲージメントが発生します。
                    また、今後、他の広告タイプがエンゲージメント指標をサポートする場合もあります。'
                );
                $table->bigInteger('customerID')->nullable()->comment(
                    '顧客ID。'
                );
                $table->text('finalURL')->nullable()->comment(
                    'インプレッションの最終URL。'
                );
                $table->bigInteger('impressions')->nullable()->comment(
                    'Googleネットワークの検索結果ページやウェブサイトに広告が表示された回数をカウントします。'
                );
                $table->double('interactionRate')->nullable()->comment(
                    'あなたの広告が表示された後、ユーザーがあなたの広告とどのくらいの頻度で相互作用するか。
                    これはインタラクションの数を広告が表示された回数で割ったものです。 '
                );
                $table->bigInteger('interactions')->nullable()->comment(
                    '相互作用の数 インタラクションとは、テキストやショッピング広告のクリック、
                    動画広告の表示など、広告フォーマットに関連する主要なユーザーアクションです。'
                );
                $table->string('interactionTypes', 50)->nullable()->comment(
                    'Interactions、InteractionRate、およびAverageCostの各列に反映される相互作用のタイプ。'
                );
                $table->bigInteger('keywordID')->nullable()->comment(
                    '広告を表示したキーワードのID。'
                );
                $table->text('keyword')->nullable()->comment(
                    'クエリと一致し、広告を表示したキーワード。'
                );
                $table->date('month')->nullable()->comment(
                    '月の最初の日。yyyy-MM-ddの形式です。'
                );
                $table->string('monthOfYear', 50)->nullable()->comment(
                    '月の名前です（例：「12月」）。'
                );
                $table->date('quarter')->nullable()->comment(
                    '四半期の最初の日は、yyyy-MM-ddの形式です。 四半期の暦年を使用します。
                    たとえば、2014年第2四半期は2014-04-01に開始します。'
                );
                $table->text('searchTerm')->nullable()->comment(
                    'この属性の文字列が128文字バイトより長い場合、返される結果は単一の集約行にはなりません。'
                );
                $table->string('matchType', 50)->nullable()->comment(
                    'バリアントを含む、広告をトリガーしたキーワードのマッチタイプ。
                    類似パターンの詳細については、https://support.google.com/adwords/answer/2472708をご覧ください。'
                );
                $table->string('addedExcluded', 50)->nullable()->comment(
                    '検索語が現在ターゲットまたは除外キーワードのいずれであるかを示します。'
                );
                $table->text('trackingTemplate')->nullable()->comment(
                    'この行のメインオブジェクトのトラッキングテンプレート。'
                );
                $table->double('valueAllConv')->nullable()->comment(
                    'すべてのコンバージョンの平均値です。'
                );
                $table->double('valueConv')->nullable()->comment(
                    'コンバージョン数の合計をコンバージョン数で割った値。'
                );
                $table->double('videoPlayedTo100')->nullable()->comment(
                    '視聴者があなたのすべての動画を視聴したインプレッションの割合。'
                );
                $table->double('videoPlayedTo25')->nullable()->comment(
                    '視聴者が動画の25％を視聴したインプレッションの割合。'
                );
                $table->double('videoPlayedTo50')->nullable()->comment(
                    '視聴者が動画の50％を視聴したインプレッションの割合。'
                );
                $table->double('videoPlayedTo75')->nullable()->comment(
                    '視聴者が動画の75％を視聴したインプレッションの割合。'
                );
                $table->double('viewRate')->nullable()->comment(
                    'TrueView動画広告の視聴回数（TrueViewインディスプレイ広告のサムネイルの表示回数など）。'
                );
                $table->bigInteger('views')->nullable()->comment(
                    '動画広告が表示された回数。'
                );
                $table->bigInteger('viewThroughConv')->nullable()->comment(
                    'ビュースルーコンバージョンの合計数。 これは、ディスプレイネットワーク広告が表示された後、
                    後で他の広告とやり取り（クリックなど）せずにサイトのコンバージョンを達成した場合に発生します。'
                );
                $table->date('week')->nullable()->comment(
                    'yyyy-MM-ddの形式の月曜日の日付。'
                );
                $table->integer('year')->nullable()->comment(
                    '年はyyyyの形式です。'
                );

                $table->unique('id', 'id_UNIQUE');
                $table->index('exeDate', 'repo_adw_search_query_performance_report_cost1');
                $table->index('startDate', 'repo_adw_search_query_performance_report_cost2');
                $table->index('endDate', 'repo_adw_search_query_performance_report_cost3');
                $table->index('account_id', 'repo_adw_search_query_performance_report_cost4');
                $table->index('campaign_id', 'repo_adw_search_query_performance_report_cost5');
                $table->index('currency', 'repo_adw_search_query_performance_report_cost6');
                $table->index('timeZone', 'repo_adw_search_query_performance_report_cost7');
                $table->index('adType', 'repo_adw_search_query_performance_report_cost8');
                $table->index('adGroupID', 'repo_adw_search_query_performance_report_cost9');
                $table->index('adGroupState', 'repo_adw_search_query_performance_report_cost10');
                $table->index('network', 'repo_adw_search_query_performance_report_cost11');
                $table->index('networkWithSearchPartners', 'repo_adw_search_query_performance_report_cost12');
                $table->index('campaignID', 'repo_adw_search_query_performance_report_cost13');
                $table->index('campaignState', 'repo_adw_search_query_performance_report_cost14');
                $table->index('adID', 'repo_adw_search_query_performance_report_cost15');
                $table->index('day', 'repo_adw_search_query_performance_report_cost16');
                $table->index('dayOfWeek', 'repo_adw_search_query_performance_report_cost17');
                $table->index('device', 'repo_adw_search_query_performance_report_cost18');
                $table->index('customerID', 'repo_adw_search_query_performance_report_cost19');
                $table->index('keywordID', 'repo_adw_search_query_performance_report_cost20');
                $table->index('month', 'repo_adw_search_query_performance_report_cost21');
                $table->index('monthOfYear', 'repo_adw_search_query_performance_report_cost22');
                $table->index('quarter', 'repo_adw_search_query_performance_report_cost23');
                $table->index('matchType', 'repo_adw_search_query_performance_report_cost24');
                $table->index('addedExcluded', 'repo_adw_search_query_performance_report_cost25');
                $table->index('week', 'repo_adw_search_query_performance_report_cost26');
                $table->index('year', 'repo_adw_search_query_performance_report_cost27');
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
        Schema::drop('repo_adw_search_query_performance_report_cost');
    }
}
