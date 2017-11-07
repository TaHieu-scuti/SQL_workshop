<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Model\RepoYssAccount;
use App\Model\RepoYssAccountReportCost;
use App\Model\RepoYssAccountReportConv;
use App\Model\RepoYssCampaignReportCost;
use App\Model\RepoYssCampaignReportConv;
use App\Model\RepoYssAdgroupReportCost;
use App\Model\RepoYssAdgroupReportConv;
use App\Model\RepoYssAdReportCost;
use App\Model\RepoYssAdReportConv;
use App\Model\RepoYssKeywordReportCost;
use App\Model\RepoYssKeywordReportConv;
use App\Model\RepoYssPrefectureReportConv;
use App\Model\RepoYssPrefectureReportCost;

use NlpTools\Random\Distributions\Dirichlet;

// @codingStandardsIgnoreLine
class DemoSeeder extends Seeder
{
    const START_DATE = '2017-10-01';
    const END_DATE = '2017-11-01';
    const NUMBER_OF_DAYS = 31;
    const ACCOUNT_ID = 1;
    const ACCOUNTID = 1;
    const CAMPAIGN_ID = 1;

    const CAMPAIGNS = [
        1 => 'コールトラッキング',
        2 => '電話計測'
    ];

    const ADGROUPS_PER_CAMPAIGN = [
        1 => [
            1 => 'コールトラッキング（完全一致）',
            2 => 'コールトラッキング（部分一致）'
        ],
        2 => [
            3 => '電話計測（完全一致）',
            4 => '電話計測（部分一致）'
        ]
    ];

    const KEYWORDS_PER_ADGROUP = [
        1 => [
            1 => 'コールトラッキング',
            2 => 'calltracking',
            3 => 'コールトラッキングシステム',
            4 => 'コールトラッキングツール'
        ],
        2 => [
            5 => 'コールトラッキング',
            6 => 'calltracking',
            7 => 'コールトラッキングシステム',
            8 => 'コールトラッキングツール'
        ],
        3 => [
            9 => '電話計測',
            10 => '電話計測システム',
            11 => '電話計測ツール'
        ],
        4 => [
            12 => '電話計測',
            13 => '電話計測システム',
            14 => '電話計測ツール'
        ]
    ];

    const COST_KEYWORDS = [
        1 => 29452,
        2 => 19463,
        3 => 25081,
        4 => 38042,
        5 => 31064,
        6 => 23431,
        7 => 38005,
        8 => 26421,
        9 => 31212,
        10 => 48662,
        11 => 39652,
        12 => 33998,
        13 => 29651,
        14 => 35866
    ];

    const COST_KEYWORDS_PER_CAMPAIGN = [
        1 => [
            29452,
            19463,
            25081,
            38042,
            31064,
            23431,
            38005,
            26421
        ],
        2 => [
            31212,
            48662,
            39652,
            33998,
            29651,
            35866
        ]
    ];

    const COST_KEYWORDS_PER_ADGROUP = [
        1 => [
            29452,
            19463,
            25081,
            38042,
        ],
        2 => [
            31064,
            23431,
            38005,
            26421
        ],
        3 => [
            31212,
            48662,
            39652,
        ],
        4 => [
            33998,
            29651,
            35866
        ]
    ];

    const IMPRESSION_KEYWORDS = [
        1 => 3976,
        2 => 3501,
        3 => 4863,
        4 => 5222,
        5 => 4173,
        6 => 2508,
        7 => 3980,
        8 => 4338,
        9 => 4490,
        10 => 5017,
        11 => 3935,
        12 => 4208,
        13 => 4309,
        14 => 5321
    ];

    const IMPRESSION_KEYWORDS_PER_CAMPAIGN = [
        1 => [
            3976,
            3501,
            4863,
            5222,
            4173,
            2508,
            3980,
            4338
        ],
        2 => [
            4490,
            5017,
            3935,
            4208,
            4309,
            5321
        ]
    ];

    const IMPRESSION_KEYWORDS_PER_ADGROUP = [
        1 => [
            3976,
            3501,
            4863,
            5222,
        ],
        2 => [
            4173,
            2508,
            3980,
            4338
        ],
        3 => [
            4490,
            5017,
            3935,
        ],
        4 => [
            4208,
            4309,
            5321
        ]
    ];

    const CLICK_KEYWORDS = [
        1 => 28,
        2 => 46,
        3 => 98,
        4 => 69,
        5 => 59,
        6 => 46,
        7 => 108,
        8 => 52,
        9 => 73,
        10 => 57,
        11 => 39,
        12 => 85,
        13 => 69,
        14 => 136
    ];

    const CLICK_KEYWORDS_PER_CAMPAIGN = [
        1 => [
            28,
            46,
            98,
            69,
            59,
            46,
            108,
            52,
        ],
        2 => [
            73,
            57,
            39,
            85,
            69,
            136
        ]
    ];

    const CLICK_KEYWORDS_PER_ADGROUP = [
        1 => [
            28,
            46,
            98,
            69,
        ],
        2 => [
            59,
            46,
            108,
            52,
        ],
        3 => [
            73,
            57,
            39,
        ],
        4 => [
            85,
            69,
            136
        ]
    ];

    const PREFECTURES = [
        '岐阜県',
        '鹿児島県',
        '京都府',
        '東京都',
        '山口県',
        '山形県',
        '大阪府',
        '北海道',
        '福岡県',
        '宮城県'
    ];

