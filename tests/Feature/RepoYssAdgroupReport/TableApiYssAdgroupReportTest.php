<?php

namespace Tests\Feature\RepoYssAdgroupReport;

use App\Http\Controllers\RepoAdgroupReport\RepoAdgroupReportController;
use App\User;

use Tests\TestCase;

use DateTime;

class TableApiYssAdgroupReportTest extends TestCase
{
    const ROUTE_ADGROUP_REPORT = '/adgroup-report';
    const COLUMN_NAME_ADGROUP_NAME = 'adgroupName';
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

    const DEFAULT_FIELDS = [
        0 => self::COLUMN_NAME_ADGROUP_NAME,
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

    const CUSTOM_FIELDS = [
        0 => self::COLUMN_NAME_ADGROUP_NAME,
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

    const LIVE_SEARCH_FIELDS = [
        11 => self::COLUMN_NAME_ADGROUP_BID,
        12 => self::COLUMN_NAME_COST,
        13 => self::COLUMN_NAME_IMPRESSIONS,
        14 => self::COLUMN_NAME_CLICKS,
        15 => self::COLUMN_NAME_CTR,
        16 => self::COLUMN_NAME_AVERAGE_CPC,
        17 => self::COLUMN_NAME_AVERAGE_POSITION,
        18 => self::COLUMN_NAME_IMPRESSION_SHARE,
        19 => self::COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE,
        20 => self::COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE,
        23 => self::COLUMN_NAME_CONVERSIONS,
        24 => self::COLUMN_NAME_CONV_RATE,
        25 => self::COLUMN_NAME_CONV_VALUE,
        26 => self::COLUMN_NAME_COST_PER_CONV,
        27 => self::COLUMN_NAME_VALUE_PER_CONV,
        28 => self::COLUMN_NAME_MOBILE_BID_ADJ,
        29 => self::COLUMN_NAME_DESKTOP_BID_ADJ,
        30 => self::COLUMN_NAME_TABLET_BID_ADJ
    ];

    const COLUMNS_FOR_FILTER = [
        11 => self::COLUMN_NAME_ADGROUP_BID,
        12 => self::COLUMN_NAME_COST,
        13 => self::COLUMN_NAME_IMPRESSIONS,
        14 => self::COLUMN_NAME_CLICKS,
        15 => self::COLUMN_NAME_CTR,
        16 => self::COLUMN_NAME_AVERAGE_CPC,
        17 => self::COLUMN_NAME_AVERAGE_POSITION,
        18 => self::COLUMN_NAME_IMPRESSION_SHARE,
        19 => self::COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE,
        20 => self::COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE,
        23 => self::COLUMN_NAME_CONVERSIONS,
        24 => self::COLUMN_NAME_CONV_RATE,
        25 => self::COLUMN_NAME_CONV_VALUE,
        26 => self::COLUMN_NAME_COST_PER_CONV,
        27 => self::COLUMN_NAME_VALUE_PER_CONV,
        28 => self::COLUMN_NAME_MOBILE_BID_ADJ,
        29 => self::COLUMN_NAME_DESKTOP_BID_ADJ,
        30 => self::COLUMN_NAME_TABLET_BID_ADJ
    ];

    const SUMMARY_REPORT = [
        0 => self::COLUMN_NAME_CLICKS,
        1 => self::COLUMN_NAME_IMPRESSIONS,
        2 => self::COLUMN_NAME_COST,
        3 => self::COLUMN_NAME_AVERAGE_CPC,
        4 => self::COLUMN_NAME_AVERAGE_POSITION,
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
    const VIEW_PATH = 'adgroupReport.index';
    const GROUPED_BY_FIELD = 'adgroupName';

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
        $response = $this->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertRedirect('/login');
    }

    public function testReturns200StatusWhenLoggedIn()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertStatus(200);
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultColumnsAreStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_FIELD_NAME,
            self::DEFAULT_FIELDS
        );
    }

