/* CONFIRMED TO BE WORKING */
SELECT
  `repo_ydn_reports`.`account_id`,
  `repo_ydn_reports`.`accountId`,
  DAYNAME(`repo_ydn_reports`.`day`) AS dayOfWeek,
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
      `phone_time_use`.`source` = 'ydn'
    AND
      `phone_time_use`.`traffic_type` = 'AD'
    AND
      DAYNAME(`phone_time_use`.`time_of_call`) = DAYNAME(`repo_ydn_reports`.`day`)
  )
WHERE
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`accountId` = 11
AND
  `repo_ydn_reports`.`day` >= '2017-01-01'
AND
  `repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY
  `repo_ydn_reports`.`account_id`,
  `repo_ydn_reports`.`accountId`,
  DAYNAME(`repo_ydn_reports`.`day`)
