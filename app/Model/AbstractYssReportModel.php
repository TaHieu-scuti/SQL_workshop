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
                    $expressions[] = DB::raw("COUNT(`phone_time_use`.`id`) as call_cv");
                    break;
                case 'call_cvr':
                    $expressions[] = DB::raw("(COUNT(`phone_time_use`.`id`) /
                    SUM(`{$this->table}`.`clicks`)) * 100 as call_cvr");
                    break;
                case 'call_cpa':
                    $expressions[] = DB::raw("
                    IFNULL(SUM(`{$this->table}`.`cost`) /
                    COUNT(`phone_time_use`.`id`), 0) as call_cpa");
                    break;
                case 'web_cv':
                    $expressions[] = DB::raw("SUM(`{$this->table}`.`conversions`) as web_cv");
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw("(SUM(`{$this->table}`.`conversions`) /
                    SUM(`{$this->table}`.`clicks`)) * 100 as web_cvr");
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw("
                    IFNULL(SUM(`{$this->table}`.`cost`) /
                    SUM(`{$this->table}`.`conversions`), 0) as web_cpa");
                    break;
                case 'total_cv':
                    $expressions[] = DB::raw("SUM(`{$this->table}`.`conversions`) +
                    COUNT(`phone_time_use`.`id`) as total_cv");
                    break;
                case 'total_cvr':
                    $expressions[] = DB::raw("
                    ((COUNT(`phone_time_use`.`id`) / SUM(`{$this->table}`.`clicks`)) * 100
                    +
                    (SUM(`{$this->table}`.`conversions`) / SUM(`{$this->table}`.`clicks`)) * 100)
                    / 2 as total_cvr");
                    break;
                case 'total_cpa':
                    $expressions[] = DB::raw("
                    IFNULL(SUM(`{$this->table}`.`cost`) / COUNT(`phone_time_use`.`id`), 0)
                    +
                    IFNULL(SUM(`{$this->table}`.`cost`) / SUM(`{$this->table}`.`conversions`), 0) as total_cpa");
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
                    . '`.`conversions`) / SUM(`conv'
                    . $i
                    . "`.`clicks`)) * 100, 0) AS 'YSS "
                    . $conversionName
                    . " CVR'"
                );
                $expressions[] = DB::raw(
                    'IFNULL(SUM(`conv'
                    . $i
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
