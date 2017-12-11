<?php

use Illuminate\Database\Seeder;
use App\Model\RepoAdwAdReportCost;

class AddAdTypeForAdwAdTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RepoAdwAdReportCost::chunk(1000, function($adReports) {
            foreach ($adReports as $adReport) {
                if ($adReport->ad === 'Some text advertisement') {
                    $adReport->adType = 'TEXT_AD';
                } else {
                    $adReport->adType = 'IMAGE_AD';
                }
                $adReport->saveOrFail();
            }
        });
    }
}
