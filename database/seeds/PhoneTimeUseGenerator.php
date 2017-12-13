<?php

use Illuminate\Database\Seeder;

use App\Model\Campaign;
use App\Model\PhoneTimeUse;
use App\Model\RepoAdwAdReportCost;
use App\Model\RepoAdwKeywordReportCost;
use App\Model\RepoYdnReport;
use App\Model\RepoYssKeywordReportConv;

// @codingStandardsIgnoreLine
class PhoneTimeUseGenerator extends Seeder
{
    const START_DATE = '2017-01-01 00:00:00';
    const END_DATE = '2018-02-03 00:00:00';
    const PREFECTURE = [
        'Hokkaido',
        'Aomori',
        'Iwate',
        'Miyagi',
        'Akita',
        'Yamagata',
        'Fukushima',
        'Ibaraki',
        'Tochigi',
        'Gunma',
        'Saitama',
        'Chiba',
        'Tokyo',
        'Kanagawa',
        'Niigata',
        'Toyama',
        'Ishikawa',
        'Fukui',
        'Yamanashi',
        'Nagano',
        'Gifu',
        'Shizuoka',
        'Aichi',
        'Mie',
        'Shiga',
        'Kyoto',
        'Osaka',
        'Hyogo',
        'Nara',
        'Wakayama',
        'Tottori',
        'Shimane',
        'Okayama',
        'Hiroshima',
        'Yamaguchi',
        'Tokushima',
        'Kagawa',
        'Ehime',
        'Kochi',
        'Fukuoka',
        'Saga',
        'Nagasaki',
        'Kumamoto',
        'Oita',
        'Miyazaki',
        'Kagoshima',
        'Okinawa'
    ];

