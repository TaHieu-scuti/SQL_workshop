<?php

use Illuminate\Database\Seeder;
use App\Model\RepoYssPrefectureReportCost;
use App\Model\RepoYssPrefectureReportConv;

class RepoYssPrefectureReportConvGenerator extends Seeder
{
    const NUMBER_OF_CONVERSION_POINTS = 2;
    const CONVERSION_NAME = 'Conversion name ';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yssCostPres = RepoYssPrefectureReportCost::all();
        foreach ($yssCostPres as $key => $yssCostPre) {
            for ($i=0; $i < self::NUMBER_OF_CONVERSION_POINTS; $i++) {
                $yssConvPre = new RepoYssPrefectureReportConv;
                $yssConvPre->exeDate = $yssCostPre->exeDate;
                $yssConvPre->startDate = $yssCostPre->startDate;
                $yssConvPre->endDate = $yssCostPre->endDate;
                $yssConvPre->account_id = $yssCostPre->account_id;
                $yssConvPre->campaign_id = $yssCostPre->campaign_id;
                $yssConvPre->campaignID = $yssCostPre->campaignID;
                $yssConvPre->adgroupID = $yssCostPre->adgroupID;
                $yssConvPre->campaignName = $yssCostPre->campaignName;
                $yssConvPre->adgroupName = $yssCostPre->adgroupName;
                $yssConvPre->conversions = $yssCostPre->conversions;
                $yssConvPre->convValue = $yssCostPre->convValue;
                $yssConvPre->valuePerConv = $yssCostPre->valuePerConv;
                $yssConvPre->allConv = $yssCostPre->allConv;
                $yssConvPre->allConvValue = $yssCostPre->allConvValue;
                $yssConvPre->valuePerAllConv = $yssCostPre->valuePerAllConv;
                $yssConvPre->network = $yssCostPre->network;
                $yssConvPre->device = $yssCostPre->device;
                $yssConvPre->day = $yssCostPre->day;
                $yssConvPre->dayOfWeek = $yssCostPre->dayOfWeek;
                $yssConvPre->quarter = $yssCostPre->quarter;
                $yssConvPre->month = $yssCostPre->month;
                $yssConvPre->week = $yssCostPre->week;
                $yssConvPre->countryTerritory = $yssCostPre->countryTerritory;
                $yssConvPre->prefecture = $yssCostPre->prefecture;
                $yssConvPre->city = $yssCostPre->city;
                $yssConvPre->cityWardDistrict = $yssCostPre->cityWardDistrict;
                $yssConvPre->accountid = $yssCostPre->accountid;
                $yssConvPre->conversionName = self::CONVERSION_NAME . ($i + 1);
                $yssConvPre->saveOrFail();
            }
        }
    }
}
