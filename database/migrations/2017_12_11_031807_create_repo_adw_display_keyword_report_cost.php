<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoAdwDisplayKeywordReportCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'repo_adw_display_keyword_report_cost',
            function (Blueprint $table) {
                $table->increments('id');
                $table->date('exeDate')->comment('レポートAPI実行日');
                $table->date('startDate')->comment('APIで指定したレポートの開始日');
                $table->date('endDate')->comment('APIで指定したレポートの終了日');
                $table->string('account_id', 50)->comment('ADgainerシステムのアカウントID');
                $table->string('campaign_id', 50)->comment(
                    'ADgainerシステムのキャンペーンID。destinationURLのクエリパラメータを分解して取得'
                );
                $table->string('currency', 50)->nullable()->comment('顧客アカウントの通貨。');
                $table->text('account')->nullable()->comment('カスタマーアカウントのわかりやすい名前。');
                $table->string('timeZone', 50)->nullable()->comment(
                    '顧客アカウント用に選択されたタイムゾーンの名前。たとえば、「（GMT-05：00）
                    東部時間」などです。このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。'
                );
                $table->double('activeViewAvgCPM')->nullable()->comment('視認可能インプレッションの平均費用（ActiveViewImpressions）。');
                $table->double('activeViewViewableCTR')->nullable()->comment('広告が表示された後にユーザーが広告をクリックした頻度');
                $table->bigInteger('activeViewViewableImpressions')->nullable()->comment('広告が表示された後にユーザーが広告をクリックした頻度');
                $table->double('activeViewMeasurableImprImpr')->nullable()->comment('アクティブビューで計測されたインプレッション数と配信済みインプレッション数の比。');
                $table->double('activeViewMeasurableCost')->nullable()->comment('Active Viewで測定可能なインプレッションの費用。');
                $table->integer('activeViewMeasurableImpr')->nullable()->comment('広告が表示されているプレースメントに広告が表示された回数。');
                $table->double('activeViewViewableImprMeasurableImpr')->nullable()->comment(
                    '広告がアクティブビュー対応サイトに表示された時間
                    （測定可能なインプレッション数）と表示可能（表示可能なインプレッション数）の割合。'
                );
                $table->bigInteger('adGroupID')->nullable()->comment('広告グループのID。');
                $table->text('adGroup')->nullable()->comment('広告グループの名前。');
                $table->string('adGroupState', 50)->nullable()->comment('広告グループのステータス。');
                $table->string('network', 50)->nullable()->comment('第1レベルのネットワークタイプ。');
                $table->string('networkWithSearchPartners', 50)->nullable()->comment('第2レベルのネットワークタイプ（検索パートナーを含む）。');
                $table->double('allConvRate')->nullable()->comment(
                    'AllConversionsをコンバージョントラッキングできる合計クリック数で割ったものです。これは、広告のクリックがコンバージョンにつながった頻度です。'
                );
                $table->double('allConv')->nullable()->comment('AdWordsが推進するコンバージョン数の最善の見積もり。ウェブサイト、クロスデバイス、電話通話のコンバージョンが含まれます。');
                $table->double('allConvValue')->nullable()->comment('推定されたものを含む、すべてのコンバージョンの合計値。');
                $table->double('avgCost')->nullable()->comment('インタラクションごとに支払う平均金額。この金額は、広告の合計費用を合計インタラクション数で割ったものです。');
                $table->double('avgCPC')->nullable()->comment('すべてのクリックの総コストを、受け取った総クリック数で割った値。');
                $table->double('avgCPE')->nullable()->comment('広告掲載に費やされた平均金額。この金額は、すべての広告契約の総費用を広告契約の総数で割ったものです。');
                $table->double('avgCPM')->nullable()->comment('平均インプレッション単価（CPM）。');
                $table->double('avgCPV')->nullable()->comment('ユーザーが広告を表示するたびに支払う平均金額。平均CPVは、すべての広告ビューの総コストをビュー数で割った値で定義されます。');
                $table->bigInteger('baseAdGroupID')->nullable()->comment('試用広告グループの基本広告グループのID。通常の広告グループの場合、これはAdGroupIdと同じです。');
                $table->bigInteger('baseCampaignID')->nullable()->comment('試用キャンペーンの基本キャンペーンのID。通常のキャンペーンの場合、これはCampaignIdと同じです。');
                $table->string('conversionOptimizerBidType', 50)->nullable()->comment('入札タイプ。');
                $table->bigInteger('campaignID')->nullable()->comment('キャンペーンのID。');
                $table->text('campaign')->nullable()->comment('キャンペーンの名前。');
                $table->string('campaignState', 50)->nullable()->comment('キャンペーンのステータス。');
                $table->bigInteger('clicks')->nullable()->comment('クリック数。');
                $table->double('convRate')->nullable()->comment('コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったものです。');
                $table->double('conversions')->nullable()->comment('最適化を選択したすべてのコンバージョンアクションのコンバージョン数。');
                $table->double('totalConvValue')->nullable()->comment('すべてのコンバージョンのコンバージョン値の合計。');
                $table->double('cost')->nullable()->comment('この期間のクリック単価（CPC）とインプレッション単価（CPM）の合計。');
                $table->double('costAllConv')->nullable()->comment('総費用をすべてのコンバージョンで割った値。');
                $table->double('costConv')->nullable()->comment('コンバージョントラッキングクリック数に起因する費用をコンバージョン数で割った値');
                $table->double('maxCPC')->nullable()->comment(
                    'クリック単価制。値は、a）小額の金額、b）AdWordsが自動的に選択された入札戦略で入札単価を設定する場合は
                    「自動：x」または「自動」、c）クリック単価が適用されない場合は「 - 」のいずれかです'
                );
                $table->string('maxCPCSource', 50)->nullable()->comment('CPC入札のソース。');
                $table->double('maxCPM')->nullable()->comment('CPM（1,000インプレッションあたりの単価）の単価');
                $table->string('maxCPMSource', 50)->nullable()->comment('CPM入札のソース。');
                $table->double('maxCPV')->nullable()->comment(
                    '視聴単価制の入札単価値は、a）小額の金額、b）AdWordsが自動的に選択した入札戦略で入札単価を設定している場合は
                    「自動：x」または「自動」、またはc）入札単価が適用されない場合は「 - 」のいずれかです'
                );
                $table->string('maxCPVSource', 50)->nullable()->comment('視聴単価の入札価格です。');
                $table->text('keyword')->nullable()->comment(
                    'Criterionの記述的な文字列。レポートの条件タイプのフォーマットの詳細については、条件プレフィックス
                    (URL:https://developers.google.com/adwords/api/docs/guides/reporting#criteria_prefixes)
                    のセクションをご覧ください。'
                );
                $table->text('destinationURL')->nullable()->comment('広告を表示した条件のリンク先URL。');
                $table->double('crossDeviceConv')->nullable()->comment(
                    '顧客が1つの端末でAdWords広告をクリックしてから別の端末やブラウザで変換した後のコンバージョンデバイス間のコンバージョンは既に
                    AllConversions列に含まれています。'
                );
                $table->double('ctr')->nullable()->comment('広告がクリックされた回数（クリック数）を広告が表示された回数（インプレッション数）で割ったものです。');
                $table->text('clientName')->nullable()->comment('カスタマーのわかりやすい名前。');
                $table->date('day')->nullable()->comment('日付はyyyy-MM-ddの形式になります。');
                $table->string('dayOfWeek', 50)->nullable()->comment('曜日の名前です（例：「月曜日」）。');
                $table->string('device', 50)->nullable()->comment('インプレッションが表示されたデバイスの種類。');
                $table->double('engagementRate')->nullable()->comment('広告が表示された後、ユーザーが広告にどのくらいの頻度で関与するか。 広告の表示回数を広告の表示回数で割ったものです。');
                $table->bigInteger('engagements')->nullable()->comment(
                    '約束の数。 視聴者がライトボックス広告を展開するとエンゲージメントが発生します。
                    また、今後、他の広告タイプがエンゲージメント指標をサポートする場合もあります。'
                );
                $table->bigInteger('customerID')->nullable()->comment('顧客ID。');
                $table->text('appFinalURL')->nullable()->comment(
                    'この行のメインオブジェクトの最終的なアプリURLのリスト。リストのエントリは、a）
                    「android-app：」（Androidアプリの場合）またはb）「os-app：」
                    （iOSアプリの場合）のいずれかで始まります。 AppUrlList要素はJSONリスト形式で返されます。'
                );
                $table->text('mobileFinalURL')->nullable()->comment('この行のメインオブジェクトの最終的なモバイルURLのリスト。 UrlList要素はJSONリスト形式で返されます。');
                $table->text('finalURL')->nullable()->comment('この行の主要オブジェクトの最終的なURLのリスト。 UrlList要素はJSONリスト形式で返されます。');
                $table->bigInteger('gmailForwards')->nullable()->comment('広告が誰かにメッセージとして転送された回数。');
                $table->bigInteger('gmailSaves')->nullable()->comment('Gmail広告をメッセージとして受信トレイに保存した回数。');
                $table->bigInteger('gmailClicksToWebsite')->nullable()->comment('Gmail広告の展開状態でのリンク先ページへのクリック数。');
                $table->bigInteger('keywordID')->nullable()->comment('この行の主オブジェクトのID。');
                $table->bigInteger('impressions')->nullable()->comment('Googleネットワークの検索結果ページやウェブサイトに広告が表示された回数をカウントします。');
                $table->double('interactionRate')->nullable()->comment(
                    '広告が表示された後にユーザーがどのくらい頻繁に広告を操作するか。
                    これはインタラクションの数を広告の表示回数で割ったものです。'
                );
                $table->bigInteger('interactions')->nullable()->comment(
                    '相互作用の数インタラクションとは、テキストやショッピング広告のクリック、
                    動画広告の表示など、広告フォーマットに関連する主要なユーザーアクショ'
                );
                $table->string('interactionTypes', 50)->nullable()->comment(
                    'Interactions、InteractionRate、およびAverageCost列に反映される相互作用のタイプ。'
                );
                $table->boolean('isNegative')->nullable()->comment('この行の基準が否定（除外）基準であるかどうかを示します。');
                $table->boolean('isRestricting')->nullable()->comment(
                    'trueの値は、基準タイプが入札単価とターゲティング制限に使用されていることを示します。 falseの値は、基準タイプが入札にのみ使用されることを示します。
                    これは、基準の対応するAdGroup.TargetingSettingDetailのtargetAllと反対の値になります。
                    たとえば、criterionTypeGroup = PLACEMENTのTargetingSettingDetailにtargetAll = trueが設定されている場合、
                    IsRestrictフィールドは配置基準に対してfalseになります。'
                );
                $table->date('month')->nullable()->comment('月の最初の日。yyyy-MM-ddの形式です。');
                $table->string('monthOfYear', 50)->nullable()->comment('月の名前です（例：「12月」）。');
                $table->date('quarter')->nullable()->comment(
                    '四半期の最初の日は、yyyy-MM-ddの形式です。四半期の暦年を使用します。
                    たとえば、2014年第2四半期は2014-04-01に開始します。'
                );
                $table->string('keywordState', 50)->nullable()->comment(
                    'この行のメインオブジェクトのステータス。たとえば、キャンペーンの掲載結果レポートでは、これが各行のキャンペーンのステータスになります。
                    広告グループの掲載結果レポートでは、これは各行の広告グループのステータスになります。'
                );
                $table->text('trackingTemplate')->nullable()->comment('この行のメインオブジェクトのトラッキングテンプレート。');
                $table->text('customParameter')->nullable()->comment(
                    'この行のメインオブジェクトのカスタムURLパラメータ。 CustomParameters要素はJSONマップ形式で返されます。'
                );
                $table->double('valueAllConv')->nullable()->comment('すべてのコンバージョンの平均値です。');
                $table->double('valueConv')->nullable()->comment('コンバージョン数の合計をコンバージョン数で割った値。');
                $table->double('videoPlayedTo100')->nullable()->comment('視聴者があなたのすべての動画を視聴したインプレッションの割合。');
                $table->double('videoPlayedTo25')->nullable()->comment('視聴者が動画の25％を視聴したインプレッションの割合。');
                $table->double('videoPlayedTo50')->nullable()->comment('視聴者が動画の50％を視聴したインプレッションの割合。');
                $table->double('videoPlayedTo75')->nullable()->comment('視聴者が動画の75％を視聴したインプレッションの割合。');
                $table->double('viewRate')->nullable()->comment(
                    'TrueView動画広告の表示回数を、TrueViewインディスプレイ広告のサムネイル表示回数を含むインプレッション数で割ったものです。'
                );
                $table->bigInteger('views')->nullable()->comment('動画広告が表示された回数。');
                $table->bigInteger('viewThroughConv')->nullable()->comment(
                    'ビュースルーコンバージョンの合計数。これは、ディスプレイネットワーク広告が表示された後、後で他の広告とやり取り
                    （クリックなど）せずにサイトのコンバージョンを達成した場合に発生します。'
                );
                $table->date('week')->nullable()->comment('yyyy-MM-ddの形式の月曜日の日付。');
                $table->bigInteger('year')->nullable()->comment('年はyyyyの形式です。');

                $table->unique('id', 'id_UNIQUE');
                $table->index('exeDate', 'repo_adw_display_keyword_report_cost1');
                $table->index('startDate', 'repo_adw_display_keyword_report_cost2');
                $table->index('endDate', 'repo_adw_display_keyword_report_cost3');
                $table->index('account_id', 'repo_adw_display_keyword_report_cost4');
                $table->index('campaign_id', 'repo_adw_display_keyword_report_cost5');
                $table->index('currency', 'repo_adw_display_keyword_report_cost6');
                $table->index('timeZone', 'repo_adw_display_keyword_report_cost7');
                $table->index('adGroupID', 'repo_adw_display_keyword_report_cost8');
                $table->index('adGroupState', 'repo_adw_display_keyword_report_cost9');
                $table->index('network', 'repo_adw_display_keyword_report_cost10');
                $table->index('networkWithSearchPartners', 'repo_adw_display_keyword_report_cost11');
                $table->index('baseAdGroupID', 'repo_adw_display_keyword_report_cost12');
                $table->index('baseCampaignID', 'repo_adw_display_keyword_report_cost13');
                $table->index('conversionOptimizerBidType', 'repo_adw_display_keyword_report_cost14');
                $table->index('campaignID', 'repo_adw_display_keyword_report_cost15');
                $table->index('campaignState', 'repo_adw_display_keyword_report_cost16');
                $table->index('maxCPC', 'repo_adw_display_keyword_report_cost17');
                $table->index('maxCPCSource', 'repo_adw_display_keyword_report_cost18');
                $table->index('maxCPM', 'repo_adw_display_keyword_report_cost19');
                $table->index('maxCPMSource', 'repo_adw_display_keyword_report_cost20');
                $table->index('maxCPV', 'repo_adw_display_keyword_report_cost21');
                $table->index('maxCPVSource', 'repo_adw_display_keyword_report_cost22');
                $table->index('day', 'repo_adw_display_keyword_report_cost23');
                $table->index('dayOfWeek', 'repo_adw_display_keyword_report_cost24');
                $table->index('device', 'repo_adw_display_keyword_report_cost25');
                $table->index('customerID', 'repo_adw_display_keyword_report_cost26');
                $table->index('keywordID', 'repo_adw_display_keyword_report_cost27');
                $table->index('isNegative', 'repo_adw_display_keyword_report_cost28');
                $table->index('isRestricting', 'repo_adw_display_keyword_report_cost29');
                $table->index('month', 'repo_adw_display_keyword_report_cost30');
                $table->index('monthOfYear', 'repo_adw_display_keyword_report_cost31');
                $table->index('quarter', 'repo_adw_display_keyword_report_cost32');
                $table->index('keywordState', 'repo_adw_display_keyword_report_cost33');
                $table->index('week', 'repo_adw_display_keyword_report_cost34');
                $table->index('year', 'repo_adw_display_keyword_report_cost35');
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
        Schema::dropIfExists('repo_adw_display_keyword_report_cost');
    }
}
