<?php

use Illuminate\Database\Seeder;

class RepoYssAccountReportsCostTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yssAccountReports = App\RepoYssAccountReport::query()->get();

        foreach ($yssAccountReports as $yssAccountReport) {
            DB::table('repo_yss_campaign_report_costs')->insert([
                'exeDate' => ,
                'startDate' => ,
                'endDate' => ,
                'account_id' => $yssAccountReport->account_id,
                'campaign_id' => $yssAccountReport->campaign_id,
                'cost' => $yssAccountReport->cost,
                'impressions' => $yssAccountReport->impressions,
                'clicks' => $yssAccountReport->clicks,
                'ctr' => $yssAccountReport->ctr,
                'averageCpc' => $yssAccountReport->averageCpc,
                'averagePosition' => $yssAccountReport->averagePosition,
                'impressionShare' => $yssAccountReport->impressionShare,
                'exactMatchImpressionShare' => $yssAccountReport->exactMatchImpressionShare,
                'budgetLostImpressionShare' => $yssAccountReport->budgetLostImpressionShare,
                'qualityLostImpressionShare' => $yssAccountReport->qualityLostImpressionShare,
                'trackingURL' => $yssAccountReport->trackingURL,
                'conversions' => $yssAccountReport->conversions,
                'convRate' => $yssAccountReport->convRate,
                'convValue' => $yssAccountReport->convValue,
                'costPerConv' => $yssAccountReport->costPerConv,
                'valuePerConv' => $yssAccountReport->valuePerConv,
                'network' => $yssAccountReport->network,
                'device' => $yssAccountReport->device,
                'day' => $yssAccountReport->day,
                'dayOfWeek' => $yssAccountReport->dayOfWeek,
                'quarter' => $yssAccountReport->quarter,
                'month' => $yssAccountReport->month,
                'week' => $yssAccountReport->week,
                'hourofday' => rand(0,24)
            ]);
        }
    }
}
