<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoAdwCampaignReportConvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'repo_adw_campaign_report_conv',
            function (Blueprint $table) {
                $table->increments('id');
                $table->dateTime('exeDate')->comment(
                    'レポートAPI実行日'
                );
                $table->dateTime('startDate')->comment(
                    'APIで指定したレポートの開始日'
                );
                $table->dateTime('endDate')->comment(
                    'APIで指定したレポートの終了日'
                );
                $table->string('account_id', 50)->comment(
                    'ADgainerシステムのアカウントID'
                );
                $table->string('campaign_id', 50)->comment(
                    'ADgainerシステムのキャンペーンID。destinationURLのクエリパラメータを分解して取得'
                );
                $table->string('currency', 50)->nullable()->comment(
                    '顧客アカウントの通貨。'
                );
                $table->text('account')->nullable()->comment(
                    'カスタマーアカウントのわかりやすい名前。'
                );
                $table->string('timeZone', 50)->nullable()->comment(
                    '顧客アカウント用に選択されたタイムゾーンの名前。たとえば、
                    「（GMT-05：00）東部時間」などです。このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。'
                );
                $table->string('network', 50)->nullable()->comment(
                    '第1レベルのネットワークタイプ。'
                );
                $table->string('networkWithSearchPartners', 50)->nullable()->comment(
                    '第2レベルのネットワークタイプ（検索パートナーを含む）。'
                );
                $table->string('advertisingSubChannel', 50)->nullable()->comment(
                    'キャンペーンのAdvertisingChannelTypeのオプションの細分化。'
                );
                $table->string('advertisingChannel', 50)->nullable()->comment(
                    'キャンペーン内の広告の主要な配信ターゲット。'
                );
                $table->double('budget')->nullable()->comment(
                    '1日の予算。キャンペーンの掲載結果レポートには、キャンペーンが共有予算から引き出された場合の共有予算全体が反映されます。'
                );
                $table->bigInteger('baseCampaignID')->nullable()->comment(
                    '試用キャンペーンの基本キャンペーンのID。通常のキャンペーンの場合、これはCampaignIdと同じです。'
                );
                $table->bigInteger('bidStrategyID')->nullable()->comment(
                    'BiddingStrategyConfigurationのIDです。'
                );
                $table->text('bidStrategyName')->nullable()->comment(
                    'BiddingStrategyConfigurationの名前。'
                );
                $table->string('bidStrategyType', 50)->nullable()->comment(
                    'BiddingStrategyConfigurationのタイプ。'
                );
                $table->string('conversionOptimizerBidType', 50)->nullable()->comment(
                    '入札タイプ。'
                );
                $table->bigInteger('budgetID')->nullable()->comment(
                    '予算のID。'
                );
                $table->double('desktopBidAdj')->nullable()->comment(
                    'キャンペーンのレベルでデスクトップの入札単価調整が上書きされます。'
                );
                $table->bigInteger('campaignGroupID')->nullable()->comment(
                    'キャンペーングループのID。'
                );
                $table->bigInteger('campaignID')->nullable()->comment(
                    'キャンペーンのID。'
                );
                $table->double('mobileBidAdj')->nullable()->comment(
                    'キャンペーンのモバイル入札単価調整機能。'
                );
                $table->text('campaign')->nullable()->comment(
                    'キャンペーンの名前。'
                );
                $table->string('campaignState', 50)->nullable()->comment(
                    'キャンペーンのステータス。'
                );
                $table->double('tabletBidAdj')->nullable()->comment(
                    'キャンペーンレベルでタブレットの入札単価調整が上書きされます。'
                );
                $table->string('campaignTrialType', 50)->nullable()->comment(
                    '"キャンペーンのタイプ。これは、キャンペーンが試用キャンペーンかどうかを示します。"'
                );
                $table->string('clickType', 50)->nullable()->comment(
                    '[インプレッション数]フィールドには、そのクリックタイプで広告が配信された頻度が反映されます。
                    広告は複数のクリックタイプで表示できるため、インプレッション数は2倍になり、合計が正確でない可能性があります。'
                );
                $table->string('conversionCategory', 50)->nullable()->comment(
                    'ユーザーがコンバージョンを達成するために実行するアクションを表すカテゴリ。
                    値：「ダウンロード」、「リード」、「購入/販売」、「サインアップ」、「キーページの表示」、「その他」の値。'
                );
                $table->double('convRate')->nullable()->comment(
                    'コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったものです。'
                );
                $table->double('conversions')->nullable()->comment(
                    '最適化を選択したすべてのコンバージョンアクションのコンバージョン数です。'
                );
                $table->bigInteger('conversionTrackerId')->nullable()->comment(
                    'コンバージョントラッカーのID。'
                );
                $table->string('conversionName', 50)->nullable()->comment(
                    'コンバージョンタイプの名前。'
                );
                $table->double('totalConvValue')->nullable()->comment(
                    'すべてのコンバージョンのコンバージョン値の合計。'
                );
                $table->double('costConv')->nullable()->comment(
                    'コンバージョントラッキングクリック数をコンバージョン数で割った値です。'
                );
                $table->double('costConvCurrentModel')->nullable()->comment(
                    '現在選択しているアトリビューションモデルで過去のCostPerConversionデータがどのように表示されるかを示します。'
                );
                $table->double('conversionsCurrentModel')->nullable()->comment(
                    '現在選択しているアトリビューションモデルでコンバージョンデータがどのように表示されるかを示します。'
                );
                $table->double('convValueCurrentModel')->nullable()->comment(
                    '現在選択しているアトリビューションモデルで過去のConversionValueデータがどのように表示されるかを示します。'
                );
                $table->text('clientName')->nullable()->comment(
                    'カスタマーのわかりやすい名前。'
                );
                $table->dateTime('day')->nullable()->comment(
                    '日付はyyyy-MM-ddの形式になります。'
                );
                $table->string('dayOfWeek', 50)->nullable()->comment(
                    '曜日の名前です（例：「月曜日」）。'
                );
                $table->string('device', 50)->nullable()->comment(
                    'インプレッションが表示されたデバイスの種類。'
                );
                $table->dateTime('campaignEndDate')->nullable()->comment(
                    'キャンペーンの終了日。yyyy-MM-ddとしてフォーマットされています。'
                );
                $table->boolean('enhancedCPCEnabled')->nullable()->comment(
                    '入札戦略でエンハンストCPCが有効になっているかどうかを示します。'
                );
                $table->boolean('enhancedCPVEnabled')->nullable()->comment(
                    '入札戦略でエンハンストCPVが有効になっているかどうかを示します。'
                );
                $table->string('conversionSource', 50)->nullable()->comment(
                    'ウェブサイトなどの変換元、通話からのインポート。'
                );
                $table->bigInteger('customerID')->nullable()->comment('顧客ID。');
                $table->integer('hourOfDay')->nullable()->comment(
                    '1日の時間は0と23の間の数値です。'
                );
                $table->boolean('budgetExplicitlyShared')->nullable()->comment(
                    '予算が共有予算（true）かキャンペーン固有（false）かを示します。'
                );
                $table->text('labelIDs')->nullable()->comment(
                    'この行の主オブジェクトのラベルIDのリスト。リスト要素はJSONリスト形式で返されます。'
                );
                $table->text('labels')->nullable()->comment(
                    'この行のメインオブジェクトのラベル名のリスト。リスト要素はJSONリスト形式で返されます。'
                );
                $table->dateTime('month')->nullable()->comment(
                    '月の最初の日。yyyy-MM-ddとしてフォーマットされています。'
                );
                $table->string('monthOfYear', 50)->nullable()->comment(
                    '月の名前です（例：「December」）。'
                );
                $table->string('budgetPeriod', 50)->nullable()->comment(
                    '予算を費やす期間。'
                );
                $table->dateTime('quarter')->nullable()->comment(
                    '四半期の最初の日は、yyyy-MM-ddの形式です。四半期の暦年を使用します。たとえば、2014年の第2四半期は2014-04-01に始まります。'
                );
                $table->string('campaignServingStatus', 50)->nullable()->comment(
                    'キャンペーンが広告を配信しているかどうかを示します。'
                );
                $table->dateTime('campaignStartDate')->nullable()->comment(
                    'キャンペーンの開始日。yyyy-MM-ddとしてフォーマットされています'
                );
                $table->text('trackingTemplate')->nullable()->comment(
                    'この行のメインオブジェクトのトラッキングテンプレート。'
                );
                $table->text('customParameter')->nullable()->comment(
                    'この行のメインオブジェクトのカスタムURLパラメータ。CustomParameters要素はJSONマップ形式で返されます。'
                );
                $table->double('valueConv')->nullable()->comment(
                    'コンバージョン数の合計をコンバージョン数で割った値。'
                );
                $table->double('valueConvCurrentModel')->nullable()->comment(
                    'ValuePerConversionの過去のデータが現在選択しているアトリビューションモデルでどのように表示されるかを示します。'
                );
                $table->bigInteger('viewThroughConv')->nullable()->comment(
                    'ビュースルーコンバージョンの合計数。これは、ディスプレイネットワーク広告が表示された後、
                    後で他の広告とやり取り（クリックなど）せずにサイトのコンバージョンを達成した場合に発生します。'
                );
                $table->dateTime('week')->nullable()->comment(
                    '月曜日の日付。yyyy-MM-ddとしてフォーマットされています。'
                );
                $table->integer('year')->nullable()->comment(
                    '年はyyyyの形式です。'
                );

                $table->unique('id', 'id_UNIQUE');
                $table->index('exeDate', 'repo_adw_campaign_report_conv1');
                $table->index('startDate', 'repo_adw_campaign_report_conv2');
                $table->index('endDate', 'repo_adw_campaign_report_conv3');
                $table->index('account_id', 'repo_adw_campaign_report_conv4');
                $table->index('campaign_id', 'repo_adw_campaign_report_conv5');
                $table->index('currency', 'repo_adw_campaign_report_conv6');
                $table->index('timeZone', 'repo_adw_campaign_report_conv7');
                $table->index('network', 'repo_adw_campaign_report_conv8');
                $table->index('networkWithSearchPartners', 'repo_adw_campaign_report_conv9');
                $table->index('advertisingSubChannel', 'repo_adw_campaign_report_conv10');
                $table->index('advertisingChannel', 'repo_adw_campaign_report_conv11');
                $table->index('budget', 'repo_adw_campaign_report_conv12');
                $table->index('baseCampaignID', 'repo_adw_campaign_report_conv13');
                $table->index('bidStrategyID', 'repo_adw_campaign_report_conv14');
                $table->index('bidStrategyType', 'repo_adw_campaign_report_conv15');
                $table->index('conversionOptimizerBidType', 'repo_adw_campaign_report_conv16');
                $table->index('budgetID', 'repo_adw_campaign_report_conv17');
                $table->index('desktopBidAdj', 'repo_adw_campaign_report_conv18');
                $table->index('campaignGroupID', 'repo_adw_campaign_report_conv19');
                $table->index('campaignID', 'repo_adw_campaign_report_conv20');
                $table->index('mobileBidAdj', 'repo_adw_campaign_report_conv21');
                $table->index('campaignState', 'repo_adw_campaign_report_conv22');
                $table->index('tabletBidAdj', 'repo_adw_campaign_report_conv23');
                $table->index('campaignTrialType', 'repo_adw_campaign_report_conv24');
                $table->index('clickType', 'repo_adw_campaign_report_conv25');
                $table->index('conversionCategory', 'repo_adw_campaign_report_conv26');
                $table->index('conversionTrackerId', 'repo_adw_campaign_report_conv27');
                $table->index('conversionName', 'repo_adw_campaign_report_conv28');
                $table->index('day', 'repo_adw_campaign_report_conv29');
                $table->index('dayOfWeek', 'repo_adw_campaign_report_conv30');
                $table->index('device', 'repo_adw_campaign_report_conv31');
                $table->index('campaignEndDate', 'repo_adw_campaign_report_conv32');
                $table->index('enhancedCPCEnabled', 'repo_adw_campaign_report_conv33');
                $table->index('enhancedCPVEnabled', 'repo_adw_campaign_report_conv34');
                $table->index('conversionSource', 'repo_adw_campaign_report_conv35');
                $table->index('customerID', 'repo_adw_campaign_report_conv36');
                $table->index('hourOfDay', 'repo_adw_campaign_report_conv37');
                $table->index('budgetExplicitlyShared', 'repo_adw_campaign_report_conv38');
                $table->index('month', 'repo_adw_campaign_report_conv39');
                $table->index('monthOfYear', 'repo_adw_campaign_report_conv40');
                $table->index('budgetPeriod', 'repo_adw_campaign_report_conv41');
                $table->index('quarter', 'repo_adw_campaign_report_conv42');
                $table->index('campaignServingStatus', 'repo_adw_campaign_report_conv43');
                $table->index('campaignStartDate', 'repo_adw_campaign_report_conv44');
                $table->index('week', 'repo_adw_campaign_report_conv45');
                $table->index('year', 'repo_adw_campaign_report_conv46');
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
        Schema::dropIfExists('repo_adw_campaign_report_conv');
    }
}
