<?php

namespace Tests\Feature;

use App\Http\Controllers\RepoAccountReport\RepoAccountReportController;
use App\User;

use Tests\TestCase;

use DateTime;

class TableApiYssAccountReportTest extends TestCase
{
    const ROUTE_ACCOUNT_REPORT = '/account_report';
    const COLUMN_NAME_ACCOUNT_NAME = 'accountName';
    const COLUMN_NAME_COST = 'cost';
    const COLUMN_NAME_IMPRESSIONS = 'impressions';
    const COLUMN_NAME_CLICKS = 'clicks';
    const COLUMN_NAME_AVERAGE_CPC = 'averageCpc';
    const COLUMN_NAME_AVERAGE_POSITION = 'averagePosition';
    const COLUMN_NAME_INVALID_CLICKS = 'invalidClicks';
    const COLUMN_NAME_INVALID_CLICK_RATE = 'invalidClickRate';
    const COLUMN_NAME_IMPRESSION_SHARE = 'impressionShare';
    const COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE = 'exactMatchImpressionShare';
    const COLUMN_NAME_BUDGET_LOST_IMPRESSION_SHARE = 'budgetLostImpressionShare';
    const COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE = 'qualityLostImpressionShare';
    const COLUMN_NAME_CONVERSIONS = 'conversions';
    const COLUMN_NAME_CONV_RATE = 'convRate';
    const COLUMN_NAME_CONV_VALUE = 'convValue';
    const COLUMN_NAME_COST_PER_CONV = 'costPerConv';
    const COLUMN_NAME_VALUE_PER_CONV = 'valuePerConv';
    const COLUMN_NAME_ALL_CONV = 'allConv';
    const COLUMN_NAME_ALL_CONV_RATE = 'allConvRate';
    const COLUMN_NAME_ALL_CONV_VALUE = 'allConvValue';
    const COLUMN_NAME_COST_PER_ALL_CONV = 'costPerAllConv';
    const COLUMN_NAME_VALUE_PER_ALL_CONV = 'valuePerAllConv';

    const DEFAULT_FIELDS = [
        0  => self::COLUMN_NAME_ACCOUNT_NAME,
        1  => self::COLUMN_NAME_COST,
        2  => self::COLUMN_NAME_IMPRESSIONS,
        3  => self::COLUMN_NAME_CLICKS,
        4  => self::COLUMN_NAME_AVERAGE_CPC,
        5  => self::COLUMN_NAME_INVALID_CLICKS,
        6 => self::COLUMN_NAME_INVALID_CLICK_RATE,
        7 => self::COLUMN_NAME_IMPRESSION_SHARE,
        8 => self::COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE,
        9 => self::COLUMN_NAME_BUDGET_LOST_IMPRESSION_SHARE,
        10 => self::COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE,
        11 => self::COLUMN_NAME_CONVERSIONS,
        12 => self::COLUMN_NAME_CONV_RATE,
        13 => self::COLUMN_NAME_CONV_VALUE,
        14 => self::COLUMN_NAME_COST_PER_CONV,
        15 => self::COLUMN_NAME_VALUE_PER_CONV,
        16 => self::COLUMN_NAME_ALL_CONV,
        17 => self::COLUMN_NAME_ALL_CONV_RATE,
        18 => self::COLUMN_NAME_ALL_CONV_VALUE,
        19 => self::COLUMN_NAME_COST_PER_ALL_CONV,
        20 => self::COLUMN_NAME_VALUE_PER_ALL_CONV,
    ];

