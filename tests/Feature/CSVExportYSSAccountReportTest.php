<?php

namespace Tests\Feature;

use Tests\TestCase;

use DateTime;

class CSVExportYSSAccountReportTest extends TestCase
{
    public function testReturnsStatus200()
    {
        $now = new DateTime;

        $response = $this->get('/account_report/export_csv');

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
        $expectedContent = file_get_contents(__DIR__ . '/../resources/repo_yss_account_report.csv');
        $this->assertSame($expectedContent, $response['response']->getContent());
    }
}
