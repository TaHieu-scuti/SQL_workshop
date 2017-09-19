<?php

namespace Tests\Feature;

use App\Http\Controllers\RepoYssAccountReport\RepoYssAccountReportController;
use App\User;

use Tests\TestCase;

use DateTime;

class CSVExportYSSAccountReportTest extends TestCase
{

    const COLUMN_NAME_ACCOUNT_NAME = 'accountName';
    const COLUMN_NAME_COST = 'cost';
    const COLUMN_NAME_IMPRESSIONS = 'impressions';
    const COLUMN_NAME_CLICKS = 'clicks';
    const COLUMN_NAME_AVERAGE_CPC = 'averageCpc';
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

    const DEFAULT_STATUS = 'enabled';
    const JANUARY_1ST_2017 = '2017-06-21';
    const JANUARY_10TH_2017 = '2017-09-19';
    const DEFAULT_COLUMN_SORT = self::COLUMN_NAME_IMPRESSIONS;
    const DEFAULT_SORT = 'desc';

    public function testReturnsStatus200()
    {
        $now = new DateTime;

        $user = (new User)->find(1)->first();

        $response = $this->actingAs($user)
                        ->withSession([
                            RepoYssAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                            RepoYssAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                            RepoYssAccountReportController::SESSION_KEY_START_DAY => self::JANUARY_1ST_2017,
                            RepoYssAccountReportController::SESSION_KEY_END_DAY => self::JANUARY_10TH_2017,
                            RepoYssAccountReportController::SESSION_KEY_COLUMN_SORT => self::DEFAULT_COLUMN_SORT,
                            RepoYssAccountReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
                        ])
                        ->get('/account_report/export_csv');

        $response->assertStatus(200);

        return ['response' => $response, 'now' => $now];
    }

    /**
     * @depends testReturnsStatus200
     * @param array $response
     */
    public function testReturnsCorrectResponseHeaders(array $response)
    {
        $response['response']->assertHeader('Content-Type', 'application/csv; charset=UTF-8');

        $fileName = $response['now']->format("Y_m_d h_i ") . 'repo_yss_account_report.csv';
        $response['response']->assertHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $response['response']->assertHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');

        $response['response']->assertHeader('Last-Modified');

        $lastModifiedDateTime = $response['now']->format('D, d M Y H:i:s');
        $lastModifiedHeader = $response['response']->headers->get('Last-Modified');

        $i = 0;
        while ($lastModifiedDateTime !== $lastModifiedHeader && $i < 30) {
            $lastModifiedDateTime = $response['now']->modify('+1 second')->format('D, d M Y H:i:s');
            $i++;
        }

        $this->assertSame($lastModifiedDateTime, $lastModifiedHeader);

        $response['response']->assertHeader('Cache-Control', 'cache, must-revalidate, private');
        $response['response']->assertHeader('Pragma', 'public');
    }

    /**
     * @depends testReturnsStatus200
     * @param array $response
     */
    public function testReturnsCorrectContent(array $response)
    {
        $expectedContent = file_get_contents(__DIR__ . '/../resources/2017_09_19 07_07 repo_yss_account_report.csv');
        $this->assertSame($expectedContent, $response['response']->getContent());
    }

    public function testRedirectsToLoginRouteWhenNotLoggedIn()
    {
        $response = $this->get('/account_report/export_csv');

        $response->assertRedirect('/login');
    }
}
