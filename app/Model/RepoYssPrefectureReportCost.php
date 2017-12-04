<?php

namespace App\Model;

use App\AbstractReportModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\Event;

use PDO;

class RepoYssPrefectureReportCost extends AbstractReportModel
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
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        if (request()->is('account_report/*') || request()->is('account_report')) {
            DB::connection()->enableQueryLog();
            $fieldNames = $this->unsetColumns($fieldNames, ['accountid']);
            $yssAggregations = $this->getAggregated($fieldNames);
            $yssPrefectureData = self::select($yssAggregations)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )->where(
                    function (Builder $query) use (
                        $adgainerId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    ) {
                        $this->addQueryConditions(
                            $query,
                            $adgainerId,
                            $accountId,
                            $campaignId,
                            $adGroupId,
                            $adReportId,
                            $keywordId
                        );
                    }
                )
                ->groupBy($groupedByField);
            $adwAggregations = $this->getAggregatedForPrefectureGoogle($fieldNames);
            $data = RepoAdwGeoReportCost::select($adwAggregations)
                ->join(
                    self::ADW_JOIN_TABLE_NAME,
                    'repo_adw_geo_report_cost.region',
                    '=',
                    self::ADW_JOIN_TABLE_NAME . '.CriteriaID'
                )
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )->where(
                    function (Builder $query) use ($adgainerId) {
                        $query->where('repo_adw_geo_report_cost.account_id', '=', $adgainerId);
                    }
                )
                ->groupBy('criteria.Name')
                ->orderBy($columnSort, $sort);
            return $data->orderBy($columnSort, $sort)->get();
        }

        return $this->getPrefectureReportsWhenCurrentPageIsNotAccountReport(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $pagination,
            $columnSort,
            $sort,
            $groupedByField,
            $accountId,
            $adgainerId,
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
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        if (request()->is('account_report/*') || request()->is('account_report')) {
            $fieldNames = $this->unsetColumns($fieldNames, ['accountid', 'prefecture']);
            Event::listen(StatementPrepared::class, function ($event) {
                $event->statement->setFetchMode(PDO::FETCH_OBJ);
            });
            $yssAggregations = $this->getAggregated($fieldNames);
            $yssPrefectureData = self::select($yssAggregations)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )->where(
                    function (Builder $query) use (
                        $adgainerId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    ) {
                        $this->addQueryConditions(
                            $query,
                            $adgainerId,
                            $accountId,
                            $campaignId,
                            $adGroupId,
                            $adReportId,
                            $keywordId
                        );
                    }
                );
            $adwAggregations = $this->getAggregatedForPrefectureGoogle($fieldNames);
            return RepoAdwGeoReportCost::select($adwAggregations)
                ->join(
                    self::ADW_JOIN_TABLE_NAME,
                    'repo_adw_geo_report_cost.region',
                    '=',
                    self::ADW_JOIN_TABLE_NAME . '.CriteriaID'
                )
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )->where(
                    function (Builder $query) use ($adgainerId) {
                        $query->where('repo_adw_geo_report_cost.account_id', '=', $adgainerId);
                    }
                )
                ->union($yssPrefectureData)
                ->first();
        }

        return $this->calculateTotalWhenCurrentPageIsNotAccountReport(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $groupedByField,
            $accountId,
            $adgainerId,
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
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        if (request()->is('account_report/*') || request()->is('account_report')) {
            $fieldNames = $this->unsetColumns($fieldNames, ['accountid', 'prefecture']);
            Event::listen(StatementPrepared::class, function ($event) {
                $event->statement->setFetchMode(PDO::FETCH_OBJ);
            });
            $yssAggregations = $this->getAggregated($fieldNames);
            $yssPrefectureData = self::select($yssAggregations)
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )->where(
                    function (Builder $query) use (
                        $adgainerId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    ) {
                        $this->addQueryConditions(
                            $query,
                            $adgainerId,
                            $accountId,
                            $campaignId,
                            $adGroupId,
                            $adReportId,
                            $keywordId
                        );
                    }
                );
            $adwAggregations = $this->getAggregatedForPrefectureGoogle($fieldNames);
            return RepoAdwGeoReportCost::select($adwAggregations)
                ->join(
                    self::ADW_JOIN_TABLE_NAME,
                    'repo_adw_geo_report_cost.region',
                    '=',
                    self::ADW_JOIN_TABLE_NAME . '.CriteriaID'
                )
                ->where(
                    function (Builder $query) use ($startDay, $endDay) {
                        $this->addTimeRangeCondition($startDay, $endDay, $query);
                    }
                )->where(
                    function (Builder $query) use ($adgainerId) {
                        $query->where('repo_adw_geo_report_cost.account_id', '=', $adgainerId);
                    }
                )
                ->union($yssPrefectureData)
                ->first();
        }

        return $this->calculateSummaryDataWhenCurrentPageIsNotAccountReport(
            $engine,
            $fieldNames,
            $accountStatus,
            $startDay,
            $endDay,
            $accountId,
            $adgainerId,
            $campaignId,
            $adGroupId,
            $adReportId,
            $keywordId
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

    private function calculateTotalWhenCurrentPageIsNotAccountReport(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $groupedByField,
        $accountId = null,
        $adgainerId = null,
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
                    $adgainerId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId
                ) {
                    $this->addQueryConditions(
                        $query,
                        $adgainerId,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
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
            $data = [];
        }

        return $data;
    }

    private function calculateSummaryDataWhenCurrentPageIsNotAccountReport(
        $engine,
        $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $accountId = null,
        $adgainerId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
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
                    $adgainerId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId
                ) {
                    $this->addQueryConditions(
                        $query,
                        $adgainerId,
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
        $engine,
        array $fieldNames,
        $accountStatus,
        $startDay,
        $endDay,
        $pagination,
        $columnSort,
        $sort,
        $groupedByField,
        $accountId = null,
        $adgainerId = null,
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
                    $adgainerId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId
                ) {
                    $this->addQueryConditions(
                        $query,
                        $adgainerId,
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
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $paginatedData = $paginatedData->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO)
                ->paginate($pagination);
        } elseif ($accountStatus == self::SHOW_ZERO_STATUS) {
            $paginatedData = $paginatedData->paginate($pagination);
        }
        return $paginatedData;
    }
}
