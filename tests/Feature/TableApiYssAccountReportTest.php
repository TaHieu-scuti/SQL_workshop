<?php

namespace Tests\Feature;

use App\Http\Controllers\RepoYssAccountReport\RepoYssAccountReportController;
use App\User;

use Tests\TestCase;

use DateTime;

class TableApiYssAccountReportTest extends TestCase
{
    const ROUTE_ACCOUNT_REPORT = '/account_report';
    const COLUMN_NAME_IMPRESSIONS = 'impressions';

    const DEFAULT_FIELDS = [
        1  => 'account_id',
        3  => 'cost',
        4  => self::COLUMN_NAME_IMPRESSIONS,
        5  => 'clicks',
        6  => 'ctr',
        7  => 'averageCpc',
        8  => 'averagePosition',
        9  => 'invalidClicks',
        10 => 'invalidClickRate',
        11 => 'impressionShare',
        12 => 'exactMatchImpressionShare',
        13 => 'budgetLostImpressionShare',
        14 => 'qualityLostImpressionShare',
        15 => 'trackingURL',
        16 => 'conversions',
        17 => 'convRate',
        18 => 'convValue',
        19 => 'costPerConv',
        20 => 'valuePerConv',
        21 => 'allConv',
        22 => 'allConvRate',
        23 => 'allConvValue',
        24 => 'costPerAllConv',
        25 => 'valuePerAllConv',
        26 => 'network',
        27 => 'device',
        28 => 'day',
        29 => 'dayOfWeek',
        30 => 'quarter',
        31 => 'month',
        32 => 'week'
    ];

    const CUSTOM_FIELDS = [
        1  => 'account_id',
        3  => 'cost',
        4  => self::COLUMN_NAME_IMPRESSIONS,
        5  => 'clicks',
        6  => 'ctr',
        7  => 'averageCpc',
        8  => 'averagePosition',
        16 => 'conversions',
        17 => 'convRate',
        18 => 'convValue',
        19 => 'costPerConv',
        20 => 'valuePerConv',
        21 => 'allConv',
        22 => 'allConvRate',
        23 => 'allConvValue',
        24 => 'costPerAllConv',
        25 => 'valuePerAllConv',
        26 => 'network',
        27 => 'device',
        28 => 'day',
        29 => 'dayOfWeek',
        30 => 'quarter',
        31 => 'month',
        32 => 'week'
    ];

    const DEFAULT_STATUS = 'enabled';
    const CUSTOM_STATUS = 'someStatus';
    const DEFAULT_TIME_PERIOD_TITLE = 'Last 90 days';
    const CUSTOM_TIME_PERIOD_TITLE = 'someTitle';
    const CUSTOM_START_DAY = '2017-01-01';
    const CUSTOM_END_DAY = '2017-02-03';
    const DEFAULT_PAGINATION = 20;
    const CUSTOM_PAGINATION = 21;
    const DEFAULT_COLUMN_SORT = self::COLUMN_NAME_IMPRESSIONS;
    const CUSTOM_COLUMN_SORT = 'someColumnName';
    const DEFAULT_SORT = 'desc';
    const CUSTOM_SORT = 'someSortOption';
    const VIEW_PATH = 'yssAccountReport.index';

    /**
     * @return \App\User
     */
    private function getUser()
    {
        return (new User)->find(1)->first();
    }

    public function setUp()
    {
        parent::setUp();
        $this->flushSession();
    }

    public function testRedirectsToLoginRouteWhenNotLoggedIn()
    {
        $response = $this->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertRedirect('/login');
    }

    public function testReturns200StatusWhenLoggedIn()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertStatus(200);
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultColumnsAreStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_FIELD_NAME,
            self::DEFAULT_FIELDS
        );
    }

    public function testWhenSessionDataIsAvailableTheColumnsInTheSessionAreNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssAccountReportController::SESSION_KEY_FIELD_NAME => self::CUSTOM_FIELDS
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_FIELD_NAME,
            self::CUSTOM_FIELDS
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultStatusIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_ACCOUNT_STATUS,
            self::DEFAULT_STATUS
        );
    }

    public function testWhenSessionDataIsAvailableTheStatusInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::CUSTOM_STATUS
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_ACCOUNT_STATUS,
            self::CUSTOM_STATUS
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultTimePeriodTitleIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE,
            self::DEFAULT_TIME_PERIOD_TITLE
        );
    }

    public function testWhenSessionDataIsAvailableTheTimePeriodTitleInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE =>
                    self::CUSTOM_TIME_PERIOD_TITLE
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE,
            self::CUSTOM_TIME_PERIOD_TITLE
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultStartDayIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $startDay = (new DateTime)->modify('-90 days')->format('Y-m-d');

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_START_DAY,
            $startDay
        );
    }

    public function testWhenSessionDataIsAvailableTheStartDayInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssAccountReportController::SESSION_KEY_START_DAY => self::CUSTOM_START_DAY
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_START_DAY,
            self::CUSTOM_START_DAY
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultEndDayIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $endDay = (new DateTime)->format('Y-m-d');

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_END_DAY,
            $endDay
        );
    }

    public function testWhenSessionDataIsAvailableTheEndDayInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssAccountReportController::SESSION_KEY_END_DAY => self::CUSTOM_END_DAY
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_END_DAY,
            self::CUSTOM_END_DAY
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultPaginationValueIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_PAGINATION,
            self::DEFAULT_PAGINATION
        );
    }

    public function testWhenSessionDataIsAvailableThePaginationValueStoredInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssAccountReportController::SESSION_KEY_PAGINATION => self::CUSTOM_PAGINATION
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_PAGINATION,
            self::CUSTOM_PAGINATION
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultColumnSortValueIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_COLUMN_SORT,
            self::DEFAULT_COLUMN_SORT
        );
    }

    public function testWhenSessionDataIsAvailableTheColumnSortValueStoredInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssAccountReportController::SESSION_KEY_COLUMN_SORT => self::CUSTOM_COLUMN_SORT
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_COLUMN_SORT,
            self::CUSTOM_COLUMN_SORT
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultSortValueIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_SORT,
            self::DEFAULT_SORT
        );
    }

    public function testWhenSessionDataIsAvailableTheSortValueStoredInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoYssAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoYssAccountReportController::SESSION_KEY_SORT,
            self::CUSTOM_SORT
        );
    }

    public function testReturnsCorrectView()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewIs(self::VIEW_PATH);
    }
}
