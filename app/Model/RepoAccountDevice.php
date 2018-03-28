<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder as QueryBuilder;

use App\Model\RepoYssAccountReportCost;

class RepoAccountDevice extends RepoYssAccountReportCost
{
    protected $groupBy = 'device';

    const YSS_PLATFORM = [
        'DESKTOP' => [
            'MOBILE' => 'No',
            'PLATFORM_NOT_LIKE' => 'Windows Phone%',
            'PLATFORM_LIKE' => [
                'Windows%',
                'Linux%',
                'Mac OS%',
                'FreeBSD%',
                'Unknown Windows OS%',
                'NetBSD%',
                'iOS%',
                'Android%',
                'Blackberry%'
            ]
        ],
        'SMART PHONE' => [
            'MOBILE' => 'Yes%',
            'PLATFORM_NOT_LIKE' => null,
            'PLATFORM_LIKE' => [
                'Windows Phone%',
                'iOS%',
                'Android%',
                'Blackberry%',
                'Symbian%'
            ],
        ],
        'NONE' => [
            'MOBILE' => null,
            'PLATFORM_NOT_LIKE' => null,
            'PLATFORM_LIKE' => ['Unknown Platform%']
        ]
    ];

    const YDN_PLATFORM = [
        'PC' => [
            'MOBILE' => 'No',
            'PLATFORM_NOT_LIKE' => 'Windows Phone%',
            'PLATFORM_LIKE' => [
                'Windows%',
                'Linux%',
                'Mac OS%',
                'FreeBSD%',
                'Unknown Windows OS%',
                'NetBSD%'
            ]
        ],
        'Tablet' => [
            'MOBILE' => 'No',
            'PLATFORM_NOT_LIKE' => null,
            'PLATFORM_LIKE' => [
                'iOS%',
                'Android%',
                'Blackberry%'
            ],
        ],
        'SmartPhone' => [
            'MOBILE' => 'Yes%',
            'PLATFORM_NOT_LIKE' => null,
            'PLATFORM_LIKE' => [
                'iOS%',
                'Android%',
                'Blackberry%',
                'Symbian%'
            ],
        ],
        'Other' => [
            'MOBILE' => null,
            'PLATFORM_NOT_LIKE' => null,
            'PLATFORM_LIKE' => ['Unknown Platform%']
        ]
    ];

    const ADW_PLATFORM = [
        'DESKTOP' => [
            'MOBILE' => 'No',
            'PLATFORM_NOT_LIKE' => 'Windows Phone%',
            'PLATFORM_LIKE' => [
                'Windows%',
                'Linux%',
                'Mac OS%',
                'FreeBSD%',
                'Unknown Windows OS%',
                'NetBSD%'
            ]
        ],
        'TABLET' => [
            'MOBILE' => 'No',
            'PLATFORM_NOT_LIKE' => null,
            'PLATFORM_LIKE' => [
                'iOS%',
                'Android%',
                'Blackberry%'
            ],
        ],
        'HIGH_END_MOBILE' => [
            'MOBILE' => 'Yes',
            'PLATFORM_NOT_LIKE' => null,
            'PLATFORM_LIKE' => null
        ],
        'UNKNOWN' => [
            'MOBILE' => null,
            'PLATFORM_NOT_LIKE' => null,
            'PLATFORM_LIKE' => ['Unknown Platform%']
        ]
    ];

    protected function insertYssDataToTemporaryTable(
        $clientId,
        $joinTableName,
        $groupedByField,
        $columns,
        $startDay,
        $endDay
    ) {
        $yssAggregations = $this->getAggregated($columns);
        foreach (self::YSS_PLATFORM as $key => $value) {
            $this->insertDeviceDataYssByType($yssAggregations, $clientId, $key, $columns, $startDay, $endDay);
        }
    }

    protected function insertYdnDataToTemporaryTable(
        $clientId,
        $groupedByField,
        $columns,
        $startDay,
        $endDay
    ) {
        $ydnReportModel = new RepoYdnReport;
        $ydnAggregations = $ydnReportModel->getAggregatedOfYdn($columns);
        foreach (self::YDN_PLATFORM as $key => $value) {
            $this->insertDeviceDataYdnByType($ydnAggregations, $clientId, $key, $columns, $startDay, $endDay);
        }
    }

