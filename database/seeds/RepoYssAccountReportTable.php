
<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\RepoYssAccountReport;

class RepoYssAccountReportTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            RepoYssAccountReport::create([
                'account_id' => str_random(10),
                'campaign_id' => str_random(10),
                'cost' => rand(1,10),
                'impressions' => rand(1,10),
                'clicks' => rand(1,10),
                'ctr' => (double)rand(1,10),
                'averageCpc' => (double)rand(1,10),
                'averagePosition' => (double)rand(1,10),
                'invalidClicks' => rand(1,10),
                'invalidClickRate' => (double)rand(1,10),
                'impressionShare' => (double)rand(1,10),
                'exactMatchImpressionShare' => (double)rand(1,10),
                'budgetLostImpressionShare' => (double)rand(1,10),
                'qualityLostImpressionShare' => (double)rand(1,10),
                'trackingURL' => str_random(20),
                'conversions' => (double)rand(1,10),
                'convRate' => (double)rand(1,10),
                'convValue' => (double)rand(1,10),
                'costPerConv' => (double)rand(1,10),
                'valuePerConv' => (double)rand(1,10),
                'allConv' => (double)rand(1,10),
                'allConvRate' => (double)rand(1,10),
                'allConvValue' => (double)rand(1,10),
                'costPerAllConv' => (double)rand(1,10),
                'valuePerAllConv' => (double)rand(1,10),
                'network' => str_random(20),
                'device' => str_random(20),
                'day' => date("Y/m/d"),
                'dayOfWeek' => '12',
                'quarter' => '2',
                'month' => 'March',
                'week' => 'Second',
            ]);
        }
    }
}
