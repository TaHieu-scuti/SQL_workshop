<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\App;

// @codingStandardsIgnoreLine
class CreatePhoneTimeUsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!App::environment('production')) {
            Schema::create(
                'phone_time_use',
                function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('unique_call_id');
                    $table->string('pre_call_id', 40);
                    $table->integer('ag_server')->default(0);
                    $table->string('phone_number');
                    $table->string('number_dialed', 20)->nullable()->default(null);
                    $table->integer('number_type')->default(0);
                    $table->string('caller_phone', 32)->nullable()->default(null);
                    $table->string('account_id');
                    $table->string('campaign_id');
                    $table->string('dblclick_name_calls', 100);
                    $table->string('dblclick_name_goals', 100);
                    $table->string('dblclick_agency_id', 100);
                    $table->string('dblclick_advertiser_id', 100);
                    $table->string('dblclick_conv_id_calls', 50);
                    $table->string('dblclick_conv_id_goals', 50);
                    $table->string('visitor_city_state');
                    $table->string('visitor_country');
                    $table->string('visitor_country_code');
                    $table->string('caller_name');
                    $table->string('caller_state');
                    $table->string('caller_country');
                    $table->string('caller_city', 100);
                    $table->string('caller_zip');
                    $table->string('keyword');
                    $table->string('ch_keyword');
                    $table->string('j_keyword');
                    $table->string('k_keyword');
                    $table->string('typed_keyword');
                    $table->string('matchtype', 30);
                    $table->string('network', 20);
                    $table->string('searchTerm');
                    $table->string('gclid', 100);
                    $table->string('banner_id', 20);
                    $table->string('landing_page_id', 20);
                    $table->string('msclkid', 100);
                    $table->string('mkwid', 20)->nullable()->default(null);
                    $table->string('pcrid', 40)->nullable()->default(null);
                    $table->string('pkw', 40)->nullable()->default(null);
                    $table->string('pmt', 40)->nullable()->default(null);
                    $table->string('pdv', 40)->nullable()->default(null);
                    $table->string('m_rev', 50)->nullable()->default(null);
                    $table->string('ip');
                    $table->text('whois');
                    $table->text('lat');
                    $table->text('lng');
                    $table->string('geoError');
                    $table->string('session_id', 100);
                    $table->string('visit_id', 50);
                    $table->string('device_id', 50);
                    $table->string('location');
                    $table->string('source');
                    $table->string('utm_content')->nullable()->default(null);
                    $table->mediumText('utm_campaign');
                    $table->string('mobile');
                    $table->string('platform');
                    $table->string('browser');
                    $table->string('traffic_type');
                    $table->string('entry_type', 20);
                    $table->integer('chat_session')->default(0);
                    $table->longText('email_data');
                    $table->longText('j_email_data');
                    $table->longText('ch_email_data');
                    $table->longText('k_email_data');
                    $table->integer('email_data_sent')->default(0);
                    $table->string('email_rating', 100);
                    $table->longText('email_comment');
                    $table->string('call_duration')->default(0);
                    $table->string('call_status');
                    $table->text('call_rate');
                    $table->text('call_comment');
                    $table->mediumText('call_data');
                    $table->string('call_recording', 200);
                    $table->string('calls_placed')->default(0);
                    $table->integer('calls_placed_after_correlation')->length(255)->default(0);
                    $table->string('time_of_call');
                    $table->string('time_assigned');
                    $table->longText('last_page');
                    $table->integer('tracking_active')->length(2);
                    $table->integer('visits');
                    $table->integer('clicks');
                    $table->integer('page_views');
                    $table->string('time_on_site');
                    $table->timestamp('time_stamp');
                    $table->integer('time_out_duration');
                    $table->integer('goal1_hit');
                    $table->dateTime('goal1_time');
                    $table->integer('goal2_hit');
                    $table->dateTime('goal2_time');
                    $table->integer('goal3_hit');
                    $table->dateTime('goal3_time');
                    $table->integer('goal4_hit');
                    $table->dateTime('goal4_time');
                    $table->integer('goal5_hit')->default(0);
                    $table->dateTime('goal5_time');
                    $table->integer('goal6_hit')->default(0);
                    $table->dateTime('goal6_time');
                    $table->integer('goal7_hit')->default(0);
                    $table->dateTime('goal7_time');
                    $table->integer('goal8_hit')->default(0);
                    $table->dateTime('goal8_time');
                    $table->integer('goal9_hit')->default(0);
                    $table->dateTime('goal9_time');
                    $table->integer('goal10_hit')->default(0);
                    $table->dateTime('goal10_time');
                    $table->string('custom1');
                    $table->string('custom2');
                    $table->string('custom3');
                    $table->string('custom4');
                    $table->string('custom5');
                    $table->string('custom6');
                    $table->string('custom7');
                    $table->string('custom8');
                    $table->string('custom9');
                    $table->string('custom10');
                    $table->string('display');
                    $table->string('from_acct', 40);
                    $table->string('from_camp', 40);
                    $table->mediumText('goal_pg_actions');
                    $table->integer('dblclick_conversion_sent')->default(0);
                    $table->integer('adw_conversion_sent')->default(0);
                    $table->string('update_key', 30);
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
            Schema::dropIfExists('phone_time_use');
        }
    }
}