    public function testWhenSessionDataIsAvailableTheColumnsInTheSessionAreNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::CUSTOM_FIELDS
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_FIELD_NAME,
            self::CUSTOM_FIELDS
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultStatusIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS,
            self::DEFAULT_STATUS
        );
    }

    public function testWhenSessionDataIsAvailableTheStatusInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::CUSTOM_STATUS
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS,
            self::CUSTOM_STATUS
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultTimePeriodTitleIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE,
            self::DEFAULT_TIME_PERIOD_TITLE
        );
    }

    public function testWhenSessionDataIsAvailableTheTimePeriodTitleInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE =>
                    self::CUSTOM_TIME_PERIOD_TITLE
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE,
            self::CUSTOM_TIME_PERIOD_TITLE
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultStartDayIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $startDay = (new DateTime)->modify('-90 days')->format('Y-m-d');

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_START_DAY,
            $startDay
        );
    }

    public function testWhenSessionDataIsAvailableTheStartDayInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::CUSTOM_START_DAY
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_START_DAY,
            self::CUSTOM_START_DAY
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultEndDayIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $endDay = (new DateTime)->format('Y-m-d');

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_END_DAY,
            $endDay
        );
    }

    public function testWhenSessionDataIsAvailableTheEndDayInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::CUSTOM_END_DAY
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_END_DAY,
            self::CUSTOM_END_DAY
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultPaginationValueIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_PAGINATION,
            self::DEFAULT_PAGINATION
        );
    }

    public function testWhenSessionDataIsAvailableThePaginationValueStoredInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::CUSTOM_PAGINATION
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_PAGINATION,
            self::CUSTOM_PAGINATION
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultColumnSortValueIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT,
            self::DEFAULT_COLUMN_SORT
        );
    }

    public function testWhenSessionDataIsAvailableTheColumnSortValueStoredInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::CUSTOM_COLUMN_SORT
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT,
            self::CUSTOM_COLUMN_SORT
        );
    }

    public function testWhenNoSessionDataIsAvailableTheDefaultSortValueIsStoredInTheSession()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_SORT,
            self::DEFAULT_SORT
        );
    }

    public function testWhenSessionDataIsAvailableTheSortValueStoredInTheSessionIsNotModified()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertSessionHas(
            RepoAdgroupReportController::SESSION_KEY_SORT,
            self::CUSTOM_SORT
        );
    }

    public function testReturnsCorrectView()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewIs(self::VIEW_PATH);
    }

    public function testViewHasFieldNamesFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::CUSTOM_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::DEFAULT_COLUMN_SORT,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::FIELD_NAMES,
            self::CUSTOM_FIELDS
        );
    }

    public function testViewHasReports()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::DEFAULT_COLUMN_SORT,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::REPORTS
        );
    }

    public function testViewHasColumns()
    {
        $response = $this->actingAs($this->getUser())
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::COLUMNS,
            self::DEFAULT_FIELDS
        );
    }

    public function testViewHasSortColumnFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::COLUMN_SORT,
            self::COLUMN_NAME_CLICKS
        );
    }

    public function testViewHasTimePeriodTitleFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::TIME_PERIOD_TITLE,
            self::CUSTOM_TIME_PERIOD_TITLE
        );
    }

    public function testViewHasStartDayFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::START_DAY,
            self::JANUARY_1ST_2017
        );
    }

    public function testViewHasEndDayFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD,
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::END_DAY,
            self::JANUARY_10TH_2017
        );
    }

    public function testViewHasColumnsForLiveSearch()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::COLUMNS_FOR_LIVE_SEARCH,
            self::LIVE_SEARCH_FIELDS
        );
    }

    public function testViewHasColumnsForFilter()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::COLUMNS_FOR_FILTER,
            self::COLUMNS_FOR_FILTER
        );
    }

    public function testViewHasStatusTitle()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::STATUS_TITLE,
            self::DEFAULT_STATUS_TITLE
        );
    }

    public function testViewHasTotalDataArray()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::DEFAULT_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD,
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::TOTAL_DATA_ARRAY,
            [
                self::COLUMN_NAME_ADGROUP_BID => '1,755,917',
                self::COLUMN_NAME_COST => '1,777,557',
                self::COLUMN_NAME_IMPRESSIONS => '7,230,149',
                self::COLUMN_NAME_CLICKS => '15,989,958',
                self::COLUMN_NAME_CTR => '5,944,429.25',
                self::COLUMN_NAME_AVERAGE_CPC => '20,830.75',
                self::COLUMN_NAME_AVERAGE_POSITION => '21,367.73',
                self::COLUMN_NAME_IMPRESSION_SHARE => '20,948.80',
                self::COLUMN_NAME_EXACT_MATCH_IMPRESSION_SHARE => '20,702.53',
                self::COLUMN_NAME_QUALITY_LOST_IMRPESSION_SHARE => '20,925.96',
                self::COLUMN_NAME_CONVERSIONS => '21,036.03',
                self::COLUMN_NAME_CONV_RATE => '20,968.35',
                self::COLUMN_NAME_CONV_VALUE => '20,902.89',
                self::COLUMN_NAME_COST_PER_CONV => '20,281.13',
                self::COLUMN_NAME_VALUE_PER_CONV => '20,687.82',
                self::COLUMN_NAME_MOBILE_BID_ADJ => '20,829.29',
                self::COLUMN_NAME_DESKTOP_BID_ADJ => '20,509.31',
                self::COLUMN_NAME_TABLET_BID_ADJ => '21,048.52',
            ]
        );
    }

    public function testViewHasPaginationFromSession()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::CUSTOM_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);

        $response->assertViewHas(
            RepoAdgroupReportController::KEY_PAGINATION,
            self::CUSTOM_PAGINATION
        );
    }

    public function testViewHasSummaryReportData()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::CUSTOM_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);
        $response->assertViewHas(
            RepoAdgroupReportController::SUMMARY_REPORT,
            [
                self::COLUMN_NAME_CLICKS => "15,989,958",
                self::COLUMN_NAME_IMPRESSIONS => "7,230,149",
                self::COLUMN_NAME_COST => "1,777,557",
                self::COLUMN_NAME_AVERAGE_CPC => "20,830.75",
                self::COLUMN_NAME_AVERAGE_POSITION => "21,367.73",
            ]
        );
    }

    public function testViewHasGroupedByFieldVariable()
    {
        $response = $this->actingAs($this->getUser())
            ->withSession([
                RepoAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                RepoAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                RepoAdgroupReportController::SESSION_KEY_TIME_PERIOD_TITLE => self::CUSTOM_TIME_PERIOD_TITLE,
                RepoAdgroupReportController::SESSION_KEY_GRAPH_COLUMN_NAME => self::DEFAULT_GRAPH_COLUMN_NAME,
                RepoAdgroupReportController::SESSION_KEY_STATUS_TITLE => self::DEFAULT_STATUS_TITLE,
                RepoAdgroupReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                RepoAdgroupReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                RepoAdgroupReportController::SESSION_KEY_PAGINATION => self::CUSTOM_PAGINATION,
                RepoAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::COLUMN_NAME_CLICKS,
                RepoAdgroupReportController::SESSION_KEY_SORT => self::CUSTOM_SORT,
                RepoAdgroupReportController::SESSION_KEY_SUMMARY_REPORT => self::SUMMARY_REPORT,
                RepoAdgroupReportController::SESSION_KEY_PREFIX_ROUTE => self::ROUTE_ADGROUP_REPORT,
                RepoAdgroupReportController::SESSION_KEY_GROUPED_BY_FIELD => self::GROUPED_BY_FIELD
            ])
            ->get(self::ROUTE_ADGROUP_REPORT);
        $response->assertViewHas(
            RepoAdgroupReportController::GROUPED_BY_FIELD,
            self::GROUPED_BY_FIELD
        );
    }
}
