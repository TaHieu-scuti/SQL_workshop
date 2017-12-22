SELECT
  SUM(`adw`.`cost`) + SUM(`ydn`.`cost`) + SUM(`yss`.`cost`) AS Cost,
  SUM(`adw`.`impressions`) + SUM(`ydn`.`impressions`) + SUM(`yss`.`impressions`) AS Impressions,
  SUM(`adw`.`clicks`) + SUM(`ydn`.`clicks`) + SUM(`yss`.`clicks`) AS Clicks,
  AVG(`adw`.`ctr`) + AVG(`ydn`.`ctr`) + AVG(`yss`.`ctr`) AS CTR,
  (AVG(`adw`.`avgCPC`) + AVG(`ydn`.`avgCPC`) + AVG(`yss`.`avgCPC`)) / 3 AS CPC,
  SUM(`adw`.`webcv`) AS "AdWords Web CV",
  (SUM(`adw`.`webcv`) / SUM(`adw`.`clicks`)) * 100 AS "AdWords Web CVR",
  SUM(`adw`.`cost`) / SUM(`adw`.`clicks`) AS "AdWords Web CPA",
  SUM(`yss`.`webcv`) AS "YSS Web CV",
  (SUM(`yss`.`webcv`) / SUM(`yss`.`clicks`)) * 100 AS "YSS Web CVR",
  SUM(`yss`.`cost`) / SUM(`yss`.`clicks`) AS "YSS Web CPA",
  SUM(`ydn`.`webcv`) AS "YDN Web CV",
  (SUM(`ydn`.`webcv`) / SUM(`ydn`.`clicks`)) * 100 AS "YDN Web CVR",
  SUM(`ydn`.`cost`) / SUM(`ydn`.`clicks`) AS "YDN Web CPA",
  SUM(`adw`.`webcv`) + `ydn`.`webcv` + `yss`.`webcv` AS "Web CV",
  ((SUM(`adw`.`webcv`) + SUM(`ydn`.`webcv`) + SUM(`yss`.`webcv`)) / (SUM(`adw`.`clicks`) + SUM(`ydn`.`clicks`) + SUM(`yss`.`clicks`))) * 100 AS "Web CVR",
  (SUM(`adw`.`cost`) + SUM(`ydn`.`cost`) + SUM(`yss`.`cost`)) / (SUM(`adw`.`clicks`) + SUM(`ydn`.`clicks`) + SUM(`yss`.`clicks`)) AS "Web CPA",
  SUM(`adw`.`callCv`) AS "AdWords Call CV",
  SUM(`adw`.`callCv`) / SUM(`adw`.`clicks`) AS "AdWords Call CVR",
  SUM(`adw`.`cost`) / SUM(`adw`.`callCv`) AS "AdWords Call CPA",
  SUM(`yss`.`callCv`) AS "YSS Call CV",
  SUM(`yss`.`callCv`) / SUM(`yss`.`clicks`) AS "YSS Call CVR",
  SUM(`yss`.`cost`) / SUM(`yss`.`callCv`) AS "YSS Call CPA",
  SUM(`ydn`.`callCv`) AS "YDN Call CV",
  SUM(`ydn`.`callCv`) / SUM(`ydn`.`clicks`) AS "YDN Call CVR",
  SUM(`ydn`.`cost`) / SUM(`ydn`.`callCv`) AS "YDN Call CPA",
  SUM(`adw`.`callCv`) + SUM(`ydn`.`callCv`) + SUM(`yss`.`callCv`) AS "Call CV",
  (SUM(`adw`.`callCv`) + SUM(`ydn`.`callCv`) + SUM(`yss`.`callCv`)) / (SUM(`adw`.`clicks`) + SUM(`ydn`.`clicks`) + SUM(`yss`.`clicks`)) AS "Call CVR",
  (SUM(`adw`.`cost`) + SUM(`ydn`.`cost`) + SUM(`yss`.`cost`)) / (SUM(`adw`.`callCv`) + SUM(`ydn`.`callCv`) + SUM(`yss`.`callCv`)) AS "Call CPA",
  SUM(`adw`.`webcv`) + SUM(`adw`.`callCv`) + SUM(`ydn`.`webcv`) + SUM(`ydn`.`callCv`) + SUM(`yss`.`webcv`) + SUM(`yss`.`callCv`) AS "Total CV",
  (SUM(`adw`.`webcv`) + SUM(`adw`.`callCv`) + SUM(`ydn`.`webcv`) + SUM(`ydn`.`callCv`) + SUM(`yss`.`webcv`) + SUM(`yss`.`callCv`)) / (SUM(`adw`.`clicks`) + SUM(`ydn`.`clicks`) + SUM(`yss`.`clicks`)) AS "Total CVR",
  (SUM(`adw`.`cost`) + SUM(`ydn`.`cost`) + SUM(`yss`.`cost`)) / (SUM(`adw`.`webcv`) + SUM(`ydn`.`webcv`) + SUM(`ydn`.`callCv`) + SUM(`adw`.`callCv`) + SUM(`yss`.`callCv`)) AS "Total CPA"
