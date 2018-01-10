/* Get all unique conversion names */
SELECT
  DISTINCT
  `adgroupID`,
  `conversionName`
FROM
  `repo_yss_adgroup_report_conv`
WHERE
  `account_id` = 1
  AND
  `accountid` = 11
  AND
  `campaignID` = 11;
/* Query result:
|adgroupID|conversionName       |
|11111111 |YSS conversion 111110|
|11111111 |YSS conversion 111111|
|11111111 |YSS conversion 111112|
|11111112 |YSS conversion 111110|
|11111112 |YSS conversion 111111|
|11111112 |YSS conversion 111112|
|11111113 |YSS conversion 111110|
|11111113 |YSS conversion 111111|
|11111113 |YSS conversion 111112|
|11111114 |YSS conversion 111110|
|11111114 |YSS conversion 111111|
|11111114 |YSS conversion 111112|
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
  `ptu`.`utm_campaign` = 11
  AND
  `ptu`.`source` = 'yss';
/* Query results:
|campaign_id|campaign_name|utm_campaign|phone_number |
|11         |Campaign Name|11          |+841234567811|
*/

SELECT
  `total`.`account_id`,
  `total`.`accountid`,
  `total`.`campaignID`,
  `total`.`adgroupID`,
  IFNULL(SUM(`total`.`impressions`), 0) AS impressions,
  IFNULL(SUM(`total`.`clicks`), 0) AS clicks,
  IFNULL(SUM(`total`.`cost`), 0) AS cost,
  IFNULL(AVG(`total`.`ctr`), 0) AS ctr,
  IFNULL(AVG(`total`.`averageCpc`), 0) AS avgCPC,
  IFNULL(AVG(`total`.`averagePosition`), 0) AS avgPosition,
  IFNULL(SUM(`conv0`.`conversions`), 0) AS 'YSS conversion 111110 CV',
  IFNULL((SUM(`conv0`.`conversions`) / SUM(`total`.`clicks`)) * 100, 0) AS 'YSS conversion 111110 CVR',
  IFNULL((SUM(`total`.`cost`) / SUM(`conv0`.`conversions`)) * 100, 0) AS 'YSS conversion 111110 CPA',
  IFNULL(SUM(`conv1`.`conversions`), 0) AS 'YSS conversion 111111 CV',
  IFNULL((SUM(`conv1`.`conversions`) / SUM(`total`.`clicks`)) * 100, 0) AS 'YSS conversion 111111 CVR',
  IFNULL((SUM(`total`.`cost`) / SUM(`conv1`.`conversions`)) * 100, 0) AS 'YSS conversion 111111 CPA',
  IFNULL(SUM(`conv2`.`conversions`), 0) AS 'YSS conversion 111112 CV',
  IFNULL((SUM(`conv2`.`conversions`) / SUM(`total`.`clicks`)) * 100, 0) AS 'YSS conversion 111112 CVR',
  IFNULL((SUM(`total`.`cost`) / SUM(`conv2`.`conversions`)) * 100, 0) AS 'YSS conversion 111112 CPA',
  IFNULL(`total`.`conversions`, 0) AS web_cv,
  IFNULL(`total`.`conversions` / `total`.`clicks`, 0) AS web_cvr,
  IFNULL(`total`.`cost` / `total`.`conversions`, 0) AS web_cpa,
  IFNULL(COUNT(`call0_ptu`.`id`), 0) AS 'Campaign Name +841234567811 CV',
  IFNULL((COUNT(`call0_ptu`.`id`) / `total`.`clicks`) * 100, 0) AS 'Campaign Name +841234567811 CVR',
  IFNULL(`total`.`cost` / COUNT(`call0_ptu`.`id`), 0) AS 'Campaign Name +841234567811 CPA',
  IFNULL(COUNT(`call0_ptu`.`id`), 0) AS call_cv,
  IFNULL((COUNT(`call0_ptu`.`id`) / `total`.`clicks`) * 100, 0) AS call_cvr,
  IFNULL(`total`.`cost` / COUNT(`call0_ptu`.`id`), 0) AS call_cpa,
  IFNULL(`total`.`conversions` + COUNT(`call0_ptu`.`id`), 0) AS total_cv,
  IFNULL((`total`.`conversions` + COUNT(`call0_ptu`.`id`)) / `total`.`clicks`, 0) AS total_cvr,
  IFNULL(`total`.`cost` / (`total`.`conversions` + COUNT(`call0_ptu`.`id`)), 0) AS total_cpa
