/* Get all unique conversion names */
SELECT
  DISTINCT
  DAYNAME(`repo_ydn_reports`.`day`),
  `repo_ydn_reports`.`conversionName`,
  `repo_ydn_reports`.`campaignID`
FROM
  `repo_ydn_reports`
WHERE
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`campaign_id` = 11
AND
  `repo_ydn_reports`.`accountid` = 11;

/*Query result:
|DAYNAME(`repo_ydn_reports`.`day`)|conversionName       |campaignID|
|Sunday                           |YDN conversion 111110|11        |
|Sunday                           |YDN conversion 111111|11        |
|Monday                           |YDN conversion 111110|11        |
|Monday                           |YDN conversion 111111|11        |
|Tuesday                          |YDN conversion 111110|11        |
|Tuesday                          |YDN conversion 111111|11        |
|Wednesday                        |YDN conversion 111110|11        |
|Wednesday                        |YDN conversion 111111|11        |
|Thursday                         |YDN conversion 111110|11        |
|Thursday                         |YDN conversion 111111|11        |
|Friday                           |YDN conversion 111110|11        |
|Friday                           |YDN conversion 111111|11        |
|Saturday                         |YDN conversion 111110|11        |
|Saturday                         |YDN conversion 111111|11        |
*/

/* Get all AG campaigns with phone number */
SELECT
  DISTINCT
  `repo_phone_time_use`.`account_id`,
  `repo_phone_time_use`.`utm_campaign`,
  `repo_phone_time_use`.`phone_number`,
  DAYNAME(`repo_phone_time_use`.`time_of_call`) as dayOfWeek
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
|account_id|utm_campaign|phone_number |dayOfWeek|
|1         |11          |+841234567811|Wednesday|
|1         |11          |+841234567811|Monday   |
|1         |11          |+841234567811|Tuesday  |
|1         |11          |+841234567811|Saturday |
|1         |11          |+841234567811|Friday   |
|1         |11          |+841234567811|Thursday |
|1         |11          |+841234567811|Sunday   |
*/

SELECT
  DAYNAME(`total`.`day`),
  SUM(`total`.`impressions`) AS impressions,
  SUM(`total`.`clicks`) AS clicks,
  SUM(`total`.`cost`) AS cost,
  AVG(`total`.`ctr`) AS ctr,
  AVG(`total`.`averageCpc`) AS cpc,
  /* add the expressions for the conversionName columns */
  SUM(`conv1`.`conversions`) AS 'YDN conversion 111110 CV',
  SUM(`conv2`.`conversions`) AS 'YDN conversion 111111 CV',
  /* add the expressions for the AG campaign_name/phone_number columns */
  COUNT(`conv3`.`id`) AS 'Campaign Name +841234567811 CV',
  COUNT(`conv3`.`id`) AS call_cv,
  SUM(`total`.`conversions`) AS webcv,
  SUM(`total`.`conversions`) + COUNT(`conv3`.`id`) AS cv,
  ((SUM(`total`.`conversions`) + COUNT(`conv3`.`id`)) / SUM(`total`.`clicks`)) * 100 AS cvr,
  SUM(`total`.`cost`) / (SUM(`total`.`conversions`) + COUNT(`conv3`.`id`)) AS cpa,
  AVG(`total`.`averagePosition`) AS avgPosition
FROM
  `repo_ydn_reports` AS total
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
      DAYNAME(`phone_time_use`.`time_of_call`) = DAYNAME(`total`.`day`)
  )
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
          DAYNAME(`total`.`day`) = DAYNAME(`conv1`.`day`)
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
          DAYNAME(`total`.`day`) = DAYNAME(`conv2`.`day`)
        AND
          `conv2`.`conversionName` = 'YDN conversion 111111'
      )
    -- /* Add joins for every AG campaign & phone_number combination */
    LEFT JOIN `repo_phone_time_use` AS conv3
      ON (
          `total`.`account_id` = `conv3`.`account_id`
        AND
          `total`.`campaign_id` = `conv3`.`campaign_id`
        AND
          `total`.`campaignID` = `conv3`.`utm_campaign`
        AND
          `total`.`day` = STR_TO_DATE(`conv3`.`time_of_call`, '%Y-%m-%d')
        AND
          `conv3`.`utm_campaign` IN (11, 12, 13)
        AND
          `conv3`.`phone_number` = '+841234567811'
        AND
          `conv3`.`source` = 'ydn'
        AND
          `conv3`.`traffic_type` = 'AD'
      )
WHERE
  `total`.`account_id` = 1
AND
  `total`.`campaign_id` = 11
AND
  `total`.`accountId` = 11
AND
  `total`.`campaignID` = 11
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <= '2017-12-31'
GROUP BY
  `total`.`account_id`,
  `total`.`accountId`,
  `total`.`campaignID`,
  DAYNAME(`total`.`day`)
/*Execution time: 38 seconds*/
