<?php

use Illuminate\Database\Seeder;

use App\Model\Campaign;

// @codingStandardsIgnoreLine
class CampaignsGenerator extends Seeder
{
    const NUMBER_OF_ACCOUNTS = 8;
    const NUMBER_OF_CAMPAIGNS_PER_ACCOUNT = 9;

    private function processCampaigns($account_id)
    {
        for ($campaign_id = 1; $campaign_id < self::NUMBER_OF_CAMPAIGNS_PER_ACCOUNT + 1; $campaign_id++) {
            $campaign = new Campaign;

            $campaign->campaign_id = ($account_id * 10) + $campaign_id;
            $campaign->account_id = $account_id;

            $campaign->yahoojpn_aid = '';
            $campaign->yahoojpn_cid = '';
            $campaign->adwords_campaign_id = '';
            $campaign->bing_campaign_id = '';
            $campaign->sp_campaign_id = '';
            $campaign->dblclick_name_calls = '';
            $campaign->dblclick_name_goals = '';
            $campaign->dblclick_agency_id = '';
            $campaign->dblclick_advertiser_id = '';
            $campaign->dblclick_action = '';
            $campaign->campaign_name = 'Campaign Name';
            $campaign->account_mgt = '';
            $campaign->campaign_mgr = '';
            $campaign->account_mgr = '';
            $campaign->correlation_time = '';
            $campaign->numbers_to_replace = '';
            $campaign->transfer_to_number = '';
            $campaign->tracking_type = '';
            $campaign->phone_format = '';
            $campaign->default_number = '';
            $campaign->greeting = '';
            $campaign->prompt = '';
            $campaign->call_notification = '';
            $campaign->goal_notify_email = '';
            $campaign->email_to_notify = '';
            $campaign->email_to_notify_user = '';
            $campaign->notified_date = '2017-11-27 01:01:11';
            $campaign->text_notify = 0;
            $campaign->cell_provider = '';
            $campaign->cell_to_text = '';
            $campaign->postback_page = '';
            $campaign->postback_fields = '';
            $campaign->tag_words = '';
            $campaign->blacklist_words = '';
            $campaign->blacklist_ips = '';
            $campaign->source_numbers = '';
            $campaign->email_tracking_email = '';
            $campaign->email_tracking_post_page = '';
            $campaign->country_tracking = '';
            $campaign->date_created = '';
            $campaign->goal1 = '';
            $campaign->goal2 = '';
            $campaign->goal3 = '';
            $campaign->goal4 = '';
            $campaign->goal5 = '';
            $campaign->goal6 = '';
            $campaign->goal7 = '';
            $campaign->goal8 = '';
            $campaign->goal9 = '';
            $campaign->goal10 = '';
            $campaign->goal1Memo = '';
            $campaign->goal2Memo = '';
            $campaign->goal3Memo = '';
            $campaign->goal4Memo = '';
            $campaign->goal5Memo = '';
            $campaign->goal6Memo = '';
            $campaign->goal7Memo = '';
            $campaign->goal8Memo = '';
            $campaign->goal9Memo = '';
            $campaign->goal10Memo = '';
            $campaign->kakao_id = '';
            $campaign->line_id = '';
            $campaign->viber_id = '';
            $campaign->whatsapp_id = '';
            $campaign->wechat_id = '';

            $adgroupidIndex = rand(1, 10);
            $creativeIndex = rand(1, 10);
            while ($creativeIndex === $adgroupidIndex) {
                $creativeIndex = rand(1, 10);
            }

            for ($camp_customIndex = 1; $camp_customIndex < 11; $camp_customIndex++) {
                if ($camp_customIndex === $adgroupidIndex) {
                    $campaign->{'camp_custom' . $camp_customIndex} = 'adgroupid';
                    continue;
                }

                if ($camp_customIndex === $creativeIndex) {
                    $campaign->{'camp_custom' . $camp_customIndex} = 'creative';
                    continue;
                }

                $campaign->{'camp_custom' . $camp_customIndex} = '';
            }

            $campaign->TWILIO_XML = '';
            $campaign->last_edited = '';
            $campaign->last_update = '2017-11-27 11:01:01';

            $campaign->saveOrFail();
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($account_id = 1; $account_id < self::NUMBER_OF_ACCOUNTS + 1; $account_id++) {
            $this->processCampaigns($account_id);
        }
    }
}
