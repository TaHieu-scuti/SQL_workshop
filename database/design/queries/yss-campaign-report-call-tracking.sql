/* Get all unique conversion names */
SELECT
  DISTINCT
  campaignID,
  conversionName
FROM
  `repo_yss_campaign_report_conv`
WHERE
  account_id = 1
AND
  accountId = 11;
/* Result from query above:
|campaignID|conversionName       |
|11        |YSS conversion 111110|
|11        |YSS conversion 111111|
|11        |YSS conversion 111112|
|12        |YSS conversion 112110|
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
  `ptu`.`source` = 'yss';
/* Result from query above:
|campaign_id|campaign_name|utm_campaign|phone_number |
|11         |Campaign Name|11          |+841234567811|
|12         |Campaign Name|12          |+841234567812|
 */

SELECT
  `total`.`account_id`,
  `total`.`accountid`,
  `total`.`campaignID`,
  SUM(`total`.`impressions`) AS impressions,
  SUM(`total`.`clicks`) AS clicks,
  SUM(`total`.`cost`) AS cost,
  AVG(`total`.`ctr`) AS ctr,
  AVG(`total`.`averageCpc`) AS cpc,
  /* add the expressions for the conversionName columns */
  SUM(`conv0`.`conversions`) AS 'YSS conversion 111110 CV',
  SUM(`conv1`.`conversions`) AS 'YSS conversion 111111 CV',
  SUM(`conv2`.`conversions`) AS 'YSS conversion 111112 CV',
  SUM(`conv3`.`conversions`) AS 'YSS conversion 112110 CV',
  /* add the expressions for the AG campaign_name/phone_number columns */
  COUNT(`call0`.`id`) AS 'Campaign Name +841234567811 CV',
  COUNT(`call1`.`id`) AS 'Campaign Name +841234567812 CV',
  COUNT(`call0`.`id`) + COUNT(`call1`.`id`) AS call_cv,
  SUM(`total`.`conversions`) AS webcv,
  SUM(`total`.`conversions`) + COUNT(`call0`.`id`) + COUNT(`call1`.`id`) AS cv,
  ((SUM(`total`.`conversions`) + COUNT(`call0`.`id`) + COUNT(`call1`.`id`)) / SUM(`total`.`clicks`)) * 100 AS cvr,
  SUM(`total`.`cost`) / (SUM(`total`.`conversions`) + COUNT(`call0`.`id`) + COUNT(`call1`.`id`)) AS cpa,
  AVG(`total`.`averagePosition`) AS avgPosition
FROM
  `repo_yss_campaign_report_cost` AS total
  /* Add joins for every campaignID & conversionName combination */
  LEFT JOIN `repo_yss_campaign_report_conv` AS conv0
    ON (
      `total`.`account_id` = `conv0`.`account_id`
    AND
      `total`.`accountid` = `conv0`.`accountid`
    AND
      `total`.`day` = `conv0`.`day`
    AND
      `total`.`campaignID` = `conv0`.`campaignID`
    AND
      `conv0`.`campaignID` = 11
    AND
      `conv0`.`conversionName` = 'YSS conversion 111110'
    )
  LEFT JOIN `repo_yss_campaign_report_conv` AS conv1
    ON (
      `total`.`account_id` = `conv1`.`account_id`
    AND
      `total`.`accountid` = `conv1`.`accountid`
    AND
      `total`.`day` = `conv1`.`day`
    AND
      `total`.`campaignID` = `conv1`.`campaignID`
    AND
      `conv1`.`campaignID` = 11
    AND
      `conv1`.`conversionName` = 'YSS conversion 111111'
    )
  LEFT JOIN `repo_yss_campaign_report_conv` AS conv2
    ON (
      `total`.`account_id` = `conv2`.`account_id`
    AND
      `total`.`accountid` = `conv2`.`accountid`
    AND
      `total`.`day` = `conv2`.`day`
    AND
      `total`.`campaignID` = `conv2`.`campaignID`
    AND
      `conv2`.`campaignID` = 11
    AND
      `conv2`.`conversionName` = 'YSS conversion 111112'
    )
  LEFT JOIN `repo_yss_campaign_report_conv` AS conv3
    ON (
      `total`.`account_id` = `conv3`.`account_id`
    AND
      `total`.`accountid` = `conv3`.`accountid`
    AND
      `total`.`day` = `conv3`.`day`
    AND
      `total`.`campaignID` = `conv3`.`campaignID`
    AND
      `conv3`.`campaignID` = 12
    AND
      `conv3`.`conversionName` = 'YSS conversion 112110'
    )
  /* Add joins for every AG campaign & phone_number combination */
  LEFT JOIN `repo_phone_time_use` AS call0
    ON (
      `total`.`account_id` = `call0`.`account_id`
    AND
      `total`.`campaign_id` = `call0`.`campaign_id`
    AND
      `total`.`campaignID` = `call0`.`utm_campaign`
    AND
      `total`.`day` = STR_TO_DATE(`call0`.`time_of_call`, '%Y-%m-%d')
    AND
      `call0`.`utm_campaign` = 11
    AND
      `call0`.`phone_number` = '+841234567811'
    AND
      `call0`.`source` = 'yss'
    )
  LEFT JOIN `repo_phone_time_use` AS call1
    ON (
      `total`.`account_id` = `call1`.`account_id`
    AND
      `total`.`campaign_id` = `call1`.`campaign_id`
    AND
      `total`.`campaignID` = `call1`.`utm_campaign`
    AND
      `total`.`day` = STR_TO_DATE(`call1`.`time_of_call`, '%Y-%m-%d')
    AND
      `call1`.`utm_campaign` = 12
    AND
      `call1`.`phone_number` = '+841234567812'
    AND
      `call1`.`source` = 'yss'
    )
WHERE
  `total`.`account_id` = 1
AND
  `total`.`accountid` = 11
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <= '2017-12-01'
GROUP BY
  `total`.`account_id`,
  `total`.`accountid`,
  `total`.`campaignID`
