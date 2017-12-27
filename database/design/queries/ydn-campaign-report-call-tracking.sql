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
  campaign_id,
  phone_number
FROM
  `phone_time_use`
WHERE
  account_id = 1;


SELECT
  `total`.`account_id`,
  `total`.`accountId`,
  `total`.`campaignID`,
  SUM(`total`.`impressions`) AS impressions,
  SUM(`total`.`clicks`) AS clicks,
  SUM(`total`.`cost`) AS cost,
  AVG(`total`.`ctr`) AS ctr,
  AVG(`total`.`averageCpc`) AS cpc,
  /* TODO: add the expressions for the conversionName columns */
  SUM(`conv1`.`conversions`) AS "YDN conversion 111110 CV",
  SUM(`conv2`.`conversions`) AS "YDN conversion 111111 CV",
  /* TODO: add the expressions for the AG campaign_name/phone_number columns */
  COUNT(`total_call`.`id`) AS call_cv,
  SUM(`total`.`conversions`) AS webcv,
  SUM(`total`.`conversions`) + COUNT(`total_call`.`id`) AS cv,
  ((SUM(`total`.`conversions`) + COUNT(`total_call`.`id`)) / SUM(`total`.`clicks`)) * 100 AS cvr,
  SUM(`total`.`cost`) / (SUM(`total`.`conversions`) + COUNT(`total_call`.`id`)) AS cpa,
  AVG(`total`.`averagePosition`) AS avgPosition
FROM
  `repo_ydn_reports` AS total
    LEFT JOIN `phone_time_use` AS total_call
      ON (
          `total_call`.`account_id` = `total`.`account_id`
        AND
          `total_call`.`campaign_id` = `total`.`campaign_id`
        AND
          `total_call`.`utm_campaign` = `total`.`campaignID`
        AND
          STR_TO_DATE(`total_call`.`time_of_call`, '%Y-%m-%d') = `total`.`day`
        AND
          `total_call`.`source` = 'ydn'
        AND
          `total_call`.`traffic_type` = 'AD'
      )
    /* TODO: Add joins for every campaignID & conversionName combination */
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
    /* TODO: Add joins for every AG campaign & phone_number combination */
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
