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
        DB::statement(
            "CREATE TABLE `accounts` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `account_id` varchar(50) NOT NULL,
                `account_subsidiary` varchar(50) NOT NULL,
                `account_language` varchar(50) NOT NULL DEFAULT 'en-US',
                `account_currency` varchar(20) NOT NULL DEFAULT 'USD',
                `account_time_zone` varchar(150) NOT NULL DEFAULT 'America/Los_Angeles',
                `ftp_user` varchar(200) NOT NULL,
                `ftp_pass` varchar(200) NOT NULL,
                `ftp_folder` text NOT NULL,
                `sftp` int(11) NOT NULL DEFAULT '0',
                `show_number` int(5) NOT NULL DEFAULT '1',
                `api_key` varchar(50) NOT NULL,
                `api_limit` int(11) NOT NULL DEFAULT '500',
                `ppc_markup` float NOT NULL DEFAULT '55',
                `super_agent_id` varchar(40) NOT NULL,
                `agent_id` varchar(100) NOT NULL,
                `accountName` varchar(255) NOT NULL,
                `dept` varchar(10) NOT NULL,
                `username` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL,
                `account_owner` int(11) NOT NULL DEFAULT '1',
                `account_pin` int(11) NOT NULL,
                `account_code` int(11) NOT NULL,
                `account_view_ppc_all` int(11) NOT NULL DEFAULT '0',
                `account_view_keywords` int(11) NOT NULL DEFAULT '1',
                `track_email` int(11) NOT NULL DEFAULT '1',
                `email` varchar(255) NOT NULL,
                `tel` varchar(20) NOT NULL,
                `companyName` varchar(255) NOT NULL,
                `address` varchar(255) NOT NULL,
                `address2` varchar(255) NOT NULL,
                `city` varchar(100) NOT NULL,
                `state` varchar(100) NOT NULL,
                `zip` varchar(20) NOT NULL,
                `country` varchar(255) NOT NULL,
                `contact` varchar(255) NOT NULL,
                `active` int(11) NOT NULL DEFAULT '0',
                `access_only` int(11) NOT NULL DEFAULT '0',
                `api_active` int(11) NOT NULL DEFAULT '0',
                `level` int(11) NOT NULL,
                `status` varchar(20) NOT NULL DEFAULT 'TRIAL',
                `account_mgr` varchar(255) NOT NULL,
                `phone_data_level` int(11) NOT NULL DEFAULT '0',
                `demograph_data_level` int(11) NOT NULL DEFAULT '0',
                `super_id` varchar(50) NOT NULL,
                `marin_id` varchar(60) DEFAULT NULL,
                `billing_type` varchar(50) NOT NULL,
                `billing_date` int(11) NOT NULL,
                `billing_address_1` varchar(255) NOT NULL,
                `billing_address_2` varchar(255) NOT NULL,
                `billing_city` varchar(150) NOT NULL,
                `billing_state` varchar(100) NOT NULL,
                `billing_zip` varchar(50) NOT NULL,
                `billing_country` varchar(255) NOT NULL,
                `card_number` varchar(20) NOT NULL,
                `cvv` varchar(10) NOT NULL,
                `exp_m` int(10) NOT NULL,
                `exp_y` int(11) NOT NULL,
                `name_on_card` varchar(255) NOT NULL,
                `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `logo` varchar(255) NOT NULL DEFAULT 'ad-gainer-logo-v1.1-250x54px-white-bkgd-flat.png     ',
                `color` varchar(100) NOT NULL DEFAULT 'dodgerBlue',
                `wallpaper` text NOT NULL,
                `subdomain` varchar(255) NOT NULL,
                `slogan` text NOT NULL,
                `offline_img` varchar(50) NOT NULL DEFAULT 'OfflineButton.png',
                `online_img` varchar(50) NOT NULL DEFAULT 'OnlineButton.png',
                `last_login` datetime NOT NULL,
                `last_edited` varchar(255) NOT NULL,
                `adw_client_id` varchar(100) NOT NULL,
                `adw_refresh_token` text NOT NULL,
                `ds_access_token` text NOT NULL,
                `ds_refresh_token` text NOT NULL,
                PRIMARY KEY (`id`),
                KEY `account_id` (`account_id`)
            )"
        );
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
