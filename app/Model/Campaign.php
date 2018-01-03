<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;

class Campaign extends Model
{
    /** @var bool */
    public $timestamps = false;

    public function getAdGainerCampaignsWithPhoneNumber($account_id, $source, array $campaignIDs)
    {
        $campaignsTableName = $this->getTable();
        $repoPhoneTimeUseTableName = (new RepoPhoneTimeUse)->getTable();
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
}
