<?php

namespace Tests\Feature;

use Tests\TestCase;

use DateTime;
use ZipArchive;

class ExcelExportYSSAccountReportTest extends TestCase
{
    public function testReturnsStatus200()
    {
        $now = new DateTime;

        $response = $this->get('/account_report/export_excel');

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
}
