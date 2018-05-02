<?php

namespace App\Model;

use App\AbstractReportModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

abstract class AbstractYdnReportModel extends AbstractTemporaryModel
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
                    . $tableName
                    . "`.`conversions".$i."`), 0) AS 'YDN "
                    . $conversionName
                    . "<br>"
                    . " CV'"
                );
                $expressions[] = DB::raw(
                    'IFNULL((SUM(`'
                    . $tableName
                    . '`.`conversions'.$i.'`) / SUM(`'
                    . $tableName
                    . "`.`clicks`)) * 100, 0) AS 'YDN "
                    . $conversionName
                    . "<br>"
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`'
                    . $tableName
                    . '`.`cost`) / SUM(`'
                    . $tableName
                    . "`.`conversions".$i."`), 0) AS 'YDN "
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
                    . "`, 0) AS 'YDN "
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
                    . "`.`clicks`), 0) AS 'YDN "
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
                    . "`, 0) AS 'YDN "
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
        if ($groupedByField === "prefecture") {
            $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare']);
        }
        $this->conversionPoints = $this->getAllDistinctConversionNames(
            $clientId,
            $accountId,
            $campaignId,
            $adGroupId,
            $adReportId,
            static::PAGE_ID
        );
        $campaignIDs = array_unique($this->conversionPoints->pluck('campaignID')->toArray());
        $adIDs = array_unique($this->conversionPoints->pluck('adID')->toArray());
        $campaigns = new Campaign;
        $this->adGainerCampaigns = $campaigns->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'ydn',
            $campaignIDs,
            static::PAGE_ID,
            $adIDs
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
        if ($this->isConv === true || $this->isCallTracking === true) {
            $columns = $fieldNames;
            $columns = $this->adjustTemporaryTableColumns($columns, $campaignId, $adGroupId);

            $this->createTemporaryTable(
                $columns,
                $this->isConv,
                $this->isCallTracking,
                $this->conversionPoints,
                $this->adGainerCampaigns
            );

            $columns = $this->unsetColumns($columns, array_merge(self::UNSET_COLUMNS, self::FIELDS_CALL_TRACKING));

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
            $aggregated = $this->processGetAggregated($fieldNames, $groupedByField, $campaignId, $adGroupId);
            $allColumns = $this->getAllColumns(DB::table(self::TABLE_TEMPORARY)->select($aggregated)->columns);
            $columnSort = $this->getSortColumn($keyPrefix, $allColumns, $columnSort);

            $builder = DB::table(self::TABLE_TEMPORARY)
            ->select($aggregated)
            ->groupby($groupedByField)
            ->orderBy($columnSort, $sort);
        }
        return $builder;
    }

    protected function adjustTemporaryTableColumns(
        $columns,
        $campaignId = null,
        $adGroupId = null
    ) {
        if (!in_array(static::PAGE_ID, $columns)) {
            array_unshift($columns, static::PAGE_ID);
        }

        if (static::PAGE_ID !== 'campaignID') {
            $columns  = $this->higherSelectionFields($columns, $campaignId, $adGroupId);
        }
        return $columns;
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
        if ($groupedByField === "prefecture") {
            $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare']);
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
            $aggregated = $this->processGetAggregated(
                $fieldNames,
                $groupedByField,
                $campaignId,
                $adGroupId
            );
            $builder = DB::table(self::TABLE_TEMPORARY)->select($aggregated);
        }

        return $builder;
    }

    public function getAllDistinctConversionNames(
        $account_id,
        $accountId,
        $campaignId,
        $adGroupId,
        $adReportId,
        $column
    ) {
        $aggregation = $this->getAggregatedConversionName($column);
         return $this->select($aggregation)
            ->distinct()
            ->where(
                function (EloquentBuilder $query) use ($account_id, $accountId, $campaignId, $adGroupId, $adReportId) {
                    $this->addConditonForConversionName(
                        $query,
                        $account_id,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId
                    );
                }
            )
            ->get();
    }

    protected function addConditonForConversionName(
        EloquentBuilder $query,
        $account_id = null,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null
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
        if ($adReportId !== null) {
            $query->where('adID', '=', $adReportId);
        }
    }

    private function getAggregatedConversionName($column)
    {
        $arraySelect = ['conversionName'];
        if ($column === 'campaignID') {
            array_unshift($arraySelect, 'campaignID');
        }

        if ($column === 'adgroupID') {
            array_unshift($arraySelect, 'campaignID', 'adgroupID');
        }

        if ($column === 'adID') {
            array_unshift($arraySelect, 'campaignID', 'adgroupID', 'adID');
        }

        return $arraySelect;
    }
}
