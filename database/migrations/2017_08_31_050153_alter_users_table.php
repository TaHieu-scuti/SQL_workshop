<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\App;

// @codingStandardsIgnoreLine
class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!App::environment('production')) {
            Schema::table(
                'users',
                function (Blueprint $table) {
                    // remove fields
                    $table->dropColumn(['created_at', 'updated_at', 'remember_token', 'name']);

                    // add fields
                    $table->string('username', 100);
                    $table->string('firstName', 255);
                    $table->string('lastName', 255);
                    $table->mediumInteger('account_owner');
                    $table->mediumInteger('write');
                    $table->string('language', 30);
                    $table->string('currency', 20);
                    $table->string('manager', 50);
                    $table->string('phone_company', 50);
                    $table->string('phone_number', 50);
                    $table->string('photo', 150);
                    $table->tinyInteger('chat_user');
                    $table->string('dept', 100);
                    $table->mediumInteger('type')->default(2);
                    $table->mediumInteger('active')->default(1);
                    $table->string('account_id', 50);
                    $table->longText('attach_accounts');
                    $table->mediumInteger('level')->default(2);
                    $table->timestamp('date_created')->nullable()->useCurrent();
                    $table->dateTime('last_login');
                    $table->dateTime('chat_time');
                    $table->dateTime('internal_chat_time');
                    $table->string('session_key', 40);
                    $table->string('color', 20);
                    $table->mediumText('wallpaper');
                    $table->string('adw_client_id', 100);
                    $table->mediumText('adw_refresh_token');
                    $table->mediumText('ds_access_token');
                    $table->mediumText('ds_refresh_token');

                    // alter fields
                    $table->string('password', 100)->change();
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!App::environment('production')) {
            Schema::table(
                'users',
                function (Blueprint $table) {
                    // add fields
                    $table->timestamps();
                    $table->string('name', 255);
                    $table->string('remember_token', 100)->nullable();

                    // remove fields
                    $table->dropColumn(
                        [
                            'username',
                            'firstName',
                            'lastName',
                            'account_owner',
                            'write',
                            'language',
                            'currency',
                            'manager',
                            'phone_company',
                            'phone_number',
                            'photo',
                            'chat_user',
                            'dept',
                            'type',
                            'active',
                            'account_id',
                            'attach_accounts',
                            'level',
                            'date_created',
                            'last_login',
                            'chat_time',
                            'internal_chat_time',
                            'session_key',
                            'color',
                            'wallpaper',
                            'adw_client_id',
                            'adw_refresh_token',
                            'ds_access_token',
                            'ds_refresh_token'
                        ]
                    );

                    // alter fields
                    $table->string('password', 255)->change();
                }
            );
        }
    }
}
