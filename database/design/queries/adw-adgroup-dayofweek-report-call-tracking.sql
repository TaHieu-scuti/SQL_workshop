/* Done for converion name, working on phone number */
SELECT
  DISTINCT
  `repo_adw_adgroup_report_conv`.`adGroupID`,
  `repo_adw_adgroup_report_conv`.`conversionName`,
  `repo_adw_adgroup_report_conv`.`dayOfWeek`
FROM
  `repo_adw_adgroup_report_conv`
WHERE
  `repo_adw_adgroup_report_conv`.`account_id` = 1
AND
  `repo_adw_adgroup_report_conv`.`campaign_id` = 11
AND
  `repo_adw_adgroup_report_conv`.`customerID` = 11
AND
  `repo_adw_adgroup_report_conv`.`campaignID` = 11;
/*
adGroupID	conversionName	dayOfWeek
1	Conversion Name 1	Sunday
1	Conversion Name 2	Sunday
1	Conversion Name 3	Sunday
2	Conversion Name 1	Sunday
2	Conversion Name 2	Sunday
2	Conversion Name 3	Sunday
3	Conversion Name 1	Sunday
3	Conversion Name 2	Sunday
3	Conversion Name 3	Sunday
1	Conversion Name 1	Monday
1	Conversion Name 2	Monday
1	Conversion Name 3	Monday
2	Conversion Name 1	Monday
2	Conversion Name 2	Monday
2	Conversion Name 3	Monday
1	Conversion Name 1	Tuesday
1	Conversion Name 2	Tuesday
1	Conversion Name 3	Tuesday
2	Conversion Name 1	Tuesday
2	Conversion Name 2	Tuesday
2	Conversion Name 3	Tuesday
1	Conversion Name 1	Wednesday
1	Conversion Name 2	Wednesday
1	Conversion Name 3	Wednesday
2	Conversion Name 1	Wednesday
2	Conversion Name 2	Wednesday
2	Conversion Name 3	Wednesday
3	Conversion Name 1	Wednesday
3	Conversion Name 2	Wednesday
3	Conversion Name 3	Wednesday
1	Conversion Name 1	Thursday
1	Conversion Name 2	Thursday
1	Conversion Name 3	Thursday
2	Conversion Name 1	Thursday
2	Conversion Name 2	Thursday
2	Conversion Name 3	Thursday
3	Conversion Name 1	Thursday
3	Conversion Name 2	Thursday
3	Conversion Name 3	Thursday
1	Conversion Name 1	Friday
1	Conversion Name 2	Friday
1	Conversion Name 3	Friday
2	Conversion Name 1	Friday
2	Conversion Name 2	Friday
2	Conversion Name 3	Friday
3	Conversion Name 1	Friday
3	Conversion Name 2	Friday
3	Conversion Name 3	Friday
1	Conversion Name 1	Saturday
1	Conversion Name 2	Saturday
1	Conversion Name 3	Saturday
2	Conversion Name 1	Saturday
2	Conversion Name 2	Saturday
2	Conversion Name 3	Saturday
3	Conversion Name 1	Saturday
3	Conversion Name 2	Saturday
3	Conversion Name 3	Saturday
3	Conversion Name 1	Monday
3	Conversion Name 2	Monday
3	Conversion Name 3	Monday
3	Conversion Name 1	Tuesday
3	Conversion Name 2	Tuesday
3	Conversion Name 3	Tuesday
*/

SELECT
  DISTINCT
  `c`.`campaign_id`,
  `c`.`campaign_name`,
  `ptu`.`utm_campaign`,
  `ptu`.`phone_number`
FROM
  `phone_time_use` as ptu,
  `campaigns` as c
WHERE
  `ptu`.`account_id` = `c`.`account_id`
AND
  `ptu`.`campaign_id` = `c`.`campaign_id`
AND
  `ptu`.`source` = 'adw'
AND
  `ptu`.`traffic_type` = 'AD'
