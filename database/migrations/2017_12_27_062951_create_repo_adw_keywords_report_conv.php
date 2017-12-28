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
        DB::statement(
            "CREATE TABLE `repo_adw_keywords_report_conv` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `exeDate` DATE NOT NULL COMMENT 'レポートAPI実行日',
                `startDate` DATE NOT NULL COMMENT 'APIで指定したレポートの開始日',
                `endDate` DATE NOT NULL COMMENT 'APIで指定したレポートの終了日',
                `account_id` VARCHAR(50) NOT NULL COMMENT 'ADgainerシステムのアカウントID',
                `campaign_id` VARCHAR(50) NOT NULL COMMENT 'ADgainerシステムのキャンペーンID。destinationURLのクエリパラメータを分解して取得',
                `currency` VARCHAR(50) NULL COMMENT '顧客口座の通貨。',
                `account` TEXT NULL COMMENT 'カスタマーアカウントのわかりやすい名前。',
                `timeZone` VARCHAR(50) NULL COMMENT '顧客アカウント用に選択されたタイムゾーンの名前。
                たとえば、「（GMT-05：00）東部時間」などです。 このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。',
                `adGroupID` INT(20) NULL COMMENT '広告グループのID。',
                `adGroup` TEXT NULL COMMENT '広告グループの名前。',
                `adGroupState` VARCHAR(50) NULL COMMENT '広告グループのステータス。',
                `network` VARCHAR(50) NULL COMMENT '第1レベルのネットワークタイプ。',
                `networkWithSearchPartners` VARCHAR(50) NULL COMMENT '第2レベルのネットワークタイプ（検索パートナーを含む）。',
                `allConvRate` Double NULL COMMENT 'AllConversionsをコンバージョントラッキングできる合計ク
                リック数で割ったものです。これは、広告のクリックがコンバージョンにつながった頻度です。 ',
                `allConv` Double NULL COMMENT 'AdWordsが推進するコンバージョン数の最善の見積もり。ウェブサイト、クロスデバイス、電話通話のコンバージョンが含まれます。',
                `allConvValue` Double NULL COMMENT '推定されたものを含む、すべてのコンバージョンの合計値。',
                `approvalStatus` VARCHAR(50) NULL COMMENT '基準の承認ステータス。',
                `baseAdGroupID` INT(20) NULL COMMENT '試用広告グループの基本広告グループのID。通常の広告グループの場合、これはAdGroupIdと同じです。',
                `baseCampaignID` INT(20) NULL COMMENT '試用キャンペーンの基本キャンペーンのID。通常のキャンペーンの場合、これはCampaignIdと同じです。',
                `bidStrategyID` INT(20) NULL COMMENT 'BiddingStrategyConfigurationのIDです。',
                `bidStrategyName` TEXT NULL COMMENT 'BiddingStrategyConfigurationの名前。',
                `biddingStrategySource` VARCHAR(50) NULL COMMENT '入札戦略が関連付けられている場所（キャンペーン、広告グループ、広告グループの条件など）を示します。',
                `bidStrategyType` VARCHAR(50) NULL COMMENT 'BiddingStrategyConfigurationのタイプ。',
                `conversionOptimizerBidType` VARCHAR(50) NULL COMMENT '入札タイプ。',
                `campaignID` INT(20) NULL COMMENT 'キャンペーンのID。',
                `campaign` TEXT NULL COMMENT 'キャンペーンの名前。',
                `campaignState` VARCHAR(50) NULL COMMENT 'キャンペーンのステータス。',
                `clickType` VARCHAR(50) NULL COMMENT '[インプレッション数]フィールドには、そのクリックタイプで広告が配信された頻度が反映されます。
                 広告は複数のクリックタイプで表示できるため、インプレッション数は2倍になり、合計が正確でない可能性があります。',
                `conversionCategory` VARCHAR(255) NULL COMMENT 'ユーザーがコンバージョンを達成するために実行す
                るアクションを表すカテゴリ。ゼロ変換の行が返されないようにします。値：「ダウンロード」、「リード」、「購入/販売」、「サインアップ」、「キーページの表示」、「その他」の値。',
                `convRate` Double NULL COMMENT 'コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったものです。 ',
                `conversions` Double NULL COMMENT '最適化を選択したすべてのコンバージョンアクションのコンバージョン数。',
                `conversionTrackerId` INT(20) NULL COMMENT 'コンバージョントラッカーのID。',
                `conversionName` VARCHAR(255) NULL COMMENT 'コンバージョンタイプの名前。ゼロ変換の行が返されないようにします。',
                `totalConvValue` Double NULL COMMENT 'すべてのコンバージョンのコンバージョン値の合計。',
                `costAllConv` Double NULL COMMENT '総費用をすべてのコンバージョンで割った値。',
                `costConv` Double NULL COMMENT 'コンバージョントラッキングクリック数に起因する費用をコンバージョン数で割った値',
                `costConvCurrentModel` Double NULL COMMENT '現在選択しているアトリビューションモデルで、
                過去の「CostPerConversion」データがどのように表示されるかを示します。',
                `maxCPC` Double NULL COMMENT 'クリック単価制。値は、
                a）小額の金額、
                b）AdWordsが自動的に選択された入札戦略で入札単価を設定する場合は「自動：x」または「自動」、
                c）クリック単価が適用されない場合は「 - 」のいずれかです行に',
                `maxCPCSource` VARCHAR(50) NULL COMMENT 'CPC入札のソース。',
                `maxCPM` Double NULL COMMENT 'CPM（1,000インプレッションあたりの単価）の単価',
                `adRelevance` VARCHAR(50) NULL COMMENT '広告の品質スコア',
                `keyword` TEXT NULL COMMENT 'Criterionの記述的な文字列。レポートの条件タイプのフォーマットの詳細については、
                レポートガイドのCriteriaプレフィックスセクション
                （URL：https://developers.google.com/adwords/api/docs/guides/reporting#criteria_prefixes）を参照してください。',
                `destinationURL` TEXT NULL COMMENT '広告を表示した条件のリンク先URL。',
                `crossDeviceConv` Double NULL COMMENT '顧客が1つの端末でAdWords広告をクリックしてから別の端末や
                ブラウザで変換した後のコンバージョンデバイス間のコンバージョンは既にAllConversions列に含まれています。',
                `conversionsCurrentModel` Double NULL COMMENT '現在選択しているアトリビューションモデルでの過去の「コンバージョン」データの表示方法を示します。',
                `convValueCurrentModel` Double NULL COMMENT '現在選択しているアトリビューションモデルで、
                過去の「ConversionValue」データがどのように表示されるかを示します。',
                `clientName` TEXT NULL COMMENT 'カスタマーのわかりやすい名前。',
                `day` Date NULL COMMENT '日付はyyyy-MM-ddの形式になります。',
                `dayOfWeek` VARCHAR(50) NULL COMMENT '曜日の名前です（例：「月曜日」）。',
                `device` VARCHAR(50) NULL COMMENT 'インプレッションが表示されたデバイスの種類。',
                `enhancedCPCEnabled` Boolean NULL COMMENT '
                入札戦略でエンハンストCPCが有効になっているかどうかを示します。',
                `estAddClicksWkFirstPositionBid` INT(20) NULL COMMENT 'FirstPositionCpcの値にキーワード
                の入札単価を変更すると、1週間あたりのクリック数を見積もることができます。',
                `estAddCostWkFirstPositionBid` Double NULL COMMENT 'FirstPositionCpcの値にキーワードの入札
                単価を変更すると、週あたりの費用の見積もりが変わる可能性があります。',
                `conversionSource` VARCHAR(50) NULL COMMENT 'ウェブサイトなどの変換元、通話からのインポート。',
                `customerID` INT(20) NULL COMMENT '顧客ID。',
                `appFinalURL` TEXT NULL COMMENT 'この行のメインオブジェクトの最終的なアプリURLのリスト。リストの
                エントリは、a）「android-app：」（Androidアプリの場合）またはb）「os-app：」（iOSアプリの場合）
                のいずれかで始まります。 AppUrlList要素はJSONリスト形式で返されます。',
                `mobileFinalURL` TEXT NULL COMMENT 'この行のメインオブジェクトの最終的なモバイルURLのリスト。 UrlList要素はJSONリスト形式で返されます。',
                `finalURL` TEXT NULL COMMENT 'この行の主要オブジェクトの最終的なURLのリスト。 UrlList要素はJSONリスト形式で返されます。',
                `firstPageCPC` Double NULL COMMENT '検索結果の最初のページに広告を表示するために必要なクリッ
                ク単価の見積もり。通常マイクロ秒単位の数字ですが、自動入札機能が使用されている場合は「auto：」
                という接頭辞が付いていてもよく、単に「auto」という文字列であってもかまいません。',
                `firstPositionCPC` Double NULL COMMENT '広告がGoogle検索結果の最初のページの最初の位置に表示さ
                れるのに必要な金額を見積もります。通常マイクロ秒単位の数字ですが、自動入札機能が使用されている場合は
                「auto：」という接頭辞が付いていてもよく、単に「auto」という文字列であってもかまいません。',
                `hasQualityScore` Boolean NULL COMMENT '基準のQualityScoreフィールドに値があるかどうか。
                レポート要求述部のこのフィールドを使用して、QualityScoreフィールドの値の有無にかかわらず条件を含めるか除外します。',
                `keywordID` INT(20) NULL COMMENT 'この行の主オブジェクトのID。',
                `isNegative` Boolean NULL COMMENT 'この行の基準が否定（除外）基準であるかどうかを示します。',
                `matchType` VARCHAR(50) NULL COMMENT 'キーワードのマッチタイプ。',
                `labelIDs` TEXT NULL COMMENT 'この行の主要オブジェクトのラベルIDのリスト。リスト要素はJSONリスト形式で返されます。この行の主要なオブジェクトのラベル名のリスト。',
                `labels` TEXT NULL COMMENT 'リスト要素はJSONリスト形式で返されます。',
                `month` Date NULL COMMENT '月の最初の日。yyyy-MM-ddの形式です。',
                `monthOfYear` VARCHAR(50) NULL COMMENT '月の名前です（例：「12月」）。',
                `landingPageExperience` VARCHAR(50) NULL COMMENT 'ランディングページの品質スコア。',
                `qualityScore` INT NULL COMMENT 'AdGroupCriterionの品質スコア。範囲は1（最低）〜10（最高）です。品質スコア情報がない場合、
                - が返されます。 「HasQualityScore」列を使用してフィルタを適用して、QualityScoreフィールドの値
                の有無にかかわらず条件を含めるか除外することができます。詳細については、レポートコンセプトガイド
                （URL：https://developers.google.com/adwords/api/docs/guides/
                reporting-concepts#quality_score_in_reports）をご覧ください。',
                `quarter` Date NULL COMMENT '四半期の最初の日は、yyyy-MM-ddの形式です。四半期の暦年を使用します。たとえば、2014年第2四半期は2014-04-01に開始します。',
                `expectedClickthroughRate` VARCHAR(50) NULL COMMENT '他の広告主様のクリック率と比較して',
                `keywordState` VARCHAR(50) NULL COMMENT 'この行のメインオブジェクトのステータス。たとえば、
                キャンペーンの掲載結果レポートでは、これが各行のキャンペーンのステータスになります。広告グループの
                掲載結果レポートでは、これは各行の広告グループのステータスになります。',
                `criterionServingStatus` VARCHAR(50) NULL COMMENT '基準のステータスを提供します。',
                `topOfPageCPC` Double NULL COMMENT '検索結果の最初のページの上部に広告を表示するために必要なクリ
                ック単価の見積もり。通常マイクロ秒単位の数字ですが、自動入札機能が使用されている場合は「auto：」
                という接頭辞が付いていてもよく、単に「auto」という文字列であってもかまいません。',
                `trackingTemplate` TEXT NULL COMMENT 'この行のメインオブジェクトのトラッキングテンプレート。',
                `customParameter` TEXT NULL COMMENT 'この行のメインオブジェクトのカスタムURLパラメータ。 CustomParameters要素はJSONマップ形式で返されます。',
                `valueAllConv` Double NULL COMMENT 'すべてのコンバージョンの平均値です。',
                `valueConv` Double NULL COMMENT 'コンバージョン数の合計をコンバージョン数で割った値。',
                `valueConvCurrentModel` Double NULL COMMENT '現在選択しているアトリビューションモデルで、
                過去の「ValuePerConversion」データがどのように表示されるかを示します。',
                `verticalID` INT(20) NULL COMMENT '垂直のID。',
                `week` Date NULL COMMENT 'yyyy-MM-ddの形式の月曜日の日付。',
                `year` INT NULL COMMENT '年はyyyyの形式です。',
                PRIMARY KEY (`id`),
                UNIQUE INDEX `id_UNIQUE` (`id` ASC),
                INDEX `repo_adw_keywords_report_conv1` (`exeDate` ASC),
                INDEX `repo_adw_keywords_report_conv2` (`startDate` ASC),
                INDEX `repo_adw_keywords_report_conv3` (`endDate` ASC),
                INDEX `repo_adw_keywords_report_conv4` (`account_id` ASC),
                INDEX `repo_adw_keywords_report_conv5` (`campaign_id` ASC),
                INDEX `repo_adw_keywords_report_conv6` (`currency` ASC),
                INDEX `repo_adw_keywords_report_conv7` (`timeZone` ASC),
                INDEX `repo_adw_keywords_report_conv8` (`adGroupID` ASC),
                INDEX `repo_adw_keywords_report_conv9` (`adGroupState` ASC),
                INDEX `repo_adw_keywords_report_conv10` (`network` ASC),
                INDEX `repo_adw_keywords_report_conv11` (`networkWithSearchPartners` ASC),
                INDEX `repo_adw_keywords_report_conv12` (`approvalStatus` ASC),
                INDEX `repo_adw_keywords_report_conv13` (`baseAdGroupID` ASC),
                INDEX `repo_adw_keywords_report_conv14` (`baseCampaignID` ASC),
                INDEX `repo_adw_keywords_report_conv15` (`bidStrategyID` ASC),
                INDEX `repo_adw_keywords_report_conv16` (`biddingStrategySource` ASC),
                INDEX `repo_adw_keywords_report_conv17` (`bidStrategyType` ASC),
                INDEX `repo_adw_keywords_report_conv18` (`conversionOptimizerBidType` ASC),
                INDEX `repo_adw_keywords_report_conv19` (`campaignID` ASC),
                INDEX `repo_adw_keywords_report_conv20` (`campaignState` ASC),
                INDEX `repo_adw_keywords_report_conv21` (`clickType` ASC),
                INDEX `repo_adw_keywords_report_conv22` (`conversionCategory` ASC),
                INDEX `repo_adw_keywords_report_conv23` (`conversionTrackerId` ASC),
                INDEX `repo_adw_keywords_report_conv24` (`conversionName` ASC),
                INDEX `repo_adw_keywords_report_conv25` (`maxCPC` ASC),
                INDEX `repo_adw_keywords_report_conv26` (`maxCPCSource` ASC),
                INDEX `repo_adw_keywords_report_conv27` (`maxCPM` ASC),
                INDEX `repo_adw_keywords_report_conv28` (`adRelevance` ASC),
                INDEX `repo_adw_keywords_report_conv29` (`day` ASC),
                INDEX `repo_adw_keywords_report_conv30` (`dayOfWeek` ASC),
                INDEX `repo_adw_keywords_report_conv31` (`device` ASC),
                INDEX `repo_adw_keywords_report_conv32` (`enhancedCPCEnabled` ASC),
                INDEX `repo_adw_keywords_report_conv33` (`estAddClicksWkFirstPositionBid` ASC),
                INDEX `repo_adw_keywords_report_conv34` (`estAddCostWkFirstPositionBid` ASC),
                INDEX `repo_adw_keywords_report_conv35` (`conversionSource` ASC),
                INDEX `repo_adw_keywords_report_conv36` (`customerID` ASC),
                INDEX `repo_adw_keywords_report_conv37` (`firstPageCPC` ASC),
                INDEX `repo_adw_keywords_report_conv38` (`firstPositionCPC` ASC),
                INDEX `repo_adw_keywords_report_conv39` (`hasQualityScore` ASC),
                INDEX `repo_adw_keywords_report_conv40` (`keywordID` ASC),
                INDEX `repo_adw_keywords_report_conv41` (`isNegative` ASC),
                INDEX `repo_adw_keywords_report_conv42` (`matchType` ASC),
                INDEX `repo_adw_keywords_report_conv43` (`month` ASC),
                INDEX `repo_adw_keywords_report_conv44` (`monthOfYear` ASC),
                INDEX `repo_adw_keywords_report_conv45` (`landingPageExperience` ASC),
                INDEX `repo_adw_keywords_report_conv46` (`qualityScore` ASC),
                INDEX `repo_adw_keywords_report_conv47` (`quarter` ASC),
                INDEX `repo_adw_keywords_report_conv48` (`expectedClickthroughRate` ASC),
                INDEX `repo_adw_keywords_report_conv49` (`keywordState` ASC),
                INDEX `repo_adw_keywords_report_conv50` (`criterionServingStatus` ASC),
                INDEX `repo_adw_keywords_report_conv51` (`topOfPageCPC` ASC),
                INDEX `repo_adw_keywords_report_conv52` (`verticalID` ASC),
                INDEX `repo_adw_keywords_report_conv53` (`week` ASC),
                INDEX `repo_adw_keywords_report_conv54` (`year` ASC)
            )"
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
