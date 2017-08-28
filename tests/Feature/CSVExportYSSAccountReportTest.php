<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\TestResponse;

use DateTime;

class CSVExportYSSAccountReportTest extends TestCase
{
    public function testReturnsStatus200()
    {
        $response = $this->get('/account_report/export_csv');

        $response->assertStatus(200);

        return $response;
    }

    /**
     * @depends testReturnsStatus200
     * @param TestResponse $response
     */
    public function testReturnsCorrectResponseHeaders(TestResponse $response)
    {
        $response->assertHeader('Content-Type', 'application/csv; charset=UTF-8');

        $fileName = date("Y_m_d h_i ") . 'repo_yss_account_report.csv';
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $response->assertHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');

        $response->assertHeader('Last-Modified');

        $now = new DateTime;
        $lastModifiedDateTime = $now->format('D, d M Y H:i:s');
        $lastModifiedHeader = $response->headers->get('Last-Modified');

        $i = 0;
        while ($lastModifiedDateTime !== $lastModifiedHeader && $i < 2) {
            $lastModifiedDateTime = $now->modify('+1 second')->format('D, d M Y H:i:s');
            $i++;
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
        $expectedContent = file_get_contents(__DIR__ . '/../resources/repo_yss_account_report.csv');
        $this->assertSame($expectedContent, $response->getContent());
    }
}
