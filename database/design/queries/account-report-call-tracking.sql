SELECT
  `repo_adw_account_report_cost`.`account_id`,
  `repo_adw_account_report_cost`.`customerID`,
  SUM(`repo_adw_account_report_cost`.`impressions`) AS impressions,
  SUM(`repo_adw_account_report_cost`.`clicks`) AS clicks,
  SUM(`repo_adw_account_report_cost`.`cost`) AS cost,
  AVG(`repo_adw_account_report_cost`.`ctr`) AS ctr,
  AVG(`repo_adw_account_report_cost`.`avgCPC`) AS avgCPC,
  COUNT(`phone_time_use`.`id`) AS call_tracking,
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
        `phone_time_use`.`time_of_call` >= '2017-01-01'
      AND
        `phone_time_use`.`time_of_call` <= '2017-12-01'
      AND
        `phone_time_use`.`source` = 'adw'
      AND
        `phone_time_use`.`traffic_type` = 'AD'
    )
WHERE
  `repo_adw_account_report_cost`.`account_id` = 1
AND
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
  `repo_adw_account_report_cost`.`account_id`,
  `repo_adw_account_report_cost`.`customerID`

UNION

/* CONFIRMED TO BE WORKING START: */
SELECT
  `repo_ydn_reports`.`account_id`,
  `repo_ydn_reports`.`accountId`,
  SUM(`repo_ydn_reports`.`impressions`) AS impressions,
  SUM(`repo_ydn_reports`.`clicks`) AS clicks,
  SUM(`repo_ydn_reports`.`cost`) AS cost,
  AVG(`repo_ydn_reports`.`ctr`) AS ctr,
  AVG(`repo_ydn_reports`.`averageCpc`) AS avgCPC,
  COUNT(`phone_time_use`.`id`) AS call_tracking,
  SUM(`repo_ydn_reports`.`conversions`) AS webcv,
  SUM(`repo_ydn_reports`.`conversions`) + COUNT(`phone_time_use`.`id`) AS cv,
  ((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`phone_time_use`.`id`)) / SUM(`repo_ydn_reports`.`clicks`)) * 100 AS cvr,
  SUM(`repo_ydn_reports`.`cost`) / (SUM(`repo_ydn_reports`.`conversions`) + COUNT(`phone_time_use`.`id`)) AS cpa,
  AVG(`repo_ydn_reports`.`averagePosition`) AS avgPosition
FROM
  `repo_ydn_reports`
  LEFT JOIN (`phone_time_use`, `campaigns`)
  ON (
      `campaigns`.`account_id` = `repo_ydn_reports`.`account_id`
    AND
      `campaigns`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
    AND
      (
        (
          `campaigns`.`camp_custom1` = 'creative'
        AND
          `phone_time_use`.`custom1` = `repo_ydn_reports`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom2` = 'creative'
        AND
          `phone_time_use`.`custom2` = `repo_ydn_reports`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom3` = 'creative'
        AND
          `phone_time_use`.`custom3` = `repo_ydn_reports`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom4` = 'creative'
        AND
          `phone_time_use`.`custom4` = `repo_ydn_reports`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom5` = 'creative'
        AND
          `phone_time_use`.`custom5` = `repo_ydn_reports`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom6` = 'creative'
        AND
          `phone_time_use`.`custom6` = `repo_ydn_reports`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom7` = 'creative'
        AND
          `phone_time_use`.`custom7` = `repo_ydn_reports`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom8` = 'creative'
        AND
          `phone_time_use`.`custom8` = `repo_ydn_reports`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom9` = 'creative'
        AND
          `phone_time_use`.`custom9` = `repo_ydn_reports`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom10` = 'creative'
        AND
          `phone_time_use`.`custom10` = `repo_ydn_reports`.`adID`
        )
      )
    AND
      `phone_time_use`.`account_id` = `repo_ydn_reports`.`account_id`
    AND
      `phone_time_use`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
    AND
      `phone_time_use`.`utm_campaign` = `repo_ydn_reports`.`campaignID`
    AND
      STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_ydn_reports`.`day`
    AND
      `phone_time_use`.`time_of_call` >= '2017-01-01'
    AND
      `phone_time_use`.`time_of_call` <= '2017-12-01'
    AND
      `phone_time_use`.`source` = 'ydn'
    AND
      `phone_time_use`.`traffic_type` = 'AD'
  )
WHERE
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`day` >= '2017-01-01'
AND
  `repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY
  `repo_ydn_reports`.`account_id`,
  `repo_ydn_reports`.`accountId`
/* CONFIRMED TO BE WORKING END */

UNION

SELECT
  `repo_yss_account_report_cost`.`account_id`,
  `repo_yss_account_report_cost`.`accountid`,
  SUM(`repo_yss_account_report_cost`.`impressions`) AS impressions,
  SUM(`repo_yss_account_report_cost`.`clicks`) AS clicks,
  SUM(`repo_yss_account_report_cost`.`cost`) AS cost,
  AVG(`repo_yss_account_report_cost`.`ctr`) AS ctr,
  AVG(`repo_yss_account_report_cost`.`averageCpc`) AS avgCPC,
  COUNT(`phone_time_use`.`id`) AS call_tracking,
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
        `phone_time_use`.`time_of_call` >= '2017-01-01'
      AND
        `phone_time_use`.`time_of_call` <= '2017-12-01'
      AND
        `phone_time_use`.`source` = 'yss'
      AND
        `phone_time_use`.`traffic_type` = 'AD'
    )
WHERE
  `repo_yss_account_report_cost`.`account_id` = 1
AND
  `repo_yss_account_report_cost`.`day` >= '2017-01-01'
AND
  `repo_yss_account_report_cost`.`day` <= '2017-12-01'
GROUP BY
  `repo_yss_account_report_cost`.`account_id`,
  `repo_yss_account_report_cost`.`accountid`
