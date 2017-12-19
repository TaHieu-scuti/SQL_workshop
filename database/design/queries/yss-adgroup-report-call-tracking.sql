/* CONFIRMED TO BE WORKING CORRECTLY */
SELECT
  `repo_yss_adgroup_report_cost`.`account_id`,
  `repo_yss_adgroup_report_cost`.`accountid`,
  `repo_yss_adgroup_report_cost`.`campaignID`,
  `repo_yss_adgroup_report_cost`.`adgroupID`,
  SUM(`repo_yss_adgroup_report_cost`.`impressions`) AS impressions,
  SUM(`repo_yss_adgroup_report_cost`.`clicks`) AS clicks,
  SUM(`repo_yss_adgroup_report_cost`.`cost`) AS cost,
  AVG(`repo_yss_adgroup_report_cost`.`ctr`) AS ctr,
  AVG(`repo_yss_adgroup_report_cost`.`averageCpc`) AS avgCPC,
  COUNT(`phone_time_use`.`id`) AS call_tracking,
  SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`) AS cv,
  ((SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) / SUM(`repo_yss_adgroup_report_cost`.`clicks`)) * 100 AS cvr,
  SUM(`repo_yss_adgroup_report_cost`.`cost`) / (SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) AS cpa,
  AVG(`repo_yss_adgroup_report_cost`.`averagePosition`) AS avgPosition,
  SUM(`repo_yss_adgroup_report_cost`.`conversions`) AS web_cv,
  ((SUM(`repo_yss_adgroup_report_cost`.`conversions`) / SUM(`repo_yss_adgroup_report_cost`.`clicks`)) * 100) AS web_cvr,
  (SUM(`repo_yss_adgroup_report_cost`.`cost`) / SUM(`repo_yss_adgroup_report_cost`.`conversions`)) AS web_cpa,
FROM
  `repo_yss_adgroup_report_cost`
    LEFT JOIN (`campaigns`, `phone_time_use`)
    ON (
        `campaigns`.`account_id` = `repo_yss_adgroup_report_cost`.`account_id`
      AND
        `campaigns`.`campaign_id` = `repo_yss_adgroup_report_cost`.`campaign_id`
      AND
        (
          (
            `campaigns`.`camp_custom1` = 'adgroupid'
          AND
            `phone_time_use`.`custom1` = `repo_yss_adgroup_report_cost`.`adgroupID`
          )
        OR
          (
            `campaigns`.`camp_custom2` = 'adgroupid'
          AND
            `phone_time_use`.`custom2` = `repo_yss_adgroup_report_cost`.`adgroupID`
          )
        OR
          (
            `campaigns`.`camp_custom3` = 'adgroupid'
          AND
            `phone_time_use`.`custom3` = `repo_yss_adgroup_report_cost`.`adgroupID`
          )
        OR
          (
            `campaigns`.`camp_custom4` = 'adgroupid'
          AND
            `phone_time_use`.`custom4` = `repo_yss_adgroup_report_cost`.`adgroupID`
          )
        OR
          (
            `campaigns`.`camp_custom5` = 'adgroupid'
          AND
            `phone_time_use`.`custom5` = `repo_yss_adgroup_report_cost`.`adgroupID`
          )
        OR
          (
            `campaigns`.`camp_custom6` = 'adgroupid'
          AND
            `phone_time_use`.`custom6` = `repo_yss_adgroup_report_cost`.`adgroupID`
          )
        OR
          (
            `campaigns`.`camp_custom7` = 'adgroupid'
          AND
            `phone_time_use`.`custom7` = `repo_yss_adgroup_report_cost`.`adgroupID`
          )
        OR
          (
            `campaigns`.`camp_custom8` = 'adgroupid'
          AND
            `phone_time_use`.`custom8` = `repo_yss_adgroup_report_cost`.`adgroupID`
          )
        OR
          (
            `campaigns`.`camp_custom9` = 'adgroupid'
          AND
            `phone_time_use`.`custom9` = `repo_yss_adgroup_report_cost`.`adgroupID`
          )
        OR
          (
            `campaigns`.`camp_custom10` = 'adgroupid'
          AND
            `phone_time_use`.`custom10` = `repo_yss_adgroup_report_cost`.`adgroupID`
          )
        )
      AND
        `phone_time_use`.`account_id` = `repo_yss_adgroup_report_cost`.`account_id`
      AND
        `phone_time_use`.`campaign_id` = `repo_yss_adgroup_report_cost`.`campaign_id`
      AND
        `phone_time_use`.`utm_campaign` = `repo_yss_adgroup_report_cost`.`campaignID`
      AND
        STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_yss_adgroup_report_cost`.`day`
      AND
        `phone_time_use`.`source` = 'yss'
      AND
        `phone_time_use`.`traffic_type` = 'AD'
    )
WHERE
  `repo_yss_adgroup_report_cost`.`account_id` = 1
AND
  `repo_yss_adgroup_report_cost`.`accountid` = 11111
AND
  `repo_yss_adgroup_report_cost`.`campaignID` = 111111
AND
  `repo_yss_adgroup_report_cost`.`day` >= '2017-01-01'
AND
  `repo_yss_adgroup_report_cost`.`day` <= '2017-12-01'
GROUP BY
  `repo_yss_adgroup_report_cost`.`account_id`,
  `repo_yss_adgroup_report_cost`.`accountid`,
  `repo_yss_adgroup_report_cost`.`campaignID`,
  `repo_yss_adgroup_report_cost`.`adgroupID`
