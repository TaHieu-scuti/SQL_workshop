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
|campaignID|conversionName       |
|12        |YDN conversion 111110|
|11        |YDN conversion 111111|
|11        |YDN conversion 111110|
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
  `ptu`.`source` = 'ydn';
/* Result from query above:
|campaign_id|campaign_name|utm_campaign|phone_number |
|11         |Campaign Name|11          |+841234567811|
|12         |Campaign Name|12          |+841234567812|
 */


SELECT
  `total`.`account_id`,
  `total`.`accountId`,
  `total`.`campaignID`,
  SUM(`total`.`impressions`) AS impressions,
  SUM(`total`.`clicks`) AS clicks,
  SUM(`total`.`cost`) AS cost,
  AVG(`total`.`ctr`) AS ctr,
  AVG(`total`.`averageCpc`) AS cpc,
  /* add the expressions for the conversionName columns */
  SUM(`conv1`.`conversions`) AS 'YDN conversion 111110 CV',
  SUM(`conv2`.`conversions`) AS 'YDN conversion 111111 CV',
  SUM(`conv3`.`conversions`) AS 'YDN conversion 111111 CV',
  /* add the expressions for the AG campaign_name/phone_number columns */
  COUNT(`conv4`.`id`) AS 'Campaign Name +841234567811 CV',
  COUNT(`conv5`.`id`) AS 'Campaign Name +841234567812 CV',
  COUNT(`conv4`.`id`) + COUNT(`conv5`.`id`) AS call_cv,
  SUM(`total`.`conversions`) AS webcv,
  SUM(`total`.`conversions`) + COUNT(`conv4`.`id`) + COUNT(`conv5`.`id`) AS cv,
  ((SUM(`total`.`conversions`) + COUNT(`conv4`.`id`) + COUNT(`conv5`.`id`)) / SUM(`total`.`clicks`)) * 100 AS cvr,
  SUM(`total`.`cost`) / (SUM(`total`.`conversions`) + COUNT(`conv4`.`id`) + COUNT(`conv5`.`id`)) AS cpa,
  AVG(`total`.`averagePosition`) AS avgPosition
FROM
  `repo_ydn_reports` AS total
    /* Add joins for every campaignID & conversionName combination */
    LEFT JOIN `repo_ydn_reports` AS conv1
      ON (
          `total`.`account_id` = `conv1`.`account_id`
        AND
          `total`.`accountId` = `conv1`.`accountId`
        AND
          `total`.`day` = `conv1`.`day`
        AND
          `total`.`campaignID` = `conv1`.`campaignID`
        AND
          `conv1`.`campaignID` = 12
        AND
          `conv1`.`conversionName` = 'YDN conversion 111110'
      )
    LEFT JOIN `repo_ydn_reports` AS conv2
      ON (
          `total`.`account_id` = `conv2`.`account_id`
        AND
          `total`.`accountId` = `conv2`.`accountId`
        AND
          `total`.`day` = `conv2`.`day`
        AND
          `total`.`campaignID` = `conv2`.`campaignID`
        AND
          `conv2`.`campaignID` = 11
        AND
          `conv2`.`conversionName` = 'YDN conversion 111111'
      )
    LEFT JOIN `repo_ydn_reports` AS conv3
      ON (
          `total`.`account_id` = `conv3`.`account_id`
        AND
          `total`.`accountId` = `conv3`.`accountId`
        AND
          `total`.`day` = `conv3`.`day`
        AND
          `total`.`campaignID` = `conv3`.`campaignID`
        AND
          `conv3`.`campaignID` = 11
        AND
          `conv3`.`conversionName` = 'YDN conversion 111110'
      )
    /* Add joins for every AG campaign & phone_number combination */
    LEFT JOIN `repo_phone_time_use` AS conv4
      ON (
          `total`.`account_id` = `conv4`.`account_id`
        AND
          `total`.`campaign_id` = `conv4`.`campaign_id`
        AND
          `total`.`campaignID` = `conv4`.`utm_campaign`
        AND
          `total`.`day` = STR_TO_DATE(`conv4`.`time_of_call`, '%Y-%m-%d')
        AND
          `conv4`.`utm_campaign` = 11
        AND
          `conv4`.`phone_number` = '+841234567811'
        AND
          `conv4`.`source` = 'ydn'
      )
    LEFT JOIN `repo_phone_time_use` AS conv5
      ON (
          `total`.`account_id` = `conv5`.`account_id`
        AND
          `total`.`campaign_id` = `conv5`.`campaign_id`
        AND
          `total`.`campaignID` = `conv5`.`utm_campaign`
        AND
          `total`.`day` = STR_TO_DATE(`conv5`.`time_of_call`, '%Y-%m-%d')
        AND
          `conv5`.`utm_campaign` = 12
        AND
          `conv5`.`phone_number` = '+841234567812'
        AND
          `conv5`.`source` = 'ydn'
      )
WHERE
  `total`.`account_id` = 1
AND
  `total`.`accountId` = 11
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <= '2017-12-31'
GROUP BY
  `total`.`account_id`,
  `total`.`accountId`,
  `total`.`campaignID`

/* Execution time without indexes: 659 seconds */
/* Execution time with index on repo_ydn_report: account_id and accountId: 616 seconds */
/* Execution time with index on repo_ydn_report: account_id, accountId and day: 258 seconds */
/* Execution time with index on repo_ydn_report: account_id, accountId, campaignID, conversionName and day: 254 seconds */
/* Execution time with index on repo_ydn_report: account_id, accountId, campaignID, conversionName and day:
   phone_time_use: account_id, campaign_id, utm_campaign, phone_number, time_of_call: 92 seconds */
