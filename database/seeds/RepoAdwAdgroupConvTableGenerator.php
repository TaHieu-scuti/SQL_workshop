<?php

use Illuminate\Database\Seeder;

use App\Model\RepoAdwAdgroupReportCost;
use App\Model\RepoAdwAdgroupReportConv;

// @codingStandardsIgnoreLine
class RepoAdwAdgroupConvTableGenerator extends Seeder
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
        $adwCostAdgroups = RepoAdwAdgroupReportCost::all();
        foreach ($adwCostAdgroups as $adwCostAdgroup) {
            $numberOfConversionPoints = mt_rand(self::MIN_CONVERSION_POINT, self::MAX_CONVERSION_POINT);
            for ($i=0; $i < $numberOfConversionPoints; $i++) {
                $adwConvAdgroup = new RepoAdwAdgroupReportConv;
                $adwConvAdgroup->exeDate = $adwCostAdgroup->exeDate;
                $adwConvAdgroup->startDate = $adwCostAdgroup->startDate;
                $adwConvAdgroup->endDate = $adwCostAdgroup->endDate;
                $adwConvAdgroup->account_id = $adwCostAdgroup->account_id;
                $adwConvAdgroup->campaign_id = $adwCostAdgroup->campaign_id;
                $adwConvAdgroup->currency = mt_rand(
                    self::MIN_CURRENCY,
                    self::MAX_CURRENCY
                );
                $adwConvAdgroup->account = $adwCostAdgroup->account;
                $adwConvAdgroup->timeZone = $adwCostAdgroup->timeZone;
                $adwConvAdgroup->adGroupID = $adwCostAdgroup->adGroupID;
                $adwConvAdgroup->adGroup = $adwCostAdgroup->adGroup;
                $adwConvAdgroup->adGroupState = $adwCostAdgroup->adGroupState;
                $adwConvAdgroup->adGroupType = $adwCostAdgroup->adGroupType;
                $adwConvAdgroup->network = $adwCostAdgroup->network;
                $adwConvAdgroup->networkWithSearchPartners = $adwCostAdgroup->networkWithSearchPartners;
                $adwConvAdgroup->baseAdGroupID = $adwCostAdgroup->baseAdGroupID;
                $adwConvAdgroup->baseCampaignID = $adwCostAdgroup->baseCampaignID;
                $adwConvAdgroup->campaignID = $adwCostAdgroup->campaignID;
                $adwConvAdgroup->campaign = $adwCostAdgroup->campaign;
                $adwConvAdgroup->campaignState = $adwCostAdgroup->campaignState;
                $adwConvAdgroup->conversions = $adwCostAdgroup->conversions / $numberOfConversionPoints;
                $adwConvAdgroup->conversionCategory = self::CONVERSION_CATEGORY[mt_rand(
                    0,
                    count(self::CONVERSION_CATEGORY) - 1
                )];
                $adwConvAdgroup->conversionTrackerId = mt_rand(0, count(self::CONVERSION_NAME) -1);
                $adwConvAdgroup->conversionName = self::CONVERSION_NAME[$adwConvAdgroup->conversionTrackerId];
                $adwConvAdgroup->clientName = $adwCostAdgroup->clientName;
                $adwConvAdgroup->day = $adwCostAdgroup->day;
                $adwConvAdgroup->dayOfWeek = $adwCostAdgroup->dayOfWeek;
                $adwConvAdgroup->device = $adwCostAdgroup->device;
                $adwConvAdgroup->customerID = $adwCostAdgroup->customerID;
                $adwConvAdgroup->hourOfDay = $adwCostAdgroup->hourOfDay;
                $adwConvAdgroup->month = $adwCostAdgroup->month;
                $adwConvAdgroup->monthOfYear = $adwCostAdgroup->monthOfYear;
                $adwConvAdgroup->quarter = $adwCostAdgroup->quarter;
                $adwConvAdgroup->week = $adwCostAdgroup->week;
                $adwConvAdgroup->year = $adwCostAdgroup->year;
                $adwConvAdgroup->saveOrFail();
            }
        }
    }
}