AND
  `ptu`.`account_id` = 1
AND
  `ptu`.`utm_campaign` = 11;

/*
campaign_id	campaign_name	utm_campaign	phone_number	DAYNAME(`ptu`.`time_of_call`)
11	Campaign Name	11	+841234567811	Sunday
11	Campaign Name	11	+841234567811	Saturday
11	Campaign Name	11	+841234567811	Tuesday
11	Campaign Name	11	+841234567811	Monday
11	Campaign Name	11	+841234567811	Friday
11	Campaign Name	11	+841234567811	Wednesday
11	Campaign Name	11	+841234567811	Thursday
*/

SELECT
  `tbl`.`dayOfWeek`,
  SUM(`Conversion Name 1 CV`),
  SUM(`Conversion Name 2 CV`),
  SUM(`Conversion Name 3 CV`),
  SUM(`Campaign Name +841234567811 CV`)
from
(SELECT
  `total`.`dayOfWeek`,
  SUM(`conv1`.`conversions`) as 'Conversion Name 1 CV',
  SUM(`conv2`.`conversions`) as 'Conversion Name 2 CV',
  SUM(`conv3`.`conversions`) as 'Conversion Name 3 CV',
  COUNT(`ptu1`.`id`) as 'Campaign Name +841234567811 CV'
FROM
  `repo_adw_adgroup_report_cost` as total
LEFT JOIN
  `repo_adw_adgroup_report_conv` as conv1
ON
  `total`.`account_id` = `conv1`.`account_id`
AND
  `total`.`campaign_id` = `conv1`.`campaign_id`
AND
  `total`.`customerID` = `conv1`.`customerID`
AND
  `total`.`campaignID` = `conv1`.`campaignID`
AND
  `total`.`adGroupID` = `conv1`.`adGroupID`
AND
  `total`.`day` = `conv1`.`day`
AND
  `total`.`dayOfWeek` = `conv1`.`dayOfWeek`
AND
  `total`.`network` = `conv1`.`network`
AND
  `conv1`.`conversionName` = 'Conversion Name 1'
LEFT JOIN
  `repo_adw_adgroup_report_conv` as conv2
ON
  `total`.`account_id` = `conv2`.`account_id`
AND
  `total`.`campaign_id` = `conv2`.`campaign_id`
AND
  `total`.`customerID` = `conv2`.`customerID`
AND
  `total`.`campaignID` = `conv2`.`campaignID`
AND
  `total`.`adGroupID` = `conv2`.`adGroupID`
AND
  `total`.`day` = `conv2`.`day`
AND
  `total`.`dayOfWeek` = `conv2`.`dayOfWeek`
AND
  `total`.`network` = `conv2`.`network`
AND
  `conv2`.`conversionName` = 'Conversion Name 2'
LEFT JOIN
  `repo_adw_adgroup_report_conv` as conv3
ON
  `total`.`account_id` = `conv3`.`account_id`
AND
  `total`.`campaign_id` = `conv3`.`campaign_id`
AND
  `total`.`customerID` = `conv3`.`customerID`
AND
  `total`.`campaignID` = `conv3`.`campaignID`
AND
  `total`.`adGroupID` = `conv3`.`adGroupID`
AND
  `total`.`day` = `conv3`.`day`
AND
  `total`.`dayOfWeek` = `conv3`.`dayOfWeek`
AND
  `total`.`network` = `conv3`.`network`
AND
  `conv3`.`conversionName` = 'Conversion Name 3'
LEFT JOIN
  (`phone_time_use` as ptu1, `campaigns` as c1)
ON
  `ptu1`.`account_id` = `c1`.`account_id`
AND
  `ptu1`.`campaign_id` = `c1`.`campaign_id`
AND
  `ptu1`.`account_id` = `total`.`account_id`
AND
  `ptu1`.`campaign_id` = `total`.`campaign_id`
AND
  `ptu1`.`utm_campaign` = `total`.`campaignID`
AND
  `ptu1`.`source` = 'adw'
AND
  `ptu1`.`traffic_type` = 'AD'
AND
  `ptu1`.`time_of_call` LIKE CONCAT(`total`.`day`, '%')
AND
  DAYNAME(`ptu1`.`time_of_call`) = `total`.`dayOfWeek`
AND
  `ptu1`.`phone_number` = '+841234567811'
AND
  (
    (
        `c1`.`camp_custom1` = 'adgroupid'
      AND
        `ptu1`.`custom1` = `total`.`adGroupID`
    )
    OR
    (
        `c1`.`camp_custom2` = 'adgroupid'
      AND
        `ptu1`.`custom2` = `total`.`adGroupID`
    )
    OR
    (
        `c1`.`camp_custom3` = 'adgroupid'
      AND
        `ptu1`.`custom3` = `total`.`adGroupID`
    )
    OR
    (
        `c1`.`camp_custom4` = 'adgroupid'
      AND
        `ptu1`.`custom4` = `total`.`adGroupID`
    )
    OR
    (
        `c1`.`camp_custom5` = 'adgroupid'
      AND
        `ptu1`.`custom5` = `total`.`adGroupID`
    )
    OR
    (
        `c1`.`camp_custom6` = 'adgroupid'
      AND
        `ptu1`.`custom6` = `total`.`adGroupID`
    )
    OR
    (
        `c1`.`camp_custom7` = 'adgroupid'
      AND
        `ptu1`.`custom7` = `total`.`adGroupID`
    )
    OR
    (
        `c1`.`camp_custom8` = 'adgroupid'
      AND
        `ptu1`.`custom8` = `total`.`adGroupID`
    )
    OR
    (
        `c1`.`camp_custom9` = 'adgroupid'
      AND
        `ptu1`.`custom9` = `total`.`adGroupID`
    )
    OR
    (
        `c1`.`camp_custom10` = 'adgroupid'
      AND
        `ptu1`.`custom10` = `total`.`adGroupID`
    )
  )
WHERE
  `total`.`account_id` = 1
AND
  `total`.`campaign_id` = 11
AND
  `total`.`customerID` = 11
AND
  `total`.`campaignID` = 11
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <= '2017-04-01'
AND
  `total`.`network` = 'SEARCH'
GROUP BY
  `total`.`account_id`,
  `total`.`campaign_id`,
  `total`.`customerID`,
  `total`.`campaignID`,
  `total`.`dayOfWeek`

UNION

SELECT
  `total`.`dayOfWeek`,
  SUM(`conv1`.`conversions`) as 'Conversion Name 1 CV',
  SUM(`conv2`.`conversions`) as 'Conversion Name 2 CV',
  SUM(`conv3`.`conversions`) as 'Conversion Name 3 CV',
  COUNT(`ptu1`.`id`) as 'Campaign Name +841234567811 CV'
FROM
  `repo_adw_ad_report_cost` as total
LEFT JOIN
  `repo_adw_ad_report_conv` as conv1
  ON
      `total`.`account_id` = `conv1`.`account_id`
    AND
      `total`.`campaign_id` = `conv1`.`campaign_id`
    AND
      `total`.`customerID` = `conv1`.`customerID`
    AND
      `total`.`campaignID` = `conv1`.`campaignID`
    AND
      `total`.`adGroupID` = `conv1`.`adGroupID`
    AND
      `total`.`day` = `conv1`.`day`
    AND
      `total`.`dayOfWeek` = `conv1`.`dayOfWeek`
    AND
      `conv1`.`conversionName` = 'Conversion Name 1'
LEFT JOIN
  `repo_adw_ad_report_conv` as conv2
  ON
      `total`.`account_id` = `conv2`.`account_id`
    AND
      `total`.`campaign_id` = `conv2`.`campaign_id`
    AND
      `total`.`customerID` = `conv2`.`customerID`
    AND
      `total`.`campaignID` = `conv2`.`campaignID`
    AND
      `total`.`adGroupID` = `conv2`.`adGroupID`
    AND
      `total`.`day` = `conv2`.`day`
    AND
      `total`.`dayOfWeek` = `conv2`.`dayOfWeek`
    AND
      `conv2`.`conversionName` = 'Conversion Name 2'
LEFT JOIN
  `repo_adw_ad_report_conv` as conv3
  ON
      `total`.`account_id` = `conv3`.`account_id`
    AND
      `total`.`campaign_id` = `conv3`.`campaign_id`
    AND
      `total`.`customerID` = `conv3`.`customerID`
    AND
      `total`.`campaignID` = `conv3`.`campaignID`
    AND
      `total`.`adGroupID` = `conv3`.`adGroupID`
    AND
      `total`.`day` = `conv3`.`day`
    AND
      `total`.`dayOfWeek` = `conv3`.`dayOfWeek`
    AND
      `conv3`.`conversionName` = 'Conversion Name 3'
LEFT JOIN
  (`phone_time_use` as ptu1, `campaigns` as c1)
  ON
      `ptu1`.`account_id` = `c1`.`account_id`
    AND
      `ptu1`.`campaign_id` = `c1`.`campaign_id`
    AND
      `ptu1`.`account_id` = `total`.`account_id`
    AND
      `ptu1`.`campaign_id` = `total`.`campaign_id`
    AND
      `ptu1`.`utm_campaign` = `total`.`campaignID`
    AND
      `ptu1`.`source` = 'adw'
    AND
      `ptu1`.`traffic_type` = 'AD'
    AND
      `ptu1`.`time_of_call` LIKE CONCAT(`total`.`day`, '%')
    AND
      DAYNAME(`ptu1`.`time_of_call`) = `total`.`dayOfWeek`
    AND
      `ptu1`.`phone_number` = '+841234567811'
    AND
      (
        (
          `c1`.`camp_custom1` = 'creative'
        AND
          `ptu1`.`custom1` = `total`.`adId`
        )
        OR
        (
          `c1`.`camp_custom2` = 'creative'
        AND
          `ptu1`.`custom2` = `total`.`adId`
        )
        OR
        (
          `c1`.`camp_custom3` = 'creative'
        AND
          `ptu1`.`custom3` = `total`.`adId`
        )
        OR
        (
          `c1`.`camp_custom4` = 'creative'
        AND
          `ptu1`.`custom4` = `total`.`adId`
        )
        OR
        (
          `c1`.`camp_custom5` = 'creative'
        AND
          `ptu1`.`custom5` = `total`.`adId`
        )
        OR
        (
          `c1`.`camp_custom6` = 'creative'
        AND
          `ptu1`.`custom6` = `total`.`adId`
        )
        OR
        (
          `c1`.`camp_custom7` = 'creative'
        AND
          `ptu1`.`custom7` = `total`.`adId`
        )
        OR
        (
          `c1`.`camp_custom8` = 'creative'
        AND
          `ptu1`.`custom8` = `total`.`adId`
        )
        OR
        (
          `c1`.`camp_custom9` = 'creative'
        AND
          `ptu1`.`custom9` = `total`.`adId`
        )
        OR
        (
          `c1`.`camp_custom10` = 'creative'
        AND
          `ptu1`.`custom10` = `total`.`adId`
        )
      )
WHERE
  `total`.`account_id` = 1
AND
  `total`.`campaign_id`= 11
AND
  `total`.`customerID` = 11
AND
  `total`.`campaignID` = 11
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <= '2017-04-01'
AND
  `total`.`network` = 'CONTENT'
GROUP BY
  `total`.`account_id`,
  `total`.`campaign_id`,
  `total`.`customerID`,
  `total`.`campaignID`,
  `total`.`dayOfWeek`
) as tbl GROUP BY dayOfWeek;
