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
        . '{"data":"546413","day":"2017-01-01"},{"data":"665271","day":"2017-01-02"},'
        . '{"data":"607610","day":"2017-01-03"},{"data":"454735","day":"2017-01-04"},'
        . '{"data":"491470","day":"2017-01-05"},{"data":"436586","day":"2017-01-06"},'
        . '{"data":"578218","day":"2017-01-07"},{"data":"606411","day":"2017-01-08"},'
        . '{"data":"431685","day":"2017-01-09"},{"data":"616937","day":"2017-01-10"},'
        . '{"data":"682869","day":"2017-01-11"},{"data":"708992","day":"2017-01-12"},'
        . '{"data":"760733","day":"2017-01-13"},{"data":"659647","day":"2017-01-14"},'
        . '{"data":"514740","day":"2017-01-15"},{"data":"504130","day":"2017-01-16"},'
        . '{"data":"461402","day":"2017-01-17"},{"data":"657594","day":"2017-01-18"},'
        . '{"data":"456231","day":"2017-01-19"},{"data":"671600","day":"2017-01-20"},'
        . '{"data":"630985","day":"2017-01-21"},{"data":"621801","day":"2017-01-22"},'
        . '{"data":"421205","day":"2017-01-23"},{"data":"674457","day":"2017-01-24"},'
        . '{"data":"470217","day":"2017-01-25"},{"data":"576869","day":"2017-01-26"},'
        . '{"data":"531274","day":"2017-01-27"},{"data":"533527","day":"2017-01-28"},'
        . '{"data":"778800","day":"2017-01-29"},{"data":"524559","day":"2017-01-30"},'
        . '{"data":"749151","day":"2017-01-31"},{"data":"587117","day":"2017-02-01"},'
        . '{"data":"709808","day":"2017-02-02"},{"data":"392408","day":"2017-02-03"},'
        . '{"data":"666931","day":"2017-02-04"},{"data":"392297","day":"2017-02-05"},'
        . '{"data":"552017","day":"2017-02-06"},{"data":"756597","day":"2017-02-07"},'
        . '{"data":"505740","day":"2017-02-08"},{"data":"875906","day":"2017-02-09"},'
        . '{"data":"541887","day":"2017-02-10"},{"data":"448062","day":"2017-02-11"},'
        . '{"data":"489908","day":"2017-02-12"},{"data":"653921","day":"2017-02-13"},'
        . '{"data":"552516","day":"2017-02-14"},{"data":"636120","day":"2017-02-15"},'
        . '{"data":"620387","day":"2017-02-16"},{"data":"527871","day":"2017-02-17"},'
        . '{"data":"460687","day":"2017-02-18"},{"data":"645859","day":"2017-02-19"},'
        . '{"data":"588841","day":"2017-02-20"},{"data":"727503","day":"2017-02-21"},'
        . '{"data":"472778","day":"2017-02-22"},{"data":"667837","day":"2017-02-23"},'
        . '{"data":"646020","day":"2017-02-24"},{"data":"505709","day":"2017-02-25"},'
        . '{"data":"389870","day":"2017-02-26"},{"data":"634650","day":"2017-02-27"},'
        . '{"data":"521696","day":"2017-02-28"},{"data":"741456","day":"2017-03-01"},'
        . '{"data":"521756","day":"2017-03-02"},{"data":"443107","day":"2017-03-03"},'
        . '{"data":"393207","day":"2017-03-04"},{"data":"574124","day":"2017-03-05"},'
        . '{"data":"556384","day":"2017-03-06"},{"data":"608985","day":"2017-03-07"},'
        . '{"data":"620668","day":"2017-03-08"},{"data":"894411","day":"2017-03-09"},'
        . '{"data":"484760","day":"2017-03-10"},{"data":"583661","day":"2017-03-11"},'
        . '{"data":"559553","day":"2017-03-12"},{"data":"698200","day":"2017-03-13"},'
        . '{"data":"701495","day":"2017-03-14"},{"data":"530714","day":"2017-03-15"},'
        . '{"data":"598192","day":"2017-03-16"},{"data":"486195","day":"2017-03-17"},'
        . '{"data":"394578","day":"2017-03-18"},{"data":"607029","day":"2017-03-19"},'
        . '{"data":"741667","day":"2017-03-20"},{"data":"685245","day":"2017-03-21"},'
        . '{"data":"561000","day":"2017-03-22"},{"data":"803338","day":"2017-03-23"},'
        . '{"data":"562925","day":"2017-03-24"},{"data":"598775","day":"2017-03-25"},'
        . '{"data":"428837","day":"2017-03-26"},{"data":"559411","day":"2017-03-27"},'
        . '{"data":"709522","day":"2017-03-28"},{"data":"556188","day":"2017-03-29"},'
        . '{"data":"598717","day":"2017-03-30"},{"data":"538698","day":"2017-03-31"},'
        . '{"data":"477767","day":"2017-04-01"}],'
        . '"field":"clicks","timePeriodLayout":"<span class=\"title\">Last 90 days<br>'
        . '<\/span>\n<span>2017-01-01 - 2017-04-01<\/span>\n<strong class=\"caret\">'
        . '<\/strong>\n","graphColumnLayout":"<span id=\"txtColumn\">clicks<\/span>\n'
        . '<strong class=\"caret selection\"><\/strong>","statusLayout":"<span>Show enabled\n'
        . '<strong class=\"caret selection\"><\/strong>\n<\/span>"}';
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
            'Hide 0'
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
            "data" => [
                ["data" => 0, "day" => self::DATE_FIRST_DAY_2016]
            ],
            "field" => "clicks",
            "graphColumnLayout" => "<span id=\"txtColumn\">clicks</span>\n"
                . "<strong class=\"caret selection\"></strong>",
            "statusLayout" => "<span>Show enabled\n<strong class=\"caret selection\"></strong>\n</span>",
            "timePeriodLayout" => "<span class=\"title\">Last 90 days<br></span>\n"
                . "<span>2016-01-01 - 2016-01-01</span>\n<strong class=\"caret\"></strong>\n"
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
            "data" => [
                ["data" => 0, "day" => "2016-01-01"], ["data" => 0,"day" => "2016-02-01"]
            ],
            "field" => "clicks",
            "graphColumnLayout" => "<span id=\"txtColumn\">clicks</span>\n"
                . "<strong class=\"caret selection\"></strong>",
            "statusLayout" => "<span>Show enabled\n"
                . "<strong class=\"caret selection\"></strong>\n</span>",
            "timePeriodLayout" => "<span class=\"title\">Last 90 days<br></span>\n"
                . "<span>2016-01-01 - 2016-02-01</span>\n"
                . "<strong class=\"caret\"></strong>\n"
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
            . '2017-01-01 and date(`day`) <= 2017-04-01) group by `day`)'
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
