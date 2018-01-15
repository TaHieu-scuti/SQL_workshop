<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use App\AbstractReportModel;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

use DB;

abstract class AbstractYssReportModel extends AbstractReportModel
{
    private $conversionPoints;
    private $adGainerCampaigns;

    protected function addJoinConditions(JoinClause $join, $joinAlias)
    {
        $join->on($joinAlias.'_campaigns.account_id', '=', $this->table . '.account_id')
            ->on($joinAlias.'_campaigns.campaign_id', '=', $this->table . '.campaign_id')
            ->on(
                function (Builder $builder) use ($joinAlias) {
                    $builder->where(
                        function (Builder $builder) use ($joinAlias) {
                            $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom1` = 'adgroupid'")
                                ->whereRaw("`".$joinAlias."`.`custom1` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) use ($joinAlias) {
                            $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom2` = 'adgroupid'")
                                ->whereRaw("`".$joinAlias."`.`custom2` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) use ($joinAlias) {
                            $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom3` = 'adgroupid'")
                                ->whereRaw("`".$joinAlias."`.`custom3` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) use ($joinAlias) {
                            $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom4` = 'adgroupid'")
                                ->whereRaw("`".$joinAlias."`.`custom4` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) use ($joinAlias) {
                            $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom5` = 'adgroupid'")
                                ->whereRaw("`".$joinAlias."`.`custom5` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) use ($joinAlias) {
                            $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom6` = 'adgroupid'")
                                ->whereRaw("`".$joinAlias."`.`custom6` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) use ($joinAlias) {
                            $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom7` = 'adgroupid'")
                                ->whereRaw("`".$joinAlias."`.`custom7` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) use ($joinAlias) {
                            $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom8` = 'adgroupid'")
                                ->whereRaw("`".$joinAlias."`.`custom8` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) use ($joinAlias) {
                            $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom9` = 'adgroupid'")
                                ->whereRaw("`".$joinAlias."`.`custom9` = `{$this->table}`.`adgroupID`");
                        }
                    )->orWhere(
                        function (Builder $builder) use ($joinAlias) {
                            $builder->whereRaw("`".$joinAlias."_campaigns`.`camp_custom10` = 'adgroupid'")
                                ->whereRaw("`".$joinAlias."`.`custom10` = `{$this->table}`.`adgroupID`");
                        }
                    );
                }
            )
            ->on($joinAlias.'.account_id', '=', $this->table . '.account_id')
            ->on($joinAlias.'.campaign_id', '=', $this->table . '.campaign_id')
            ->on($joinAlias.'.utm_campaign', '=', $this->table . '.campaignID')
            ->on(
                DB::raw("STR_TO_DATE(`".$joinAlias."`.`time_of_call`, '%Y-%m-%d')"),
                '=',
                $this->table . '.day'
            )
            ->where($joinAlias.'.source', '=', 'yss')
            ->where($joinAlias.'.traffic_type', '=', 'AD');
    }

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
            $adGroupId,
            static::PAGE_ID
        );
        $campaignIDs = array_unique($this->conversionPoints->pluck('campaignID')->toArray());
        $campaigns = new Campaign;
        $this->adGainerCampaigns = $campaigns->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'yss',
            $campaignIDs,
            static::PAGE_ID
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

        if ($column === 'keyword') {
            array_unshift($arraySelect, 'campaignID', 'adgroupID', 'keyword');
        }

        return $arraySelect;
    }
}
