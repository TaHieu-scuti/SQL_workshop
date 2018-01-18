SELECT
    'DESKTOP' as devices,
    SUM(`repo_yss_campaign_report_cost`.`impressions`) AS impressions,
    SUM(`repo_yss_campaign_report_cost`.`clicks`) AS clicks,
    SUM(`repo_yss_campaign_report_cost`.`cost`) AS cost,
    AVG(`repo_yss_campaign_report_cost`.`ctr`) AS ctr,
    AVG(`repo_yss_campaign_report_cost`.`averageCPC`) AS avgCPC,
    COUNT(`repo_phone_time_use`.`id`) AS call_tracking,
    SUM(`repo_yss_campaign_report_cost`.`conversions`) AS webcv,
    SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`) AS cv,
    ((SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_yss_campaign_report_cost`.`clicks`)) * 100 AS cvr,
    SUM(`repo_yss_campaign_report_cost`.`cost`) / (SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) AS cpa,
    AVG(`repo_yss_campaign_report_cost`.`averagePosition`) AS avgPosition
FROM
    `repo_yss_campaign_report_cost`
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
                `repo_phone_time_use`.`account_id` = `repo_yss_campaign_report_cost`.`account_id`
            AND
                `repo_phone_time_use`.`campaign_id` = `repo_yss_campaign_report_cost`.`campaign_id`
            AND
                `repo_phone_time_use`.`utm_campaign` = `repo_yss_campaign_report_cost`.`campaignID`
            AND
                `repo_yss_campaign_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
            AND
                `repo_phone_time_use`.`source` = 'yss'
            AND
                `repo_phone_time_use`.`traffic_type` = 'AD'
        )
WHERE
    `repo_yss_campaign_report_cost`.`device` = 'DESKTOP'
AND
    `repo_yss_campaign_report_cost`.`account_id` = 1
AND
    `repo_yss_campaign_report_cost`.`campaign_id` = 11
AND
    `repo_yss_campaign_report_cost`.`accountid` = 11
AND
    `repo_yss_campaign_report_cost`.`day` >= '2017-01-01'
AND
    `repo_yss_campaign_report_cost`.`day` <= '2017-12-01'
GROUP BY  devices

UNION

SELECT
    'SMART_PHONE' as devices,
    SUM(`repo_yss_campaign_report_cost`.`impressions`) AS impressions,
    SUM(`repo_yss_campaign_report_cost`.`clicks`) AS clicks,
    SUM(`repo_yss_campaign_report_cost`.`cost`) AS cost,
    AVG(`repo_yss_campaign_report_cost`.`ctr`) AS ctr,
    AVG(`repo_yss_campaign_report_cost`.`averageCPC`) AS avgCPC,
    COUNT(`repo_phone_time_use`.`id`) AS call_tracking,
    SUM(`repo_yss_campaign_report_cost`.`conversions`) AS webcv,
    SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`) AS cv,
    ((SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_yss_campaign_report_cost`.`clicks`)) * 100 AS cvr,
    SUM(`repo_yss_campaign_report_cost`.`cost`) / (SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) AS cpa,
    AVG(`repo_yss_campaign_report_cost`.`averagePosition`) AS avgPosition
FROM
    `repo_yss_campaign_report_cost`
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
                `repo_phone_time_use`.`account_id` = `repo_yss_campaign_report_cost`.`account_id`
            AND
                `repo_phone_time_use`.`campaign_id` = `repo_yss_campaign_report_cost`.`campaign_id`
            AND
                `repo_phone_time_use`.`utm_campaign` = `repo_yss_campaign_report_cost`.`campaignID`
            AND
                `repo_yss_campaign_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
            AND
                `repo_phone_time_use`.`source` = 'yss'
            AND
                `repo_phone_time_use`.`traffic_type` = 'AD'
        )
WHERE
    `repo_yss_campaign_report_cost`.`device` = 'SMART_PHONE'
AND
    `repo_yss_campaign_report_cost`.`account_id` = 1
AND
    `repo_yss_campaign_report_cost`.`campaign_id` = 11
AND
    `repo_yss_campaign_report_cost`.`accountid` = 11
AND
    `repo_yss_campaign_report_cost`.`day` >= '2017-01-01'
AND
    `repo_yss_campaign_report_cost`.`day` <= '2017-12-01'
GROUP BY  devices

UNION

SELECT
    'NONE' as devices,
    SUM(`repo_yss_campaign_report_cost`.`impressions`) AS impressions,
    SUM(`repo_yss_campaign_report_cost`.`clicks`) AS clicks,
    SUM(`repo_yss_campaign_report_cost`.`cost`) AS cost,
    AVG(`repo_yss_campaign_report_cost`.`ctr`) AS ctr,
    AVG(`repo_yss_campaign_report_cost`.`averageCPC`) AS avgCPC,
    COUNT(`repo_phone_time_use`.`id`) AS call_tracking,
    SUM(`repo_yss_campaign_report_cost`.`conversions`) AS webcv,
    SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`) AS cv,
    ((SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) / SUM(`repo_yss_campaign_report_cost`.`clicks`)) * 100 AS cvr,
    SUM(`repo_yss_campaign_report_cost`.`cost`) / (SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`repo_phone_time_use`.`id`)) AS cpa,
    AVG(`repo_yss_campaign_report_cost`.`averagePosition`) AS avgPosition
FROM
    `repo_yss_campaign_report_cost`
        JOIN `repo_phone_time_use`
        ON (
                `repo_phone_time_use`.`platform` LIKE 'Unknown Platform%'
            AND
                `repo_yss_campaign_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
            AND
                `repo_phone_time_use`.`source` = 'yss'
            AND
                `repo_phone_time_use`.`traffic_type` = 'AD'
        )
WHERE
    `repo_yss_campaign_report_cost`.`device` = 'NONE'
AND
    `repo_yss_campaign_report_cost`.`account_id` = 1
AND
    `repo_yss_campaign_report_cost`.`campaign_id` = 11
AND
    `repo_yss_campaign_report_cost`.`accountid` = 11
AND
    `repo_yss_campaign_report_cost`.`day` >= '2017-01-01'
AND
    `repo_yss_campaign_report_cost`.`day` <= '2017-12-01'
GROUP BY  devices