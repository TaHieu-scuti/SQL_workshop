<?php

namespace Tests\Feature\RepoYssCampaignReport;

use App\Http\Controllers\RepoYssCampaignReport\RepoYssCampaignReportController;
use App\User;

use Tests\TestCase;

use DateTime;

class GraphApiYssCampaignReportTest extends TestCase
{
    const ROUTE_DISPLAY_GRAPH = '/campaign-report/display-graph';
    const ROUTE_LOGIN = '/login';
    const CORRECT_DATA_90_DAYS = '{"data":['
        . '{"data":"424857","day":"2017-01-01"},{"data":"514258","day":"2017-01-02"},'
        . '{"data":"920341","day":"2017-01-03"},{"data":"755348","day":"2017-01-04"},'
        . '{"data":"559277","day":"2017-01-05"},{"data":"563889","day":"2017-01-06"},'
        . '{"data":"330595","day":"2017-01-07"},{"data":"510463","day":"2017-01-08"},'
        . '{"data":"579018","day":"2017-01-09"},{"data":"571484","day":"2017-01-10"},'
        . '{"data":"369065","day":"2017-01-11"},{"data":"486148","day":"2017-01-12"},'
        . '{"data":"380478","day":"2017-01-13"},{"data":"519916","day":"2017-01-14"},'
        . '{"data":"732648","day":"2017-01-15"},{"data":"725344","day":"2017-01-16"},'
        . '{"data":"750990","day":"2017-01-17"},{"data":"365090","day":"2017-01-18"},'
        . '{"data":"838475","day":"2017-01-19"},{"data":"667129","day":"2017-01-20"},'
        . '{"data":"668377","day":"2017-01-21"},{"data":"730255","day":"2017-01-22"},'
        . '{"data":"604291","day":"2017-01-23"},{"data":"743538","day":"2017-01-24"},'
        . '{"data":"655515","day":"2017-01-25"},{"data":"562739","day":"2017-01-26"},'
        . '{"data":"599901","day":"2017-01-27"},{"data":"841133","day":"2017-01-28"},'
        . '{"data":"471843","day":"2017-01-29"},{"data":"694733","day":"2017-01-30"},'
        . '{"data":"759499","day":"2017-01-31"},{"data":"433515","day":"2017-02-01"},'
        . '{"data":"712840","day":"2017-02-02"},{"data":"553386","day":"2017-02-03"},'
        . '{"data":"751608","day":"2017-02-04"},{"data":"714238","day":"2017-02-05"},'
        . '{"data":"520816","day":"2017-02-06"},{"data":"487213","day":"2017-02-07"},'
        . '{"data":"627834","day":"2017-02-08"},{"data":"536354","day":"2017-02-09"},'
        . '{"data":"620851","day":"2017-02-10"},{"data":"465067","day":"2017-02-11"},'
        . '{"data":"595879","day":"2017-02-12"},{"data":"429055","day":"2017-02-13"},'
        . '{"data":"642375","day":"2017-02-14"},{"data":"935480","day":"2017-02-15"},'
        . '{"data":"795073","day":"2017-02-16"},{"data":"459872","day":"2017-02-17"},'
        . '{"data":"762288","day":"2017-02-18"},{"data":"483315","day":"2017-02-19"},'
        . '{"data":"541839","day":"2017-02-20"},{"data":"573024","day":"2017-02-21"},'
        . '{"data":"792818","day":"2017-02-22"},{"data":"787060","day":"2017-02-23"},'
        . '{"data":"458822","day":"2017-02-24"},{"data":"786161","day":"2017-02-25"},'
        . '{"data":"538579","day":"2017-02-26"},{"data":"806882","day":"2017-02-27"},'
        . '{"data":"465054","day":"2017-02-28"},{"data":"511548","day":"2017-03-01"},'
        . '{"data":"713058","day":"2017-03-02"},{"data":"541415","day":"2017-03-03"},'
        . '{"data":"693624","day":"2017-03-04"},{"data":"500821","day":"2017-03-05"},'
        . '{"data":"344967","day":"2017-03-06"},{"data":"580422","day":"2017-03-07"},'
        . '{"data":"535227","day":"2017-03-08"},{"data":"430360","day":"2017-03-09"},'
        . '{"data":"892097","day":"2017-03-10"},{"data":"603575","day":"2017-03-11"},'
        . '{"data":"431605","day":"2017-03-12"},{"data":"434524","day":"2017-03-13"},'
        . '{"data":"642983","day":"2017-03-14"},{"data":"325289","day":"2017-03-15"},'
        . '{"data":"509533","day":"2017-03-16"},{"data":"626984","day":"2017-03-17"},'
        . '{"data":"734196","day":"2017-03-18"},{"data":"771441","day":"2017-03-19"},'
        . '{"data":"529961","day":"2017-03-20"},{"data":"613136","day":"2017-03-21"},'
        . '{"data":"595638","day":"2017-03-22"},{"data":"624926","day":"2017-03-23"},'
        . '{"data":"811780","day":"2017-03-24"},{"data":"611770","day":"2017-03-25"},'
        . '{"data":"552200","day":"2017-03-26"},{"data":"550417","day":"2017-03-27"},'
        . '{"data":"689352","day":"2017-03-28"},{"data":"526854","day":"2017-03-29"},'
        . '{"data":"799330","day":"2017-03-30"},{"data":"801419","day":"2017-03-31"}],'
        . '"field":"clicks",'
        . '"timePeriodLayout":"<span class=\"title\">Last 90 days<br><\/span>\n<span>2017-01-01 - '
        . '2017-04-01<\/span>\n<strong class=\"caret\"><\/strong>\n","graphColumnLayout":"<span id='
        . '\"txtColumn\">clicks<\/span>\n<strong class=\"caret selection\"><\/strong>","statusLayout":'
        . '"<span>Show enabled\n<strong class=\"caret selection\"><\/strong>\n<\/span>"}';
    const COLUMN_NAME_CAMPAIGN_NAME = 'campaignName';
    const COLUMN_NAME_DAILY_SPENDING_LIMIT = 'dailySpendingLimit';
    const COLUMN_NAME_COST = 'cost';
    const COLUMN_NAME_IMPRESSIONS = 'impressions';
    const COLUMN_NAME_CLICKS = 'clicks';
    const COLUMN_NAME_CTR = 'ctr';
    const COLUMN_NAME_AVERAGE_CPC = 'averageCpc';
    const COLUMN_NAME_AVERAGE_POSITION = 'averagePosition';
    const COLUMN_NAME_IMPRESSION_SHARE = 'impressionShare';
    const COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE = 'exactMatchImpressionShare';
    const COLUMN_NAME_BUDGET_LOST_IMPRESSION_SHARE = 'budgetLostImpressionShare';
    const COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE = 'qualityLostImpressionShare';
    const COLUMN_NAME_CONVERSIONS = 'conversions';
    const COLUMN_NAME_CONV_RATE = 'convRate';
    const COLUMN_NAME_CONV_VALUE = 'convValue';
    const COLUMN_NAME_COST_PER_CONV = 'costPerConv';
    const COLUMN_NAME_VALUE_PER_CONV = 'valuePerConv';
    const COLUMN_NAME_MOBILE_BID_ADJ = 'mobileBidAdj';
    const COLUMN_NAME_DESKTOP_BID_ADJ = 'desktopBidAdj';
    const COLUMN_NAME_TABLET_BID_ADJ = 'tabletBidAdj';

