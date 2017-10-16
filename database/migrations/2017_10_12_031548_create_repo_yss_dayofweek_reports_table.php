<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoYssDayofweekReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo_yss_dayofweek_report', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id', 50)->nullable()->comment('ADgainerシステムのアカウントID');
            $table->string('campaign_id', 50)
                    ->nullable()
                    ->comment('ADgainerシステムのキャンペーンID\ndestinationURLのクエリパラメータを分解して取得');
            $table->bigInteger('campaignID')->nullable()->comment('キャンペーンID');
            $table->text('campaignName')->nullable()->comment('キャンペーン名');
            $table->bigInteger('cost')->nullable()->commnet('コスト');
            $table->bigInteger('impressions')->nullable()->commnet('インプレッション数');
            $table->bigInteger('clicks')->nullable()->commnet('クリック数');
            $table->double('ctr')->nullable()->comment('クリック率');
            $table->double('averageCpc')->nullable()->comment('平均CPC');
            $table->double('averagePosition')->nullable()->comment('平均掲載順位');
            $table->bigInteger('bidAdjustment')->nullable()->commnet('入札価格調整率(％)');
            $table->bigInteger('targetScheduleID')->nullable()->commnet('曜日・時間帯ID');
            $table->string('targetSchedule', 50)->nullable()->commnet('曜日・時間帯');
            $table->double('conversions')->nullable()->commnet('コンバージョン数');
            $table->double('convRate')->nullable()->commnet('コンバージョン率');
            $table->double('convValue')->nullable()->commnet('コンバージョンの価値');
            $table->double('costPerConv')->nullable()->commnet('コスト/コンバージョン数');
            $table->double('valuePerConv')->nullable()->commnet('価値/コンバージョン数');
            $table->double('allConv')->nullable()->commnet('すべてのコンバージョン数');
            $table->double('allConvRate')->nullable()->commnet('すべてのコンバージョン率');
            $table->double('allConvValue')->nullable()->commnet('すべてのコンバージョンの価値');
            $table->double('costPerAllConv')->nullable()->commnet('コスト/すべてのコンバージョン数');
            $table->double('valuePerAllConv')->nullable()->commnet('価値/すべてのコンバージョン数');
            $table->dateTime('day')->nullable()
                ->comment('レコードの対象日：年（year）、月（monthofYear）、日（day）。左項目を加工してDATETIMEに変換');
            $table->string('quarter', 50)->nullable()->comment('四半期');
            $table->string('month', 50)->nullable()->comment('毎月');
            $table->string('week', 50)->nullable()->comment('毎週');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repo_yss_dayofweek_report');
    }
}
