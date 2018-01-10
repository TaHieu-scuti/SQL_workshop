/* Get all unique conversion names */
SELECT
  DISTINCT
  campaignID,
  adgroupID,
  conversionName
FROM
  `repo_ydn_reports`
WHERE
  account_id = 2
AND
  accountId = 23;
/* Result from query above:
campaignID |adgroupID       |conversionName       |
21         |22123211        |YDN conversion 221230|
21         |22123211        |YDN conversion 221231|
21         |22123212        |YDN conversion 221230|
21         |22123212        |YDN conversion 221231|
21         |22123213        |YDN conversion 221230|
21         |22123213        |YDN conversion 221231|
 */


/* Get all AG campaigns with phone number */
SELECT
  DISTINCT
  `c`.`campaign_id`,
  `c`.`campaign_name`,
  `ptu`.`utm_campaign`,
  `ptu`.`phone_number`
FROM
  `campaigns` c,
  `phone_time_use` ptua
WHERE
  `c`.`campaign_id` = `ptu`.`campaign_id`
AND
  `c`.`account_id` = `ptu`.`account_id`
AND
  `c`.`account_id` = 2
AND
  `ptu`.`utm_campaign` IN (21)
AND
  `ptu`.`source` = 'ydn';
/* Result from query above:
|campaign_id|campaign_name|utm_campaign|phone_number |
|21         |Campaign Name|21          |+841234567821|
 */


SELECT `repo_ydn_reports`.`adgroupID`,
       `repo_ydn_reports`.`adgroupName`,
       IFNULL(SUM(repo_ydn_reports.impressions), 0) AS impressions,
       IFNULL(SUM(repo_ydn_reports.clicks), 0) AS clicks,
       IFNULL(SUM(repo_ydn_reports.cost), 0) AS cost,
       IFNULL(ROUND(AVG(repo_ydn_reports.ctr), 2), 0) AS ctr,
       IFNULL(ROUND(AVG(repo_ydn_reports.averageCpc), 2), 0) AS averageCpc,
       IFNULL(ROUND(AVG(repo_ydn_reports.averagePosition), 2), 0) AS averagePosition,
       IFNULL(SUM(`conv0`.`conversions`), 0) AS 'YDN YDN conversion 221230 CV',
       IFNULL((SUM(`conv0`.`conversions`) / SUM(`conv0`.`clicks`)) * 100, 0) AS 'YDN YDN conversion 221230 CVR',
       IFNULL(SUM(`conv0`.`cost`) / SUM(`conv0`.`conversions`), 0) AS 'YDN YDN conversion 221230 CPA',
       IFNULL(SUM(`conv1`.`conversions`), 0) AS 'YDN YDN conversion 221231 CV',
       IFNULL((SUM(`conv1`.`conversions`) / SUM(`conv1`.`clicks`)) * 100, 0) AS 'YDN YDN conversion 221231 CVR',
       IFNULL(SUM(`conv1`.`cost`) / SUM(`conv1`.`conversions`), 0) AS 'YDN YDN conversion 221231 CPA',
       IFNULL(SUM(`repo_ydn_reports`.`conversions`), 0) AS web_cv,
       IFNULL((SUM(`repo_ydn_reports`.`conversions`) / SUM(`repo_ydn_reports`.`clicks`)) * 100, 0) AS web_cvr,
       IFNULL(SUM(`repo_ydn_reports`.`cost`) / SUM(`repo_ydn_reports`.`conversions`), 0) AS web_cpa,
       IFNULL(COUNT(`call0`.`id`), 0) AS 'YDN Campaign Name +841234567821 CV',
       IFNULL(COUNT(`call0`.`id`) / SUM(`repo_ydn_reports`.`clicks`), 0) AS 'YDN Campaign Name +841234567821 CVR',
       IFNULL(SUM(`repo_ydn_reports`.`cost`) / COUNT(`call0`.`id`), 0) AS 'YDN Campaign Name +841234567821 CPA',
       IFNULL(COUNT(`call0`.`id`), 0) AS call_cv,
       IFNULL((COUNT(`call0`.`id`)) / 1, 0) AS call_cvr,
       IFNULL(SUM(`repo_ydn_reports`.`cost`) / (COUNT(`call0`.`id`)), 0) AS call_cpa,
       IFNULL(SUM(`repo_ydn_reports`.`conversions`) + COUNT(`call0`.`id`), 0) AS total_cv,
       IFNULL((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`call0`.`id`)) / SUM(`repo_ydn_reports`.`clicks`), 0) AS total_cvr,
       IFNULL(SUM(`repo_ydn_reports`.`cost`) / (SUM(`repo_ydn_reports`.`conversions`) + COUNT(`call0`.`id`)), 0) AS total_cpa
