<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepoYssAccountReportsConvTable extends Seeder
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
            DB::table('repo_yss_account_report_convs')->insert([
                'exeDate' => $endDate,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'account_id' => $yssAccountReport->account_id,
                'campaign_id' => $yssAccountReport->campaign_id,
                'trackingURL' => $yssAccountReport->trackingURL,
                'conversions' => $yssAccountReport->conversions,
                'convValue' => $yssAccountReport->convValue,
                'valuePerConv' => $yssAccountReport->valuePerConv,
                'allConv' => $yssAccountReport->allConv,
                'allConvValue' => $yssAccountReport->allConvValue,
                'valuePerAllConv' => $yssAccountReport->valuePerAllConv,
                'network' => $yssAccountReport->network,
                'clickType' => $yssAccountReport->clickType,
                'device' => $yssAccountReport->device,
                'day' => $yssAccountReport->day,
                'dayOfWeek' => $yssAccountReport->dayOfWeek,
                'quarter' => $yssAccountReport->quarter,
                'month' => $yssAccountReport->month,
                'week' => $yssAccountReport->week,
                'objectiveOfConversionTracking' => self::OBJECTIVE_OF_CONVERSION_TRACKING,
                'conversionName' => self::CONVERSION_NAME,
            ]);
        }
    }
}
