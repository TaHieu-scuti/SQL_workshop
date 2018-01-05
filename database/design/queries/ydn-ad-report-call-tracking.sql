/* Get all unique conversion names */
SELECT
  DISTINCT
  campaignID,
  conversionName
FROM
  `repo_ydn_reports`
WHERE
  account_id = 1
AND
  accountId = 11;
/* Result from query above:
|campaignID              |conversionName       |
|1111111111111111        |YDN conversion 111110|
|1111111111111111        |YDN conversion 111111|
|1111111111111112        |YDN conversion 111111|
|1111111111111112        |YDN conversion 111110|
|1111111111111121        |YDN conversion 111110|
|1111111111111121        |YDN conversion 111111|
|1111111111111122        |YDN conversion 111111|
|1111111111111122        |YDN conversion 111110|
|1111111111111123        |YDN conversion 111110|
|1111111111111123        |YDN conversion 111111|
|1111111111111131        |YDN conversion 111111|
|1111111111111131        |YDN conversion 111110|
|1111111111111132        |YDN conversion 111110|
|1111111111111132        |YDN conversion 111111|
|1111111111111133        |YDN conversion 111111|
|1111111111111133        |YDN conversion 111110|
|1111111111111113        |YDN conversion 111110|
|1111111111111113        |YDN conversion 111111|
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
  `phone_time_use` ptu
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


SELECT `repo_ydn_reports`.`displayURL`,
       `repo_ydn_reports`.`description1`,
       repo_ydn_reports.adType AS adType,
       `repo_ydn_reports`.`adID`,
       `repo_ydn_reports`.`adName`,
       `repo_ydn_reports`.`campaignID`,

  (SELECT campaignName
   FROM repo_ydn_reports
   WHERE repo_ydn_reports.campaignID = repo_ydn_reports.campaignID
   LIMIT 1) AS campaignName,
       IFNULL(SUM(repo_ydn_reports.impressions), 0) AS impressions,
       IFNULL(SUM(repo_ydn_reports.clicks), 0) AS clicks,
       IFNULL(SUM(repo_ydn_reports.cost), 0) AS cost,
       IFNULL(ROUND(AVG(repo_ydn_reports.ctr), 2), 0) AS ctr,
       IFNULL(ROUND(AVG(repo_ydn_reports.averageCpc), 2), 0) AS averageCpc,
       IFNULL(ROUND(AVG(repo_ydn_reports.averagePosition), 2), 0) AS averagePosition,
       IFNULL(SUM(`conv0`.`conversions`), 0) AS 'YDN YDN conversion 221220 CV',
       IFNULL((SUM(`conv0`.`conversions`) / SUM(`conv0`.`clicks`)) * 100, 0) AS 'YDN YDN conversion 221220 CVR',
       IFNULL(SUM(`conv0`.`cost`) / SUM(`conv0`.`conversions`), 0) AS 'YDN YDN conversion 221220 CPA',
       IFNULL(SUM(`conv1`.`conversions`), 0) AS 'YDN YDN conversion 221221 CV',
       IFNULL((SUM(`conv1`.`conversions`) / SUM(`conv1`.`clicks`)) * 100, 0) AS 'YDN YDN conversion 221221 CVR',
       IFNULL(SUM(`conv1`.`cost`) / SUM(`conv1`.`conversions`), 0) AS 'YDN YDN conversion 221221 CPA',
       IFNULL(SUM(`repo_ydn_reports`.`conversions`), 0) AS web_cv,
       IFNULL((SUM(`repo_ydn_reports`.`conversions`) / SUM(`repo_ydn_reports`.`clicks`)) * 100, 0) AS web_cvr,
       IFNULL(SUM(`repo_ydn_reports`.`cost`) / SUM(`repo_ydn_reports`.`conversions`), 0) AS web_cpa,
       IFNULL(COUNT(`call0`.`id`), 0) AS 'YDN Campaign Name +841234567821 CV',
       IFNULL(COUNT(`call0`.`id`) / SUM(`repo_ydn_reports`.`clicks`), 0) AS 'YDN Campaign Name +841234567821 CVR',
       IFNULL(SUM(`repo_ydn_reports`.`cost`) / COUNT(`call0`.`id`), 0) AS 'YDN Campaign Name +841234567821 CPA',
       IFNULL(COUNT(`call0`.`id`), 0) AS call_cv,
       IFNULL((COUNT(`call0`.`id`)) / 1, 0) AS call_cvr,
       IFNULL(SUM(`repo_ydn_reports`.`cost`) / (COUNT(`call0`.`id`)), 0) AS call_cpa,
       IFNULL(SUM(`repo_ydn_reports`.`conversions`) + COUNT(`call0`.`id`), 0) AS repo_ydn_reports_cv,
       IFNULL((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`call0`.`id`)) / SUM(`repo_ydn_reports`.`clicks`), 0) AS repo_ydn_reports_cvr,
       IFNULL(SUM(`repo_ydn_reports`.`cost`) / (SUM(`repo_ydn_reports`.`conversions`) + COUNT(`call0`.`id`)), 0) AS repo_ydn_reports_cpa
FROM `repo_ydn_reports` AS repo_ydn_reports
LEFT JOIN `repo_ydn_reports` AS `conv0` ON `repo_ydn_reports`.`account_id` = `conv0`.`account_id`
AND `repo_ydn_reports`.`accountId` = `conv0`.`accountId`
AND `repo_ydn_reports`.`day` = `conv0`.`day`
AND `repo_ydn_reports`.`campaignID` = `conv0`.`campaignID`
AND `conv0`.`campaignID` = 21
AND `conv0`.`conversionName` = 'YDN conversion 221220'
LEFT JOIN `repo_ydn_reports` AS `conv1` ON `repo_ydn_reports`.`account_id` = `conv1`.`account_id`
AND `repo_ydn_reports`.`accountId` = `conv1`.`accountId`
AND `repo_ydn_reports`.`day` = `conv1`.`day`
AND `repo_ydn_reports`.`campaignID` = `conv1`.`campaignID`
AND `conv1`.`campaignID` = 21
AND `conv1`.`conversionName` = 'YDN conversion 221221'
LEFT JOIN `repo_phone_time_use` AS `call0` ON `repo_ydn_reports`.`account_id` = `call0`.`account_id`
AND `repo_ydn_reports`.`campaign_id` = `call0`.`campaign_id`
AND `repo_ydn_reports`.`campaignID` = `call0`.`utm_campaign`
AND `repo_ydn_reports`.`day` = STR_TO_DATE(`call0`.`time_of_call`, '%Y-%m-%d')
AND `call0`.`utm_campaign` = 21
AND `call0`.`phone_number` = '+841234567821'
AND `call0`.`source` = 'ydn'
WHERE (date(`repo_ydn_reports`.`day`) >= '2017-10-07'
       AND date(`repo_ydn_reports`.`day`) <= '2018-01-05')
  AND (`repo_ydn_reports`.`adgroupID` = '22121211')
GROUP BY `repo_ydn_reports`.`campaignID`,
         `repo_ydn_reports`.`adID`,
         `repo_ydn_reports`.`adName`,
         `repo_ydn_reports`.`adType`,
         `repo_ydn_reports`.`displayURL`,
         `repo_ydn_reports`.`description1`
ORDER BY `adType` DESC

/* Execution time without indexes: 659 seconds */
/* Execution time with index on repo_ydn_report: account_id and accountId: 616 seconds */
/* Execution time with index on repo_ydn_report: account_id, accountId and day: 258 seconds */
/* Execution time with index on repo_ydn_report: account_id, accountId, campaignID, conversionName and day: 254 seconds */
/* Execution time with index on repo_ydn_report: account_id, accountId, campaignID, conversionName and day:
   phone_time_use: account_id, campaign_id, utm_campaign, phone_number, time_of_call: 92 seconds */