    const MIN_DAILY_SPENDING_LIMIT = 1;
    const MAX_DAILY_SPENDING_LIMIT = 1004;
    const MIN_AVERAGE_POSITION = 1000000;
    const MAX_AVERAGE_POSITION = 89489437437880;
    const MIN_IMPRESSION_SHARE = 1000000;
    const MAX_IMPRESSION_SHARE = 89489437437880;
    const MIN_EXACT_MATCH_IMPRESSION_SHARE = 1000000;
    const MAX_EXACT_MATCH_IMPRESSION_SHARE = 89489437437880;
    const MIN_BUDGET_LOST_IMPRESSION_SHARE = 1000000;
    const MAX_BUDGET_LOST_IMPRESSION_SHARE = 89489437437880;
    const MIN_QUALITY_LOST_IMPRESSION_SHARE = 1000000;
    const MAX_QUALITY_LOST_IMPRESSION_SHARE = 89489437437880;
    const TRACKING_URL = 'http://we.track.people/';
    const MIN_CONVERSIONS = 1000000;
    const MAX_CONVERSIONS = 89489437437880;
    const MIN_CONV_RATE = 1000000;
    const MAX_CONV_RATE = 89489437437880;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 89489437437880;
    const MIN_COST_PER_CONV = 1000000;
    const MAX_COST_PER_CONV = 89489437437880;
    const MIN_VALUE_PER_CONV = 1000000;
    const MAX_VALUE_PER_CONV = 89489437437880;
    const MIN_ALL_CONV = 1000000;
    const MAX_ALL_CONV = 89489437437880;
    const MIN_ALL_CONV_RATE = 1000000;
    const MAX_ALL_CONV_RATE = 89489437437880;
    const MIN_ALL_CONV_VALUE = 1000000;
    const MAX_ALL_CONV_VALUE = 89489437437880;
    const MIN_COST_PER_ALL_CONV = 1000000;
    const MAX_COST_PER_ALL_CONV = 89489437437880;
    const MIN_VALUE_PER_ALL_CONV = 1000000;
    const MAX_VALUE_PER_ALL_CONV = 89489437437880;
    const NETWORKS = ['network1', 'network2', 'network3'];
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];
    const CUSTOM_PARAMETERS = 'Custom Parameters';
    const MIN_MOBILE_BID_ADJ = 1000000;
    const MAX_MOBILE_BID_ADJ = 89489437437880;
    const MIN_DESKTOP_BID_ADJ = 1000000;
    const MAX_DESKTOP_BID_ADJ = 89489437437880;
    const MIN_TABLET_BID_ADJ = 1000000;
    const MAX_TABLET_BID_ADJ = 89489437437880;
    const CAMPAIGN_TYPE = [
        'Campaign Type 1', 'Campaign Type 2',
        'Campaign Type 3', 'Campaign Type 4'
    ];
    const CLICK_TYPE = [
        'Click Type 1', 'Click Type 2',
        'Click Type 3', 'Click Type 4'
    ];
    const OBJECTIVE_OF_CONVERSION_TRACKING = 'Objective of conversion tracking';
    const CONVERSION_NAME = [
        'Conversion Name 1', 'Conversion Name 2',
        'Conversion Name 3', 'Conversion Name 4'
    ];
    const MIN_ADGROUP_BID = 1;
    const MAX_ADGROUP_BID = 1004;
    const DISPLAY_URL = 'http://we.track.displayURL/';
    const DESTINATION_URL = 'http://we.track.destinationURL/';
    const AD_TYPE = [
        'Ad Report Type 1', 'Ad Report Type 2',
        'Ad Report Type 3', 'Ad Report Type 4'
    ];
    const LOADING_PAGE_URL = 'http://we.track.landingPageURL/';
    const LOADING_PAGE_URL_SMART_PHONE = 'http://we.track.landingPageURLSmartphone/';
    const CUSTOM_URL = 'Custom URL ';
    const KEYWORD_DISTRIBUTION_SETTINGS = 'Keyword distribution settings';
    const KEYWORD_EDITORIAL_STATUS = 'Keyword editorial status';
    const MIN_BID = 1;
    const MAX_BID = 1004;
    const NEGATIVE_KEY_WORDS = 'Negative key words';
    const MIN_QUALITY_INDEX = 1;
    const MAX_QUALITY_INDEX = 10;
    const MIN_FIRST_PAGE_BID_ESTIMATE = 1;
    const MAX_FIRST_PAGE_BID_ESTIMATE = 20;
    const KEYWORD_MATCH_TYPE = 'Keyword match type';
    const MIN_TOP_OF_PAGE_BID_ESTIMATE = 1;
    const MAX_TOP_OF_PAGE_BID_ESTIMATE = 100;
    const LANDING_PAGE_URL = 'http://lading.page/';
    const LANDING_PAGE_URL_SMART_PHONE = 'http://lading.page.smartphone/';
    const CITY = [
        'HO CHI MINH', 'DA NANG',
        'NHA TRANG', 'KYOTO'
    ];
    const CITY_WAR_DISTRICT = [
        'NGUYEN PHONG SAC STREET', 'CAY GIAY STREET',
        'NGUYEN TRAI STREET'
    ];
    const AVERAGE_POSITION = 1.2;

    /*const WEBCV_KEYWORDS = [
        2,
        0,
        1,
        3,
        0,
        2,
        3,
        0,
        1,
        1,
        3,
        0,
        1,
        2
    ];

    const CALLTRACKING_KEYWORDS = [
        2,
        1,
        6,
        2,
        3,
        0,
        2,
        4,
        2,
        3,
        7,
        2,
        1,
        3
    ];*/

    private function seedUsers()
    {
        $user = new User;
        $user->email = "info@example.com";
        $user->password = 'admin';
        $user->username = 'admin';
        $user->firstName = '';
        $user->lastName = 'アドゲイナー';
        $user->account_owner = 1;
        $user->write = 1;
        $user->language = 'en-US';
        $user->currency = 'USD';
        $user->manager = '';
        $user->phone_company = '+84123456789';
        $user->phone_number = '+84123456789';
        $user->photo = 'photo.jpg';
        $user->chat_user = 0;
        $user->dept = 'Sales';
        $user->type = 1;
        $user->active = 1;
        $user->account_id = self::ACCOUNT_ID;
        $user->attach_accounts = '';
        $user->level = 7;
        $user->last_login = '2017-02-03 01:00:00';
        $user->chat_time = '2017-02-03 01:00:00';
        $user->internal_chat_time = '2017-02-01 02:00:00';
        $user->session_key = '';
        $user->color = 'FFFFFF';
        $user->wallpaper = '';
        $user->adw_client_id = '925-573-7287';
        $user->adw_refresh_token = '{"client_id":"655668221369-ltnvmrqaqbcinvki'
            . '85jmvadoujjbgl6c.apps.googleusercontent.com","client_secret":"mS'
            . 'yUQAwTQPFg-Z5AqxPrRhPr","refresh_token":"1\/J8InjEfhud149RoribB9'
            . 'pd48U0Q2eY_hovAcxLqaxFE","access_token":"ya29.FQIqV5o5jc3LOpknmM'
            . 'o6b5IWEke_VjNvjzOBycwnA-hQDYAv2meYo1_E2oBCBm8wkE8p","token_type"'
            . ':"Bearer","expires_in":3600,"timestamp":1445572938}';
        $user->ds_access_token = '';
        $user->ds_refresh_token = '{"access_token":"ya29.0QHt2LddwnwDoQM2Bc6Be7'
            . 'TWi-4W_CpYM74gz5FqEVPAcZPh98bFqJNmOmD0Oy6VkRcM","token_type":"Be'
            . 'arer","expires_in":3600,"refresh_token":"1\/lMEFJORxmrbVL7CeCvRd'
            . 'vJ67FYnzjbTRvt7JCh0VI54","created":1439726389}';

        $user->saveOrFail();
    }

    private function seedYssAccounts()
    {
        $account = new RepoYssAccount;
        $account->accountid = self::ACCOUNTID;
        $account->account_id = self::ACCOUNT_ID;
        $account->accountName = 'Yahooプロモーション';
        $account->accountType = str_random(10);
        $account->accountStatus = 'enabled';
        $account->deliveryStatus = 'enabled';
        $account->created_at = date('Y-m-d H:i:s');
        $account->updated_at = date('Y-m-d H:i:s');

        $account->saveOrFail();
    }

    private function getStartDateTime()
    {
        return new DateTime(self::START_DATE);
    }

    private function getEndDateTime()
    {
        return new DateTime(self::END_DATE);
    }

    private function getInterval()
    {
        return new DateInterval('P1D');
    }

    private function getDatePeriod()
    {
        return new DatePeriod(
            $this->getStartDateTime(),
            $this->getInterval(),
            $this->getEndDateTime()
        );
    }

    private function getRandomValues($sum, $numValues)
    {
        $dirichlet = new Dirichlet($sum, $numValues);
        $values = $dirichlet->sample();

        foreach ($values as &$value) {
            $value *= $sum;
        }

        return $values;
    }

    private function sumTwoDimensionalArray($array)
    {
        $sum = 0;
        foreach ($array as $i => $campaign) {
            $sum += array_sum($campaign);
        }

        return $sum;
    }

    private function getSumsAccountCost()
    {
        return $this->sumTwoDimensionalArray(self::COST_KEYWORDS_PER_CAMPAIGN);
    }

    private function getSumAccountImpression()
    {
        return $this->sumTwoDimensionalArray(self::IMPRESSION_KEYWORDS_PER_CAMPAIGN);
    }

    private function getSumAccountClick()
    {
        return $this->sumTwoDimensionalArray(self::CLICK_KEYWORDS_PER_CAMPAIGN);
    }

    private function sumSubArrays($array)
    {
        $sums = [];
        foreach ($array as $i => $subArray) {
            $sums[$i] = array_sum($subArray);
        }

        return $sums;
    }

    private function getSumsCampaignCost()
    {
        return $this->sumSubArrays(self::COST_KEYWORDS_PER_CAMPAIGN);
    }

    private function getSumsCampaignImpression()
    {
        return $this->sumSubArrays(self::IMPRESSION_KEYWORDS_PER_CAMPAIGN);
    }

    private function getSumsCampaignClick()
    {
        return $this->sumSubArrays(self::CLICK_KEYWORDS_PER_CAMPAIGN);
    }

    private function getRandomValuesForSums($sums)
    {
        $valueArrays = [];
        foreach ($sums as $id => $sum) {
            $valueArrays[$id] = $this->getRandomValues(
                $sum,
                self::NUMBER_OF_DAYS
            );
        }

        return $valueArrays;
    }

    private function getSumsAdgroupCost()
    {
        return $this->sumSubArrays(self::COST_KEYWORDS_PER_ADGROUP);
    }

    private function getSumsAdgroupImpression()
    {
        return $this->sumSubArrays(self::IMPRESSION_KEYWORDS_PER_ADGROUP);
    }

    private function getSumsAdgroupClick()
    {
        return $this->sumSubArrays(self::CLICK_KEYWORDS_PER_ADGROUP);
    }

    private function seedYssAccountReports()
    {
        $dateRange = $this->getDatePeriod();

        $sumCost = $this->getSumsAccountCost();
        $costValues = $this->getRandomValues($sumCost, self::NUMBER_OF_DAYS);

        $sumImpressions = $this->getSumAccountImpression();
        $impressionValues = $this->getRandomValues($sumImpressions, self::NUMBER_OF_DAYS);

        $sumClicks = $this->getSumAccountClick();
        $clickValues = $this->getRandomValues($sumClicks, self::NUMBER_OF_DAYS);

        foreach ($dateRange as $i => $day) {
            $costReport = new RepoYssAccountReportCost;
            $convReport = new RepoYssAccountReportConv;

            $costReport->account_id = self::ACCOUNT_ID;
            $convReport->account_id = self::ACCOUNT_ID;

            $costReport->accountid = self::ACCOUNTID;
            $convReport->accountid = self::ACCOUNTID;

            $costReport->campaign_id = self::CAMPAIGN_ID;
            $convReport->campaign_id = $costReport->campaign_id;

            $costReport->cost = $costValues[$i];

            $costReport->impressions = $impressionValues[$i];

            $costReport->clicks = $clickValues[$i];

            $costReport->ctr = ($costReport->clicks / $costReport->impressions) * 100;

            $costReport->averageCpc = $costReport->cost / $costReport->clicks;

            $costReport->averagePosition = self::AVERAGE_POSITION;

            $costReport->impressionShare = mt_rand(
                    self::MIN_IMPRESSION_SHARE,
                    self::MAX_IMPRESSION_SHARE
                ) / mt_getrandmax();

            $costReport->exactMatchImpressionShare = mt_rand(
                    self::MIN_EXACT_MATCH_IMPRESSION_SHARE,
                    self::MAX_EXACT_MATCH_IMPRESSION_SHARE
                ) / mt_getrandmax();

            $costReport->budgetLostImpressionShare = mt_rand(
                    self::MIN_BUDGET_LOST_IMPRESSION_SHARE,
                    self::MAX_BUDGET_LOST_IMPRESSION_SHARE
                ) / mt_getrandmax();

            $costReport->qualityLostImpressionShare = mt_rand(
                    self::MIN_QUALITY_LOST_IMPRESSION_SHARE,
                    self::MAX_QUALITY_LOST_IMPRESSION_SHARE
                ) / mt_getrandmax();

            $costReport->trackingURL = self::TRACKING_URL;
            $convReport->trackingURL = $costReport->trackingURL;

            $costReport->conversions = mt_rand(
                    self::MIN_CONVERSIONS,
                    self::MAX_CONVERSIONS
                ) / mt_getrandmax();
            $convReport->conversions = $costReport->conversions;

            $costReport->convRate = mt_rand(
                    self::MIN_CONV_RATE,
                    self::MAX_CONV_RATE
                ) / mt_getrandmax();

            $costReport->convValue = mt_rand(
                    self::MIN_CONV_VALUE,
                    self::MAX_CONV_VALUE
                ) / mt_getrandmax();
            $convReport->convValue = $costReport->convValue;

            $costReport->costPerConv = mt_rand(
                    self::MIN_COST_PER_CONV,
                    self::MAX_COST_PER_CONV
                ) / mt_getrandmax();

            $costReport->valuePerConv = mt_rand(
                    self::MIN_VALUE_PER_CONV,
                    self::MAX_VALUE_PER_CONV
                ) / mt_getrandmax();
            $convReport->valuePerConv = $costReport->valuePerConv;

            $convReport->allConv = mt_rand(
                    self::MIN_ALL_CONV,
                    self::MAX_ALL_CONV
                ) / mt_getrandmax();

            $convReport->allConvValue = mt_rand(
                    self::MIN_ALL_CONV_VALUE,
                    self::MAX_ALL_CONV_VALUE
                ) / mt_getrandmax();

            $convReport->valuePerAllConv = mt_rand(
                    self::MIN_VALUE_PER_ALL_CONV,
                    self::MAX_VALUE_PER_ALL_CONV
                ) / mt_getrandmax();

            $convReport->clickType = 'random click';
            $convReport->objectiveOfConversionTracking = 'objective';
            $convReport->conversionName = 'name of this conversion';

            $costReport->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
            $convReport->network = $costReport->network;

            $costReport->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
            $convReport->device = $costReport->device;

            $costReport->day = $day;
            $convReport->day = $costReport->day;

            $costReport->dayOfWeek = $day->format('l');
            $convReport->dayOfWeek = $costReport->dayOfWeek;

            $costReport->quarter = (int)ceil((int)$day->format('n') / 3);
            $convReport->quarter = $costReport->quarter;

            $costReport->month = $day->format('F');
            $convReport->month = $costReport->month;

            $costReport->week = $day->format('W');
            $convReport->week = $costReport->week;

            $costReport->exeDate = $day->format('Y-m-d');
            $convReport->exeDate = $costReport->exeDate;

            $costReport->startDate = $day->format('Y-m-d');
            $convReport->startDate = $costReport->startDate;

            $costReport->endDate = $day->format('Y-m-d');
            $convReport->endDate = $costReport->endDate;

            $costReport->saveOrFail();
            $convReport->saveOrFail();
        }
    }

    private function seedYssCampaignReports()
    {
        $dateRange = $this->getDatePeriod();

        $sumsCost = $this->getSumsCampaignCost();
        $costValues = $this->getRandomValuesForSums($sumsCost);

        $sumsImpression = $this->getSumsCampaignImpression();
        $impressionValues = $this->getRandomValuesForSums($sumsImpression);

        $sumsClick = $this->getSumsCampaignClick();
        $clickValues = $this->getRandomValuesForSums($sumsClick);

        foreach ($dateRange as $i => $day) {
            foreach (self::CAMPAIGNS as $campaignID => $campaignName) {
                $campaignReportCost = new RepoYssCampaignReportCost;
                $campaignReportConv = new RepoYssCampaignReportConv;
                $campaignReportCost->exeDate = $day->format('Y-m-d');
                $campaignReportConv->exeDate = $day->format('Y-m-d');
                $campaignReportCost->startDate = $day->format('Y-m-d');
                $campaignReportConv->startDate = $day->format('Y-m-d');
                $campaignReportCost->endDate = $day->format('Y-m-d');
                $campaignReportConv->endDate = $day->format('Y-m-d');
                $campaignReportCost->account_id = self::ACCOUNT_ID;
                $campaignReportConv->account_id = self::ACCOUNT_ID;
                $campaignReportCost->campaign_id = self::CAMPAIGN_ID;
                $campaignReportConv->campaign_id = self::CAMPAIGN_ID;
                $campaignReportCost->campaignID = $campaignID;
                $campaignReportConv->campaignID = $campaignID;;
                $campaignReportCost->campaignName = $campaignName;
                $campaignReportConv->campaignName = $campaignName;
                $campaignReportCost->campaignDistributionSettings = 'Distribution Settings ' . self::CAMPAIGN_ID;
                $campaignReportConv->campaignDistributionSettings = 'Distribution Settings ' . self::CAMPAIGN_ID;
                $campaignReportCost->campaignDistributionStatus = 'Distribution Status' . self::CAMPAIGN_ID;
                $campaignReportConv->campaignDistributionStatus = 'Distribution Status' . self::CAMPAIGN_ID;
                $campaignReportCost->dailySpendingLimit = mt_rand(
                    self::MIN_DAILY_SPENDING_LIMIT,
                    self::MAX_DAILY_SPENDING_LIMIT
                );
                $campaignReportConv->dailySpendingLimit = $campaignReportCost->dailySpendingLimit;
                $campaignReportCost->campaignStartDate = $day->format('Y-m-d');
                $campaignReportCost->campaignEndDate = $day->format('Y-m-d');
                $campaignReportConv->campaignStartDate = $day->format('Y-m-d');
                $campaignReportConv->campaignEndDate = $day->format('Y-m-d');

                $campaignReportCost->cost = $costValues[$campaignID][$i];
                $campaignReportCost->impressions = $impressionValues[$campaignID][$i];
                $campaignReportCost->clicks = $clickValues[$campaignID][$i];

                $campaignReportCost->ctr = $campaignReportCost->clicks / $campaignReportCost->impressions * 100;
                $campaignReportCost->averageCpc = $campaignReportCost->cost / $campaignReportCost->clicks;
                $campaignReportCost->averagePosition = self::AVERAGE_POSITION;
                $campaignReportCost->impressionShare = mt_rand(
                        self::MIN_IMPRESSION_SHARE,
                        self::MAX_IMPRESSION_SHARE
                    ) / mt_getrandmax();
                $campaignReportCost->exactMatchImpressionShare = mt_rand(
                        self::MIN_EXACT_MATCH_IMPRESSION_SHARE,
                        self::MAX_EXACT_MATCH_IMPRESSION_SHARE
                    ) / mt_getrandmax();
                $campaignReportCost->budgetLostImpressionShare = mt_rand(
                        self::MIN_BUDGET_LOST_IMPRESSION_SHARE,
                        self::MAX_BUDGET_LOST_IMPRESSION_SHARE
                    ) / mt_getrandmax();
                $campaignReportCost->qualityLostImpressionShare = mt_rand(
                        self::MIN_QUALITY_LOST_IMPRESSION_SHARE,
                        self::MAX_QUALITY_LOST_IMPRESSION_SHARE
                    ) / mt_getrandmax();
                $campaignReportCost->trackingURL = self::TRACKING_URL;
                $campaignReportConv->trackingURL = self::TRACKING_URL;
                $campaignReportCost->customParameters = self::CUSTOM_PARAMETERS . ' ' . self::CAMPAIGN_ID;
                $campaignReportConv->customParameters = self::CUSTOM_PARAMETERS . ' ' . self::CAMPAIGN_ID;
                $campaignReportCost->campaignTrackingID = self::CAMPAIGN_ID;
                $campaignReportConv->campaignTrackingID = self::CAMPAIGN_ID;
                $campaignReportCost->conversions = mt_rand(
                        self::MIN_CONVERSIONS,
                        self::MAX_CONVERSIONS
                    ) / mt_getrandmax();
                $campaignReportConv->conversions = $campaignReportCost->conversions;
                $campaignReportCost->convRate = mt_rand(
                        self::MIN_CONV_RATE,
                        self::MAX_CONV_RATE
                    ) / mt_getrandmax();
                $campaignReportCost->convValue = mt_rand(
                        self::MIN_CONV_VALUE,
                        self::MAX_CONV_VALUE
                    ) / mt_getrandmax();
                $campaignReportConv->convValue = $campaignReportCost->convValue;
                $campaignReportCost->costPerConv = mt_rand(
                        self::MIN_COST_PER_CONV,
                        self::MAX_COST_PER_CONV
                    ) / mt_getrandmax();
                $campaignReportCost->valuePerConv = mt_rand(
                        self::MIN_VALUE_PER_CONV,
                        self::MAX_VALUE_PER_CONV
                    ) / mt_getrandmax();
                $campaignReportConv->valuePerConv = $campaignReportCost->valuePerConv;
                $campaignReportCost->mobileBidAdj = mt_rand(
                        self::MIN_MOBILE_BID_ADJ,
                        self::MAX_MOBILE_BID_ADJ
                    ) / mt_getrandmax();
                $campaignReportConv->mobileBidAdj = $campaignReportCost->mobileBidAdj;
                $campaignReportCost->desktopBidAdj = mt_rand(
                        self::MIN_DESKTOP_BID_ADJ,
                        self::MAX_DESKTOP_BID_ADJ
                    ) / mt_getrandmax();
                $campaignReportConv->desktopBidAdj = $campaignReportCost->desktopBidAdj;
                $campaignReportCost->tabletBidAdj = mt_rand(
                        self::MIN_TABLET_BID_ADJ,
                        self::MAX_TABLET_BID_ADJ
                    ) / mt_getrandmax();
                $campaignReportConv->tabletBidAdj = $campaignReportCost->tabletBidAdj;
                $campaignReportConv->valuePerAllConv = mt_rand(
                        self::MIN_VALUE_PER_ALL_CONV,
                        self::MAX_VALUE_PER_ALL_CONV
                    ) / mt_getrandmax();
                $campaignReportConv->allConv = mt_rand(
                        self::MIN_ALL_CONV,
                        self::MAX_ALL_CONV
                    ) / mt_getrandmax();
                $campaignReportConv->allConvValue = mt_rand(
                        self::MIN_ALL_CONV_VALUE,
                        self::MAX_ALL_CONV_VALUE
                    ) / mt_getrandmax();
                $campaignReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                $campaignReportConv->network = $campaignReportCost->network;
                $campaignReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                $campaignReportConv->device = $campaignReportCost->device;
                $campaignReportCost->day = $day;
                $campaignReportConv->day = $campaignReportCost->day;
                $campaignReportCost->dayOfWeek = $day->format('l');
                $campaignReportConv->dayOfWeek = $campaignReportCost->dayOfWeek;
                $campaignReportCost->quarter = (int)ceil((int)$day->format('n') / 3);
                $campaignReportConv->quarter = $campaignReportCost->quarter;
                $campaignReportCost->month = $day->format('F');
                $campaignReportConv->month = $campaignReportCost->month;
                $campaignReportCost->week = $day->format('W');
                $campaignReportConv->week = $campaignReportCost->week;
                $campaignReportCost->hourofday = $day->format('H');
                $campaignReportCost->campaignType = self::CAMPAIGN_TYPE[mt_rand(0, count(self::CAMPAIGN_TYPE) - 1)];
                $campaignReportConv->campaignType = $campaignReportCost->campaignType;
                $campaignReportConv->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
                $campaignReportConv->objectiveOfConversionTracking = self::OBJECTIVE_OF_CONVERSION_TRACKING;
                $campaignReportConv->conversionName = self::CONVERSION_NAME[mt_rand(0, count(self::CONVERSION_NAME) - 1)];
                $campaignReportCost->accountid = self::ACCOUNTID;
                $campaignReportConv->accountid = self::ACCOUNTID;

                $campaignReportCost->saveOrFail();
                $campaignReportConv->saveOrFail();
            }
        }
    }

    private function seedYssAdGroupReports()
    {
        $dateRange = $this->getDatePeriod();

        $costSums = $this->getSumsAdgroupCost();
        $costValues = $this->getRandomValuesForSums($costSums);

        $impressionSums = $this->getSumsAdgroupImpression();
        $impressionValues = $this->getRandomValuesForSums($impressionSums);

        $clickSums = $this->getSumsAdgroupClick();
        $clickValues = $this->getRandomValuesForSums($clickSums);

        foreach ($dateRange as $i => $day) {
            foreach (self::ADGROUPS_PER_CAMPAIGN as $campaignID => $adGroups) {
                foreach ($adGroups as $adgroupID => $adgroupName) {
                    $adgroupReportCost = new RepoYssAdgroupReportCost;
                    $adgroupReportConv = new RepoYssAdgroupReportConv;
                    $adgroupReportCost->exeDate = $day->format('Y-m-d');
                    $adgroupReportConv->exeDate = $day->format('Y-m-d');
                    $adgroupReportCost->startDate = $day->format('Y-m-d');
                    $adgroupReportConv->startDate = $day->format('Y-m-d');
                    $adgroupReportCost->endDate = $day->format('Y-m-d');
                    $adgroupReportConv->endDate = $day->format('Y-m-d');
                    $adgroupReportCost->account_id = self::ACCOUNT_ID;
                    $adgroupReportConv->account_id = self::ACCOUNT_ID;
                    $adgroupReportCost->campaign_id = self::CAMPAIGN_ID;
                    $adgroupReportConv->campaign_id = self::CAMPAIGN_ID;
                    $adgroupReportCost->campaignID = $campaignID;
                    $adgroupReportConv->campaignID = $campaignID;
                    $adgroupReportCost->adgroupID = $adgroupID;
                    $adgroupReportConv->adgroupID = $adgroupID;
                    $adgroupReportCost->campaignName = self::CAMPAIGNS[$campaignID];
                    $adgroupReportConv->campaignName = self::CAMPAIGNS[$campaignID];
                    $adgroupReportCost->adgroupName = $adgroupName;
                    $adgroupReportConv->adgroupName = $adgroupName;
                    $adgroupReportCost->adgroupDistributionSettings = 'Adgroup Distribution setting';
                    $adgroupReportConv->adgroupDistributionSettings = $adgroupReportCost->adgroupDistributionSettings;
                    $adgroupReportCost->adGroupBid = mt_rand(
                        self::MIN_ADGROUP_BID,
                        self::MAX_ADGROUP_BID
                    );
                    $adgroupReportConv->adGroupBid = $adgroupReportCost->adGroupBid;

                    $adgroupReportCost->cost = $costValues[$adgroupID][$i];

                    $adgroupReportCost->impressions = $impressionValues[$adgroupID][$i];

                    $adgroupReportCost->clicks = $clickValues[$adgroupID][$i];


                    $adgroupReportCost->ctr = ($adgroupReportCost->clicks / $adgroupReportCost->impressions) * 100;

                    $adgroupReportCost->averageCpc = $adgroupReportCost->cost / $adgroupReportCost->clicks;

                    $adgroupReportCost->averagePosition = self::AVERAGE_POSITION;

                    $adgroupReportCost->impressionShare = mt_rand(
                            self::MIN_IMPRESSION_SHARE,
                            self::MAX_IMPRESSION_SHARE
                        ) / mt_getrandmax();

                    $adgroupReportCost->exactMatchImpressionShare = mt_rand(
                            self::MIN_EXACT_MATCH_IMPRESSION_SHARE,
                            self::MAX_EXACT_MATCH_IMPRESSION_SHARE
                        ) / mt_getrandmax();

                    $adgroupReportCost->qualityLostImpressionShare = mt_rand(
                            self::MIN_BUDGET_LOST_IMPRESSION_SHARE,
                            self::MAX_BUDGET_LOST_IMPRESSION_SHARE
                        ) / mt_getrandmax();

                    $adgroupReportCost->trackingURL = self::TRACKING_URL;
                    $adgroupReportConv->trackingURL = self::TRACKING_URL;
                    $adgroupReportCost->customParameters = self::CUSTOM_PARAMETERS . ' ' . $i;
                    $adgroupReportCost->conversions = mt_rand(
                            self::MIN_CONVERSIONS,
                            self::MAX_CONVERSIONS
                        ) / mt_getrandmax();
                    $adgroupReportConv->conversions = $adgroupReportCost->conversions;
                    $adgroupReportCost->convRate = mt_rand(
                            self::MIN_CONV_RATE,
                            self::MAX_CONV_RATE
                        ) / mt_getrandmax();
                    $adgroupReportCost->convValue = mt_rand(
                            self::MIN_CONV_VALUE,
                            self::MAX_CONV_VALUE
                        ) / mt_getrandmax();
                    $adgroupReportConv->convValue = $adgroupReportCost->convValue;
                    $adgroupReportCost->costPerConv = mt_rand(
                            self::MIN_COST_PER_CONV,
                            self::MAX_COST_PER_CONV
                        ) / mt_getrandmax();
                    $adgroupReportCost->valuePerConv = mt_rand(
                            self::MIN_VALUE_PER_CONV,
                            self::MAX_VALUE_PER_CONV
                        ) / mt_getrandmax();
                    $adgroupReportConv->valuePerConv = $adgroupReportCost->valuePerConv;
                    $adgroupReportCost->mobileBidAdj = mt_rand(
                            self::MIN_MOBILE_BID_ADJ,
                            self::MAX_MOBILE_BID_ADJ
                        ) / mt_getrandmax();
                    $adgroupReportConv->mobileBidAdj = $adgroupReportCost->mobileBidAdj;
                    $adgroupReportCost->desktopBidAdj = mt_rand(
                            self::MIN_DESKTOP_BID_ADJ,
                            self::MAX_DESKTOP_BID_ADJ
                        ) / mt_getrandmax();
                    $adgroupReportConv->desktopBidAdj = $adgroupReportCost->desktopBidAdj;
                    $adgroupReportCost->tabletBidAdj = mt_rand(
                            self::MIN_TABLET_BID_ADJ,
                            self::MAX_TABLET_BID_ADJ
                        ) / mt_getrandmax();
                    $adgroupReportConv->tabletBidAdj = $adgroupReportCost->tabletBidAdj;
                    $adgroupReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                    $adgroupReportConv->network = $adgroupReportCost->network;
                    $adgroupReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                    $adgroupReportConv->device = $adgroupReportCost->device;

                    $adgroupReportCost->day = $day;
                    $adgroupReportConv->day = $day;
                    $adgroupReportCost->dayOfWeek = $day->format('l');
                    $adgroupReportConv->dayOfWeek = $day->format('l');
                    $adgroupReportCost->quarter = (int)ceil((int)$day->format('n') / 3);
                    $adgroupReportConv->quarter = $adgroupReportCost->quarter;
                    $adgroupReportCost->month = $day->format('F');
                    $adgroupReportConv->month = $day->format('F');
                    $adgroupReportCost->week = $day->format('W');
                    $adgroupReportConv->week = $day->format('W');
                    $adgroupReportCost->hourofday = $day->format('H');
                    $adgroupReportConv->customParameters = self::CUSTOM_PARAMETERS . ' ' . $i;
                    $adgroupReportConv->allConv = mt_rand(
                            self::MIN_ALL_CONV,
                            self::MAX_ALL_CONV
                        ) / mt_getrandmax();
                    $adgroupReportConv->allConvValue = mt_rand(
                            self::MIN_ALL_CONV_VALUE,
                            self::MAX_ALL_CONV_VALUE
                        ) / mt_getrandmax();
                    $adgroupReportConv->convValue = mt_rand(
                            self::MIN_CONV_VALUE,
                            self::MAX_CONV_VALUE
                        ) / mt_getrandmax();
                    $adgroupReportConv->valuePerAllConv = mt_rand(
                            self::MIN_VALUE_PER_ALL_CONV,
                            self::MAX_VALUE_PER_ALL_CONV
                        ) / mt_getrandmax();
                    $adgroupReportConv->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
                    $adgroupReportConv->objectiveOfConversionTracking = self::OBJECTIVE_OF_CONVERSION_TRACKING;
                    $adgroupReportConv->conversionName = self::CONVERSION_NAME[mt_rand(0, count(self::CONVERSION_NAME) - 1)];
                    $adgroupReportConv->accountid = self::ACCOUNTID;
                    $adgroupReportCost->accountid = self::ACCOUNTID;

                    $adgroupReportCost->saveOrFail();
                    $adgroupReportConv->saveOrFail();
                }
            }
        }
    }

    private function seedYssAdReports()
    {
        $dateRange = $this->getDatePeriod();

        $costValues = $this->getRandomValuesForSums(self::COST_KEYWORDS);
        $impressionValues = $this->getRandomValuesForSums(self::IMPRESSION_KEYWORDS);
        $clickValues = $this->getRandomValuesForSums(self::CLICK_KEYWORDS);

        foreach ($dateRange as $i => $day) {
            foreach (self::ADGROUPS_PER_CAMPAIGN as $campaignID => $adGroups) {
                foreach ($adGroups as $adgroupID => $adgroupName) {
                    foreach (self::KEYWORDS_PER_ADGROUP[$adgroupID] as $keywordID => $keyword) {
                        $adReportCost = new RepoYssAdReportCost;
                        $adReportConv = new RepoYssAdReportConv;
                        $adReportCost->exeDate = $day->format('Y-m-d');
                        $adReportConv->exeDate = $day->format('Y-m-d');
                        $adReportCost->startDate = $day->format('Y-m-d');
                        $adReportConv->startDate = $day->format('Y-m-d');
                        $adReportCost->endDate = $day->format('Y-m-d');
                        $adReportConv->endDate = $day->format('Y-m-d');
                        $adReportCost->account_id = self::ACCOUNT_ID;
                        $adReportConv->account_id = self::ACCOUNT_ID;
                        $adReportCost->campaign_id = self::CAMPAIGN_ID;
                        $adReportConv->campaign_id = self::CAMPAIGN_ID;
                        $adReportCost->campaignID = $campaignID;
                        $adReportConv->campaignID = $campaignID;
                        $adReportCost->adgroupID = $adgroupID;
                        $adReportConv->adgroupID = $adgroupID;
                        $adReportCost->adID = $keywordID;
                        $adReportConv->adID = $keywordID;
                        $adReportCost->campaignName = self::CAMPAIGNS[$campaignID];
                        $adReportConv->campaignName = self::CAMPAIGNS[$campaignID];
                        $adReportCost->adgroupName = $adgroupName;
                        $adReportConv->adgroupName = $adgroupName;
                        $adReportCost->adName = $keyword;
                        $adReportConv->adName = $keyword;
                        $adReportCost->title = str_random(10);
                        $adReportConv->title = $adReportCost->title;
                        $adReportCost->description1 = str_random(10);
                        $adReportConv->description1 = $adReportCost->description1;
                        $adReportConv->description2 = str_random(10);
                        $adReportCost->description2 = $adReportConv->description2;
                        $adReportCost->displayURL = self::DISPLAY_URL;
                        $adReportConv->displayURL = $adReportCost->displayURL;
                        $adReportCost->destinationURL = self::DESTINATION_URL;
                        $adReportConv->destinationURL = $adReportCost->destinationURL;
                        $adReportCost->adType = self::AD_TYPE[mt_rand(0, count(self::AD_TYPE) -1)];
                        $adReportConv->adType = $adReportCost->adType;
                        $adReportCost->adDistributionSettings = str_random(10);
                        $adReportConv->adDistributionSettings = $adReportCost->adDistributionSettings;
                        $adReportCost->adEditorialStatus = str_random(10);
                        $adReportConv->adEditorialStatus = $adReportCost->adEditorialStatus;
                        $adReportConv->focusDevice = str_random(10);
                        $adReportConv->trackingURL = self::TRACKING_URL;
                        $adReportConv->customParameters = str_random(10);

                        $adReportCost->cost = $costValues[$keywordID][$i];

                        $adReportCost->impressions = $impressionValues[$keywordID][$i];

                        $adReportCost->clicks = $clickValues[$keywordID][$i];

                        $adReportCost->ctr = ($adReportCost->clicks / $adReportCost->impressions) * 100;

                        $adReportCost->averageCpc = $adReportCost->cost / $adReportCost->clicks;

                        $adReportCost->averagePosition = self::AVERAGE_POSITION;

                        $adReportConv->landingPageURL = self::LOADING_PAGE_URL;
                        $adReportConv->landingPageURLSmartphone = self::LOADING_PAGE_URL_SMART_PHONE;
                        $adReportConv->adTrackingID = $i;
                        $adReportCost->focusDevice = $adReportConv->focusDevice;
                        $adReportConv->conversions =  mt_rand(
                                self::MIN_CONVERSIONS,
                                self::MAX_CONVERSIONS
                        ) / mt_getrandmax();
                        $adReportCost->trackingURL = $adReportConv->trackingURL;
                        $adReportConv->convValue = mt_rand(
                                self::MIN_CONV_VALUE,
                                self::MAX_CONV_VALUE
                            ) / mt_getrandmax();
                        $adReportCost->customParameters = $adReportConv->customParameters;
                        $adReportConv->valuePerConv = mt_rand(
                                self::MIN_VALUE_PER_CONV,
                                self::MAX_VALUE_PER_CONV
                            ) / mt_getrandmax();
                        $adReportCost->landingPageURL = $adReportConv->landingPageURL;
                        $adReportConv->allConv = mt_rand(
                                self::MIN_ALL_CONV,
                                self::MAX_ALL_CONV
                            ) / mt_getrandmax();
                        $adReportCost->landingPageURLSmartphone = $adReportConv->landingPageURLSmartphone;
                        $adReportConv->allConvValue = mt_rand(
                                self::MIN_ALL_CONV_VALUE,
                                self::MAX_ALL_CONV_VALUE
                            ) / mt_getrandmax();
                        $adReportCost->adTrackingID = $adReportConv->adTrackingID;
                        $adReportConv->valuePerAllConv = mt_rand(
                                self::MIN_VALUE_PER_ALL_CONV,
                                self::MAX_VALUE_PER_ALL_CONV
                            ) / mt_getrandmax();
                        $adReportCost->conversions = $adReportConv->conversions;
                        $adReportConv->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                        $adReportCost->convRate = mt_rand(
                                self::MIN_CONV_RATE,
                                self::MAX_CONV_RATE
                            ) / mt_getrandmax();
                        $adReportConv->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
                        $adReportCost->convValue = $adReportConv->convValue;
                        $adReportConv->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                        $adReportCost->costPerConv = mt_rand(
                                self::MIN_COST_PER_CONV,
                                self::MAX_COST_PER_CONV
                            ) / mt_getrandmax();
                        $adReportConv->day = $day;
                        $adReportCost->valuePerConv = $adReportConv->valuePerConv;
                        $adReportConv->dayOfWeek = $day->format('l');
                        $adReportCost->allConv = $adReportConv->allConv;
                        $adReportConv->quarter = (int)ceil((int)$day->format('n') / 3);
                        $adReportCost->allConvRate = mt_rand(
                                self::MIN_ALL_CONV_RATE,
                                self::MAX_ALL_CONV_RATE
                            ) / mt_getrandmax();
                        $adReportConv->month = $day->format('F');
                        $adReportCost->allConvValue = $adReportConv->allConvValue;
                        $adReportConv->week = $day->format('W');
                        $adReportCost->costPerAllConv = mt_rand(
                                self::MIN_COST_PER_ALL_CONV,
                                self::MAX_COST_PER_ALL_CONV
                            ) / mt_getrandmax();
                        $adReportConv->objectiveOfConversionTracking = str_random(10);
                        $adReportCost->valuePerAllConv = $adReportConv->valuePerAllConv;
                        $adReportConv->conversionName = self::CONVERSION_NAME[
                        mt_rand(0, count(self::CONVERSION_NAME) - 1)
                        ];
                        $adReportCost->network = $adReportConv->network;
                        $adReportConv->adKeywordID = $i;
                        $adReportCost->clickType = $adReportConv->clickType;
                        $adReportConv->title1 = str_random(10);
                        $adReportCost->device = $adReportConv->device;
                        $adReportConv->title2 = str_random(10);
                        $adReportCost->day = $day;
                        $adReportConv->description = str_random(10);
                        $adReportCost->dayOfWeek = $adReportConv->dayOfWeek;
                        $adReportConv->directory1 = str_random(10);
                        $adReportCost->quarter = $adReportConv->quarter;
                        $adReportConv->directory2 = str_random(10);
                        $adReportCost->month = $adReportConv->month;
                        $adReportCost->week = $adReportConv->week;
                        $adReportCost->adKeywordID = $keywordID;
                        $adReportCost->title1 = $adReportConv->title1;
                        $adReportCost->title2 = $adReportConv->title2;
                        $adReportCost->description = $adReportConv->description;
                        $adReportCost->directory1 = $adReportConv->directory1;
                        $adReportCost->directory2 = $adReportConv->directory2;
                        $adReportConv->accountid = self::ACCOUNTID;
                        $adReportCost->accountid = self::ACCOUNTID;

                        $adReportCost->saveOrFail();
                        $adReportConv->saveOrFail();
                    }
                }
            }
        }
    }

    private function seedYssKeywordReports()
    {
        $dateRange = $this->getDatePeriod();

        $costValues = $this->getRandomValuesForSums(self::COST_KEYWORDS);
        $impressionValues = $this->getRandomValuesForSums(self::IMPRESSION_KEYWORDS);
        $clickValues = $this->getRandomValuesForSums(self::CLICK_KEYWORDS);

        foreach ($dateRange as $i => $day) {
            foreach (self::ADGROUPS_PER_CAMPAIGN as $campaignID => $adGroups) {
                foreach ($adGroups as $adgroupID => $adgroupName) {
                    foreach (self::KEYWORDS_PER_ADGROUP[$adgroupID] as $keywordID => $keyword) {
                        $keywordReportCost = new RepoYssKeywordReportCost;
                        $keywordReportConv = new RepoYssKeywordReportConv;
                        $keywordReportCost->exeDate = $day->format('Y-m-d');
                        $keywordReportConv->exeDate = $day->format('Y-m-d');
                        $keywordReportCost->startDate = $day->format('Y-m-d');
                        $keywordReportConv->startDate = $day->format('Y-m-d');
                        $keywordReportCost->endDate = $day->format('Y-m-d');
                        $keywordReportConv->endDate = $day->format('Y-m-d');
                        $keywordReportCost->account_id = self::ACCOUNT_ID;
                        $keywordReportConv->account_id = self::ACCOUNT_ID;
                        $keywordReportCost->campaign_id = self::CAMPAIGN_ID;
                        $keywordReportConv->campaign_id = self::CAMPAIGN_ID;
                        $keywordReportCost->campaignID = $campaignID;
                        $keywordReportConv->campaignID = $campaignID;
                        $keywordReportCost->adgroupID = $adgroupID;
                        $keywordReportConv->adgroupID = $adgroupID;
                        $keywordReportCost->keywordID = $keywordID;
                        $keywordReportConv->keywordID = $keywordID;
                        $keywordReportCost->campaignName = self::CAMPAIGNS[$campaignID];
                        $keywordReportConv->campaignName = self::CAMPAIGNS[$campaignID];
                        $keywordReportCost->adgroupName = $adgroupName;
                        $keywordReportConv->adgroupName = $adgroupName;
                        $keywordReportCost->customURL = self::CUSTOM_URL . $i;
                        $keywordReportConv->customURL = self::CUSTOM_URL . $i;
                        $keywordReportCost->keyword = $keyword;
                        $keywordReportConv->keyword = $keyword;
                        $keywordReportCost->keywordDistributionSettings = self::KEYWORD_DISTRIBUTION_SETTINGS;
                        $keywordReportConv->keywordDistributionSettings = self::KEYWORD_DISTRIBUTION_SETTINGS;
                        $keywordReportCost->kwEditorialStatus = self::KEYWORD_EDITORIAL_STATUS;
                        $keywordReportConv->kwEditorialStatus = self::KEYWORD_EDITORIAL_STATUS;
                        $keywordReportCost->adGroupBid = mt_rand(
                            self::MIN_ADGROUP_BID,
                            self::MAX_ADGROUP_BID
                        );
                        $keywordReportConv->adGroupBid = $keywordReportCost->adGroupBid;
                        $keywordReportCost->bid = mt_rand(
                            self::MIN_BID,
                            self::MAX_BID
                        );
                        $keywordReportConv->bid = $keywordReportCost->bid;
                        $keywordReportCost->negativeKeywords = self::NEGATIVE_KEY_WORDS;
                        $keywordReportConv->negativeKeywords = self::NEGATIVE_KEY_WORDS;
                        $keywordReportCost->qualityIndex = mt_rand(
                            self::MIN_QUALITY_INDEX,
                            self::MAX_QUALITY_INDEX
                        );
                        $keywordReportConv->qualityIndex = $keywordReportCost->qualityIndex;
                        $keywordReportCost->firstPageBidEstimate = mt_rand(
                            self::MIN_FIRST_PAGE_BID_ESTIMATE,
                            self::MAX_FIRST_PAGE_BID_ESTIMATE
                        );
                        $keywordReportConv->firstPageBidEstimate = $keywordReportCost->firstPageBidEstimate;
                        $keywordReportCost->keywordMatchType = self::KEYWORD_MATCH_TYPE;
                        $keywordReportConv->keywordMatchType = self::KEYWORD_MATCH_TYPE;

                        $keywordReportCost->cost = $costValues[$keywordID][$i];

                        $keywordReportCost->impressions = $impressionValues[$keywordID][$i];

                        $keywordReportCost->clicks = $clickValues[$keywordID][$i];

                        $keywordReportCost->ctr = ($keywordReportCost->clicks / $keywordReportCost->impressions) * 100;

                        $keywordReportCost->averageCpc = $keywordReportCost->cost / $keywordReportCost->clicks;

                        $keywordReportCost->averagePosition = self::AVERAGE_POSITION;

                        $keywordReportCost->impressionShare = mt_rand(
                                self::MIN_IMPRESSION_SHARE,
                                self::MAX_IMPRESSION_SHARE
                        ) / mt_getrandmax();
                        $keywordReportCost->exactMatchImpressionShare = mt_rand(
                                self::MIN_EXACT_MATCH_IMPRESSION_SHARE,
                                self::MAX_EXACT_MATCH_IMPRESSION_SHARE
                        ) / mt_getrandmax();
                        $keywordReportCost->qualityLostImpressionShare = mt_rand(
                                self::MIN_BUDGET_LOST_IMPRESSION_SHARE,
                                self::MAX_BUDGET_LOST_IMPRESSION_SHARE
                        ) / mt_getrandmax();
                        $keywordReportCost->topOfPageBidEstimate = mt_rand(
                            self::MIN_TOP_OF_PAGE_BID_ESTIMATE,
                            self::MAX_TOP_OF_PAGE_BID_ESTIMATE
                        );
                        $keywordReportConv->topOfPageBidEstimate = $keywordReportCost->topOfPageBidEstimate;
                        $keywordReportCost->trackingURL = self::TRACKING_URL;
                        $keywordReportConv->trackingURL = self::TRACKING_URL;
                        $keywordReportCost->customParameters = self::CUSTOM_PARAMETERS;
                        $keywordReportConv->customParameters = self::CUSTOM_PARAMETERS;
                        $keywordReportCost->landingPageURL = self::LANDING_PAGE_URL;
                        $keywordReportConv->landingPageURL = self::LANDING_PAGE_URL;
                        $keywordReportCost->landingPageURLSmartphone = self::LANDING_PAGE_URL_SMART_PHONE;
                        $keywordReportConv->landingPageURLSmartphone = self::LANDING_PAGE_URL_SMART_PHONE;
                        $keywordReportCost->conversions = mt_rand(
                                self::MIN_CONVERSIONS,
                                self::MAX_CONVERSIONS
                            ) / mt_getrandmax();
                        $keywordReportConv->conversions = $keywordReportCost->conversions;
                        $keywordReportCost->convRate = mt_rand(
                                self::MIN_CONV_RATE,
                                self::MAX_CONV_RATE
                            ) / mt_getrandmax();
                        $keywordReportCost->convValue = mt_rand(
                                self::MIN_CONV_VALUE,
                                self::MAX_CONV_VALUE
                            ) / mt_getrandmax();
                        $keywordReportConv->convValue = $keywordReportCost->convValue;
                        $keywordReportCost->costPerConv = mt_rand(
                                self::MIN_COST_PER_CONV,
                                self::MAX_COST_PER_CONV
                            ) / mt_getrandmax();

                        $keywordReportCost->valuePerConv = mt_rand(
                                self::MIN_VALUE_PER_CONV,
                                self::MAX_VALUE_PER_CONV
                            ) / mt_getrandmax();
                        $keywordReportConv->valuePerConv = $keywordReportCost->valuePerConv;
                        $keywordReportCost->allConv = mt_rand(
                                self::MIN_ALL_CONV,
                                self::MAX_ALL_CONV
                            ) / mt_getrandmax();
                        $keywordReportConv->allConv = $keywordReportCost->allConv;
                        $keywordReportCost->allConvRate = mt_rand(
                                self::MIN_ALL_CONV_RATE,
                                self::MAX_ALL_CONV_RATE
                            ) / mt_getrandmax();
                        $keywordReportCost->allConvValue = mt_rand(
                                self::MIN_ALL_CONV_VALUE,
                                self::MAX_ALL_CONV_VALUE
                            ) / mt_getrandmax();
                        $keywordReportConv->allConvValue = $keywordReportCost->allConvValue;
                        $keywordReportCost->costPerAllConv = mt_rand(
                                self::MIN_COST_PER_ALL_CONV,
                                self::MAX_COST_PER_ALL_CONV
                            ) / mt_getrandmax();
                        $keywordReportCost->valuePerAllConv = mt_rand(
                                self::MIN_VALUE_PER_ALL_CONV,
                                self::MAX_VALUE_PER_ALL_CONV
                            ) / mt_getrandmax();
                        $keywordReportConv->valuePerAllConv = $keywordReportCost->valuePerAllConv;
                        $keywordReportCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                        $keywordReportConv->network = $keywordReportCost->network;
                        $keywordReportConv->clickType = self::CLICK_TYPE[mt_rand(0, count(self::CLICK_TYPE) - 1)];
                        $keywordReportCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                        $keywordReportConv->device = $keywordReportCost->device;
                        $keywordReportCost->day = $day;
                        $keywordReportConv->day = $day;
                        $keywordReportCost->dayOfWeek = $day->format('l');
                        $keywordReportConv->dayOfWeek = $day->format('l');
                        $keywordReportCost->quarter = (int)ceil((int)$day->format('n') / 3);
                        $keywordReportConv->quarter = (int)ceil((int)$day->format('n') / 3);
                        $keywordReportCost->month = $day->format('F');
                        $keywordReportConv->month = $day->format('F');
                        $keywordReportCost->week = $day->format('W');
                        $keywordReportConv->week = $day->format('W');
                        $keywordReportConv->objectiveOfConversionTracking = self::OBJECTIVE_OF_CONVERSION_TRACKING;
                        $keywordReportConv->conversionName = self::CONVERSION_NAME[
                            mt_rand(0, count(self::CONVERSION_NAME) - 1)
                        ];
                        $keywordReportConv->accountid = self::ACCOUNTID;
                        $keywordReportCost->accountid = self::ACCOUNTID;

                        $keywordReportCost->saveOrFail();
                        $keywordReportConv->saveOrFail();
                    }
                }
            }
        }
    }

    private function seedYssPrefectureReports()
    {
        $dateRange = $this->getDatePeriod();

        $costSums = $this->getSumsAdgroupCost();
        $impressionSums = $this->getSumsAdgroupImpression();
        $clickSums = $this->getSumsAdgroupClick();

        // Get random sums per prefecture
        $sumsAdgroupPrefectureCost = [];
        foreach ($costSums as $adgroupID => $cost) {
            $sumsAdgroupPrefectureCost[$adgroupID] = $this->getRandomValues($cost, count(self::PREFECTURES));
        }

        $sumsAdgroupPrefectureImpression = [];
        foreach ($impressionSums as $adgroupID => $impression) {
            $sumsAdgroupPrefectureImpression[$adgroupID] = $this->getRandomValues($impression, count(self::PREFECTURES));
        }

        $sumsAdgroupPrefectureClick = [];
        foreach ($impressionSums as $adgroupID => $click) {
            $sumsAdgroupPrefectureClick[$adgroupID] = $this->getRandomValues($click, count(self::PREFECTURES));
        }

        // Get random values
        $costValuesAdgroupPrefecture = [];
        foreach ($sumsAdgroupPrefectureCost as $adgroupID => $costs) {
            $costValuesAdgroupPrefecture[$adgroupID] = [];
            foreach ($costs as $cost) {
                $costValuesAdgroupPrefecture[$adgroupID][] = $this->getRandomValues($cost, self::NUMBER_OF_DAYS);
            }
        }

        $impressionValuesAdgroupPrefecture = [];
        foreach ($sumsAdgroupPrefectureImpression as $adgroupID => $impressions) {
            $impressionValuesAdgroupPrefecture[$adgroupID] = [];
            foreach ($impressions as $impression) {
                $impressionValuesAdgroupPrefecture[$adgroupID][] = $this->getRandomValues($impression, self::NUMBER_OF_DAYS);
            }
        }

        $clickValuesAdgroupPrefecture = [];
        foreach ($sumsAdgroupPrefectureClick as $adgroupID => $clicks) {
            $clickValuesAdgroupPrefecture[$adgroupID] = [];
            foreach ($clicks as $click) {
                $clickValuesAdgroupPrefecture[$adgroupID][] = $this->getRandomValues($click, self::NUMBER_OF_DAYS);
            }
        }

        foreach ($dateRange as $i => $day) {
            foreach (self::ADGROUPS_PER_CAMPAIGN as $campaignID => $adGroups) {
                foreach ($adGroups as $adgroupID => $adgroupName) {
                    foreach (self::PREFECTURES as $j => $prefectureName) {
                        // The commented out lines below are there because I wanted to seed that table too,
                        // but it seems the cost table already has all the info we need.
                        // I want to keep these lines because I have a feeling that the cost table structure will
                        // change soon.

                        $prefectureCost = new RepoYssPrefectureReportCost;
                        //$prefectureConv = new RepoYssPrefectureReportConv;

                        $prefectureCost->exeDate = $day->format('Y-m-d');
                        //$prefectureConv->exeDate = $day->format('Y-m-d');

                        $prefectureCost->startDate = $day->format('Y-m-d');
                        //$prefectureConv->startDate = $day->format('Y-m-d');

                        $prefectureCost->endDate = $day->format('Y-m-d');
                        //$prefectureConv->endDate = $day->format('Y-m-d');

                        $prefectureCost->accountid = self::ACCOUNTID;
                        //$prefectureConv->accountid = self::ACCOUNTID;

                        $prefectureCost->account_id = self::ACCOUNT_ID;
                        //$prefectureConv->account_id = self::ACCOUNT_ID;

                        $prefectureCost->campaign_id = self::CAMPAIGN_ID;
                        //$prefectureConv->campaign_id = self::CAMPAIGN_ID;

                        $prefectureCost->campaignID = $campaignID;
                        //$prefectureConv->campaignID = $campaignID;

                        $prefectureCost->adgroupID = $adgroupID;
                        //$prefectureConv->adgroupID = $adgroupID;

                        $prefectureCost->campaignName = self::CAMPAIGNS[$campaignID];
                        //$prefectureConv->campaignName = self::CAMPAIGNS[$campaignID];

                        $prefectureCost->adgroupName = $adgroupName;
                        //$prefectureConv->adgroupName = $adgroupName;

                        $prefectureCost->cost = $costValuesAdgroupPrefecture[$adgroupID][$j][$i];

                        $prefectureCost->impressions = $impressionValuesAdgroupPrefecture[$adgroupID][$j][$i];

                        $prefectureCost->clicks = $clickValuesAdgroupPrefecture[$adgroupID][$j][$i];

                        $prefectureCost->ctr = ($prefectureCost->clicks / $prefectureCost->impressions) * 100;

                        $prefectureCost->averageCpc = $prefectureCost->cost / $prefectureCost->clicks;

                        $prefectureCost->averagePosition = self::AVERAGE_POSITION;

                        $prefectureCost->conversions = mt_rand(
                                self::MIN_CONVERSIONS,
                                self::MAX_CONVERSIONS
                            ) / mt_getrandmax();

                        $prefectureCost->convRate = mt_rand(
                                self::MIN_CONV_RATE,
                                self::MAX_CONV_RATE
                            ) / mt_getrandmax();
                        $prefectureCost->convValue = mt_rand(
                                self::MIN_CONV_VALUE,
                                self::MAX_CONV_VALUE
                            ) / mt_getrandmax();
                        $prefectureCost->costPerConv = mt_rand(
                                self::MIN_COST_PER_CONV,
                                self::MAX_COST_PER_CONV
                            ) / mt_getrandmax();
                        $prefectureCost->valuePerConv = mt_rand(
                                self::MIN_VALUE_PER_CONV,
                                self::MAX_VALUE_PER_CONV
                            ) / mt_getrandmax();
                        $prefectureCost->allConv = mt_rand(
                                self::MIN_ALL_CONV,
                                self::MAX_ALL_CONV
                            ) / mt_getrandmax();
                        $prefectureCost->allConvRate = mt_rand(
                                self::MIN_ALL_CONV_RATE,
                                self::MAX_ALL_CONV_RATE
                            ) / mt_getrandmax();
                        $prefectureCost->allConvValue = mt_rand(
                                self::MIN_ALL_CONV_VALUE,
                                self::MAX_ALL_CONV_VALUE
                            ) / mt_getrandmax();
                        $prefectureCost->costPerAllConv = mt_rand(
                                self::MIN_COST_PER_ALL_CONV,
                                self::MAX_COST_PER_ALL_CONV
                            ) / mt_getrandmax();
                        $prefectureCost->valuePerAllConv = mt_rand(
                                self::MIN_VALUE_PER_ALL_CONV,
                                self::MAX_VALUE_PER_ALL_CONV
                            ) / mt_getrandmax();
                        $prefectureCost->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                        $prefectureCost->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                        $prefectureCost->day = $day;
                        $prefectureCost->dayOfWeek = $day->format('l');
                        $prefectureCost->quarter = (int)ceil((int)$day->format('n') / 3);
                        $prefectureCost->month = $day->format('F');
                        $prefectureCost->week = $day->format('W');
                        $prefectureCost->countryTerritory = 'Japan';
                        $prefectureCost->prefecture = $prefectureName;
                        $prefectureCost->city = self::CITY[mt_rand(0, count(self::CITY) - 1)];
                        $prefectureCost->cityWardDistrict = self::CITY_WAR_DISTRICT[mt_rand(0, count(self::CITY_WAR_DISTRICT) - 1)];
                        $prefectureCost->saveOrFail();
                    }
                }
            }
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedUsers();
        $this->seedYssAccounts();
        $this->seedYssAccountReports();
        $this->seedYssCampaignReports();
        $this->seedYssAdGroupReports();
        $this->seedYssAdReports();
        $this->seedYssKeywordReports();
        $this->seedYssPrefectureReports();
    }
}
