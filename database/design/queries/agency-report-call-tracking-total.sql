SELECT IFNULL(SUM(adw.impressions + ydn.impressions + yss.impressions), 0) AS impressions,
       IFNULL(SUM(adw.cost + ydn.cost + yss.cost), 0) AS cost,
       IFNULL(SUM(adw.clicks + ydn.clicks + yss.clicks), 0) AS clicks,
       IFNULL((AVG(ydn.ctr) + AVG(yss.ctr) + AVG(adw.ctr)) / 3, 0) AS ctr,
       IFNULL((AVG(ydn.averageCpc) + AVG(yss.averageCpc) + AVG(adw.averageCpc)) / 3, 0) AS averageCpc,
       IFNULL((AVG(ydn.averagePosition) + AVG(yss.averagePosition) + AVG(adw.averagePosition)) / 3, 0) AS averagePosition,
       IFNULL(SUM(ydn.web_cv), 0) AS ydn_web_cv,
       IFNULL(AVG(ydn.web_cvr), 0) AS ydn_web_cvr,
       IFNULL(AVG(ydn.web_cpa), 0) AS ydn_web_cpa,
       IFNULL(SUM(yss.web_cv), 0) AS yss_web_cv,
       IFNULL(AVG(yss.web_cvr), 0) AS yss_web_cvr,
       IFNULL(AVG(yss.web_cpa), 0) AS yss_web_cpa,
       IFNULL(SUM(adw.web_cv), 0) AS adw_web_cv,
       IFNULL(AVG(adw.web_cvr), 0) AS adw_web_cvr,
       IFNULL(AVG(adw.web_cpa), 0) AS adw_web_cpa,
       IFNULL(SUM(ydn.web_cv) + SUM(yss.web_cv) + SUM(adw.web_cv), 0) AS web_cv,
       IFNULL((SUM(ydn.web_cv) + SUM(yss.web_cv) + SUM(adw.web_cv)) / (SUM(ydn.clicks) + SUM(yss.clicks) + SUM(adw.clicks)), 0) AS web_cvr,
       IFNULL((SUM(ydn.cost) + SUM(yss.cost) + SUM(adw.cost)) / (SUM(ydn.web_cv) + SUM(yss.web_cv) + SUM(adw.web_cv)), 0) AS web_cpa,
       IFNULL(SUM(ydn.call_cv), 0) AS ydn_call_cv,
       IFNULL(AVG(ydn.call_cvr), 0) AS ydn_call_cvr,
       IFNULL(AVG(ydn.call_cpa), 0) AS ydn_call_cpa,
       IFNULL(SUM(yss.call_cv), 0) AS yss_call_cv,
       IFNULL(AVG(yss.call_cvr), 0) AS yss_call_cvr,
       IFNULL(AVG(yss.call_cpa), 0) AS yss_call_cpa,
       IFNULL(SUM(adw.call_cv), 0) AS adw_call_cv,
       IFNULL(AVG(adw.call_cvr), 0) AS adw_call_cvr,
       IFNULL(AVG(adw.call_cpa), 0) AS adw_call_cpa,
       IFNULL(SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv), 0) AS call_cv,
       IFNULL((SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv)) / (SUM(ydn.clicks) + SUM(yss.clicks) + SUM(adw.clicks)), 0) AS call_cvr,
       IFNULL((SUM(ydn.cost) + SUM(yss.cost) + SUM(adw.cost)) / (SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv)), 0) AS call_cpa,
       IFNULL(SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv) + SUM(ydn.web_cv) + SUM(yss.web_cv) + SUM(adw.web_cv), 0) AS total_cv,
       IFNULL((SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv) + SUM(ydn.web_cv) + SUM(yss.web_cv) + SUM(adw.web_cv)) / (SUM(ydn.clicks) + SUM(yss.clicks) + SUM(adw.clicks)), 0) AS total_cvr,
       IFNULL((SUM(ydn.cost) + SUM(yss.cost) + SUM(adw.cost)) / (SUM(ydn.call_cv) + SUM(yss.call_cv) + SUM(adw.call_cv) + SUM(ydn.web_cv) + SUM(yss.web_cv) + SUM(adw.web_cv)), 0) AS total_cpa
