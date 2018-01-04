<?php

namespace App\Model;

use App\AbstractReportModel;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

abstract class AbstractYdnReportModel extends AbstractReportModel
{
    private $conversionPoints;
    private $adGainerCampaigns;

    private function addRawExpressionsConversionPoint(array $expressions)
    {
        if ($this->conversionPoints !== null) {
            foreach ($this->conversionPoints as $i => $point) {
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`conv'
                    . $i
                    . "`.`conversions`), 0) AS 'YDN "
                    . $point->conversionName
                    . " CV'"
                );
                $expressions[] = DB::raw(
                    'IFNULL((SUM(`conv'
                    . $i
                    . '`.`conversions`) / SUM(`conv'
                    . $i
                    . "`.`clicks`)) * 100, 0) AS 'YDN "
                    . $point->conversionName
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`conv'
                    . $i
                    . '`.`cost`) / SUM(`conv'
                    . $i
                    . "`.`conversions`), 0) AS 'YDN "
                    . $point->conversionName
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
                    . "`.`id`), 0) AS 'YDN "
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
                    . "`.`clicks`), 0) AS 'YDN "
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
                    . "`.`id`), 0) AS 'YDN "
                    . $campaign->campaign_name
                    . ' '
                    . $campaign->phone_number
                    . " CPA'"
                );
            }
        }

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

    /* TODO: check if we still need it */
    protected function addJoin(EloquentBuilder $builder)
    {
        $builder->leftJoin(
            DB::raw('(`phone_time_use`, `campaigns`)'),
            function (JoinClause $join) {
                $this->addJoinConditions($join);
            }
        );
    }

    /* TODO: check if we still need it */
    protected function addJoinConditions(JoinClause $join)
    {
        $join->on('campaigns.account_id', '=', $this->table . '.account_id')
            ->on('campaigns.campaign_id', '=', $this->table . '.campaign_id')
            ->on(
                function (Builder $builder) {
                    $builder->where(
                        function (Builder $builder) {
                            $builder->whereRaw('campaigns.camp_custom1 = "creative"')
                                ->whereRaw("phone_time_use.custom1 = {$this->table}.adID");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw('campaigns.camp_custom2 = "creative"')
                                ->whereRaw("phone_time_use.custom2 = {$this->table}.adID");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw('campaigns.camp_custom3 = "creative"')
                                ->whereRaw("phone_time_use.custom3 = {$this->table}.adID");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw('campaigns.camp_custom4 = "creative"')
                                ->whereRaw("phone_time_use.custom4 = {$this->table}.adID");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw('campaigns.camp_custom5 = "creative"')
                                ->whereRaw("phone_time_use.custom5 = {$this->table}.adID");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw('campaigns.camp_custom6 = "creative"')
                                ->whereRaw("phone_time_use.custom6 = {$this->table}.adID");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw('campaigns.camp_custom7 = "creative"')
                                ->whereRaw("phone_time_use.custom7 = {$this->table}.adID");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw('campaigns.camp_custom8 = "creative"')
                                ->whereRaw("phone_time_use.custom8 = {$this->table}.adID");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw('campaigns.camp_custom9 = "creative"')
                                ->whereRaw("phone_time_use.custom9 = {$this->table}.adID");
                        }
                    )->orWhere(
                        function (Builder $builder) {
                            $builder->whereRaw('campaigns.camp_custom10 = "creative"')
                                ->whereRaw("phone_time_use.custom10 = {$this->table}.adID");
                        }
                    );
                }
            )
            ->on('phone_time_use.account_id', '=', $this->table . '.account_id')
            ->on('phone_time_use.campaign_id', '=', $this->table . '.campaign_id')
            ->on('phone_time_use.utm_campaign', '=', $this->table . '.campaignID')
            ->on(
                DB::raw("STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d')"),
                '=',
                $this->table . '.day'
            )
            ->where('phone_time_use.source', '=', 'ydn')
            ->where('phone_time_use.traffic_type', '=', 'AD');
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
        $this->conversionPoints = $this->getAllDistinctConversionNames($clientId, $accountId);

        $campaignIDs = array_unique($this->conversionPoints->pluck('campaignID')->toArray());
        $campaigns = new Campaign;
        $this->adGainerCampaigns = $campaigns->getAdGainerCampaignsWithPhoneNumber(
            $clientId,
            'ydn',
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

    public function getAllDistinctConversionNames($account_id, $accountId)
    {
        return $this->select(['campaignID', 'conversionName'])
            ->distinct()
            ->where('account_id', '=', $account_id)
            ->where('accountId', '=', $accountId)
            ->get();
    }
}
