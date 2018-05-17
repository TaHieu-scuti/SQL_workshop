<?php

namespace Tests\Feature;

use App\Http\Controllers\RepoAccountReport\RepoAccountReportController;
use App\User;

use Tests\TestCase;

use DateTime;
use ZipArchive;

class ExcelExportYSSAccountReportTest extends TestCase
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
        21 => 'accountid'
    ];
    const DEFAULT_STATUS = 'hideZero';
    const CUSTOM_START_DAY = '2017-09-01';
    const CUSTOM_END_DAY = '2017-09-30';
    const DEFAULT_COLUMN_SORT = self::COLUMN_NAME_IMPRESSIONS;
    const DEFAULT_SORT = 'desc';
    const GROUPED_BY_FIELD = 'accountName';

    public function testReturnsStatus200()
    {
        $now = new DateTime;

        $user = (new User)->find(1)->first();

        $response = $this->actingAs($user)
                        ->withSession([
                            RepoAccountReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                            RepoAccountReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                            RepoAccountReportController::SESSION_KEY_START_DAY => self::CUSTOM_START_DAY,
                            RepoAccountReportController::SESSION_KEY_END_DAY => self::CUSTOM_END_DAY,
                            RepoAccountReportController::SESSION_KEY_COLUMN_SORT => self::DEFAULT_COLUMN_SORT,
                            RepoAccountReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
                        ])
                         ->get('/account_report/export_excel');

        $response->assertStatus(200);

        return ['response' => $response, 'now' => $now];
    }

    /**
     * @depends testReturnsStatus200
     * @param array $response
     */
    public function testReturnsCorrectResponseHeaders(array $response)
    {
        $response['response']->assertHeader(
            'Content-Type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8'
        );

        $fileName = $response['now']->format("Y_m_d h_i ") . 'repo_yss_account_report.xlsx';
        $response['response']->assertHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $response['response']->assertHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');

        $response['response']->assertHeader('Last-Modified');

        $lastModifiedDateTime = $response['now']->format('D, d M Y H:i:s');
        $lastModifiedHeader = $response['response']->headers->get('Last-Modified');

        $i = 0;
        while ($lastModifiedDateTime !== $lastModifiedHeader && $i < 180) {
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
        $resourceZipArchive = new ZipArchive;
        $resourceZipArchive->open(__DIR__ . '/../resources/repo_yss_account_report.xlsx');
        $expectedSheet = $resourceZipArchive->getFromName('xl/worksheets/sheet1.xml');

        $fileName = tempnam('/tmp', 'repo_yss_account_report');
        file_put_contents($fileName, $response['response']->getContent());

        $actualZipArchive = new ZipArchive;
        $actualZipArchive->open($fileName);
        $actualSheet = $actualZipArchive->getFromName('xl/worksheets/sheet1.xml');

        $this->assertSame($expectedSheet, $actualSheet);
    }

    public function testRedirectsToLoginRouteWhenNotLoggedIn()
    {
        $response = $this->get('/account_report/export_excel');

        $response->assertRedirect('/login');
    }
}