FROM `accounts`
LEFT JOIN
 (SELECT repo_yss_account_report_cost.account_id AS account_id,
         SUM(repo_yss_account_report_cost.clicks) AS clicks,
         SUM(repo_yss_account_report_cost.cost) AS cost,
         SUM(repo_yss_account_report_cost.impressions) AS impressions,
         ROUND(AVG(repo_yss_account_report_cost.ctr), 2) AS ctr,
         ROUND(AVG(repo_yss_account_report_cost.averageCpc), 2) AS averageCpc,
         ROUND(AVG(repo_yss_account_report_cost.averagePosition), 2) AS averagePosition,
         COUNT(`phone_time_use`.`id`) AS call_cv,
         (COUNT(`phone_time_use`.`id`) / SUM(repo_yss_account_report_cost.clicks)) * 100 AS call_cvr,
         SUM(repo_yss_account_report_cost.cost) / COUNT(`phone_time_use`.`id`) AS call_cpa,
         SUM(repo_yss_account_report_cost.conversions) AS web_cv,
         (SUM(repo_yss_account_report_cost.conversions) / SUM(repo_yss_account_report_cost.clicks)) * 100 AS web_cvr,
         SUM(repo_yss_account_report_cost.cost) / SUM(repo_yss_account_report_cost.conversions) AS web_cpa,
         SUM(repo_yss_account_report_cost.conversions) + COUNT(`phone_time_use`.`id`) AS total_cv,
         (SUM(repo_yss_account_report_cost.conversions) + COUNT(`phone_time_use`.`id`)) / SUM(repo_yss_account_report_cost.clicks) AS total_cvr,
         SUM(repo_yss_account_report_cost.cost) / (SUM(repo_yss_account_report_cost.conversions) + COUNT(`phone_time_use`.`id`)) AS total_cpa
  FROM `repo_yss_account_report_cost`
  LEFT JOIN `phone_time_use` ON (`repo_yss_account_report_cost`.`account_id` = 'phone_time_use.account_id'
                                 AND `repo_yss_account_report_cost`.`campaign_id` = 'phone_time_use.campaign_id'
                                 AND `repo_yss_account_report_cost`.`day` = STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d')
                                 AND `phone_time_use`.`source` = 'yss'
                                 AND `phone_time_use`.`traffic_type` = 'AD')
  WHERE (date(`day`) >= '2017-09-29'
         AND date(`day`) <= '2017-12-28')
  GROUP BY `account_id`) AS yss ON `accounts`.`account_id` = `yss`.`account_id`
LEFT JOIN
 (SELECT repo_ydn_reports.account_id AS account_id,
         SUM(repo_ydn_reports.clicks) AS clicks,
         SUM(repo_ydn_reports.cost) AS cost,
         SUM(repo_ydn_reports.impressions) AS impressions,
         ROUND(AVG(repo_ydn_reports.ctr), 2) AS ctr,
         ROUND(AVG(repo_ydn_reports.averageCpc), 2) AS averageCpc,
         ROUND(AVG(repo_ydn_reports.averagePosition), 2) AS averagePosition,
         COUNT(`phone_time_use`.`id`) AS call_cv,
         (COUNT(`phone_time_use`.`id`) / SUM(repo_ydn_reports.clicks)) * 100 AS call_cvr,
         SUM(repo_ydn_reports.cost) / COUNT(`phone_time_use`.`id`) AS call_cpa,
         SUM(repo_ydn_reports.conversions) AS web_cv,
         (SUM(repo_ydn_reports.conversions) / SUM(repo_ydn_reports.clicks)) * 100 AS web_cvr,
         SUM(repo_ydn_reports.cost) / SUM(repo_ydn_reports.conversions) AS web_cpa,
         SUM(repo_ydn_reports.conversions) + COUNT(`phone_time_use`.`id`) AS total_cv,
         (SUM(repo_ydn_reports.conversions) + COUNT(`phone_time_use`.`id`)) / SUM(repo_ydn_reports.clicks) AS total_cvr,
         SUM(repo_ydn_reports.cost) / (SUM(repo_ydn_reports.conversions) + COUNT(`phone_time_use`.`id`)) AS total_cpa
  FROM `repo_ydn_reports`
  LEFT JOIN `phone_time_use` ON (`repo_ydn_reports`.`account_id` = 'phone_time_use.account_id'
                                 AND `repo_ydn_reports`.`campaign_id` = 'phone_time_use.campaign_id'
                                 AND `repo_ydn_reports`.`day` = STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d')
                                 AND `phone_time_use`.`source` = 'ydn'
                                 AND `phone_time_use`.`traffic_type` = 'AD')
  WHERE (date(`day`) >= '2017-09-29'
         AND date(`day`) <= '2017-12-28')
  GROUP BY `account_id`) AS ydn ON `accounts`.`account_id` = `ydn`.`account_id`
