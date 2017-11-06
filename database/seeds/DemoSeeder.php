<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Model\RepoYssAccount;
use App\Model\RepoYssAccountReportCost;
use App\Model\RepoYssAccountReportConv;
use App\Model\RepoYssCampaignReportCost;
use App\Model\RepoYssCampaignReportConv;

use NlpTools\Random\Distributions\Dirichlet;

// @codingStandardsIgnoreLine
class DemoSeeder extends Seeder
{
    const START_DATE = '2017-11-01';
    const END_DATE = '2017-12-01';
    const NUMBER_OF_DAYS = 30;
    const ACCOUNT_ID = 1;
    const ACCOUNTID = 1;
    const CAMPAIGN_ID = 1;

    const CAMPAIGNS = [
        1 => 'コールトラッキング',
        2 => '電話計測'
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

    private function getRandomValuesPerCampaign($sums)
    {
        $costValuesPerCampaign = [];
        foreach ($sums as $campaignId => $sum) {
            $costValuesPerCampaign[$campaignId] = $this->getRandomValues(
                $sum,
                self::NUMBER_OF_DAYS
            );
        }

        return $costValuesPerCampaign;
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

            $costReport->averagePosition = mt_rand(
                    self::MIN_AVERAGE_POSITION,
                    self::MAX_AVERAGE_POSITION
                ) / mt_getrandmax();

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
        $costValues = $this->getRandomValuesPerCampaign($sumsCost);

        $sumsImpression = $this->getSumsCampaignImpression();
        $impressionValues = $this->getRandomValuesPerCampaign($sumsImpression);

        $sumsClick = $this->getSumsCampaignClick();
        $clickValues = $this->getRandomValuesPerCampaign($sumsClick);

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
                $campaignReportCost->averagePosition = mt_rand(
                        self::MIN_AVERAGE_POSITION,
                        self::MAX_AVERAGE_POSITION
                    ) / mt_getrandmax();
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
                $campaignReportCost->dayOfWeek = $day->format('l');;
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
    }
}