    const CUSTOM_FIELDS = [
        0  => self::COLUMN_NAME_ACCOUNT_NAME,
        1  => self::COLUMN_NAME_COST,
        2  => self::COLUMN_NAME_IMPRESSIONS,
        3  => self::COLUMN_NAME_CLICKS,
        4  => self::COLUMN_NAME_AVERAGE_CPC,
        5  => self::COLUMN_NAME_INVALID_CLICKS,
        6 => self::COLUMN_NAME_INVALID_CLICK_RATE,
        7 => self::COLUMN_NAME_IMPRESSION_SHARE,
        8 => self::COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE,
        9 => self::COLUMN_NAME_BUDGET_LOST_IMPRESSION_SHARE,
        10 => self::COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE,
        11 => self::COLUMN_NAME_CONVERSIONS,
        12 => self::COLUMN_NAME_CONV_RATE,
        13 => self::COLUMN_NAME_CONV_VALUE,
        14 => self::COLUMN_NAME_COST_PER_CONV,
        15 => self::COLUMN_NAME_VALUE_PER_CONV,
        16 => self::COLUMN_NAME_ALL_CONV,
        17 => self::COLUMN_NAME_ALL_CONV_RATE,
        18 => self::COLUMN_NAME_ALL_CONV_VALUE,
        19 => self::COLUMN_NAME_COST_PER_ALL_CONV,
        20 => self::COLUMN_NAME_VALUE_PER_ALL_CONV,
    ];

    const LIVE_SEARCH_FIELDS = [
        3  => self::COLUMN_NAME_COST,
        4  => self::COLUMN_NAME_IMPRESSIONS,
        5  => self::COLUMN_NAME_CLICKS,
        7  => self::COLUMN_NAME_AVERAGE_CPC,
        9  => self::COLUMN_NAME_INVALID_CLICKS,
        10 => self::COLUMN_NAME_INVALID_CLICK_RATE,
        11 => self::COLUMN_NAME_IMPRESSION_SHARE,
        12 => self::COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE,
        13 => self::COLUMN_NAME_BUDGET_LOST_IMPRESSION_SHARE,
        14 => self::COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE,
        16 => self::COLUMN_NAME_CONVERSIONS,
        17 => self::COLUMN_NAME_CONV_RATE,
        18 => self::COLUMN_NAME_CONV_VALUE,
        19 => self::COLUMN_NAME_COST_PER_CONV,
        20 => self::COLUMN_NAME_VALUE_PER_CONV,
        21 => self::COLUMN_NAME_ALL_CONV,
        22 => self::COLUMN_NAME_ALL_CONV_RATE,
        23 => self::COLUMN_NAME_ALL_CONV_VALUE,
        24 => self::COLUMN_NAME_COST_PER_ALL_CONV,
        25 => self::COLUMN_NAME_VALUE_PER_ALL_CONV,
    ];

    const COLUMNS_FOR_FILTER = [
        3  => self::COLUMN_NAME_COST,
        4  => self::COLUMN_NAME_IMPRESSIONS,
        5  => self::COLUMN_NAME_CLICKS,
        7  => self::COLUMN_NAME_AVERAGE_CPC,
        9  => self::COLUMN_NAME_INVALID_CLICKS,
        10 => self::COLUMN_NAME_INVALID_CLICK_RATE,
        11 => self::COLUMN_NAME_IMPRESSION_SHARE,
        12 => self::COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE,
        13 => self::COLUMN_NAME_BUDGET_LOST_IMPRESSION_SHARE,
        14 => self::COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE,
        16 => self::COLUMN_NAME_CONVERSIONS,
        17 => self::COLUMN_NAME_CONV_RATE,
        18 => self::COLUMN_NAME_CONV_VALUE,
        19 => self::COLUMN_NAME_COST_PER_CONV,
        20 => self::COLUMN_NAME_VALUE_PER_CONV,
        21 => self::COLUMN_NAME_ALL_CONV,
        22 => self::COLUMN_NAME_ALL_CONV_RATE,
        23 => self::COLUMN_NAME_ALL_CONV_VALUE,
        24 => self::COLUMN_NAME_COST_PER_ALL_CONV,
        25 => self::COLUMN_NAME_VALUE_PER_ALL_CONV,
    ];

    const SUMMARY_REPORT = [
        0 => self::COLUMN_NAME_CLICKS,
        1 => self::COLUMN_NAME_IMPRESSIONS,
        2 => self::COLUMN_NAME_COST,
        3 => self::COLUMN_NAME_AVERAGE_CPC,
        4 => self::COLUMN_NAME_AVERAGE_POSITION,
        5 => self::COLUMN_NAME_INVALID_CLICKS,
    ];

