<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepoYssAccountReportsCostTable extends Seeder
{
    const OBJECTIVE_OF_CONVERSION_TRACKING = 'Conversion Tracking';
    const CONVERSION_NAME = 'Conversion Name';
    const START_DATE = '2017-01-01 00:00:00';
    const END_DATE = '2018-02-03 00:00:00';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yssAccountReports = App\RepoYssAccountReport::query()->get();
        $startDate = new DateTime(self::START_DATE);
        $endDate = new DateTime(self::END_DATE);

        foreach ($yssAccountReports as $yssAccountReport) {
            DB::table('repo_yss_account_report_costs')->insert([
                'exeDate' => $endDate,
                'startDate' => $startDate,
                'endDate' => $endDate,
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