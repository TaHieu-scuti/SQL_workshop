<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use App\AbstractReportModel;
use Illuminate\Support\Facades\Auth;
use DB;

abstract class AbstractTemporaryModel extends AbstractReportModel
{
    protected $isConv = false;
    protected $isCallTracking = false;

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

    const FIELDS_TYPE_BIGINT = [
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

        $fieldNames = $this->checkAndUpdateFieldNames(
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
                    if (in_array($fieldName, self::FIELDS_TYPE_BIGINT)) {
                        $table->bigInteger($key)->nullable();
                    } elseif (in_array($fieldName, $this->groupCallTracking)) {
                        $table->integer($key)->nullable();
                    } elseif (in_array($fieldName, self::FIELDS_TYPE_STRING)) {
                        $table->string($key)->nullable();
                    } elseif ($fieldName === 'day') {
                        $table->dateTime($key)->nullable();
                    } else {
                        $table->double($key)->nullable();
                    }
                }
                $table->temporary();
            }
        );
    }

    protected function getAggregatedForTemporary(
        array $fieldNames,
        array $higherLayerSelections = null
    ) {
        $tableName = null;
        if ($this->isConv || $this->isCallTracking) {
            $tableName = self::TABLE_TEMPORARY;
        }
        $expressions = parent::getAggregated($fieldNames, $higherLayerSelections, $tableName);
        foreach ($fieldNames as $fieldName) {
            switch ($fieldName) {
                case '[conversionValues]':
                    $expressions = $this->addRawExpressionsConversionPoint($expressions);
                    break;
                case '[phoneNumberValues]':
                    $expressions = $this->addRawExpressionsPhoneNumberConversions($expressions);
                    break;
                case 'call_cv':
                    $expressions = $this->addRawExpressionCallConversions($expressions);
                    break;
                case 'call_cvr':
                    $expressions = $this->addRawExpressionCallConversionRate($expressions);
                    break;
                case 'call_cpa':
                    $expressions = $this->addRawExpressionCallCostPerAction($expressions);
                    break;
                case 'web_cv':
                    $expressions[] = DB::raw("IFNULL(SUM(`".self::TABLE_TEMPORARY."`.`conversions`), 0) as web_cv");
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw("IFNULL((SUM(`".self::TABLE_TEMPORARY."`.`conversions`) /
                    SUM(`".self::TABLE_TEMPORARY."`.`clicks`)) * 100, 0) as web_cvr");
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw("IFNULL(SUM(`".self::TABLE_TEMPORARY."`.`cost`) /
                    SUM(`".self::TABLE_TEMPORARY."`.`conversions`), 0) as web_cpa");
                    break;
                case 'total_cv':
                    $expressions = $this->addRawExpressionTotalConversions($expressions);
                    break;
                case 'total_cvr':
                    $expressions = $this->addRawExpressionTotalConversionRate($expressions);
                    break;
                case 'total_cpa':
                    $expressions = $this->addRawExpressionTotalCostPerAction($expressions);
                    break;
            }
        }
        return $expressions;
    }

    private function checkAndUpdateFieldNames(
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
            foreach ($adGainerCampaigns as $i => $adgainer) {
                array_unshift($fieldNames, 'call'.$i);
                array_unshift($this->groupCallTracking, 'call'.$i);
            }
        }
        return $this->updateFieldNames($fieldNames);
    }

    protected function addConditonForDate(EloquentBuilder $query, $tableName, $startDay, $endDay)
    {
        if ($startDay === $endDay) {
            $query->whereRaw('STR_TO_DATE('.$tableName.
                '.time_of_call, "%Y-%m-%d %H:%i:%s") LIKE "'.$endDay.'%"');
        } else {
            $query->whereRaw('STR_TO_DATE('.$tableName.
                '.time_of_call, "%Y-%m-%d %H:%i:%s") >= "'.$startDay.'"')
                ->whereRaw('STR_TO_DATE('.$tableName.
                    '.time_of_call, "%Y-%m-%d %H:%i:%s") <= "'.$endDay.'"');
        }
    }

    protected function processGetAggregated(
        $fieldNames,
        $groupedByField,
        $campaignId,
        $adGroupId
    ) {
        $higherLayerSelections = [];
        if ($groupedByField !== self::DEVICE
            && $groupedByField !== self::HOUR_OF_DAY
            && $groupedByField !== self::DAY_OF_WEEK
            && $groupedByField !== self::PREFECTURE
        ) {
            $higherLayerSelections = $this->higherLayerSelections($campaignId, $adGroupId);
        }
        return $this->getAggregatedForTemporary($fieldNames, $higherLayerSelections);
    }
}
