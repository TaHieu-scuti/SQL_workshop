<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepoAdwAdgroupReportConvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'repo_adw_adgroup_report_conv',
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
                $table->double('desktopBidAdj')->nullable()->comment(
                    '広告グループレベルでデスクトップの入札単価調整が上書きされます。'
                );
                $table->bigInteger('adGroupID')->nullable()->comment(
                    '広告グループのID。'
                );
                $table->double('mobileBidAdj')->nullable()->comment(
                    '広告グループレベルでモバイルの入札単価調整が上書きされます。'
                );
                $table->text('adGroup')->nullable()->comment(
                    '広告グループの名前。'
                );
                $table->string('adGroupState', 50)->nullable()->comment(
                    '広告グループのステータス。'
                );
                $table->double('tabletBidAdj')->nullable()->comment(
                    'タブグループの入札単価調整が広告グループレベルでオーバーライドされます。'
                );
                $table->string('adGroupType', 50)->nullable()->comment(
                    '広告グループのタイプ。'
                );
                $table->string('network', 50)->nullable()->comment(
                    '第1レベルのネットワークタイプ。'
                );
                $table->string('networkWithSearchPartners', 50)->nullable()->comment(
                    '第2レベルのネットワークタイプ（検索パートナーを含む）。'
                );
                $table->bigInteger('baseAdGroupID')->nullable()->comment(
                    '試用広告グループの基本広告グループのID。通常の広告グループの場合、これはAdGroupIdと同じです。'
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
                $table->string('biddingStrategySource', 50)->nullable()->comment(
                    '入札戦略が関連付けられている場所（キャンペーン、広告グループ、広告グループの条件など）を示します。'
                );
                $table->string('bidStrategyType', 50)->nullable()->comment(
                    'BiddingStrategyConfigurationのタイプ。'
                );
                $table->string('conversionOptimizerBidType', 50)->nullable()->comment(
                    '入札タイプ。'
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
                $table->string('clickType', 50)->nullable()->comment(
                    '[インプレッション数]フィールドには、そのクリックタイプで広告が配信された頻度が反映されます。
                    広告は複数のクリックタイプで表示できるため、インプレッション数は2倍になり、合計が正確でない可能性があります。'
                );
                $table->string('contentNetworkBidDimension', 50)->nullable()->comment(
                    '広告グループでディスプレイネットワークの絶対的な入札単価に使用する条件のタイプ。'
                );
                $table->string('conversionCategory', 50)->nullable()->comment(
                    'ユーザーがコンバージョンを達成するために実行するアクションを表すカテゴリ。ゼロ変換の行が返されないようにします。
                    値：「ダウンロード」、「リード」、「購入/販売」、「サインアップ」、「キーページの表示」、「その他」の値。'
                );
                $table->double('convRate')->nullable()->comment(
                    'コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったものです。'
                );
                $table->double('conversions')->nullable()->comment(
                    '最適化を選択したすべてのコンバージョンアクションのコンバージョン数。'
                );
                $table->bigInteger('conversionTrackerId')->nullable()->comment(
                    'コンバージョントラッカーのID。'
                );
                $table->string('conversionName', 50)->nullable()->comment(
                    'コンバージョンタイプの名前。ゼロ変換の行が返されないようにします。'
                );
                $table->double('totalConvValue')->nullable()->comment(
                    'すべてのコンバージョンのコンバージョン値の合計。'
                );
                $table->double('costConv')->nullable()->comment(
                    'コンバージョントラッキングクリック数に起因する費用をコンバージョン数で割った値'
                );
                $table->double('costConvCurrentModel')->nullable()->comment(
                    '現在選択しているアトリビューションモデルで、過去の「CostPerConversion」データがどのように表示されるかを示します。'
                );
                $table->double('defaultMaxCPC')->nullable()->comment(
                    'クリック単価制。値は、a）小額の金額、b）AdWordsが自動的に選択された入札戦略で入札単価を設定する場合は
                    「自動：x」または「自動」、c）クリック単価が適用されない場合は「 - 」のいずれかです行に'
                );
                $table->double('maxCPM')->nullable()->comment(
                    'CPM（1,000インプレッションあたりの単価）の単価'
                );
                $table->double('maxCPV')->nullable()->comment(
                    '視聴単価制の入札単価値は、a）小額の金額、b）AdWordsが自動的に選択した入札戦略で入札単価を設定している場合は
                    「自動：x」または「自動」、またはc）入札単価が適用されない場合は「 - 」のいずれかです行に'
                );
                $table->double('conversionsCurrentModel')->nullable()->comment(
                    '現在選択しているアトリビューションモデルでの過去の「コンバージョン」データの表示方法を示します。'
                );
                $table->double('convValueCurrentModel')->nullable()->comment(
                    '現在選択しているアトリビューションモデルで、過去の「ConversionValue」データがどのように表示されるかを示します。'
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
                $table->double('targetROAS')->nullable()->comment(
                    '効果的なターゲット広告費用対効果、オーバーライドを考慮します。'
                );
                $table->string('targetROASSource', 50)->nullable()->comment(
                    'オーバーライドを考慮して、効果的な目標のROASのソース。'
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
                $table->bigInteger('customerID')->nullable()->comment(
                    '顧客ID。'
                );
                $table->integer('hourOfDay')->nullable()->comment(
                    '1日の時間は0と23の間の数値です。'
                );
                $table->text('labelIDs')->nullable()->comment(
                    'この行のメインオブジェクトのラベルIDのリスト。リスト要素はJSONリスト形式で返されます。'
                );
                $table->text('labels')->nullable()->comment(
                    'この行のメインオブジェクトのラベル名のリスト。リスト要素はJSONリスト形式で返されます。'
                );
                $table->dateTime('month')->nullable()->comment(
                    '月の最初の日。yyyy-MM-ddの形式です。'
                );
                $table->string('monthOfYear', 50)->nullable()->comment(
                    '月の名前です（例：「12月」）。'
                );
                $table->dateTime('quarter')->nullable()->comment(
                    '四半期の最初の日は、yyyy-MM-ddの形式です。四半期の暦年を使用します。たとえば、2014年第2四半期は2014-04-01に開始します。'
                );
                $table->double('targetCPA')->nullable()->comment(
                    'ターゲットコンバージョン単価の入札戦略で設定された平均コンバージョン単価ターゲット。'
                );
                $table->string('targetCPASource', 50)->nullable()->comment(
                    '目標コンバージョン単価が設定されたレベル。 これは広告グループレベルでのみ適用されます。'
                );
                $table->text('trackingTemplate')->nullable()->comment(
                    'この行のメインオブジェクトのトラッキングテンプレート。'
                );
                $table->text('customParameter')->nullable()->comment(
                    'この行のメインオブジェクトのカスタムURLパラメータ。 CustomParameters要素はJSONマップ形式で返されます。'
                );
                $table->double('valueConv')->nullable()->comment(
                    'コンバージョン数の合計をコンバージョン数で割った値。
                    このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。'
                );
                $table->double('valueConvCurrentModel')->nullable()->comment(
                    '現在選択しているアトリビューションモデルで、過去の「ValuePerConversion」データがどのように表示されるかを示します。'
                );
                $table->dateTime('week')->nullable()->comment(
                    'yyyy-MM-ddの形式の月曜日の日付。'
                );
                $table->integer('year')->nullable()->comment(
                    '年はyyyyの形式です。'
                );

                $table->unique('id', 'id_UNIQUE');
                $table->index('exeDate', 'repo_adw_adgroup_report_conv1');
                $table->index('startDate', 'repo_adw_adgroup_report_conv2');
                $table->index('endDate', 'repo_adw_adgroup_report_conv3');
                $table->index('account_id', 'repo_adw_adgroup_report_conv4');
                $table->index('campaign_id', 'repo_adw_adgroup_report_conv5');
                $table->index('currency', 'repo_adw_adgroup_report_conv6');
                $table->index('timeZone', 'repo_adw_adgroup_report_conv7');
                $table->index('desktopBidAdj', 'repo_adw_adgroup_report_conv8');
                $table->index('adGroupID', 'repo_adw_adgroup_report_conv9');
                $table->index('mobileBidAdj', 'repo_adw_adgroup_report_conv10');
                $table->index('adGroupState', 'repo_adw_adgroup_report_conv11');
                $table->index('tabletBidAdj', 'repo_adw_adgroup_report_conv12');
                $table->index('adGroupType', 'repo_adw_adgroup_report_conv13');
                $table->index('network', 'repo_adw_adgroup_report_conv14');
                $table->index('networkWithSearchPartners', 'repo_adw_adgroup_report_conv15');
                $table->index('baseAdGroupID', 'repo_adw_adgroup_report_conv16');
                $table->index('baseCampaignID', 'repo_adw_adgroup_report_conv17');
                $table->index('bidStrategyID', 'repo_adw_adgroup_report_conv18');
                $table->index('biddingStrategySource', 'repo_adw_adgroup_report_conv19');
                $table->index('bidStrategyType', 'repo_adw_adgroup_report_conv20');
                $table->index('conversionOptimizerBidType', 'repo_adw_adgroup_report_conv21');
                $table->index('campaignID', 'repo_adw_adgroup_report_conv22');
                $table->index('campaignState', 'repo_adw_adgroup_report_conv23');
                $table->index('clickType', 'repo_adw_adgroup_report_conv24');
                $table->index('contentNetworkBidDimension', 'repo_adw_adgroup_report_conv25');
                $table->index('conversionCategory', 'repo_adw_adgroup_report_conv26');
                $table->index('conversionTrackerId', 'repo_adw_adgroup_report_conv27');
                $table->index('conversionName', 'repo_adw_adgroup_report_conv28');
                $table->index('defaultMaxCPC', 'repo_adw_adgroup_report_conv29');
                $table->index('maxCPM', 'repo_adw_adgroup_report_conv30');
                $table->index('maxCPV', 'repo_adw_adgroup_report_conv31');
                $table->index('day', 'repo_adw_adgroup_report_conv32');
                $table->index('dayOfWeek', 'repo_adw_adgroup_report_conv33');
                $table->index('device', 'repo_adw_adgroup_report_conv34');
                $table->index('targetROAS', 'repo_adw_adgroup_report_conv35');
                $table->index('targetROASSource', 'repo_adw_adgroup_report_conv36');
                $table->index('enhancedCPCEnabled', 'repo_adw_adgroup_report_conv37');
                $table->index('enhancedCPVEnabled', 'repo_adw_adgroup_report_conv38');
                $table->index('conversionSource', 'repo_adw_adgroup_report_conv39');
                $table->index('customerID', 'repo_adw_adgroup_report_conv40');
                $table->index('hourOfDay', 'repo_adw_adgroup_report_conv41');
                $table->index('month', 'repo_adw_adgroup_report_conv42');
                $table->index('monthOfYear', 'repo_adw_adgroup_report_conv43');
                $table->index('quarter', 'repo_adw_adgroup_report_conv44');
                $table->index('targetCPA', 'repo_adw_adgroup_report_conv45');
                $table->index('targetCPASource', 'repo_adw_adgroup_report_conv46');
                $table->index('week', 'repo_adw_adgroup_report_conv47');
                $table->index('year', 'repo_adw_adgroup_report_conv48');
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
        Schema::dropIfExists('repo_adw_adgroup_report_conv');
    }
}
