<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoAdwAccountReportCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo_adw_account_report_cost', function (Blueprint $table) {
            $table->increments('id');
            $table->date('exeDate')->comment('レポートAPI実行日');
            $table->date('startDate')->comment('APIで指定したレポートの開始日');
            $table->date('endDate')->comment('APIで指定したレポートの終了日');
            $table->string('account_id', 50)->nullable()->comment('ADgainerシステムのアカウントID');
            $table->string('campaign_id', 50)
                ->nullable()
                ->comment('ADgainerシステムのキャンペーンID\ndestinationURLのクエリパラメータを分解して取得');
            $table->string('currency', 50)->nullable()->comment('顧客アカウントの通貨。');
            $table->text('account')->nullable()->comment('カスタマーアカウントのわかりやすい名。');
            $table->string('timeZone', 50)->nullable()->comment('顧客アカウント用に選択されたタイムゾーンの名前。
                たとえば、「（GMT-05：00）東部時間」などです。
                このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。');
            $table->double('activeViewAvgCPM')->nullable()
                ->comment('視認可能インプレッションの平均費用（ActiveViewImpressions）。');
            $table->double('activeViewViewableCTR')->nullable()
                ->comment('広告が表示された後にユーザーが広告をクリックした頻度');
            $table->bigInteger('activeViewViewableImpressions')->nullable()
                ->comment('ディスプレイネットワークサイトで広告が表示される頻度');
            $table->double('activeViewMeasurableImprImpr')->nullable()
                ->comment('アクティブビューで計測されたインプレッション数と配信済みインプレッション数の比。');
            $table->double('activeViewMeasurableCost')->nullable()
                ->comment('Active Viewで測定可能なインプレッションの費。');
            $table->bigInteger('activeViewMeasurableImpr')->nullable()
                ->comment('広告が表示されているプレースメントに広告が表示された回数。');
            $table->double('activeViewViewableImprMeasurableImpr')->nullable()
                ->comment('Active View対応サイトに広告が表示された時間（測定可能なインプレッション数）
                    と表示可能（表示可能なインプレッション数）の割合です。');
            $table->string('network', 50)->nullable()->comment('第1レベルのネットワークタイプ。');
            $table->string('networkWithSearchPartners', 50)->nullable()
                ->comment('第2レベルのネットワークタイプ（検索パートナーを含む）。');
            $table->double('avgCost')->nullable()
                ->comment('インタラクションごとに支払う平均金額。この金額は、広告の合計費用を合計インタラクション数で割ったものです。');
            $table->double('avgCPC')->nullable()
                ->comment('すべてのクリックの総コストを、受け取った総クリック数で割った値。');
            $table->double('avgCPM')->nullable()->comment('平均インプレッション単価（CPM）。');
            $table->double('avgPosition')->nullable()->comment('他の広告主様との相対的な広告の掲載順位');
            $table->boolean('canManageClients')->nullable()
                ->comment('アカウントがクライアントセンターアカウント（true）か通常のAdWordsアカウント（false）かを示します。');
            $table->bigInteger('clicks')->nullable()->comment('クリック数。');
            $table->string('clickType', 50)->nullable()
                ->comment('[インプレッション数]フィールドには、そのクリックタイプで広告が配信された頻度が反映されます。
                    広告は複数のクリックタイプで表示できるため、インプレッション数は2倍になり、合計が正確でない可能性があります。');
            $table->double('convRate')->nullable()
                ->comment('コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったものです。
                x.xx％として返されるパーセンテージ。');
            $table->double('conversions')->nullable()
                ->comment('最適化を選択したすべてのコンバージョンアクションのコンバージョン数。このフィールドは、');
            $table->double('totalConvValue')->nullable()->comment('すべてのコンバージョンのコンバージョン値の合計。');
            $table->double('cost')->nullable()
                ->comment('この期間のクリック単価（CPC）とインプレッション単価（CPM）の合計。');
            $table->double('costConv')->nullable()
                ->comment('コンバージョントラッキングクリック数をコンバージョン数で割った値です。');
            $table->double('ctr')->nullable()
                ->comment('広告がクリックされた回数（クリック数）を広告が表示された回数（インプレッション数）
                    で割ったものです。x.xx％として返されるパーセンテージ。');
            $table->string('clientName')->nullable()->comment('カスタマーのわかりやすい名前。');
            $table->date('day')->nullable()->comment('日付はyyyy-MM-ddの形式になります。');
            $table->string('dayOfWeek', 50)->nullable()->comment('曜日の名前です（例：「月曜日」）。');
            $table->string('device', 50)->nullable()->comment('インプレッションが表示されたデバイスの種類。');
            $table->bigInteger('customerID')->nullable()->comment('顧客ID。');
            $table->bigInteger('hourOfDay')->nullable()->comment('1日の時間は0と23の間の数値です。');
            $table->bigInteger('impressions')->nullable()
                ->comment('Googleネットワークの検索結果ページやウェブサイトに広告が表示された回数をカウントします。');
            $table->double('interactionRate')->nullable()
                ->comment('広告が表示された後にユーザーがどのくらい頻繁に広告を操作するか。
                    これはインタラクションの数を広告の表示回数で割ったものです。x.xx％として返されるパーセンテージ。');
            $table->bigInteger('interactions')->nullable()
                ->comment('相互作用の数 インタラクションとは、テキストやショッピング広告のクリック、
                    動画広告の表示など、広告フォーマットに関連する主要なユーザーアクションです。');
            $table->text('interactionTypes')->nullable()
                ->comment('インタラクションに反映されるインタラクションの種類、インタラクションレート、平均コストの各列を表示します。');
            $table->boolean('autoTaggingEnabled')->nullable()
                ->comment('アカウントで自動タグ設定が有効になっているかどうかを示します。');
            $table->boolean('testAccount')->nullable()->comment('アカウントがテストアカウントかどうかを示します。');
            $table->date('month')->nullable()->comment('月の最初の日。yyyy-MM-ddの形式です。');
            $table->string('monthOfYear')->nullable()->comment('月の名前です（例：「12月」）。');
            $table->date('quarter')->nullable()
                ->comment('四半期の最初の日は、yyyy-MM-ddの形式です。
                    四半期の暦年を使用します。たとえば、2014年の第2四半期は2014-04-01に始まります。');
            $table->double('valueConv')->nullable()->comment('コンバージョン数の合計をコンバージョン数で割った値。');
            $table->string('week')->nullable()->comment('yyyy-MM-ddの形式の月曜日の日付。');
            $table->bigInteger('year')->nullable()->comment('年はyyyyの形式です。');

            $table->index('exeDate', 'repo_adw_account_report_cost_idx1');
            $table->index('startDate', 'repo_adw_account_report_cost_idx2');
            $table->index('endDate', 'repo_adw_account_report_cost_idx3');
            $table->index('account_id', 'repo_adw_account_report_cost_idx4');
            $table->index('campaign_id', 'repo_adw_account_report_cost_idx5');
            $table->index('network', 'repo_adw_account_report_cost_idx6');
            $table->index('networkWithSearchPartners', 'repo_adw_account_report_cost_idx7');
            $table->index('clickType', 'repo_adw_account_report_cost_idx8');
            $table->index('day', 'repo_adw_account_report_cost_idx9');
            $table->index('dayOfWeek', 'repo_adw_account_report_cost_idx10');
            $table->index('device', 'repo_adw_account_report_cost_idx11');
            $table->index('hourOfDay', 'repo_adw_account_report_cost_idx12');
            $table->index('month', 'repo_adw_account_report_cost_idx13');
            $table->index('monthOfYear', 'repo_adw_account_report_cost_idx14');
            $table->index('quarter', 'repo_adw_account_report_cost_idx15');
            $table->index('week', 'repo_adw_account_report_cost_idx16');
            $table->index('year', 'repo_adw_account_report_cost_idx17');
            $table->index('currency', 'repo_adw_account_report_cost_idx18');
            $table->index('timeZone', 'repo_adw_account_report_cost_idx19');
            $table->index('canManageClients', 'repo_adw_account_report_cost_idx20');
            $table->index('customerID', 'repo_adw_account_report_cost_idx21');
            $table->index('autoTaggingEnabled', 'repo_adw_account_report_cost_idx22');
            $table->index('testAccount', 'repo_adw_account_report_cost_idx23');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repo_adw_account_report_cost');
    }
}
