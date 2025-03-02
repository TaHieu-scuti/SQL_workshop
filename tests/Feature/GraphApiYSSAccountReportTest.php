<?php

namespace Tests\Feature;

use App\Http\Controllers\RepoAccountReport\RepoAccountReportController;
use App\User;

use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class GraphApiYSSAccountReportTest extends TestCase
{
    const ROUTE_DISPLAY_GRAPH = 'account_report/display-graph';
    const ROUTE_LOGIN = '/login';
    const CORRECT_DATA_90_DAYS = '{"data":['
        . '{"data":"553148","day":"2017-01-01"},{"data":"707527","day":"2017-01-02"},'
        . '{"data":"582703","day":"2017-01-03"},{"data":"481094","day":"2017-01-04"},'
        . '{"data":"532685","day":"2017-01-05"},{"data":"432597","day":"2017-01-06"},'
        . '{"data":"582879","day":"2017-01-07"},{"data":"547004","day":"2017-01-08"},'
        . '{"data":"432158","day":"2017-01-09"},{"data":"585913","day":"2017-01-10"},'
        . '{"data":"673137","day":"2017-01-11"},{"data":"695529","day":"2017-01-12"},'
        . '{"data":"685347","day":"2017-01-13"},{"data":"603064","day":"2017-01-14"},'
        . '{"data":"515710","day":"2017-01-15"},{"data":"500877","day":"2017-01-16"},'
        . '{"data":"440290","day":"2017-01-17"},{"data":"687509","day":"2017-01-18"},'
        . '{"data":"446964","day":"2017-01-19"},{"data":"690972","day":"2017-01-20"},'
        . '{"data":"544482","day":"2017-01-21"},{"data":"699122","day":"2017-01-22"},'
        . '{"data":"423855","day":"2017-01-23"},{"data":"698564","day":"2017-01-24"},'
        . '{"data":"497049","day":"2017-01-25"},{"data":"595418","day":"2017-01-26"},'
        . '{"data":"588094","day":"2017-01-27"},{"data":"503637","day":"2017-01-28"},'
        . '{"data":"821562","day":"2017-01-29"},{"data":"500287","day":"2017-01-30"},'
        . '{"data":"775881","day":"2017-01-31"},{"data":"721318","day":"2017-02-01"},'
        . '{"data":"613325","day":"2017-02-02"},{"data":"409485","day":"2017-02-03"},'
        . '{"data":"777195","day":"2017-02-04"},{"data":"427746","day":"2017-02-05"},'
        . '{"data":"586813","day":"2017-02-06"},{"data":"758686","day":"2017-02-07"},'
        . '{"data":"536509","day":"2017-02-08"},{"data":"854312","day":"2017-02-09"},'
        . '{"data":"509539","day":"2017-02-10"},{"data":"493119","day":"2017-02-11"},'
        . '{"data":"498485","day":"2017-02-12"},{"data":"665407","day":"2017-02-13"},'
        . '{"data":"515300","day":"2017-02-14"},{"data":"584155","day":"2017-02-15"},'
        . '{"data":"635584","day":"2017-02-16"},{"data":"479773","day":"2017-02-17"},'
        . '{"data":"461065","day":"2017-02-18"},{"data":"689576","day":"2017-02-19"},'
        . '{"data":"580992","day":"2017-02-20"},{"data":"746309","day":"2017-02-21"},'
        . '{"data":"465680","day":"2017-02-22"},{"data":"707111","day":"2017-02-23"},'
        . '{"data":"644201","day":"2017-02-24"},{"data":"499899","day":"2017-02-25"},'
        . '{"data":"396990","day":"2017-02-26"},{"data":"592452","day":"2017-02-27"},'
        . '{"data":"511997","day":"2017-02-28"},{"data":"744332","day":"2017-03-01"},'
        . '{"data":"490189","day":"2017-03-02"},{"data":"445314","day":"2017-03-03"},'
        . '{"data":"470192","day":"2017-03-04"},{"data":"534625","day":"2017-03-05"},'
        . '{"data":"586651","day":"2017-03-06"},{"data":"609259","day":"2017-03-07"},'
        . '{"data":"601890","day":"2017-03-08"},{"data":"971454","day":"2017-03-09"},'
        . '{"data":"510416","day":"2017-03-10"},{"data":"606732","day":"2017-03-11"},'
        . '{"data":"526233","day":"2017-03-12"},{"data":"628797","day":"2017-03-13"},'
        . '{"data":"697533","day":"2017-03-14"},{"data":"570822","day":"2017-03-15"},'
        . '{"data":"662077","day":"2017-03-16"},{"data":"438592","day":"2017-03-17"},'
        . '{"data":"373831","day":"2017-03-18"},{"data":"584256","day":"2017-03-19"},'
        . '{"data":"757148","day":"2017-03-20"},{"data":"645527","day":"2017-03-21"},'
        . '{"data":"597585","day":"2017-03-22"},{"data":"821855","day":"2017-03-23"},'
        . '{"data":"492603","day":"2017-03-24"},{"data":"528918","day":"2017-03-25"},'
        . '{"data":"445791","day":"2017-03-26"},{"data":"523383","day":"2017-03-27"},'
        . '{"data":"723413","day":"2017-03-28"},{"data":"501060","day":"2017-03-29"},'
        . '{"data":"674264","day":"2017-03-30"},{"data":"532990","day":"2017-03-31"},'
        . '{"data":"464408","day":"2017-04-01"}],"field":"clicks","timePeriodLayout":'
        . '"<span class=\"title\">Last 90 days<br><\/span>\n<span>2017-01-01 - 2017-04-01<\/span>\n'
        . '<strong class=\"caret\"><\/strong>\n","graphColumnLayout":"'
        . '<span id=\"txtColumn\">clicks<\/span>\n<strong class=\"caret selection\">'
        . '<\/strong>","statusLayout":"<span>Hide 0\n<strong class=\"caret selection\">'
        . '<\/strong>\n<\/span>","displayNoDataFoundMessageOnGraph":false}';

    const DEFAULT_FIELD_NAMES = [
        3 => "cost",
        4 => "impressions",
        5 => "clicks",
        6 => "ctr",
        7 => "averageCpc",
        8 => "averagePosition",
        9 => "invalidClicks",
        10 => "invalidClickRate",
        11 => "impressionShare",
        12 => "exactMatchImpressionShare",
        13 => "budgetLostImpressionShare",
        14 => "qualityLostImpressionShare",
        15 => "trackingURL",
        16 => "conversions",
        17 => "convRate",
        18 => "convValue",
        19 => "costPerConv",
        20 => "valuePerConv",
        21 => "allConv",
        22 => "allConvRate",
        23 => "allConvValue",
        24 => "costPerAllConv",
        25 => "valuePerAllConv",
        26 => "network",
        27 => "device",
        28 => "day",
        29 => "dayOfWeek",
        30 => "quarter",
        31 => "month",
        32 => "week"
    ];

    const DEFAULT_ACCOUNT_STATUS = 'hideZero';
    const DEFAULT_STATUS_TITLE = 'Hide 0';
    const DEFAULT_PAGINATION = 20;
    const DEFAULT_SORT = 'desc';
    const DATE_FIRST_DAY_2016 = '2016-01-01';
    const JSON_ERROR_FIELD_NAME = 'error';
    const JSON_STATUS_CODE_FIELD_NAME = 'code';

    const COLUMN_NAME_IMPRESSIONS = 'impressions';

    private function getUser()
    {
        return (new User)->find(1)->first();
    }

    private function getDefaultSessionValues()
    {
        return [
            RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELD_NAMES,
            RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_ACCOUNT_STATUS,
            RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
            RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_IMPRESSIONS,
            RepoAccountReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
            RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE
        ];
    }

    private function displayGraphRouteRedirectsToLoginRouteWhenNotLoggedIn($method)
    {
        /** @var TestResponse $response */
        $response = $this->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertRedirect(self::ROUTE_LOGIN);
    }

    public function getUserAndAccessToAccountReport()
    {
        $user = $this->getUser();
        $this->actingAs($user)->get('/account_report');
    }

    public function testDisplayGraphRouteRedirectsToLoginRouteWhenNotLoggedIn()
    {
        $this->displayGraphRouteRedirectsToLoginRouteWhenNotLoggedIn('get');
        $this->displayGraphRouteRedirectsToLoginRouteWhenNotLoggedIn('post');
    }

    private function returns200StatusWhenLoggedIn($method)
    {
        $this->getUserAndAccessToAccountReport();
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
        $this->getUserAndAccessToAccountReport();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSuccessful();

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME,
            'clicks'
        );
    }

    public function testGraphColumnNameInSessionIsSetToClicksAsDefaultValue()
    {
        $this->graphColumnNameInSessionIsSetToClicksAsDefaultValue('get');
        $this->graphColumnNameInSessionIsSetToClicksAsDefaultValue('post');
    }

    private function statusTitleInSessionIsSetToHideZeroAsDefaultValue($method)
    {
        $this->getUserAndAccessToAccountReport();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSuccessful();

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_STATUS_TITLE,
            'Hide 0'
        );
    }
    
    public function testStatusTitleInSessionIsSetToHideZeroAsDefaultValue()
    {
        $this->statusTitleInSessionIsSetToHideZeroAsDefaultValue('get');
        $this->statusTitleInSessionIsSetToHideZeroAsDefaultValue('post');
    }

    private function doesNotSetGraphColumnNameToDefaultValueClicksWhenItIsAlreadySet($method)
    {
        $this->flushSession();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::COLUMN_NAME_IMPRESSIONS
            ])->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME,
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
            RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME,
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
            RepoAccountReportController::SESSION_KEY_STATUS_TITLE,
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
            RepoAccountReportController::SESSION_KEY_START_DAY => '2011-09-01',
            RepoAccountReportController::SESSION_KEY_END_DAY => '2011-10-01',
            RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => 'someTitle'
        ]);
    }

    public function testDoesNotUpdateStartDayEndDayAndTimePeriodTitleWhenNotPosted()
    {
        $this->flushSession();

        $response = $this->actingAs($this->getUser())
            ->withSession([
                    RepoAccountReportController::SESSION_KEY_START_DAY => '2010-09-01',
                    RepoAccountReportController::SESSION_KEY_END_DAY => '2010-10-01',
                    RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => 'someTimePeriodTitle'
            ])->post(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSessionHasAll([
            RepoAccountReportController::SESSION_KEY_START_DAY => '2010-09-01',
            RepoAccountReportController::SESSION_KEY_END_DAY => '2010-10-01',
            RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => 'someTimePeriodTitle'
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
            RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS,
            'someStatus'
        );
    }

    public function testDoesNotUpdateStatusWhenNotPosted()
    {
        $this->flushSession();

        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => 'aStatus'
            ])->post(self::ROUTE_DISPLAY_GRAPH);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS,
            'aStatus'
        );
    }

    private function returnsCorrectDataFor90Days($method)
    {
        $this->getUserAndAccessToAccountReport();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession(
                $this->getDefaultSessionValues() +
                [
                    RepoAccountReportController::SESSION_KEY_START_DAY => '2017-01-01',
                    RepoAccountReportController::SESSION_KEY_END_DAY => '2017-04-01'
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
        $this->getUserAndAccessToAccountReport();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession(
                $this->getDefaultSessionValues() +
                [
                    RepoAccountReportController::SESSION_KEY_START_DAY => self::DATE_FIRST_DAY_2016,
                    RepoAccountReportController::SESSION_KEY_END_DAY => self::DATE_FIRST_DAY_2016,
                ]
            )->$method(self::ROUTE_DISPLAY_GRAPH);
        $object = [
            'data' => [
                ['data' => null, 'day' => self::DATE_FIRST_DAY_2016]
            ],
            'displayNoDataFoundMessageOnGraph'=>true,
            'field' => 'clicks',
            'timePeriodLayout' => "<span class=\"title\">Last 90 days<br></span>\n"
                . "<span>2016-01-01 - 2016-01-01</span>\n<strong class=\"caret\"></strong>\n",
            'graphColumnLayout' => "<span id=\"txtColumn\">clicks</span>\n"
                ."<strong class=\"caret selection\"></strong>",
            'statusLayout' => "<span>Hide 0\n"
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
        $this->getUserAndAccessToAccountReport();

        /** @var TestResponse $response */
        $response = $this->actingAs($this->getUser())
            ->withSession(
                $this->getDefaultSessionValues() +
                [
                    RepoAccountReportController::SESSION_KEY_START_DAY => self::DATE_FIRST_DAY_2016,
                    RepoAccountReportController::SESSION_KEY_END_DAY => '2016-02-01'
                ]
            )->$method(self::ROUTE_DISPLAY_GRAPH);

        $object = [
            'data' => [
                ['data' => null, 'day' => '2016-01-01'], ['data' => null, 'day' => '2016-02-01']
            ],
            'displayNoDataFoundMessageOnGraph'=>true,
            'field' => 'clicks',
            'graphColumnLayout' => "<span id=\"txtColumn\">clicks</span>\n"
                ."<strong class=\"caret selection\"></strong>",
            'statusLayout' => "<span>Hide 0\n"
                ."<strong class=\"caret selection\"></strong>\n"
                ."</span>",
            'timePeriodLayout' => "<span class=\"title\">Last 90 days<br></span>\n"
                . "<span>2016-01-01 - 2016-02-01</span>\n<strong class=\"caret\"></strong>\n"
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
                    RepoAccountReportController::SESSION_KEY_START_DAY => '2017-01-01',
                    RepoAccountReportController::SESSION_KEY_END_DAY => '2017-04-01',
                    RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => 'someNonExistingColumnName'
                ]
            )->$method(self::ROUTE_DISPLAY_GRAPH);

        $response->assertStatus(500);

        $errorObject = [
            self::JSON_STATUS_CODE_FIELD_NAME => 500,
            self::JSON_ERROR_FIELD_NAME => 'SQLSTATE[42S22]: Column not found: '
                . '1054 Unknown column \'someNonExistingColumnName\' in \'field list\' (SQL'
                . ': select SUM(someNonExistingColumnName) as data, DATE(day) as day from `'
                . 'repo_yss_account_report` inner join `repo_yss_accounts` on `repo_yss_acc'
                . 'ount_report`.`account_id` = `repo_yss_accounts`.`account_id` where (date'
                . '(`day`) >= 2017-01-01 and date(`day`) <= 2017-04-01) group by `day` '
                . 'having SUM(impressions) != 0)'
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
                    RepoAccountReportController::SESSION_KEY_START_DAY => 'testing',
                    RepoAccountReportController::SESSION_KEY_END_DAY => '2017-01-05'
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
                    RepoAccountReportController::SESSION_KEY_START_DAY => '2017-01-05',
                    RepoAccountReportController::SESSION_KEY_END_DAY => 'testing'
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
