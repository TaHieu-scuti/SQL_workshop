/* Get all unique conversion names */
SELECT
  DISTINCT
  campaignID,
  adgroupID,
  conversionName
FROM
  `repo_ydn_reports`
WHERE
  account_id = 1
AND
  accountId = 11;
/* Result from query above:
campaignID |adgroupID     |conversionName       |
11         |111111        |YDN conversion 111110|
11         |111111        |YDN conversion 111111|
11         |111112        |YDN conversion 111110|
11         |111112        |YDN conversion 111111|
11         |111113        |YDN conversion 111111|
11         |111113        |YDN conversion 111110|
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
  `c`.`account_id` = 1
AND
  `ptu`.`utm_campaign` IN (11, 12)
AND
  `ptu`.`source` = 'ydn';
/* Result from query above:
|campaign_id|campaign_name|utm_campaign|phone_number |
|11         |Campaign Name|11          |+841234567811|
|12         |Campaign Name|12          |+841234567812|
 */


SELECT `repo_ydn_reports`.`adgroupID`,
       `repo_ydn_reports`.`adgroupName`,
       IFNULL(SUM(repo_ydn_reports.impressions), 0) AS impressions,
       IFNULL(SUM(repo_ydn_reports.clicks), 0) AS clicks,
       IFNULL(SUM(repo_ydn_reports.cost), 0) AS cost,
       IFNULL(ROUND(AVG(repo_ydn_reports.ctr), 2), 0) AS ctr,
       IFNULL(ROUND(AVG(repo_ydn_reports.averageCpc), 2), 0) AS averageCpc,
       IFNULL(ROUND(AVG(repo_ydn_reports.averagePosition), 2), 0) AS averagePosition,
       IFNULL(SUM(`conv0`.`conversions`), 0) AS 'YDN YDN conversion 221210 CV',
       IFNULL((SUM(`conv0`.`conversions`) / SUM(`conv0`.`clicks`)) * 100, 0) AS 'YDN YDN conversion 221210 CVR',
       IFNULL(SUM(`conv0`.`cost`) / SUM(`conv0`.`conversions`), 0) AS 'YDN YDN conversion 221210 CPA',
       IFNULL(SUM(`conv1`.`conversions`), 0) AS 'YDN YDN conversion 221211 CV',
       IFNULL((SUM(`conv1`.`conversions`) / SUM(`conv1`.`clicks`)) * 100, 0) AS 'YDN YDN conversion 221211 CVR',
       IFNULL(SUM(`conv1`.`cost`) / SUM(`conv1`.`conversions`), 0) AS 'YDN YDN conversion 221211 CPA',
       IFNULL(SUM(`conv2`.`conversions`), 0) AS 'YDN YDN conversion 221210 CV',
       IFNULL((SUM(`conv2`.`conversions`) / SUM(`conv2`.`clicks`)) * 100, 0) AS 'YDN YDN conversion 221210 CVR',
       IFNULL(SUM(`conv2`.`cost`) / SUM(`conv2`.`conversions`), 0) AS 'YDN YDN conversion 221210 CPA',
       IFNULL(SUM(`conv3`.`conversions`), 0) AS 'YDN YDN conversion 221211 CV',
       IFNULL((SUM(`conv3`.`conversions`) / SUM(`conv3`.`clicks`)) * 100, 0) AS 'YDN YDN conversion 221211 CVR',
       IFNULL(SUM(`conv3`.`cost`) / SUM(`conv3`.`conversions`), 0) AS 'YDN YDN conversion 221211 CPA',
       IFNULL(SUM(`conv4`.`conversions`), 0) AS 'YDN YDN conversion 221210 CV',
       IFNULL((SUM(`conv4`.`conversions`) / SUM(`conv4`.`clicks`)) * 100, 0) AS 'YDN YDN conversion 221210 CVR',
       IFNULL(SUM(`conv4`.`cost`) / SUM(`conv4`.`conversions`), 0) AS 'YDN YDN conversion 221210 CPA',
       IFNULL(SUM(`conv5`.`conversions`), 0) AS 'YDN YDN conversion 221211 CV',
       IFNULL((SUM(`conv5`.`conversions`) / SUM(`conv5`.`clicks`)) * 100, 0) AS 'YDN YDN conversion 221211 CVR',
       IFNULL(SUM(`conv5`.`cost`) / SUM(`conv5`.`conversions`), 0) AS 'YDN YDN conversion 221211 CPA',
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
FROM `repo_ydn_reports`
LEFT JOIN `repo_ydn_reports` AS `conv0` ON `repo_ydn_reports`.`account_id` = `conv0`.`account_id`
AND `repo_ydn_reports`.`accountId` = `conv0`.`accountId`
AND `repo_ydn_reports`.`day` = `conv0`.`day`
AND `repo_ydn_reports`.`campaignID` = `conv0`.`campaignID`
AND `repo_ydn_reports`.`adgroupID` = `conv0`.`adgroupID`
AND `conv0`.`campaignID` = 21
AND `conv0`.`adgroupID` = 22121211
AND `conv0`.`conversionName` = 'YDN conversion 221210'
LEFT JOIN `repo_ydn_reports` AS `conv1` ON `repo_ydn_reports`.`account_id` = `conv1`.`account_id`
AND `repo_ydn_reports`.`accountId` = `conv1`.`accountId`
AND `repo_ydn_reports`.`day` = `conv1`.`day`
AND `repo_ydn_reports`.`campaignID` = `conv1`.`campaignID`
AND `repo_ydn_reports`.`adgroupID` = `conv1`.`adgroupID`
AND `conv1`.`campaignID` = 21
AND `conv1`.`adgroupID` = 22121211
AND `conv1`.`conversionName` = 'YDN conversion 221211'
LEFT JOIN `repo_ydn_reports` AS `conv2` ON `repo_ydn_reports`.`account_id` = `conv2`.`account_id`
AND `repo_ydn_reports`.`accountId` = `conv2`.`accountId`
AND `repo_ydn_reports`.`day` = `conv2`.`day`
AND `repo_ydn_reports`.`campaignID` = `conv2`.`campaignID`
AND `repo_ydn_reports`.`adgroupID` = `conv2`.`adgroupID`
AND `conv2`.`campaignID` = 21
AND `conv2`.`adgroupID` = 22121212
AND `conv2`.`conversionName` = 'YDN conversion 221210'
LEFT JOIN `repo_ydn_reports` AS `conv3` ON `repo_ydn_reports`.`account_id` = `conv3`.`account_id`
AND `repo_ydn_reports`.`accountId` = `conv3`.`accountId`
AND `repo_ydn_reports`.`day` = `conv3`.`day`
AND `repo_ydn_reports`.`campaignID` = `conv3`.`campaignID`
AND `repo_ydn_reports`.`adgroupID` = `conv3`.`adgroupID`
AND `conv3`.`campaignID` = 21
AND `conv3`.`adgroupID` = 22121212
AND `conv3`.`conversionName` = 'YDN conversion 221211'
LEFT JOIN `repo_ydn_reports` AS `conv4` ON `repo_ydn_reports`.`account_id` = `conv4`.`account_id`
AND `repo_ydn_reports`.`accountId` = `conv4`.`accountId`
AND `repo_ydn_reports`.`day` = `conv4`.`day`
AND `repo_ydn_reports`.`campaignID` = `conv4`.`campaignID`
AND `repo_ydn_reports`.`adgroupID` = `conv4`.`adgroupID`
AND `conv4`.`campaignID` = 21
AND `conv4`.`adgroupID` = 22121213
AND `conv4`.`conversionName` = 'YDN conversion 221210'
LEFT JOIN `repo_ydn_reports` AS `conv5` ON `repo_ydn_reports`.`account_id` = `conv5`.`account_id`
AND `repo_ydn_reports`.`accountId` = `conv5`.`accountId`
AND `repo_ydn_reports`.`day` = `conv5`.`day`
AND `repo_ydn_reports`.`campaignID` = `conv5`.`campaignID`
AND `repo_ydn_reports`.`adgroupID` = `conv5`.`adgroupID`
AND `conv5`.`campaignID` = 21
AND `conv5`.`adgroupID` = 22121213
AND `conv5`.`conversionName` = 'YDN conversion 221211'
LEFT JOIN `repo_phone_time_use` AS `call0` ON `repo_ydn_reports`.`account_id` = `call0`.`account_id`
AND `repo_ydn_reports`.`campaign_id` = `call0`.`campaign_id`
AND `repo_ydn_reports`.`campaignID` = `call0`.`utm_campaign`
AND `repo_ydn_reports`.`day` = STR_TO_DATE(`call0`.`time_of_call`, '%Y-%m-%d')
AND `call0`.`utm_campaign` = 21
AND `call0`.`phone_number` = '+841234567821'
AND `call0`.`source` = 'ydn'
LEFT JOIN (`phone_time_use`,
           `campaigns`) ON `campaigns`.`account_id` = `repo_ydn_reports`.`account_id`
