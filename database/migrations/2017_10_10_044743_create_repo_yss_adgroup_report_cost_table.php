<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoYssAdgroupReportCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo_yss_adgroup_report_cost', function (Blueprint $table) {
            $table->increments('id');
            $table->date('exeDate')->comment('YSSレポートAPI実行日');
            $table->date('startDate')->comment('YSSレポートAPIで指定したレポートの開始日');
            $table->date('endDate')->comment('YSSレポートAPIで指定したレポートの終了日');
            $table->string('account_id', 50)->nullable()->comment('ADgainerシステムのアカウントID');
            $table->string('campaign_id', 50)
                    ->nullable()
                    ->comment('ADgainerシステムのキャンペーンID destinationURLのクエリパラメータを分解して取得');
            $table->bigInteger('campaignID')->nullable()->comment('キャンペーンID');
            $table->bigInteger('adgroupID')->nullable()->comment('広告グループID');
            $table->text('campaignName')->nullable()->comment('キャンペーン名');
            $table->string('adgroupName')->nullable()->comment('広告グループ名');
            $table->string('adgroupDistributionSettings', 50)->nullable()->comment('配信設定');
            $table->bigInteger('adGroupBid')->nullable()->comment('広告グループの入札価格');
            $table->bigInteger('cost')->nullable()->comment('コスト');
            $table->bigInteger('impressions')->nullable()->comment('インプレッション数');
            $table->bigInteger('clicks')->nullable()->comment('クリック数');
            $table->double('ctr')->nullable()->comment('クリック率');
            $table->double('averageCpc')->comment('平均CPC');
            $table->double('averagePosition')->nullable()->comment('平均掲載順位');
            $table->double('impressionShare')->nullable()->comment('インプレッションシェア');
            $table->double('exactMatchImpressionShare')->nullable()->comment('完全一致のインプレッションシェア');
            $table->double('qualityLostImpressionShare')->comment('インプレッション損失率（掲載順位）');
            $table->text('trackingURL')->nullable()->comment('トラッキングURL');
            $table->text('customParameters')->nullable()->comment('カスタムパラメータ');
            $table->double('conversions')->nullable()->comment('コンバージョン数');
            $table->double('convRate')->nullable()->comment('コンバージョン率');
            $table->double('convValue')->nullable()->comment('コンバージョンの価値');
            $table->double('costPerConv')->nullable()->comment('コスト/コンバージョン数');
            $table->double('valuePerConv')->nullable()->comment('価値/コンバージョン数');
            $table->double('mobileBidAdj')->nullable()->comment('スマートフォン入札価格調整率（％）');
            $table->double('desktopBidAdj')->nullable()->comment('PC入札価格調整率（％）');
            $table->double('tabletBidAdj')->nullable()->comment('タブレット入札価格調整率（％）');
            $table->string('network', 50)->nullable()->comment('広告掲載方式の指定');
            $table->string('device', 50)->nullable()->comment('デバイス');
            $table->date('day')->comment('レコードの対象日：年（year）、月（monthofYear）、日（day）。左項目を加工してDATETIMEに変換');
            $table->string('dayOfWeek', 50)->nullable()->comment('曜日');
            $table->string('quarter', 50)->nullable()->comment('四半期');
            $table->string('month', 50)->nullable()->comment('毎月');
            $table->string('week', 50)->nullable()->comment('毎週');
            $table->bigInteger('hourofday')->nullable()->comment('時間');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repo_yss_adgroup_report_cost');
    }
}
