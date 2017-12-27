<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoAdwSearchQueryPerformanceReportConv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "CREATE TABLE IF NOT EXISTS `ADGAINER_db_SECURE`.`repo_adw_search_query_performance_report_conv` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `exeDate` DATE NOT NULL COMMENT 'レポートAPI実行日',
                `startDate` DATE NOT NULL COMMENT 'APIで指定したレポートの開始日',
                `endDate` DATE NOT NULL COMMENT 'APIで指定したレポートの終了日',
                `account_id` VARCHAR(50) NOT NULL COMMENT 'ADgainerシステムのアカウントID',
                `campaign_id` VARCHAR(50) NOT NULL COMMENT 'ADgainerシステムのキャンペーンID。destinationURLのクエリパラメータを分解して取得',
                `currency` VARCHAR(50) NULL COMMENT '顧客口座の通貨。',
                `account` TEXT NULL COMMENT 'カスタマーアカウントのわかりやすい名前。',
                `timeZone` VARCHAR(50) NULL COMMENT '顧客アカウント用に選択されたタイムゾーンの名前。 たとえば、「（GMT-05：00）東部時間」などです。 このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。',
                `adType` VARCHAR(50) NULL COMMENT '広告の基礎となるメディア形式。 値は、テンプレート広告ページの書式、 またはMediaType 列挙型です。',
                `adGroupID` INT(20) NULL COMMENT '広告グループのID。',
                `adGroup` TEXT NULL COMMENT '広告グループの名前。',
                `adGroupState` VARCHAR(50) NULL COMMENT '広告グループのステータス。',
                `network` VARCHAR(50) NULL COMMENT '第1レベルのネットワークタイプ。',
                `networkWithSearchPartners` VARCHAR(50) NULL COMMENT '第2レベルのネットワークタイプ（検索パートナーを含む）。',
                `allConvRate` Double NULL COMMENT 'AllConversionsをコンバージョントラッキング可能な合計クリック数で割った値です。これは広告のクリックがコンバージョンにつながった頻度です。',
                `allConv` Double NULL COMMENT 'AdWordsが推進するコンバージョン数の最善の見積もり。ウェブサイト、クロスデバイス、電話通話のコンバージョンが含まれます。',
                `allConvValue` Double NULL COMMENT '推定されたものを含め、すべてのコンバージョンの合計値。',
                `campaignID` INT(20) NULL COMMENT '「他の広告主様との広告の掲載順位。',
                `campaign` TEXT NULL COMMENT 'キャンペーンの名前。',
                `campaignState` VARCHAR(50) NULL COMMENT 'キャンペーンのステータス。',
                `conversionCategory` VARCHAR(255) NULL COMMENT 'ユーザーがコンバージョンを達成するために実行するアクションを表すカテゴリ。 ゼロ変換の行が返されないようにします。 値：「ダウンロード」、「リード」、「購入/販売」、「サインアップ」、「キーページの表示」、「その他」の値。',
                `convRate` Double NULL COMMENT 'コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったもの。',
                `conversions` Double NULL COMMENT '最適化を選択したすべてのコンバージョンアクションのコンバージョン数。',
                `conversionTrackerId` INT(20) NULL COMMENT 'コンバージョントラッカーのID。',
                `conversionName` VARCHAR(255) NULL COMMENT 'コンバージョンタイプの名前。 ゼロ変換の行が返されないようにします。',
                `totalConvValue` Double NULL COMMENT 'すべてのコンバージョンのコンバージョン値の合計です。',
                `costAllConv` Double NULL COMMENT '総費用をすべてのコンバージョンで割った値。',
                `costConv` Double NULL COMMENT 'コンバージョントラッキングクリック数に起因する費用をコンバージョン数で割った値',
                `adID` INT(20) NULL COMMENT '広告のID。',
                `crossDeviceConv` Double NULL COMMENT 'ユーザーが1つのデバイスでAdWords広告をクリックして別のデバイスやブラウザでコンバージョンを達成したときのコンバージョンデバイス間のコンバージョンは既にAllConversions列に含まれています。',
                `clientName` TEXT NULL COMMENT 'カスタマーのわかりやすい名前。',
                `day` Date NULL COMMENT '日付はyyyy-MM-ddの形式になります。',
                `dayOfWeek` VARCHAR(50) NULL COMMENT '曜日の名前です（例：「月曜日」）。',
                `destinationURL` TEXT NULL COMMENT 'インプレッションのリンク先URL。',
                `device` VARCHAR(50) NULL COMMENT 'インプレッションが表示されたデバイスの種類。',
                `conversionSource` VARCHAR(50) NULL COMMENT '約束の数。 視聴者がライトボックス広告を展開するとエンゲージメントが発生します。 また、今後、他の広告タイプがエンゲージメント指標をサポートする場合もあります。',
                `customerID` INT(20) NULL COMMENT '顧客ID。',
                `finalURL` TEXT NULL COMMENT 'インプレッションの最終URL。',
                `keywordID` INT(20) NULL COMMENT '広告を表示したキーワードのID。',
                `keyword` TEXT NULL COMMENT 'クエリと一致し、広告を表示したキーワード。',
                `month` Date NULL COMMENT '月の最初の日。yyyy-MM-ddの形式です。',
                `monthOfYear` VARCHAR(50) NULL COMMENT '月の名前です（例：「12月」）。',
                `quarter` Date NULL COMMENT '四半期の最初の日は、yyyy-MM-ddの形式です。 四半期の暦年を使用します。たとえば、2014年第2四半期は2014-04-01に開始します。',
                `searchTerm` TEXT NULL COMMENT 'この属性の文字列が128文字バイトより長い場合、返される結果は単一の集約行にはなりません。',
                `matchType` VARCHAR(50) NULL COMMENT 'バリアントを含む、広告をトリガーしたキーワードのマッチタイプ。 類似パターンの詳細については、https://support.google.com/adwords/answer/2472708をご覧ください。',
                `addedExcluded` VARCHAR(50) NULL COMMENT '検索語が現在ターゲットまたは除外キーワードのいずれであるかを示します。',
                `trackingTemplate` TEXT NULL COMMENT 'この行のメインオブジェクトのトラッキングテンプレート。',
                `valueAllConv` Double NULL COMMENT 'すべてのコンバージョンの平均値です。',
                `valueConv` Double NULL COMMENT 'コンバージョンの合計値を総コンバージョン数で割ったものです。',
                `viewThroughConv` INT(20) NULL COMMENT 'ビュースルーコンバージョンの合計数。 これは、ディスプレイネットワーク広告が表示された後、後で他の広告とやり取り（クリックなど）せずにサイトのコンバージョンを達成した場合に発生します。',
                `week` Date NULL COMMENT 'yyyy-MM-ddの形式の月曜日の日付。',
                `year` INT NULL COMMENT '年はyyyyの形式です。',
                PRIMARY KEY (`id`),
                UNIQUE INDEX `id_UNIQUE` (`id` ASC),
                INDEX `repo_adw_search_query_performance_report_conv1` (`exeDate` ASC),
                INDEX `repo_adw_search_query_performance_report_conv2` (`startDate` ASC),
                INDEX `repo_adw_search_query_performance_report_conv3` (`endDate` ASC),
                INDEX `repo_adw_search_query_performance_report_conv4` (`account_id` ASC),
                INDEX `repo_adw_search_query_performance_report_conv5` (`campaign_id` ASC),
                INDEX `repo_adw_search_query_performance_report_conv6` (`currency` ASC),
                INDEX `repo_adw_search_query_performance_report_conv7` (`timeZone` ASC),
                INDEX `repo_adw_search_query_performance_report_conv8` (`adType` ASC),
                INDEX `repo_adw_search_query_performance_report_conv9` (`adGroupID` ASC),
                INDEX `repo_adw_search_query_performance_report_conv10` (`adGroupState` ASC),
                INDEX `repo_adw_search_query_performance_report_conv11` (`network` ASC),
                INDEX `repo_adw_search_query_performance_report_conv12` (`networkWithSearchPartners` ASC),
                INDEX `repo_adw_search_query_performance_report_conv13` (`campaignID` ASC),
                INDEX `repo_adw_search_query_performance_report_conv14` (`campaignState` ASC),
                INDEX `repo_adw_search_query_performance_report_conv15` (`conversionCategory` ASC),
                INDEX `repo_adw_search_query_performance_report_conv16` (`conversionTrackerId` ASC),
                INDEX `repo_adw_search_query_performance_report_conv17` (`conversionName` ASC),
                INDEX `repo_adw_search_query_performance_report_conv18` (`adID` ASC),
                INDEX `repo_adw_search_query_performance_report_conv19` (`day` ASC),
                INDEX `repo_adw_search_query_performance_report_conv20` (`dayOfWeek` ASC),
                INDEX `repo_adw_search_query_performance_report_conv21` (`device` ASC),
                INDEX `repo_adw_search_query_performance_report_conv22` (`conversionSource` ASC),
                INDEX `repo_adw_search_query_performance_report_conv23` (`customerID` ASC),
                INDEX `repo_adw_search_query_performance_report_conv24` (`keywordID` ASC),
                INDEX `repo_adw_search_query_performance_report_conv25` (`month` ASC),
                INDEX `repo_adw_search_query_performance_report_conv26` (`monthOfYear` ASC),
                INDEX `repo_adw_search_query_performance_report_conv27` (`quarter` ASC),
                INDEX `repo_adw_search_query_performance_report_conv28` (`matchType` ASC),
                INDEX `repo_adw_search_query_performance_report_conv29` (`addedExcluded` ASC),
                INDEX `repo_adw_search_query_performance_report_conv30` (`week` ASC),
                INDEX `repo_adw_search_query_performance_report_conv31` (`year` ASC)
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
        Schema::dropIfExists('repo_adw_search_query_performance_report_conv');
    }
}
