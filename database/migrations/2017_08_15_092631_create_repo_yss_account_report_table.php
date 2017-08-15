<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepoYssAccountReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo_yss_account_report', function (Blueprint $table) {
            $table->increments('id', 11)->index();
            $table->string('account_id', 50)->comment('ADgainerシステムのアカウントID')->nullable();
            $table->string('campaign_id', 50)->comment('ADgainerシステムのキャンペーンID destinationURLのクエリパラメータを分解して取得')->nullable();
            $table->bigInteger('cost')->comment('ADgainerシステムのキャンペーンID destinationURLのクエリパラメータを分解して取得')->nullable()->unsigned();
            $table->bigInteger('impressions')->comment('インプレッション数')->nullable()->unsigned();
            $table->bigInteger('clicks')->comment('クリック数')->nullable()->unsigned();
            $table->double('ctr')->comment('クリック率')->nullable();
            $table->double('averageCpc')->comment('平均CPC')->nullable();
            $table->double('averagePosition')->comment('平均掲載順位')->nullable();
            $table->bigInteger('invalidClicks')->comment('無効なクリック')->nullable()->unsigned();
            $table->double('invalidClickRate')->comment('無効なクリック率')->nullable();
            $table->double('impressionShare')->comment('インプレッションシェア')->nullable();
            $table->double('exactMatchImpressionShare')->comment('完全一致のインプレッションシェア')->nullable();
            $table->double('budgetLostImpressionShare')->comment('インプレッション損失率（予算）')->nullable();
            $table->double('qualityLostImpressionShare')->comment('インプレッション損失率（掲載順位）')->nullable();
            $table->text('trackingURL')->comment('トラッキングURL')->nullable();
            $table->double('conversions')->comment('コンバージョン数')->nullable();
            $table->double('convRate')->comment('コンバージョン率')->nullable();
            $table->double('convValue')->comment('コンバージョンの価値')->nullable();
            $table->double('costPerConv')->comment('コスト/コンバージョン数')->nullable();
            $table->double('valuePerConv')->comment('価値/コンバージョン数')->nullable();
            $table->double('allConv')->comment('すべてのコンバージョン数')->nullable();
            $table->double('allConvRate')->comment('すべてのコンバージョン率')->nullable();
            $table->double('allConvValue')->comment('すべてのコンバージョンの価値')->nullable();
            $table->double('costPerAllConv')->comment('コスト/すべてのコンバージョン数')->nullable();
            $table->double('valuePerAllConv')->comment('価値/すべてのコンバージョン数')->nullable();
            $table->string('network', 50)->comment('広告掲載方式の指定')->nullable();
            $table->double('device', 50)->comment('デバイス')->nullable();
            $table->dateTime('day', 50)->comment('レコードの対象日：年（year）、月（monthofYear）、日（day）。左項目を加工してDATETIMEに変換')->nullable();
            $table->string('dayOfWeek', 50)->comment('曜日')->nullable();
            $table->string('quarter', 50)->comment('四半期')->nullable();
            $table->string('month', 50)->comment('毎月')->nullable();
            $table->string('week', 50)->comment('毎週')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('repo_yss_account_report');
    }
}


