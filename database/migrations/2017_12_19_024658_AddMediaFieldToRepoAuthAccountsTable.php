<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class AddMediaFieldToRepoAuthAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'repo_authaccounts',
            function (Blueprint $table) {
                $table->tinyInteger('media')
                    ->default(0)
                    ->comment(
                        'どのメディアのアカウントなのか？\n0: adwords, 1: Yahoo Display Network, 2: Yahoo sopnsord search'
                    );
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
        Schema::table(
            'repo_authaccounts',
            function (Blueprint $table) {
                $table->dropColumn('media');
            }
        );
    }
}
