/* Get all unique conversion names */
SELECT
  DISTINCT
  `repo_ydn_reports`.`hourofday`,
  `repo_ydn_reports`.`conversionName`,
  `repo_ydn_reports`.`campaignID`
FROM
  `repo_ydn_reports`
WHERE
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`campaign_id` = 11
AND
  `repo_ydn_reports`.`accountid` = 11
AND
  `repo_ydn_reports`.`campaignID` = 11;
/*Query result:
|hourofday  |conversionName       |campaignID|
|14         |YDN conversion 111110|11        |
|8          |YDN conversion 111111|11        |
|2          |YDN conversion 111110|11        |
|0          |YDN conversion 111111|11        |
|11         |YDN conversion 111110|11        |
|11         |YDN conversion 111111|11        |
|5          |YDN conversion 111110|11        |
|17         |YDN conversion 111111|11        |
|10         |YDN conversion 111111|11        |
|0          |YDN conversion 111110|11        |
|15         |YDN conversion 111111|11        |
|16         |YDN conversion 111110|11        |
|4          |YDN conversion 111111|11        |
|5          |YDN conversion 111111|11        |
|22         |YDN conversion 111111|11        |
|9          |YDN conversion 111110|11        |
|20         |YDN conversion 111111|11        |
|19         |YDN conversion 111110|11        |
|2          |YDN conversion 111111|11        |
|21         |YDN conversion 111110|11        |
|20         |YDN conversion 111110|11        |
|3          |YDN conversion 111110|11        |
|3          |YDN conversion 111111|11        |
|12         |YDN conversion 111111|11        |
|12         |YDN conversion 111110|11        |
*/

/* Get all AG campaigns with phone number */
SELECT
  DISTINCT
  `repo_phone_time_use`.`account_id`,
  `repo_phone_time_use`.`utm_campaign`,
  `repo_phone_time_use`.`phone_number`,
  HOUR(`repo_phone_time_use`.`time_of_call`) as hourOfDay
FROM
  `repo_phone_time_use`
WHERE
  `repo_phone_time_use`.`account_id` = 1
AND
  `repo_phone_time_use`.`campaign_id` = 11
AND
  `repo_phone_time_use`.`utm_campaign` = 11
AND
  `repo_phone_time_use`.`source` = 'ydn'
AND
  `repo_phone_time_use`.`traffic_type` = 'AD';
/*Query result:
|account_id |utm_campaign   |phone_number |hourOfDay|
|1          |11             |+841234567811|20       |
|1          |11             |+841234567811|13       |
|1          |11             |+841234567811|21       |
|1          |11             |+841234567811|22       |
|1          |11             |+841234567811|0        |
|1          |11             |+841234567811|2        |
|1          |11             |+841234567811|18       |
|1          |11             |+841234567811|8        |
|1          |11             |+841234567811|12       |
|1          |11             |+841234567811|5        |
|1          |11             |+841234567811|14       |
|1          |11             |+841234567811|11       |
*/
SELECT
    `total`.`hourofday`,
    SUM(`total`.`impressions`) AS impressions,
    SUM(`total`.`clicks`) AS clicks,
    SUM(`total`.`cost`) AS cost,
    AVG(`total`.`ctr`) AS ctr,
    AVG(`total`.`averageCpc`) AS cpc,
    /* add the expressions for the conversionName columns */
    SUM(`conv1`.`conversions`) AS 'YDN conversion 111110 CV',
    SUM(`conv2`.`conversions`) AS 'YDN conversion 111111 CV',
    /* add the expressions for the AG campaign_name/phone_number columns */
    COUNT(`phone_time_use`.`id`) AS 'Campaign Name +841234567811 CV',
    COUNT(`phone_time_use`.`id`) AS call_cv,
    SUM(`total`.`conversions`) AS webcv,
    SUM(`total`.`conversions`) + COUNT(`phone_time_use`.`id`) AS cv,
    ((SUM(`total`.`conversions`) + COUNT(`phone_time_use`.`id`)) / SUM(`total`.`clicks`)) * 100 AS cvr,
    SUM(`total`.`cost`) / (SUM(`total`.`conversions`) + COUNT(`phone_time_use`.`id`)) AS cpa,
    AVG(`total`.`averagePosition`) AS avgPosition
FROM
    `repo_ydn_reports` as total
    LEFT JOIN (`phone_time_use`, `campaigns`)
  ON (
      `campaigns`.`account_id` = `total`.`account_id`
    AND
      `campaigns`.`campaign_id` = `total`.`campaign_id`
    AND
      (
        (
          `campaigns`.`camp_custom1` = 'creative'
        AND
          `phone_time_use`.`custom1` = `total`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom2` = 'creative'
        AND
          `phone_time_use`.`custom2` = `total`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom3` = 'creative'
        AND
          `phone_time_use`.`custom3` = `total`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom4` = 'creative'
        AND
          `phone_time_use`.`custom4` = `total`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom5` = 'creative'
        AND
          `phone_time_use`.`custom5` = `total`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom6` = 'creative'
        AND
          `phone_time_use`.`custom6` = `total`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom7` = 'creative'
        AND
          `phone_time_use`.`custom7` = `total`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom8` = 'creative'
        AND
          `phone_time_use`.`custom8` = `total`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom9` = 'creative'
        AND
          `phone_time_use`.`custom9` = `total`.`adID`
        )
      OR
        (
          `campaigns`.`camp_custom10` = 'creative'
        AND
          `phone_time_use`.`custom10` = `total`.`adID`
        )
      )
    AND
      `phone_time_use`.`account_id` = `total`.`account_id`
    AND
      `phone_time_use`.`campaign_id` = `total`.`campaign_id`
    AND
      `phone_time_use`.`utm_campaign` = `total`.`campaignID`
    AND
      STR_TO_DATE(`phone_time_use`.`time_of_call`, '%Y-%m-%d') = `total`.`day`
    AND
      `phone_time_use`.`source` = 'ydn'
    AND
      `phone_time_use`.`traffic_type` = 'AD'
    AND
      HOUR(`phone_time_use`.`time_of_call`) = `total`.`hourofday`
    AND
      `phone_time_use`.`phone_number` = '+841234567811'
    )
    LEFT JOIN `repo_ydn_reports` AS conv1
    ON (
            `total`.`account_id` = `conv1`.`account_id`
        AND
            `total`.`accountId` = `conv1`.`accountId`
        AND
            `total`.`day` = `conv1`.`day`
        AND
            `total`.`hourofday` = `conv1`.`hourofday`
        AND
            `total`.`campaignID` = `conv1`.`campaignID`
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
            `total`.`hourofday` = `conv2`.`hourofday`
        AND
            `total`.`campaignID` = `conv2`.`campaignID`
        AND
            `conv2`.`conversionName` = 'YDN conversion 111111'
    )
WHERE
    `total`.`account_id` = 1
AND
    `total`.`campaign_id` = 11
AND
    `total`.`accountId` = 11
AND
    `total`.`campaignId` = 11
AND
    `total`.`day` >= '2017-01-01'
AND
    `total`.`day` <= '2017-12-01'
GROUP BY
    `total`.`account_id`,
    `total`.`campaign_id`,
    `total`.`accountId`,
    `total`.`campaignID`,
    `total`.`hourofday`
