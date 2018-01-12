<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Eloquent\Builder;

class Campaign extends Model
{
    /** @var bool */
    public $timestamps = false;

    public function getAdGainerCampaignsWithPhoneNumber(
        $account_id,
        $source,
        array $campaignIDs,
        array $adIDs = null,
        $flag = null
    ) {
        $campaignsTableName = $this->getTable();
        $repoPhoneTimeUseTableName = (new RepoPhoneTimeUse)->getTable();
        if ($flag === 'adID' || $flag === 'adgroupID') {
            $repoPhoneTimeUseTableName = (new PhoneTimeUse)->getTable();
        }
        return $this->distinct()
            ->select([
                $campaignsTableName . '.campaign_id',
                $campaignsTableName . '.campaign_name',
                $repoPhoneTimeUseTableName . '.utm_campaign',
                $repoPhoneTimeUseTableName . '.phone_number'
            ])
            ->join(
                $repoPhoneTimeUseTableName,
                function (JoinClause $join) use ($campaignsTableName, $repoPhoneTimeUseTableName) {
                    $join->on(
                        $campaignsTableName . '.account_id',
                        '=',
                        $repoPhoneTimeUseTableName . '.account_id'
                    )->on(
                        $campaignsTableName . '.campaign_id',
                        '=',
                        $repoPhoneTimeUseTableName . '.campaign_id'
                    );
                }
            )
            ->where(
                function (Builder $query) use ($flag, $campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                    if ($flag === 'adID') {
                        $this->addConditionForCampaignsWithPhoneNumber(
                            $query,
                            $campaignsTableName,
                            $repoPhoneTimeUseTableName,
                            $adIDs
                        );
                    }
                }
            )
            ->where(
                $campaignsTableName . '.account_id',
                '=',
                $account_id
            )
            ->where(
                $repoPhoneTimeUseTableName . '.source',
                '=',
                $source
            )
            ->whereIn($repoPhoneTimeUseTableName . '.utm_campaign', $campaignIDs)
            ->get();
    }

    private function addConditionForCampaignsWithPhoneNumber(
        Builder $query,
        $campaignsTableName,
        $repoPhoneTimeUseTableName,
        $adIDs
    ) {
        $query->where(
            function (Builder $query) use ($campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                $query->whereRaw($campaignsTableName.'.camp_custom1 = "creative"')
                    ->whereIn($repoPhoneTimeUseTableName.".custom1", $adIDs);
            }
        )->orWhere(
            function (Builder $query) use ($campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                $query->whereRaw($campaignsTableName.'.camp_custom2 = "creative"')
                    ->whereIn($repoPhoneTimeUseTableName.".custom2", $adIDs);
            }
        )->orWhere(
            function (Builder $query) use ($campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                $query->whereRaw($campaignsTableName.'.camp_custom3 = "creative"')
                    ->whereIn($repoPhoneTimeUseTableName.".custom3", $adIDs);
            }
        )->orWhere(
            function (Builder $query) use ($campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                $query->whereRaw($campaignsTableName.'.camp_custom4 = "creative"')
                    ->whereIn($repoPhoneTimeUseTableName.".custom4", $adIDs);
            }
        )->orWhere(
            function (Builder $query) use ($campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                $query->whereRaw($campaignsTableName.'.camp_custom5 = "creative"')
                    ->whereIn($repoPhoneTimeUseTableName.".custom5", $adIDs);
            }
        )->orWhere(
            function (Builder $query) use ($campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                $query->whereRaw($campaignsTableName.'.camp_custom6 = "creative"')
                    ->whereIn($repoPhoneTimeUseTableName.".custom6", $adIDs);
            }
        )->orWhere(
            function (Builder $query) use ($campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                $query->whereRaw($campaignsTableName.'.camp_custom7 = "creative"')
                    ->whereIn($repoPhoneTimeUseTableName.".custom7", $adIDs);
            }
        )->orWhere(
            function (Builder $query) use ($campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                $query->whereRaw($campaignsTableName.'.camp_custom8 = "creative"')
                    ->whereIn($repoPhoneTimeUseTableName.".custom8", $adIDs);
            }
        )->orWhere(
            function (Builder $query) use ($campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                $query->whereRaw($campaignsTableName.'.camp_custom9 = "creative"')
                    ->whereIn($repoPhoneTimeUseTableName.".custom9", $adIDs);
            }
        )->orWhere(
            function (Builder $query) use ($campaignsTableName, $repoPhoneTimeUseTableName, $adIDs) {
                $query->whereRaw($campaignsTableName.'.camp_custom10 = "creative"')
                    ->whereIn($repoPhoneTimeUseTableName.".custom10", $adIDs);
            }
        );
    }
}