    private function newRecord(
        $account_id,
        $campaign_id,
        $engine,
        $campaignID,
        $adID = null,
        $adgroupID = null,
        $keyword = null,
        $keywordMatchType = null
    ) {
        $phoneTimeUse = new PhoneTimeUse;
        $phoneTimeUse->unique_call_id = uniqid();

        $phoneTimeUse->pre_call_id = '';
        $phoneTimeUse->phone_number = '';
        $phoneTimeUse->number_dialed = '';

        $phoneTimeUse->account_id = $account_id;
        $phoneTimeUse->campaign_id = $campaign_id;

        $phoneTimeUse->dblclick_name_calls = '';
        $phoneTimeUse->dblclick_name_goals = '';
        $phoneTimeUse->dblclick_agency_id = '';
        $phoneTimeUse->dblclick_advertiser_id = '';
        $phoneTimeUse->dblclick_conv_id_calls = '';
        $phoneTimeUse->dblclick_conv_id_goals = '';

        $phoneTimeUse->visitor_city_state = 'aabbcc - '
            . self::PREFECTURE[rand(0, count(self::PREFECTURE) - 1)]
            . ' (Japan)';

        $phoneTimeUse->visitor_country = '';
        $phoneTimeUse->visitor_country_code = '';
        $phoneTimeUse->caller_name = '';
        $phoneTimeUse->caller_state = '';
        $phoneTimeUse->caller_country = '';
        $phoneTimeUse->caller_city = '';
        $phoneTimeUse->caller_zip = '';

        $phoneTimeUse = $this->addKeywords($phoneTimeUse, $keyword);
        $phoneTimeUse = $this->addKeywordMatchType($phoneTimeUse, $keywordMatchType);

        $phoneTimeUse->network = '';
        $phoneTimeUse->searchTerm = '';
        $phoneTimeUse->gclid = '';
        $phoneTimeUse->banner_id = '';
        $phoneTimeUse->landing_page_id = '';
        $phoneTimeUse->msclkid = '';
        $phoneTimeUse->ip = '';
        $phoneTimeUse->whois = '';
        $phoneTimeUse->lat = '';
        $phoneTimeUse->lng = '';
        $phoneTimeUse->geoError = '';
        $phoneTimeUse->session_id = '';
        $phoneTimeUse->visit_id = '';
        $phoneTimeUse->device_id = '';
        $phoneTimeUse->location = '';

        $phoneTimeUse->source = $engine;

        $phoneTimeUse->utm_campaign = $campaignID;

        $phoneTimeUse->mobile = '';
        $phoneTimeUse->platform = '';
        $phoneTimeUse->browser = '';

        $phoneTimeUse->traffic_type = 'AD';

        $phoneTimeUse->entry_type = '';
        $phoneTimeUse->email_data = '';
        $phoneTimeUse->j_email_data = '';
        $phoneTimeUse->ch_email_data = '';
        $phoneTimeUse->k_email_data = '';
        $phoneTimeUse->email_rating = '';
        $phoneTimeUse->email_comment = '';
        $phoneTimeUse->call_status = '';
        $phoneTimeUse->call_rate = '';
        $phoneTimeUse->call_comment = '';
        $phoneTimeUse->call_data = '';
        $phoneTimeUse->call_recording = '';

        $minDate = new DateTime(self::START_DATE);
        $maxDate = new DateTime(self::END_DATE);

        $randUnixTime = mt_rand($minDate->format('U'), $maxDate->format('U'));
        $randDate = new DateTime;
        $randDate->setTimestamp($randUnixTime);

        $phoneTimeUse->time_of_call = $randDate;

        $phoneTimeUse->time_assigned = '';
        $phoneTimeUse->last_page = '';

        $phoneTimeUse->tracking_active = 0;
        $phoneTimeUse->visits = 0;
        $phoneTimeUse->clicks = 0;
        $phoneTimeUse->page_views = 0;

        $phoneTimeUse->time_on_site = '';

        $phoneTimeUse->time_out_duration = 0;
        $phoneTimeUse->goal1_hit = 0;
        $phoneTimeUse->goal2_hit = 0;
        $phoneTimeUse->goal3_hit = 0;
        $phoneTimeUse->goal4_hit = 0;
        $phoneTimeUse->goal5_hit = 0;
        $phoneTimeUse->goal6_hit = 0;
        $phoneTimeUse->goal7_hit = 0;
        $phoneTimeUse->goal8_hit = 0;
        $phoneTimeUse->goal9_hit = 0;
        $phoneTimeUse->goal10_hit = 0;

        $phoneTimeUse->goal1_time = '2019-01-01';
        $phoneTimeUse->goal2_time = '2019-01-01';
        $phoneTimeUse->goal3_time = '2019-01-01';
        $phoneTimeUse->goal4_time = '2019-01-01';
        $phoneTimeUse->goal5_time = '2019-01-01';
        $phoneTimeUse->goal6_time = '2019-01-01';
        $phoneTimeUse->goal7_time = '2019-01-01';
        $phoneTimeUse->goal8_time = '2019-01-01';
        $phoneTimeUse->goal9_time = '2019-01-01';
        $phoneTimeUse->goal10_time = '2019-01-01';

        $customFields = $this->getCustomFields($campaign_id, $account_id);

        if ($adgroupID !== null) {
            for ($i = 1; $i < 11; $i++) {
                if ($customFields->{'camp_custom' . $i} === 'adgroupid') {
                    $phoneTimeUse->{'custom' . $i} = $adgroupID;
                    continue;
                }

                $phoneTimeUse->{'custom' . $i} = '';
            }
        } elseif ($adID !== null) {
            for ($i = 1; $i < 11; $i++) {
                if ($customFields->{'camp_custom' . $i} === 'creative') {
                    $phoneTimeUse->{'custom' . $i} = $adID;
                    continue;
                }

                $phoneTimeUse->{'custom' . $i} = '';
            }
        }

        $phoneTimeUse->display = '';
        $phoneTimeUse->from_acct = '';
        $phoneTimeUse->from_camp = '';
        $phoneTimeUse->goal_pg_actions = '';
        $phoneTimeUse->update_key = '';

        $phoneTimeUse->saveOrFail();
    }

