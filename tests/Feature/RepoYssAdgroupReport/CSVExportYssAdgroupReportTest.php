<?php

namespace Tests\Feature\RepoYssAdgroupReport;

use App\Http\Controllers\RepoYssAdgroupReport\RepoYssAdgroupReportController;
use App\User;

use Tests\TestCase;

use DateTime;

class CSVExportYssAdgroupReportTest extends TestCase
{
    const COLUMN_NAME_ADGROUP_NAME = 'adgroupName';
    const COLUMN_NAME_AD_GROUP_BID = 'adGroupBid';
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
        1 => self::COLUMN_NAME_AD_GROUP_BID,
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

    const DEFAULT_STATUS = 'enabled';
    const CUSTOM_START_DAY = '2017-07-22';
    const CUSTOM_END_DAY = '2017-10-20';
    const DEFAULT_COLUMN_SORT = self::COLUMN_NAME_IMPRESSIONS;
    const DEFAULT_SORT = 'desc';

    public function testReturnsStatus200()
    {
        $now = new DateTime();

        $user = (new User)->find(1)->first();

        $response = $this->actingAs($user)
                        ->withSession([
                            RepoYssAdgroupReportController::SESSION_KEY_FIELD_NAME => self::DEFAULT_FIELDS,
                            RepoYssAdgroupReportController::SESSION_KEY_ACCOUNT_STATUS => self::DEFAULT_STATUS,
                            RepoYssAdgroupReportController::SESSION_KEY_START_DAY => self::CUSTOM_START_DAY,
                            RepoYssAdgroupReportController::SESSION_KEY_END_DAY => self::CUSTOM_END_DAY,
                            RepoYssAdgroupReportController::SESSION_KEY_COLUMN_SORT => self::DEFAULT_COLUMN_SORT,
                            RepoYssAdgroupReportController::SESSION_KEY_SORT => self::DEFAULT_SORT,
                        ])
                        ->get('/adgroup-report/export_csv');

        return ['response' => $response, 'now' => $now];
    }

    /**
     * @depends testReturnsStatus200
     * @param array $response
     */
    public function testReturnsCorrectResponseHeaders(array $response)
    {
        $response['response']->assertHeader('Content-Type', 'application/csv; charset=UTF-8');

        $fileName = $response['now']->format("Y_m_d h_i ") . 'repo_yss_adgroup_report_cost.csv';
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
        $expectedContent = file_get_contents(__DIR__ . '/../../resources/repo_yss_adgroup_report_cost.csv');
        $this->assertSame($expectedContent, $response['response']->getContent());
    }

    public function testRedirectsToLoginRouteWhenNotLoggedIn()
    {
        $response = $this->get('/adgroup-report/export_csv');

        $response->assertRedirect('/login');
    }
}