FROM `repo_ydn_reports` FORCE INDEX (repo_ydn_reports_day_campaignID_idx)
LEFT JOIN `repo_ydn_reports` AS `conv0` ON `repo_ydn_reports`.`account_id` = `conv0`.`account_id`
AND `repo_ydn_reports`.`accountId` = `conv0`.`accountId`
AND `repo_ydn_reports`.`day` = `conv0`.`day`
AND `repo_ydn_reports`.`campaignID` = `conv0`.`campaignID`
AND `conv0`.`adgroupID` IN ('22123211', '22123212', '22123213')
AND `conv0`.`conversionName` = 'YDN conversion 221230'
LEFT JOIN `repo_ydn_reports` AS `conv1` ON `repo_ydn_reports`.`account_id` = `conv1`.`account_id`
AND `repo_ydn_reports`.`accountId` = `conv1`.`accountId`
AND `repo_ydn_reports`.`day` = `conv1`.`day`
AND `repo_ydn_reports`.`campaignID` = `conv1`.`campaignID`
AND `conv1`.`adgroupID` IN ('22123211', '22123212', '22123213')
AND `conv1`.`conversionName` = 'YDN conversion 221231'
LEFT JOIN (`phone_time_use` AS call0,
           `campaigns` AS call0_campaigns) ON `call0_campaigns`.`account_id` = `repo_ydn_reports`.`account_id`
AND `call0_campaigns`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
AND ((call0_campaigns.camp_custom1 = "creative"
      AND call0.custom1 = repo_ydn_reports.adID)
     OR (call0_campaigns.camp_custom2 = "creative"
         AND call0.custom2 = repo_ydn_reports.adID)
     OR (call0_campaigns.camp_custom3 = "creative"
         AND call0.custom3 = repo_ydn_reports.adID)
     OR (call0_campaigns.camp_custom4 = "creative"
         AND call0.custom4 = repo_ydn_reports.adID)
     OR (call0_campaigns.camp_custom5 = "creative"
         AND call0.custom5 = repo_ydn_reports.adID)
     OR (call0_campaigns.camp_custom6 = "creative"
         AND call0.custom6 = repo_ydn_reports.adID)
     OR (call0_campaigns.camp_custom7 = "creative"
         AND call0.custom7 = repo_ydn_reports.adID)
     OR (call0_campaigns.camp_custom8 = "creative"
         AND call0.custom8 = repo_ydn_reports.adID)
     OR (call0_campaigns.camp_custom9 = "creative"
         AND call0.custom9 = repo_ydn_reports.adID)
     OR (call0_campaigns.camp_custom10 = "creative"
         AND call0.custom10 = repo_ydn_reports.adID))
AND `call0`.`account_id` = `repo_ydn_reports`.`account_id`
AND `call0`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
AND `call0`.`utm_campaign` = `repo_ydn_reports`.`campaignID`
AND STR_TO_DATE(`call0`.`time_of_call`, '%Y-%m-%d') = `repo_ydn_reports`.`day`
AND `call0`.`phone_number` = '+841234567821'
AND `call0`.`source` = 'ydn'
AND `call0`.`traffic_type` = 'AD'
WHERE (`repo_ydn_reports`.`day` >= '2017-10-12'
       AND `repo_ydn_reports`.`day` <= '2018-01-10')
  AND (`repo_ydn_reports`.`campaignID` = 21)
GROUP BY `repo_ydn_reports`.`adgroupID`,
         `repo_ydn_reports`.`adgroupName`
ORDER BY `impressions` DESC