    protected function insertAdwDataToTemporaryTable(
        $clientId,
        $groupedByField,
        $columns,
        $startDay,
        $endDay
    ) {
        $adwAggregations = $this->getAggregatedOfGoogle($columns);
        foreach (self::ADW_PLATFORM as $key => $value) {
            $this->insertDeviceDataAdwByType($adwAggregations, $clientId, $key, $columns, $startDay, $endDay);
        }
    }

    private function changeValueDeviceSelection($arraySelection, $engine, $device)
    {
        foreach ($arraySelection as $key => $value) {
            if (strripos($value->getValue(), 'as device') !== false) {
                $arraySelection[$key] = DB::raw('"'.$engine.' '.$device.'" as device');
                break;
            }
        }
        return $arraySelection;
    }

    private function insertDeviceDataYssByType($yssAggregations, $clientId, $device, $columns, $startDay, $endDay)
    {
        $yssAggregations = $this->changeValueDeviceSelection($yssAggregations, 'YSS', $device);
        $yssData = $this->select(
            array_merge(
                [DB::raw("'YSS' as engine, ".$this->getTable().".account_id as account_id")],
                $yssAggregations
            )
        )->addSelect(DB::raw('SUM('.$this->getTable().'.`conversions`) AS sumConversions'))
        ->where(
            function (Builder $query) use ($startDay, $endDay) {
                if ($startDay === $endDay) {
                    $query->whereDate($this->getTable().'.day', '=', $endDay);
                } else {
                    $query->whereDate($this->getTable().'.day', '>=', $startDay)
                        ->whereDate($this->getTable().'.day', '<=', $endDay);
                }
            }
        )
        ->where($this->getTable().'.account_id', '=', $clientId)
        ->where($this->getTable().'.device', '=', $device)
        ->groupBy($this->groupBy);

        DB::insert('INSERT into '.self::TEMPORARY_ACCOUNT_TABLE.' ('.implode(', ', $columns).', sumConversions) '
            . $this->getBindingSql($yssData));
    }

    private function insertDeviceDataYdnByType($ydnAggregations, $clientId, $device, $columns, $startDay, $endDay)
    {
        $ydnAggregations = $this->changeValueDeviceSelection($ydnAggregations, 'YDN', $device);
        $arraySelection = array_merge(
            [DB::raw("repo_ydn_reports.adID, 'ydn' as engine, repo_ydn_reports.account_id as account_id")],
            $ydnAggregations
        );
        $ydnData = (new RepoYdnReport)->select(
            array_merge(
                $arraySelection,
                [DB::raw('SUM(dailySpendingLimit) AS dailySpendingLimit,
                SUM(repo_ydn_reports.conversions) AS sumConversions')]
            )
        )->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_ydn_reports');
            }
        )
        ->where('repo_ydn_reports.account_id', '=', $clientId)
        ->where('repo_ydn_reports.device', '=', $device)
        ->groupBy($this->groupBy);

        DB::insert('INSERT into '.self::TEMPORARY_ACCOUNT_TABLE.'(adID, '.implode(', ', $columns)
            .', dailySpendingLimit, sumConversions) '
            . $this->getBindingSql($ydnData));
    }

    private function insertDeviceDataAdwByType($adwAggregations, $clientId, $device, $columns, $startDay, $endDay)
    {
        $adwAggregations = $this->changeValueDeviceSelection($adwAggregations, 'ADW', $device);
        $adwData = RepoAdwAccountReportCost::select(
            array_merge(
                [DB::raw("'adw ".$device."' as engine, repo_adw_account_report_cost.account_id as account_id")],
                $adwAggregations
            )
        )->addSelect(DB::raw('SUM(repo_adw_account_report_cost.conversions) AS sumConversions'))
        ->where(
            function (Builder $query) use ($startDay, $endDay) {
                $this->addTimeRangeCondition($startDay, $endDay, $query, 'repo_adw_account_report_cost');
            }
        )
        ->where('repo_adw_account_report_cost.account_id', '=', $clientId)
        ->where(
            function (Builder $query) {
                $query->whereRaw("`repo_adw_account_report_cost`.`network` = 'SEARCH'")
                ->orWhereRaw("`repo_adw_account_report_cost`.`network` = 'CONTENT'");
            }
        )
        ->where('repo_adw_account_report_cost.device', '=', $device)
        ->groupBy($this->groupBy);

        DB::insert('INSERT into '.self::TEMPORARY_ACCOUNT_TABLE.' ('.implode(', ', $columns).', sumConversions) '
            . $this->getBindingSql($adwData));
    }

