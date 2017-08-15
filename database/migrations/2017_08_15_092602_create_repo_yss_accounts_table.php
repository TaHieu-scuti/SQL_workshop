<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepoYssAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo_yss_accounts', function (Blueprint $table) {
            $table->increments('id', 11)->index();
            $table->bigInteger('accountid')->comment('アカウントID')->nullable()->unsigned();
            $table->string('account_id', 50)->comment('ADgainerシステムのアカウントID')->nullable();
            $table->string('accountName', 255)->comment('アカウント名')->nullable();
            $table->string('accountType', 20)->comment('料金の支払い方法 https://github.com/yahoojp-marketing/sponsored-search-api-documents/blob/master/docs/ja/api_reference/data/AccountType.md')->nullable();
            $table->string('accountStatus', 20)->comment('アカウントの契約状況 https://github.com/yahoojp-marketing/sponsored-search-api-documents/blob/master/docs/ja/api_reference/data/AccountStatus.md')->nullable();
            $table->string('deliveryStatus', 20)->comment('広告の配信状況 https://github.com/yahoojp-marketing/sponsored-search-api-documents/blob/master/docs/ja/api_reference/data/DeliveryStatus.md')->nullable();
            $table->timestamp('created_at')->comment('作成日時')->nullable();
            $table->timestamp('updated_at')->comment('更新日時')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('repo_yss_account');
    }
}


