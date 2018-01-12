<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use App\AbstractReportModel;

use DB;

abstract class AbstractYssReportModel extends AbstractReportModel
{
    private $conversionPoints;
    private $adGainerCampaigns;

    protected function getAggregated(array $fieldNames, array $higherLayerSelections = null)
    {
        $expressions = parent::getAggregated($fieldNames, $higherLayerSelections);
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
                    $expressions[] = DB::raw("IFNULL(SUM(`{$this->table}`.`conversions`), 0) as web_cv");
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw("IFNULL((SUM(`{$this->table}`.`conversions`) /
                    SUM(`{$this->table}`.`clicks`)) * 100, 0) as web_cvr");
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw("IFNULL(SUM(`{$this->table}`.`cost`) /
                    SUM(`{$this->table}`.`conversions`), 0) as web_cpa");
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

    private function addRawExpressionsConversionPoint(array $expressions)
    {
        $conversionNames = array_unique($this->conversionPoints->pluck('conversionName')->toArray());
        if ($conversionNames !== null) {
            foreach ($conversionNames as $i => $conversionName) {
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`conv'
                    . $i
                    . "`.`conversions`), 0) AS 'YSS "
                    . $conversionName
                    . " CV'"
                );
                $expressions[] = DB::raw(
                    'IFNULL((SUM(`conv'
                    . $i
                    . '`.`conversions`) / SUM(`'
                    . $this->getTable()
                    . "`.`clicks`)) * 100, 0) AS 'YSS "
                    . $conversionName
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    . $this->getTable()
                    . '`.`cost`) / SUM(`conv'
                    . $i
                    . "`.`conversions`), 0) AS 'YSS "
                    . $conversionName
                    . " CPA'"
                );
            }
        }

        return $expressions;
    }

    private function addRawExpressionsPhoneNumberConversions(array $expressions)
    {
        if ($this->adGainerCampaigns !== null) {
            foreach ($this->adGainerCampaigns as $i => $campaign) {
                $expressions[] = DB::raw(
                    'IFNULL(COUNT(`call'
                    . $i
                    . "`.`id`), 0) AS 'YSS "
                    . $campaign->campaign_name
                    . ' '
                    . $campaign->phone_number
                    . " CV'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(COUNT(`call'
                    . $i
                    . '`.`id`) / SUM(`'
                    . $this->table
                    . "`.`clicks`), 0) AS 'YSS "
                    . $campaign->campaign_name
                    . ' '
                    . $campaign->phone_number
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    . $this->table
                    . '`.`cost`) / COUNT(`call'
                    . $i
                    . "`.`id`), 0) AS 'YSS "
                    . $campaign->campaign_name
                    . ' '
                    . $campaign->phone_number
                    . " CPA'"
                );
            }
        }

        return $expressions;
    }

    private function addRawExpressionTotalCostPerAction(array $expressions)
    {
        $expression = 'IFNULL(SUM(`' . $this->table . '`.`cost`) / (SUM(`' . $this->table . '`.`conversions`) + ';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= 'COUNT(`call'
                . $i
                . '`.`id`) + ';
        }

        $expression .= 'COUNT(`call'
            . ($numberOfCampaigns - 1)
            . '`.`id`)), 0) AS total_cpa';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    private function addRawExpressionTotalConversionRate(array $expressions)
    {
        $expression = 'IFNULL((SUM(`' . $this->table . '`.`conversions`) + ';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= 'COUNT(`call'
                . $i
                . '`.`id`) + ';
        }

        $expression .= 'COUNT(`call'
            . ($numberOfCampaigns - 1)
            . '`.`id`)) / '
            . 'SUM(`'
            . $this->table
            . '`.`clicks`), 0) AS total_cvr';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    private function addRawExpressionTotalConversions(array $expressions)
    {
        $expression = 'IFNULL(SUM(`' . $this->table . '`.`conversions`) + ';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= 'COUNT(`call'
                . $i
                . '`.`id`) + ';
        }

        $expression .= 'COUNT(`call'
            . ($numberOfCampaigns - 1)
            . '`.`id`), 0) AS total_cv';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    private function addRawExpressionCallCostPerAction(array $expressions)
    {
        $expression = 'IFNULL(SUM(`' . $this->table . '`.`cost`) / (';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= 'COUNT(`call'
                . $i
                . '`.`id`) + ';
        }

        $expression .= 'COUNT(`call'
            . ($numberOfCampaigns - 1)
            . '`.`id`)), 0) AS call_cpa';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    private function addRawExpressionCallConversions(array $expressions)
    {
        $expression = 'IFNULL(';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= 'COUNT(`call' . $i . '`.`id`) + ';
        }

        $expression .= 'COUNT(`call' . ($numberOfCampaigns - 1) . '`.`id`), 0) AS call_cv';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    private function addRawExpressionCallConversionRate(array $expressions)
    {
        $expression = 'IFNULL((';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= 'COUNT(`call'
                . $i
                . "`.`id`) + ";
        }

        $expression .= 'COUNT(`call'
            . ($numberOfCampaigns - 1)
            . '`.`id`)) / '
            . $numberOfCampaigns
            . ', 0) AS call_cvr';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    protected function getBuilderForGetDataForTable(
        $engine,
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $columnSort,
        $sort,
        $groupedByField,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $this->conversionPoints = $this->getAllDistinctConversionNames(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId
        );
        $campaignIDs = array_unique($this->conversionPoints->pluck('campaignID')->toArray());

        $campaigns = new Campaign;
        $this->adGainerCampaigns = $campaigns->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'yss',
            $campaignIDs
        );
        $builder = parent::getBuilderForGetDataForTable(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $columnSort,
            $sort,
            $groupedByField,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );

        $this->addJoin($builder, $this->conversionPoints, $this->adGainerCampaigns);
        return $builder;
    }

    protected function getBuilderForCalculateData(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $groupedByField,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $builder = parent::getBuilderForCalculateData(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $groupedByField,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );

        $this->addJoin($builder, $this->conversionPoints, $this->adGainerCampaigns);

        return $builder;
    }

    protected function getAggregatedConversionName($campaignId, $adGroupId)
    {
        $arraySelect = ['campaignID', 'conversionName'];
        if ($campaignId !== null) {
            $arraySelect[] = 'adgroupID';
        }

        if ($adGroupId !== null) {
            $arraySelect[] = 'adID';
        }

        return $arraySelect;
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
        } elseif ($campaignId !== null) {
            $query->where('campaignID', '=', $campaignId);
        } elseif ($adGroupId !== null) {
            $query->where('adgroupID', '=', $adGroupId);
        }
    }
}
