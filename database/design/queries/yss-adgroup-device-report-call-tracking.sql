SELECT
    'DESKTOP' as device,
    SUM(`repo_yss_adgroup_report_cost`.`impressions`) AS impressions,
    SUM(`repo_yss_adgroup_report_cost`.`clicks`) AS clicks,
    SUM(`repo_yss_adgroup_report_cost`.`cost`) AS cost,
    AVG(`repo_yss_adgroup_report_cost`.`ctr`) AS ctr,
    AVG(`repo_yss_adgroup_report_cost`.`averageCPC`) AS avgCPC,
    COUNT(`repo_phone_time_use`.`id`) AS call_tracking,
    SUM(`repo_yss_adgroup_report_cost`.`conversions`) AS webcv,
    SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`) AS cv,
    ((SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_yss_adgroup_report_cost`.`clicks`)) * 100 AS cvr,
    SUM(`repo_yss_adgroup_report_cost`.`cost`) / (SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) AS cpa,
    AVG(`repo_yss_adgroup_report_cost`.`averagePosition`) AS avgPosition
FROM
  `repo_yss_adgroup_report_cost`
  LEFT JOIN (`phone_time_use`, `campaigns`)
    ON (
      `campaigns`.`account_id` = `repo_yss_adgroup_report_cost`.`account_id`
    AND
      `campaigns`.`campaign_id` = `repo_yss_adgroup_report_cost`.`campaign_id`
    AND
      (
        (
          `campaigns`.`camp_custom1` = 'creative'
        AND
          `phone_time_use`.`custom1` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom2` = 'creative'
        AND
          `phone_time_use`.`custom2` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom3` = 'creative'
        AND
          `phone_time_use`.`custom3` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom4` = 'creative'
        AND
          `phone_time_use`.`custom4` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom5` = 'creative'
        AND
          `phone_time_use`.`custom5` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom6` = 'creative'
        AND
          `phone_time_use`.`custom6` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom7` = 'creative'
        AND
          `phone_time_use`.`custom7` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom8` = 'creative'
        AND
          `phone_time_use`.`custom8` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom9` = 'creative'
        AND
          `phone_time_use`.`custom9` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom10` = 'creative'
        AND
          `phone_time_use`.`custom10` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      )
    )
  JOIN `repo_phone_time_use`
      ON (
            `repo_phone_time_use`.`phone_time_use_id` = `phone_time_use`.`id`
          AND
            `repo_phone_time_use`.`mobile` = 'No'
          AND
            (`repo_phone_time_use`.`platform` NOT LIKE 'Windows Phone%'
            AND
                (
                `repo_phone_time_use`.`platform` LIKE 'Windows%'
                OR
                `repo_phone_time_use`.`platform` LIKE 'Linux%'
                OR
                `repo_phone_time_use`.`platform` LIKE 'Mac OS%'
                OR
                `repo_phone_time_use`.`platform` LIKE 'FreeBSD%'
                OR
                `repo_phone_time_use`.`platform` LIKE 'Unknown Windows OS%'
                OR
                `repo_phone_time_use`.`platform` LIKE 'NetBSD%'
                OR
                `repo_phone_time_use`.`platform` LIKE 'FreeBSD%'
                OR
                `repo_phone_time_use`.`platform` LIKE 'iOS%'
                OR
                `repo_phone_time_use`.`platform` LIKE 'Android%'
                OR
                `repo_phone_time_use`.`platform` LIKE 'Blackberry%'
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
    `repo_yss_adgroup_report_cost`.`device` = 'DESKTOP'
AND
  `repo_yss_adgroup_report_cost`.`account_id` = 1
AND
  `repo_yss_adgroup_report_cost`.`campaign_id` = 11
AND
  `repo_yss_adgroup_report_cost`.`accountid` = 11
AND
  `repo_yss_adgroup_report_cost`.`campaignID` = 11
AND
  `repo_yss_adgroup_report_cost`.`day` >= '2017-01-01'
AND
  `repo_yss_adgroup_report_cost`.`day` <= '2017-12-01'
GROUP BY device

UNION

SELECT
    'SMART PHONE' as device,
    SUM(`repo_yss_adgroup_report_cost`.`impressions`) AS impressions,
    SUM(`repo_yss_adgroup_report_cost`.`clicks`) AS clicks,
    SUM(`repo_yss_adgroup_report_cost`.`cost`) AS cost,
    AVG(`repo_yss_adgroup_report_cost`.`ctr`) AS ctr,
    AVG(`repo_yss_adgroup_report_cost`.`averageCPC`) AS avgCPC,
    COUNT(`repo_phone_time_use`.`id`) AS call_tracking,
    SUM(`repo_yss_adgroup_report_cost`.`conversions`) AS webcv,
    SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`) AS cv,
    ((SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_yss_adgroup_report_cost`.`clicks`)) * 100 AS cvr,
    SUM(`repo_yss_adgroup_report_cost`.`cost`) / (SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) AS cpa,
    AVG(`repo_yss_adgroup_report_cost`.`averagePosition`) AS avgPosition
FROM
  `repo_yss_adgroup_report_cost`
  LEFT JOIN (`phone_time_use`, `campaigns`)
    ON (
      `campaigns`.`account_id` = `repo_yss_adgroup_report_cost`.`account_id`
    AND
      `campaigns`.`campaign_id` = `repo_yss_adgroup_report_cost`.`campaign_id`
    AND
      (
        (
          `campaigns`.`camp_custom1` = 'creative'
        AND
          `phone_time_use`.`custom1` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom2` = 'creative'
        AND
          `phone_time_use`.`custom2` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom3` = 'creative'
        AND
          `phone_time_use`.`custom3` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom4` = 'creative'
        AND
          `phone_time_use`.`custom4` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom5` = 'creative'
        AND
          `phone_time_use`.`custom5` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom6` = 'creative'
        AND
          `phone_time_use`.`custom6` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom7` = 'creative'
        AND
          `phone_time_use`.`custom7` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom8` = 'creative'
        AND
          `phone_time_use`.`custom8` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom9` = 'creative'
        AND
          `phone_time_use`.`custom9` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom10` = 'creative'
        AND
          `phone_time_use`.`custom10` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      )
    )
  JOIN `repo_phone_time_use`
      ON (
            `repo_phone_time_use`.`phone_time_use_id` = `phone_time_use`.`id`
          AND
          `repo_phone_time_use`.`mobile` LIKE 'Yes%'
            AND
           (
                `repo_phone_time_use`.`platform` LIKE 'Windows Phone%'
            OR
                `repo_phone_time_use`.`platform` LIKE 'iOS%'
            OR
                `repo_phone_time_use`.`platform` LIKE 'Android%'
            OR
                `repo_phone_time_use`.`platform` LIKE 'Blackberry%'
            OR
                `repo_phone_time_use`.`platform` LIKE 'Symbian%'
            )
            AND
                `repo_phone_time_use`.`account_id` = `repo_yss_adgroup_report_cost`.`account_id`
            AND
                `repo_phone_time_use`.`campaign_id` = `repo_yss_adgroup_report_cost`.`campaign_id`
            AND
                `repo_phone_time_use`.`utm_campaign` = `repo_yss_adgroup_report_cost`.`campaignID`
            AND
                `repo_yss_adgroup_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
            AND
                `repo_phone_time_use`.`source` = 'yss'
            AND
                `repo_phone_time_use`.`traffic_type` = 'AD'
      )
WHERE
    `repo_yss_adgroup_report_cost`.`device` = 'SMART PHONE'
AND
  `repo_yss_adgroup_report_cost`.`account_id` = 1
AND
  `repo_yss_adgroup_report_cost`.`campaign_id` = 11
AND
  `repo_yss_adgroup_report_cost`.`accountid` = 11
AND
  `repo_yss_adgroup_report_cost`.`campaignID` = 11
AND
  `repo_yss_adgroup_report_cost`.`day` >= '2017-01-01'
AND
  `repo_yss_adgroup_report_cost`.`day` <= '2017-12-01'
GROUP BY device

UNION

SELECT
    'NONE' as device,
    SUM(`repo_yss_adgroup_report_cost`.`impressions`) AS impressions,
    SUM(`repo_yss_adgroup_report_cost`.`clicks`) AS clicks,
    SUM(`repo_yss_adgroup_report_cost`.`cost`) AS cost,
    AVG(`repo_yss_adgroup_report_cost`.`ctr`) AS ctr,
    AVG(`repo_yss_adgroup_report_cost`.`averageCPC`) AS avgCPC,
    COUNT(`repo_phone_time_use`.`id`) AS call_tracking,
    SUM(`repo_yss_adgroup_report_cost`.`conversions`) AS webcv,
    SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`) AS cv,
    ((SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_yss_adgroup_report_cost`.`clicks`)) * 100 AS cvr,
    SUM(`repo_yss_adgroup_report_cost`.`cost`) / (SUM(`repo_yss_adgroup_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) AS cpa,
    AVG(`repo_yss_adgroup_report_cost`.`averagePosition`) AS avgPosition
FROM
  `repo_yss_adgroup_report_cost`
  LEFT JOIN (`phone_time_use`, `campaigns`)
    ON (
      `campaigns`.`account_id` = `repo_yss_adgroup_report_cost`.`account_id`
    AND
      `campaigns`.`campaign_id` = `repo_yss_adgroup_report_cost`.`campaign_id`
    AND
      (
        (
          `campaigns`.`camp_custom1` = 'creative'
        AND
          `phone_time_use`.`custom1` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom2` = 'creative'
        AND
          `phone_time_use`.`custom2` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom3` = 'creative'
        AND
          `phone_time_use`.`custom3` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom4` = 'creative'
        AND
          `phone_time_use`.`custom4` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom5` = 'creative'
        AND
          `phone_time_use`.`custom5` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom6` = 'creative'
        AND
          `phone_time_use`.`custom6` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom7` = 'creative'
        AND
          `phone_time_use`.`custom7` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom8` = 'creative'
        AND
          `phone_time_use`.`custom8` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom9` = 'creative'
        AND
          `phone_time_use`.`custom9` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      OR
        (
          `campaigns`.`camp_custom10` = 'creative'
        AND
          `phone_time_use`.`custom10` = `repo_yss_adgroup_report_cost`.`adGroupId`
        )
      )
    )
  JOIN `repo_phone_time_use`
      ON (
                `repo_phone_time_use`.`platform` LIKE 'Unknown Platform%'
            AND
                `repo_phone_time_use`.`account_id` = `repo_yss_adgroup_report_cost`.`account_id`
            AND
                `repo_phone_time_use`.`campaign_id` = `repo_yss_adgroup_report_cost`.`campaign_id`
            AND
                `repo_phone_time_use`.`utm_campaign` = `repo_yss_adgroup_report_cost`.`campaignID`
            AND
                `repo_yss_adgroup_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
            AND
                `repo_phone_time_use`.`source` = 'yss'
            AND
                `repo_phone_time_use`.`traffic_type` = 'AD'
      )
WHERE
    `repo_yss_adgroup_report_cost`.`device` = 'NONE'
AND
  `repo_yss_adgroup_report_cost`.`account_id` = 1
AND
  `repo_yss_adgroup_report_cost`.`campaign_id` = 11
AND
  `repo_yss_adgroup_report_cost`.`accountid` = 11
AND
  `repo_yss_adgroup_report_cost`.`campaignID` = 11
AND
  `repo_yss_adgroup_report_cost`.`day` >= '2017-01-01'
AND
  `repo_yss_adgroup_report_cost`.`day` <= '2017-12-01'
GROUP BY device