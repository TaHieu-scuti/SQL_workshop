<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoAdwAdReportCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'repo_adw_ad_report_cost',
            function (Blueprint $table) {
                $table->increments('id');
                $table->date('exeDate')->comment('レポートAPI実行日');
                $table->date('startDate')->comment('APIで指定したレポートの開始日');
                $table->date('endDate')->comment('APIで指定したレポートの終了日');
                $table->string('account_id', 50)->nullable()->comment('ADgainerシステムのアカウントID');
                $table->string('campaign_id', 50)
                    ->nullable()
                    ->comment('ADgainerシステムのキャンペーンID\ndestinationURLのクエリパラメータを分解して取得');
                $table->string('accentColorResponsive', 50)->nullable()
                    ->comment('レスポンシブディスプレイ広告のアクセントカラー。');
                $table->string('currency', 50)->nullable()->comment('顧客口座の通貨。');
                $table->text('account')->nullable()->comment('カスタマーアカウントのわかりやすい名前。');
                $table->string('timeZone', 50)->nullable()
                    ->comment(
                        '顧客アカウント用に選択されたタイムゾーンの名前。 たとえば、「（GMT-05：00）東部時間」などです。
                    このフィールドには、タイムゾーンの夏時間の現在の状態は反映されません。'
                    );
                $table->double('activeViewAvgCPM')->nullable()->comment('視認可能インプレッションの平均費用（ActiveViewImpressions）。');
                $table->double('activeViewViewableCTR')->nullable()->comment('広告が表示された後にユーザーが広告をクリックした頻度');
                $table->bigInteger('activeViewViewableImpressions')->nullable()->comment('ディスプレイネットワークサイトで広告が表示される頻度');
                $table->double('activeViewMeasurableImprImpr')
                    ->nullable()
                    ->comment('アクティブビューで計測されたインプレッション数と配信インプレッション数の比。');
                $table->double('activeViewMeasurableCost')->nullable()->comment('Active Viewで測定可能なインプレッションの費用。');
                $table->bigInteger('activeViewMeasurableImpr')->nullable()->comment('広告が表示されているプレースメントに広告が表示された回数。');
                $table->double('activeViewViewableImprMeasurableImpr')
                    ->nullable()
                    ->comment('広告がアクティブビュー対応サイトに表示された時間（測定可能なインプレッション数）と表示可能（表示可能なインプレッション数）の割合。');
                $table->bigInteger('adGroupID')->nullable()->comment('広告グループのID。');
                $table->text('adGroup')->nullable()->comment('広告グループの名前。');
                $table->string('adGroupState', 50)->nullable()->comment('広告グループのステータス。');
                $table->string('network', 50)->nullable()->comment('第1レベルのネットワークタイプ。');
                $table->string('networkWithSearchPartners', 50)->nullable()->comment('第2レベルのネットワークタイプ（検索パートナーを含む）。');
                $table->string('adType', 50)
                    ->nullable()
                    ->comment('広告のタイプ。広告のタイプがレポートリクエストのAPIバージョンでサポートされていない場合、このフィールドの値は「不明」になります。');
                $table->double('allConvRate')
                    ->nullable()
                    ->comment(
                        'AllConversionsをコンバージョントラッキングできる合計クリック数で割ったものです。'
                        . 'これは、広告のクリックがコンバージョンにつながった頻度です。 "x.xx％"として返されるパーセンテージ。'
                    );
                $table->double('allConv')
                    ->nullable()
                    ->comment(
                        'AdWordsが推進するコンバージョン数の最善の見積もり。ウェブサイト、クロスデバイス、電話通話のコンバージョンが含まれます。'
                        . 'このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。'
                    );
                $table->double('allConvValue')
                    ->nullable()
                    ->comment(
                        '推定されたものを含む、すべてのコンバージョンの合計値。このフィールドは、'
                        . '小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。'
                    );
                $table->boolean('allowFlexibleColorResponsive')
                    ->nullable()
                    ->comment('応答性の高いディスプレイ広告の色を厳密に使用する必要があるかどうか。');
                $table->double('avgCost')->nullable()->comment('インタラクションごとに支払う平均金額。この金額は、広告の合計費用を合計インタラクション数で割ったものです。');
                $table->double('avgCPC')->nullable()->comment('すべてのクリックの総コストを、受け取った総クリック数で割った値。');
                $table->double('avgCPM')->nullable()->comment('平均インプレッション単価（CPM）。');
                $table->double('avgPosition')->nullable()->comment('他の広告主様との相対的な広告の掲載順位');
                $table->bigInteger('baseAdGroupID')
                    ->nullable()
                    ->comment('試用広告グループの基本広告グループのID。通常の広告グループの場合、これはAdGroupIdと同じです。');
                $table->bigInteger('baseCampaignID')
                    ->nullable()
                    ->comment('試用キャンペーンの基本キャンペーンのID。通常のキャンペーンの場合、これはCampaignIdと同じです。');
                $table->string('businessName')->nullable()->comment('反応性の高いディスプレイ広告のビジネス名。');
                $table->string('callOnlyAdPhoneNumber')->nullable()->comment('通話専用広告の電話番号。');
                $table->string('callToActionTextResponsive')->nullable()->comment('反応性ディスプレイ広告の行動を促すフレーズ。');
                $table->bigInteger('campaignID')->nullable()->comment('キャンペーンのID。');
                $table->text('campaign')->nullable()->comment('キャンペーンの名前。');
                $table->string('campaignState', 50)->nullable()->comment('キャンペーンのステータス。');
                $table->bigInteger('clicks')->nullable()->comment('クリック数。');
                $table->string('clickType', 50)
                    ->nullable()
                    ->comment(
                        '[インプレッション数]フィールドには、そのクリックタイプで広告が配信された頻度が反映されます。 '
                        . '広告は複数のクリックタイプで表示できるため、インプレッション数は2倍になり、合計が正確でない可能性があります。'
                    );
                $table->string('approvalStatus', 50)->nullable()->comment('レビューステートとステータスを組み合わせた承認ステータス。');
                $table->double('convRate')i
                    ->nullable()
                    ->comment('コンバージョン数をコンバージョンにトラッキングできる合計クリック数で割ったものです。 "x.xx％"として返されるパーセンテージ。');
                $table->double('conversions')
                    ->nullable()
                    ->comment(
                        '最適化を選択したすべてのコンバージョンアクションのコンバージョン数。このフィールドは、'
                        . '小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。'
                    );
                $table->double('totalConvValue')
                    ->nullable()
                    ->comment(
                        'すべてのコンバージョンのコンバージョン値の合計。このフィールドは、'
                        . '小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。'
                    );
                $table->double('cost')->nullable()->comment('この期間のクリック単価（CPC）とインプレッション単価（CPM）の合計。');
                $table->double('costAllConv')->nullable()->comment('総費用をすべてのコンバージョンで割った値。');
                $table->double('costConv')->nullable()->comment('コンバージョントラッキングクリック数に起因する費用をコンバージョン数で割った値');
                $table->double('costConvCurrentModel')
                    ->nullable()
                    ->comment(
                        '現在選択しているアトリビューションモデルで、過去の「CostPerConversion」データがどのように表示されるかを示します。'
                        . 'このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。'
                    );
                $table->text('destinationURL')->nullable()->comment('広告のリンク先URL。');
                $table->text('appFinalURL')
                    ->nullable()
                    ->comment(
                        '広告の最終的なアプリURLのリスト。 リストのエントリは、a）「android-app：」（Androidアプリの場合）またはb）「os-app：」'
                        . '（iOSアプリの場合）のいずれかで始まります。 リスト要素はJSONリスト形式で返されます。'
                    );
                $table->text('mobileFinalURL')->nullable()->comment('広告の最終的なモバイルURLのリスト。 リスト要素はJSONリスト形式で返されます。');
                $table->text('finalURL')->nullable()->comment('広告の最終的なURLのリスト。 リスト要素はJSONリスト形式で返されます。');
                $table->text('trackingTemplate')->nullable()->comment('広告のトラッキングテンプレート。');
                $table->text('customParameter')
                    ->nullable()
                    ->comment('広告のカスタムパラメータのリスト。 CustomParameters要素はJSONマップ形式で返されます。');
                $table->bigInteger('keywordID')->nullable()->comment('基準ID。');
                $table->string('criteriaType', 50)->nullable()->comment('基準のタイプ。');
                $table->double('crossDeviceConv')
                    ->nullable()
                    ->comment(
                        '顧客が1つの端末でAdWords広告をクリックしてから別の端末やブラウザで変換した後のコンバージョンデバイス'
                        . '間のコンバージョンは既にAllConversions列に含まれています。このフィールドは、小数点の区切り文字として'
                        . 'ドット（"."）でフォーマットされます（例：1000000.00）。'
                    );
                $table->double('ctr')
                    ->nullable()
                    ->comment(
                        '広告がクリックされた回数（クリック数）を広告が表示された回数（インプレッション数）で割ったものです。 '
                        . '"x.xx％"として返されるパーセンテージ。'
                    );
                $table->double('conversionsCurrentModel')
                    ->nullable()
                    ->comment(
                        '現在選択しているアトリビューションモデルでの過去の「コンバージョン」データの表示方法を示します。このフィールドは、'
                        . '小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。'
                    );
                $table->double('convValueCurrentModel')
                    ->nullable()
                    ->comment(
                        '現在選択しているアトリビューションモデルで、過去の「ConversionValue」データがどのように表示されるかを示します。'
                        . 'このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。'
                    );
                $table->text('clientName')->nullable()->comment('カスタマーのわかりやすい名前。');
                $table->date('day')->nullable()->comment('日付はyyyy-MM-ddの形式になります。');
                $table->string('dayOfWeek', 50)->nullable()->comment('曜日の名前です（例：「月曜日」）。');
                $table->text('description')->nullable()->comment('拡張テキスト広告または敏感なディスプレイ広告の説明文。');
                $table->text('descriptionLine1')->nullable()->comment('広告の1行目の説明。');
                $table->text('descriptionLine2')->nullable()->comment('広告の2行目の説明。');
                $table->string('device', 50)->nullable()->comment('インプレッションが表示されたデバイスの種類。');
                $table->bigInteger('devicePreference')
                    ->nullable()
                    ->comment(
                        'デバイスプリファレンスのプラットフォームID。 Platformsリファレンスで、対応する名前やその他の情報を参照できます。 '
                        . 'URL：https://developers.google.com/adwords/api/docs/appendix/platforms'
                    );
                $table->text('displayURL')->nullable()->comment('広告のURLを表示します。');
                $table->bigInteger('landscapeLogoIDResponsive')->nullable()->comment('ランドスケープロゴ画像のID。');
                $table->bigInteger('logoIDResponsive')->nullable()->comment('ResponsiveDisplayAdで使用されるロゴイメージのID。');
                $table->bigInteger('imageIDResponsive')
                    ->nullable()
                    ->comment('ResponsiveDisplayAdで使用されるマーケティングイメージのID。');
                $table->bigInteger('squareImageIDResponsive')->nullable()->comment('正方形のマーケティングイメージのID。');
                $table->bigInteger('customerID')->nullable()->comment('顧客ID。');
                $table->string('adFormatPreferenceResponsive', 50)->nullable()->comment('レスポンシブディスプレイ広告のフォーマット設定。');
                $table->bigInteger('gmailForwards')->nullable()->comment('広告が誰かにメッセージとして転送された回数。');
                $table->bigInteger('gmailSaves')->nullable()->comment('Gmail広告をメッセージとして受信トレイに保存した回数。');
                $table->bigInteger('gmailClicksToWebsite')->nullable()->comment('Gmail広告の展開状態でのリンク先ページへのクリック数。');
                $table->text('ad')
                    ->nullable()
                    ->comment('extAdの広告見出し。 TemplateAdなどの他の広告タイプの場合、このフィールドには広告のキー属性の文字列表現が含まれます。');
                $table->text('headline1')->nullable()->comment('拡張テキスト広告の見出しの最初の部分。');
                $table->text('headline2')->nullable()->comment('拡張テキスト広告の見出しの2番目の部分です。');
                $table->bigInteger('adID')->nullable()->comment('この行の主オブジェクトのID。');
                $table->text('imageAdURL')
                    ->nullable()
                    ->comment('完全なURLを取得するには、この値の前に「https://tpc.googlesyndication.com/pageadimg/imgad?id=」と入力します。');
                $table->integer('imageHeight')->nullable()->comment('画像広告の高さ。他の広告タイプの場合は、値はありません。');
                $table->integer('imageWidth')->nullable()->comment('イメージ広告の幅他の広告タイプの場合は、値はありません。');
                $table->integer('imageMimeType')->nullable()->comment('画像のMIMEタイプ。イメージ広告にのみ掲載されます。');
                $table->text('imageAdName')->nullable()->comment('イメージ広告の名前。');
                $table->bigInteger('impressions')
                    ->nullable()
                    ->comment('Googleネットワークの検索結果ページやウェブサイトに広告が表示された回数をカウントします。');
                $table->double('interactionRate')
                    ->nullable()
                    ->comment(
                        '広告が表示された後にユーザーがどのくらい頻繁に広告を操作するか。これはインタラクションの数を広告の表示回数で割ったものです。 '
                        . '"x.xx％"として返されるパーセンテージ。'
                    );
                $table->bigInteger('interactions')
                    ->nullable()
                    ->comment(
                        '相互作用の数インタラクションとは、テキストやショッピング広告のクリック、動画広告の表示など、'
                        . '広告フォーマットに関連する主要なユーザーアクションです。'
                    );
                $table->text('interactionTypes')
                    ->nullable()
                    ->comment('Interactions、InteractionRate、およびAverageCost列に反映される相互作用のタイプ。');
                $table->boolean('isNegative')->nullable()->comment('この行の基準が否定（除外）基準であるかどうかを示します。');
                $table->text('labelIDs')->nullable()->comment('この行の主オブジェクトのラベルIDのリスト。 リスト要素はJSONリスト形式で返されます。');
                $table->text('labels')->nullable()->comment('この行のメインオブジェクトのラベル名のリスト。 リスト要素はJSONリスト形式で返されます。');
                $table->text('longHeadline')->nullable()->comment('レスポンシブディスプレイ広告の見出しの長い形式。');
                $table->string('mainColorResponsive', 50)->nullable()->comment('レスポンシブディスプレイ広告のメインカラーです。');
                $table->date('month')->nullable()->comment('月の最初の日。yyyy-MM-ddの形式です。');
                $table->string('monthOfYear', 50)->nullable()->comment('月の名前です（例：「12月」）。');
                $table->text('path1')->nullable()->comment('展開されたテキスト広告のURLが表示された広告に表示されるテキスト。');
                $table->text('path2')->nullable()->comment('「Path1」に加えて、拡張テキスト広告のURLが表示された広告に表示されるテキスト。');
                $table->text('policy')->nullable()->comment('広告のポリシー情報。');
                $table->text('pricePrefixResponsive')->nullable()->comment('プレフィックスは価格の前に表示されます。');
                $table->text('promotionTextResponsive')->nullable()->comment('反応性ディスプレイ広告のプロモーションテキスト。');
                $table->date('quarter')
                    ->nullable()
                    ->comment('四半期の最初の日は、yyyy-MM-ddの形式です。 四半期の暦年を使用します。たとえば、2014年第2四半期は2014-04-01に開始します。');
                $table->text('shortHeadline')->nullable()->comment('レスポンシブディスプレイ広告の見出しの短い形式。');
                $table->string('adState', 50)
                    ->nullable()
                    ->comment(
                        'この行のメインオブジェクトのステータス。たとえば、キャンペーンの掲載結果レポートでは、'
                        . 'これが各行のキャンペーンのステータスになります。広告グループの掲載結果レポートでは、'
                        . 'これは各行の広告グループのステータスになります。'
                    );
                $table->double('valueAllConv')
                    ->nullable()
                    ->comment('すべてのコンバージョンの平均値です。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->double('valueConv')
                    ->nullable()
                    ->comment('コンバージョン数の合計をコンバージョン数で割った値。このフィールドは、小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。');
                $table->double('valueConvCurrentModel')
                    ->nullable()
                    ->comment(
                        '現在選択しているアトリビューションモデルで、過去の「ValuePerConversion」データがどのように表示されるかを示します。このフィールドは、'
                        . '小数点の区切り文字としてドット（"."）でフォーマットされます（例：1000000.00）。'
                    );
                $table->date('week')->nullable()->comment('yyyy-MM-ddの形式の月曜日の日付。');
                $table->integer('year')->nullable()->comment('年はyyyyの形式です。');

                $table->index('exeDate', 'repo_adw_ad_report_cost1');
                $table->index('startDate', 'repo_adw_ad_report_cost2');
                $table->index('endDate', 'repo_adw_ad_report_cost3');
                $table->index('account_id', 'repo_adw_ad_report_cost4');
                $table->index('campaign_id', 'repo_adw_ad_report_cost5');
                $table->index('network', 'repo_adw_ad_report_cost6');
                $table->index('networkWithSearchPartners', 'repo_adw_ad_report_cost7');
                $table->index('clickType', 'repo_adw_ad_report_cost8');
                $table->index('keywordID', 'repo_adw_ad_report_cost9');
                $table->index('day', 'repo_adw_ad_report_cost10');
                $table->index('dayOfWeek', 'repo_adw_ad_report_cost11');
                $table->index('device', 'repo_adw_ad_report_cost12');
                $table->index('month', 'repo_adw_ad_report_cost13');
                $table->index('monthOfYear', 'repo_adw_ad_report_cost14');
                $table->index('quarter', 'repo_adw_ad_report_cost15');
                $table->index('week', 'repo_adw_ad_report_cost16');
                $table->index('year', 'repo_adw_ad_report_cost17');
                $table->index('accentColorResponsive', 'repo_adw_ad_report_cost18');
                $table->index('currency', 'repo_adw_ad_report_cost19');
                $table->index('timeZone', 'repo_adw_ad_report_cost20');
                $table->index('adGroupID', 'repo_adw_ad_report_cost21');
                $table->index('adGroupState', 'repo_adw_ad_report_cost22');
                $table->index('adType', 'repo_adw_ad_report_cost23');
                $table->index('allowFlexibleColorResponsive', 'repo_adw_ad_report_cost24');
                $table->index('baseAdGroupID', 'repo_adw_ad_report_cost25');
                $table->index('baseCampaignID', 'repo_adw_ad_report_cost26');
                $table->index('businessName', 'repo_adw_ad_report_cost27');
                $table->index('callOnlyAdPhoneNumber', 'repo_adw_ad_report_cost28');
                $table->index('callToActionTextResponsive', 'repo_adw_ad_report_cost29');
                $table->index('campaignID', 'repo_adw_ad_report_cost30');
                $table->index('campaignState', 'repo_adw_ad_report_cost31');
                $table->index('approvalStatus', 'repo_adw_ad_report_cost32');
                $table->index('criteriaType', 'repo_adw_ad_report_cost33');
                $table->index('devicePreference', 'repo_adw_ad_report_cost34');
                $table->index('landscapeLogoIDResponsive', 'repo_adw_ad_report_cost35');
                $table->index('logoIDResponsive', 'repo_adw_ad_report_cost36');
                $table->index('imageIDResponsive', 'repo_adw_ad_report_cost37');
                $table->index('squareImageIDResponsive', 'repo_adw_ad_report_cost38');
                $table->index('customerID', 'repo_adw_ad_report_cost39');
                $table->index('adFormatPreferenceResponsive', 'repo_adw_ad_report_cost40');
                $table->index('adID', 'repo_adw_ad_report_cost41');
                $table->index('imageHeight', 'repo_adw_ad_report_cost42');
                $table->index('imageWidth', 'repo_adw_ad_report_cost43');
                $table->index('imageMimeType', 'repo_adw_ad_report_cost44');
                $table->index('isNegative', 'repo_adw_ad_report_cost45');
                $table->index('mainColorResponsive', 'repo_adw_ad_report_cost46');
                $table->index('adState', 'repo_adw_ad_report_cost47');
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
        Schema::dropIfExists('repo_adw_ad_report_cost');
    }
}
