/* Get all agency clients */
SELECT
  `accounts`.`account_id`,
  `accounts`.`accountName`,
  `adw`.`cost` + `ydn`.`cost` + `yss`.`cost` AS Cost,
  `adw`.`impressions` + `ydn`.`impressions` + `yss`.`impressions` AS Impressions,
  `adw`.`clicks` + `ydn`.`clicks` + `yss`.`clicks` AS Clicks,
  `adw`.`ctr` + `ydn`.`ctr` + `yss`.`ctr` AS CTR,
  (`adw`.`avgCPC` + `ydn`.`avgCPC` + `yss`.`avgCPC`) / 3 AS CPC,
  `adw`.`webcv` AS "AdWords Web CV",
  (`adw`.`webcv` / `adw`.`clicks`) * 100 AS "AdWords Web CVR",
  `adw`.`cost` / `adw`.`clicks` AS "AdWords Web CPA",
  `yss`.`webcv` AS "YSS Web CV",
  (`yss`.`webcv` / `yss`.`clicks`) * 100 AS "YSS Web CVR",
  `yss`.`cost` / `yss`.`clicks` AS "YSS Web CPA",
  `ydn`.`webcv` AS "YDN Web CV",
  (`ydn`.`webcv` / `ydn`.`clicks`) * 100 AS "YDN Web CVR",
  `ydn`.`cost` / `ydn`.`clicks` AS "YDN Web CPA",
  `adw`.`webcv` + `ydn`.`webcv` + `yss`.`webcv` AS "Web CV",
  ((`adw`.`webcv` + `ydn`.`webcv` + `yss`.`webcv`) / (`adw`.`clicks` + `ydn`.`clicks` + `yss`.`clicks`)) * 100 AS "Web CVR",
  (`adw`.`cost` + `ydn`.`cost` + `yss`.`cost`) / (`adw`.`clicks` + `ydn`.`clicks` + `yss`.`clicks`) AS "Web CPA",
  `adw`.`callCv` AS "AdWords Call CV",
  `adw`.`callCv` / `adw`.`clicks` AS "AdWords Call CVR",
  `adw`.`cost` / `adw`.`callCv` AS "AdWords Call CPA",
  `yss`.`callCv` AS "YSS Call CV",
  `yss`.`callCv` / `yss`.`clicks` AS "YSS Call CVR",
  `yss`.`cost` / `yss`.`callCv` AS "YSS Call CPA",
  `ydn`.`callCv` AS "YDN Call CV",
  `ydn`.`callCv` / `ydn`.`clicks` AS "YDN Call CVR",
  `ydn`.`cost` / `ydn`.`callCv` AS "YDN Call CPA",
  `adw`.`callCv` + `ydn`.`callCv` + `yss`.`callCv` AS "Call CV",
  (`adw`.`callCv` + `ydn`.`callCv` + `yss`.`callCv`) / (`adw`.`clicks` + `ydn`.`clicks` + `yss`.`clicks`) AS "Call CVR",
  (`adw`.`cost` + `ydn`.`cost` + `yss`.`cost`) / (`adw`.`callCv` + `ydn`.`callCv` + `yss`.`callCv`) AS "Call CPA",
  `adw`.`webcv` + `adw`.`callCv` + `ydn`.`webcv` + `ydn`.`callCv` + `yss`.`webcv` + `yss`.`callCv` AS "Total CV",
  (`adw`.`webcv` + `adw`.`callCv` + `ydn`.`webcv` + `ydn`.`callCv` + `yss`.`webcv` + `yss`.`callCv`) / (`adw`.`clicks` + `ydn`.`clicks` + `yss`.`clicks`) AS "Total CVR",
  (`adw`.`cost` + `ydn`.`cost` + `yss`.`cost`) / (`adw`.`webcv` + `ydn`.`webcv` + `ydn`.`callCv` + `adw`.`callCv` + `yss`.`callCv`) AS "Total CPA"
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
  `accounts`.`agent_id` != ''
