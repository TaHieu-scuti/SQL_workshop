SELECT
    'YSS DESKTOP' as devices,
    IFNULL(SUM(`repo_yss_account_report_cost`.`impressions`), 0) AS impressions,
    IFNULL(SUM(`repo_yss_account_report_cost`.`clicks`), 0) AS clicks,
    IFNULL(SUM(`repo_yss_account_report_cost`.`cost`), 0) AS cost,
    IFNULL(AVG(`repo_yss_account_report_cost`.`ctr`), 0) AS ctr,
    IFNULL(AVG(`repo_yss_account_report_cost`.`averageCpc`), 0) AS avgCPC,
    IFNULL(COUNT(`repo_yss_account_report_cost`.`id`), 0) AS call_tracking,
    IFNULL(SUM(`repo_yss_account_report_cost`.`conversions`), 0) AS webcv,
    IFNULL((SUM(`repo_yss_account_report_cost`.`conversions`) / SUM(`repo_yss_account_report_cost`.`clicks`) * 100), 0) AS webcvr,
    IFNULL((SUM(`repo_yss_account_report_cost`.`cost`) / SUM(`repo_yss_account_report_cost`.`conversions`)), 0) AS webcpa,
    IFNULL((SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
    IFNULL(((SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_yss_account_report_cost`.`clicks`) * 100), 0) AS cvr,
    IFNULL((SUM(`repo_yss_account_report_cost`.`cost`) / (SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
    IFNULL(AVG(`repo_yss_account_report_cost`.`averagePosition`), 0) AS avgPosition
FROM
    `repo_yss_account_report_cost`
        JOIN `repo_phone_time_use`
        ON (
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
                `repo_phone_time_use`.`account_id` = `repo_yss_account_report_cost`.`account_id`
            AND
                `repo_phone_time_use`.`campaign_id` = `repo_yss_account_report_cost`.`campaign_id`
            AND
                 `repo_yss_account_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
            AND
                `repo_phone_time_use`.`source` = 'yss'
            AND
                `repo_phone_time_use`.`traffic_type` = 'AD'
        )
WHERE
    `repo_yss_account_report_cost`.`device` = 'DESKTOP'
AND
    `repo_yss_account_report_cost`.`account_id` = 1
AND
    `repo_yss_account_report_cost`.`campaign_id` = 11
AND
    `repo_yss_account_report_cost`.`accountid` = 11
AND
    `repo_yss_account_report_cost`.`day` >= '2017-01-01'
AND
    `repo_yss_account_report_cost`.`day` <= '2017-12-01'
GROUP BY  devices

UNION

SELECT
    'YSS SMART PHONE' as devices,
    IFNULL(SUM(`repo_yss_account_report_cost`.`impressions`), 0) AS impressions,
    IFNULL(SUM(`repo_yss_account_report_cost`.`clicks`), 0) AS clicks,
    IFNULL(SUM(`repo_yss_account_report_cost`.`cost`), 0) AS cost,
    IFNULL(AVG(`repo_yss_account_report_cost`.`ctr`), 0) AS ctr,
    IFNULL(AVG(`repo_yss_account_report_cost`.`averageCpc`), 0) AS avgCPC,
    IFNULL(COUNT(`repo_yss_account_report_cost`.`id`), 0) AS call_tracking,
    IFNULL(SUM(`repo_yss_account_report_cost`.`conversions`), 0) AS webcv,
    IFNULL((SUM(`repo_yss_account_report_cost`.`conversions`) / SUM(`repo_yss_account_report_cost`.`clicks`) * 100), 0) AS webcvr,
    IFNULL((SUM(`repo_yss_account_report_cost`.`cost`) / SUM(`repo_yss_account_report_cost`.`conversions`)), 0) AS webcpa,
    IFNULL((SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
    IFNULL(((SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_yss_account_report_cost`.`clicks`) * 100), 0) AS cvr,
    IFNULL((SUM(`repo_yss_account_report_cost`.`cost`) / (SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
    IFNULL(AVG(`repo_yss_account_report_cost`.`averagePosition`), 0) AS avgPosition
FROM
    `repo_yss_account_report_cost`
        JOIN `repo_phone_time_use`
        ON (
          `repo_phone_time_use`.`mobile` LIKE 'Yes%'
            AND
                `repo_phone_time_use`.`platform` LIKE 'Windows Phone%'
            OR
                `repo_phone_time_use`.`platform` LIKE 'iOS%'
            OR
                `repo_phone_time_use`.`platform` LIKE 'Android%'
            OR
                `repo_phone_time_use`.`platform` LIKE 'Blackberry%'
            OR
                `repo_phone_time_use`.`platform` LIKE 'Symbian%'
            AND
                `repo_phone_time_use`.`account_id` = `repo_yss_account_report_cost`.`account_id`
            AND
                `repo_phone_time_use`.`campaign_id` = `repo_yss_account_report_cost`.`campaign_id`
            AND
                `repo_yss_account_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
            AND
                `repo_phone_time_use`.`source` = 'yss'
            AND
                `repo_phone_time_use`.`traffic_type` = 'AD'
        )
WHERE
    `repo_yss_account_report_cost`.`device` = 'SMART PHONE'
AND
    `repo_yss_account_report_cost`.`account_id` = 1
AND
    `repo_yss_account_report_cost`.`campaign_id` = 11
AND
    `repo_yss_account_report_cost`.`accountid` = 11
AND
    `repo_yss_account_report_cost`.`day` >= '2017-01-01'
AND
    `repo_yss_account_report_cost`.`day` <= '2017-12-01'
GROUP BY  devices

UNION

SELECT
    'YSS NONE' as devices,
    IFNULL(SUM(`repo_yss_account_report_cost`.`impressions`), 0) AS impressions,
    IFNULL(SUM(`repo_yss_account_report_cost`.`clicks`), 0) AS clicks,
    IFNULL(SUM(`repo_yss_account_report_cost`.`cost`), 0) AS cost,
    IFNULL(AVG(`repo_yss_account_report_cost`.`ctr`), 0) AS ctr,
    IFNULL(AVG(`repo_yss_account_report_cost`.`averageCpc`), 0) AS avgCPC,
    IFNULL(COUNT(`repo_yss_account_report_cost`.`id`), 0) AS call_tracking,
    IFNULL(SUM(`repo_yss_account_report_cost`.`conversions`), 0) AS webcv,
    IFNULL((SUM(`repo_yss_account_report_cost`.`conversions`) / SUM(`repo_yss_account_report_cost`.`clicks`) * 100), 0) AS webcvr,
    IFNULL((SUM(`repo_yss_account_report_cost`.`cost`) / SUM(`repo_yss_account_report_cost`.`conversions`)), 0) AS webcpa,
    IFNULL((SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
    IFNULL(((SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_yss_account_report_cost`.`clicks`) * 100), 0) AS cvr,
    IFNULL((SUM(`repo_yss_account_report_cost`.`cost`) / (SUM(`repo_yss_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
    IFNULL(AVG(`repo_yss_account_report_cost`.`averagePosition`), 0) AS avgPosition
FROM
    `repo_yss_account_report_cost`
        JOIN `repo_phone_time_use`
        ON (
                `repo_phone_time_use`.`platform` LIKE 'Unknown Platform%'
            AND
                `repo_yss_account_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
            AND
                `repo_phone_time_use`.`source` = 'yss'
            AND
                `repo_phone_time_use`.`traffic_type` = 'AD'
        )
WHERE
    `repo_yss_account_report_cost`.`device` = 'NONE'
AND
    `repo_yss_account_report_cost`.`account_id` = 1
AND
    `repo_yss_account_report_cost`.`campaign_id` = 11
AND
    `repo_yss_account_report_cost`.`accountid` = 11
AND
    `repo_yss_account_report_cost`.`day` >= '2017-01-01'
AND
    `repo_yss_account_report_cost`.`day` <= '2017-12-01'
GROUP BY  devices

UNION

SELECT
  'YDN PC' as device,
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
  JOIN (`repo_phone_time_use`)
  ON (
      `repo_phone_time_use`.`mobile` = 'No'
      AND
      (
        `repo_phone_time_use`.`platform` NOT LIKE 'Windows Phone%'
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
  )
WHERE
  `repo_ydn_reports`.`device` = 'PC'
AND
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`accountId` = 11
AND
  `repo_ydn_reports`.`day` >= '2017-01-01'
AND
  `repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY  device

UNION

SELECT
  'YDN Tablet' as device,
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
    JOIN (`repo_phone_time_use`)
    ON (
      `repo_phone_time_use`.`mobile` = 'No'
      AND
      (
        (
        `repo_phone_time_use`.`platform` LIKE 'iOS%'
        OR
        `repo_phone_time_use`.`platform` LIKE 'Android%'
        OR
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
  )
WHERE
  `repo_ydn_reports`.`device` = 'Tablet'
AND
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`accountId` = 11
AND
  `repo_ydn_reports`.`day` >= '2017-01-01'
AND
  `repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY  device

UNION

SELECT
  'YDN SmartPhone' as device,
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
    JOIN (`repo_phone_time_use`)
    ON (
      `repo_phone_time_use`.`mobile` LIKE 'Yes%'
      AND
      (
        `repo_phone_time_use`.`platform` LIKE 'iOS%'
        OR
        `repo_phone_time_use`.`platform` LIKE 'Android%'
        OR
        `repo_phone_time_use`.`platform` LIKE 'Blackberry%'
        OR
        `repo_phone_time_use`.`platform` LIKE 'Symbian%'
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
  )
WHERE
  `repo_ydn_reports`.`device` = 'SmartPhone'
AND
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`accountId` = 11
AND
  `repo_ydn_reports`.`day` >= '2017-01-01'
AND
  `repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY  device

UNION

SELECT
  'YDN Other' as device,
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
    )
WHERE
  `repo_ydn_reports`.`device` = 'Other'
AND
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`accountId` = 11
AND
  `repo_ydn_reports`.`day` >= '2017-01-01'
AND
  `repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY  device

UNION

SELECT
  'ADW UNKNOWN' as device,
  IFNULL(SUM(`repo_adw_account_report_cost`.`impressions`), 0) AS impressions,
  IFNULL(SUM(`repo_adw_account_report_cost`.`clicks`), 0) AS clicks,
  IFNULL(SUM(`repo_adw_account_report_cost`.`cost`), 0) AS cost,
  IFNULL(AVG(`repo_adw_account_report_cost`.`ctr`), 0) AS ctr,
  IFNULL(AVG(`repo_adw_account_report_cost`.`avgCPC`), 0) AS avgCPC,
  IFNULL(COUNT(`repo_phone_time_use`.`id`), 0) AS call_tracking,
  IFNULL(SUM(`repo_adw_account_report_cost`.`conversions`), 0) AS webcv,
  IFNULL((SUM(`repo_adw_account_report_cost`.`conversions`) / SUM(`repo_adw_account_report_cost`.`clicks`) * 100), 0) AS webcvr,
  IFNULL((SUM(`repo_adw_account_report_cost`.`cost`) / SUM(`repo_adw_account_report_cost`.`conversions`)), 0) AS webcpa,
  IFNULL((SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
  IFNULL(((SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_adw_account_report_cost`.`clicks`) * 100), 0) AS cvr,
  IFNULL((SUM(`repo_adw_account_report_cost`.`cost`) / (SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
  IFNULL(AVG(`repo_adw_account_report_cost`.`avgPosition`), 0) AS avgPosition
FROM
  `repo_adw_account_report_cost`
JOIN
  `repo_phone_time_use`
  ON
      `repo_phone_time_use`.`account_id` = `repo_adw_account_report_cost`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_adw_account_report_cost`.`campaign_id`
    AND
      `repo_adw_account_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`source` = 'adw'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
    AND
      `repo_phone_time_use`.`platform` LIKE 'Unknown Platform%'
WHERE
  `repo_adw_account_report_cost`.`account_id` = 1
AND
  `repo_adw_account_report_cost`.`campaign_id` = 11
AND
  `repo_adw_account_report_cost`.`customerID` = 11
AND
  `repo_adw_account_report_cost`.`device` = 'UNKNOWN'
AND
  `repo_adw_account_report_cost`.`day` >= '2017-01-01'
AND
  `repo_adw_account_report_cost`.`day` <= '2017-12-01'
GROUP BY
  device

UNION

SELECT
  'ADW DESKTOP' as device,
  IFNULL(SUM(`repo_adw_account_report_cost`.`impressions`), 0) AS impressions,
  IFNULL(SUM(`repo_adw_account_report_cost`.`clicks`), 0) AS clicks,
  IFNULL(SUM(`repo_adw_account_report_cost`.`cost`), 0) AS cost,
  IFNULL(AVG(`repo_adw_account_report_cost`.`ctr`), 0) AS ctr,
  IFNULL(AVG(`repo_adw_account_report_cost`.`avgCPC`), 0) AS avgCPC,
  IFNULL(COUNT(`repo_phone_time_use`.`id`), 0) AS call_tracking,
  IFNULL(SUM(`repo_adw_account_report_cost`.`conversions`), 0) AS webcv,
  IFNULL((SUM(`repo_adw_account_report_cost`.`conversions`) / SUM(`repo_adw_account_report_cost`.`clicks`) * 100), 0) AS webcvr,
  IFNULL((SUM(`repo_adw_account_report_cost`.`cost`) / SUM(`repo_adw_account_report_cost`.`conversions`)), 0) AS webcpa,
  IFNULL((SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
  IFNULL(((SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_adw_account_report_cost`.`clicks`) * 100), 0) AS cvr,
  IFNULL((SUM(`repo_adw_account_report_cost`.`cost`) / (SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
  IFNULL(AVG(`repo_adw_account_report_cost`.`avgPosition`), 0) AS avgPosition
FROM
  `repo_adw_account_report_cost`
JOIN
  `repo_phone_time_use`
  ON
      `repo_phone_time_use`.`account_id` = `repo_adw_account_report_cost`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_adw_account_report_cost`.`campaign_id`
    AND
      `repo_adw_account_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`source` = 'adw'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
    AND
      (
          `repo_phone_time_use`.`mobile` = 'No'
        AND
          `repo_phone_time_use`.`platform` NOT LIKE 'Window Phone%'
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
              `repo_phone_time_use`.`platform` LIKE 'NetBSD%'
            OR
              `repo_phone_time_use`.`platform` LIKE 'Unknown Windows OS%'
          )
      )
WHERE
  `repo_adw_account_report_cost`.`device` = 'DESKTOP'
AND
  `repo_adw_account_report_cost`.`account_id` = 1
AND
  `repo_adw_account_report_cost`.`campaign_id` = 11
AND
  `repo_adw_account_report_cost`.`customerID` = 11
AND
  `repo_adw_account_report_cost`.`day` >= '2017-01-01'
AND
  `repo_adw_account_report_cost`.`day` <= '2017-12-01'
GROUP BY
  device

UNION

SELECT
  'ADW HIGH_END_MOBILE' as device,
  IFNULL(SUM(`repo_adw_account_report_cost`.`impressions`), 0) AS impressions,
  IFNULL(SUM(`repo_adw_account_report_cost`.`clicks`), 0) AS clicks,
  IFNULL(SUM(`repo_adw_account_report_cost`.`cost`), 0) AS cost,
  IFNULL(AVG(`repo_adw_account_report_cost`.`ctr`), 0) AS ctr,
  IFNULL(AVG(`repo_adw_account_report_cost`.`avgCPC`), 0) AS avgCPC,
  IFNULL(COUNT(`repo_phone_time_use`.`id`), 0) AS call_tracking,
  IFNULL(SUM(`repo_adw_account_report_cost`.`conversions`), 0) AS webcv,
  IFNULL((SUM(`repo_adw_account_report_cost`.`conversions`) / SUM(`repo_adw_account_report_cost`.`clicks`) * 100), 0) AS webcvr,
  IFNULL((SUM(`repo_adw_account_report_cost`.`cost`) / SUM(`repo_adw_account_report_cost`.`conversions`)), 0) AS webcpa,
  IFNULL((SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
  IFNULL(((SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_adw_account_report_cost`.`clicks`) * 100), 0) AS cvr,
  IFNULL((SUM(`repo_adw_account_report_cost`.`cost`) / (SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
  IFNULL(AVG(`repo_adw_account_report_cost`.`avgPosition`), 0) AS avgPosition
FROM
  `repo_adw_account_report_cost`
JOIN
  `repo_phone_time_use`
  ON
      `repo_phone_time_use`.`account_id` = `repo_adw_account_report_cost`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_adw_account_report_cost`.`campaign_id`
    AND
      `repo_adw_account_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`source` = 'adw'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
    AND
      `repo_phone_time_use`.`mobile` = 'Yes'
WHERE
  `repo_adw_account_report_cost`.`device` = 'HIGH_END_MOBILE'
AND
  `repo_adw_account_report_cost`.`account_id` = 1
AND
  `repo_adw_account_report_cost`.`campaign_id` = 11
AND
  `repo_adw_account_report_cost`.`customerID` = 11
AND
  `repo_adw_account_report_cost`.`day` >= '2017-01-01'
AND
  `repo_adw_account_report_cost`.`day` <= '2017-12-01'
GROUP BY
  device

UNION

SELECT
  'ADW TABLET' as device,
  IFNULL(SUM(`repo_adw_account_report_cost`.`impressions`), 0) AS impressions,
  IFNULL(SUM(`repo_adw_account_report_cost`.`clicks`), 0) AS clicks,
  IFNULL(SUM(`repo_adw_account_report_cost`.`cost`), 0) AS cost,
  IFNULL(AVG(`repo_adw_account_report_cost`.`ctr`), 0) AS ctr,
  IFNULL(AVG(`repo_adw_account_report_cost`.`avgCPC`), 0) AS avgCPC,
  IFNULL(COUNT(`repo_phone_time_use`.`id`), 0) AS call_tracking,
  IFNULL(SUM(`repo_adw_account_report_cost`.`conversions`), 0) AS webcv,
  IFNULL((SUM(`repo_adw_account_report_cost`.`conversions`) / SUM(`repo_adw_account_report_cost`.`clicks`) * 100), 0) AS webcvr,
  IFNULL((SUM(`repo_adw_account_report_cost`.`cost`) / SUM(`repo_adw_account_report_cost`.`conversions`)), 0) AS webcpa,
  IFNULL((SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)), 0) AS cv,
  IFNULL(((SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_adw_account_report_cost`.`clicks`) * 100), 0) AS cvr,
  IFNULL((SUM(`repo_adw_account_report_cost`.`cost`) / (SUM(`repo_adw_account_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`))), 0) AS cpa,
  IFNULL(AVG(`repo_adw_account_report_cost`.`avgPosition`), 0) AS avgPosition

FROM
  `repo_adw_account_report_cost`
JOIN
  `repo_phone_time_use`
  ON
      `repo_phone_time_use`.`account_id` = `repo_adw_account_report_cost`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_adw_account_report_cost`.`campaign_id`
    AND
      `repo_adw_account_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`source` = 'adw'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
    AND
      `repo_phone_time_use`.`mobile` = 'No'
    AND
      `repo_phone_time_use`.`platform` LIKE 'Android%'
    AND
      (
          `repo_phone_time_use`.`mobile` = 'No'
        AND
          (
              `repo_phone_time_use`.`platform` LIKE 'iOS%'
            OR
              `repo_phone_time_use`.`platform` LIKE 'Android%'
            OR
              `repo_phone_time_use`.`platform` LIKE 'Blackberry'
          )
      )
WHERE
  `repo_adw_account_report_cost`.`device` = 'TABLET'
AND
  `repo_adw_account_report_cost`.`account_id` = 1
AND
  `repo_adw_account_report_cost`.`campaign_id` = 11
AND
  `repo_adw_account_report_cost`.`customerID` = 11
AND
  `repo_adw_account_report_cost`.`day` >= '2017-01-01'
AND
  `repo_adw_account_report_cost`.`day` <= '2017-12-01'
GROUP BY
  device;