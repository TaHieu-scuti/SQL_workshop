<?php

namespace App\Model;

use Auth;
use App\Http\Controllers\AbstractReportController;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;

class RepoYdnAdgroupReport extends AbstractYdnReportModel
{
    const GROUPED_BY_FIELD_NAME = 'adgroupName';
    const PAGE_ID = 'adgroupID';
    const ALL_HIGHER_LAYERS =
    [
        [
            'columnName' => 'campaignName',
            'tableJoin' => 'repo_ydn_reports',
            'columnId' => 'campaignID',
            'aliasId' => 'campaignID',
            'aliasName' => 'campaignName'
        ]
    ];

    protected $table = 'repo_ydn_reports';
    public $timestamps = false;

    private function addJoinsForConversionPoints(
        EloquentBuilder $builder,
        $conversionPoints
    ) {
        foreach ($conversionPoints as $i => $point) {
            $joinAlias = 'conv' . $i;
            $builder->leftJoin(
                $this->table . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $point) {
                    $join->on(
                        $this->table . '.account_id',
                        '=',
                        $joinAlias . '.account_id'
                    )
                        ->on(
                            $this->table . '.accountId',
                            '=',
                            $joinAlias . '.accountId'
                        )->on(
                            $this->table . '.day',
                            '=',
                            $joinAlias . '.day'
                        )->on(
                            $this->table . '.campaignID',
                            '=',
                            $joinAlias . '.campaignID'
                        )->on(
                            $this->table . '.adgroupID',
                            '=',
                            $joinAlias . '.adgroupID'
                        )->where(
                            $joinAlias . '.campaignID',
                            '=',
                            $point->campaignID
                        )->where(
                            $joinAlias . '.adgroupID',
                            '=',
                            $point->adgroupID
                        )->where(
                            $joinAlias . '.conversionName',
                            '=',
                            $point->conversionName
                        );
                }
            );
        }
    }

    private function addJoinsForCallConversions(EloquentBuilder $builder, $adGainerCampaigns)
    {
        $joinTableName = (new RepoPhoneTimeUse)->getTable();
        foreach ($adGainerCampaigns as $i => $campaign) {
            $joinAlias = 'call' . $i;
            $builder->leftJoin(
                $joinTableName . ' AS ' . $joinAlias,
                function (JoinClause $join) use ($joinAlias, $campaign) {
                    $join->on(
                        $this->table . '.account_id',
                        '=',
                        $joinAlias . '.account_id'
                    )->on(
                        $this->table . '.campaign_id',
                        '=',
                        $joinAlias . '.campaign_id'
                    )->on(
                        $this->table . '.campaignID',
                        '=',
                        $joinAlias . '.utm_campaign'
                    )->on(
                        $this->table . '.day',
                        '=',
                        DB::raw("STR_TO_DATE(`" . $joinAlias . "`.`time_of_call`, '%Y-%m-%d')")
                    )->where(
                        $joinAlias . '.utm_campaign',
                        '=',
                        $campaign->utm_campaign
                    )->whereRaw(
                        '`' . $joinAlias . "`.`phone_number` = '" . $campaign->phone_number . "'"
                    )->where(
                        $joinAlias . '.source',
                        '=',
                        'ydn'
                    );
                }
            );
        }
    }

    protected function addJoin(EloquentBuilder $builder, $conversionPoints = null, $adGainerCampaigns = null)
    {
        $builder->leftJoin(
            DB::raw('(`phone_time_use`, `campaigns`)'),
            function (JoinClause $join) {
                $this->addJoinConditions($join);
            }
        );
        $this->addJoinsForConversionPoints($builder, $conversionPoints);
        $this->addJoinsForCallConversions($builder, $adGainerCampaigns);
    }

    public function getAllYdnAdgroup(
        $accountId = null,
        $campaignId = null
    ) {
        $engine = session(static::SESSION_KEY_ENGINE);
        return self::select('adgroupID', 'adgroupName')
            ->where(
                function ($query) use ($accountId, $campaignId, $engine) {
                    $this->addQueryConditions(
                        $query,
                        session(AbstractReportController::SESSION_KEY_CLIENT_ID),
                        $engine,
                        $accountId,
                        $campaignId
                    );
                }
            )
            ->groupBy('adgroupID', 'adgroupName')->get();
    }
}
