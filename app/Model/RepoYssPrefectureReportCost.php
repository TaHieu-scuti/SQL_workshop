<?php

namespace App\Model;

use App\Model\AbstractYssReportModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\Event;

use PDO;

class RepoYssPrefectureReportCost extends AbstractYssReportModel
{
    const GROUPED_BY_FIELD_NAME = 'prefecture';
    const ADW_JOIN_TABLE_NAME = 'criteria';

    const ADW_FIELDS_MAP = [
        //'alias' => 'columns'
        'impressions' => 'impressions',
        'clicks' => 'clicks',
        'cost' => 'cost',
        'ctr' => 'ctr',
        'averageCpc' => 'avgCPC',
        'averagePosition' => 'avgPosition',
        'campaignName' => 'campaign',
        'adgroupName' => 'adGroup'
    ];

    const YDN_FIELDS_MAP = [
        //'alias' => 'columns'
        'impressions' => 'impressions',
        'clicks' => 'clicks',
        'cost' => 'cost',
        'ctr' => 'ctr',
        'averageCpc' => 'averageCpc',
        'averagePosition' => 'averagePosition',
        'campaignName' => 'campaignName',
        'adgroupName' => 'adgroupName'
    ];

    protected $table = 'repo_yss_prefecture_report_cost';

    /**
     * @var bool
     */
    public $timestamps = false;

