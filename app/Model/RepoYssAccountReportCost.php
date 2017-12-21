<?php

namespace App\Model;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
//use Illuminate\Database\Eloquent\JoinClause;


use App\AbstractReportModel;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\Event;
use \App\Model\RepoAdwAccountReportCost;

use DateTime;
use Exception;
use Auth;
use PDO;

class RepoYssAccountReportCost extends AbstractReportModel
{
    protected $table = 'repo_yss_account_report_cost';
    const GROUPED_BY_FIELD_NAME = 'accountName';
    const GROUPED_BY_FIELD_NAME_ADW = 'account';
    const PAGE_ID = 'accountid';
    const ADW_CUSTOMER_ID = 'customerID';

    /**
     * @var bool
     */
    public $timestamps = false;

    // constant
    const FOREIGN_KEY_YSS_ACCOUNTS = 'account_id';
    const HIDE_ZERO_STATUS = 'hideZero';
    const SHOW_ZERO_STATUS = 'showZero';

    const ADW_FIELDS = [
        self::CLICKS => self::CLICKS,
        self::COST => self::COST,
        self::IMPRESSIONS => self::IMPRESSIONS,
        self::CTR => self::CTR,
        self::AVERAGE_POSITION => self::ADW_AVERAGE_POSITION,
        self::AVERAGE_CPC => self::ADW_AVERAGE_CPC
    ];

    const ARR_FIELDS = [
        self::CLICKS => self::CLICKS,
        self::COST => self::COST,
        self::IMPRESSIONS => self::IMPRESSIONS,
        self::CTR => self::CTR,
        self::AVERAGE_POSITION => self::AVERAGE_POSITION,
        self::AVERAGE_CPC => self::AVERAGE_CPC
    ];

    private function getAggregatedGraphOfGoogle($column)
    {
        $arrSelect = [];
        $tableName = (new RepoAdwAccountReportCost)->getTable();
        $arrSelect[] = DB::raw('DATE(day) as day');
        if (in_array($column, static::AVERAGE_FIELDS)) {
            if ($column === self::AVERAGE_CPC) {
                $arrSelect[] = DB::raw(
                    'ROUND(AVG( avgCPC ), 2) AS data'
                );
            }
            if ($column === self::AVERAGE_POSITION) {
                $arrSelect[] = DB::raw(
                    'ROUND(AVG( avgPosition ), 2) AS data'
                );
            }
        } elseif (in_array($column, static::SUM_FIELDS)) {
            if (DB::connection()->getDoctrineColumn($tableName, $column)
                    ->getType()
                    ->getName()
                === self::FIELD_TYPE) {
                $arrSelect[] = DB::raw(
                    'ROUND(SUM(' . $column . '), 2) AS data'
                );
            } else {
                $arrSelect[] = DB::raw(
                    'SUM( ' . $column . ' ) AS data'
                );
            }
        }
        return $arrSelect;
    }

    public function getAggregatedGraph($column)
    {
        $arrSelect = [];
        $tableName = $this->getTable();
        $arrSelect[] = DB::raw('DATE(day) as day');
        if (in_array($column, static::AVERAGE_FIELDS)) {
            $arrSelect[] = DB::raw(
                'ROUND(AVG('. $column .'), 2) AS data'
            );
        } elseif (in_array($column, static::SUM_FIELDS)) {
            if (DB::connection()->getDoctrineColumn($tableName, $column)
                    ->getType()
                    ->getName()
                === self::FIELD_TYPE) {
                $arrSelect[] = DB::raw(
                    'ROUND(SUM(' . $column . '), 2) AS data'
                );
            } else {
                $arrSelect[] = DB::raw(
                    'SUM( ' . $column . ' ) AS data'
                );
            }
        }
        return $arrSelect;
    }

