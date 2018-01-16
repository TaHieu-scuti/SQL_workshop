SELECT
   'PC' as device,
   IFNULL(SUM(`repo_ydn_reports`.`impressions`), 0) AS impressions,
   IFNULL(SUM(`repo_ydn_reports`.`clicks`), 0) AS clicks,
   IFNULL(SUM(`repo_ydn_reports`.`cost`), 0) AS cost,
   IFNULL(AVG(`repo_ydn_reports`.`ctr`), 0) AS ctr,
   IFNULL(AVG(`repo_ydn_reports`.`averageCpc`), 0) AS avgCPC,
   IFNULL(COUNT(`repo_phone_time_use`.`id`), 0) AS call_tracking,
   IFNULL(SUM(`repo_ydn_reports`.`conversions`), 0) AS webcv,
   IFNULL((SUM(`repo_ydn_reports`.`conversions`) / SUM(`repo_ydn_reports`.`clicks`) * 100), 0) AS webcvr,
   IFNULL((SUM(`repo_ydn_reports`.`cost`) / SUM(`repo_ydn_reports`.`conversions`)), 0) AS webcpa,
   IFNULL((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
   IFNULL(((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_ydn_reports`.`clicks`) * 100), 0) AS cvr,
   IFNULL((SUM(`repo_ydn_reports`.`cost`) / (SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
   IFNULL(AVG(`repo_ydn_reports`.`averagePosition`), 0) AS avgPosition
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
  )
  JOIN (`repo_phone_time_use`)
  ON (
      `repo_phone_time_use`.`mobile` = 'No'
      AND
      (
        (
        `repo_phone_time_use`.`platform` NOT LIKE 'Windows Phone%'
        AND
        `repo_phone_time_use`.`platform` LIKE 'Windows%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'Linux%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'Mac OS%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'FreeBSD%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'Unknown Windows OS%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'NetBSD%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'FreeBSD%'
        )
      )
    AND
      `repo_ydn_reports`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`account_id` = `repo_ydn_reports`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
    AND
      `repo_phone_time_use`.`utm_campaign` = `repo_ydn_reports`.`campaignID`
    AND
      `repo_phone_time_use`.`source` = 'ydn'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
  )
WHERE
  `repo_ydn_reports`.`device` = 'PC'
AND
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`accountId` = 11
AND
  `repo_ydn_reports`.`campaignID` = 11
AND
  `repo_ydn_reports`.`day` >= '2017-01-01'
AND
  `repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY device

UNION 

SELECT
   'Tablet' as device,
   IFNULL(SUM(`repo_ydn_reports`.`impressions`), 0) AS impressions,
   IFNULL(SUM(`repo_ydn_reports`.`clicks`), 0) AS clicks,
   IFNULL(SUM(`repo_ydn_reports`.`cost`), 0) AS cost,
   IFNULL(AVG(`repo_ydn_reports`.`ctr`), 0) AS ctr,
   IFNULL(AVG(`repo_ydn_reports`.`averageCpc`), 0) AS avgCPC,
   IFNULL(COUNT(`repo_phone_time_use`.`id`), 0) AS call_tracking,
   IFNULL(SUM(`repo_ydn_reports`.`conversions`), 0) AS webcv,
   IFNULL((SUM(`repo_ydn_reports`.`conversions`) / SUM(`repo_ydn_reports`.`clicks`) * 100), 0) AS webcvr,
   IFNULL((SUM(`repo_ydn_reports`.`cost`) / SUM(`repo_ydn_reports`.`conversions`)), 0) AS webcpa,
   IFNULL((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
   IFNULL(((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_ydn_reports`.`clicks`) * 100), 0) AS cvr,
   IFNULL((SUM(`repo_ydn_reports`.`cost`) / (SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
   IFNULL(AVG(`repo_ydn_reports`.`averagePosition`), 0) AS avgPosition
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
  )
  JOIN (`repo_phone_time_use`)
    ON (
      `repo_phone_time_use`.`mobile` = 'No'
      AND
      (
        (
        `repo_phone_time_use`.`platform` LIKE 'iOS%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'Android%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'Blackberry%'
        )
      )
    AND
      `repo_ydn_reports`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`account_id` = `repo_ydn_reports`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
    AND
      `repo_phone_time_use`.`utm_campaign` = `repo_ydn_reports`.`campaignID`
    AND
      `repo_phone_time_use`.`source` = 'ydn'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
  )
WHERE
  `repo_ydn_reports`.`device` = 'Tablet'
AND
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`accountId` = 11
AND
  `repo_ydn_reports`.`campaignID` = 11
AND
  `repo_ydn_reports`.`day` >= '2017-01-01'
AND
  `repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY device

UNION

SELECT
   'SmartPhone' as device,
   IFNULL(SUM(`repo_ydn_reports`.`impressions`), 0) AS impressions,
   IFNULL(SUM(`repo_ydn_reports`.`clicks`), 0) AS clicks,
   IFNULL(SUM(`repo_ydn_reports`.`cost`), 0) AS cost,
   IFNULL(AVG(`repo_ydn_reports`.`ctr`), 0) AS ctr,
   IFNULL(AVG(`repo_ydn_reports`.`averageCpc`), 0) AS avgCPC,
   IFNULL(COUNT(`repo_phone_time_use`.`id`), 0) AS call_tracking,
   IFNULL(SUM(`repo_ydn_reports`.`conversions`), 0) AS webcv,
   IFNULL((SUM(`repo_ydn_reports`.`conversions`) / SUM(`repo_ydn_reports`.`clicks`) * 100), 0) AS webcvr,
   IFNULL((SUM(`repo_ydn_reports`.`cost`) / SUM(`repo_ydn_reports`.`conversions`)), 0) AS webcpa,
   IFNULL((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
   IFNULL(((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_ydn_reports`.`clicks`) * 100), 0) AS cvr,
   IFNULL((SUM(`repo_ydn_reports`.`cost`) / (SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
   IFNULL(AVG(`repo_ydn_reports`.`averagePosition`), 0) AS avgPosition
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
  )
  JOIN (`repo_phone_time_use`)
    ON (
      `repo_phone_time_use`.`mobile` LIKE 'Yes%'
      AND
      (
        (
        `repo_phone_time_use`.`platform` LIKE 'iOS%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'Android%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'Blackberry%'
        )
        OR
        (
        `repo_phone_time_use`.`platform` LIKE 'Symbian%'
        )
      )
    AND
      `repo_ydn_reports`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`account_id` = `repo_ydn_reports`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
    AND
      `repo_phone_time_use`.`utm_campaign` = `repo_ydn_reports`.`campaignID`
    AND
      `repo_phone_time_use`.`source` = 'ydn'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
  )
WHERE
  `repo_ydn_reports`.`device` = 'SmartPhone'
AND
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`accountId` = 11
AND
  `repo_ydn_reports`.`campaignID` = 11
AND
  `repo_ydn_reports`.`day` >= '2017-01-01'
AND
  `repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY device

UNION

SELECT
   'Other' as device,
   IFNULL(SUM(`repo_ydn_reports`.`impressions`), 0) AS impressions,
   IFNULL(SUM(`repo_ydn_reports`.`clicks`), 0) AS clicks,
   IFNULL(SUM(`repo_ydn_reports`.`cost`), 0) AS cost,
   IFNULL(AVG(`repo_ydn_reports`.`ctr`), 0) AS ctr,
   IFNULL(AVG(`repo_ydn_reports`.`averageCpc`), 0) AS avgCPC,
   IFNULL(COUNT(`repo_phone_time_use`.`id`), 0) AS call_tracking,
   IFNULL(SUM(`repo_ydn_reports`.`conversions`), 0) AS webcv,
   IFNULL((SUM(`repo_ydn_reports`.`conversions`) / SUM(`repo_ydn_reports`.`clicks`) * 100), 0) AS webcvr,
   IFNULL((SUM(`repo_ydn_reports`.`cost`) / SUM(`repo_ydn_reports`.`conversions`)), 0) AS webcpa,
   IFNULL((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
   IFNULL(((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_ydn_reports`.`clicks`) * 100), 0) AS cvr,
   IFNULL((SUM(`repo_ydn_reports`.`cost`) / (SUM(`repo_ydn_reports`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
   IFNULL(AVG(`repo_ydn_reports`.`averagePosition`), 0) AS avgPosition
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
  )
  JOIN (`repo_phone_time_use`)
    ON (
    `repo_phone_time_use`.`platform` LIKE 'Unknown Platform%'
    AND
      `repo_ydn_reports`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`account_id` = `repo_ydn_reports`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
    AND
      `repo_phone_time_use`.`utm_campaign` = `repo_ydn_reports`.`campaignID`
    AND
      `repo_phone_time_use`.`source` = 'ydn'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
  )
WHERE
  `repo_ydn_reports`.`device` = 'Other'
AND
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`accountId` = 11
AND
  `repo_ydn_reports`.`campaignID` = 11
AND
  `repo_ydn_reports`.`day` >= '2017-01-01'
AND
  `repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY device