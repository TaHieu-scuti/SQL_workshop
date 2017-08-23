<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\TestResponse;

use DateTime;
use ZipArchive;

class ExcelExportYSSAccountReportTest extends TestCase
{
    public function testReturnsStatus200()
    {
        $response = $this->get('/account_report/export_excel');

        $response->assertStatus(200);

        return $response;
    }

    /**
     * @depends testReturnsStatus200
     * @param TestResponse $response
     */
    public function testReturnsCorrectResponseHeaders(TestResponse $response)
    {
        $response->assertHeader(
            'Content-Type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=UTF-8'
        );

        $fileName = date("Y_m_d h_i ") . 'repo_yss_account_report.xlsx';
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $response->assertHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');

        $response->assertHeader('Last-Modified');

        $now = new DateTime;
        $lastModifiedDateTime = $now->format('D, d M Y H:i:s');
        $lastModifiedHeader = $response->headers->get('Last-Modified');

        if ($lastModifiedDateTime !== $lastModifiedHeader) {
            $lastModifiedDateTime = $now->modify('+1 second')->format('D, d M Y H:i:s');
        }

        $this->assertSame($lastModifiedDateTime, $lastModifiedHeader);

        $response->assertHeader('Cache-Control', 'cache, must-revalidate, private');
        $response->assertHeader('Pragma', 'public');
    }

    /**
     * @depends testReturnsStatus200
     * @param TestResponse $response
     */
    public function testReturnsCorrectContent(TestResponse $response)
    {
        $resourceZipArchive = new ZipArchive;
        $resourceZipArchive->open(__DIR__ . '/../resources/repo_yss_account_report.xlsx');
        $expectedSheet = $resourceZipArchive->getFromName('xl/worksheets/sheet1.xml');

        $fileName = tempnam('/tmp', 'repo_yss_account_report');
        file_put_contents($fileName, $response->getContent());

        $actualZipArchive = new ZipArchive;
        $actualZipArchive->open($fileName);
        $actualSheet = $actualZipArchive->getFromName('xl/worksheets/sheet1.xml');

        $this->assertSame($expectedSheet, $actualSheet);
    }
}
