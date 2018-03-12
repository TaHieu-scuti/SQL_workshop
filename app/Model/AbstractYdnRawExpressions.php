<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class AbstractYdnRawExpressions extends AbstractTemporaryModel
{
    protected $conversionPoints;
    protected $adGainerCampaigns;

    protected function addRawExpressionsConversionPoint(array $expressions, $tableName = "")
    {
        $conversionNames = array_values(array_unique($this->conversionPoints->pluck('conversionName')->toArray()));
        if ($conversionNames !== null) {
            foreach ($conversionNames as $i => $conversionName) {
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    . $tableName
                    . "`.`conversions".$i."`), 0) AS 'YDN "
                    . $conversionName
                    . " CV'"
                );
                $expressions[] = DB::raw(
                    'IFNULL((SUM(`'
                    . $tableName
                    . '`.`conversions'.$i.'`) / SUM(`'
                    . $tableName
                    . "`.`clicks`)) * 100, 0) AS 'YDN "
                    . $conversionName
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    . $tableName
                    . '`.`cost`) / SUM(`'
                    . $tableName
                    . "`.`conversions".$i."`), 0) AS 'YDN "
                    . $conversionName
                    . " CPA'"
                );
            }
        }

        return $expressions;
    }

    protected function addRawExpressionsPhoneNumberConversions(array $expressions, $tableName = "")
    {
        if ($this->adGainerCampaigns !== null) {
            foreach ($this->adGainerCampaigns as $i => $campaign) {
                $expressions[] = DB::raw(
                    'IFNULL(`call'
                    . $i
                    . "`, 0) AS 'YDN "
                    . $campaign->campaign_name
                    . ' '
                    . $campaign->phone_number
                    . " CV'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(`call'
                    . $i
                    . '` / SUM(`'
                    . $tableName
                    . "`.`clicks`), 0) AS 'YDN "
                    . $campaign->campaign_name
                    . ' '
                    . $campaign->phone_number
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    . $tableName
                    . '`.`cost`) / `call'
                    . $i
                    . "`, 0) AS 'YDN "
                    . $campaign->campaign_name
                    . ' '
                    . $campaign->phone_number
                    . " CPA'"
                );
            }
        }

        return $expressions;
    }

    protected function addRawExpressionCallConversions(array $expressions)
    {
        $numberOfCampaigns = count($this->adGainerCampaigns);
        if ($numberOfCampaigns > 0) {
            $expression = 'IFNULL(';
            for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
                $expression .= '`call' . $i . '` + ';
            }

            $expression .= '`call' . ($numberOfCampaigns - 1) . '`, 0) AS call_cv';

            $expressions[] = DB::raw($expression);
        }
        return $expressions;
    }

    protected function addRawExpressionCallConversionRate(array $expressions)
    {
        $numberOfCampaigns = count($this->adGainerCampaigns);
        if ($numberOfCampaigns > 0) {
            $expression = 'IFNULL((';
            for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
                $expression .= '`call'
                    . $i
                    . "` + ";
            }

            $expression .= '`call'
                . ($numberOfCampaigns - 1)
                . '`) / '
                . $numberOfCampaigns
                . ', 0) AS call_cvr';

            $expressions[] = DB::raw($expression);
        }
        return $expressions;
    }

    protected function addRawExpressionCallCostPerAction(array $expressions, $tableName = "")
    {
        $numberOfCampaigns = count($this->adGainerCampaigns);
        if ($numberOfCampaigns > 0) {
            $expression = 'IFNULL(SUM(`' . $tableName . '`.`cost`) / (';
            for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
                $expression .= '`call'
                    . $i
                    . '` + ';
            }

            $expression .= '`call'
                . ($numberOfCampaigns - 1)
                . '`), 0) AS call_cpa';

            $expressions[] = DB::raw($expression);
        }
        return $expressions;
    }

    protected function addRawExpressionTotalConversions(array $expressions, $tableName = "")
    {
        $numberOfCampaigns = count($this->adGainerCampaigns);
        if ($numberOfCampaigns > 0) {
            $expression = 'IFNULL(SUM(`' . $tableName . '`.`conversions`) + ';
            for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
                $expression .= '`call'
                    . $i
                    . '` + ';
            }

            $expression .= '`call'
                . ($numberOfCampaigns - 1)
                . '`, 0) AS total_cv';

            $expressions[] = DB::raw($expression);
        }
        return $expressions;
    }

    protected function addRawExpressionTotalConversionRate(array $expressions, $tableName = "")
    {
        $numberOfCampaigns = count($this->adGainerCampaigns);
        if ($numberOfCampaigns > 0) {
            $expression = 'IFNULL((SUM(`' . $tableName . '`.`conversions`) + ';
            for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
                $expression .= '`call'
                    . $i
                    . '` + ';
            }

            $expression .= '`call'
                . ($numberOfCampaigns - 1)
                . '`) / '
                . 'SUM(`'
                . $tableName
                . '`.`clicks`), 0) AS total_cvr';

            $expressions[] = DB::raw($expression);
        }
        return $expressions;
    }

    protected function addRawExpressionTotalCostPerAction(array $expressions, $tableName = "")
    {
        $numberOfCampaigns = count($this->adGainerCampaigns);
        if ($numberOfCampaigns > 0) {
            $expression = 'IFNULL(SUM(`' . $tableName . '`.`cost`) / (SUM(`'
                . $tableName . '`.`conversions`) + ';
            for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
                $expression .= '`call'
                    . $i
                    . '` + ';
            }

            $expression .= '`call'
                . ($numberOfCampaigns - 1)
                . '`), 0) AS total_cpa';

            $expressions[] = DB::raw($expression);
        }
        return $expressions;
    }

    protected function addConditonForConversionName(
        EloquentBuilder $query,
        $account_id = null,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null
    ) {
        if ($account_id !== null && $accountId !== null) {
            $query->where('account_id', '=', $account_id)
                ->where('accountId', '=', $accountId);
        }
        if ($campaignId !== null) {
            $query->where('campaignID', '=', $campaignId);
        }
        if ($adGroupId !== null) {
            $query->where('adgroupID', '=', $adGroupId);
        }
    }

    protected function getAggregatedConversionName($column)
    {
        $arraySelect = ['conversionName'];
        if ($column === 'campaignID') {
            array_unshift($arraySelect, 'campaignID');
        }

        if ($column === 'adgroupID') {
            array_unshift($arraySelect, 'campaignID', 'adgroupID');
        }

        if ($column === 'keywordID') {
            array_unshift($arraySelect, 'campaignID', 'adgroupID', 'keyword');
        }

        return $arraySelect;
    }

    protected function checkConditionFieldName($fieldNames)
    {
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === '[conversionValues]') {
                $this->isConv = true;
            }

            if (in_array($fieldName, self::FIELDS_CALL_TRACKING)) {
                $this->isCallTracking = true;
            }
        }

        if ($this->isConv || $this->isCallTracking) {
            if (!in_array('cost', $fieldNames)) {
                array_unshift($fieldNames, 'cost');
            }
            if (!in_array('clicks', $fieldNames)) {
                array_unshift($fieldNames, 'clicks');
            }
        }
        return $fieldNames;
    }
}
