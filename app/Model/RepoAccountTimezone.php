<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

use App\Model\RepoYssAccountReportCost;

class RepoAccountTimezone extends RepoYssAccountReportCost
{
    protected function addJoinConditionForYss(Builder $builder)
    {
        $builder->leftJoin(
            DB::raw("`phone_time_use`"),
            function (JoinClause $join) {
                $join->on(
                    function (JoinClause $builder) {
                        $builder->whereRaw(
                            "`phone_time_use`.`account_id` = `repo_yss_account_report_cost`.`account_id`"
                        )->whereRaw("`phone_time_use`.`campaign_id` = `repo_yss_account_report_cost`.`campaign_id`")
                        ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'")
                        ->whereRaw("`phone_time_use`.`source` = 'yss'")
                        ->whereRaw(
                            "STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') =
                            `repo_yss_account_report_cost`.`day`"
                        )
                        ->whereRaw(
                            "HOUR(`phone_time_use`.`time_of_call`) = `repo_yss_account_report_cost`.`hourofday`"
                        );
                    }
                );
            }
        );
    }

    protected function addJoinConditionForAdw(Builder $builder)
    {
        $builder->leftJoin(
            DB::raw("`phone_time_use`"),
            function (JoinClause $join) {
                $join->on(
                    function (JoinClause $builder) {
                        $builder->whereRaw(
                            "`phone_time_use`.`account_id` = `repo_adw_account_report_cost`.`account_id`"
                        )->whereRaw("`phone_time_use`.`campaign_id` = `repo_adw_account_report_cost`.`campaign_id`")
                        ->whereRaw(
                            "STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') =
                            `repo_adw_account_report_cost`.`day`"
                        )->whereRaw("`phone_time_use`.`source` = 'adw'")
                        ->whereRaw("`phone_time_use`.`traffic_type` = 'AD'")
                        ->whereRaw(
                            "HOUR(`phone_time_use`.`time_of_call`) = `repo_adw_account_report_cost`.`hourOfDay`"
                        );
                    }
                );
            }
        );
    }

    protected function addJoinConditionForYdn(Builder $builder)
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
                ->where('phone_time_use.traffic_type', '=', 'AD')
                ->whereRaw(
                    "HOUR(`phone_time_use`.`time_of_call`) = `repo_ydn_reports`.`hourofday`"
                );
            }
        );
    }
}
