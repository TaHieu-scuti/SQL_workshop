<?php

namespace App;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Model\RepoYssAccount;

use DateTime;
use Exception;

abstract class AbstractReportModel extends Model
{
    const HIDE_ZERO_STATUS = 'hideZero';
    const SHOW_ZERO_STATUS = 'showZero';
    const SUM_IMPRESSIONS_EQUAL_ZERO = 'SUM(impressions) = 0';
    const SUM_IMPRESSIONS_NOT_EQUAL_ZERO = 'SUM(impressions) != 0';
    const SUM_IMPRESSIONS_NOT_EQUAL_ZERO_OF_CLIENT = 'impressions != 0';
    // Please override these constants in the derived report models when necessary
    const FIELD_TYPE = 'float';
    const GROUPED_BY_FIELD_NAME = 'id';
    const PAGE_ID = 'pageId';
    const DEVICE = "device";
    const DAY_OF_WEEK = "dayOfWeek";
    const PREFECTURE ="prefecture";
    const HOUR_OF_DAY = "hourofday";
    const SESSION_KEY_ENGINE = 'engine';
    const YSS_SEARCH_QUERY = 'searchQuery';
    const CLICKS = 'clicks';
    const COST = 'cost';
    const IMPRESSIONS = 'impressions';
    const CONVERSIONS = 'conversions';
    const CTR = 'ctr';
    const AVERAGE_POSITION = 'averagePosition';
    const AVERAGE_CPC = 'averageCpc';
    const ADW_AVERAGE_CPC = 'avgCPC';
    const ADW_AVERAGE_POSITION = 'avgPosition';
    const ADW_CAMPAIGN_NAME = 'campaign';
    const YSS_CAMPAIGN_NAME = 'campaignName';
    const ADW_ADGROUP_NAME = 'adGroup';
    const YSS_ADGROUP_NAME = 'adgroupName';
    const ADW_SEARCH_QUERY = 'searchTerm';
    const YSS_IMPRESSION_SHARE = 'impressionShare';
    const DAILY_SPENDING_LIMIT = 'dailySpendingLimit';
    const FOREIGN_KEY_YSS_ACCOUNTS = 'account_id';

    const FIELDS = [
    ];

    const AVERAGE_FIELDS = [
        self::AVERAGE_CPC,
        self::AVERAGE_POSITION,
        self::CTR,
        self::YSS_IMPRESSION_SHARE
    ];

    const SUM_FIELDS = [
        self::CLICKS,
        self::IMPRESSIONS,
        self::COST,
        self::CONVERSIONS,
    ];

    const SUMMARY_FIELDS = [
        self::CLICKS,
        self::IMPRESSIONS,
        self::COST
    ];

    const YSS_FIELDS_MAP = [
//      'columns' => 'alias'
        self::YSS_IMPRESSION_SHARE => self::YSS_IMPRESSION_SHARE,
        'keywordMatchType' => 'matchType'
    ];

    const ADW_FIELDS_MAP = [
//      'columns' => 'alias'
        self::ADW_AVERAGE_CPC => self::AVERAGE_CPC,
        self::ADW_AVERAGE_POSITION => self::AVERAGE_POSITION,
        self::ADW_CAMPAIGN_NAME => self::YSS_CAMPAIGN_NAME,
        self::ADW_ADGROUP_NAME => self::YSS_ADGROUP_NAME,
    ];

    const YDN_FIELDS_MAP = [
//      'columns' => 'alias'
        'keywordMatchType' => 'matchType',
    ];

    const ALL_HIGHER_LAYERS = [];
    const ADW = 'adw';
    const YSS = 'yss';

    protected $casts = [
        'conversions' => 'integer',
    ];

    protected $groupByFieldName = [
        self::DEVICE,
        self::HOUR_OF_DAY,
        self::DAY_OF_WEEK,
        self::PREFECTURE,
    ];

    protected $groupBy = [];
    protected $preFixRoute = '';