LEFT JOIN
 (SELECT repo_adw_account_report_cost.account_id AS account_id,
         SUM(repo_adw_account_report_cost.clicks) AS clicks,
         ROUND(SUM(repo_adw_account_report_cost.cost), 2) AS cost,
         SUM(repo_adw_account_report_cost.impressions) AS impressions,
         ROUND(AVG(repo_adw_account_report_cost.ctr), 2) AS ctr,
         ROUND(AVG(repo_adw_account_report_cost.avgCPC), 2) AS averageCpc,
         ROUND(AVG(repo_adw_account_report_cost.avgPosition), 2) AS averagePosition,
         COUNT(`phone_time_use`.`id`) AS call_cv,
         (COUNT(`phone_time_use`.`id`) / SUM(repo_adw_account_report_cost.clicks)) * 100 AS call_cvr,
         SUM(repo_adw_account_report_cost.cost) / COUNT(`phone_time_use`.`id`) AS call_cpa,
         SUM(repo_adw_account_report_cost.conversions) AS web_cv,
         (SUM(repo_adw_account_report_cost.conversions) / SUM(repo_adw_account_report_cost.clicks)) * 100 AS web_cvr,
         SUM(repo_adw_account_report_cost.cost) / SUM(repo_adw_account_report_cost.conversions) AS web_cpa,
         SUM(repo_adw_account_report_cost.conversions) + COUNT(`phone_time_use`.`id`) AS total_cv,
         (SUM(repo_adw_account_report_cost.conversions) + COUNT(`phone_time_use`.`id`)) / SUM(repo_adw_account_report_cost.clicks) AS total_cvr,
         SUM(repo_adw_account_report_cost.cost) / (SUM(repo_adw_account_report_cost.conversions) + COUNT(`phone_time_use`.`id`)) AS total_cpa
  FROM `repo_adw_account_report_cost`
  LEFT JOIN `phone_time_use` ON (`repo_adw_account_report_cost`.`account_id` = 'phone_time_use.account_id'
                                 AND `repo_adw_account_report_cost`.`campaign_id` = 'phone_time_use.campaign_id'
                                 AND `repo_adw_account_report_cost`.`day` = STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d')
                                 AND `phone_time_use`.`source` = 'adw'
                                 AND `phone_time_use`.`traffic_type` = 'AD')
  WHERE (date(`day`) >= '2017-09-29'
         AND date(`day`) <= '2017-12-28')
   AND (`repo_adw_account_report_cost`.`network` = 'SEARCH'
        OR `repo_adw_account_report_cost`.`network` = 'CONTENT')
  GROUP BY `account_id`) AS adw ON `accounts`.`account_id` = `adw`.`account_id`
LEFT JOIN accounts AS parentAccounts ON `accounts`.`agent_id` = `parentAccounts`.`account_id`