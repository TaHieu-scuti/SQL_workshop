<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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
        if (!App::environment('production')) {
            DB::statement(
                "CREATE TABLE `accounts` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `account_id` VARCHAR(50) NOT NULL,
                    `account_subsidiary` VARCHAR(50) NOT NULL,
                    `account_language` VARCHAR(50) NOT NULL DEFAULT 'en-US',
                    `account_currency` VARCHAR(20) NOT NULL DEFAULT 'USD',
                    `account_time_zone` VARCHAR(150) NOT NULL DEFAULT 'America/Los_Angeles',
                    `ftp_user` VARCHAR(200) NOT NULL,
                    `ftp_pass` VARCHAR(200) NOT NULL,
                    `ftp_folder` TEXT NOT NULL,
                    `sftp` INT(11) NOT NULL DEFAULT '0',
                    `show_number` INT(5) NOT NULL DEFAULT '1',
                    `api_key` VARCHAR(50) NOT NULL,
                    `api_limit` INT(11) NOT NULL DEFAULT '500',
                    `ppc_markup` FLOAT NOT NULL DEFAULT '55',
                    `super_agent_id` VARCHAR(40) NOT NULL,
                    `agent_id` VARCHAR(100) NOT NULL,
                    `accountName` VARCHAR(255) NOT NULL,
                    `dept` VARCHAR(10) NOT NULL,
                    `username` VARCHAR(255) NOT NULL,
                    `password` VARCHAR(255) NOT NULL,
                    `account_owner` INT(11) NOT NULL DEFAULT '1',
                    `account_pin` INT(11) NOT NULL,
                    `account_code` INT(11) NOT NULL,
                    `account_view_ppc_all` INT(11) NOT NULL DEFAULT '0',
                    `account_view_keywords` INT(11) NOT NULL DEFAULT '1',
                    `track_email` INT(11) NOT NULL DEFAULT '1',
                    `email` VARCHAR(255) NOT NULL,
                    `tel` VARCHAR(20) NOT NULL,
                    `companyName` VARCHAR(255) NOT NULL,
                    `address` VARCHAR(255) NOT NULL,
                    `address2` VARCHAR(255) NOT NULL,
                    `city` VARCHAR(100) NOT NULL,
                    `state` VARCHAR(100) NOT NULL,
                    `zip` VARCHAR(20) NOT NULL,
                    `country` VARCHAR(255) NOT NULL,
                    `contact` VARCHAR(255) NOT NULL,
                    `active` INT(11) NOT NULL DEFAULT '0',
                    `access_only` INT(11) NOT NULL DEFAULT '0',
                    `api_active` INT(11) NOT NULL DEFAULT '0',
                    `level` INT(11) NOT NULL,
                    `status` VARCHAR(20) NOT NULL DEFAULT 'TRIAL',
                    `account_mgr` VARCHAR(255) NOT NULL,
                    `phone_data_level` INT(11) NOT NULL DEFAULT '0',
                    `demograph_data_level` INT(11) NOT NULL DEFAULT '0',
                    `super_id` VARCHAR(50) NOT NULL,
                    `marin_id` VARCHAR(60) DEFAULT NULL,
                    `billing_type` VARCHAR(50) NOT NULL,
                    `billing_date` INT(11) NOT NULL,
                    `billing_address_1` VARCHAR(255) NOT NULL,
                    `billing_address_2` VARCHAR(255) NOT NULL,
                    `billing_city` VARCHAR(150) NOT NULL,
                    `billing_state` VARCHAR(100) NOT NULL,
                    `billing_zip` VARCHAR(50) NOT NULL,
                    `billing_country` VARCHAR(255) NOT NULL,
                    `card_number` VARCHAR(20) NOT NULL,
                    `cvv` VARCHAR(10) NOT NULL,
                    `exp_m` INT(10) NOT NULL,
                    `exp_y` INT(11) NOT NULL,
                    `name_on_card` VARCHAR(255) NOT NULL,
                    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    `logo` VARCHAR(255) NOT NULL DEFAULT 'ad-gainer-logo-v1.1-250x54px-white-bkgd-flat.png     ',
                    `color` VARCHAR(100) NOT NULL DEFAULT 'dodgerBlue',
                    `wallpaper` TEXT NOT NULL,
                    `subdomain` VARCHAR(255) NOT NULL,
                    `slogan` TEXT NOT NULL,
                    `offline_img` VARCHAR(50) NOT NULL DEFAULT 'OfflineButton.png',
                    `online_img` VARCHAR(50) NOT NULL DEFAULT 'OnlineButton.png',
                    `last_login` DATETIME NOT NULL,
                    `last_edited` VARCHAR(255) NOT NULL,
                    `adw_client_id` VARCHAR(100) NOT NULL,
                    `adw_refresh_token` TEXT NOT NULL,
                    `ds_access_token` TEXT NOT NULL,
                    `ds_refresh_token` TEXT NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `account_id` (`account_id`)
                )"
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
            Schema::dropIfExists('accounts');
        }
    }
}