    const DEFAULT_FIELD_NAMES = [
        0 => self::COLUMN_NAME_CAMPAIGN_NAME,
        1 => self::COLUMN_NAME_DAILY_SPENDING_LIMIT,
        2 => self::COLUMN_NAME_COST,
        3 => self::COLUMN_NAME_IMPRESSIONS,
        4 => self::COLUMN_NAME_CLICKS,
        5 => self::COLUMN_NAME_CTR,
        6 => self::COLUMN_NAME_AVERAGE_CPC,
        7 => self::COLUMN_NAME_AVERAGE_POSITION,
        8 => self::COLUMN_NAME_IMPRESSION_SHARE,
        9 => self::COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE,
        10 => self::COLUMN_NAME_BUDGET_LOST_IMPRESSION_SHARE,
        11 => self::COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE,
        12 => self::COLUMN_NAME_CONVERSIONS,
        13 => self::COLUMN_NAME_CONV_RATE,
        14 => self::COLUMN_NAME_CONV_VALUE,
        15 => self::COLUMN_NAME_COST_PER_CONV,
        16 => self::COLUMN_NAME_VALUE_PER_CONV,
        17 => self::COLUMN_NAME_MOBILE_BID_ADJ,
        18 => self::COLUMN_NAME_DESKTOP_BID_ADJ,
        19 => self::COLUMN_NAME_TABLET_BID_ADJ
    ];