    private function conditionForPlatform(QueryBuilder $query, $platform)
    {
        $table = 'repo_phone_time_use';
        if ($platform['MOBILE'] !== null) {
            $query->whereRaw($table.'.`mobile` LIKE "'.$platform['MOBILE'].'"');
        }
        if ($platform['PLATFORM_NOT_LIKE'] !== null) {
            $query->whereRaw($table.'.`platform` NOT LIKE "'.$platform['PLATFORM_NOT_LIKE'].'"');
        }
        if ($platform['PLATFORM_LIKE'] !== null) {
            $query->where(function ($subQuery) use ($table, $platform) {
                foreach ($platform['PLATFORM_LIKE'] as $key => $value) {
                    $subQuery->orWhereRaw($table.'.`platform` LIKE "'.$platform['PLATFORM_LIKE'][$key].'"');
                }
            });
        }
    }

    protected function updateTemporaryTableWithPhoneTimeUseForYssAdw(
        $account_id,
        $traffic_type,
        $source,
        $startDay,
        $endDay
    ) {
        $enginePlatform = self::YSS_PLATFORM;
        if ($source === 'adw') {
            $enginePlatform = self::ADW_PLATFORM;
        }
        foreach ($enginePlatform as $key => $platform) {
            $query = DB::table('repo_phone_time_use')
            ->select(DB::raw('
                "YSS '.$key.'" AS device,
                count(id) AS id,
                account_id,
                `source`'))
            ->where('account_id', $account_id)
            ->where('traffic_type', $traffic_type)
            ->where('source', $source)
            ->where(function (QueryBuilder $query) use ($startDay, $endDay) {
                $this->conditionForDate($query, 'repo_phone_time_use', $startDay, $endDay);
            })
            ->where(function (QueryBuilder $query) use ($platform) {
                $this->conditionForPlatform($query, $platform);
            });

            DB::update(
                'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
                .$this->getBindingSql($query).')AS tbl set totalPhoneTimeUse = tbl.id where '
                .self::TEMPORARY_ACCOUNT_TABLE.'.account_id = tbl.account_id AND '
                .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "'.$source.'" AND '
                .self::TEMPORARY_ACCOUNT_TABLE.'.device = tbl.device'
            );
        }
    }

    protected function updateTemporaryTableWithPhoneTimeUseForYdn($clientId, $startDay, $endDay)
    {
        foreach (self::YDN_PLATFORM as $key => $platform) {
            $query = DB::table('repo_phone_time_use')
            ->select(DB::raw('
                "YDN '.$key.'" AS device,
                count(id) AS id,
                account_id'))
            ->where('account_id', $clientId)
            ->where('source', '=', 'ydn')
            ->where(function (QueryBuilder $query) use ($startDay, $endDay) {
                $this->conditionForDate($query, 'repo_phone_time_use', $startDay, $endDay);
            })
            ->where(function (QueryBuilder $query) use ($platform) {
                $this->conditionForPlatform($query, $platform);
            });

            DB::update(
                'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
                .$this->getBindingSql($query).') AS tbl set totalPhoneTimeUse = tbl.id where '
                .self::TEMPORARY_ACCOUNT_TABLE.'.account_id = tbl.account_id AND '
                .self::TEMPORARY_ACCOUNT_TABLE.'.engine = "ydn" AND '
                .self::TEMPORARY_ACCOUNT_TABLE.'.device = tbl.device'
            );
        }
    }
}
