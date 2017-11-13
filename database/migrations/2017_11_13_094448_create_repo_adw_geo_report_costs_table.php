<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoAdwGeoReportCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo_adw_geo_report_cost', function (Blueprint $table) {
            $table->increments('id');
            $table->date('exeDate')->comment('レポートAPI実行日');
            $table->date('startDate')->comment('APIで指定したレポートの開始日');
            $table->date('endDate')->comment('APIで指定したレポートの終了日');
            $table->string('account_id', 50)->comment('ADgainerシステムのアカウントID');
            $table->string('campaign_id', 50)->comment('ADgainerシステムのキャンペーンID。destinationURLのクエリパラメータを分解して取得');
            $table->string('currency', 50)->nullable()->comment('顧客口座の通貨。');
            $table->text('account')->nullable()->comment('カスタマーアカウントのわかりやすい名前。');
            $table->string('timeZone', 50)->nullable()->comment('顧客アカウント用に選択されたタイムゾーンの名前。 たとえば、「（GMT-05：00）東部時間」などです。 このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。');
            $table->string('adType', 50)->nullable()->comment('広告の基礎となるメディア形式。 値は[テンプレート広告]ページ（URL：https://developers.google.com/adwords/api/docs/appendix/templateads）、またはMediaType（URL：https://developers.google.com/adwords/api/docs/reference/latest/AdGroupAdService.Media.MediaType）列挙型です。');
            $table->bigInteger('adGroupID')->nullable()->comment('広告グループのID。');
            $table->text('adGroup')->nullable()->comment('広告グループの名前。');
            $table->string('adGroupState', 50)->nullable()->comment('広告グループのステータス。');
            $table->string('network', 50)->nullable()->comment('第1レベルのネットワークタイプ。');
            $table->string('networkWithSearchPartners', 50)->nullable()->comment('第2レベルのネットワークタイプ（検索パートナーを含む）。');
            $table->double('allConvRate')->nullable()->comment('AllConversionsをコンバージョントラッキングできる合計クリック数で割ったものです。これは、広告のクリックがコンバージョンにつながった頻度です。 "x.xx％"として返されるパーセンテージ。');
            $table->double('allConv')->nullable()->comment('AdWordsが推進するコンバージョン数の最善の見積もり。ウェブサイト、クロスデバイス、電話通話のコンバージョンが含まれます。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
            $table->double('allConvValue')->nullable()->comment('推定されたものを含む、すべてのコンバージョンの合計値。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
            $table->double('avgCost')->nullable()->comment('インタラクションごとに支払う平均金額。この金額は、広告の合計費用を合計インタラクション数で割ったものです。');
            $table->double('avgCPC')->nullable()->comment('すべてのクリックの総コストを、受け取った総クリック数で割った値。');
            $table->double('avgCPM')->nullable()->comment('平均インプレッション単価（CPM）。');
            $table->double('avgCPV')->nullable()->comment('ユーザーが広告を表示するたびに支払う平均金額。平均CPVは、すべての広告ビューの総コストをビュー数で割った値で定義されます。');
            $table->double('avgPosition')->nullable()->comment('他の広告主様との相対的な広告の掲載順位');
            $table->bigInteger('campaignID')->nullable()->comment('キャンペーンのID。');
            $table->text('campaign')->nullable()->comment('キャンペーンの名前。');
            $table->string('campaignState', 50)->nullable()->comment('キャンペーンのステータス。');
            $table->integer('city')->nullable()->comment('印象に関連付けられた都市のID。 LocationCriterionService（URL：https://developers.google.com/adwords/api/docs/reference/latest/LocationCriterionService）を使用して、対応する名前やその他の情報を検索できます。');
            $table->bigInteger('clicks')->nullable()->comment('クリック数。');
            $table->double('convRate')->nullable()->comment('コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったものです。 "x.xx％"として返されるパーセンテージ。');
            $table->double('conversions')->nullable()->comment('最適化を選択したすべてのコンバージョンアクションのコンバージョン数。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
            $table->double('totalConvValue')->nullable()->comment('すべてのコンバージョンのコンバージョン値の合計。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
            $table->double('cost')->nullable()->comment('この期間のクリック単価（CPC）とインプレッション単価（CPM）の合計。');
            $table->double('costAllConv')->nullable()->comment('総費用をすべてのコンバージョンで割った値。');
            $table->double('costConv')->nullable()->comment('コンバージョントラッキングクリック数に起因する費用をコンバージョン数で割った値');
            $table->integer('countryTerritory')->nullable()->comment('インプレッションに関連付けられた国のID。 LocationCriterionService (URL：https://developers.google.com/adwords/api/docs/reference/latest/LocationCriterionService)を使用すると、対応する名前やその他の情報を参照できます。');
            $table->double('crossDeviceConv')->nullable()->comment('顧客が1つの端末でAdWords広告をクリックしてから別の端末やブラウザで変換した後のコンバージョンデバイス間のコンバージョンは既にAllConversions列に含まれています。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
            $table->double('ctr')->nullable()->comment('広告がクリックされた回数（クリック数）を広告が表示された回数（インプレッション数）で割ったものです。 "x.xx％"として返されるパーセンテージ。');
            $table->text('clientName')->nullable()->comment('カスタマーのわかりやすい名前。');
            $table->date('day')->nullable()->comment('日付はyyyy-MM-ddの形式になります。');
            $table->string('dayOfWeek', 50)->nullable()->comment('曜日の名前です（例：「月曜日」）。');
            $table->string('device', 50)->nullable()->comment('インプレッションが表示されたデバイスの種類。');
            $table->bigInteger('customerID')->nullable()->comment('顧客ID。');
            $table->bigInteger('impressions')->nullable()->comment('Googleネットワークの検索結果ページやウェブサイトに広告が表示された回数をカウントします。');
            $table->double('interactionRate')->nullable()->comment('広告が表示された後にユーザーがどのくらい頻繁に広告を操作するか。これはインタラクションの数を広告の表示回数で割ったものです。 "x.xx％"として返されるパーセンテージ。');
            $table->bigInteger('interactions')->nullable()->comment('相互作用の数インタラクションとは、テキストやショッピング広告のクリック、動画広告の表示など、広告フォーマットに関連する主要なユーザーアクションです。');
            $table->text('interactionTypes')->nullable()->comment('Interactions、InteractionRate、およびAverageCost列に反映される相互作用のタイプ。');
            $table->boolean('isTargetable')->nullable()->comment('行の場所（インプレッションに関連付けられたすべての場所の中）が、その行のインプレッションのターゲティングの場所であるかどうかを示します。');
            $table->string('locationType', 50)->nullable()->comment('場所のタイプ。 AREA_OF_INTERESTは、検索された場所、または表示されたコンテンツから派生した場所を示します。 LOCATION_OF_PRESENCEは、ユーザーの実際の物理的な場所です。');
            $table->integer('metroArea')->nullable()->comment('メトロエリアのID印象に関連付けられた場所。 LocationCriterionService（URL：https://developers.google.com/adwords/api/docs/reference/latest/LocationCriterionService）を使用して、対応する名前やその他の情報を検索できます。');
            $table->string('month', 50)->nullable()->comment('月の最初の日。yyyy-MM-ddの形式です。');
            $table->string('monthOfYear', 50)->nullable()->comment('月の名前です（例：「12月」）。');
            $table->bigInteger('mostSpecificLocation')->nullable()->comment('インプレッションに関連付けられた最も具体的なロケーション基準のID。 LocationCriterionService（URL：https://developers.google.com/adwords/api/docs/reference/latest/LocationCriterionService）を使用して、対応する名前やその他の情報を検索できます。');
            $table->date('quarter')->nullable()->comment('四半期の最初の日は、yyyy-MM-ddの形式です。四半期の暦年を使用します。たとえば、2014年第2四半期は2014-04-01に開始します。');
            $table->integer('region')->nullable()->comment('インプレッションに関連付けられた地域のID。 LocationCriterionService（URL：https://developers.google.com/adwords/api/docs/reference/latest/LocationCriterionService）を使用して、対応する名前やその他の情報を検索できます。');
            $table->double('valueAllConv')->nullable()->comment('すべてのコンバージョンの平均値です。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
            $table->double('valueConv')->nullable()->comment('コンバージョン数の合計をコンバージョン数で割った値。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
            $table->double('viewRate')->nullable()->comment('TrueView動画広告の表示回数を、TrueViewインディスプレイ広告のサムネイル表示回数を含むインプレッション数で割ったものです。 "x.xx％"として返されるパーセンテージ。');
            $table->bigInteger('views')->nullable()->comment('動画広告が表示された回数。');
            $table->bigInteger('viewThroughConv')->nullable()->comment('ビュースルーコンバージョンの合計数。これは、ディスプレイネットワーク広告が表示された後、後で他の広告とやり取り（クリックなど）せずにサイトのコンバージョンを達成した場合に発生します。このフィールドは、米国のロケールを使用してフォーマットされています。つまり、3桁区切り「,」、小数点区切りは「.」を使用しています。');
            $table->date('week')->nullable()->comment('yyyy-MM-ddの形式の月曜日の日付。');
            $table->integer('year')->nullable()->comment('年はyyyyの形式です。');

            $table->unique('id', 'id_UNIQUE');
            $table->index('exeDate', 'repo_adw_geo_report_cost1');
            $table->index('startDate', 'repo_adw_geo_report_cost2');
            $table->index('endDate', 'repo_adw_geo_report_cost3');
            $table->index('account_id', 'repo_adw_geo_report_cost4');
            $table->index('campaign_id', 'repo_adw_geo_report_cost5');
            $table->index('adType', 'repo_adw_geo_report_cost6');
            $table->index('adGroupID', 'repo_adw_geo_report_cost7');
            $table->index('adGroupState', 'repo_adw_geo_report_cost8');
            $table->index('network', 'repo_adw_geo_report_cost9');
            $table->index('networkWithSearchPartners', 'repo_adw_geo_report_cost10');
            $table->index('day', 'repo_adw_geo_report_cost11');
            $table->index('dayOfWeek', 'repo_adw_geo_report_cost12');
            $table->index('device', 'repo_adw_geo_report_cost13');
            $table->index('locationType', 'repo_adw_geo_report_cost14');
            $table->index('month', 'repo_adw_geo_report_cost15');
            $table->index('monthOfYear', 'repo_adw_geo_report_cost16');
            $table->index('quarter', 'repo_adw_geo_report_cost17');
            $table->index('week', 'repo_adw_geo_report_cost18');
            $table->index('year', 'repo_adw_geo_report_cost19');
            $table->index('currency', 'repo_adw_geo_report_cost20');
            $table->index('timeZone', 'repo_adw_geo_report_cost21');
            $table->index('campaignID', 'repo_adw_geo_report_cost22');
            $table->index('campaignState', 'repo_adw_geo_report_cost23');
            $table->index('city', 'repo_adw_geo_report_cost24');
            $table->index('countryTerritory', 'repo_adw_geo_report_cost25');
            $table->index('customerID', 'repo_adw_geo_report_cost26');
            $table->index('isTargetable', 'repo_adw_geo_report_cost27');
            $table->index('metroArea', 'repo_adw_geo_report_cost28');
            $table->index('mostSpecificLocation', 'repo_adw_geo_report_cost29');
            $table->index('region', 'repo_adw_geo_report_cost30');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repo_adw_geo_report_cost');
    }
}