AND `campaigns`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
AND ((campaigns.camp_custom1 = "creative"
      AND phone_time_use.custom1 = repo_ydn_reports.adID)
     OR (campaigns.camp_custom2 = "creative"
         AND phone_time_use.custom2 = repo_ydn_reports.adID)
     OR (campaigns.camp_custom3 = "creative"
         AND phone_time_use.custom3 = repo_ydn_reports.adID)
     OR (campaigns.camp_custom4 = "creative"
         AND phone_time_use.custom4 = repo_ydn_reports.adID)
     OR (campaigns.camp_custom5 = "creative"
         AND phone_time_use.custom5 = repo_ydn_reports.adID)
     OR (campaigns.camp_custom6 = "creative"
         AND phone_time_use.custom6 = repo_ydn_reports.adID)
     OR (campaigns.camp_custom7 = "creative"
         AND phone_time_use.custom7 = repo_ydn_reports.adID)
     OR (campaigns.camp_custom8 = "creative"
         AND phone_time_use.custom8 = repo_ydn_reports.adID)
     OR (campaigns.camp_custom9 = "creative"
         AND phone_time_use.custom9 = repo_ydn_reports.adID)
     OR (campaigns.camp_custom10 = "creative"
         AND phone_time_use.custom10 = repo_ydn_reports.adID))
AND `phone_time_use`.`account_id` = `repo_ydn_reports`.`account_id`
AND `phone_time_use`.`campaign_id` = `repo_ydn_reports`.`campaign_id`
AND `phone_time_use`.`utm_campaign` = `repo_ydn_reports`.`campaignID`
AND STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `repo_ydn_reports`.`day`
AND `phone_time_use`.`source` = 'ydn'
AND `phone_time_use`.`traffic_type` = 'AD'
AND `phone_time_use`.`phone_number` = '+841234567821'
WHERE (date(`repo_ydn_reports`.`day`) >= '2017-10-11'
       AND date(`repo_ydn_reports`.`day`) <= '2018-01-09')
  AND (`repo_ydn_reports`.`campaignID` = 21)
GROUP BY `repo_ydn_reports`.`adgroupID`,
         `repo_ydn_reports`.`adgroupName`
ORDER BY `impressions` DESC
