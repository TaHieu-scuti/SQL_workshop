<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoAdwKeywordsReportConv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'repo_adw_keywords_report_conv',
            function (Blueprint $table) {
                $table->increments('id', 11);
                $table->date('exeDate')->comment('レポートAPI実行日');
                $table->date('startDate')->comment('APIで指定したレポートの終了日');
                $table->date('endDate')->comment('APIで指定したレポートの終了日');
                $table->string('account_id', 50)->comment('ADgainerシステムのアカウントID');
                $table->string('campaign_id', 50)->comment('ADgainerシステムのキャンペーンID。destinationURLのクエリパラメータを分解して取得');
                $table->string('currency', 50)->comment('顧客口座の通貨。');
                $table->text('account')->comment('カスタマーアカウントのわかりやすい名前。')->nullable();
                $table->string('timeZone')->comment('顧客アカウント用に選択されたタイムゾーンの名前。 たとえば、「（GMT-05：00）東部時間」などです。 このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。')
                        ->nullable();
                $table->bigInteger('adGroupId', 20)->comment('広告グループのID。')->nullable();
                $table->text('adGroup')->comment('広告グループの名前。')->nullable();
                $table->string('adGroupState', 50)->comment('広告グループのステータス。')->nullable();
                $table->string('network', 50)->comment('第1レベルのネットワークタイプ。')->nullable();
                $table->string('networkWithSearchPartners', 50)->comment('第2レベルのネットワークタイプ（検索パートナーを含む）。')->nullable();
                $table->double('allConvRate')->comment('AllConversionsをコンバージョントラッキングできる合計クリック数で割ったものです。これは、広告のクリックがコンバージョンにつながった頻度です。')->nullable();
                $table->double('allConv')->comment('第2レベルのネットワークタイプ（検索パートナーを含む）。')->nullable();
                $table->double('allConvValue')->comment('推定されたものを含む、すべてのコンバージョンの合計値。')->nullable();
                $table->string('approvalStatus', 50)->comment('基準の承認ステータス。')->nullable();
                $table->bigInteger('baseAdGroupID', 20)->comment('試用広告グループの基本広告グループのID。通常の広告グループの場合、これはAdGroupIdと同じです。')->nullable();
                $table->bigInteger('baseCampaignID', 20)->comment('試用キャンペーンの基本キャンペーンのID。通常のキャンペーンの場合、これはCampaignIdと同じです。')->nullable();
                $table->bigInteger('bidStrategyID', 20)->comment('BiddingStrategyConfigurationのIDです。')->nullable();
                $table->text('bidStrategyName')->comment('BiddingStrategyConfigurationの名前。')->nullable();
                $table->string('biddingStrategySource', 50)->comment('入札戦略が関連付けられている場所（キャンペーン、広告グループ、広告グループの条件など）を示します。')->nullable();
                $table->string('bidStrategyType', 50)->comment('BiddingStrategyConfigurationのタイプ。')->nullable();
                $table->string('conversionOptimizerBidType', 50)->comment('入札タイプ。')->nullable();
                $table->bigInteger('campaignID', 20)->comment('キャンペーンのID。')->nullable();
                $table->text('campaign')->comment('キャンペーンの名前。')->nullable();
                $table->string('campaignState', 50)->comment('キャンペーンのステータス。')->nullable();
                $table->string('clickType', 50)->comment('[インプレッション数]フィールドには、そのクリックタイプで広告が配信された頻度が反映されます。 広告は複数のクリックタイプで表示できるため、インプレッション数は2倍になり、合計が正確でない可能性があります。')->nullable();
                $table->string('conversionCategory', 255)->comment('ユーザーがコンバージョンを達成するために実行するアクションを表すカテゴリ。ゼロ変換の行が返されないようにします。値：「ダウンロード」、「リード」、「購入/販売」、「サインアップ」、「キーページの表示」、「その他」の値。')->nullable();
                $table->double('convRate')->comment('コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったものです。')->nullable();
                $table->double('conversions')->comment('最適化を選択したすべてのコンバージョンアクションのコンバージョン数。')->nullable();
                $table->bigInteger('conversionTrackerId', 20)->comment('コンバージョントラッカーのID。')->nullable();
                $table->string('conversionName', 255)->comment('コンバージョンタイプの名前。ゼロ変換の行が返されないようにします。')->nullable();
                $table->double('totalConvValue')->comment('すべてのコンバージョンのコンバージョン値の合計。')->nullable();
                $table->double('costAllConv')->comment('総費用をすべてのコンバージョンで割った値。')->nullable();
                $table->double('costConvCurrentModel')->comment('コンバージョントラッキングクリック数に起因する費用をコンバージョン数で割った値')->nullable();
                $table->double('maxCPC')->comment('クリック単価制。値は、a）小額の金額、b）AdWordsが自動的に選択された入札戦略で入札単価を設定する場合は「自動：x」または「自動」、c）クリック単価が適用されない場合は「 - 」のいずれかです行に')->nullable();
                $table->string('maxCPCSource', 50)->comment('CPC入札のソース。')->nullable();
                $table->double('maxCPM')->comment('CPM（1,000インプレッションあたりの単価）の単価')->nullable();
                $table->string('adRelevance', 50)->comment('広告の品質スコア')->nullable();
                $table->text('keyword')->comment('Criterionの記述的な文字列。レポートの条件タイプのフォーマットの詳細については、レポートガイドのCriteriaプレフィックスセクション（URL：https://developers.google.com/adwords/api/docs/guides/reporting#criteria_prefixes）を参照してください。')->nullable();
                $table->text('destinationURL')->comment('広告を表示した条件のリンク先URL。')->nullable();
                $table->double('crossDeviceConv')->comment('顧客が1つの端末でAdWords広告をクリックしてから別の端末やブラウザで変換した後のコンバージョンデバイス間のコンバージョンは既にAllConversions列に含まれています。')->nullable();
                $table->double('conversionsCurrentModel')->comment('現在選択しているアトリビューションモデルでの過去の「コンバージョン」データの表示方法を示します。')->nullable();
                $table->double('convValueCurrentModel')->comment('現在選択しているアトリビューションモデルで、過去の「ConversionValue」データがどのように表示されるかを示します。')->nullable();
                $table->text('clientName')->comment('カスタマーのわかりやすい名前。')->nullable();
                $table->date('day')->comment('日付はyyyy-MM-ddの形式になります。')->nullable();
                $table->string('dayOfWeek', 50)->comment('曜日の名前です（例：「月曜日」）。')->nullable();
                $table->string('device', 50)->comment('インプレッションが表示されたデバイスの種類。')->nullable();
                $table->boolean('enhancedCPCEnabled')->comment('入札戦略でエンハンストCPCが有効になっているかどうかを示します。')->nullable();
                $table->double('estAddClicksWkFirstPositionBid')->comment('FirstPositionCpcの値にキーワードの入札単価を変更すると、1週間あたりのクリック数を見積もることができます。')->nullable();
                $table->double('estAddCostWkFirstPositionBid')->comment('FirstPositionCpcの値にキーワードの入札単価を変更すると、週あたりの費用の見積もりが変わる可能性があります。')->nullable();
                $table->string('conversionSource', 50)->comment('ウェブサイトなどの変換元、通話からのインポート。')->nullable();
                $table->bigInteger('customerID', 20)->comment('顧客ID。')->nullable();
                $table->text('appFinalURL', 20)->comment('この行のメインオブジェクトの最終的なアプリURLのリスト。リストのエントリは、a）「android-app：」（Androidアプリの場合）またはb）「os-app：」（iOSアプリの場合）のいずれかで始まります。 AppUrlList要素はJSONリスト形式で返されます。')->nullable();
                $table->text('mobileFinalURL', 20)->comment('この行のメインオブジェクトの最終的なモバイルURLのリスト。 UrlList要素はJSONリスト形式で返されます。')->nullable();
                $table->text('finalURL', 20)->comment('この行の主要オブジェクトの最終的なURLのリスト。 UrlList要素はJSONリスト形式で返されます。')->nullable();
                $table->double('firstPageCPC')->comment('検索結果の最初のページに広告を表示するために必要なクリック単価の見積もり。通常マイクロ秒単位の数字ですが、自動入札機能が使用されている場合は「auto：」という接頭辞が付いていてもよく、単に「auto」という文字列であってもかまいません。')->nullable();
                $table->double('firstPositionCPC')->comment('広告がGoogle検索結果の最初のページの最初の位置に表示されるのに必要な金額を見積もります。通常マイクロ秒単位の数字ですが、自動入札機能が使用されている場合は「auto：」という接頭辞が付いていてもよく、単に「auto」という文字列であってもかまいません。')->nullable();
                $table->bigInteger('keywordID', 20)->comment('この行の主オブジェクトのID。')->nullable();
                $table->boolean('isNegative')->comment('この行の基準が否定（除外）基準であるかどうかを示します。')->nullable();
                $table->string('matchType', 50)->comment('キーワードのマッチタイプ。')->nullable();
                $table->text('labelIDs')->comment('この行の主要オブジェクトのラベルIDのリスト。リスト要素はJSONリスト形式で返されます。この行の主要なオブジェクトのラベル名のリスト。')->nullable();
                $table->text('labels')->comment('リスト要素はJSONリスト形式で返されます。')->nullable();
                $table->date('month')->comment('月の最初の日。yyyy-MM-ddの形式です。')->nullable();
                $table->string('monthOfYear', 50)->comment('月の名前です（例：「12月」）。')->nullable();
                $table->string('landingPageExperience', 50)->comment('ランディングページの品質スコア。')->nullable();
                $table->bigInteger('qualityScore')->comment('AdGroupCriterionの品質スコア。範囲は1（最低）〜10（最高）です。品質スコア情報がない場合、 " - "が返されます。 「HasQualityScore」列を使用してフィルタを適用して、QualityScoreフィールドの値の有無にかかわらず条件を含めるか除外することができます。詳細については、レポートコンセプトガイド（URL：https://developers.google.com/adwords/api/docs/guides/reporting-concepts#quality_score_in_reports）をご覧ください。')->nullable();
                $table->date('quarter')->comment('四半期の最初の日は、yyyy-MM-ddの形式です。四半期の暦年を使用します。たとえば、2014年第2四半期は2014-04-01に開始します。')->nullable();
                $table->string('expectedClickthroughRate', 50)->comment('他の広告主様のクリック率と比較して')->nullable();
                $table->string('keywordState', 50)->comment('この行のメインオブジェクトのステータス。たとえば、キャンペーンの掲載結果レポートでは、これが各行のキャンペーンのステータスになります。広告グループの掲載結果レポートでは、これは各行の広告グループのステータスになります。')->nullable();
                $table->string('criterionServingStatus', 50)->comment('基準のステータスを提供します。')->nullable();
                $table->double('topOfPageCPC')->comment('検索結果の最初のページの上部に広告を表示するために必要なクリック単価の見積もり。通常マイクロ秒単位の数字ですが、自動入札機能が使用されている場合は「auto：」という接頭辞が付いていてもよく、単に「auto」という文字列であってもかまいません。')->nullable();
                $table->text('trackingTemplate')->comment('この行のメインオブジェクトのトラッキングテンプレート。')->nullable();
                $table->text('customParameter')->comment('この行のメインオブジェクトのカスタムURLパラメータ。 CustomParameters要素はJSONマップ形式で返されます。')->nullable();
                $table->double('valueAllConv')->comment('すべてのコンバージョンの平均値です。')->nullable();
                $table->double('valueConv')->comment('コンバージョン数の合計をコンバージョン数で割った値。')->nullable();
                $table->double('valueConvCurrentModel')->comment('現在選択しているアトリビューションモデルで、過去の「ValuePerConversion」データがどのように表示されるかを示します。')->nullable();
                $table->bigInteger('verticalID', 20)->comment('垂直のID。')->nullable();
                $table->date('week')->comment('yyyy-MM-ddの形式の月曜日の日付。')->nullable();
                $table->bigInteger('year')->comment('年はyyyyの形式です。')->nullable();

                $table->unique('id', 'id_UNIQUE');
                $table->index('exeDate', 'repo_adw_keywords_report_conv1');
                $table->index('startDate', 'repo_adw_keywords_report_conv2');
                $table->index('endDate', 'repo_adw_keywords_report_conv3');
                $table->index('account_id', 'repo_adw_keywords_report_conv4');
                $table->index('campaign_id', 'repo_adw_keywords_report_conv5');
                $table->index('currency', 'repo_adw_keywords_report_conv6');
                $table->index('timeZone', 'repo_adw_keywords_report_conv7');
                $table->index('adGroupID', 'repo_adw_keywords_report_conv8');
                $table->index('adGroupState', 'repo_adw_keywords_report_conv9');
                $table->index('network', 'repo_adw_keywords_report_conv10');
                $table->index('networkWithSearchPartners', 'repo_adw_keywords_report_conv11');
                $table->index('approvalStatus', 'repo_adw_keywords_report_conv12');
                $table->index('baseAdGroupID', 'repo_adw_keywords_report_conv13');
                $table->index('baseCampaignID', 'repo_adw_keywords_report_conv14');
                $table->index('bidStrategyID', 'repo_adw_keywords_report_conv15');
                $table->index('biddingStrategySource', 'repo_adw_keywords_report_conv16');
                $table->index('bidStrategyType', 'repo_adw_keywords_report_conv17');
                $table->index('conversionOptimizerBidType', 'repo_adw_keywords_report_conv18');
                $table->index('campaignID', 'repo_adw_keywords_report_conv19');
                $table->index('campaignState', 'repo_adw_keywords_report_conv20');
                $table->index('clickType', 'repo_adw_keywords_report_conv21');
                $table->index('conversionCategory', 'repo_adw_keywords_report_conv22');
                $table->index('conversionTrackerId', 'repo_adw_keywords_report_conv23');
                $table->index('conversionName', 'repo_adw_keywords_report_conv24');
                $table->index('maxCPC', 'repo_adw_keywords_report_conv25');
                $table->index('maxCPCSource', 'repo_adw_keywords_report_conv26');
                $table->index('maxCPM', 'repo_adw_keywords_report_conv27');
                $table->index('adRelevance', 'repo_adw_keywords_report_conv28');
                $table->index('day', 'repo_adw_keywords_report_conv29');
                $table->index('dayOfWeek', 'repo_adw_keywords_report_conv30');
                $table->index('device', 'repo_adw_keywords_report_conv31');
                $table->index('enhancedCPCEnabled', 'repo_adw_keywords_report_conv32');
                $table->index('estAddClicksWkFirstPositionBid', 'repo_adw_keywords_report_conv33');
                $table->index('estAddCostWkFirstPositionBid', 'repo_adw_keywords_report_conv34');
                $table->index('conversionSource', 'repo_adw_keywords_report_conv35');
                $table->index('customerID', 'repo_adw_keywords_report_conv36');
                $table->index('firstPageCPC', 'repo_adw_keywords_report_conv37');
                $table->index('firstPositionCPC', 'repo_adw_keywords_report_conv38');
                $table->index('hasQualityScore', 'repo_adw_keywords_report_conv39');
                $table->index('keywordID', 'repo_adw_keywords_report_conv40');
                $table->index('isNegative', 'repo_adw_keywords_report_conv41');
                $table->index('matchType', 'repo_adw_keywords_report_conv42');
                $table->index('month', 'repo_adw_keywords_report_conv43');
                $table->index('monthOfYear', 'repo_adw_keywords_report_conv44');
                $table->index('landingPageExperience', 'repo_adw_keywords_report_conv45');
                $table->index('qualityScore', 'repo_adw_keywords_report_conv46');
                $table->index('quarter', 'repo_adw_keywords_report_conv47');
                $table->index('expectedClickthroughRate', 'repo_adw_keywords_report_conv48');
                $table->index('keywordState', 'repo_adw_keywords_report_conv49');
                $table->index('criterionServingStatus', 'repo_adw_keywords_report_conv50');
                $table->index('topOfPageCPC', 'repo_adw_keywords_report_conv51');
                $table->index('verticalID', 'repo_adw_keywords_report_conv52');
                $table->index('week', 'repo_adw_keywords_report_conv53');
                $table->index('year', 'repo_adw_keywords_report_conv54');
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
        Schema::dropIfExists('repo_adw_keywords_report_conv');
    }
}
