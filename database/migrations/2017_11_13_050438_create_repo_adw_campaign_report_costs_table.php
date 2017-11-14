<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepoAdwCampaignReportCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo_adw_campaign_report_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('exeDate')
                    ->comment('レポートAPI実行日')
                    ->index('repo_adw_campaign_report_cost1');
            $table->date('startDate')
                    ->comment('APIで指定したレポートの開始日')
                    ->index('repo_adw_campaign_report_cost2');
            $table->date('endDate')
                    ->comment('APIで指定したレポートの終了日')
                    ->index('repo_adw_campaign_report_cost3');
            $table->string('account_id', 50)
                    ->nullable()
                    ->comment('ADgainerシステムのアカウントID')
                    ->index('repo_adw_campaign_report_cost4');
            $table->string('campaign_id', 50)
                    ->nullable()
                    ->comment('ADgainerシステムのキャンペーンID。destinationURLのクエリパラメータを分解して取得')
                    ->index('repo_adw_campaign_report_cost5');
            $table->string('currency', 50)
                    ->nullable()
                    ->comment('顧客アカウントの通貨。')
                    ->index('repo_adw_campaign_report_cost18');
            $table->text('account')
                    ->nullable()
                    ->comment('カスタマーアカウントのわかりやすい名前。');
            $table->string('timeZone', 50)
                    ->comment('顧客アカウント用に選択されたタイムゾーンの名前。たとえば、
                              「（GMT-05：00）東部時間」などです。このフィールドには、
                              タイムゾーンの夏時間の現在の状態は反映されません。')
                    ->index('repo_adw_campaign_report_cost19');
            $table->double('activeViewAvgCPM')
                    ->nullable()
                    ->comment('視認可能インプレッションの平均費用（ActiveViewImpressions）。');
            $table->double('activeViewViewableCTR')
                    ->nullable()
                    ->comment('広告が表示された後にユーザーが広告をクリックした頻度');
            $table->bigInteger('activeViewViewableImpressions')
                    ->nullable()
                    ->comment('ディスプレイネットワークサイトで広告が表示される頻度');
            $table->double('activeViewMeasurableImprImpr')
                    ->nullable()
                    ->comment('アクティブビューで計測されたインプレッション数と配信インプレッション数の比。');
            $table->double('activeViewMeasurableCost')
                    ->nullable()
                    ->comment('Active Viewで測定可能なインプレッションの費用。');
            $table->bigInteger('activeViewMeasurableImpr')
                    ->nullable()
                    ->comment('広告が表示されているプレースメントに広告が表示された回数。');
            $table->double('activeViewViewableImprMeasurableImpr')
                    ->nullable()
                    ->comment('広告がアクティブビュー対応サイトに表示された時間（測定可能なインプレッション数）
                                と表示可能（表示可能なインプレッション数）の割合。');
            $table->string('network', 50)
                    ->nullable()
                    ->comment('第1レベルのネットワークタイプ。')
                    ->index('repo_adw_campaign_report_cost6');
            $table->string('networkWithSearchPartners', 50)
                    ->nullable()
                    ->comment('第2レベルのネットワークタイプ（検索パートナーを含む）。')
                    ->index('repo_adw_campaign_report_cost7');
            $table->string('advertisingSubChannel', 50)
                    ->nullable()
                    ->comment('キャンペーンのAdvertisingChannelTypeのオプションの細分化。')
                    ->index('repo_adw_campaign_report_cost20');
            $table->string('advertisingChannel', 50)
                    ->nullable()
                    ->comment('キャンペーン内の広告の主要な配信ターゲット。')
                    ->index('repo_adw_campaign_report_cost21');
            $table->double('budget')
                    ->nullable()
                    ->comment('1日の予算。キャンペーンの掲載結果レポートには、
                                キャンペーンが共有予算から引き出された場合の共有予算全体が反映されます。')
                    ->index('repo_adw_campaign_report_cost22');
            $table->double('avgCost')
                    ->nullable()
                    ->comment('インタラクションごとに支払う平均金額。この金額は、
                                広告の合計費用を合計インタラクション数で割ったものです。');
            $table->double('avgCPC')
                    ->nullable()
                    ->comment('すべてのクリックの総コストを、受け取った総クリック数で割った値。');
            $table->double('avgCPM')
                    ->nullable()
                    ->comment('平均インプレッション単価（CPM）。');
            $table->double('avgPosition')
                    ->nullable()
                    ->comment('他の広告主様との相対的な広告の掲載順位。');
            $table->bigInteger('baseCampaignID')
                    ->nullable()
                    ->comment('試用キャンペーンの基本キャンペーンのID。
                                通常のキャンペーンの場合、これはCampaignIdと同じです。')
                    ->index('repo_adw_campaign_report_cost23');
            $table->bigInteger('bidStrategyID')
                    ->nullable()
                    ->comment('BiddingStrategyConfigurationのIDです。')
                    ->index('repo_adw_campaign_report_cost24');
            $table->text('bidStrategyName')
                    ->nullable()
                    ->comment('BiddingStrategyConfigurationの名前。');
            $table->string('bidStrategyType', 50)
                    ->nullable()
                    ->comment('BiddingStrategyConfigurationのタイプ。')
                    ->index('repo_adw_campaign_report_cost26');
            $table->string('conversionOptimizerBidType', 50)
                    ->nullable()
                    ->comment('入札タイプ。')
                    ->index('repo_adw_campaign_report_cost27');
            $table->bigInteger('budgetID')
                    ->nullable()
                    ->comment('予算のID。')
                    ->index('repo_adw_campaign_report_cost28');
            $table->double('desktopBidAdj')
                    ->nullable()
                    ->comment('キャンペーンのレベルでデスクトップの入札単価調整が上書きされます。')
                    ->index('repo_adw_campaign_report_cost29');
            $table->bigInteger('campaignGroupID')
                    ->nullable()
                    ->comment('キャンペーングループのID。。')
                    ->index('repo_adw_campaign_report_cost30');
            $table->bigInteger('campaignID')
                    ->nullable()
                    ->comment('キャンペーンのID。')
                    ->index('repo_adw_campaign_report_cost31');
            $table->double('mobileBidAdj')
                    ->nullable()
                    ->comment('キャンペーンのモバイル入札単価調整機能。このフィールドでフィルタリングするには、
                                0より大きく1以下の値を使用します。x.xx％として返されるパーセンテージ。。')
                    ->index('repo_adw_campaign_report_cost32');
            $table->text('campaign')
                    ->nullable()
                    ->comment('キャンペーンの名前。');
            $table->string('campaignState', 50)
                    ->nullable()
                    ->comment('キャンペーンのステータス。')
                    ->index('repo_adw_campaign_report_cost33');
            $table->double('tabletBidAdj')
                    ->nullable()
                    ->comment('キャンペーンレベルでタブレットの入札単価調整が上書きされます。')
                    ->index('repo_adw_campaign_report_cost34');
            $table->string('campaignTrialType', 50)
                    ->nullable()
                    ->comment('キャンペーンのタイプ。これは、キャンペーンが試用キャンペーンかどうかを示します。')
                    ->index('repo_adw_campaign_report_cost35');
            $table->bigInteger('clicks')
                    ->nullable()
                    ->comment('クリック数。');
            $table->string('clickType', 50)
                    ->nullable()
                    ->comment('[インプレッション数]フィールドには、そのクリックタイプで広告が配信された頻度が反映されます。
                                広告は複数のクリックタイプで表示できるため、インプレッション数は2倍になり、合計が正確でない可能性があります。')
                    ->index('repo_adw_campaign_report_cost8');
            $table->double('convRate')
                    ->nullable()
                    ->comment('コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったものです。x.xx％として返されるパーセンテージ。');
            $table->double('conversions')
                    ->nullable()
                    ->comment('最適化を選択したすべてのコンバージョンアクションのコンバージョン数です。');
            $table->double('totalConvValue')
                    ->nullable()
                    ->comment('すべてのコンバージョンのコンバージョン値の合計。');
            $table->double('cost')
                    ->nullable()
                    ->comment('この期間のクリック単価（CPC）とインプレッション単価（CPM）の合計。');
            $table->double('costConv')
                    ->nullable()
                    ->comment('コンバージョントラッキングクリック数をコンバージョン数で割った値です。');
            $table->double('costConvCurrentModel')
                    ->nullable()
                    ->comment('現在選択しているアトリビューションモデルで過去のCostPerConversionデータがどのように表示されるかを示します。');
            $table->double('ctr')
                    ->nullable()
                    ->comment('広告がクリックされた回数（クリック数）を広告が表示された回数
                                （インプレッション数）で割ったものです。x.xx％として返されるパーセンテージ。');
            $table->double('conversionsCurrentModel')
                    ->nullable()
                    ->comment('現在選択しているアトリビューションモデルでコンバージョンデータがどのように表示されるかを示します。');
            $table->double('convValueCurrentModel')
                    ->nullable()
                    ->comment('現在選択しているアトリビューションモデルで過去のConversionValueデータがどのように表示されるかを示します。');
            $table->text('clientName')
                    ->nullable()
                    ->comment('カスタマーのわかりやすい名前。');
            $table->date('day')
                    ->nullable()
                    ->comment('日付はyyyy-MM-ddの形式になります。')
                    ->index('repo_adw_campaign_report_cost9');
            $table->string('dayOfWeek', 50)
                    ->nullable()
                    ->comment('曜日の名前です（例：「月曜日」）。')
                    ->index('repo_adw_campaign_report_cost10');
            $table->string('device', 50)
                    ->nullable()
                    ->comment('インプレッションが表示されたデバイスの種類。')
                    ->index('repo_adw_campaign_report_cost11');
            $table->date('campaignEndDate')
                    ->nullable()
                    ->comment('キャンペーンの終了日。yyyy-MM-ddとしてフォーマットされています。')
                    ->index('repo_adw_campaign_report_cost36');
            $table->boolean('enhancedCPCEnabled')
                    ->nullable()
                    ->comment('入札戦略でエンハンストCPCが有効になっているかどうかを示します。')
                    ->index('repo_adw_campaign_report_cost37');
            $table->boolean('enhancedCPVEnabled')
                    ->nullable()
                    ->comment('入札戦略でエンハンストCPVが有効になっているかどうかを示します。')
                    ->index('repo_adw_campaign_report_cost38');
            $table->bigInteger('customerID')
                    ->nullable()
                    ->comment('顧客ID。')
                    ->index('repo_adw_campaign_report_cost39');
            $table->bigInteger('gmailForwards')
                    ->nullable()
                    ->comment('広告が誰かにメッセージとして転送された回数。');
            $table->bigInteger('gmailSaves')
                    ->nullable()
                    ->comment('Gmail広告をメッセージとして受信トレイに保存した回数。');
            $table->bigInteger('gmailClicksToWebsite')
                    ->nullable()
                    ->comment('Gmail広告の展開状態でのリンク先ページへのクリック数。');
            $table->bigInteger('hourOfDay')
                    ->nullable()
                    ->comment('1日の時間は0と23の間の数値です。')
                    ->index('repo_adw_campaign_report_cost12');
            $table->bigInteger('impressions')
                    ->nullable()
                    ->comment('Googleネットワークの検索結果ページやウェブサイトに広告が表示された回数をカウントします。');
            $table->double('interactionRate')
                    ->nullable()
                    ->comment('広告が表示された後にユーザーがどのくらい頻繁に広告を操作するか。これはインタラクションの数を広告の表示回数で割ったものです。');
            $table->bigInteger('interactions')
                    ->nullable()
                    ->comment('相互作用の数インタラクションとは、テキストやショッピング広告のクリック、
                                動画広告の表示など、広告フォーマットに関連する主要なユーザーアクションです。');
            $table->text('interactionTypes')
                    ->nullable()
                    ->comment('Interactions、InteractionRate、およびAverageCost列に反映される相互作用のタイプ。');
            $table->boolean('budgetExplicitlyShared')
                    ->nullable()
                    ->comment('予算が共有予算（true）かキャンペーン固有（false）かを示します。')
                    ->index('repo_adw_campaign_report_cost40');
            $table->text('labelIDs')
                    ->nullable()
                    ->comment('この行の主オブジェクトのラベルIDのリスト。リスト要素はJSONリスト形式で返されます。');
            $table->text('labels')
                    ->nullable()
                    ->comment('この行のメインオブジェクトのラベル名のリスト。リスト要素はJSONリスト形式で返されます。');
            $table->string('month', 50)
                    ->nullable()
                    ->comment('月の最初の日。yyyy-MM-ddとしてフォーマットされています。')
                    ->index('repo_adw_campaign_report_cost13');
            $table->string('monthOfYear', 50)
                    ->nullable()
                    ->comment('月の名前です（例：「December」）。')
                    ->index('repo_adw_campaign_report_cost14');
            $table->text('budgetPeriod')
                    ->nullable()
                    ->comment('予算を費やす期間。');
            $table->date('quarter')
                    ->nullable()
                    ->comment('四半期の最初の日は、yyyy-MM-ddの形式です。四半期の暦年を使用します。
                                たとえば、2014年の第2四半期は2014-04-01に始まります。')
                    ->index('repo_adw_campaign_report_cost15');
            $table->string('campaignServingStatus', 50)
                    ->nullable()
                    ->comment('キャンペーンが広告を配信しているかどうかを示します。')
                    ->index('repo_adw_campaign_report_cost44');
            $table->date('campaignStartDate')
                    ->nullable()
                    ->comment('キャンペーンの開始日。yyyy-MM-ddとしてフォーマットされています')
                    ->index('repo_adw_campaign_report_cost45');
            $table->text('trackingTemplate')
                    ->nullable()
                    ->comment('この行のメインオブジェクトのトラッキングテンプレート。');
            $table->text('customParameter')
                    ->nullable()
                    ->comment('この行のメインオブジェクトのカスタムURLパラメータ。CustomParameters要素はJSONマップ形式で返されます。');
            $table->double('valueConv')
                    ->nullable()
                    ->comment('コンバージョン数の合計をコンバージョン数で割った値。');
            $table->double('valueConvCurrentModel')
                    ->nullable()
                    ->comment('ValuePerConversionの過去のデータが現在選択しているアトリビューションモデルでどのように表示されるかを示します。');
            $table->date('week')
                    ->nullable()
                    ->comment('月曜日の日付。yyyy-MM-ddとしてフォーマットされています。')
                    ->index('repo_adw_campaign_report_cost16');
            $table->bigInteger('year')
                    ->nullable()
                    ->comment('年はyyyyの形式です。')
                    ->index('repo_adw_campaign_report_cost17');
            $table->bigInteger('accountid')
                    ->comment('media Id');
            $table->
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repo_adw_campaign_report_costs');
    }
}