    const DEFAULT_STATUS = 'hideZero';
    const CUSTOM_STATUS = 'someStatus';
    const DEFAULT_TIME_PERIOD_TITLE = 'Last 90 days';
    const DEFAULT_STATUS_TITLE = 'Hide 0';
    const DEFAULT_GRAPH_COLUMN_NAME = 'clicks';
    const CUSTOM_TIME_PERIOD_TITLE = '10 days';
    const CUSTOM_START_DAY = '2017-01-01';
    const CUSTOM_END_DAY = '2017-02-03';
    const JANUARY_1ST_2017 = '2017-01-01';
    const JANUARY_10TH_2017 = '2017-01-10';
    const DEFAULT_PAGINATION = 20;
    const CUSTOM_PAGINATION = 21;
    const DEFAULT_COLUMN_SORT = self::COLUMN_NAME_IMPRESSIONS;
    const CUSTOM_COLUMN_SORT = 'someColumnName';
    const DEFAULT_SORT = 'desc';
    const CUSTOM_SORT = 'someSortOption';
    const VIEW_PATH = 'accountReport.index';
    const GROUPED_BY_FIELD = 'accountName';

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
            RepoAccountReportController::SESSION_KEY_FIELD_NAME,
            self::DEFAULT_FIELDS
        );
    }

    public function testWhenSessionDataIsAvailableTheColumnsInTheSessionAreNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::CUSTOM_FIELDS
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_FIELD_NAME,
            self::CUSTOM_FIELDS
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultStatusIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS,
            self::DEFAULT_STATUS
        );
    }

    public function testWhenSessionDataIsAvailableTheStatusInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::CUSTOM_STATUS
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS,
            self::CUSTOM_STATUS
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultTimePeriodTitleIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE,
            self::DEFAULT_TIME_PERIOD_TITLE
        );
    }

    public function testWhenSessionDataIsAvailableTheTimePeriodTitleInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE =>
                    self::CUSTOM_TIME_PERIOD_TITLE
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE,
            self::CUSTOM_TIME_PERIOD_TITLE
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultStartDayIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $startDay = (new DateTime)->modify('-90 days')->format('Y-m-d');

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_START_DAY,
            $startDay
        );
    }

    public function testWhenSessionDataIsAvailableTheStartDayInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_START_DAY => self::CUSTOM_START_DAY
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_START_DAY,
            self::CUSTOM_START_DAY
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultEndDayIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $endDay = (new DateTime)->format('Y-m-d');

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_END_DAY,
            $endDay
        );
    }

    public function testWhenSessionDataIsAvailableTheEndDayInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_END_DAY => self::CUSTOM_END_DAY
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_END_DAY,
            self::CUSTOM_END_DAY
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultPaginationValueIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_PAGINATION,
            self::DEFAULT_PAGINATION
        );
    }

    public function testWhenSessionDataIsAvailableThePaginationValueStoredInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::CUSTOM_PAGINATION
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_PAGINATION,
            self::CUSTOM_PAGINATION
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultColumnSortValueIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_COLUMN_SORT,
            self::DEFAULT_COLUMN_SORT
        );
    }

    public function testWhenSessionDataIsAvailableTheColumnSortValueStoredInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::CUSTOM_COLUMN_SORT
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_COLUMN_SORT,
            self::CUSTOM_COLUMN_SORT
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultSortValueIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_SORT,
            self::DEFAULT_SORT
        );
    }

    public function testWhenSessionDataIsAvailableTheSortValueStoredInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertSessionHas(
            RepoAccountReportController::SESSION_KEY_SORT,
            self::CUSTOM_SORT
        );
    }

    public function testReturnsCorrectView()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewIs(self::VIEW_PATH);
    }

    public function testViewHasFieldNamesFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::CUSTOM_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::DEFAULT_COLUMN_SORT,
                RepoAccountReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::FIELD_NAMES,
            self::CUSTOM_FIELDS
        );
    }

    public function testViewHasReports()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::DEFAULT_COLUMN_SORT,
                RepoAccountReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::REPORTS
        );
    }

    public function testViewHasColumns()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::COLUMNS,
            self::DEFAULT_FIELDS
        );
    }

    public function testViewHasSortColumnFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::COLUMN_SORT,
            self::COLUMN_NAME_CLICKS
        );
    }

    public function testViewHasSortOrderFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::SORT,
            self::CUSTOM_SORT
        );
    }

    public function testViewHasTimePeriodTitleFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::TIME_PERIOD_TITLE,
            self::CUSTOM_TIME_PERIOD_TITLE
        );
    }

    public function testViewHasStartDayFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::START_DAY,
            self::JANUARY_1ST_2017
        );
    }

    public function testViewHasEndDayFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD,
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::END_DAY,
            self::JANUARY_10TH_2017
        );
    }

    public function testViewHasColumnsForLiveSearch()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::COLUMNS_FOR_LIVE_SEARCH,
            self::LIVE_SEARCH_FIELDS
        );
    }
    public function testViewHasColumnsForFilter()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::COLUMNS_FOR_FILTER,
            self::COLUMNS_FOR_FILTER
        );
    }

    public function testViewHasStatusTitle()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::STATUS_TITLE,
            self::DEFAULT_STATUS_TITLE
        );
    }

    public function testViewHasTotalDataArray()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD,
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);
        $response->assertViewHas(
            RepoAccountReportController::TOTAL_DATA_ARRAY,
            [
                self::COLUMN_NAME_INVALID_CLICK_RATE => '21,513.75',
                self::COLUMN_NAME_IMPRESSION_SHARE => '20,888.02',
                self::COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE => '20,620.22',
                self::COLUMN_NAME_BUDGET_LOST_IMPRESSION_SHARE => '20,845.00',
                self::COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE => '20,761.93',
                self::COLUMN_NAME_CONVERSIONS => '20,931.56',
                self::COLUMN_NAME_CONV_VALUE => '20,786.00',
                self::COLUMN_NAME_COST_PER_CONV => '20,394.64',
                self::COLUMN_NAME_VALUE_PER_CONV => '20,828.03',
                self::COLUMN_NAME_ALL_CONV_RATE => '20,919.81',
                self::COLUMN_NAME_COST_PER_ALL_CONV => '20,904.12',
                self::COLUMN_NAME_VALUE_PER_ALL_CONV => '21,076.20',
                self::COLUMN_NAME_COST => '607,845',
                self::COLUMN_NAME_IMPRESSIONS => '2,464,084',
                self::COLUMN_NAME_CLICKS => '5,437,708',
                self::COLUMN_NAME_AVERAGE_CPC => '20,443.75',
                self::COLUMN_NAME_INVALID_CLICKS => '53,642,266',
                self::COLUMN_NAME_CONV_RATE => '20,594.76',
                self::COLUMN_NAME_ALL_CONV => '24,850,081.21',
                self::COLUMN_NAME_ALL_CONV_VALUE => '25,166,907.03',
            ]
        );
    }

    public function testViewHasPaginationFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::CUSTOM_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);

        $response->assertViewHas(
            RepoAccountReportController::KEY_PAGINATION,
            self::CUSTOM_PAGINATION
        );
    }

    public function testViewHasSummaryReportData()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::CUSTOM_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);
        $response->assertViewHas(
            RepoAccountReportController::SUMMARY_REPORT,
            [
                self::COLUMN_NAME_CLICKS => "5,437,708",
                self::COLUMN_NAME_IMPRESSIONS => "2,464,084",
                self::COLUMN_NAME_COST => "607,845",
                self::COLUMN_NAME_AVERAGE_CPC => "20,443.75",
                self::COLUMN_NAME_AVERAGE_POSITION => "20,938.07",
                self::COLUMN_NAME_INVALID_CLICKS => "53,642,266"
            ]
        );
    }

    public function testViewHasGroupedByFieldVariable()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAccountReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAccountReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAccountReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAccountReportController::SESSION_KEY_PAGINATION => self::CUSTOM_PAGINATION,
                RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAccountReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAccountReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAccountReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ACCOUNT_REPORT,
                RepoAccountReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ACCOUNT_REPORT);
        $response->assertViewHas(
            RepoAccountReportController::GROUPED_BY_FIELD,
            self::GROUPED_BY_FIELD
        );
    }
}
