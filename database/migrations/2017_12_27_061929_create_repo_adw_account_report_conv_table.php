<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoAdwAccountReportConvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'repo_adw_account_report_conv',
            function (Blueprint $table) {
                $table->increments('id');
                $table->date('exeDate')->comment('レポートAPI実行日');
                $table->date('startDate')->comment('APIで指定したレポートの開始日');
                $table->date('endDate')->comment('APIで指定したレポートの終了日');
                $table->string('account_id', 50)->comment('ADgainerシステムのアカウントID');
                $table->string('campaign_id', 50)->comment(
                    'ADgainerシステムのキャンペーンID。destinationURLのクエリパラメータを分解して取得'
                );
                $table->string('currency', 50)->comment(
                    '顧客アカウントの通貨。'
                );
                $table->text('account')->nullable()->comment('カスタマーアカウントのわかりやすい名前。');
                $table->string('timeZone', 50)->nullable()->comment(
                    '顧客アカウント用に選択されたタイムゾーンの名前。 たとえば、「（GMT-05：00）東部時間」などです。
                    このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。'
                );
                $table->string('network', 50)->nullable()->comment(
                    '第1レベルのネットワークタイプ。'
                );
                $table->string('networkWithSearchPartners', 50)->nullable()->comment(
                    '第2レベルのネットワークタイプ（検索パートナーを含む）。'
                );
                $table->boolean('canManageClients')->nullable()->comment(
                    'アカウントがクライアントセンターアカウント（true）か通常のAdWordsアカウント（false）かを示します。'
                );
                $table->string('clickType', 50)->nullable()->comment(
                    '"[インプレッション数]フィールドには、そのクリックタイプで広告が配信された頻度が反映されます。
                    広告は複数のクリックタイプで表示できるため、インプレッション数は2倍になり、合計が正確でない可能性があります。"'
                );
                $table->string('conversionCategory', 50)->nullable()->comment(
                    'ユーザーがコンバージョンを達成するために実行するアクションを表すカテゴリ。
                    ゼロ変換の行が返されないようにします。値：「ダウンロード」、「リード」、「購入'
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
                $table->string('conversionName')->nullable()->comment(
                    'コンバージョンタイプの名前。ゼロ変換の行が返されないようにします。'
                );
                $table->double('totalConvValue')->nullable()->comment(
                    'すべてのコンバージョンのコンバージョン値の合計。'
                );
                $table->double('costConv')->nullable()->comment(
                    'コンバージョントラッキングクリック数をコンバージョン数で割った値です。'
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
                $table->string('conversionSource', 50)->nullable()->comment(
                    'ウェブサイトなどの変換元、通話からのインポート。'
                );
                $table->bigInteger('customerID')->nullable()->comment(
                    '顧客ID。'
                );
                $table->integer('hourOfDay')->nullable()->comment(
                    '1日の時間は0と23の間の数値です。'
                );
                $table->boolean('autoTaggingEnabled')->nullable()->comment(
                    'アカウントで自動タグ設定が有効になっているかどうかを示します。'
                );
                $table->boolean('testAccount')->nullable()->comment(
                    'アカウントがテストアカウントかどうかを示します。'
                );
                $table->dateTime('month')->nullable()->comment(
                    '月の最初の日。yyyy-MM-ddの形式です。'
                );
                $table->string('monthOfYear', 50)->nullable()->comment(
                    '月の名前です（例：「12月」）。'
                );
                $table->dateTime('quarter')->nullable()->comment(
                    '四半期の最初の日は、yyyy-MM-ddの形式です。四半期の暦年を使用します。
                    たとえば、2014年の第2四半期は2014-04-01に始まります。'
                );
                $table->double('valueConv')->nullable()->comment(
                    'コンバージョン数の合計をコンバージョン数で割った値。'
                );
                $table->bigInteger('viewThroughConv')->nullable()->comment(
                    'ビュースルーコンバージョンの合計数。これは、ディスプレイネットワーク広告が表示された後、
                    後で他の広告とやり取り（クリックなど）せずにサイトのコンバージョンを達成した場合に発生します。'
                );
                $table->dateTime('week')->nullable()->comment(
                    'yyyy-MM-ddの形式の月曜日の日付。'
                );
                $table->integer('year')->nullable()->comment(
                    '年はyyyyの形式です。'
                );

                $table->unique('id', 'id_UNIQUE');
                $table->index('exeDate', 'repo_adw_account_report_conv1');
                $table->index('startDate', 'repo_adw_account_report_conv2');
                $table->index('endDate', 'repo_adw_account_report_conv3');
                $table->index('account_id', 'repo_adw_account_report_conv4');
                $table->index('campaign_id', 'repo_adw_account_report_conv5');
                $table->index('currency', 'repo_adw_account_report_conv6');
                $table->index('timeZone', 'repo_adw_account_report_conv7');
                $table->index('network', 'repo_adw_account_report_conv8');
                $table->index('networkWithSearchPartners', 'repo_adw_account_report_conv9');
                $table->index('canManageClients', 'repo_adw_account_report_conv10');
                $table->index('clickType', 'repo_adw_account_report_conv11');
                $table->index('conversionCategory', 'repo_adw_account_report_conv12');
                $table->index('conversionTrackerId', 'repo_adw_account_report_conv13');
                $table->index('conversionName', 'repo_adw_account_report_conv14');
                $table->index('day', 'repo_adw_account_report_conv15');
                $table->index('dayOfWeek', 'repo_adw_account_report_conv16');
                $table->index('device', 'repo_adw_account_report_conv17');
                $table->index('conversionSource', 'repo_adw_account_report_conv18');
                $table->index('customerID', 'repo_adw_account_report_conv19');
                $table->index('hourOfDay', 'repo_adw_account_report_conv20');
                $table->index('autoTaggingEnabled', 'repo_adw_account_report_conv21');
                $table->index('testAccount', 'repo_adw_account_report_conv22');
                $table->index('month', 'repo_adw_account_report_conv23');
                $table->index('monthOfYear', 'repo_adw_account_report_conv24');
                $table->index('quarter', 'repo_adw_account_report_conv25');
                $table->index('week', 'repo_adw_account_report_conv26');
                $table->index('year', 'repo_adw_account_report_conv27');
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
        Schema::dropIfExists('repo_adw_account_report_conv');
    }
}
