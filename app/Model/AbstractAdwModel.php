<?php

namespace App\Model;

use App\AbstractReportModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

abstract class AbstractAdwModel extends AbstractTemporaryModel
{
    protected $conversionPoints;
    protected $adGainerCampaigns;

    protected function getAggregated(array $fieldNames, array $higherLayerSelections = null, $tableName = '')
    {
        return $this->getAggregatedToUpdateTemporaryTable($fieldNames, $higherLayerSelections, $tableName);
    }

    protected function addRawExpressionsConversionPoint(array $expressions, $tableName = "")
    {
        $conversionNames = array_values(array_unique($this->conversionPoints->pluck('conversionName')->toArray()));
        if ($conversionNames !== null) {
            foreach ($conversionNames as $i => $conversionName) {
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    .$tableName
                    . "`.`conversions".$i."`), 0) AS 'Adw "
                    . $conversionName
                    . "<br>"
                    . " CV'"
                );
                $expressions[] = DB::raw(
                    'IFNULL((SUM(`'
                    . $tableName
                    . '`.`conversions'.$i.'`) / SUM(`'
                    . $tableName
                    . "`.`clicks`)) * 100, 0) AS 'Adw "
                    . $conversionName
                    . "<br>"
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    . $tableName
                    . '`.`cost`) / SUM(`'
                    . $tableName
                    . "`.`conversions".$i."`), 0) AS 'Adw "
                    . $conversionName
                    . "<br>"
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
                    . "`, 0) AS 'Adw "
                    . $campaign->campaign_name
                    . "<br>"
                    . $campaign->phone_number
                    . "<br>"
                    . " CV'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(`call'
                    . $i
                    . '` / SUM(`'
                    . $tableName
                    . "`.`clicks`), 0) AS 'Adw "
                    . $campaign->campaign_name
                    . "<br>"
                    . $campaign->phone_number
                    . "<br>"
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    . $tableName
                    . '`.`cost`) / `call'
                    . $i
                    . "`, 0) AS 'Adw "
                    . $campaign->campaign_name
                    . "<br>"
                    . $campaign->phone_number
                    . "<br>"
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

    protected function getBuilderForGetDataForTable(
        $engine,
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $columnSort,
        $sort,
        $groupedByField,
        $keyPrefix,
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
        $adIDs = array_unique($this->conversionPoints->pluck('adID')->toArray());
        $adgroupIDs = array_unique($this->conversionPoints->pluck('adgroupID')->toArray());
        $campaigns = new Campaign;
        $this->adGainerCampaigns = $campaigns->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'adw',
            $campaignIDs,
            static::PAGE_ID,
            $adIDs,
            $adgroupIDs
        );
        $fieldNames = $this->checkConditionFieldName($fieldNames);
        $builder = parent::getBuilderForGetDataForTable(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $columnSort,
            $sort,
            $groupedByField,
            $keyPrefix,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
        );
        if ($this->isConv || $this->isCallTracking) {
            $columns = $fieldNames;
            if ($this->isSearchQueryReport && !in_array(static::PAGE_ID, $columns)) {
                array_splice($columns, 1, 0, static::PAGE_ID);
            } elseif (!in_array(static::PAGE_ID, $columns)) {
                array_unshift($columns, static::PAGE_ID);
            }

            if (static::PAGE_ID !== 'campaignID') {
                $columns  = $this->higherSelectionFields($columns, $campaignId, $adGroupId);
            }
            $this->createTemporaryTable(
                $columns,
                $this->isConv,
                $this->isCallTracking,
                $this->conversionPoints,
                $this->adGainerCampaigns
            );
            $columns = $this->unsetColumns(
                $columns,
                array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING)
            );
            $columns = array_keys($this->updateFieldNames($columns));
            if(isset($this->isSearchQueryReport)) {
                $columns = $this->unsetColumns($columns, ['impressionShare']);
            }
            DB::insert('INSERT into '.self::TABLE_TEMPORARY.' ('.implode(', ', $columns).') '
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
            $arr = [];
            if (static::PAGE_ID !== 'adID' && in_array('impressionShare', $fieldNames)) {
                $arr[] = DB::raw("IFNULL(ROUND(impressionShare, 2), 0) AS impressionShare");
            }
            $fields = $this->unsetColumns($fieldNames, ['impressionShare']);
            $aggregated = $this->processGetAggregated(
                $fields,
                $groupedByField,
                $campaignId,
                $adGroupId
            );
            $allColumns = $this->getAllColumns(
                DB::table(self::TABLE_TEMPORARY)->select(array_merge($aggregated, $arr))->columns
            );
            $columnSort = $this->getSortColumn($keyPrefix, $allColumns, $columnSort);
            $builder = DB::table(self::TABLE_TEMPORARY)
                ->select(array_merge($aggregated, $arr))
                ->groupBy(array_unique($this->groupBy))
                ->orderBy($columnSort, $sort);
        }

        if (static::PAGE_ID === 'adgroupID') {
            $model = new RepoAdwAdReportCost();

            $query = $model->getQueryForDataTable(
                $engine,
                $fieldNames,
                $accountStatus,
                $startDay,
                $endDay,
                $columnSort,
                $sort,
                $groupedByField,
                $keyPrefix,
                $agencyId,
                $accountId,
                $clientId,
                $campaignId,
                $adGroupId,
                $adReportId,
                $keywordId,
                $this->conversionPoints,
                $this->adGainerCampaigns
            );
            $builder = $builder->union($query);
            $outerQuery = DB::query()
                ->from(DB::raw("({$this->getBindingSql($builder)}) AS tbl"))
                ->groupBy(array_unique($this->groupBy))
                ->orderBy($columnSort, $sort);
            $results = $outerQuery->get();
            return isset($results) ? $results : [];
        }
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
        $fieldNames = $this->checkConditionFieldName($fieldNames);
        if (in_array('adType', $fieldNames)) {
            $fieldNames = $this->unsetColumns($fieldNames, ['adType']);
        }
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

        if ($this->isConv || $this->isCallTracking) {
            $arr = [];
            if (static::PAGE_ID !== 'adID' && in_array('impressionShare', $fieldNames)) {
                $arr[] = DB::raw("IFNULL(ROUND(impressionShare, 2), 0) AS impressionShare");
            }
            $fields = $this->unsetColumns($fieldNames, ['impressionShare']);
            $aggregated = $this->processGetAggregated(
                $fields,
                $groupedByField,
                $campaignId,
                $adGroupId
            );

            $builder = DB::table(self::TABLE_TEMPORARY)->select(
                array_merge(
                    $aggregated,
                    $arr
                )
            );
        }

        if (static::PAGE_ID === 'adgroupID') {
            $model = new RepoAdwAdReportCost();

            $query = $model->getQueryForCalculateData(
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
                $keywordId,
                $this->conversionPoints,
                $this->adGainerCampaigns
            );
            $builder->union($query);
        }

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
                ->where('customerID', '=', $accountId);
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
        } elseif ($column === 'adgroupID') {
            array_unshift($arraySelect, 'campaignID', 'adgroupID');
        } elseif ($column === 'adID') {
            array_unshift($arraySelect, 'campaignID', 'adgroupID', 'adID');
        } elseif ($column === 'keywordID') {
            array_unshift($arraySelect, 'campaignID', 'adgroupID', 'keywordID');
        }
        return $arraySelect;
    }
}
