<?php

use Illuminate\Database\Seeder;
use App\Model\RepoAdwAccountReportCost;
use App\Model\RepoAdwAccountReportConv;

// @codingStandardsIgnoreLine
class RepoAdwAccountConvTableGenerator extends Seeder
{
    const CONVERSION_CATEGORY = [
        'Conversion category 1', 'Conversion category 2',
        'Conversion categroy 3', 'Conversion category 4'
    ];
    const CONVERSION_NAME = [
        'Conversion name 1', 'Conversion name 2',
        'Conversion name 3', 'Conversion name 4'
    ];
    const MIN_CURRENCY = 1;
    const MAX_CURRENCY = 100;
    const MIN_CONVERSION_POINT = 1;
    const MAX_CONVERSION_POINT = 3;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adwCostAccounts = RepoAdwAccountReportCost::all();
        foreach ($adwCostAccounts as $adwCostAccount) {
            $numberOfConversionPoints = mt_rand(self::MIN_CONVERSION_POINT, self::MAX_CONVERSION_POINT);
            for ($i = 0; $i < $numberOfConversionPoints; $i++) {
                $adwConvAccount = new RepoAdwAccountReportConv;
                $adwConvAccount->exeDate = $adwCostAccount->exeDate;
                $adwConvAccount->startDate = $adwCostAccount->startDate;
                $adwConvAccount->endDate = $adwCostAccount->endDate;
                $adwConvAccount->account_id = $adwCostAccount->account_id;
                $adwConvAccount->campaign_id = $adwCostAccount->campaign_id;
                $adwConvAccount->currency = mt_rand(self::MIN_CURRENCY, self::MAX_CURRENCY);
                $adwConvAccount->account = $adwCostAccount->account;
                $adwConvAccount->timeZone = $adwCostAccount->timeZone;
                $adwConvAccount->network = $adwCostAccount->network;
                $adwConvAccount->networkWithSearchPartners = $adwCostAccount->networkWithSearchPartners;
                $adwConvAccount->conversionCategory = self::CONVERSION_CATEGORY[mt_rand(
                    0,
                    count(self::CONVERSION_CATEGORY) - 1
                )];
                $adwConvAccount->conversions = $adwCostAccount->conversions / $numberOfConversionPoints;
                $adwConvAccount->conversionTrackerId = mt_rand(0, count(self::CONVERSION_NAME) -1);
                $adwConvAccount->conversionName = self::CONVERSION_NAME[$adwConvAccount->conversionTrackerId];
                $adwConvAccount->day = $adwCostAccount->day;
                $adwConvAccount->dayOfWeek = $adwCostAccount->dayOfWeek;
                $adwConvAccount->device = $adwCostAccount->device;
                $adwConvAccount->customerID = $adwCostAccount->customerID;
                $adwConvAccount->hourOfDay = $adwCostAccount->hourOfDay;
                $adwConvAccount->month = $adwCostAccount->month;
                $adwConvAccount->monthOfYear = $adwCostAccount->monthOfYear;
                $adwConvAccount->quarter = $adwCostAccount->quarter;
                $adwConvAccount->week = $adwCostAccount->week;
                $adwConvAccount->year = $adwCostAccount->year;
                $adwConvAccount->saveOrFail();
            }
        }
    }
}