FROM
  `repo_yss_adgroup_report_cost` AS total
  /* Add joins for the conversion points */
    LEFT JOIN `repo_yss_adgroup_report_conv` AS conv0
      ON (
          `conv0`.`conversionName` = 'YSS conversion 111110'
        AND
          `total`.`day` = `conv0`.`day`
        AND
          `conv0`.`adgroupID` IN (11111111, 11111112, 11111113, 11111114)
        AND
          `total`.`campaignID` = `conv0`.`campaignID`
        AND
          `total`.`accountid` = `conv0`.`accountid`
        AND
          `total`.`account_id` = `conv0`.`account_id`
      )
    LEFT JOIN `repo_yss_adgroup_report_conv` AS conv1
      ON (
          `conv1`.`conversionName` = 'YSS conversion 111111'
        AND
          `total`.`day` = `conv1`.`day`
        AND
          `conv1`.`adgroupID` IN (11111111, 11111112, 11111113, 11111114)
        AND
          `total`.`campaignID` = `conv1`.`campaignID`
        AND
          `total`.`accountid` = `conv1`.`accountid`
        AND
          `total`.`account_id` = `conv1`.`account_id`
      )
    LEFT JOIN `repo_yss_adgroup_report_conv` AS conv2
      ON (
          `conv2`.`conversionName` = 'YSS conversion 111112'
        AND
          `total`.`day` = `conv2`.`day`
        AND
          `conv2`.`adgroupID` IN (11111111, 11111112, 11111113, 11111114)
        AND
          `total`.`campaignID` = `conv2`.`campaignID`
        AND
          `total`.`accountid` = `conv2`.`accountid`
        AND
          `total`.`account_id` = `conv2`.`account_id`
      )
    /* Add joins for every AG campaign & phone_number combination */
    LEFT JOIN (`campaigns` AS call0_campaigns, `phone_time_use` AS call0_ptu)
      ON (
          `call0_campaigns`.`campaign_id` = `total`.`campaign_id`
        AND
          `call0_campaigns`.`account_id` = `total`.`account_id`
        AND
          (
            (
              `call0_campaigns`.`camp_custom1` = 'adgroupid'
            AND
              `call0_ptu`.`custom1` = `total`.`adgroupID`
            )
          OR
            (
              `call0_campaigns`.`camp_custom2` = 'adgroupid'
            AND
              `call0_ptu`.`custom2` = `total`.`adgroupID`
            )
          OR
            (
              `call0_campaigns`.`camp_custom3` = 'adgroupid'
            AND
              `call0_ptu`.`custom3` = `total`.`adgroupID`
            )
          OR
            (
              `call0_campaigns`.`camp_custom4` = 'adgroupid'
            AND
              `call0_ptu`.`custom4` = `total`.`adgroupID`
            )
          OR
            (
              `call0_campaigns`.`camp_custom5` = 'adgroupid'
            AND
              `call0_ptu`.`custom5` = `total`.`adgroupID`
            )
          OR
            (
              `call0_campaigns`.`camp_custom6` = 'adgroupid'
            AND
              `call0_ptu`.`custom6` = `total`.`adgroupID`
            )
          OR
            (
              `call0_campaigns`.`camp_custom7` = 'adgroupid'
            AND
              `call0_ptu`.`custom7` = `total`.`adgroupID`
            )
          OR
            (
              `call0_campaigns`.`camp_custom8` = 'adgroupid'
            AND
              `call0_ptu`.`custom8` = `total`.`adgroupID`
            )
          OR
            (
              `call0_campaigns`.`camp_custom9` = 'adgroupid'
            AND
              `call0_ptu`.`custom9` = `total`.`adgroupID`
            )
          OR
            (
              `call0_campaigns`.`camp_custom10` = 'adgroupid'
            AND
              `call0_ptu`.`custom10` = `total`.`adgroupID`
            )
          )
        AND
          `call0_ptu`.`utm_campaign` = `total`.`campaignID`
        AND
          `call0_ptu`.`account_id` = `total`.`account_id`
        AND
          `call0_ptu`.`campaign_id` = `total`.`campaign_id`
        AND
          STR_TO_DATE(`call0_ptu`.`time_of_call`, '%Y-%m-%d') = `total`.`day`
        AND
          `call0_ptu`.`phone_number` = '+841234567811'
        AND
          `call0_ptu`.`source` = 'yss'
        AND
          `call0_ptu`.`traffic_type` = 'AD'
      )
WHERE
  `total`.`account_id` = 1
AND
  `total`.`accountid` = 11
AND
  `total`.`campaignID` = 11
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <= '2017-01-02'
GROUP BY
  `total`.`account_id`,
  `total`.`accountid`,
  `total`.`campaignID`,
  `total`.`adgroupID`
