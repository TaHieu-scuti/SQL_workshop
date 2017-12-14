<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoAuthaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo_authaccounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id', 50)
                ->comment('ADgainerシステムのアカウントID')
                ->index('repo_authaccounts_idx1');
            $table->string('license', 19)
                ->nullable()
                ->comment('Yahoo! JAPANが発行するライセンス番号です。\n「xxxx-xxxx-xxxx-xxxx」の形式で入力ください。');
            $table->string('apiAccountId', 19)
                ->nullable()
                ->comment('Yahoo! JAPANが発行するAPIシステムに認証するためのIDです。
                    \n「xxxx-xxxx-xxxx-xxxx」の形式で入力ください。');
            $table->string('apiAccountPassword', 255)
                ->nullable()
                ->comment('Yahoo APIアカウントIDにお客様自身がAPI管理ツールで設定したパスワードです。');
            $table->string('accountId', 20)
                ->nullable()
                ->comment('APIからアクセスするスポンサードサーチもしくはYahoo!ディスプレイアドネットワークのアカウントIDです。
                    \n外部の運用ツールが代理店または広告主など別のユーザーの代わりにアクセスするために必要です。');
            $table->string('onBehalfOfAccountId', 20)
                ->nullable()
                ->comment('アプリケーションからアカウントIDにアクセスするためのAPIアクセス専用のアカウントID（アプリケーションアカウントID）です。
                    \nAccountIdに対応したIDである必要があります。\n正しくセットされていない場合はエラーとなります。
                    \n外部の運用ツールが代理店または広告主など別のユーザーの代わりにアクセスするために必要です。');
            $table->string('onBehalfOfPassword', 255)
                ->nullable()
                ->comment('アプリケーションアカウントID（onBehalfOfAccountID）に設定されたパスワードです。
                    \n外部の運用ツールが代理店または広告主など別のユーザーの代わりにアクセスするために必要です。');
            $table->string('developerToken', 22)
                ->nullable()
                ->comment('AdWords API の開発者を個別に識別するための 22 文字の文字列です。
                    \n開発者トークン文字列の例: ABcdeFGH93KL-NOPQ_STUv。\nMCCアカウントに対して発行されます。');
            $table->text('userAgent')
                ->nullable()
                ->comment('リクエストの送信者と目的を定義するユーザー指定の文字列です。
                    \n問題を 診断する際にリクエストが見つかりやすいように、アプリケーション名とバージョンを
                     設定してください。例: example.com:ReportDownloader:V7.18。');
            $table->string('clientCustomerId', 12)
                ->nullable()
                ->comment('対象とする AdWords アカウントのお客様 ID です。
                    \n通常は、 123-456-7890 のような形式になります。
                    \nCustomerService と ReportDefinitionService 
                    を除くすべてのサービスに対するすべての呼び出しで必須です。');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repo_authaccounts');
    }
}
