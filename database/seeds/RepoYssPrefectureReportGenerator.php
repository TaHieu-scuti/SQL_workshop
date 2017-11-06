<?php

use Illuminate\Database\Seeder;
use App\Model\RepoYssAdgroupReportCost;
use App\Model\RepoYssPrefectureReportCost;

// @codingStandardsIgnoreLine
class RepoYssPrefectureReportGenerator extends Seeder
{
	const MIN_NUMBER_OF_PREFECTURE = 1;
    const MAX_NUMBER_OF_PREFECTURE = 2;
    const MIN_COST = 1;
    const MAX_COST = 1004;
    const MIN_IMPRESSIONS = 0;
    const MAX_IMPRESSIONS = 4096;
    const MIN_BIDADJUSTMENT = 1;
    const MAX_BIDADJUSTMENT = 1000;
    const MIN_CLICKS = 0;
    const MAX_CLICKS = 9001;
    const MIN_CTR = 1000000;
    const MAX_CTR = 2032456345;
    const MIN_CONV_RATE = 1000000;
    const MAX_CONV_RATE = 2037437880;
    const MIN_AVERAGE_CPC = 1000000;
    const MAX_AVERAGE_CPC = 2037437880;
    const MIN_AVERAGE_POSITION = 1000000;
    const MAX_AVERAGE_POSITION = 2037437880;
    const MIN_CONVERSIONS = 1000000;
    const MAX_CONVERSIONS = 2037437880;
    const MIN_CONV_VALUE = 1000000;
    const MAX_CONV_VALUE = 2037437880;
    const MIN_COST_PER_CONV = 1000000;
    const MAX_COST_PER_CONV = 2037437880;
    const MIN_VALUE_PER_CONV = 1000000;
    const MAX_VALUE_PER_CONV = 2037437880;
    const MIN_MOBILE_BID_ADJ = 1000000;
    const MAX_MOBILE_BID_ADJ = 2037437880;
    const MIN_DESKTOP_BID_ADJ = 1000000;
    const MAX_DESKTOP_BID_ADJ = 2037437880;
    const MIN_TABLET_BID_ADJ = 1000000;
    const MAX_TABLET_BID_ADJ = 2037437880;
    const MIN_VALUE_PER_ALL_CONV = 1000000;
    const MAX_VALUE_PER_ALL_CONV = 2037437880;
    const MIN_ALL_CONV = 1000000;
    const MAX_ALL_CONV = 2037437880;
    const MIN_ALL_CONV_VALUE = 1000000;
    const MAX_ALL_CONV_VALUE = 2037437880;
    const MIN_ALL_CONV_RATE = 1000;
    const MAX_ALL_CONV_RATE = 1437437880;
    const MIN_COST_PER_ALL_CONV = 1000;
    const MAX_COST_PER_ALL_CONV = 1437437880;
    const NETWORKS = ['network1', 'network2', 'network3'];
    const DEVICES = ['mobile', 'tablet', 'pc', 'apple'];
    const COUNTRY_TERRITORY = [
        'USA', 'VIET NAM',
        'JAPAN', 'KOREA'
    ];
    Const PREFECTURE = [
    	'HA NOI', 'NEW YORK',
    	'SEOUL'
    ];
    const CITY = [
    	'HO CHI MINH', 'DA NANG',
    	'NHA TRANG'
    ];
    const CITY_WAR_DISTRICT = [
    	'NGUYEN PHONG SAC STREET', 'CAY GIAY STREET',
    	'NGUYEN TRAI STREET'
    ];
    const OBJECTIVE_OF_CONVERSION_TRACKING = 'Objective of conversion tracking';
    const CONVERSION_NAME = [
        'Conversion Name 1', 'Conversion Name 2',
        'Conversion Name 3', 'Conversion Name 4'
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adgroupReports = RepoYssAdgroupReportCost::all();
        foreach ($adgroupReports as $key => $adgroupReport) {
        	$ammountOfAdgroup = rand(
                self::MIN_NUMBER_OF_PREFECTURE,
                self::MAX_NUMBER_OF_PREFECTURE
            );

            for ($i=0; $i < $ammountOfAdgroup ; $i++) { 
            	$prefecture = new RepoYssPrefectureReportCost;
            	$prefecture->exeDate = $adgroupReport->exeDate;
            	$prefecture->startDate = $adgroupReport->startDate;
            	$prefecture->endDate = $adgroupReport->endDate;
            	$prefecture->accountid = $adgroupReport->accountid;
            	$prefecture->account_id = $adgroupReport->account_id;
            	$prefecture->campaign_id = $adgroupReport->campaign_id;
            	$prefecture->campaignID = $adgroupReport->campaignID;
            	$prefecture->adgroupID = $adgroupReport->adgroupID;
            	$prefecture->campaignName = $adgroupReport->campaignName;
            	$prefecture->adgroupName = $adgroupReport->adgroupName;
            	$prefecture->cost = mt_rand(
                    self::MIN_COST,
                    self::MAX_COST
                );
                $prefecture->impressions = mt_rand(
                    self::MIN_IMPRESSIONS,
                    self::MAX_IMPRESSIONS
                );
                $prefecture->clicks = mt_rand(
                    self::MIN_CLICKS,
                    self::MAX_CLICKS
                );
                $prefecture->ctr = mt_rand(
                    self::MIN_CTR,
                    self::MAX_CTR
                ) / mt_getrandmax();
                $prefecture->averageCpc = mt_rand(
                    self::MIN_AVERAGE_CPC,
                    self::MAX_AVERAGE_CPC
                ) / mt_getrandmax();
             	$prefecture->conversions = mt_rand(
                    self::MIN_CONVERSIONS,
                    self::MAX_CONVERSIONS
                ) / mt_getrandmax();
                $prefecture->convRate = mt_rand(
                    self::MIN_CONV_RATE,
                    self::MAX_CONV_RATE
                ) / mt_getrandmax();
                $prefecture->convValue = mt_rand(
                    self::MIN_CONV_VALUE,
                    self::MAX_CONV_VALUE
                ) / mt_getrandmax();
                $prefecture->costPerConv = mt_rand(
                    self::MIN_COST_PER_CONV,
                    self::MAX_COST_PER_CONV
                ) / mt_getrandmax();
                $prefecture->valuePerConv = mt_rand(
                    self::MIN_VALUE_PER_CONV,
                    self::MAX_VALUE_PER_CONV
                ) / mt_getrandmax();
                $prefecture->allConv = mt_rand(
                    self::MIN_ALL_CONV,
                    self::MAX_ALL_CONV
                ) / mt_getrandmax();
                $prefecture->allConvRate = mt_rand(
                    self::MIN_ALL_CONV_RATE,
                    self::MAX_ALL_CONV_RATE
                ) / mt_getrandmax();
                $prefecture->allConvValue = mt_rand(
                    self::MIN_ALL_CONV_VALUE,
                    self::MAX_ALL_CONV_VALUE
                ) / mt_getrandmax();
                $prefecture->costPerAllConv = mt_rand(
                    self::MIN_COST_PER_ALL_CONV,
                    self::MAX_COST_PER_ALL_CONV
                ) / mt_getrandmax();
                $prefecture->valuePerAllConv = mt_rand(
                    self::MIN_VALUE_PER_ALL_CONV,
                    self::MAX_VALUE_PER_ALL_CONV
                ) / mt_getrandmax();
                $prefecture->network = self::NETWORKS[mt_rand(0, count(self::NETWORKS) - 1)];
                $prefecture->device = self::DEVICES[mt_rand(0, count(self::DEVICES) - 1)];
                $prefecture->day = $adgroupReport->day;
                $prefecture->dayOfWeek = $adgroupReport->dayOfWeek;
                $prefecture->quarter = $adgroupReport->quarter;
                $prefecture->month = $adgroupReport->month;
                $prefecture->week = $adgroupReport->week;
                $prefecture->countryTerritory = self::COUNTRY_TERRITORY[mt_rand(0, count(self::COUNTRY_TERRITORY) - 1)];
                $prefecture->prefecture = self::PREFECTURE[mt_rand(0, count(self::PREFECTURE) - 1)];
                $prefecture->city = self::CITY[mt_rand(0, count(self::CITY) - 1)];
                $prefecture->cityWardDistrict = self::CITY_WAR_DISTRICT[mt_rand(0, count(self::CITY_WAR_DISTRICT) - 1)];
            }
        }
    }
}
