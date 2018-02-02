<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use App\AbstractReportModel;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

use DB;

abstract class AbstractYssReportModel extends AbstractTemporaryModel
{
    private $conversionPoints;
    private $adGainerCampaigns;
    private $isConv = false;
    private $isCallTracking = false;

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

    protected function getAggregated(array $fieldNames, array $higherLayerSelections = null, $tableName = null)
    {
        $expressions = parent::getAggregated($fieldNames, $higherLayerSelections);
        return $expressions;
    }

    protected function getAggregatedForTemprary(array $fieldNames, array $higherLayerSelections = null)
    {
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

    private function processGetAggregated($fieldNames, $groupedByField, $campaignId, $adGroupId)
    {
        $higherLayerSelections = [];
        if ($groupedByField !== self::DEVICE
            && $groupedByField !== self::HOUR_OF_DAY
            && $groupedByField !== self::DAY_OF_WEEK
            && $groupedByField !== self::PREFECTURE
        ) {
            $higherLayerSelections = $this->higherLayerSelections($campaignId, $adGroupId);
        }

        $aggregations = $this->getAggregatedForTemprary($fieldNames, $higherLayerSelections);
        return $aggregations;
    }

    private function addRawExpressionsConversionPoint(array $expressions)
    {
        $conversionNames = array_unique($this->conversionPoints->pluck('conversionName')->toArray());
        if ($conversionNames !== null) {
            foreach ($conversionNames as $i => $conversionName) {
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    .self::TABLE_TEMPORARY
                    . "`.`conversions".$i."`), 0) AS 'YSS "
                    . $conversionName
                    . " CV'"
                );
                $expressions[] = DB::raw(
                    'IFNULL((SUM(`'
                    . self::TABLE_TEMPORARY
                    . '`.`conversions'.$i.'`) / SUM(`'
                    . self::TABLE_TEMPORARY
                    . "`.`clicks`)) * 100, 0) AS 'YSS "
                    . $conversionName
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    . self::TABLE_TEMPORARY
                    . '`.`cost`) / SUM(`'
                    . self::TABLE_TEMPORARY
                    . "`.`conversions".$i."`), 0) AS 'YSS "
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
                    'IFNULL(`call'
                    . $i
                    . "`, 0) AS 'YSS "
                    . $campaign->campaign_name
                    . ' '
                    . $campaign->phone_number
                    . " CV'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(`call'
                    . $i
                    . '` / SUM(`'
                    . self::TABLE_TEMPORARY
                    . "`.`clicks`), 0) AS 'YSS "
                    . $campaign->campaign_name
                    . ' '
                    . $campaign->phone_number
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    . self::TABLE_TEMPORARY
                    . '`.`cost`) / `call'
                    . $i
                    . "`, 0) AS 'YSS "
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
        $expression = 'IFNULL(SUM(`' . self::TABLE_TEMPORARY . '`.`cost`) / (SUM(`'
            . self::TABLE_TEMPORARY . '`.`conversions`) + ';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= '`call'
                . $i
                . '` + ';
        }

        $expression .= '`call'
            . ($numberOfCampaigns - 1)
            . '`), 0) AS total_cpa';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    private function addRawExpressionTotalConversionRate(array $expressions)
    {
        $expression = 'IFNULL((SUM(`' . self::TABLE_TEMPORARY . '`.`conversions`) + ';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= '`call'
                . $i
                . '` + ';
        }

        $expression .= '`call'
            . ($numberOfCampaigns - 1)
            . '`) / '
            . 'SUM(`'
            . self::TABLE_TEMPORARY
            . '`.`clicks`), 0) AS total_cvr';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    private function addRawExpressionTotalConversions(array $expressions)
    {
        $expression = 'IFNULL(SUM(`' . self::TABLE_TEMPORARY . '`.`conversions`) + ';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= '`call'
                . $i
                . '` + ';
        }

        $expression .= '`call'
            . ($numberOfCampaigns - 1)
            . '`, 0) AS total_cv';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    private function addRawExpressionCallCostPerAction(array $expressions)
    {
        $expression = 'IFNULL(SUM(`' . self::TABLE_TEMPORARY . '`.`cost`) / (';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= '`call'
                . $i
                . '` + ';
        }

        $expression .= '`call'
            . ($numberOfCampaigns - 1)
            . '`), 0) AS call_cpa';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    private function addRawExpressionCallConversions(array $expressions)
    {
        $expression = 'IFNULL(';
        $numberOfCampaigns = count($this->adGainerCampaigns);
        for ($i = 0; $i < $numberOfCampaigns - 1; $i++) {
            $expression .= '`call' . $i . '` + ';
        }

        $expression .= '`call' . ($numberOfCampaigns - 1) . '`, 0) AS call_cv';

        $expressions[] = DB::raw($expression);

        return $expressions;
    }

    private function addRawExpressionCallConversionRate(array $expressions)
    {
        $expression = 'IFNULL((';
        $numberOfCampaigns = count($this->adGainerCampaigns);
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
        $this->checkconditionfieldName($fieldNames);
        if ($this->isConv || $this->isCallTracking) {
            $this->createTemporaryTable(
                $fieldNames,
                $this->isConv,
                $this->isCallTracking,
                $this->conversionPoints,
                $this->adGainerCampaigns
            );
        }

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
         DB::insert('INSERT into '.self::TABLE_TEMPORARY.' ('.implode(', ', static::FIX_INSERT_FILEDS).') '
             . $this->getBindingSql($builder));

        if ($this->isConv) {
            $this->updateTemporaryTableWithConversion(
                $this->conversionPoints,
                $groupedByField,
                $startDay,
                $endDay,
                $engine,
                $clientId,
                $accountId,
                $campaignId,
                $adGroupId,
                $adReportId,
                $keywordId
            );
        }

        if ($this->isCallTracking) {
            $this->updateTemporaryTableWithCallTracking(
                $this->adGainerCampaigns,
                $groupedByField,
                $startDay,
                $endDay,
                $engine,
                $clientId,
                $accountId,
                $campaignId,
                $adGroupId,
                $adReportId,
                $keywordId
            );
        }
        $aggregated = $this->processGetAggregated($fieldNames, $groupedByField, $campaignId, $adGroupId);

        $builderTemp = DB::table(self::TABLE_TEMPORARY)->select($aggregated)->groupby($groupedByField);
        return $builderTemp;
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
        $aggregated = $this->processGetAggregated($fieldNames, $groupedByField, $campaignId, $adGroupId);
        $builder = DB::table(self::TABLE_TEMPORARY)->select($aggregated);
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

        if ($column === 'keywordID') {
            array_unshift($arraySelect, 'campaignID', 'adgroupID', 'keyword');
        }

        return $arraySelect;
    }

    private function checkconditionfieldName($fieldNames)
    {
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === '[conversionValues]') {
                $this->isConv = true;
            } elseif (in_array($fieldName, self::FIELDS_CALL_TRACKING)) {
                $this->isCallTracking = true;
            }
        }
    }
}