    private function getCustomFields($campaign_id, $account_id)
    {
        $campaign = new Campaign;
        return $campaign->select([
            'camp_custom1',
            'camp_custom2',
            'camp_custom3',
            'camp_custom4',
            'camp_custom5',
            'camp_custom6',
            'camp_custom7',
            'camp_custom8',
            'camp_custom9',
            'camp_custom10',
        ])
            ->where('campaign_id', '=', $campaign_id)
            ->where('account_id', '=', $account_id)
            ->firstOrFail();
    }

    private function addKeywords(PhoneTimeUse $phoneTimeUse, $keyword)
    {
        if ($keyword !== null) {
            $phoneTimeUse->keyword = $keyword;
            $phoneTimeUse->ch_keyword = $keyword;
            $phoneTimeUse->j_keyword = $keyword;
            $phoneTimeUse->k_keyword = $keyword;
            $phoneTimeUse->typed_keyword = $keyword;
        } else {
            $phoneTimeUse->keyword = '';
            $phoneTimeUse->ch_keyword = '';
            $phoneTimeUse->j_keyword = '';
            $phoneTimeUse->k_keyword = '';
            $phoneTimeUse->typed_keyword = '';
        }

        return $phoneTimeUse;
    }

    private function addKeywordMatchType(PhoneTimeUse $phoneTimeUse, $keywordMatchType)
    {
        if ($keywordMatchType !== null) {
            $phoneTimeUse->matchtype = $keywordMatchType;
        } else {
            $phoneTimeUse->matchtype = '';
        }

        return $phoneTimeUse;
    }

    private function processYssKeywordReports()
    {
        $yssKeywordReport = new RepoYssKeywordReportConv;
        $yssKeywordReports = $yssKeywordReport->select(
            [
                'account_id',
                'campaign_id',
                'accountid',
                'campaignID',
                'adgroupID',
                'keyword',
                'keywordMatchType'
            ]
        )
            ->distinct()
            ->get();

        foreach ($yssKeywordReports as $yssKeywordReport) {
            $this->newRecord(
                $yssKeywordReport->account_id,
                $yssKeywordReport->campaign_id,
                'yss',
                $yssKeywordReport->campaignID,
                null,
                $yssKeywordReport->adgroupID,
                $yssKeywordReport->keyword,
                $yssKeywordReport->keywordMatchType
            );
        }
    }

    private function processYdnAdReports()
    {
        $ydnReport = new RepoYdnReport;
        $ydnReports = $ydnReport->select(
            [
                'account_id',
                'campaign_id',
                'accountid',
                'campaignID',
                'adID'
            ]
        )
            ->distinct()
            ->get();

        foreach ($ydnReports as $ydnReport) {
            $this->newRecord(
                $ydnReport->account_id,
                $ydnReport->campaign_id,
                'ydn',
                $ydnReport->campaignID,
                $ydnReport->adID
            );
        }
    }

    private function processAdwKeywordReports()
    {
        $adwKeywordReport = new RepoAdwKeywordReportCost;
        $adwKeywordReports = $adwKeywordReport->select([
            'account_id',
            'campaign_id',
            'accountid',
            'campaignID',
            'adgroupID',
            'keyword',
            'matchType'
        ])
            ->distinct()
            ->get();

        foreach ($adwKeywordReports as $adwKeywordReport) {
            $this->newRecord(
                $adwKeywordReport->account_id,
                $adwKeywordReport->campaign_id,
                'adw',
                $adwKeywordReport->campaignID,
                null,
                $adwKeywordReport->adgroupID,
                $adwKeywordReport->keyword,
                $adwKeywordReport->matchType
            );
        }
    }

    private function processAdwAdReports()
    {
        $adwAdReport = new RepoAdwAdReportCost;
        $adwAdReports = $adwAdReport->select([
            'account_id',
            'campaign_id',
            'accountid',
            'campaignID',
            'adID'
        ])
            ->distinct()
            ->get();

        foreach ($adwAdReports as $adwAdReport) {
            $this->newRecord(
                $adwAdReport->account_id,
                $adwAdReport->campaign_id,
                'adw',
                $adwAdReport->campaignID,
                $adwAdReport->adID
            );
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->processYssKeywordreports();
        $this->processYdnAdReports();
        $this->processAdwKeywordReports();
        $this->processAdwAdReports();
    }
}
