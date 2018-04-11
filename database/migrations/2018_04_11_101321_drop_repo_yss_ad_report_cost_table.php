<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropRepoYssAdReportCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('repo_yss_ad_report_cost');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create(
            'repo_yss_ad_report_cost',
            function (Blueprint $table) {
                $table->increments('id');
                $table->date('exeDate')->comment('YSSレポートAPI実行日');
                $table->date('startDate')->comment('YSSレポートAPIで指定したレポートの開始日');
                $table->date('endDate')->comment('YSSレポートAPIで指定したレポートの終了日');
                $table->string('account_id', 50)->nullable()->comment('ADgainerシステムのアカウントID');
                $table->string('campaign_id', 50)
                    ->nullable()
                    ->comment('ADgainerシステムのキャンペーンID\ndestinationURLのクエリパラメータを分解して取得');
                $table->bigInteger('campaignID')->nullable()->comment('キャンペーンID');
                $table->bigInteger('adgroupID')->nullable()->comment('広告グループID');
                $table->bigInteger('adID')->nullable()->comment('広告ID');
                $table->text('campaignName')->nullable()->comment('キャンペーン名');
                $table->text('adgroupName')->nullable()->comment('広告グループ名');
                $table->text('adName')->nullable()->comment('広告名');
                $table->text('title')->nullable()->comment('タイトル');
                $table->text('description1')->nullable()->comment('説明文1');
                $table->text('displayURL')->nullable()->comment('表示URL');
                $table->text('destinationURL')->nullable()->comment('リンク先URL');
                $table->string('adType', 50)->nullable()->comment('広告タイプ');
                $table->string('adDistributionSettings', 50)->nullable()->comment('配信設定');
                $table->string('adEditorialStatus', 50)->nullable()->comment('審査状況');
                $table->bigInteger('cost')->nullable()->comment('コスト');
                $table->bigInteger('impressions')->nullable()->comment('インプレッション数');
                $table->bigInteger('clicks')->nullable()->comment('クリック数');
                $table->double('ctr')->nullable()->comment('クリック率');
                $table->double('averageCpc')->nullable()->comment('平均CPC');
                $table->double('averagePosition')->nullable()->comment('平均掲載順位');
                $table->text('description2')->nullable()->comment('説明文2');
                $table->string('focusDevice', 50)->nullable()->comment('優先デバイス');
                $table->text('trackingURL')->nullable()->comment('トラッキングURL');
                $table->text('customParameters')->nullable()->comment('カスタムパラメータ');
                $table->text('landingPageURL')->nullable()->comment('最終リンク先URL');
                $table->text('landingPageURLSmartphone')->nullable()->comment('最終リンク先URL（スマートフォン）');
                $table->bigInteger('adTrackingID')->nullable()->comment('広告トラッキングID');
                $table->double('conversions')->nullable()->comment('コンバージョン数');
                $table->double('convRate')->nullable()->comment('コンバージョン率');
                $table->double('convValue')->nullable()->comment('コンバージョンの価値');
                $table->double('costPerConv')->nullable()->comment('コスト/コンバージョン数');
                $table->double('valuePerConv')->nullable()->comment('価値/コンバージョン数');
                $table->double('allConv')->nullable()->comment('すべてのコンバージョン数');
                $table->double('allConvRate')->nullable()->comment('すべてのコンバージョン率');
                $table->double('allConvValue')->nullable()->comment('すべてのコンバージョンの価値');
                $table->double('costPerAllConv')->nullable()->comment('コスト/すべてのコンバージョン数');
                $table->double('valuePerAllConv')->nullable()->comment('価値/すべてのコンバージョン数');
                $table->string('network', 50)->nullable()->comment('広告掲載方式の指定');
                $table->string('clickType', 50)->nullable()->comment('クリック種別');
                $table->string('device', 50)->nullable()->comment('デバイス');
                $table->dateTime('day')->nullable()
                    ->comment('レコードの対象日：年（year）、月（monthofYear）、日（day）。左項目を加工してDATETIMEに変換');
                $table->string('dayOfWeek', 50)->nullable()->comment('曜日');
                $table->string('quarter', 50)->nullable()->comment('四半期');
                $table->string('month', 50)->nullable()->comment('毎月');
                $table->string('week', 50)->nullable()->comment('毎週');
                $table->bigInteger('adKeywordID')->nullable()->comment('キーワードID');
                $table->text('title1')->nullable()->comment('タイトル1');
                $table->text('title2')->nullable()->comment('タイトル2');
                $table->text('description')->nullable()->comment('説明文');
                $table->text('directory1')->nullable()->comment('ディレクトリ1');
                $table->text('directory2')->nullable()->comment('ディレクトリ2');
            }
        );
    }
}
