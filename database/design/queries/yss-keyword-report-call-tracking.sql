/* Get all unique conversion names */
SELECT
  DISTINCT
  keyword,
  adgroupID,
  conversionName
FROM
  `repo_yss_keyword_report_conv`
WHERE
  account_id = 1
AND
  adgroupID = 11111111;
/* Result from query above:
|keyword  |conversionName       |
|Keyword 0|YSS conversion 111110|
|Keyword 1|YSS conversion 111111|
|Keyword 2|YSS conversion 111112|
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
  (
    (
      `c`.`camp_custom1` = 'adgroupid'
    AND
      `ptu`.`custom1` = 11111111
    )
  OR
    (
      `c`.`camp_custom2` = 'adgroupid'
    AND
      `ptu`.`custom2` = 11111111
    )
  OR
    (
      `c`.`camp_custom3` = 'adgroupid'
    AND
      `ptu`.`custom3` = 11111111
    )
  OR
    (
      `c`.`camp_custom4` = 'adgroupid'
    AND
      `ptu`.`custom4` = 11111111
    )
  OR
    (
      `c`.`camp_custom5` = 'adgroupid'
    AND
      `ptu`.`custom5` = 11111111
    )
  OR
    (
      `c`.`camp_custom6` = 'adgroupid'
    AND
      `ptu`.`custom6` = 11111111
    )
  OR
    (
      `c`.`camp_custom7` = 'adgroupid'
    AND
      `ptu`.`custom7` = 11111111
    )
  OR
    (
      `c`.`camp_custom8` = 'adgroupid'
    AND
      `ptu`.`custom8` = 11111111
    )
  OR
    (
      `c`.`camp_custom9` = 'adgroupid'
    AND
      `ptu`.`custom9` = 11111111
    )
  OR
    (
      `c`.`camp_custom10` = 'adgroupid'
    AND
      `ptu`.`custom10` = 11111111
    )
  )
AND
  `c`.`account_id` = 1
AND
  `ptu`.`utm_campaign` = 11
AND
  `ptu`.`source` = 'yss';
/* Result from query above:
|campaign_id|campaign_name|utm_campaign|phone_number |
|11         |Campaign Name|11          |+841234567811|
*/

SELECT
  `total`.`account_id`,
  `total`.`accountid`,
  `total`.`campaignID`,
  `total`.`adgroupID`,
  `total`.`keyword`,
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
  `repo_yss_keyword_report_cost` AS total
  LEFT JOIN `repo_yss_keyword_report_conv` AS conv0
    ON (
      `conv0`.`conversionName` = 'YSS conversion 111110'
    AND
      `total`.`day` = `conv0`.`day`
    AND
      `total`.`keywordID` = `conv0`.`keywordID`
    AND
      `total`.`adgroupID` = `conv0`.`adgroupID`
    AND
      `total`.`campaignID` = `conv0`.`campaignID`
    AND
      `total`.`accountid` = `conv0`.`accountid`
    )
  LEFT JOIN `repo_yss_keyword_report_conv` AS conv1
    ON (
      `conv1`.`conversionName` = 'YSS conversion 111111'
    AND
      `total`.`day` = `conv1`.`day`
    AND
      `total`.`keywordID` = `conv1`.`keywordID`
    AND
      `total`.`adgroupID` = `conv1`.`adgroupID`
    AND
      `total`.`campaignID` = `conv1`.`campaignID`
    AND
      `total`.`accountid` = `conv1`.`accountid`
    )
  LEFT JOIN `repo_yss_keyword_report_conv` AS conv2
    ON (
      `conv2`.`conversionName` = 'YSS conversion 111112'
    AND
      `total`.`day` = `conv2`.`day`
    AND
      `total`.`keywordID` = `conv2`.`keywordID`
    AND
      `total`.`adgroupID` = `conv2`.`adgroupID`
    AND
      `total`.`campaignID` = `conv2`.`campaignID`
    AND
      `total`.`accountid` = `conv2`.`accountid`
    )
  LEFT JOIN (`campaigns` AS call0_camp, `phone_time_use` AS call0_ptu)
    ON (
    `call0_camp`.`account_id` = `total`.`account_id`
    AND
    `call0_camp`.`campaign_id` = `total`.`campaign_id`
    AND
    (
      (
        `call0_camp`.`camp_custom1` = 'adgroupid'
        AND
        `call0_ptu`.`custom1` = `total`.`adgroupID`
      )
      OR
      (
        `call0_camp`.`camp_custom2` = 'adgroupid'
        AND
        `call0_ptu`.`custom2` = `total`.`adgroupID`
      )
      OR
      (
        `call0_camp`.`camp_custom3` = 'adgroupid'
        AND
        `call0_ptu`.`custom3` = `total`.`adgroupID`
      )
      OR
      (
        `call0_camp`.`camp_custom4` = 'adgroupid'
        AND
        `call0_ptu`.`custom4` = `total`.`adgroupID`
      )
      OR
      (
        `call0_camp`.`camp_custom5` = 'adgroupid'
        AND
        `call0_ptu`.`custom5` = `total`.`adgroupID`
      )
      OR
      (
        `call0_camp`.`camp_custom6` = 'adgroupid'
        AND
        `call0_ptu`.`custom6` = `total`.`adgroupID`
      )
      OR
      (
        `call0_camp`.`camp_custom7` = 'adgroupid'
        AND
        `call0_ptu`.`custom7` = `total`.`adgroupID`
      )
      OR
      (
        `call0_camp`.`camp_custom8` = 'adgroupid'
        AND
        `call0_ptu`.`custom8` = `total`.`adgroupID`
      )
      OR
      (
        `call0_camp`.`camp_custom9` = 'adgroupid'
        AND
        `call0_ptu`.`custom9` = `total`.`adgroupID`
      )
      OR
      (
        `call0_camp`.`camp_custom10` = 'adgroupid'
        AND
        `call0_ptu`.`custom10` = `total`.`adgroupID`
      )
    )
    AND
      `call0_ptu`.`account_id` = `total`.`account_id`
    AND
      `call0_ptu`.`campaign_id` = `total`.`campaign_id`
    AND
      `call0_ptu`.`utm_campaign` = `total`.`campaignID`
    AND
      STR_TO_DATE(`call0_ptu`.`time_of_call`, '%Y-%m-%d') = `total`.`day`
    AND
      `call0_ptu`.`source` = 'yss'
    AND
      `call0_ptu`.`matchtype` = `total`.`keywordMatchType`
    AND
      `call0_ptu`.`j_keyword` = `total`.`keyword`
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
  `total`.`adgroupID` = 11111111
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <= '2017-12-01'
GROUP BY
  `total`.`account_id`,
  `total`.`accountid`,
  `total`.`campaignID`,
  `total`.`adgroupID`,
  `total`.`keyword`;
