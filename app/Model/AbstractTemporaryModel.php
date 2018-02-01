<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;
use Illuminate\Support\Facades\Auth;

Abstract class AbstractTemporaryModel extends AbstractReportModel
{
    const FIELDS_CONV_POINT = [
        '[conversionValues]'
    ];
    const FIELDS_CALL_TRACKING = [
        '[phoneNumberValues]',
        'call_cv',
        'call_cvr',
        'call_cpa',
        'total_cv',
        'total_cvr',
        'total_cpa'
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
            array_merge(self::FIELDS_CALL_TRACKING, self::FIELDS_CONV_POINT)
        );
        $tableName = 'temporary_'.Auth::user()->account_id.'_Table';
        $fieldNames = $this->checkAndUpdateFiledNames(
            $fieldNames,
            $isConv,
            $isCallTracking,
            $conversionPoints,
            $adGainerCampaigns
        );

        Schema::create(
            $tableName,
            function (Blueprint $table) use ($fieldNames) {
                $table->increments('id');
                foreach ($fieldNames as $fieldName) {

                }
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
            }
        }

        return $fieldNames;
    }

}