    /**
     * @param string[] $fieldNames
     * @return Expression[]
     */
    protected function getAggregated(array $fieldNames, array $higherLayerSelections = null, $tableName = '')
    {
        $fieldNames = $this->updateFieldNames($fieldNames);
        if (empty($tableName)) {
            $tableName = $this->getTable();
        }
        $joinTableName = (new RepoYssAccount)->getTable();
        if (isset($fieldNames[0]) && $fieldNames[0] === self::PREFECTURE) {
            $tableName = 'repo_yss_prefecture_report_cost';
        }
        if (array_search(static::GROUPED_BY_FIELD_NAME, $fieldNames) === false) {
            $key = array_search(static::PAGE_ID, $fieldNames);
            if ($key !== false) {
                unset($fieldNames[$key]);
            }
        }
        $arrayCalculate = [];
        foreach ($fieldNames as $key => $fieldName) {
            if ($fieldName === 'impressionShare' && session(static::SESSION_KEY_ENGINE) === 'ydn') {
                continue;
            }
            if ($fieldName === 'region') {
                $arrayCalculate[] = $fieldName;
                continue;
            }
            if ($fieldName === 'impressionShare' && session(self::SESSION_KEY_ENGINE) === 'adw') {
                if (in_array(static::GROUPED_BY_FIELD_NAME, ['keyword']) === true) {
                    $arrayCalculate[] = DB::raw(
                        'ROUND(AVG(' . $tableName . '.searchImprShare), 2) AS ' . $fieldName
                    );
                    continue;
                } elseif (static::GROUPED_BY_FIELD_NAME === 'ad') {
                    $arrayCalculate[] = DB::raw('0 AS '. $fieldName);
                    continue;
                } else {
                    $arrayCalculate[] = DB::raw(
                        'ROUND(AVG(' . $tableName . '.searchImprShare) + AVG(' .
                        $tableName . '.contentImprShare), 2) AS ' . $fieldName
                    );
                    continue;
                }
            }
            if ($fieldName === self::DAILY_SPENDING_LIMIT) {
                $arrayCalculate[] = DB::raw(
                    'SUM( ' . $fieldName . ' ) AS ' . $fieldName
                );
            }
            if ($fieldName === 'matchType') {
                $arrayCalculate[] = DB::raw($tableName . '.' . $key . ' as ' . $fieldName);
            }
            if ($fieldName === static::GROUPED_BY_FIELD_NAME) {
                if (static::PAGE_ID !== 'accountid' && static::PAGE_ID !== 'pageId') {
                    $arrayCalculate[] = static::PAGE_ID;
                    $this->groupBy[] = static::PAGE_ID;
                }
                $arrayCalculate[] = $fieldName;
                if (!empty($higherLayerSelections)) {
                    $arrayCalculate = array_merge($arrayCalculate, $higherLayerSelections);
                }
                continue;
            }

            if ($fieldName === self::DEVICE
                || $fieldName === self::HOUR_OF_DAY
                || $fieldName === self::DAY_OF_WEEK
                || $fieldName === self::PREFECTURE
                || $fieldName === 'adType'
                || $fieldName === self::YSS_SEARCH_QUERY
                || $fieldName === self::ADW_SEARCH_QUERY
            ) {
                if ($fieldName === self::DAY_OF_WEEK && session(static::SESSION_KEY_ENGINE) === 'ydn') {
                    $arrayCalculate[] = DB::raw($key . ' as ' . $fieldName);
                } elseif ($fieldName === self::HOUR_OF_DAY
                    && static::PAGE_ID === 'keywordID'
                    && $tableName !== 'temporary_table'
                ) {
                    $arrayCalculate[] = DB::raw('hour('.$tableName . '.day) as ' . $fieldName);
                } else {
                    $arrayCalculate[] = DB::raw($tableName . '.' . $key . ' as ' . $fieldName);
                }

                continue;
            }

            if ($fieldName === 'accountid') {
                if ($tableName === 'temporary_account_table') {
                    $arrayCalculate[] = DB::raw($tableName . '.' . $fieldName);
                } else {
                    $arrayCalculate[] = DB::raw($joinTableName . '.' . $fieldName);
                }
            }

            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ROUND(AVG(' . $tableName . '.' . $key . '), 2), 0) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if ($tableName === 'temporary_table'
                    || $tableName === 'temporary_table_ad'
                    || $tableName === 'temporary_account_table'
                ) {
                    $arrayCalculate[] = DB::raw(
                        'IFNULL(SUM( ' . $tableName . '.' . $key . ' ), 0) AS ' . $fieldName
                    );
                } else {
                    if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                            ->getType()
                            ->getName()
                        === self::FIELD_TYPE
                    ) {
                        $arrayCalculate[] = DB::raw(
                            'IFNULL(ROUND(SUM(' . $tableName . '.' . $key . '), 2), 0) AS ' . $fieldName
                        );
                    } else {
                        $arrayCalculate[] = DB::raw(
                            'IFNULL(SUM( ' . $tableName . '.' . $key . ' ), 0) AS ' . $fieldName
                        );
                    }
                }
            }
        }
        return $arrayCalculate;
    }

    protected function getAggregatedToUpdateTemporatyTable(
        array $fieldNames,
        array $higherLayerSelections = null,
        $tableName = ''
    ) {
        $fieldNames = $this->updateFieldNames($fieldNames);
        if (empty($tableName)) {
            $tableName = $this->getTable();
        }
        $joinTableName = (new RepoYssAccount)->getTable();
        if (isset($fieldNames[0]) && $fieldNames[0] === self::PREFECTURE) {
            $tableName = 'repo_yss_prefecture_report_cost';
        }
        if (array_search(static::GROUPED_BY_FIELD_NAME, $fieldNames) === false) {
            $key = array_search(static::PAGE_ID, $fieldNames);
            if ($key !== false) {
                unset($fieldNames[$key]);
            }
        }
        $arrayCalculate = [];
        foreach ($fieldNames as $key => $fieldName) {
            if ($fieldName === 'impressionShare' && session(static::SESSION_KEY_ENGINE) === 'ydn') {
                continue;
            }
            if ($fieldName === 'dayOfWeek' && session(static::SESSION_KEY_ENGINE) === 'ydn') {
                $arrayCalculate[] = DB::raw('DAYNAME(`day`)');
                continue;
            }
            if ($fieldName === 'impressionShare' && session(self::SESSION_KEY_ENGINE) === 'adw') {
                if (in_array(static::GROUPED_BY_FIELD_NAME, ['keyword']) === true) {
                    $arrayCalculate[] = DB::raw(
                        'AVG(' . $tableName . '.searchImprShare) AS ' . $fieldName
                    );
                    continue;
                } elseif (static::GROUPED_BY_FIELD_NAME === 'ad') {
                    $arrayCalculate[] = DB::raw('0 AS '. $fieldName);
                    continue;
                } else {
                    $arrayCalculate[] = DB::raw(
                        'AVG('. $tableName .'.searchImprShare) + AVG('.
                        $tableName .'.contentImprShare) AS ' .$fieldName
                    );
                    continue;
                }
            }
            if ($fieldName === self::DAILY_SPENDING_LIMIT) {
                $arrayCalculate[] = DB::raw(
                    'SUM( ' .$fieldName. ' ) AS ' . $fieldName
                );
            }
            if ($fieldName === 'matchType') {
                $arrayCalculate[] = DB::raw($tableName . '.' . $key.' as '.$fieldName);
            }
            if ($fieldName === static::GROUPED_BY_FIELD_NAME) {
                if (static::PAGE_ID !== 'accountid' && static::PAGE_ID !== 'pageId') {
                    $arrayCalculate[] = static::PAGE_ID;
                    $this->groupBy[] = static::PAGE_ID;
                }
                $arrayCalculate[] = $fieldName;
                if (!empty($higherLayerSelections)) {
                    $arrayCalculate = array_merge($arrayCalculate, $higherLayerSelections);
                }

                continue;
            }

            if ($fieldName === self::DEVICE
                || $fieldName === self::HOUR_OF_DAY
                || $fieldName === self::DAY_OF_WEEK
                || $fieldName === self::PREFECTURE
                || $fieldName === 'adType'
                || $fieldName === self::YSS_SEARCH_QUERY
                || $fieldName === self::ADW_SEARCH_QUERY
            ) {
                if ($fieldName === self::DAY_OF_WEEK && session(static::SESSION_KEY_ENGINE) === 'ydn') {
                    $arrayCalculate[] = DB::raw($key.' as '.$fieldName);
                } else {
                    $arrayCalculate[] = DB::raw($tableName.'.'.$key.' as '.$fieldName);
                }

                continue;
            }

            if ($fieldName === 'accountid') {
                $arrayCalculate[] = DB::raw($joinTableName . '.' . $fieldName);
            }

            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(AVG(' . $tableName . '.' . $key . '), 0) AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if ($tableName === 'temporary_table') {
                    $arrayCalculate[] = DB::raw(
                        'IFNULL(SUM( ' . $tableName . '.' . $key . ' ), 0) AS ' . $fieldName
                    );
                } else {
                    if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                            ->getType()
                            ->getName()
                        === self::FIELD_TYPE
                    ) {
                        $arrayCalculate[] = DB::raw(
                            'IFNULL(SUM(' . $tableName . '.' . $key . '), 0) AS ' . $fieldName
                        );
                    } else {
                        $arrayCalculate[] = DB::raw(
                            'IFNULL(SUM( ' . $tableName . '.' . $key . ' ), 0) AS ' . $fieldName
                        );
                    }
                }
            }
        }
        return $arrayCalculate;
    }

    protected function getAggregatedAgency(array $fieldNames)
    {
        $arrayCalculate = [];
        $tableName = $this->getTable();
        $arrayCalculate[] = DB::raw($tableName.'.account_id AS account_id');
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ROUND(AVG(' . $tableName . '.' . static::ARR_FIELDS[$fieldName] . '), 2), 0)
                    AS ' . $fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                if (DB::connection()->getDoctrineColumn($tableName, $fieldName)
                        ->getType()
                        ->getName()
                    === self::FIELD_TYPE
                ) {
                    $arrayCalculate[] = DB::raw(
                        'IFNULL(ROUND(SUM(' . $tableName . '.' . static::ARR_FIELDS[$fieldName] . '), 2), 0)
                        AS ' . $fieldName
                    );
                } else {
                    $arrayCalculate[] = DB::raw(
                        'IFNULL(SUM( ' . $tableName . '.' . static::ARR_FIELDS[$fieldName] . ' ), 0) AS ' . $fieldName
                    );
                }
            }
        }
        return $arrayCalculate;
    }

    protected function getBindingSql($data)
    {
        $sql = $data->toSql();
        foreach ($data->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'".$binding."'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }

    protected function addQueryConditions(
        Builder $query,
        $clientId,
        $engine = null,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        if ($accountId !== null && $campaignId === null && $adGroupId === null && $adReportId === null) {
            if ($engine === 'adw') {
                $query->where($this->getTable().'.customerID', '=', $accountId);
            } else {
                $query->where($this->getTable().'.accountid', '=', $accountId);
            }
        }
        if ($campaignId !== null && $adGroupId === null && $adReportId === null) {
            $query->where($this->getTable().'.campaignID', '=', $campaignId);
        }
        if ($adGroupId !== null && $adReportId === null) {
            $query->where($this->getTable().'.adgroupID', '=', $adGroupId);
        }
        if ($adReportId !== null) {
            $query->where($this->getTable().'.adID', '=', $adReportId);
        }
        if ($keywordId !== null) {
            $query->where($this->getTable().'.keywordID', '=', $keywordId);
        }
        if ($accountId === null && $campaignId === null && $adGroupId === null && $adReportId === null) {
            $query->where($this->getTable().'.account_id', '=', $clientId);
        }
    }

    /**
     * @param string  $startDay
     * @param string  $endDay
     * @param Builder $query
     */
    protected function addTimeRangeCondition($startDay, $endDay, Builder $query, $tableName = null)
    {
        if ($tableName === null) {
            if ($startDay === $endDay) {
                $query->where($this->getTable().'.day', '=', $endDay);
            } else {
                $query->where($this->getTable().'.day', '>=', $startDay)
                    ->where($this->getTable().'.day', '<=', $endDay);
            }
        } else {
            if ($startDay === $endDay) {
                $query->where($tableName.'.day', '=', $endDay);
            } else {
                $query->where($tableName.'.day', '>=', $startDay)
                    ->where($tableName.'.day', '<=', $endDay);
            }
        }
    }

    protected function getAverageExpression($fieldName)
    {
        return DB::raw(
            'ROUND('.'AVG(' . $fieldName . '),2'.') AS ' . $fieldName
        );
    }

    protected function getTrimmedSumExpression($fieldName)
    {
        return DB::raw(
            'ROUND( SUM(' . $fieldName . '), 2) AS ' . $fieldName
        );
    }

    protected function getSumExpression($fieldName)
    {
        return DB::raw('SUM( ' . $fieldName . ' ) AS ' . $fieldName);
    }

    protected function getAggregatedForTable()
    {
        return [];
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
        $higherLayerSelections = [];
        if ($groupedByField !== self::DEVICE
            && $groupedByField !== self::HOUR_OF_DAY
            && $groupedByField !== self::DAY_OF_WEEK
            && $groupedByField !== self::PREFECTURE
        ) {
            $higherLayerSelections = $this->higherLayerSelections($fieldNames, $campaignId, $adGroupId);
        }
        if ($groupedByField === 'prefecture' && $engine === 'adw') {
            //replace prefecture with criteria.Name
            $key = array_search('prefecture', $fieldNames);
            $fieldNames[$key] = 'region';
        }
        $aggregations = $this->getAggregated($fieldNames, $higherLayerSelections);
        if ($groupedByField === 'dayOfWeek' && $engine === 'ydn') {
            array_push($this->groupBy, DB::raw('DAYNAME(day)'));
        } else {
            array_push($this->groupBy, $groupedByField);
        }
        if ($groupedByField === 'ad' || $groupedByField === 'adName') {
            array_push($this->groupBy, 'adType');
        }

        if ($groupedByField === 'keyword') {
            if ($engine === self::ADW) {
                array_push($this->groupBy, 'matchType');
            } elseif ($engine === self::YSS) {
                array_push($this->groupBy, 'keywordMatchType');
            }
        }
        // merge static::FIELDS in order to display ad as requested
        if ($engine === 'adw'
            && static::GROUPED_BY_FIELD_NAME === 'ad'
            && $this->preFixRoute === 'adgroup'
        ) {
            $this->groupBy = array_merge($this->groupBy, []);
        } else {
            $this->groupBy = array_merge($this->groupBy, static::FIELDS);
        }
        $groupBy = $this->groupBy;

        foreach ($groupBy as &$item) {
            if (is_string($item)) {
                if ($item === self::HOUR_OF_DAY && static::PAGE_ID === 'keywordID') {
                    $item = DB::raw('hour('.$this->getTable() . '.day)');
                } else {
                    $item = $this->getTable() . '.' . $item;
                }
            }
        }

        if ($groupedByField === 'prefecture' && $engine === 'adw') {
            //remove prefecture out of groupBy
            $key = array_search($this->getTable() . '.prefecture', $groupBy);
            unset($groupBy[$key]);
            $groupedByField = 'region';
            array_push($groupBy, $groupedByField);
        }
        foreach ($aggregations as &$aggregation) {
            if (is_string($aggregation)) {
                $aggregation = $this->getTable() . '.' . $aggregation;
            }
        }
        $selectBy = static::FIELDS;
        if ($engine === 'adw'
                && static::GROUPED_BY_FIELD_NAME === 'ad'
                && $this->preFixRoute === 'adgroup'
        ) {
            $selectBy = static::FIELDS_ADGROUP_ADW;
        }
        foreach ($selectBy as &$item) {
            if (is_string($item)) {
                $item = $this->getTable() . '.' . $item;
            }
        }
        $builder = $this->select(array_merge($selectBy, $aggregations))
            ->where(
                function (Builder $query) use (
                    $startDay,
                    $endDay,
                    $engine,
                    $clientId,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId
                ) {
                    $this->getCondition(
                        $query,
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
            )
            ->groupBy($groupBy);

        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $builder = $builder->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }
        return $builder;
    }

    protected function getCondition(
        Builder $query,
        $startDay,
        $endDay,
        $engine,
        $clientId = null,
        $accountId = null,
        $campaignId = null,
        $adGroupId = null,
        $adReportId = null,
        $keywordId = null
    ) {
        $query->where(
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
                $keywordId,
                $engine
            ) {
                $this->addQueryConditions(
                    $query,
                    $clientId,
                    $engine,
                    $accountId,
                    $campaignId,
                    $adGroupId,
                    $adReportId,
                    $keywordId
                );
            }
        )->where(
            function (Builder $query) use ($engine) {
                $this->addConditionNetworkQueryForADW($engine, $query);
            }
        );
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
        if ($groupedByField === 'ad' || $groupedByField === 'adName') {
            $fieldNames = $this->unsetColumns($fieldNames, ['adType']);
        }
        if ($groupedByField === 'keyword') {
            $fieldNames = $this->unsetColumns($fieldNames, ['matchType']);
        }
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
                    $keywordId,
                    $engine
                ) {
                    $this->addQueryConditions(
                        $query,
                        $clientId,
                        $engine,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    );
                }
            )->where(
                function (Builder $query) use ($engine) {
                    $this->addConditionNetworkQueryForADW($engine, $query);
                }
            );
        if ($accountStatus == self::HIDE_ZERO_STATUS) {
            $data = $data->havingRaw(self::SUM_IMPRESSIONS_NOT_EQUAL_ZERO);
        }

        return $data;
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
        $data = $this->getBuilderForCalculateData(
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

        $data = $data->first();

        if ($data === null) {
            $data = [];
        }
        return $data;
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
                    $adReportId,
                    $engine
                ) {
                    $this->addQueryConditions(
                        $query,
                        $clientId,
                        $engine,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId
                    );
                }
            )->where(
                function (Builder $query) use ($engine) {
                    $this->addConditionNetworkQueryForADW($engine, $query);
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

    /**
     * @param string $fieldName
     * @param int    $resultPerPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDataByFilter($fieldName, $resultPerPage)
    {
        return $this->paginate($resultPerPage, $fieldName);
    }

    /**
     * @return array
     */
    public function getColumnNames()
    {
        $columns = $this->getAllColumnNames();

        // unset "id" and "campaign_id" from array cause we dont need it for filter
        return $this->unsetColumns($columns, ['id', 'campaign_id']);
    }

    /**
     * @return array
     */
    public function getAllColumnNames()
    {
        return Schema::getColumnListing($this->getTable());
    }

    /**
     * @param string[] $columns
     * @param string[] $columnsToUnset
     * @return string[]
     */
    public function unsetColumns(array $columns, array $columnsToUnset)
    {
        foreach ($columnsToUnset as $name) {
            if (($key = array_search($name, $columns)) !== false) {
                unset($columns[$key]);
            }
        }

        return $columns;
    }

    /**
     * @param string[] $fieldNames
     * @param string   $accountStatus
     * @param string   $startDay
     * @param string   $endDay
     * @param int      $pagination
     * @param string   $columnSort
     * @param string   $sort
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
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
        $builder = $this->getBuilderForGetDataForTable(
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

        if ($engine === 'adw' && static::GROUPED_BY_FIELD_NAME === 'adGroup') {
            return $builder;
        }

        return $builder->paginate($pagination);
    }

    /**
     * @param string $column
     * @param string $accountStatus
     * @param string $startDay
     * @param string $endDay
     * @return \Illuminate\Support\Collection
     * @throws \InvalidArgumentException
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
        $column = $this->updateColumnForGraph($column);
        try {
            new DateTime($startDay); //NOSONAR
            new DateTime($endDay); //NOSONAR
        } catch (Exception $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), 0, $exception);
        }

        return $this->select(
            DB::raw('SUM(' . $column . ') as data'),
            DB::raw(
                'DATE(day) as day'
            )
        )
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
                    $keywordId,
                    $engine
                ) {
                    $this->addQueryConditions(
                        $query,
                        $clientId,
                        $engine,
                        $accountId,
                        $campaignId,
                        $adGroupId,
                        $adReportId,
                        $keywordId
                    );
                }
            )->where(
                function (Builder $query) use ($engine) {
                    $this->addConditionNetworkQueryForADW($engine, $query);
                }
            )
            ->groupBy('day')
            ->get();
    }

    /**
     * @param string $startDay
     * @param string $endDay
     * @return array
     */
    public function getSummaryData($startDay, $endDay)
    {
        $expressions = $this->getAggregated(static::SUMMARY_FIELDS);

        if (empty($expressions)) {
            return $expressions;
        }

        return $this->select($expressions)
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->first()
            ->toArray();
    }

    /**
     * @param string $startDay
     * @param string $endDay
     * @return array
     */
    public function getTotalsRow($startDay, $endDay)
    {
        $expressions = $this->getAggregated(array_merge(static::AVERAGE_FIELDS, static::SUM_FIELDS));

        $fields = $this->unsetColumns(static::FIELDS, [static::GROUPED_BY_FIELD_NAME]);

        return $this->select(array_merge($fields, $expressions))
            ->where(
                function (Builder $query) use ($startDay, $endDay) {
                    $this->addTimeRangeCondition($startDay, $endDay, $query);
                }
            )
            ->first()
            ->toArray();
    }

    /**
     * @return string[]
     */
    public function getColumnNamesForSearch($keyword)
    {
        $allFieldNames = array_merge(static::AVERAGE_FIELDS, static::SUM_FIELDS);
        $matchingFieldNames = [];
        foreach ($allFieldNames as $fieldName) {
            if (strpos($allFieldNames, $keyword) !== false) {
                $matchingFieldNames[] = $fieldName;
            }
        }

        return $matchingFieldNames;
    }

    public function updateFieldNames(array $fieldNames)
    {
        $resultFieldNames = [];
        $engine = session(self::SESSION_KEY_ENGINE);
        if ($engine === 'yss' || $engine === null) {
            $resultFieldNames = $this->setKeyFieldNames($fieldNames, self::YSS_FIELDS_MAP);
        } elseif ($engine === 'adw') {
            $resultFieldNames = $this->setKeyFieldNames($fieldNames, self::ADW_FIELDS_MAP);
        } elseif ($engine === 'ydn') {
            $resultFieldNames = $this->setKeyFieldNames($fieldNames, self::YDN_FIELDS_MAP);
        }
        return $resultFieldNames;
    }

    public function setKeyFieldNames(array $fieldNames, array $fieldsMap)
    {
        $result = [];
        foreach ($fieldNames as $fieldName) {
            //check fieldName is included in the fieldsMap
            $key = array_search($fieldName, $fieldsMap);
            if ($key !== false) {
                $result[$key] = $fieldsMap[$key];
            } else {
                $result[$fieldName] = $fieldName;
            }
        }
        return $result;
    }

    public function updateColumnForGraph($column)
    {
        $engine = session(self::SESSION_KEY_ENGINE);
        $arrayMapping = [];
        if ($engine === 'yss') {
            $arrayMapping = self::YSS_FIELDS_MAP;
        } elseif ($engine === 'adw') {
            $arrayMapping = self::ADW_FIELDS_MAP;
        }
        foreach ($arrayMapping as $key => $value) {
            if ($column === $value) {
                return $key;
            }
        }
        return $column;
    }

    protected function getRawExpressions($fieldNames)
    {
        $rawExpression = [];
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, $this->groupByFieldName)) {
                $rawExpression[] = $fieldName;
                continue;
            }
            if ($fieldName === 'accountName') {
                $rawExpression[] = DB::raw($fieldName. ' AS agencyName');
                continue;
            }
            if (in_array($fieldName, static::SUM_FIELDS)) {
                $rawExpression[] = DB::raw('sum(' .$fieldName. ') as ' . $fieldName);
            } elseif (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $rawExpression[] = DB::raw('avg(' .$fieldName. ') as ' . $fieldName);
            }
        }

        return $rawExpression;
    }

    public function higherLayerSelections(
        $fieldNames,
        $campaignId = null,
        $adGroupId = null,
        $tableName = ""
    ) {
        $table = $this->getTable();
        if ($tableName !== "") {
            $table = $tableName;
        }
        $arrayAlias = [];
        $arraySelections = [];

        if (isset($campaignId)) {
            array_push($arrayAlias, 'campaignID');
        }
        if (isset($adGroupId)) {
            array_push($arrayAlias, 'adgroupID');
        }

        $all_higher_layers = static::ALL_HIGHER_LAYERS;
        foreach ($all_higher_layers as $key => $value) {
            if (in_array($value['aliasId'], $arrayAlias)) {
                unset($all_higher_layers[$key]);
            }
        }

        foreach ($all_higher_layers as $key => $value) {
            $querySelectId = $value['columnId'];
            $querySelectName = DB::raw('(SELECT ' . $value['columnName'] .' FROM '. $value['tableJoin']
                . ' WHERE '. $table .'.'.$value['columnId']
                .' = '.$value['tableJoin'].'.'.$value['columnId'].' LIMIT 1) AS '.$value['aliasName']);
            array_push($arraySelections, $querySelectId, $querySelectName);
            array_push($this->groupBy, $value['columnId']);
        }

        if (static::GROUPED_BY_FIELD_NAME === 'keyword' && isset($adGroupId)) {
            array_push($arraySelections, 'adgroupID');
            array_push($this->groupBy, 'adgroupID');
        }

        return $arraySelections;
    }

    private function addConditionNetworkQueryForADW($engine, Builder $query)
    {
        if ($engine === 'adw') {
            if (static::GROUPED_BY_FIELD_NAME === 'keyword') {
                $query->where($this->getTable() . '.network', 'SEARCH');
            } elseif (static::GROUPED_BY_FIELD_NAME === 'ad') {
                $query->where($this->getTable() . '.network', 'CONTENT');
            } else {
                $query->where($this->getTable() . '.network', 'SEARCH')
                    ->orWhere($this->getTable() . '.network', 'CONTENT');
            }
        }
    }
}
