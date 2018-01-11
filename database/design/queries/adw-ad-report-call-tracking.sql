/* get all unique conversion name of adw ad */
SELECT
	DISTINCT
adId,
conversionName
FROM
  `repo_adw_ad_report_conv`
WHERE
  customerId = 11
AND
  campaignId = 11
AND
  adGroupId = 1;

/*
adId	conversionName
1	Conversion name 1
1	Conversion name 2
2	Conversion name 1
2	Conversion name 2
3	Conversion name 1
3	Conversion name 2
*/

/* get all phone number */
SELECT
  DISTINCT
    `c`.`campaign_id`,
  	`c`.`campaign_name`,
    `ptu`.`utm_campaign`,
    `ptu`.`phone_number`
FROM
  `campaigns` as c,
  `phone_time_use` as ptu
WHERE
  `c`.`account_id` = `ptu`.`account_id`
AND
  `c`.`campaign_id` = `ptu`.`campaign_id`
AND
  `ptu`.`source` = 'adw'
AND
  `ptu`.`traffic_type` = 'AD'
AND
  `ptu`.`account_id` = 1
AND
  `ptu`.`utm_campaign` = 11
AND
  (
    (
        `c`.`camp_custom1` = 'creative'
      AND
        `ptu`.`custom1` IN (1, 2, 3)
    )
    OR
    (
        `c`.`camp_custom2` = 'creative'
      AND
        `ptu`.`custom2` IN (1, 2, 3)
    )
    OR
    (
        `c`.`camp_custom3` = 'creative'
      AND
        `ptu`.`custom3` IN (1, 2, 3)
    )
    OR
    (
        `c`.`camp_custom4` = 'creative'
      AND
        `ptu`.`custom4` IN (1, 2, 3)
    )
    OR
    (
        `c`.`camp_custom5` = 'creative'
      AND
        `ptu`.`custom5` IN (1, 2, 3)
    )
    OR
    (
        `c`.`camp_custom6` = 'creative'
      AND
        `ptu`.`custom6` IN (1, 2, 3)
    )
    OR
    (
        `c`.`camp_custom7` = 'creative'
      AND
        `ptu`.`custom7` IN (1, 2, 3)
    )
    OR
    (
        `c`.`camp_custom8` = 'creative'
      AND
        `ptu`.`custom8` IN (1, 2, 3)
    )
    OR
    (
        `c`.`camp_custom9` = 'creative'
      AND
        `ptu`.`custom9` IN (1, 2, 3)
    )
    OR
    (
        `c`.`camp_custom10` = 'creative'
      AND
        `ptu`.`custom10` IN (1, 2, 3)
    )
  );

/* result of this query
campaign_id	campaign_name	utm_campaign	phone_number
11	Campaign Name	11	+841234567811
*/

SELECT
  `total`.`customerId`,
  `total`.`campaignId`,
  `total`.`adGroupId`,
  `total`.`adId`,
  SUM(`conv1`.`conversions`) as 'ADW Conversion Name 1 CV',
  SUM(`conv2`.`conversions`) as 'ADW Conversion Name 2 CV',
  COUNT(`ptu1`.`id`) as 'Phone number +841234567811 CV',
  SUM(`total`.`conversions`) as web_cv,
  COUNT(`ptu1`.`id`) as call_cv
FROM
  `repo_adw_ad_report_cost` as total
LEFT JOIN
  `repo_adw_ad_report_conv` as conv1
ON
    `total`.`account_id` = `conv1`.`account_id`
  AND
    `total`.`customerId` = `conv1`.`customerId`
  AND
    `total`.`campaignId` = `conv1`.`campaignId`
  AND
    `total`.`adGroupId` = `conv1`.`adGroupId`
  AND
    `total`.`adID` = `conv1`.`adID`
  AND
    `total`.`day` = `conv1`.`day`
  AND
    `conv1`.`conversionName` = 'Conversion name 1'
LEFT JOIN
  `repo_adw_ad_report_conv` as conv2
ON
    `total`.`account_id` = `conv2`.`account_id`
  AND
    `total`.`customerId` = `conv2`.`customerId`
  AND
    `total`.`campaign_id` = `conv2`.`campaign_id`
  AND
    `total`.`adGroupId` = `conv2`.`adGroupId`
  AND
    `total`.`adID` = `conv2`.`adID`
  AND
    `total`.`day` = `conv2`.`day`
  AND
    `conv2`.`conversionName` = 'Conversion name 2'
LEFT JOIN
  (`phone_time_use` as ptu1, `campaigns` as c1)
ON
  `c1`.`account_id` = `ptu1`.`account_id`
AND
  `c1`.`campaign_id` = `ptu1`.`campaign_id`
AND
  `ptu1`.`source` = 'adw'
AND
  `ptu1`.`traffic_type` = 'AD'
AND
  `ptu1`.`account_id` = 1
AND
  `ptu1`.`utm_campaign` = 11
AND
  `ptu1`.`phone_number` = '+841234567811'
AND
    (
        `c1`.`camp_custom1` = 'creative'
      AND
        `ptu1`.`custom1` = `total`.`adID`
    )
    OR
    (
        `c1`.`camp_custom2` = 'creative'
      AND
        `ptu1`.`custom2` = `total`.`adID`
    )
    OR
    (
        `c1`.`camp_custom3` = 'creative'
      AND
        `ptu1`.`custom3` = `total`.`adID`
    )
    OR
    (
        `c1`.`camp_custom4` = 'creative'
      AND
        `ptu1`.`custom4` = `total`.`adID`
    )
    OR
    (
        `c1`.`camp_custom5` = 'creative'
      AND
        `ptu1`.`custom5` = `total`.`adID`
    )
    OR
    (
        `c1`.`camp_custom6` = 'creative'
      AND
        `ptu1`.`custom6` = `total`.`adID`
    )
    OR
    (
        `c1`.`camp_custom7` = 'creative'
      AND
        `ptu1`.`custom7` = `total`.`adID`
    )
    OR
    (
        `c1`.`camp_custom8` = 'creative'
      AND
        `ptu1`.`custom8` = `total`.`adID`
    )
    OR
    (
        `c1`.`camp_custom9` = 'creative'
      AND
        `ptu1`.`custom9` = `total`.`adID`
    )
    OR
    (
        `c1`.`camp_custom10` = 'creative'
      AND
        `ptu1`.`custom10` = `total`.`adID`
    )
WHERE
  `total`.`account_id` = 1
AND
  `total`.`customerId` = 11
AND
  `total`.`campaignId` = 11
AND
  `total`.`adGroupId` = 1
AND
	`total`.`day` >= '2017-01-01'
AND
	`total`.`day` <= '2017-02-01'
GROUP BY
  `total`.`customerId`,
  `total`.`campaignId`,
  `total`.`adGroupId`,
  `total`.`adId`;