    private function getAggregatedOfGoogle(array $fieldNames)
    {
        $tableName = 'repo_adw_account_report_cost';
        array_unshift($fieldNames, self::GROUPED_BY_FIELD_NAME_ADW);
        if (array_search('accountName', $fieldNames) === false) {
            $key = array_search(static::GROUPED_BY_FIELD_NAME_ADW, $fieldNames);
            if ($key !== false) {
                unset($fieldNames[$key]);
            }
            $keyPageId = array_search(static::PAGE_ID, $fieldNames);
            if ($keyPageId !== false) {
                unset($fieldNames[$key]);
            }
        }
        $tableName = (new RepoAdwAccountReportCost)->getTable();
        if (isset($fieldNames[0]) && $fieldNames[0] === self::PREFECTURE) {
            $tableName = 'repo_yss_prefecture_report_cost';
        }
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === self::DEVICE
                || $fieldName === self::HOUR_OF_DAY
                || $fieldName === self::DAY_OF_WEEK
                || $fieldName === self::PREFECTURE
            ) {
                $key = array_search(static::PAGE_ID, $fieldNames);
                if ($key !== false) {
                    unset($fieldNames[$key]);
                }
            }
        }

        $arrayCalculate = [];
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === self::PAGE_ID) {
                $arrayCalculate[] = self::ADW_CUSTOMER_ID.' as accountid';
                continue;
            }
            if ($fieldName === self::DEVICE
                || $fieldName === self::HOUR_OF_DAY
                || $fieldName === self::DAY_OF_WEEK
                || $fieldName === self::PREFECTURE
                || $fieldName === self::PAGE_ID
            ) {
                $arrayCalculate[] = $fieldName;
                continue;
            }
            if ($fieldName === static::GROUPED_BY_FIELD_NAME_ADW) {
                $arrayCalculate[] = $fieldName .' AS accountName';
                continue;
            }
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'ROUND(AVG('. $tableName. '.' . self::ADW_FIELDS[$fieldName] . '), 2) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                        ->getType()
                        ->getName()
                    === self::FIELD_TYPE) {
                    $arrayCalculate[] = DB::raw(
                        'ROUND(SUM(' . $tableName. '.' . self::ADW_FIELDS[$fieldName] . '), 2) AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw(
                        'SUM( ' . $tableName. '.' . self::ADW_FIELDS[$fieldName] . ' ) AS ' . $fieldName
                    );
                }
            }
        }
        return $arrayCalculate;
    }

    /**
     * @param string $column
     * @param string $accountStatus
     * @param string $startDay
     * @param string $endDay
     * @return \Illuminate\Support\Collection
     */
    public function getDataForGraph(
        $engine,
        $column,
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
        try {
            new DateTime($startDay);
            new DateTime($endDay);
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }
        $modelYdnReport = new RepoYdnReport();
        $arrSelect = $this->getAggregatedGraph($column);
        $arrSelectGoogle = $this->getAggregatedGraphOfGoogle($column);
        $ydnAccountDataForGraph = $modelYdnReport->ydnAccountDataForGraph($column, $startDay, $endDay, $clientId);
        $dataForGoogle = RepoAdwAccountReportCost::select($arrSelectGoogle)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
                function ($query) use ($clientId) {
                    $query->where('account_id', '=', $clientId);
                }
            )
            ->groupBy('day');

        $data = self::select($arrSelect)
            ->join(
                'repo_yss_accounts',
                'repo_yss_account_report_cost.account_id',
                '=',
                'repo_yss_accounts.account_id'
            )
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->where(
                function ($query) use ($clientId) {
                    $query->where('repo_yss_account_report_cost.account_id', '=', $clientId);
                }
            )
            ->groupBy('day');
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $dataForGoogle = $dataForGoogle->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
            $ydnAccountDataForGraph = $ydnAccountDataForGraph->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }

        $data = $data->union($dataForGoogle)->union($ydnAccountDataForGraph);
        $sql = $this->getBindingSql($data);
        $data = DB::table(DB::raw("({$sql}) as tbl"))
            ->select(DB::raw('day, sum(data) as data'))
            ->groupBy('day');

        $data = $data->get();

        return $data;
    }

    /**
     * @param $fieldNames
     * @param $accountStatus
     * @param $startDay
     * @param $endDay
     * @return array
     */
    // public function calculateData(
    //     $engine,
    //     $fieldNames,
    //     $accountStatus,
    //     $startDay,
    //     $endDay,
    //     $groupedByField,
    //     $agencyId = null,
    //     $accountId = null,
    //     $clientId = null,
    //     $campaignId = null,
    //     $adGroupId = null,
    //     $adReportId = null,
    //     $keywordId = null
    // ) {
    //     Event::listen(StatementPrepared::class, function ($event) {
    //         $event->statement->setFetchMode(PDO::FETCH_OBJ);
    //     });
    //     $modelYdnReport = new RepoYdnReport();
    //     $tableName = $this->getTable();
    //     $fieldNames = $this->unsetColumns($fieldNames, [$groupedByField, self::PAGE_ID]);
    //     $ydnAccountCalculate = $modelYdnReport->ydnAccountCalculate($fieldNames, $startDay, $endDay, $clientId);
    //     $arrayCalculate = $this->getAggregated($fieldNames);
    //     $joinTableName = (new RepoYssAccount)->getTable();
    //     if (empty($arrayCalculate)) {
    //         return $arrayCalculate;
    //     }
    //     $adwAccountReport = $this->getDatasAccountOfGoogle($fieldNames, $startDay, $endDay, $clientId);
    //     $data = $this->select($arrayCalculate)
    //         ->join(
    //             $joinTableName,
    //             $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
    //             '=',
    //             $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
    //         )->where(
    //             function (Builder $query) use ($startDay, $endDay) {
    //                 $this->addTimeRangeCondition($startDay, $endDay, $query);
    //             }
    //         )
    //         ->where(
    //             function ($query) use ($clientId) {
    //                 $query->where('repo_yss_account_report_cost.account_id', '=', $clientId);
    //             }
    //         );
    //     if ($accountStatus == self::HIDE_ZERO_STATUS) {
    //         $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
    //         $adwAccountReport = $adwAccountReport->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
    //         $ydnAccountCalculate = $ydnAccountCalculate->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
    //     }
    //     $data = $data->union($adwAccountReport)->union($ydnAccountCalculate);
    //
    //     $sql = $this->getBindingSql($data);
    //     $rawExpression = $this->getRawExpressions($fieldNames);
    //     $data = DB::table(DB::raw("({$sql}) as tbl"))
    //     ->select($rawExpression);
    //     $data = $data->first();
    //     return $data;
    // }

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
        $fieldNames = $this->unsetColumns($fieldNames, ['accountName', 'accountid']);
        //YSS
        $joinTableName = 'repo_yss_accounts';
        $yssAggregations = $this->getAggregated($fieldNames);
        $yssAggregations = array_merge($this->getAggregatedForAccounts('repo_yss_account_report_cost'), $yssAggregations);
        $yssData = $this->select($yssAggregations)
        ->join(
            $joinTableName,
            $this->getTable(). '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
            '=',
            $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
        )->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query);
            }
        )->where(
            function (Builder $query) use ($clientId) {
                $query->where('repo_yss_account_report_cost.account_id', '=', $clientId);
            }
        );
        $yssData->leftJoin(
            DB::raw("`phone_time_use`"),
            function (JoinClause $join) {
                $join->on(
                    function (JoinClause $builder) {
                        $builder->whereRaw("`phone_time_use`.`account_id` = `repo_yss_account_report_cost`.`account_id`")
                        ->whereRaw("`phone_time_use`.`campaign_id` = `repo_yss_account_report_cost`.`campaign_id`")
                        ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'")
                        ->whereRaw("`phone_time_use`.`source` = 'yss'")
                        ->whereRaw(
                            "STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_yss_account_report_cost`.`day`"
                        );
                    }
                );
            }
        );
        //Adw
        $adwAggregations = $this->getAggregatedOfGoogle($fieldNames);
        $adwAggregations = array_merge($this->getAggregatedForAccounts('repo_adw_account_report_cost'), $adwAggregations);
        $adwData = RepoAdwAccountReportCost::select($adwAggregations)
        ->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query);
            }
        )->where(
            function (Builder $query) use ($clientId) {
                $query->where('repo_adw_account_report_cost.account_id', '=', $clientId);
            }
        );
        $adwData->leftJoin(
            DB::raw("`phone_time_use`"),
            function (JoinClause $join) {
                $join->on(
                    function (JoinClause $builder) {
                        $builder->whereRaw("`phone_time_use`.`account_id` = `repo_adw_account_report_cost`.`account_id`")
                        ->whereRaw("`phone_time_use`.`campaign_id` = `repo_adw_account_report_cost`.`campaign_id`")
                        ->whereRaw(
                            "STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_adw_account_report_cost`.`day`"
                        )->whereRaw("`phone_time_use`.`source` = 'adw'")
                        ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'");
                    }
                );
            }
        );
        //YDN
        $modelYdnReport = new RepoYdnReport;
        $ydnData = $modelYdnReport->ydnAccountCalculate($fieldNames, $startDay, $endDay, $clientId);
        $ydnData->leftJoin(
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

        $data = $ydnData->union($yssData)->union($adwData);
        $sql = $this->getBindingSql($data);
        $rawExpression = $this->getRawExpressions($fieldNames);
        $array = [
            DB::raw('SUM(call_cv) as call_cv'),
            DB::raw('AVG(call_cvr) as call_cvr'),
            DB::raw('AVG(call_cpa) as call_cpa'),
            DB::raw('SUM(web_cv) as Web_CV'),
            DB::raw('AVG(web_cvr) as Web_CVR'),
            DB::raw('AVG(web_cpa) as Web_CPA')
        ];
        $rawExpression = array_merge($array, $rawExpression);
        $data = DB::table(DB::raw("({$sql}) as tbl"))
        ->select($rawExpression);
        return $data->first();
    }

    public function repoYssAccounts()
    {
        return $this->hasOne('App\Model\RepoYssAccount', 'account_id', 'account_id');
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
        $fieldNames = $this->unsetColumns($fieldNames, ['accountName', 'accountid']);
        //YSS
        $joinTableName = 'repo_yss_accounts';
        $yssAggregations = $this->getAggregated($fieldNames);
        $yssData = $this->select($yssAggregations)
        ->join(
            $joinTableName,
            $this->getTable(). '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
            '=',
            $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
        )->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query);
            }
        )->where(
            function (Builder $query) use ($clientId) {
                $query->where('repo_yss_account_report_cost.account_id', '=', $clientId);
            }
        );
        $yssData->leftJoin(
            DB::raw("`phone_time_use`"),
            function (JoinClause $join) {
                $join->on(
                    function (JoinClause $builder) {
                        $builder->whereRaw("`phone_time_use`.`account_id` = `repo_yss_account_report_cost`.`account_id`")
                        ->whereRaw("`phone_time_use`.`campaign_id` = `repo_yss_account_report_cost`.`campaign_id`")
                        ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'")
                        ->whereRaw("`phone_time_use`.`source` = 'yss'")
                        ->whereRaw(
                            "STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_yss_account_report_cost`.`day`"
                        );
                    }
                );
            }
        );
        //Adw
        $adwAggregations = $this->getAggregatedOfGoogle($fieldNames);
        $adwData = RepoAdwAccountReportCost::select($adwAggregations)
        ->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query);
            }
        )->where(
            function (Builder $query) use ($clientId) {
                $query->where('repo_adw_account_report_cost.account_id', '=', $clientId);
            }
        );
        $adwData->leftJoin(
            DB::raw("`phone_time_use`"),
            function (JoinClause $join) {
                $join->on(
                    function (JoinClause $builder) {
                        $builder->whereRaw("`phone_time_use`.`account_id` = `repo_adw_account_report_cost`.`account_id`")
                        ->whereRaw("`phone_time_use`.`campaign_id` = `repo_adw_account_report_cost`.`campaign_id`")
                        ->whereRaw(
                            "STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_adw_account_report_cost`.`day`"
                        )->whereRaw("`phone_time_use`.`source` = 'adw'")
                        ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'");
                    }
                );
            }
        );
        //YDN
        $modelYdnReport = new RepoYdnReport;
        $ydnData = $modelYdnReport->calculateSummaryDataYdn($fieldNames, $startDay, $endDay, $clientId);
        $ydnData->leftJoin(
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

        $data = $ydnData->union($yssData)->union($adwData);
        Event::listen(StatementPrepared::class, function ($event) {
            $event->statement->setFetchMode(PDO::FETCH_ASSOC);
        });
        $sql = $this->getBindingSql($data);
        $rawExpression = $this->getRawExpressions($fieldNames);
        $data = DB::table(DB::raw("({$sql}) as tbl"))
        ->select($rawExpression)->first();
        if ($data === null) {
            $data = [
                'clicks' => 0,
                'impressions' => 0,
                'cost' => 0,
                'averageCpc' => 0,
                'averagePosition' => 0
            ];
        }
        return $data;
    }

    // public function calculateSummaryData(
    //     $engine,
    //     $fieldNames,
    //     $accountStatus,
    //     $startDay,
    //     $endDay,
    //     $agencyId = null,
    //     $accountId = null,
    //     $clientId = null,
    //     $campaignId = null,
    //     $adGroupId = null,
    //     $adReportId = null,
    //     $keywordId = null
    // ) {
    //     $modelYdnReport = new RepoYdnReport();
    //     $tableName = $this->getTable();
    //     $arrayCalculate = $this->getAggregated($fieldNames);
    //     $joinTableName = (new RepoYssAccount)->getTable();
    //     $ydnAccountCalculate = $modelYdnReport->calculateSummaryDataYdn($fieldNames, $startDay, $endDay, $clientId);
    //     $adwAccountReport = $this->getDatasAccountOfGoogle($fieldNames, $startDay, $endDay, $clientId);
    //     $data = self::select($arrayCalculate)
    //             ->join(
    //                 $joinTableName,
    //                 $tableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
    //                 '=',
    //                 $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
    //             )->where(
    //                 function (Builder $query) use ($startDay, $endDay) {
    //                     $this->addTimeRangeCondition($startDay, $endDay, $query);
    //                 }
    //             )
    //             ->where(
    //                 function ($query) use ($clientId) {
    //                     $query->where('repo_yss_account_report_cost.account_id', '=', $clientId);
    //                 }
    //             );
    //     if ($accountStatus == self::HIDE_ZERO_STATUS) {
    //         $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
    //         $adwAccountReport = $adwAccountReport->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
    //         $ydnAccountCalculate = $ydnAccountCalculate->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
    //     }
    //
    //     $data->union($adwAccountReport)->union($ydnAccountCalculate);
    //
    //     $sql = $this->getBindingSql($data);
    //     Event::listen(StatementPrepared::class, function ($event) {
    //         $event->statement->setFetchMode(PDO::FETCH_ASSOC);
    //     });
    //     $data = DB::table(DB::raw("({$sql}) as tbl"))
    //         ->select(DB::raw('
    //             sum(clicks) as clicks,
    //             sum(cost) as cost,
    //             sum(impressions) as impressions,
    //             avg(averageCpc) as averageCpc,
    //             avg(averagePosition) as averagePosition
    //         '));
    //
    //     $data = $data->first();
    //     if ($data === null) {
    //         $data = [
    //             'clicks' => 0,
    //             'impressions' => 0,
    //             'cost' => 0,
    //             'averageCpc' => 0,
    //             'averagePosition' => 0
    //         ];
    //     }
    //
    //     return $data;
    // }

    // public function getDataForTable(
    //     $engine,
    //     array $fieldNames,
    //     $accountStatus,
    //     $startDay,
    //     $endDay,
    //     $pagination,
    //     $columnSort,
    //     $sort,
    //     $groupedByField,
    //     $agencyId = null,
    //     $accountId = null,
    //     $clientId = null,
    //     $campaignId = null,
    //     $adGroupId = null,
    //     $adReportId = null,
    //     $keywordId = null
    // ) {
    //     $modelYdnReport = new RepoYdnReport();
    //     $ydnAccountReports = $modelYdnReport->getAllAccountYdn(
    //         $fieldNames,
    //         $groupedByField,
    //         $columnSort,
    //         $sort,
    //         $startDay,
    //         $endDay,
    //         $clientId,
    //         $accountId
    //     );
    //
    //     $aggregations = $this->getAggregated($fieldNames);
    //     $joinTableName = (new RepoYssAccount)->getTable();
    //
    //     $adwAggregations = $this->getAggregatedOfGoogle($fieldNames);
    //     $adwAccountReport = RepoAdwAccountReportCost::select(
    //         array_merge([DB::raw("'adw' as engine")], $adwAggregations)
    //     )
    //         ->where(
    //             function (Builder $query) use ($startDay, $endDay) {
    //                 $this->addTimeRangeCondition($startDay, $endDay, $query);
    //             }
    //         )->where(
    //             function (Builder $query) use ($clientId) {
    //                 $query->where('account_id', '=', $clientId);
    //             }
    //         )
    //         ->groupBy($groupedByField)
    //         ->orderBy($columnSort, $sort);
    //
    //     if (!in_array($groupedByField, $this->groupByFieldName)) {
    //         $adwAccountReport = $adwAccountReport->groupBy(self::ADW_CUSTOMER_ID);
    //     }
    //
    //     $datas = $this->select(array_merge([DB::raw("'yss' as engine")], $aggregations))
    //         ->join(
    //             $joinTableName,
    //             $this->getTable(). '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
    //             '=',
    //             $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
    //         )
    //         ->where(
    //             function (Builder $query) use ($startDay, $endDay) {
    //                 $this->addTimeRangeCondition($startDay, $endDay, $query);
    //             }
    //         )->where(
    //             function (Builder $query) use ($clientId) {
    //                 $query->where('repo_yss_account_report_cost.account_id', '=', $clientId);
    //             }
    //         )
    //         ->groupBy($groupedByField)
    //         ->orderBy($columnSort, $sort);
    //     if (!in_array($groupedByField, $this->groupByFieldName)) {
    //         $datas = $datas->groupBy('repo_yss_accounts.accountid');
    //     }
    //     if ($accountStatus == self::HIDE_ZERO_STATUS) {
    //         $datas = $datas->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
    //         $adwAccountReport = $adwAccountReport->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
    //         $ydnAccountReports = $ydnAccountReports->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
    //     }
    //
    //     $datas = $datas->union($adwAccountReport)->union($ydnAccountReports);
    //
    //     if (in_array($groupedByField, $this->groupByFieldName)) {
    //         Event::listen(StatementPrepared::class, function ($event) {
    //             $event->statement->setFetchMode(PDO::FETCH_ASSOC);
    //         });
    //         $fieldNames = $this->unsetColumns($fieldNames, ['accountid']);
    //
    //         $sql = $this->getBindingSql($datas);
    //         $rawExpressions = $this->getRawExpressions($fieldNames);
    //         array_unshift($rawExpressions, DB::raw($groupedByField));
    //         return DB::table(DB::raw("({$sql}) as tbl"))
    //             ->select(
    //                 $rawExpressions
    //             )
    //             ->groupBy($groupedByField)
    //             ->orderBy($columnSort, $sort)->get();
    //     }
    //     return $datas->orderBy($columnSort, $sort)->get();
    // }

    protected function getDatasAccountOfGoogle(
        array $fieldNames,
        $startDay,
        $endDay,
        $clientId = null
    ) {
        $adwAggregations = $this->getAggregatedOfGoogle($fieldNames);
        $adwAccountReport = RepoAdwAccountReportCost::select(array_merge($adwAggregations))
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )->where(
                function (Builder $query) use ($clientId) {
                    $query->where('account_id', '=', $clientId);
                }
            );

        return $adwAccountReport;
    }

    public function yssAccountDataForGraphOfAgencyList($column, $startDay, $endDay)
    {
        $aggreations = $this->getAggregatedGraph($column);
        return self::select($aggreations)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy('day');
    }

    public function getYssAccountAgency(array $fieldNames, $startDay, $endDay)
    {
        $getAggregatedYssAccounts = $this->getAggregatedAgency($fieldNames);

        $accounts = self::select($getAggregatedYssAccounts)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->groupBy(self::FOREIGN_KEY_YSS_ACCOUNTS);

        return $accounts;
    }

    public function getGraphForAgencyYss($column, $startDay, $endDay, $arrAccountsAgency)
    {
        $getAggregatedYssAccounts = $this->getAggregatedGraph($column);
        return self::select($getAggregatedYssAccounts)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->whereIn('account_id', $arrAccountsAgency)
            ->groupBy('day');
    }

    private function getAggregatedForAccounts($tableName)
    {
        return [
            DB::raw('COUNT(`phone_time_use`.`id`) AS call_cv'),
            DB::raw(
                "((SUM(`{$tableName}`.`conversions`) + COUNT(`phone_time_use`.`id`)) "
                . "/ SUM(`{$tableName}`.`clicks`)) * 100 AS call_cvr"
            ),
            DB::raw(
                "SUM(`{$tableName}`.`cost`) / (SUM(`{$tableName}`.`conversions`) "
                . "+ COUNT(`phone_time_use`.`id`)) AS call_cpa"
            ),
            DB::raw(
                "SUM(`{$tableName}`.conversions) AS Web_CV"
            ),
            DB::raw(
                "(SUM(`{$tableName}`.conversions) / SUM(`{$tableName}`.clicks) * 100) AS Web_CVR"
            ),
            DB::raw(
                "(SUM(`{$tableName}`.cost) / SUM(`{$tableName}`.conversions)) AS Web_CPA"
            )
        ];
    }

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
        // YSS
        $joinTableName = 'repo_yss_accounts';
        $yssAggregations = $this->getAggregated($fieldNames);
        $yssAggregations = array_merge($this->getAggregatedForAccounts('repo_yss_account_report_cost'), $yssAggregations);
        $yssData = $this->select(
            array_merge([DB::raw("'yss' as engine")], $yssAggregations)
        )->join(
            $joinTableName,
            $this->getTable(). '.'.self::FOREIGN_KEY_YSS_ACCOUNTS,
            '=',
            $joinTableName . '.'.self::FOREIGN_KEY_YSS_ACCOUNTS
        )->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query);
            }
        )->where(
            function (Builder $query) use ($clientId) {
                $query->where('repo_yss_account_report_cost.account_id', '=', $clientId);
            }
        )
        ->groupBy($groupedByField)
        ->orderBy($columnSort, $sort);
        $yssData = $yssData->leftJoin(
            DB::raw("`phone_time_use`"),
            function (JoinClause $join) {
                $join->on(
                    function (JoinClause $builder) {
                        $builder->whereRaw("`phone_time_use`.`account_id` = `repo_yss_account_report_cost`.`account_id`")
                        ->whereRaw("`phone_time_use`.`campaign_id` = `repo_yss_account_report_cost`.`campaign_id`")
                        ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'")
                        ->whereRaw("`phone_time_use`.`source` = 'yss'")
                        ->whereRaw(
                            "STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_yss_account_report_cost`.`day`"
                        );
                    }
                );
            }
        );
        if (!in_array($groupedByField, $this->groupByFieldName)) {
            $yssData = $yssData->groupBy('repo_yss_accounts.accountid');
        }

        // YDN
        $accountYdnReport = new RepoYdnReport;
        $ydnData = $accountYdnReport->getAllAccountYdn(
            $fieldNames,
            $groupedByField,
            $columnSort,
            $sort,
            $startDay,
            $endDay,
            $clientId,
            $accountId
        );
        $ydnData->leftJoin(
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

        //Adw
        $adwAggregations = $this->getAggregatedOfGoogle($fieldNames);
        $adwAggregations = array_merge($this->getAggregatedForAccounts('repo_adw_account_report_cost'), $adwAggregations);
        $adwData = RepoAdwAccountReportCost::select(
            array_merge([DB::raw("'adw' as engine")], $adwAggregations)
        )->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query);
            }
        )->where(
            function (Builder $query) use ($clientId) {
                $query->where('repo_adw_account_report_cost.account_id', '=', $clientId);
            }
        )
        ->groupBy($groupedByField)
        ->orderBy($columnSort, $sort);

        if (!in_array($groupedByField, $this->groupByFieldName)) {
            $adwData = $adwData->groupBy(self::ADW_CUSTOMER_ID);
        }
        $adwData->leftJoin(
            DB::raw("`phone_time_use`"),
            function (JoinClause $join) {
                $join->on(
                    function (JoinClause $builder) {
                        $builder->whereRaw("`phone_time_use`.`account_id` = `repo_adw_account_report_cost`.`account_id`")
                        ->whereRaw("`phone_time_use`.`campaign_id` = `repo_adw_account_report_cost`.`campaign_id`")
                        ->whereRaw(
                            "STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_adw_account_report_cost`.`day`"
                        )->whereRaw("`phone_time_use`.`source` = 'adw'")
                        ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'");
                    }
                );
            }
        );

        return $adwData->union($ydnData)->union($yssData)->orderBy($columnSort, $sort)->get();
    }
}
