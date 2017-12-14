<?php

use Illuminate\Database\Seeder;
use App\Model\RepoAdwAdReportCost;

// @codingStandardsIgnoreLine
class AddImageLinkForAdwAdTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RepoAdwAdReportCost::chunk(1000, function ($adReports) {
            foreach ($adReports as $adReport) {
                if ($adReport->ad !== 'Some text advertisement') {
                    $adReport->ad = 'https://flydigitalprint.com/wp/wp-content/uploads/2016/12/banner-sign.jpg';
                }
                $adReport->saveOrFail();
            }
        });
    }
}
