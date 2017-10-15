<?php

namespace Tests\Feature\RepoYssAdgroupReport;

use App\Http\Controllers\RepoYssAdgroupReport\RepoYssAdgroupReportController;
use App\User;

use Tests\TestCase;

use DateTime;

class GraphApiYssAdgroupReportTest extends TestCase
{
    const ROUTE_DISPLAY_GRAPH = '/adgroup-report/display-graph';
    const ROUTE_LOGIN = '/login';
    const CORRECT_DATA_90_DAYS = '{"data":['
        . '{"data":"1542069","day":"2017-01-01"},{"data":"2043237","day":"2017-01-02"},'
        . '{"data":"1782063","day":"2017-01-03"},{"data":"1402651","day":"2017-01-04"},'
        . '{"data":"1665502","day":"2017-01-05"},{"data":"1204499","day":"2017-01-06"},'
        . '{"data":"1783416","day":"2017-01-07"},{"data":"1602239","day":"2017-01-08"},'
        . '{"data":"1287318","day":"2017-01-09"},{"data":"1676964","day":"2017-01-10"},'
        . '{"data":"1958809","day":"2017-01-11"},{"data":"2174906","day":"2017-01-12"},'
        . '{"data":"2139790","day":"2017-01-13"},{"data":"2030924","day":"2017-01-14"},'
        . '{"data":"1609607","day":"2017-01-15"},{"data":"1606059","day":"2017-01-16"},'
        . '{"data":"1380615","day":"2017-01-17"},{"data":"2052917","day":"2017-01-18"},'
        . '{"data":"1247760","day":"2017-01-19"},{"data":"2230291","day":"2017-01-20"},'
        . '{"data":"1732932","day":"2017-01-21"},{"data":"2065755","day":"2017-01-22"},'
        . '{"data":"1336952","day":"2017-01-23"},{"data":"2049736","day":"2017-01-24"},'
        . '{"data":"1423377","day":"2017-01-25"},{"data":"1769292","day":"2017-01-26"},'
        . '{"data":"1602502","day":"2017-01-27"},{"data":"1588970","day":"2017-01-28"},'
        . '{"data":"2408652","day":"2017-01-29"},{"data":"1520671","day":"2017-01-30"},'
        . '{"data":"2274447","day":"2017-01-31"},{"data":"2038588","day":"2017-02-01"},'
        . '{"data":"1971571","day":"2017-02-02"},{"data":"1198870","day":"2017-02-03"},'
        . '{"data":"2296305","day":"2017-02-04"},{"data":"1114635","day":"2017-02-05"},'
        . '{"data":"1684433","day":"2017-02-06"},{"data":"2131268","day":"2017-02-07"},'
        . '{"data":"1560907","day":"2017-02-08"},{"data":"2511526","day":"2017-02-09"},'
        . '{"data":"1508227","day":"2017-02-10"},{"data":"1585407","day":"2017-02-11"},'
        . '{"data":"1469473","day":"2017-02-12"},{"data":"2062962","day":"2017-02-13"},'
        . '{"data":"1541081","day":"2017-02-14"},{"data":"1837701","day":"2017-02-15"},'
        . '{"data":"1909225","day":"2017-02-16"},{"data":"1487869","day":"2017-02-17"},'
        . '{"data":"1399324","day":"2017-02-18"},{"data":"1982677","day":"2017-02-19"},'
        . '{"data":"1758345","day":"2017-02-20"},{"data":"2248829","day":"2017-02-21"},'
        . '{"data":"1377324","day":"2017-02-22"},{"data":"2106607","day":"2017-02-23"},'
        . '{"data":"1914239","day":"2017-02-24"},{"data":"1639837","day":"2017-02-25"},'
        . '{"data":"1202855","day":"2017-02-26"},{"data":"1846064","day":"2017-02-27"},'
        . '{"data":"1604958","day":"2017-02-28"},{"data":"2182834","day":"2017-03-01"},'
        . '{"data":"1429311","day":"2017-03-02"},{"data":"1235451","day":"2017-03-03"},'
        . '{"data":"1215801","day":"2017-03-04"},{"data":"1498561","day":"2017-03-05"},'
        . '{"data":"1826142","day":"2017-03-06"},{"data":"2021841","day":"2017-03-07"},'
        . '{"data":"1796231","day":"2017-03-08"},{"data":"2722898","day":"2017-03-09"},'
        . '{"data":"1496162","day":"2017-03-10"},{"data":"1705815","day":"2017-03-11"},'
        . '{"data":"1643944","day":"2017-03-12"},{"data":"2165811","day":"2017-03-13"},'
        . '{"data":"1811309","day":"2017-03-14"},{"data":"1634932","day":"2017-03-15"},'
        . '{"data":"1900519","day":"2017-03-16"},{"data":"1318272","day":"2017-03-17"},'
        . '{"data":"1214704","day":"2017-03-18"},{"data":"1601421","day":"2017-03-19"},'
        . '{"data":"2131611","day":"2017-03-20"},{"data":"2063121","day":"2017-03-21"},'
        . '{"data":"1869779","day":"2017-03-22"},{"data":"2524090","day":"2017-03-23"},'
        . '{"data":"1601744","day":"2017-03-24"},{"data":"1572083","day":"2017-03-25"},'
        . '{"data":"1256493","day":"2017-03-26"},{"data":"1616048","day":"2017-03-27"},'
        . '{"data":"2238306","day":"2017-03-28"},{"data":"1507471","day":"2017-03-29"},'
        . '{"data":"1841819","day":"2017-03-30"},{"data":"1651261","day":"2017-03-31"}],'
        . '"field":"clicks","timePeriodLayout":"<span class=\"title\">Last 90 days<br>'
        . '<\/span>\n<span>2017-01-01 - 2017-04-01<\/span>\n<strong class=\"caret\">'
        . '<\/strong>\n","graphColumnLayout":"<span id=\"txtColumn\">clicks'
        . '<\/span>\n<strong class=\"caret selection\"><\/strong>",'
        . '"statusLayout":"<span>Show enabled\n<strong class=\"caret selection\">'
        . '<\/strong>\n<\/span>"}';
    const COLUMN_NAME_CAMPAIGN_NAME = 'adgroupName';
    const COLUMN_NAME_ADGROUP_BID = 'adGroupBid';
    const COLUMN_NAME_COST = 'cost';
    const COLUMN_NAME_IMPRESSIONS = 'impressions';
    const COLUMN_NAME_CLICKS = 'clicks';
    const COLUMN_NAME_CTR = 'ctr';
    const COLUMN_NAME_AVERAGE_CPC = 'averageCpc';
    const COLUMN_NAME_AVERAGE_POSITION = 'averagePosition';
    const COLUMN_NAME_IMPRESSION_SHARE = 'impressionShare';
    const COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE = 'exactMatchImpressionShare';
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
        1 => self::COLUMN_NAME_ADGROUP_BID,
        2 => self::COLUMN_NAME_COST,
        3 => self::COLUMN_NAME_IMPRESSIONS,
        4 => self::COLUMN_NAME_CLICKS,
        5 => self::COLUMN_NAME_CTR,
        6 => self::COLUMN_NAME_AVERAGE_CPC,
        7 => self::COLUMN_NAME_AVERAGE_POSITION,
        8 => self::COLUMN_NAME_IMPRESSION_SHARE,
        9 => self::COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE,
        10 => self::COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE,
        11 => self::COLUMN_NAME_CONVERSIONS,
        12 => self::COLUMN_NAME_CONV_RATE,
        13 => self::COLUMN_NAME_CONV_VALUE,
        14 => self::COLUMN_NAME_COST_PER_CONV,
        15 => self::COLUMN_NAME_VALUE_PER_CONV,
        16 => self::COLUMN_NAME_MOBILE_BID_ADJ,
        17 => self::COLUMN_NAME_DESKTOP_BID_ADJ,
        18 => self::COLUMN_NAME_TABLET_BID_ADJ
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
            RepoYssAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELD_NAMES,
            RepoYssAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_ACCOUNT_STATUS,
            RepoYssAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
            RepoYssAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_IMPRESSIONS,
            RepoYssAdgroupReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
            RepoYssAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE
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
        $this->actingAs($user)->get('/adgroup-report');
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
            RepoYssAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME,
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
            RepoYssAdgroupReportController::SESSION_KEY_STATUS_TITLE,
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
                RepoYssAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::COLUMN_NAME_IMPRESSIONS
            ])->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSessionHas(
            RepoYssAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME,
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
            RepoYssAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME,
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
            RepoYssAdgroupReportController::SESSION_KEY_STATUS_TITLE,
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
            RepoYssAdgroupReportController::SESSION_KEY_START_DAY => '2011-09-01',
            RepoYssAdgroupReportController::SESSION_KEY_END_DAY => '2011-10-01',
            RepoYssAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => 'someTitle'
        ]);
    }

    public function testDoesNotUpdateStartDayEndDayAndTimePeriodTitleWhenNotPosted()
    {
        $this->flushSession();

        $response = $this->actingAs($this->getUser())
            ->withSession([
                    RepoYssAdgroupReportController::SESSION_KEY_START_DAY => '2010-09-01',
                    RepoYssAdgroupReportController::SESSION_KEY_END_DAY => '2010-10-01',
                    RepoYssAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => 'someTimePeriodTitle'
            ])->post(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSessionHasAll([
            RepoYssAdgroupReportController::SESSION_KEY_START_DAY => '2010-09-01',
            RepoYssAdgroupReportController::SESSION_KEY_END_DAY => '2010-10-01',
            RepoYssAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => 'someTimePeriodTitle'
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
            RepoYssAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS,
            'someStatus'
        );
    }

    public function testDoesNotUpdateStatusWhenNotPosted()
    {
        $this->flushSession();

        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => 'aStatus'
            ])->post(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSessionHas(
            RepoYssAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS,
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
                    RepoYssAdgroupReportController::SESSION_KEY_START_DAY => '2017-01-01',
                    RepoYssAdgroupReportController::SESSION_KEY_END_DAY => '2017-04-01'
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
                    RepoYssAdgroupReportController::SESSION_KEY_START_DAY => self::DATE_FIRST_DAY_2016,
                    RepoYssAdgroupReportController::SESSION_KEY_END_DAY => self::DATE_FIRST_DAY_2016,
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
                    RepoYssAdgroupReportController::SESSION_KEY_START_DAY => self::DATE_FIRST_DAY_2016,
                    RepoYssAdgroupReportController::SESSION_KEY_END_DAY => '2016-02-01'
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
                    RepoYssAdgroupReportController::SESSION_KEY_START_DAY => '2017-01-01',
                    RepoYssAdgroupReportController::SESSION_KEY_END_DAY => '2017-04-01',
                    RepoYssAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => 'someNonExistingColumnName'
                ]
            )->$method(self::ROUTE_DISPLAY_GRAPH);
        $response->assertStatus(500);

        $errorObject = [
            self::JSON_STATUS_CODE_FIELD_NAME => 500,
            self::JSON_ERROR_FIELD_NAME => 'SQLSTATE[42S22]: Column not found: 1054 Unknown column \''
            . 'someNonExistingColumnName\' in \'field list\' (SQL: select SUM(someNonExistingColumnName)'
            . ' as data, DATE(day) as day from `repo_yss_adgroup_report_cost` where (date(`day`) >= '
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
                    RepoYssAdgroupReportController::SESSION_KEY_START_DAY => 'testing',
                    RepoYssAdgroupReportController::SESSION_KEY_END_DAY => '2017-01-05'
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
                    RepoYssAdgroupReportController::SESSION_KEY_START_DAY => '2017-01-05',
                    RepoYssAdgroupReportController::SESSION_KEY_END_DAY => 'testing'
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
