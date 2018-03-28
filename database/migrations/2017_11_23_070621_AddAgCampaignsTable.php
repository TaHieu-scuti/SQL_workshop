<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

// @codingStandardsIgnoreLine
class AddAgCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() //NOSONAR
    {
        if (!App::environment('production')) {
            DB::statement(
                "CREATE TABLE `campaigns` (
                    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `campaign_id` VARCHAR(50) NOT NULL,
                    `account_id` VARCHAR(40) NOT NULL,
                    `yahoojpn_aid` VARCHAR(40) NOT NULL,
                    `yahoojpn_cid` VARCHAR(40) NOT NULL,
                    `adwords_campaign_id` VARCHAR(40) NOT NULL,
                    `bing_campaign_id` VARCHAR(50) NOT NULL,
                    `sp_campaign_id` VARCHAR(50) NOT NULL,
                    `dblclick_name_calls` VARCHAR(100) NOT NULL,
                    `dblclick_name_goals` VARCHAR(100) NOT NULL,
                    `dblclick_agency_id` VARCHAR(100) NOT NULL,
                    `dblclick_advertiser_id` VARCHAR(50) NOT NULL,
                    `dblclick_action` VARCHAR(100) NOT NULL,
                    `ppc_markup` FLOAT NOT NULL DEFAULT '55',
                    `chat_campaign` INT(1) NOT NULL DEFAULT '0',
                    `marin` INT(10) DEFAULT '0',
                    `int_merger` INT(1) NOT NULL DEFAULT '0',
                    `save_chat` INT(1) NOT NULL DEFAULT '1',
                    `campaign_name` VARCHAR(255) NOT NULL,
                    `account_mgt` VARCHAR(10) NOT NULL,
                    `campaign_cycle` INT(2) NOT NULL DEFAULT '1',
                    `campaign_budget` INT(20) NOT NULL DEFAULT '0',
                    `campaign_currency` VARCHAR(20) NOT NULL DEFAULT 'USD',
                    `campaign_mgr` VARCHAR(255) NOT NULL,
                    `account_mgr` VARCHAR(255) NOT NULL,
                    `campaign_per` FLOAT NOT NULL DEFAULT '0',
                    `correlation_time` VARCHAR(255) NOT NULL,
                    `timeZone` VARCHAR(30) NOT NULL DEFAULT 'America/Los_Angeles',
                    `show_number` INT(5) NOT NULL DEFAULT '1',
                    `numbers_to_replace` LONGTEXT NOT NULL,
                    `transfer_to_number` TEXT NOT NULL,
                    `vanity_to_replace` VARCHAR(30) DEFAULT NULL,
                    `tracking_type` VARCHAR(255) NOT NULL,
                    `phone_format` VARCHAR(255) NOT NULL,
                    `default_number` VARCHAR(255) NOT NULL,
                    `default_phone_format` INT(11) NOT NULL DEFAULT '1',
                    `conversion_flag` INT(11) NOT NULL DEFAULT '0',
                    `greet_voice` VARCHAR(20) NOT NULL DEFAULT 'alice',
                    `language` VARCHAR(10) NOT NULL DEFAULT 'en-US',
                    `greeting` TEXT NOT NULL,
                    `prompt` VARCHAR(255) NOT NULL,
                    `record_calls` INT(11) NOT NULL DEFAULT '1',
                    `call_notification` TEXT NOT NULL,
                    `multi_phone` INT(11) NOT NULL DEFAULT '1',
                    `email_notify` INT(11) NOT NULL DEFAULT '0',
                    `email_notify_user` INT(11) NOT NULL DEFAULT '0',
                    `default_notify` INT(11) NOT NULL DEFAULT '0',
                    `goal_notify` INT(11) NOT NULL DEFAULT '0',
                    `goal_notify_email` TEXT NOT NULL,
                    `email_to_notify` TEXT NOT NULL,
                    `email_to_notify_user` TEXT NOT NULL,
                    `notified` INT(11) NOT NULL DEFAULT '0',
                    `notified_date` DATETIME NOT NULL,
                    `text_notify` INT(11) NOT NULL,
                    `cell_provider` VARCHAR(255) NOT NULL,
                    `cell_to_text` VARCHAR(255) NOT NULL,
                    `postback_page` TEXT NOT NULL,
                    `post_back_type` VARCHAR(10) NOT NULL DEFAULT 'POST',
                    `postback_fields` TEXT NOT NULL,
                    `tag_words` LONGTEXT NOT NULL,
                    `blacklist_words` LONGTEXT NOT NULL,
                    `blacklist_ips` LONGTEXT NOT NULL,
                    `heatmap_code` INT(11) NOT NULL DEFAULT '0',
                    `fingerprint_code` INT(11) NOT NULL DEFAULT '0',
                    `tracking_code` INT(11) NOT NULL DEFAULT '1',
                    `email_code` INT(11) NOT NULL DEFAULT '0',
                    `multi_code` INT(11) NOT NULL DEFAULT '0',
                    `source_code` INT(11) NOT NULL DEFAULT '0',
                    `source_numbers` TEXT NOT NULL,
                    `source_multi` INT(11) NOT NULL DEFAULT '0',
                    `omit_google_ip` INT(11) NOT NULL DEFAULT '1',
                    `email_tracking` INT(11) NOT NULL DEFAULT '0',
                    `email_tracking_email` MEDIUMTEXT NOT NULL,
                    `email_tracking_post_page` MEDIUMTEXT NOT NULL,
                    `active` VARCHAR(255) NOT NULL DEFAULT '1',
                    `number_serve_active` INT(11) NOT NULL DEFAULT '1',
                    `number_serve_schedule` VARCHAR(20) NOT NULL DEFAULT 'manual',
                    `single_to_many_numbers` INT(11) NOT NULL DEFAULT '0',
                    `country_tracking` VARCHAR(255) NOT NULL,
                    `tracking_campaign_type` VARCHAR(20) NOT NULL DEFAULT 'online',
                    `allow_leads` INT(11) NOT NULL DEFAULT '1',
                    `location_tracking` INT(11) NOT NULL DEFAULT '0',
                    `location_device` INT(11) NOT NULL DEFAULT '2',
                    `device_tracking` INT(11) NOT NULL DEFAULT '2',
                    `date_created` VARCHAR(255) NOT NULL,
                    `goal1` TEXT NOT NULL,
                    `goal2` TEXT NOT NULL,
                    `goal3` TEXT NOT NULL,
                    `goal4` TEXT NOT NULL,
                    `goal5` TEXT NOT NULL,
                    `goal6` TEXT NOT NULL,
                    `goal7` TEXT NOT NULL,
                    `goal8` TEXT NOT NULL,
                    `goal9` TEXT NOT NULL,
                    `goal10` TEXT NOT NULL,
                    `goal1Memo` VARCHAR(255) NOT NULL,
                    `goal2Memo` VARCHAR(255) NOT NULL,
                    `goal3Memo` VARCHAR(255) NOT NULL,
                    `goal4Memo` VARCHAR(255) NOT NULL,
                    `goal5Memo` VARCHAR(200) NOT NULL,
                    `goal6Memo` VARCHAR(200) NOT NULL,
                    `goal7Memo` VARCHAR(200) NOT NULL,
                    `goal8Memo` VARCHAR(200) NOT NULL,
                    `goal9Memo` VARCHAR(200) NOT NULL,
                    `goal10Memo` VARCHAR(200) NOT NULL,
                    `goal1_inc` INT(11) NOT NULL DEFAULT '0',
                    `goal2_inc` INT(11) NOT NULL DEFAULT '0',
                    `goal3_inc` INT(11) NOT NULL DEFAULT '0',
                    `goal4_inc` INT(11) NOT NULL DEFAULT '0',
                    `goal5_inc` INT(11) NOT NULL DEFAULT '0',
                    `goal6_inc` INT(11) NOT NULL DEFAULT '0',
                    `goal7_inc` INT(11) NOT NULL DEFAULT '0',
                    `goal8_inc` INT(11) NOT NULL DEFAULT '0',
                    `goal9_inc` INT(11) NOT NULL DEFAULT '0',
                    `goal10_inc` INT(11) NOT NULL DEFAULT '0',
                    `kakao_id` VARCHAR(100) NOT NULL,
                    `line_id` VARCHAR(100) NOT NULL,
                    `viber_id` VARCHAR(100) NOT NULL,
                    `whatsapp_id` VARCHAR(100) NOT NULL,
                    `wechat_id` VARCHAR(100) NOT NULL,
                    `avgCalls` INT(11) NOT NULL DEFAULT '0',
                    `avgClicks` INT(11) NOT NULL DEFAULT '0',
                    `avgEmails` INT(11) NOT NULL DEFAULT '0',
                    `avgGoalPgs` INT(11) NOT NULL DEFAULT '0',
                    `avgConversions` INT(11) NOT NULL DEFAULT '0',
                    `goalsThresh` INT(11) NOT NULL DEFAULT '0',
                    `callsThresh` INT(11) NOT NULL DEFAULT '0',
                    `emailsThresh` INT(11) NOT NULL DEFAULT '0',
                    `clicksThresh` INT(11) NOT NULL DEFAULT '0',
                    `convsThresh` INT(11) NOT NULL DEFAULT '0',
                    `camp_custom1` VARCHAR(255) NOT NULL,
                    `camp_custom2` VARCHAR(255) NOT NULL,
                    `camp_custom3` VARCHAR(255) NOT NULL,
                    `camp_custom4` VARCHAR(255) NOT NULL,
                    `camp_custom5` VARCHAR(255) NOT NULL,
                    `camp_custom6` VARCHAR(255) NOT NULL,
                    `camp_custom7` VARCHAR(255) NOT NULL,
                    `camp_custom8` VARCHAR(255) NOT NULL,
                    `camp_custom9` VARCHAR(255) NOT NULL,
                    `camp_custom10` VARCHAR(255) NOT NULL,
                    `TWILIO_XML` MEDIUMTEXT NOT NULL,
                    `last_edited` VARCHAR(150) NOT NULL,
                    `last_update` DATETIME NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `campaign_id` (`campaign_id`),
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
            Schema::dropIfExists('campaigns');
        }
    }
}
