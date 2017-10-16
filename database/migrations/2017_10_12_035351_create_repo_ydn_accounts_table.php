<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateRepoYdnAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo_ydn_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('accountId')->nullable()->comment('アカウントID');
            $table->string('account_id', 50)->nullable()->comment('ADgainerシステムのアカウントID');
            $table->string('accountName')->nullable()->comment('アカウント名');
            $table->string('accountType', 50)->nullable()
                    ->comment(
                        'アカウントの種別\nhttps://github.com/yahoojp-marketing
                        /ydn-api-documents/blob/master/docs/ja/api_reference/data/AccountType.md'
                    );
            $table->string('accountStatus', 50)->nullable()
                    ->comment('アカウント登録状況\nhttps://github.com
                        /yahoojp-marketing/ydn-api-documents/blob/master/docs/ja
                        /api_reference/data/AccountStatus.md');
            $table->string('deliveryStatus', 45)->nullable()
                    ->comment('配信状況\nhttps://github.com/yahoojp-marketing
                        /ydn-api-documents/blob/master/docs/ja/api_reference/data/DeliveryStatus.md');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repo_ydn_accounts');
    }
}