FROM
  `accounts`
  LEFT JOIN
  (
    SELECT
      `repo_adw_account_report_cost`.`account_id`,
      SUM(`repo_adw_account_report_cost`.`impressions`) AS impressions,
      SUM(`repo_adw_account_report_cost`.`clicks`) AS clicks,
      SUM(`repo_adw_account_report_cost`.`cost`) AS cost,
      AVG(`repo_adw_account_report_cost`.`ctr`) AS ctr,
      AVG(`repo_adw_account_report_cost`.`avgCPC`) AS avgCPC,
      COUNT(`phone_time_use`.`id`) AS callCv,
      SUM(`repo_adw_account_report_cost`.`conversions`) AS webcv,
      SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`) AS cv,
      ((SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) / SUM(`repo_adw_account_report_cost`.`clicks`)) * 100 AS cvr,
      SUM(`repo_adw_account_report_cost`.`cost`) / (SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) AS cpa,
      AVG(`repo_adw_account_report_cost`.`avgPosition`) AS avgPosition
    FROM
      `repo_adw_account_report_cost`
      LEFT JOIN `phone_time_use`
        ON (
          `phone_time_use`.`account_id` = `repo_adw_account_report_cost`.`account_id`
        AND
          `phone_time_use`.`campaign_id` = `repo_adw_account_report_cost`.`campaign_id`
        AND
          STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_adw_account_report_cost`.`day`
        AND
          `phone_time_use`.`source` = 'adw'
        AND
          `phone_time_use`.`traffic_type` = 'AD'
        )
    WHERE
      `repo_adw_account_report_cost`.`day` >= '2017-01-01'
    AND
      `repo_adw_account_report_cost`.`day` <= '2017-12-01'
    AND
      (
        `repo_adw_account_report_cost`.`network` = 'SEARCH'
      OR
        `repo_adw_account_report_cost`.`network` = 'CONTENT'
      )
    GROUP BY
      `repo_adw_account_report_cost`.`account_id`
  ) AS adw
    ON
      `accounts`.`account_id` = `adw`.`account_id`
  LEFT JOIN
  (
    SELECT
      `repo_ydn_reports`.`account_id`,
      SUM(`repo_ydn_reports`.`impressions`) AS impressions,
      SUM(`repo_ydn_reports`.`clicks`) AS clicks,
      SUM(`repo_ydn_reports`.`cost`) AS cost,
      AVG(`repo_ydn_reports`.`ctr`) AS ctr,
      AVG(`repo_ydn_reports`.`averageCpc`) AS avgCPC,
      COUNT(`phone_time_use`.`id`) AS callCv,
      SUM(`repo_ydn_reports`.`conversions`) AS webcv,
      SUM(`repo_ydn_reports`.`conversions`) + COUNT(`phone_time_use`.`id`) AS cv,
      ((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`phone_time_use`.`id`)) / SUM(`repo_ydn_reports`.`clicks`)) * 100 AS cvr,
      SUM(`repo_ydn_reports`.`cost`) / (SUM(`repo_ydn_reports`.`conversions`) + COUNT(`phone_time_use`.`id`)) AS cpa,
      AVG(`repo_ydn_reports`.`averagePosition`) AS avgPosition
    FROM
      `repo_ydn_reports`
      LEFT JOIN `phone_time_use`
        ON (
          `phone_time_use`.`account_id` = `repo_ydn_reports`.`account_id`
        AND
          `phone_time_use`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
        AND
          STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_ydn_reports`.`day`
        AND
          `phone_time_use`.`source` = 'ydn'
        AND
          `phone_time_use`.`traffic_type` = 'AD'
        )
    WHERE
      `repo_ydn_reports`.`day` >= '2017-01-01'
    AND
      `repo_ydn_reports`.`day` <= '2017-12-01'
    GROUP BY
      `repo_ydn_reports`.`account_id`
  ) AS ydn
    ON
      `accounts`.`account_id` = `ydn`.`account_id`
  LEFT JOIN
  (
    SELECT
      `repo_yss_account_report_cost`.`account_id`,
      SUM(`repo_yss_account_report_cost`.`impressions`) AS impressions,
      SUM(`repo_yss_account_report_cost`.`clicks`) AS clicks,
      SUM(`repo_yss_account_report_cost`.`cost`) AS cost,
      AVG(`repo_yss_account_report_cost`.`ctr`) AS ctr,
      AVG(`repo_yss_account_report_cost`.`averageCpc`) AS avgCPC,
      COUNT(`phone_time_use`.`id`) AS callCv,
      SUM(`repo_yss_account_report_cost`.`conversions`) AS webcv,
      SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`) AS cv,
      ((SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) / SUM(`repo_yss_account_report_cost`.`clicks`)) * 100 AS cvr,
      SUM(`repo_yss_account_report_cost`.`cost`) / (SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) AS cpa,
      AVG(`repo_yss_account_report_cost`.`averagePosition`) AS avgPosition
    FROM
      `repo_yss_account_report_cost`
      LEFT JOIN `phone_time_use`
        ON (
          `phone_time_use`.`account_id` = `repo_yss_account_report_cost`.`account_id`
        AND
          `phone_time_use`.`campaign_id` = `repo_yss_account_report_cost`.`campaign_id`
        AND
          STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_yss_account_report_cost`.`day`
        AND
          `phone_time_use`.`source` = 'yss'
        AND
          `phone_time_use`.`traffic_type` = 'AD'
        )
    WHERE
      `repo_yss_account_report_cost`.`day` >= '2017-01-01'
    AND
      `repo_yss_account_report_cost`.`day` <= '2017-12-01'
    GROUP BY
      `repo_yss_account_report_cost`.`account_id`
  ) AS yss
    ON
      `accounts`.`account_id` = `yss`.`account_id`
WHERE
  `accounts`.`level` = 3
AND
  `accounts`.`agent_id` = ''