    const DEFAULT_ACCOUNT_STATUS = 'enabled';
    const DEFAULT_STATUS_TITLE = 'enabled';
    const DEFAULT_PAGINATION = 20;
    const DEFAULT_SORT = 'desc';
    const DATE_FIRST_DAY_2016 = '2016-01-01';
    const JSON_ERROR_FIELD_NAME = 'error';
    const JSON_STATUS_CODE_FIELD_NAME = 'code';

    private function getUser()
    {
        return (new User)->find(1)->first();
    }

    private function getDefaultSessionValues()
    {
        return [
            RepoYssCampaignReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELD_NAMES,
            RepoYssCampaignReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_ACCOUNT_STATUS,
            RepoYssCampaignReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
            RepoYssCampaignReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_IMPRESSIONS,
            RepoYssCampaignReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
            RepoYssCampaignReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE
        ];
    }

    private function displayGraphRouteRedirectsToLoginRouteWhenNotLoggedIn($method)
    {
        /** @var TestResponse $response */
        $response = $this->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertRedirect(self::ROUTE_LOGIN);
    }

    public function getUserAndAccessToCampaignReport()
    {
        $user = $this->getUser();
        $this->actingAs($user)->get('/campaign-report');
    }

    public function testDisplayGraphRouteRedirectsToLoginRouteWhenNotLoggedIn()
    {
        $this->displayGraphRouteRedirectsToLoginRouteWhenNotLoggedIn('get');
        $this->displayGraphRouteRedirectsToLoginRouteWhenNotLoggedIn('post');
    }

    private function returns200StatusWhenLoggedIn($method)
    {
        $this->getUserAndAccessToCampaignReport();
        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertStatus(200);
    }

    public function testReturns200StatusWhenLoggedIn()
    {
        $this->returns200StatusWhenLoggedIn('get');
        $this->returns200StatusWhenLoggedIn('post');
    }

    private function graphColumnNameInSessionIsSetToClicksAsDefaultValue($method)
    {
        $this->getUserAndAccessToCampaignReport();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSuccessful();

        $response->assertSessionHas(
            RepoYssCampaignReportController::SESSION_KEY_GRAPH_COLUMN_NAME,
            'clicks'
        );
    }

    public function testGraphColumnNameInSessionIsSetToClicksAsDefaultValue()
    {
        $this->graphColumnNameInSessionIsSetToClicksAsDefaultValue('get');
        $this->graphColumnNameInSessionIsSetToClicksAsDefaultValue('post');
    }

    private function statusTitleInSessionIsSetToEnabledAsDefaultValue($method)
    {
        $this->getUserAndAccessToCampaignReport();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSuccessful();

        $response->assertSessionHas(
            RepoYssCampaignReportController::SESSION_KEY_STATUS_TITLE,
            'enabled'
        );
    }
    
