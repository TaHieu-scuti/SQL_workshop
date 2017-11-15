<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class RepoAdwKeywordReportCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'repo_adw_keywords_report_cost',
            function (Blueprint $table) {
                $table->increments('id');
                $table->date('exeDate')->comment('レポートAPI実行日');
                $table->date('startDate')->comment('APIで指定したレポートの開始日');
                $table->date('endDate')->comment('APIで指定したレポートの終了日');
                $table->string('account_id', 50)->comment('ADgainerシステムのアカウントID');
                $table->string('campaign_id', 50)->comment('ADgainerシステムのキャンペーンID。destinationURLのクエリパラメータを分解して取得');
                $table->string('currency', 50)->nullable()->comment('顧客口座の通貨。');
                $table->text('account')->nullable()->comment('カスタマーアカウントのわかりやすい名前。');
                $table->string('timeZone')->nullable()
                    ->comment('顧客アカウント用に選択されたタイムゾーンの名前。 たとえば、「（GMT-05：00）東部時間」などです。 このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。');
                $table->double('activeViewAvgCPM')->nullable()
                    ->comment('視認可能インプレッションの平均費用（ActiveViewImpressions）。');
                $table->double('activeViewViewableCTR')->nullable()
                    ->comment('広告が表示された後にユーザーが広告をクリックした頻度');
                $table->bigInteger('activeViewViewableImpressions')->nullable()
                    ->comment('ディスプレイネットワークサイトで広告が表示される頻度');
                $table->double('activeViewMeasurableImprImpr')->nullable()
                    ->comment('アクティブビューで計測されたインプレッション数と配信インプレッション数の比。');
                $table->double('activeViewMeasurableCost')->nullable()
                    ->comment('Active Viewで測定可能なインプレッションの費用。');
                $table->bigInteger('activeViewMeasurableImpr')->nullable()
                    ->comment('広告が表示されているプレースメントに広告が表示された回数。');
                $table->double('activeViewViewableImprMeasurableImpr')->nullable()
                    ->comment('広告がアクティブビュー対応サイトに表示された時間（測定可能なインプレッション数）と表示可能（表示可能なインプレッション数）の割合。');
                $table->bigInteger('adGroupID')->nullable()->comment('広告グループのID。');
                $table->text('adGroup')->nullable()->comment('広告グループの名前。');
                $table->string('adGroupState', 50)->nullable()->comment('広告グループのステータス。');
                $table->string('network', 50)->nullable()->comment('第1レベルのネットワークタイプ。');
                $table->string('networkWithSearchPartners', 50)->nullable()
                    ->comment('第2レベルのネットワークタイプ（検索パートナーを含む）。');
                $table->double('allConvRate')->nullable()
                    ->comment('AllConversionsをコンバージョントラッキングできる合計クリック数で割ったものです。これは、広告のクリックがコンバージョンにつながった頻度です。 "x.xx％"として返されるパーセンテージ。');
                $table->double('allConv')->nullable()
                    ->comment('AdWordsが推進するコンバージョン数の最善の見積もり。ウェブサイト、クロスデバイス、電話通話のコンバージョンが含まれます。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->double('allConvValue')->nullable()
                    ->comment('推定されたものを含む、すべてのコンバージョンの合計値。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->string('approvalStatus', 50)->nullable()->comment('基準の承認ステータス。');
                $table->double('avgCost')->nullable()
                    ->comment('インタラクションごとに支払う平均金額。この金額は、広告の合計費用を合計インタラクション数で割ったものです。');
                $table->double('avgCPC')->nullable()->comment('すべてのクリックの総コストを、受け取った総クリック数で割った値。');
                $table->double('avgCPM')->nullable()->comment('平均インプレッション単価（CPM）。');
                $table->double('avgPosition')->nullable()->comment('他の広告主様との相対的な広告の掲載順位');
                $table->bigInteger('baseAdGroupID')->nullable()
                    ->comment('試用広告グループの基本広告グループのID。通常の広告グループの場合、これはAdGroupIdと同じです。');
                $table->bigInteger('baseCampaignID')->nullable()
                    ->comment('試用キャンペーンの基本キャンペーンのID。通常のキャンペーンの場合、これはCampaignIdと同じです。');
                $table->bigInteger('bidStrategyID')->nullable()->comment('BiddingStrategyConfigurationのIDです。');
                $table->text('bidStrategyName')->nullable()->comment('BiddingStrategyConfigurationの名前。');
                $table->string('biddingStrategySource', 50)->nullable()
                    ->comment('入札戦略が関連付けられている場所（キャンペーン、広告グループ、広告グループの条件など）を示します。');
                $table->string('bidStrategyType', 50)->nullable()->comment('BiddingStrategyConfigurationのタイプ。');
                $table->string('conversionOptimizerBidType', 50)->nullable()->comment('入札タイプ。');
                $table->bigInteger('campaignID')->nullable()->comment('キャンペーンのID。');
                $table->text('campaign')->nullable()->comment('キャンペーンの名前。');
                $table->string('campaignState', 50)->nullable()->comment('キャンペーンのステータス。');
                $table->bigInteger('clicks')->nullable()->comment('クリック数。');
                $table->string('clickType', 50)->nullable()
                    ->comment('[インプレッション数]フィールドには、そのクリックタイプで広告が配信された頻度が反映されます。 広告は複数のクリックタイプで表示できるため、インプレッション数は2倍になり、合計が正確でない可能性があります。');
                $table->double('convRate')->nullable()
                    ->comment('コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったものです。 "x.xx％"として返されるパーセンテージ。');
                $table->double('conversions')->nullable()
                    ->comment('最適化を選択したすべてのコンバージョンアクションのコンバージョン数。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->double('totalConvValue')->nullable()
                    ->comment('すべてのコンバージョンのコンバージョン値の合計。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->double('cost')->nullable()->comment('この期間のクリック単価（CPC）とインプレッション単価（CPM）の合計。');
                $table->double('costAllConv')->nullable()->comment('総費用をすべてのコンバージョンで割った値。');
                $table->double('costConv')->nullable()
                    ->comment('コンバージョントラッキングクリック数に起因する費用をコンバージョン数で割った値');
                $table->double('costConvCurrentModel')->nullable()
                    ->comment('現在選択しているアトリビューションモデルで、過去の「CostPerConversion」データがどのように表示されるかを示します。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->double('maxCPC')->nullable()
                    ->comment('クリック単価制。値は、a）小額の金額、b）AdWordsが自動的に選択された入札戦略で入札単価を設定する場合は「自動：x」または「自動」、c）クリック単価が適用されない場合は「 - 」のいずれかです行に');
                $table->string('maxCPCSource', 50)->nullable()->comment('CPC入札のソース。 - 」のいずれかです行に');
                $table->double('maxCPM')->nullable()->comment('1,000インプレッションあたりの単価）の単価');
                $table->string('adRelevance', 50)->nullable()->comment('広告の品質スコア');
                $table->text('keyword')->nullable()
                    ->comment('Criterionの記述的な文字列。レポートの条件タイプのフォーマットの詳細については、レポートガイドのCriteriaプレフィックスセクション（URL：https://developers.google.com/adwords/api/docs/guides/reporting#criteria_prefixes）を参照してください。');
                $table->text('destinationURL')->nullable()->comment('広告を表示した条件のリンク先URL。');
                $table->double('crossDeviceConv')->nullable()
                    ->comment('顧客が1つの端末でAdWords広告をクリックしてから別の端末やブラウザで変換した後のコンバージョンデバイス間のコンバージョンは既にAllConversions列に含まれています。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->double('ctr')->nullable()
                    ->comment('広告がクリックされた回数（クリック数）を広告が表示された回数（インプレッション数）で割ったものです。 "x.xx％"として返されるパーセンテージ。');
                $table->double('conversionsCurrentModel')->nullable()
                    ->comment('現在選択しているアトリビューションモデルでの過去の「コンバージョン」データの表示方法を示します。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->double('convValueCurrentModel')->nullable()
                    ->comment('現在選択しているアトリビューションモデルで、過去の「ConversionValue」データがどのように表示されるかを示します。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->text('clientName')->nullable()->comment('カスタマーのわかりやすい名前。');
                $table->date('day')->nullable()->comment('日付はyyyy-MM-ddの形式になります。');
                $table->string('dayOfWeek', 50)->nullable()->comment('曜日の名前です（例：「月曜日」）。');
                $table->string('device', 50)->nullable()->comment('インプレッションが表示されたデバイスの種類。');
                $table->boolean('enhancedCPCEnabled')->nullable()
                    ->comment('入札戦略でエンハンストCPCが有効になっているかどうかを示します。');
                $table->bigInteger('estAddClicksWkFirstPositionBid')->nullable()
                    ->comment('FirstPositionCpcの値にキーワードの入札単価を変更すると、1週間あたりのクリック数を見積もることができます。');
                $table->double('estAddCostWkFirstPositionBid')->nullable()
                    ->comment('FirstPositionCpcの値にキーワードの入札単価を変更すると、週あたりの費用の見積もりが変わる可能性があります。');
                $table->bigInteger('customerID')->nullable()->comment('顧客ID。');
                $table->text('appFinalURL')->nullable()
                    ->comment('この行のメインオブジェクトの最終的なアプリURLのリスト。リストのエントリは、a）「android-app：」（Androidアプリの場合）またはb）「os-app：」（iOSアプリの場合）のいずれかで始まります。 AppUrlList要素はJSONリスト形式で返されます。');
                $table->text('mobileFinalURL')->nullable()
                    ->comment('この行のメインオブジェクトの最終的なモバイルURLのリスト。 UrlList要素はJSONリスト形式で返されます。');
                $table->text('finalURL')->nullable()
                    ->comment('この行の主要オブジェクトの最終的なURLのリスト。 UrlList要素はJSONリスト形式で返されます。');
                $table->double('firstPageCPC')->nullable()
                    ->comment('検索結果の最初のページに広告を表示するために必要なクリック単価の見積もり。通常マイクロ秒単位の数字ですが、自動入札機能が使用されている場合は「auto：」という接頭辞が付いていてもよく、単に「auto」という文字列であってもかまいません。');
                $table->double('firstPositionCPC')->nullable()
                    ->comment('広告がGoogle検索結果の最初のページの最初の位置に表示されるのに必要な金額を見積もります。通常マイクロ秒単位の数字ですが、自動入札機能が使用されている場合は「auto：」という接頭辞が付いていてもよく、単に「auto」という文字列であってもかまいません。');
                $table->bigInteger('gmailForwards')->nullable()->comment('広告が誰かにメッセージとして転送された回数。');
                $table->bigInteger('gmailSaves')->nullable()
                    ->comment('Gmail広告をメッセージとして受信トレイに保存した回数。');
                $table->bigInteger('gmailClicksToWebsite')->nullable()
                    ->comment('Gmail広告の展開状態でのリンク先ページへのクリック数。');
                $table->boolean('hasQualityScore')->nullable()
                    ->comment('基準のQualityScoreフィールドに値があるかどうか。レポート要求述部のこのフィールドを使用して、QualityScoreフィールドの値の有無にかかわらず条件を含めるか除外します。');
                $table->bigInteger('keywordID')->nullable()->comment('この行の主オブジェクトのID。');
                $table->bigInteger('impressions')->nullable()
                    ->comment('Googleネットワークの検索結果ページやウェブサイトに広告が表示された回数をカウントします。');
                $table->double('interactionRate')->nullable()
                    ->comment('広告が表示された後にユーザーがどのくらい頻繁に広告を操作するか。これはインタラクションの数を広告の表示回数で割ったものです。 "x.xx％"として返されるパーセンテージ。');
                $table->bigInteger('interactions')->nullable()
                    ->comment('相互作用の数インタラクションとは、テキストやショッピング広告のクリック、動画広告の表示など、広告フォーマットに関連する主要なユーザーアクションです。');
                $table->text('interactionTypes')->nullable()
                    ->comment('Interactions、InteractionRate、およびAverageCost列に反映される相互作用のタイプ。');
                $table->boolean('isNegative')->nullable()->comment('この行の基準が否定（除外）基準であるかどうかを示します。');
                $table->string('matchType', 50)->nullable()->comment('キーワードのマッチタイプ。');
                $table->text('labelIDs')->nullable()
                    ->comment('この行の主要オブジェクトのラベルIDのリスト。リスト要素はJSONリスト形式で返されます。');
                $table->text('labels')->nullable()
                    ->comment('この行の主要なオブジェクトのラベル名のリスト。リスト要素はJSONリスト形式で返されます。');
                $table->string('month', 50)->nullable()->comment('月の最初の日。yyyy-MM-ddの形式です。');
                $table->string('monthOfYear', 50)->nullable()->comment('月の名前です（例：「12月」）。');
                $table->string('landingPageExperience', 50)->nullable()->comment('ランディングページの品質スコア。');
                $table->integer('qualityScore')->nullable()
                    ->comment('AdGroupCriterionの品質スコア。範囲は1（最低）〜10（最高）です。品質スコア情報がない場合、 " - "が返されます。 「HasQualityScore」列を使用してフィルタを適用して、QualityScoreフィールドの値の有無にかかわらず条件を含めるか除外することができます。詳細については、レポートコンセプトガイド（URL：https://developers.google.com/adwords/api/docs/guides/reporting-concepts#quality_score_in_reports）をご覧ください。');
                $table->date('quarter')->nullable()
                    ->comment('四半期の最初の日は、yyyy-MM-ddの形式です。四半期の暦年を使用します。たとえば、2014年第2四半期は2014-04-01に開始します。');
                $table->string('expectedClickthroughRate', 50)->nullable()->comment('他の広告主様のクリック率と比較して');
                $table->string('keywordState', 50)->nullable()
                    ->comment('この行のメインオブジェクトのステータス。たとえば、キャンペーンの掲載結果レポートでは、これが各行のキャンペーンのステータスになります。広告グループの掲載結果レポートでは、これは各行の広告グループのステータスになります。');
                $table->string('criterionServingStatus', 50)->nullable()->comment('基準のステータスを提供します。');
                $table->double('topOfPageCPC')->nullable()
                    ->comment('検索結果の最初のページの上部に広告を表示するために必要なクリック単価の見積もり。通常マイクロ秒単位の数字ですが、自動入札機能が使用されている場合は「auto：」という接頭辞が付いていてもよく、単に「auto」という文字列であってもかまいません。');
                $table->text('trackingTemplate')->nullable()->comment('この行のメインオブジェクトのトラッキングテンプレート。');
                $table->text('customParameter')->nullable()
                    ->comment('この行のメインオブジェクトのカスタムURLパラメータ。 CustomParameters要素はJSONマップ形式で返されます。');
                $table->double('valueAllConv')->nullable()
                    ->comment('すべてのコンバージョンの平均値です。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->double('valueConv')->nullable()
                    ->comment('コンバージョン数の合計をコンバージョン数で割った値。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->double('valueConvCurrentModel')->nullable()
                    ->comment('現在選択しているアトリビューションモデルで、過去の「ValuePerConversion」データがどのように表示されるかを示します。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->bigInteger('verticalID')->nullable()->comment('垂直のID。');
                $table->date('week')->nullable()->comment('yyyy-MM-ddの形式の月曜日の日付。');
                $table->integer('year')->nullable()->comment('年はyyyyの形式です。');
                $table->bigInteger('accountid')->comment('media id');

                $table->unique('id', 'id_UNIQUE');
                $table->index('exeDate', 'repo_adw_keywords_report_cost1');
                $table->index('startDate', 'repo_adw_keywords_report_cost2');
                $table->index('endDate', 'repo_adw_keywords_report_cost3');
                $table->index('account_id', 'repo_adw_keywords_report_cost4');
                $table->index('campaign_id', 'repo_adw_keywords_report_cost5');
                $table->index('network', 'repo_adw_keywords_report_cost6');
                $table->index('networkWithSearchPartners', 'repo_adw_keywords_report_cost7');
                $table->index('clickType', 'repo_adw_keywords_report_cost8');
                $table->index('day', 'repo_adw_keywords_report_cost9');
                $table->index('dayOfWeek', 'repo_adw_keywords_report_cost10');
                $table->index('device', 'repo_adw_keywords_report_cost11');
                $table->index('month', 'repo_adw_keywords_report_cost12');
                $table->index('monthOfYear', 'repo_adw_keywords_report_cost13');
                $table->index('quarter', 'repo_adw_keywords_report_cost14');
                $table->index('week', 'repo_adw_keywords_report_cost15');
                $table->index('year', 'repo_adw_keywords_report_cost16');
                $table->index('currency', 'repo_adw_keywords_report_cost17');
                $table->index('timeZone', 'repo_adw_keywords_report_cost18');
                $table->index('adGroupID', 'repo_adw_keywords_report_cost19');
                $table->index('adGroupState', 'repo_adw_keywords_report_cost20');
                $table->index('approvalStatus', 'repo_adw_keywords_report_cost21');
                $table->index('baseAdGroupID', 'repo_adw_keywords_report_cost22');
                $table->index('baseCampaignID', 'repo_adw_keywords_report_cost23');
                $table->index('bidStrategyID', 'repo_adw_keywords_report_cost24');
                $table->index('biddingStrategySource', 'repo_adw_keywords_report_cost25');
                $table->index('bidStrategyType', 'repo_adw_keywords_report_cost26');
                $table->index('conversionOptimizerBidType', 'repo_adw_keywords_report_cost27');
                $table->index('campaignID', 'repo_adw_keywords_report_cost28');
                $table->index('campaignState', 'repo_adw_keywords_report_cost29');
                $table->index('maxCPC', 'repo_adw_keywords_report_cost30');
                $table->index('maxCPCSource', 'repo_adw_keywords_report_cost31');
                $table->index('maxCPM', 'repo_adw_keywords_report_cost32');
                $table->index('adRelevance', 'repo_adw_keywords_report_cost33');
                $table->index('enhancedCPCEnabled', 'repo_adw_keywords_report_cost34');
                $table->index('estAddClicksWkFirstPositionBid', 'repo_adw_keywords_report_cost35');
                $table->index('estAddCostWkFirstPositionBid', 'repo_adw_keywords_report_cost36');
                $table->index('customerID', 'repo_adw_keywords_report_cost37');
                $table->index('firstPageCPC', 'repo_adw_keywords_report_cost38');
                $table->index('firstPositionCPC', 'repo_adw_keywords_report_cost39');
                $table->index('hasQualityScore', 'repo_adw_keywords_report_cost40');
                $table->index('keywordID', 'repo_adw_keywords_report_cost41');
                $table->index('isNegative', 'repo_adw_keywords_report_cost42');
                $table->index('matchType', 'repo_adw_keywords_report_cost43');
                $table->index('landingPageExperience', 'repo_adw_keywords_report_cost44');
                $table->index('qualityScore', 'repo_adw_keywords_report_cost45');
                $table->index('expectedClickthroughRate', 'repo_adw_keywords_report_cost46');
                $table->index('keywordState', 'repo_adw_keywords_report_cost47');
                $table->index('criterionServingStatus', 'repo_adw_keywords_report_cost48');
                $table->index('topOfPageCPC', 'repo_adw_keywords_report_cost49');
                $table->index('verticalID', 'repo_adw_keywords_report_cost50');
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
        Schema::dropIfExists('repo_adw_keywords_report_cost');
    }
}
