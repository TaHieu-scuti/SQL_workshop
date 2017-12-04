<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreLine
class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id', 50);
            $table->string('account_subsidiary', 50);
            $table->string('account_language', 50);
            $table->string('account_time_zone', 150)->default('America/Los_Angeles');
            $table->string('ftp_user', 200);
            $table->string('ftp_pass', 200);
            $table->text('ftp_folder');
            $table->integer('sftp')->default(0);
            $table->integer('show_number', 5)->default(1);
            $table->string('api_key', 50);
            $table->integer('api_limit')->default(500);
            $table->float('api_limit')->default(55);
            $table->string('super_agent_id', 40);
            $table->string('agent_id', 100);
            $table->string('accountName');
            $table->string('dept', 10);
            $table->string('username');
            $table->string('password');
            $table->integer('account_owner')->default(1);
            $table->integer('account_pin');
            $table->integer('account_code');
            $table->integer('account_view_ppc_all')->default(0);
            $table->integer('account_view_keywords')->default(1);
            $table->integer('track_email')->default(1);
            $table->string('email');
            $table->string('tel', 20);
            $table->string('companyName');
            $table->string('address');
            $table->string('address2');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('zip', 20);
            $table->string('country');
            $table->string('contact');
            $table->integer('active')->default(0);
            $table->integer('access_only')->default(0);
            $table->integer('api_active')->default(0);
            $table->integer('level');
            $table->string('status', 20);
            $table->string('account_mgr');
            $table->integer('phone_data_level')->default(0);
            $table->integer('demograph_data_level')->default(0);
            $table->string('super_id', 50);
            $table->string('marin_id', 60);
            $table->string('billing_type', 50);
            $table->integer('billing_date');
            $table->string('billing_address_1');
            $table->string('billing_address_2');
            $table->string('billing_city', 150);
            $table->string('billing_state', 100);
            $table->string('billing_zip', 50);
            $table->string('billing_country');
            $table->string('card_number', 20);
            $table->string('cvv', 10);
            $table->integer('exp_m', 10);
            $table->integer('exp_y');
            $table->string('name_on_card');
            $table->timestamp('date_created');
            $table->string('logo')->default('ad-gainer-logo-v1.1-250x54px-white-bkgd-flat.png');
            $table->string('color', 100)->default('dodgerBlue');
            $table->text('wallpaper');
            $table->string('subdomain');
            $table->text('slogan');
            $table->string('offline_img', 50)->default('OfflineButton.png');
            $table->string('online_img', 50)->default('OfflineButton.png');
            $table->dateTime('last_login');
            $table->string('last_edited');
            $table->string('adw_client_id', 100);
            $table->text('adw_refresh_token');
            $table->text('ds_access_token');
            $table->text('ds_refresh_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