    public function teststatusTitleInSessionIsSetToEnabledAsDefaultValue()
    {
        $this->statusTitleInSessionIsSetToEnabledAsDefaultValue('get');
        $this->statusTitleInSessionIsSetToEnabledAsDefaultValue('post');
    }

    private function doesNotSetGraphColumnNameToDefaultValueClicksWhenItIsAlreadySet($method)
    {
        $this->flushSession();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssCampaignReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::COLUMN_NAME_IMPRESSIONS
            ])->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSessionHas(
            RepoYssCampaignReportController::SESSION_KEY_GRAPH_COLUMN_NAME,
            self::COLUMN_NAME_IMPRESSIONS
        );
    }

    public function testDoesNotSetGraphColumnNameToDefaultValueClicksWhenItIsAlreadySet()
    {
        $this->doesNotSetGraphColumnNameToDefaultValueClicksWhenItIsAlreadySet('get');
        $this->doesNotSetGraphColumnNameToDefaultValueClicksWhenItIsAlreadySet('post');
    }

    public function testUpdatesGraphColumnNameInSessionWhenPosted()
    {
        $this->flushSession();

        $response = $this->actingAs($this->getUser())
            ->post(
                self::ROUTE_DISPLAY_GRAPH,
                ['graphColumnName' => 'someColumnName']
            );

        $response->assertSessionHas(
            RepoYssCampaignReportController::SESSION_KEY_GRAPH_COLUMN_NAME,
            'someColumnName'
        );
    }

    public function testUpdatesStatusTitleInSessionWhenPosted()
    {
        $this->flushSession();

        $response = $this->actingAs($this->getUser())
            ->post(
                self::ROUTE_DISPLAY_GRAPH,
                ['statusTitle' => 'someStatusTitle']
            );

        $response->assertSessionHas(
            RepoYssCampaignReportController::SESSION_KEY_STATUS_TITLE,
            'someStatusTitle'
        );
    }

    public function testUpdatesStartDayEndDayAndTimePeriodTitleWhenPosted()
    {
        $this->flushSession();

        $response = $this->actingAs($this->getUser())
            ->post(
                self::ROUTE_DISPLAY_GRAPH,
                [
                    'startDay' => '2011-09-01',
                    'endDay' => '2011-10-01',
                    'timePeriodTitle' => 'someTitle'
                ]
            );

        $response->assertSessionHasAll([
            RepoYssCampaignReportController::SESSION_KEY_START_DAY => '2011-09-01',
            RepoYssCampaignReportController::SESSION_KEY_END_DAY => '2011-10-01',
            RepoYssCampaignReportController::SESSION_KEY_TIME_PERIOD_TITLE => 'someTitle'
        ]);
    }

    public function testDoesNotUpdateStartDayEndDayAndTimePeriodTitleWhenNotPosted()
    {
        $this->flushSession();

        $response = $this->actingAs($this->getUser())
            ->withSession([
                    RepoYssCampaignReportController::SESSION_KEY_START_DAY => '2010-09-01',
                    RepoYssCampaignReportController::SESSION_KEY_END_DAY => '2010-10-01',
                    RepoYssCampaignReportController::SESSION_KEY_TIME_PERIOD_TITLE => 'someTimePeriodTitle'
            ])->post(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSessionHasAll([
            RepoYssCampaignReportController::SESSION_KEY_START_DAY => '2010-09-01',
            RepoYssCampaignReportController::SESSION_KEY_END_DAY => '2010-10-01',
            RepoYssCampaignReportController::SESSION_KEY_TIME_PERIOD_TITLE => 'someTimePeriodTitle'
        ]);
    }

    public function testUpdatesStatusWhenPosted()
    {
        $this->flushSession();

        $response = $this->actingAs($this->getUser())
            ->post(
                self::ROUTE_DISPLAY_GRAPH,
                ['status' => 'someStatus']
            );

        $response->assertSessionHas(
            RepoYssCampaignReportController::SESSION_KEY_ACCOUNT_STATUS,
            'someStatus'
        );
    }

    public function testDoesNotUpdateStatusWhenNotPosted()
    {
        $this->flushSession();

        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssCampaignReportController::SESSION_KEY_ACCOUNT_STATUS => 'aStatus'
            ])->post(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSessionHas(
            RepoYssCampaignReportController::SESSION_KEY_ACCOUNT_STATUS,
            'aStatus'
        );
    }

    private function returnsCorrectDataFor90Days($method)
    {
        $this->getUserAndAccessToCampaignReport();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession(
                $this->getDefaultSessionValues() +
                [
                    RepoYssCampaignReportController::SESSION_KEY_START_DAY => '2017-01-01',
                    RepoYssCampaignReportController::SESSION_KEY_END_DAY => '2017-04-01'
                ]
            )->$method(self::ROUTE_DISPLAY_GRAPH);
        $this->assertSame(self::CORRECT_DATA_90_DAYS, $response->getContent());
    }

    public function testReturnsCorrectDataFor90Days()
    {
        $this->returnsCorrectDataFor90Days('get');
        $this->returnsCorrectDataFor90Days('post');
    }

    private function returnsCorrectResponseWhenNoDataIsAvailableAndStartDayAndEndDayAreTheSame($method)
    {
        $this->getUserAndAccessToCampaignReport();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession(
                $this->getDefaultSessionValues() +
                [
                    RepoYssCampaignReportController::SESSION_KEY_START_DAY => self::DATE_FIRST_DAY_2016,
                    RepoYssCampaignReportController::SESSION_KEY_END_DAY => self::DATE_FIRST_DAY_2016,
                ]
            )->$method(self::ROUTE_DISPLAY_GRAPH);
        $object = [
            'data' => [
                ['data' => 0, 'day' => self::DATE_FIRST_DAY_2016]
            ],
            'field' => 'clicks',
            'timePeriodLayout' => "<span class=\"title\">Last 90 days<br></span>\n"
                . "<span>2016-01-01 - 2016-01-01</span>\n<strong class=\"caret\"></strong>\n",
            'graphColumnLayout' => "<span id=\"txtColumn\">clicks</span>\n"
                ."<strong class=\"caret selection\"></strong>",
            'statusLayout' => "<span>Show enabled\n"
                ."<strong class=\"caret selection\"></strong>\n"
                ."</span>"
        ];

        $response->assertExactJson($object);
    }

    public function testReturnsCorrectResponseWhenNoDataIsAvailableAndStartDayAndEndDayAreTheSame()
    {
        $this->returnsCorrectResponseWhenNoDataIsAvailableAndStartDayAndEndDayAreTheSame('get');
        $this->returnsCorrectResponseWhenNoDataIsAvailableAndStartDayAndEndDayAreTheSame('post');
    }

    private function returnsCorrectResponseWhenNoDataIsAvailableAndStartDayAndEndDayAreNotTheSame($method)
    {
        $this->getUserAndAccessToCampaignReport();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession(
                $this->getDefaultSessionValues() +
                [
                    RepoYssCampaignReportController::SESSION_KEY_START_DAY => self::DATE_FIRST_DAY_2016,
                    RepoYssCampaignReportController::SESSION_KEY_END_DAY => '2016-02-01'
                ]
            )->$method(self::ROUTE_DISPLAY_GRAPH);

        $object = [
            'data' => [
                ['data' => 0, 'day' => '2016-01-01'], ['data' => 0, 'day' => '2016-02-01']
            ],
            'field' => 'clicks',
            'timePeriodLayout' => "<span class=\"title\">Last 90 days<br></span>\n"
                . "<span>2016-01-01 - 2016-02-01</span>\n<strong class=\"caret\"></strong>\n",
            'graphColumnLayout' => "<span id=\"txtColumn\">clicks</span>\n"
                ."<strong class=\"caret selection\"></strong>",
            'statusLayout' => "<span>Show enabled\n"
                ."<strong class=\"caret selection\"></strong>\n"
                ."</span>"
        ];

        $response->assertExactJson($object);
    }

    public function testReturnsCorrectResponseWhenNoDataIsAvailableAndStartDayAndEndDayAreNotTheSame()
    {
        $this->returnsCorrectResponseWhenNoDataIsAvailableAndStartDayAndEndDayAreNotTheSame('get');
        $this->returnsCorrectResponseWhenNoDataIsAvailableAndStartDayAndEndDayAreNotTheSame('post');
    }

    private function errorHandlingIncorrectFieldName($method)
    {
        $this->flushSession();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession(
                $this->getDefaultSessionValues() +
                [
                    RepoYssCampaignReportController::SESSION_KEY_START_DAY => '2017-01-01',
                    RepoYssCampaignReportController::SESSION_KEY_END_DAY => '2017-04-01',
                    RepoYssCampaignReportController::SESSION_KEY_GRAPH_COLUMN_NAME => 'someNonExistingColumnName'
                ]
            )->$method(self::ROUTE_DISPLAY_GRAPH);
        $response->assertStatus(500);

        $errorObject = [
            self::JSON_STATUS_CODE_FIELD_NAME => 500,
            self::JSON_ERROR_FIELD_NAME => 'SQLSTATE[42S22]: Column not found: 1054 Unknown column \''
            . 'someNonExistingColumnName\' in \'field list\' (SQL: select SUM(someNonExistingColumnName)'
            . ' as data, DATE(day) as day from `repo_yss_campaign_report_costs` where (date(`day`) >= '
            . '2017-01-01 and date(`day`) < 2017-04-01) group by `day`)'
        ];

        $response->assertExactJson($errorObject);
    }

    public function testErrorHandlingIncorrectFieldName()
    {
        $this->errorHandlingIncorrectFieldName('get');
        $this->errorHandlingIncorrectFieldName('post');
    }

    private function errorHandlingIncorrectStartDay($method)
    {
        $this->flushSession();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession(
                $this->getDefaultSessionValues() +
                [
                    RepoYssCampaignReportController::SESSION_KEY_START_DAY => 'testing',
                    RepoYssCampaignReportController::SESSION_KEY_END_DAY => '2017-01-05'
                ]
            )->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertStatus(500);

        $errorObject = [
            self::JSON_STATUS_CODE_FIELD_NAME => 500,
            self::JSON_ERROR_FIELD_NAME => 'DateTime::__construct(): Failed to parse time string (testing) '
                . 'at position 0 (t): The timezone could not be found in the database'
        ];

        $response->assertExactJson($errorObject);
    }

    public function testErrorHandlingIncorrectStartDay()
    {
        $this->errorHandlingIncorrectStartDay('get');
        $this->errorHandlingIncorrectStartDay('post');
    }

    private function errorHandlingIncorrectEndDay($method)
    {
        $this->flushSession();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession(
                $this->getDefaultSessionValues() +
                [
                    RepoYssCampaignReportController::SESSION_KEY_START_DAY => '2017-01-05',
                    RepoYssCampaignReportController::SESSION_KEY_END_DAY => 'testing'
                ]
            )->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertStatus(500);

        $errorObject = [
            self::JSON_STATUS_CODE_FIELD_NAME => 500,
            self::JSON_ERROR_FIELD_NAME => 'DateTime::__construct(): Failed to parse time string (testing) '
                . 'at position 0 (t): The timezone could not be found in the database'
        ];

        $response->assertExactJson($errorObject);
    }

    public function testErrorHandlingIncorrectEndDay()
    {
        $this->errorHandlingIncorrectEndDay('get');
        $this->errorHandlingIncorrectEndDay('post');
    }
}
