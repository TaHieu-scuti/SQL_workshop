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

    const TABLE_TEMPORARY_AD = 'temporary_table_ad';

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
        'adgroupName',
        'matchType',
        'keywordMatchType',
        'keyword',
        'displayURL',
        'description1',
        'description',
        'adName',
        'ad',
        'adType'
    ];

    const FIELDS_TYPE_BIGINT = [
        'adGroupID',
        'clicks',
        'impressions',
        'campaignID',
        'customerID',
        'keywordID',
        'adID'
    ];

    const FIX_FIELDS = [
        'day',
        'conversions',
        'account_id',
        'campaign_id',
    ];

    protected function createTemporaryTable(
        array $fieldNames,
        $isConv = false,
        $isCallTracking = false,
        $conversionPoints = null,
        $adGainerCampaigns = null,
        $preFixRoute = ""
    ) {
        $tableName = self::TABLE_TEMPORARY;
        if ($preFixRoute === 'adgroup') {
            $tableName = self::TABLE_TEMPORARY_AD;
        }
        $fieldNames = $this->unsetColumns(
            $fieldNames,
            array_merge(self::FIELDS_CALL_TRACKING, self::UNSET_COLUMNS)
        );
        $fieldNames = array_merge($fieldNames, self::FIX_FIELDS, [static::PAGE_ID]);

        $fieldNames = $this->checkAndUpdateFieldNames(
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
        array $higherLayerSelections,
        $tableName = ""
    ) {
        $expressions = parent::getAggregated($fieldNames, $higherLayerSelections, $tableName);
        foreach ($fieldNames as $fieldName) {
            switch ($fieldName) {
                case '[conversionValues]':
                    $expressions = $this->addRawExpressionsConversionPoint($expressions, $tableName);
                    break;
                case '[phoneNumberValues]':
                    $expressions = $this->addRawExpressionsPhoneNumberConversions($expressions, $tableName);
                    break;
                case 'call_cv':
                    $expressions = $this->addRawExpressionCallConversions($expressions);
                    break;
                case 'call_cvr':
                    $expressions = $this->addRawExpressionCallConversionRate($expressions);
                    break;
                case 'call_cpa':
                    $expressions = $this->addRawExpressionCallCostPerAction($expressions, $tableName);
                    break;
                case 'web_cv':
                    $expressions[] = DB::raw("IFNULL(SUM(`".$tableName."`.`conversions`), 0) as web_cv");
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw("IFNULL((SUM(`".$tableName."`.`conversions`) /
                    SUM(`".$tableName."`.`clicks`)) * 100, 0) as web_cvr");
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw("IFNULL(SUM(`".$tableName."`.`cost`) /
                    SUM(`".$tableName."`.`conversions`), 0) as web_cpa");
                    break;
                case 'total_cv':
                    $expressions = $this->addRawExpressionTotalConversions($expressions, $tableName);
                    break;
                case 'total_cvr':
                    $expressions = $this->addRawExpressionTotalConversionRate($expressions, $tableName);
                    break;
                case 'total_cpa':
                    $expressions = $this->addRawExpressionTotalCostPerAction($expressions, $tableName);
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
        $adGroupId,
        $tableName = ""
    ) {
        if (empty($tableName)) {
            $tableName = self::TABLE_TEMPORARY;
        }
        $higherLayerSelections = [];
        if ($groupedByField !== self::DEVICE
            && $groupedByField !== self::HOUR_OF_DAY
            && $groupedByField !== self::DAY_OF_WEEK
            && $groupedByField !== self::PREFECTURE
        ) {
            $higherLayerSelections = $this->higherLayerSelections($fieldNames, $campaignId, $adGroupId, $tableName);
        }
        $aggregations = $this->getAggregatedForTemporary($fieldNames, $higherLayerSelections, $tableName);
        $selectBy = static::FIELDS;
        if ($tableName === self::TABLE_TEMPORARY_AD) {
            $selectBy = static::FIELDS_ADGROUP_ADW;
        }

        return array_merge($selectBy, $aggregations);
    }

    protected function higherSelectionFields($columns, $campaignId, $adGroupId, $preFixRoute = "")
    {
        $arrayAlias = [];
        if (!isset($campaignId)) {
            array_push($arrayAlias, 'campaignID');
            array_push($arrayAlias, 'campaignName');
        }
        if (!isset($adGroupId) && static::PAGE_ID !== 'adgroupID') {
            array_push($arrayAlias, 'adgroupID');
            array_push($arrayAlias, 'adgroupName');
        }
        array_splice($columns, 2, 0, $arrayAlias);
        if (session(self::SESSION_KEY_ENGINE) === 'yss' && $key = array_search('matchType', $columns)) {
            $columns[$key] = 'keywordMatchType';
        }

        if (session(self::SESSION_KEY_ENGINE) !== 'yss' && static::PAGE_ID === 'adID' && $preFixRoute = "") {
            $columns = array_merge(static::FIELDS, $columns);
        }

        return $columns;
    }

    protected function getFieldName($campaign, $field)
    {
        $fieldName = '';

        if ($campaign->camp_custom1 === $field) {
            $fieldName = 'custom1';
        } elseif ($campaign->camp_custom2 === $field) {
            $fieldName = 'custom2';
        } elseif ($campaign->camp_custom3 === $field) {
            $fieldName = 'custom3';
        } elseif ($campaign->camp_custom4 === $field) {
            $fieldName = 'custom4';
        } elseif ($campaign->camp_custom5 === $field) {
            $fieldName = 'custom5';
        } elseif ($campaign->camp_custom6 === $field) {
            $fieldName = 'custom6';
        } elseif ($campaign->camp_custom7 === $field) {
            $fieldName = 'custom7';
        } elseif ($campaign->camp_custom8 === $field) {
            $fieldName = 'custom8';
        } elseif ($campaign->camp_custom9 === $field) {
            $fieldName = 'custom9';
        } elseif ($campaign->camp_custom10 === $field) {
            $fieldName = 'custom10';
        }

        return $fieldName;
    }
}