    public function getDataForTable(
        $engine,
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $pagination,
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
        $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare']);
        if (request()->is('account_report/*') || request()->is('account_report')) {
            $fieldNames = $this->unsetColumns($fieldNames, ['accountid']);
            //YSS prefecture
            $yssAggregations = $this->getAggregated($fieldNames);
            $yssPrefectureData = self::select($yssAggregations)
                ->whereRaw("`phone_time_use`.`source` = 'yss'")
                ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'")
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        if ($startDay === $endDay) {
                            $query->whereDate($this->getTable().'.day', '=', $endDay);
                        } else {
                            $query->whereDate($this->getTable().'.day', '>=', $startDay)
                                ->whereDate($this->getTable().'.day', '<=', $endDay);
                        }
                    }
                )->where(
                    function (Builder $query) use ($clientId) {
                        $query->where('repo_yss_prefecture_report_cost.account_id', '=', $clientId);
                    }
                )
                ->groupBy($groupedByField)
                ->orderBy($columnSort, $sort);
            $this->addJoinConditionForYssPrefecture($yssPrefectureData);
            $this->addJoinConditonCampaignReportCostForYss($yssPrefectureData);
            //YDN prefecture
            $ydnAggregations = $this->getAggregatedForPrefectureYdn($fieldNames);
            $ydnAggregations = array_merge(
                $this->getAggregatedForPrefecture('repo_ydn_reports', $fieldNames),
                $ydnAggregations
            );
            $ydnPrefectureData = RepoYdnPrefecture::select($ydnAggregations)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_ydn_reports');
                    }
                )->where(
                    function (Builder $query) use ($clientId) {
                        $query->where('repo_ydn_reports.account_id', '=', $clientId);
                    }
                )
                ->groupBy($groupedByField)
                ->orderBy($columnSort, $sort);
            $this->addJoinConditionForYdnPrefecture($ydnPrefectureData);
            //ADW prefecture
            $adwAggregations = $this->getAggregatedForPrefectureGoogle($fieldNames);
            $adwAggregations = array_merge(
                $this->getAggregatedForPrefecture('repo_adw_geo_report_cost', $fieldNames),
                $adwAggregations,
                [
                    DB::raw("SUM('0') AS dailySpendingLimit")
                ]
            );
            $adwPrefectureData = RepoAdwGeoReportCost::select($adwAggregations)
                ->join(
                    self::ADW_JOIN_TABLE_NAME,
                    'repo_adw_geo_report_cost.region',
                    '=',
                    self::ADW_JOIN_TABLE_NAME . '.CriteriaID'
                )
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_geo_report_cost');
                    }
                )->where(
                    function (Builder $query) use ($clientId) {
                        $query->where('repo_adw_geo_report_cost.account_id', '=', $clientId);
                    }
                )
                ->groupBy('criteria.Name')
                ->orderBy($columnSort, $sort);
            $this->addJoinConditionForAdwPrefecture($adwPrefectureData);
            $data = $adwPrefectureData->union($yssPrefectureData)->union($ydnPrefectureData);
            $sql = $this->getBindingSql($data);
            Event::listen(StatementPrepared::class, function ($event) {
                $event->statement->setFetchMode(PDO::FETCH_ASSOC);
            });
            $rawExpressions = $this->getRawExpressions($fieldNames);
            return DB::table(DB::raw("({$sql}) as tbl"))
                ->select($rawExpressions)
                ->groupBy('prefecture')
                ->orderBy($columnSort, $sort)->get();
        }

        return $this->getPrefectureReportsWhenCurrentPageIsNotAccountReport(
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $pagination,
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
    }

    public function calculateData(
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
        $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare']);
        if (request()->is('account_report/*') || request()->is('account_report')) {
            $fieldNames = $this->unsetColumns($fieldNames, ['accountid', 'prefecture']);
            Event::listen(StatementPrepared::class, function ($event) {
                $event->statement->setFetchMode(PDO::FETCH_OBJ);
            });
            $yssAggregations = $this->getAggregated($fieldNames);
            $yssPrefectureData = self::select($yssAggregations)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        if ($startDay === $endDay) {
                            $query->whereDate($this->getTable().'.day', '=', $endDay);
                        } else {
                            $query->whereDate($this->getTable().'.day', '>=', $startDay)
                                ->whereDate($this->getTable().'.day', '<=', $endDay);
                        }
                    }
                )->where(
                    function (Builder $query) use ($clientId) {
                        $query->where('repo_yss_prefecture_report_cost.account_id', '=', $clientId);
                    }
                );
            $this->addJoinConditionForYssPrefecture($yssPrefectureData);
            $this->addJoinConditonCampaignReportCostForYss($yssPrefectureData);
            //YDN prefecture
            $ydnAggregations = $this->getAggregatedForPrefectureYdn($fieldNames);
            $ydnAggregations = array_merge(
                $this->getAggregatedForPrefecture('repo_ydn_reports', $fieldNames),
                $ydnAggregations
            );
            $ydnPrefectureData = RepoYdnPrefecture::select($ydnAggregations)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_ydn_reports');
                    }
                )->where(
                    function (Builder $query) use ($clientId) {
                        $query->where('repo_ydn_reports.account_id', '=', $clientId);
                    }
                );
            $this->addJoinConditionForYdnPrefecture($ydnPrefectureData);
            //ADW prefecture
            $adwAggregations = $this->getAggregatedForPrefectureGoogle($fieldNames);
            $adwAggregations = array_merge(
                $this->getAggregatedForPrefecture('repo_adw_geo_report_cost', $fieldNames),
                $adwAggregations,
                [
                    DB::raw("SUM('0') AS dailySpendingLimit")
                ]
            );
            $adwPrefectureData = RepoAdwGeoReportCost::select($adwAggregations)
                ->join(
                    self::ADW_JOIN_TABLE_NAME,
                    'repo_adw_geo_report_cost.region',
                    '=',
                    self::ADW_JOIN_TABLE_NAME . '.CriteriaID'
                )
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_geo_report_cost');
                    }
                )->where(
                    function (Builder $query) use ($clientId) {
                        $query->where('repo_adw_geo_report_cost.account_id', '=', $clientId);
                    }
                );
            $this->addJoinConditionForAdwPrefecture($adwPrefectureData);
            $data = $adwPrefectureData->union($yssPrefectureData)->union($ydnPrefectureData);
            $sql = $this->getBindingSql($data);
            $rawExpressions = $this->getRawExpressions($fieldNames);
            $array = [
                DB::raw('SUM(call_cv) as call_cv'),
                DB::raw('AVG(call_cvr) as call_cvr'),
                DB::raw('AVG(call_cpa) as call_cpa'),
                DB::raw('SUM(web_cv) as Web_CV'),
                DB::raw('AVG(web_cvr) as Web_CVR'),
                DB::raw('AVG(web_cpa) as Web_CPA')
            ];
            $rawExpressions = array_merge(
                $array,
                $rawExpressions,
                [DB::raw("SUM(dailySpendingLimit) AS dailySpendingLimit")]
            );
            return DB::table(DB::raw("({$sql}) as tbl"))
            ->select($rawExpressions)->first();
        }

        return $this->calculateTotalWhenCurrentPageIsNotAccountReport(
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
    }

    public function calculateSummaryData(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $fieldNames = $this->unsetColumns($fieldNames, ['impressionShare']);
        if (request()->is('account_report/*') || request()->is('account_report')) {
            $fieldNames = $this->unsetColumns($fieldNames, ['accountid', 'prefecture']);
            $yssAggregations = $this->getAggregated($fieldNames);
            $yssPrefectureData = self::select($yssAggregations)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);if ($startDay === $endDay) {
                            $query->whereDate($this->getTable().'.day', '=', $endDay);
                        } else {
                            $query->whereDate($this->getTable().'.day', '>=', $startDay)
                                ->whereDate($this->getTable().'.day', '<=', $endDay);
                        }
                    }
                )->where(
                    function (Builder $query) use ($clientId) {
                        $query->where('repo_yss_prefecture_report_cost.account_id', '=', $clientId);
                    }
                );
            $this->addJoinConditionForYssPrefecture($yssPrefectureData);
            //YDN prefecture
            $ydnAggregations = $this->getAggregatedForPrefectureYdn($fieldNames);
            $ydnPrefectureData = RepoYdnPrefecture::select($ydnAggregations)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_ydn_reports');
                    }
                )->where(
                    function (Builder $query) use ($clientId) {
                        $query->where('repo_ydn_reports.account_id', '=', $clientId);
                    }
                );
            $this->addJoinConditionForYdnPrefecture($ydnPrefectureData);
            //ADW prefecture
            $adwAggregations = $this->getAggregatedForPrefectureGoogle($fieldNames);
            $adwPrefectureData = RepoAdwGeoReportCost::select($adwAggregations)
                ->join(
                    self::ADW_JOIN_TABLE_NAME,
                    'repo_adw_geo_report_cost.region',
                    '=',
                    self::ADW_JOIN_TABLE_NAME . '.CriteriaID'
                )
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_geo_report_cost');
                    }
                )->where(
                    function (Builder $query) use ($clientId) {
                        $query->where('repo_adw_geo_report_cost.account_id', '=', $clientId);
                    }
                );
            $this->addJoinConditionForAdwPrefecture($adwPrefectureData);
            $data = $adwPrefectureData->union($yssPrefectureData)->union($ydnPrefectureData);
            Event::listen(StatementPrepared::class, function ($event) {
                $event->statement->setFetchMode(PDO::FETCH_ASSOC);
            });
            $sql = $this->getBindingSql($data);
            $rawExpressions = $this->getRawExpressions($fieldNames);
            return DB::table(DB::raw("({$sql}) as tbl"))
            ->select($rawExpressions)->first();
        }

        return $this->calculateSummaryDataWhenCurrentPageIsNotAccountReport(
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $agencyId,
            $accountId,
            $clientId,
            $campaignId,
            $adGroupId,
            $adReportId
        );
    }

    private function getAggregatedForPrefectureGoogle(array $fieldNames)
    {
        $adwAggregations = [];
        $joinTableName = 'criteria';
        $tableName = 'repo_adw_geo_report_cost';
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === 'prefecture') {
                $adwAggregations[] = DB::raw($joinTableName . '.Name as prefecture');
                continue;
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $adwAggregations[] = DB::raw(
                    'ROUND(AVG(' . $tableName . '.' . self::ADW_FIELDS_MAP[$fieldName] . '), 2) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                                    ->getType()
                                    ->getName()
                    === self::FIELD_TYPE
                ) {
                    $adwAggregations[] = DB::raw(
                        'ROUND(SUM(' . $tableName . '.' . self::ADW_FIELDS_MAP[$fieldName] . '), 2) AS ' . $fieldName
                    );
                } else {
                    $adwAggregations[] = DB::raw(
                        'SUM( ' . $tableName . '.' . self::ADW_FIELDS_MAP[$fieldName] . ' ) AS ' . $fieldName
                    );
                }
            }
        }
        return $adwAggregations;
    }

    private function getAggregatedForPrefectureYdn(array $fieldNames)
    {
        $ydnAggregations = [];
        $tableName = 'repo_ydn_reports';
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === 'prefecture') {
                $ydnAggregations[] = DB::raw($fieldName);
                continue;
            }
            if ($fieldName === self::DAILY_SPENDING_LIMIT) {
                $ydnAggregations[] = DB::raw(
                    'SUM('.$fieldName.') AS '.$fieldName
                );
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $ydnAggregations[] = DB::raw(
                    'ROUND(AVG(' . $tableName . '.' . self::YDN_FIELDS_MAP[$fieldName] . '), 2) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                                    ->getType()
                                    ->getName()
                    === self::FIELD_TYPE
                ) {
                    $ydnAggregations[] = DB::raw(
                        'ROUND(SUM(' . $tableName . '.' . self::YDN_FIELDS_MAP[$fieldName] . '), 2) AS ' . $fieldName
                    );
                } else {
                    $ydnAggregations[] = DB::raw(
                        'SUM( ' . $tableName . '.' . self::YDN_FIELDS_MAP[$fieldName] . ' ) AS ' . $fieldName
                    );
                }
            }
        }
        return $ydnAggregations;
    }

    private function calculateTotalWhenCurrentPageIsNotAccountReport(
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
        $fieldNames = $this->unsetColumns($fieldNames, [$groupedByField]);

        $aggregations = $this->getAggregated($fieldNames);
        $data = self::select($aggregations)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
                function (Builder $query) use (
                    $clientId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId
                ) {
                    $this->addQueryConditions(
                        $query,
                        $clientId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    );
                }
            );
        $this->addJoinConditionForYssPrefecture($data);
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                ->first();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->first();
        }
        if ($data === null) {
            $data = [];
        }

        return $data;
    }

    private function calculateSummaryDataWhenCurrentPageIsNotAccountReport(
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $agencyId = null,
        $accountId = null,
        $clientId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null
    ) {
        $arrayCalculate = $this->getAggregated($fieldNames);
        $data = self::select($arrayCalculate)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
                function (Builder $query) use (
                    $clientId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId
                ) {
                    $this->addQueryConditions(
                        $query,
                        $clientId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId
                    );
                }
            );
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                ->first();
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $data = $data->first();
        }
        if ($data === null) {
            $data = [
                'clicks' => 0,
                'impressions' => 0,
                'cost' => 0,
                'averageCpc' => 0,
                'averagePosition' => 0
            ];
        } else {
            $data = $data->toArray();
        }
        return $data;
    }

    private function getPrefectureReportsWhenCurrentPageIsNotAccountReport(
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $pagination,
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
        $aggregations = $this->getAggregated($fieldNames);
        $paginatedData = $this->select(array_merge(static::FIELDS, $aggregations))
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )->where(
                function (Builder $query) use (
                    $clientId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId
                ) {
                    $this->addQueryConditions(
                        $query,
                        $clientId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    );
                }
            )
            ->groupBy($groupedByField)
            ->orderBy($columnSort, $sort);
        $this->addJoinConditionForYssPrefecture($paginatedData);
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $paginatedData = $paginatedData->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                ->paginate($pagination);
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $paginatedData = $paginatedData->paginate($pagination);
        }
        return $paginatedData;
    }

    private function addJoinConditionForYdnPrefecture(Builder $builder)
    {
        $builder->leftJoin(
            DB::raw("(`phone_time_use`,`campaigns`)"),
            function (JoinClause $join) {
                $join->on('campaigns.account_id', '=', 'repo_ydn_reports.account_id')
                ->on('campaigns.campaign_id', '=', 'repo_ydn_reports.campaign_id')
                ->on(
                    function (JoinClause $builder) {
                        $builder->where(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom1` = 'creative'")
                                ->whereRaw("`phone_time_use`.`custom1` = `repo_ydn_reports`.`adID`");
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom2` = 'creative'")
                                ->whereRaw("`phone_time_use`.`custom2` = `repo_ydn_reports`.`adID`");
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom3` = 'creative'")
                                ->whereRaw("`phone_time_use`.`custom3` = `repo_ydn_reports`.`adID`");
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom4` = 'creative'")
                                ->whereRaw("`phone_time_use`.`custom4` = `repo_ydn_reports`.`adID`");
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom5` = 'creative'")
                                ->whereRaw("`phone_time_use`.`custom5` = `repo_ydn_reports`.`adID`");
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom6` = 'creative'")
                                ->whereRaw("`phone_time_use`.`custom6` = `repo_ydn_reports`.`adID`");
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom7` = 'creative'")
                                ->whereRaw("`phone_time_use`.`custom7` = `repo_ydn_reports`.`adID`");
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom8` = 'creative'")
                                ->whereRaw("`phone_time_use`.`custom8` = `repo_ydn_reports`.`adID`");
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom9` = 'creative'")
                                ->whereRaw("`phone_time_use`.`custom9` = `repo_ydn_reports`.`adID`");
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom10` = 'creative'")
                                ->whereRaw("`phone_time_use`.`custom10` = `repo_ydn_reports`.`adID`");
                            }
                        );
                    }
                )
                ->on('phone_time_use.account_id', '=', 'repo_ydn_reports.account_id')
                ->on('phone_time_use.campaign_id', '=', 'repo_ydn_reports.campaign_id')
                ->on('phone_time_use.utm_campaign', '=', 'repo_ydn_reports.campaignID')
                ->where('phone_time_use.source', '=', 'ydn')
                ->where('phone_time_use.traffic_type', '=', 'AD');
            }
        );
    }

    private function addJoinConditionForYssPrefecture(Builder $builder)
    {
        $builder->leftJoin(
            DB::raw("(`phone_time_use`, `campaigns`)"),
            function (JoinClause $join) {
                $join->on('campaigns.account_id', '=', 'repo_yss_prefecture_report_cost.account_id')
                ->on('campaigns.campaign_id', '=', 'repo_yss_prefecture_report_cost.campaign_id')
                ->on(
                    function (JoinClause $builder) {
                        $builder->where(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom1` = 'adgroupid'")
                                ->whereRaw(
                                    "`phone_time_use`.`custom1` = `repo_yss_prefecture_report_cost`.`adgroupID`"
                                );
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom2` = 'adgroupid'")
                                ->whereRaw(
                                    "`phone_time_use`.`custom2` = `repo_yss_prefecture_report_cost`.`adgroupID`"
                                );
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom3` = 'adgroupid'")
                                ->whereRaw(
                                    "`phone_time_use`.`custom3` = `repo_yss_prefecture_report_cost`.`adgroupID`"
                                );
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom4` = 'adgroupid'")
                                ->whereRaw(
                                    "`phone_time_use`.`custom4` = `repo_yss_prefecture_report_cost`.`adgroupID`"
                                );
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom5` = 'adgroupid'")
                                ->whereRaw(
                                    "`phone_time_use`.`custom5` = `repo_yss_prefecture_report_cost`.`adgroupID`"
                                );
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom6` = 'adgroupid'")
                                ->whereRaw(
                                    "`phone_time_use`.`custom6` = `repo_yss_prefecture_report_cost`.`adgroupID`"
                                );
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom7` = 'adgroupid'")
                                ->whereRaw(
                                    "`phone_time_use`.`custom7` = `repo_yss_prefecture_report_cost`.`adgroupID`"
                                );
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom8` = 'adgroupid'")
                                ->whereRaw(
                                    "`phone_time_use`.`custom8` = `repo_yss_prefecture_report_cost`.`adgroupID`"
                                );
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom9` = 'adgroupid'")
                                ->whereRaw(
                                    "`phone_time_use`.`custom9` = `repo_yss_prefecture_report_cost`.`adgroupID`"
                                );
                            }
                        )->orWhere(
                            function (JoinClause $builder) {
                                $builder->whereRaw("`campaigns`.`camp_custom10` = 'adgroupid'")
                                ->whereRaw(
                                    "`phone_time_use`.`custom10` = `repo_yss_prefecture_report_cost`.`adgroupID`"
                                );
                            }
                        );
                    }
                )->on('phone_time_use.account_id', '=', 'repo_yss_prefecture_report_cost.account_id')
                ->on('phone_time_use.campaign_id', '=', 'repo_yss_prefecture_report_cost.campaign_id')
                ->on('phone_time_use.utm_campaign', '=', 'repo_yss_prefecture_report_cost.campaignID')
                ->whereRaw(
                    "STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_yss_prefecture_report_cost`.`day`"
                )->whereRaw("`phone_time_use`.`source` = 'yss'")
                ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'")
                ->whereRaw(
                    "`phone_time_use`.`visitor_city_state` LIKE
                    CONCAT('%', `repo_yss_prefecture_report_cost`.`prefecture`, ' (Japan)')"
                );
            }
        );
    }

    private function addJoinConditionForAdwPrefecture(Builder $builder)
    {
        $builder->leftJoin(
            DB::raw("`phone_time_use`"),
            function (JoinClause $join) {
                $join->on('phone_time_use.account_id', '=', 'repo_adw_geo_report_cost.account_id')
                ->on('phone_time_use.campaign_id', '=', 'repo_adw_geo_report_cost.campaign_id')
                ->on('phone_time_use.utm_campaign', '=', 'repo_adw_geo_report_cost.campaignID')
                ->whereRaw(
                    "STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_adw_geo_report_cost`.`day`"
                )->whereRaw("`phone_time_use`.`source` = 'adw'")
                ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'")
                ->whereRaw("`phone_time_use`.`visitor_city_state` LIKE CONCAT('%', `criteria`.`Name`, ' (Japan)')");
            }
        );
    }

    private function getAggregatedForPrefecture($tableName, $fieldNames)
    {
        $expressions = [];
        foreach ($fieldNames as $fieldName) {
            switch ($fieldName) {
                case 'call_cv':
                    $expressions[] = DB::raw('COUNT(`phone_time_use`.`id`) AS call_cv');
                    break;
                case 'call_cvr':
                    $expressions[] = DB::raw(
                        "((SUM(`{$tableName}`.`conversions`) + COUNT(`phone_time_use`.`id`)) "
                        . "/ SUM(`{$tableName}`.`clicks`)) * 100 AS call_cvr"
                    );
                    break;
                case 'call_cpa':
                    $expressions[] = DB::raw(
                        "SUM(`{$tableName}`.`cost`) / (SUM(`{$tableName}`.`conversions`) "
                        . "+ COUNT(`phone_time_use`.`id`)) AS call_cpa"
                    );
                    break;
                case 'web_cv':
                    $expressions[] = DB::raw(
                        "SUM(`{$tableName}`.conversions) AS web_cv"
                    );
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw(
                        "(SUM(`{$tableName}`.conversions) / SUM(`{$tableName}`.clicks) * 100) AS web_cvr"
                    );
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw(
                        "(SUM(`{$tableName}`.cost) / SUM(`{$tableName}`.conversions)) AS web_cpa"
                    );
                    break;
                case 'total_cv':
                    $expressions[] = DB::raw(
                        "(SUM(`{$tableName}`.`conversions`) + COUNT(`phone_time_use`.`id`)) as total_cv"
                    );
                    break;
                case 'total_cvr':
                    $expressions[] = DB::raw(
                        "((COUNT(`phone_time_use`.`id`) / SUM(`{$tableName}`.`clicks`)) * 100
                    +
                    (SUM(`{$tableName}`.`conversions`) / SUM(`{$tableName}`.`clicks`)) * 100)
                    / 2 as total_cvr"
                    );
                    break;
                case 'total_cpa':
                    $expressions[] = DB::raw(
                        "SUM(`{$tableName}`.`cost`) / COUNT(`phone_time_use`.`id`) +
                        SUM(`{$tableName}`.`cost`) / SUM(`{$tableName}`.`conversions`) as total_cpa"
                    );
                    break;
            }
        }
        return $expressions;
    }

    private function addJoinConditonCampaignReportCostForYss(Builder $builder)
    {
        $builder->leftJoin(
            DB::raw("`repo_yss_campaign_report_cost`"),
            function (JoinClause $join) {
                $join->on(
                    function (JoinClause $builder) {
                        $builder->whereRaw(
                            "`repo_yss_campaign_report_cost`.`account_id` =
                            `repo_yss_prefecture_report_cost`.`account_id`"
                        )
                        ->whereRaw(
                            "`repo_yss_campaign_report_cost`.`campaign_id` =
                            `repo_yss_prefecture_report_cost`.`campaign_id`"
                        )
                        ->whereRaw("`repo_yss_campaign_report_cost`.`day` = `repo_yss_prefecture_report_cost`.`day`");
                    }
                );
            }
        );
    }

    protected function getRawExpressions($fieldNames)
    {
        $expressions = parent::getRawExpressions($fieldNames);
        foreach ($fieldNames as $fieldName) {
            switch ($fieldName) {
                case 'call_cv':
                    $expressions[] = DB::raw('SUM(`call_cv`) AS call_cv');
                    break;
                case 'call_cvr':
                    $expressions[] = DB::raw(
                        "((SUM(`web_cv`) + COUNT(`call_cv`)) "
                        . "/ SUM(`clicks`)) * 100 AS call_cvr"
                    );
                    break;
                case 'call_cpa':
                    $expressions[] = DB::raw(
                        "SUM(`cost`) / (SUM(`web_cv`) "
                        . "+ SUM(`call_cv`)) AS call_cpa"
                    );
                    break;
                case 'web_cv':
                    $expressions[] = DB::raw(
                        "SUM(`web_cv`) AS web_cv"
                    );
                    break;
                case 'web_cvr':
                    $expressions[] = DB::raw(
                        "(SUM(`web_cv`) / SUM(`clicks`) * 100) AS web_cvr"
                    );
                    break;
                case 'web_cpa':
                    $expressions[] = DB::raw(
                        "(SUM(`cost`) / SUM(`web_cv`)) AS web_cpa"
                    );
                    break;
                case 'dailySpendingLimit':
                    $expressions[] = DB::raw("SUM(`dailySpendingLimit`) AS dailySpendingLimit");
                    break;
                case 'total_cv':
                    $expressions[] = DB::raw("(SUM(`web_cv`) + SUM(`call_cv`)) as total_cv");
                    break;
                case 'total_cvr':
                    $expressions[] = DB::raw("((SUM(`web_cvr`) + SUM(`call_cvr`))/2) as total_cvr");
                    break;
                case 'total_cpa':
                    $expressions[] = DB::raw("(SUM(`web_cpa`) + SUM(`call_cpa`)) as total_cpa");
                    break;
            }
        }
        return $expressions;
    }
}
