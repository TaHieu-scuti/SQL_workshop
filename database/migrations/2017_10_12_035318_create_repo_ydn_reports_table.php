<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoYdnReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'repo_ydn_reports',
            function (Blueprint $table) {
                $table->increments('id');
                $table->date('exeDate')->comment('YSSレポートAPI実行日');
                $table->date('startDate')->comment('YSSレポートAPIで指定したレポートの開始日');
                $table->date('endDate')->comment('YSSレポートAPIで指定したレポートの終了日');
                $table->bigInteger('accountId')->nullable()->comment('アカウントID');
                $table->text('accountName')->nullable()->comment('アカウント名');
                $table->bigInteger('campaignID')->nullable()->comment('キャンペーンID');
                $table->text('campaignName')->nullable()->comment('キャンペーン名');
                $table->string('account_id', 50)->nullable()->comment('ADgainerシステムのアカウントID');
                $table->string('campaign_id', 50)
                    ->nullable()
                    ->comment('ADgainerシステムのキャンペーンID\ndestinationURLのクエリパラメータを分解して取得');
                $table->bigInteger('adgroupID')->nullable()->comment('広告グループID');
                $table->text('adgroupName')->nullable()->comment('広告グループ名');
                $table->bigInteger('adID')->nullable()->comment('広告ID');
                $table->text('adName')->nullable()->comment('広告名');
                $table->text('adType')->nullable()
                    ->comment(
                        '広告タイプ\nhttps://github.com/yahoojp-marketing
                        /ydn-api-documents/blob/master/docs/ja/api_reference
                        /appendix/reports.md#ad_typeレスポンス'
                    );
                $table->bigInteger('destinationURLID')->nullable()->comment('リンク先URLID');
                $table->text('destinationURL')->nullable()->comment('リンク先URL');
                $table->string('prefectureID', 100)->nullable()->comment('都道府県ID');
                $table->string('prefecture', 100)->nullable()->comment('都道府県');
                $table->string('cityID', 100)->nullable()->comment('市区郡ID');
                $table->string('city', 100)->nullable()->comment('市区郡');
                $table->string('wardID', 100)->nullable()->comment('行政区ID');
                $table->string('ward', 100)->nullable()->comment('行政区');
                $table->string('gender', 20)->nullable()
                    ->comment(
                        '性別\nhttps://github.com/yahoojp-marketing
                    /ydn-api-documents/blob/master/docs/ja
                    /api_reference/appendix/reports.md#genderレスポンス'
                    );
                $table->string('age', 20)->nullable()
                    ->comment(
                        '年齢\nhttps://github.com/yahoojp-marketing
                    /ydn-api-documents/blob/master/docs/ja/api_reference
                    /appendix/reports.md#ageレスポンス'
                    );
                $table->dateTime('day')->nullable()
                    ->comment('レコードの対象日：年は取得年、月（month）、日（day）。左項目を加工してDATETIMEに変換');
                $table->bigInteger('hourofday')->nullable()->comment('時間');
                $table->text('deliverName')->nullable()->comment('広告掲載方式');
                $table->string('device', 50)->nullable()
                    ->comment(
                        'デバイス\nhttps://github.com/yahoojp-marketing
                    /ydn-api-documents/blob/master/docs/ja
                    /api_reference/appendix/reports.md#deviceレスポンス'
                    );
                $table->string('adStyle', 45)->nullable()
                    ->comment(
                        '掲載フォーマット（画像タイプ）\nhttps://github.com
                    /yahoojp-marketing/ydn-api-documents/blob/master
                    /docs/ja/api_reference/appendix/reports.md#ad_styleレスポンス'
                    );
                $table->string('mediaID', 100)->nullable()->comment('メディアID');
                $table->string('mediaName', 100)->nullable()->comment('メディア名');
                $table->string('fileName', 100)->nullable()->comment('ファイル名');
                $table->string('pixelSize', 100)->nullable()->comment('ピクセルサイズ');
                $table->text('title')->nullable()->comment('タイトル');
                $table->text('description1')->nullable()->comment('説明文1');
                $table->text('description2')->nullable()->comment('説明文2');
                $table->text('displayURL')->nullable()->comment('表示URL');
                $table->bigInteger('searchKeywordID')->nullable()->comment('サーチキーワードID');
                $table->text('searchKeyword')->nullable()->comment('サーチキーワード');
                $table->text('conversionName')->nullable()->comment('コンバージョンラベル名');
                $table->text('objectiveOfConversionTracking')->nullable()->comment('コンバージョン測定の目的\n');
                $table->string('carrier', 50)->nullable()
                    ->comment(
                        'キャリア\nhttps://github.com/yahoojp-marketing
                    /ydn-api-documents/blob/master/docs/ja/api_reference
                    /appendix/reports.md#carrierレスポンス'
                    );
                $table->string('adLayout', 50)->nullable()
                    ->comment(
                        'レイアウト\nhttps://github.com/yahoojp-marketing
                    /ydn-api-documents/blob/master/docs/ja/api_reference/appendix/reports.md#ad_layoutレスポンス'
                    );
                $table->string('imageOption', 20)->nullable()
                    ->comment(
                        '画像自動付与\nhttps://github.com/yahoojp-marketing/ydn-api-documents
                    /blob/master/docs/ja/api_reference/appendix/reports.md#image_optionレスポンス'
                    );
                $table->string('os', 100)->nullable()->comment('OS');
                $table->text('appli')->nullable()->comment('ウェブ/アプリ');
                $table->bigInteger('impressions')->nullable()->comment('インプレッション数');
                $table->double('ctr')->nullable()->comment('クリック率');
                $table->bigInteger('cost')->nullable()->comment('コスト');
                $table->bigInteger('clicks')->nullable()->comment('クリック数');
                $table->double('averageCpc')->nullable()->comment('平均CPC');
                $table->bigInteger('totalConversionsOld')->nullable()->comment('コンバージョン数（旧）');
                $table->double('totalConversionRateOld')->nullable()->comment('コンバージョン率（旧）');
                $table->double('costTotalConversionsOld')->nullable()->comment('コスト/コンバージョン数（旧）');
                $table->double('averagePosition')->nullable()->comment('平均掲載順位');
                $table->bigInteger('totalRevenueOld')->nullable()->comment('合計売上金額（旧）');
                $table->double('revenueTotalConversionOld')->nullable()->comment('売上/コンバージョン数（旧）');
                $table->bigInteger('autoVideoPlays')->nullable()->comment('動画の自動再生数');
                $table->bigInteger('clickVideoPlays')->nullable()->comment('クリックによる動画再生数');
                $table->double('videoViewedRate')->nullable()->comment('動画の再生率');
                $table->double('avgCpv')->nullable()->comment('平均CPV');
                $table->bigInteger('videoPlays')->nullable()->comment('動画が再生開始された回数');
                $table->bigInteger('videoViewsTo25')->nullable()->comment('動画が25%まで再生された回数');
                $table->bigInteger('videoViewsTo50')->nullable()->comment('動画が50%まで再生された回数');
                $table->bigInteger('videoViewsTo75')->nullable()->comment('動画が75%まで再生された回数');
                $table->bigInteger('videoViewsTo95')->nullable()->comment('動画が95%まで再生された回数');
                $table->bigInteger('videoViewsTo100')->nullable()->comment('動画が100%まで再生された回数');
                $table->double('avgPercentVideoViewed')->nullable()->comment('動画の平均再生率');
                $table->double('avgDurationVideoViewed')->nullable()->comment('動画の平均再生時間（秒）');
                $table->bigInteger('conversions')->nullable()->comment('コンバージョン数');
                $table->double('convRate')->nullable()->comment('コンバージョン率');
                $table->double('costPerConv')->nullable()->comment('コスト/コンバージョン数');
                $table->bigInteger('revenue')->nullable()->comment('合計売上金額');
                $table->bigInteger('revenuePerConv')->nullable()->comment('合計売上金額');
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
        Schema::dropIfExists('repo_ydn_reports');
    }
}
