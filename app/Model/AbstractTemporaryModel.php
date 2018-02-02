<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\AbstractReportModel;
use Illuminate\Support\Facades\Auth;
use DB;

abstract class AbstractTemporaryModel extends AbstractReportModel
{
    const UNSET_COLUMNS = [
        '[conversionValues]',
        'web_cv',
        'web_cvr',
        'web_cpa',
    ];

    const TABLE_TEMPORARY = 'temporary_table';

    const FIELDS_CALL_TRACKING = [
        '[phoneNumberValues]',
        'call_cv',
        'call_cvr',
        'call_cpa',
        'total_cv',
        'total_cvr',
        'total_cpa'
    ];
    private $groupCallTracking = [];

    const FIELDS_TYPE_STRING = [
        'network',
        'conversionName',
        'source',
        'phone_number',
        'utm_campaign',
        'campaign',
        'adGroup',
        'account_id',
        'campaign_id',
        'campaignName',

    ];

    const FILEDS_TYPE_BIGINT = [
        'adGroupID',
        'clicks',
        'impressions',
        'campaignID',
        'customerID',
        'adID'
    ];

    const FIX_FIELDS = [
        'day',
        'conversions',
        'account_id',
        'campaign_id',
        'campaignID'
    ];
    protected function createTemporaryTable(
        array $fieldNames,
        $isConv = false,
        $isCallTracking = false,
        $conversionPoints = null,
        $adGainerCampaigns = null
    ) {

        $fieldNames = $this->unsetColumns(
            $fieldNames,
            array_merge(self::FIELDS_CALL_TRACKING, self::UNSET_COLUMNS)
        );
        $fieldNames = array_merge($fieldNames, self::FIX_FIELDS);

        $fieldNames = $this->checkAndUpdateFiledNames(
            $fieldNames,
            $isConv,
            $isCallTracking,
            $conversionPoints,
            $adGainerCampaigns
        );

        Schema::create(
            self::TABLE_TEMPORARY,
            function (Blueprint $table) use ($fieldNames) {
                $table->increments('id');
                foreach ($fieldNames as $key => $fieldName) {
                    if (in_array($fieldName, self::FILEDS_TYPE_BIGINT)) {
                        $table->bigInteger($key);
                    } elseif (in_array($fieldName, $this->groupCallTracking)) {
                        $table->integer($key);
                    } elseif (in_array($fieldName, self::FIELDS_TYPE_STRING)) {
                        $table->string($key)->nullable();
                    } elseif ($fieldName === 'day') {
                        $table->dateTime($key)->nullable();
                    } else {
                        $table->double($key)->nullable();
                    }

                    $table->index($key, 'IX_'.$key);
                }
                $table->temporary();
            }
        );
    }

    private function checkAndUpdateFiledNames(
        $fieldNames,
        $isConv,
        $isCallTracking,
        $conversionPoints,
        $adGainerCampaigns
    ) {
        if ($isConv) {
            $conversionNames = array_unique($conversionPoints->pluck('conversionName')->toArray());
            foreach ($conversionNames as $i => $conversionName) {
                array_unshift($fieldNames, 'conversions'.$i);
            }
        }

        if ($isCallTracking) {
            foreach ($adGainerCampaigns as $i => $adgeiner) {
                array_unshift($fieldNames, 'call'.$i);
                array_unshift($this->groupCallTracking, 'call'.$i);
            }
        }
        $fieldNames = $this->updateFieldNames($fieldNames);
        return $fieldNames;
    }
}
