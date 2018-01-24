select
  distinct
  `repo_adw_adgroup_report_conv`.`conversionName`,
  `repo_adw_adgroup_report_conv`.`hourOfDay`
from
  `repo_adw_adgroup_report_conv`
WHERE
  `repo_adw_adgroup_report_conv`.`account_id` = 1
AND
  `repo_adw_adgroup_report_conv`.`campaign_id` = 11
AND
  `repo_adw_adgroup_report_conv`.`customerId` = 11
AND
  `repo_adw_adgroup_report_conv`.`campaignID` = 11
AND
  (
    `repo_adw_adgroup_report_conv`.`network` = 'SEARCH'
  OR
    `repo_adw_account_report_conv`.`network` = 'CONTENT'
  )
ORDER BY
  `repo_adw_adgroup_report_conv`.`hourOfDay`
ASC;

/* result of query
conversionName	hourOfDay
Conversion Name 1	0
Conversion Name 2	0
Conversion Name 3	0
Conversion Name 1	1
Conversion Name 2	1
Conversion Name 3	1
Conversion Name 1	2
Conversion Name 2	2
Conversion Name 3	2
Conversion Name 1	3
Conversion Name 2	3
Conversion Name 3	3
Conversion Name 1	4
Conversion Name 2	4
Conversion Name 3	4
Conversion Name 1	5
Conversion Name 2	5
Conversion Name 3	5
Conversion Name 1	6
Conversion Name 2	6
Conversion Name 3	6
Conversion Name 1	7
Conversion Name 2	7
Conversion Name 3	7
Conversion Name 1	8
Conversion Name 2	8
Conversion Name 3	8
Conversion Name 1	9
Conversion Name 2	9
Conversion Name 3	9
Conversion Name 1	10
Conversion Name 2	10
Conversion Name 3	10
Conversion Name 1	11
Conversion Name 2	11
Conversion Name 3	11
Conversion Name 1	12
Conversion Name 2	12
Conversion Name 3	12
Conversion Name 1	13
Conversion Name 2	13
Conversion Name 3	13
Conversion Name 1	14
Conversion Name 2	14
Conversion Name 3	14
Conversion Name 1	15
Conversion Name 2	15
Conversion Name 3	15
Conversion Name 1	16
Conversion Name 2	16
Conversion Name 3	16
Conversion Name 1	17
Conversion Name 2	17
Conversion Name 3	17
Conversion Name 1	18
Conversion Name 2	18
Conversion Name 3	18
Conversion Name 1	19
Conversion Name 2	19
Conversion Name 3	19
Conversion Name 1	20
Conversion Name 2	20
Conversion Name 3	20
Conversion Name 1	21
Conversion Name 2	21
Conversion Name 3	21
Conversion Name 1	22
Conversion Name 2	22
Conversion Name 3	22
Conversion Name 1	23
Conversion Name 2	23
Conversion Name 3	23
*/

select
  distinct
  `repo_phone_time_use`.`phone_number`,
  `repo_phone_time_use`.`campaign_id`,
  `repo_phone_time_use`.`account_id`,
  hour(`repo_phone_time_use`.`time_of_call`)
from
  `repo_phone_time_use`
where
  `repo_phone_time_use`.`account_id` = 1
AND
  `repo_phone_time_use`.`campaign_id` = 11
AND
  `repo_phone_time_use`.`utm_campaign` = 11
AND
  `repo_phone_time_use`.`source` = 'adw'
AND
  `repo_phone_time_use`.`traffic_type` = 'AD'
ORDER BY
  hour(`repo_phone_time_use`.`time_of_call`);

/* result of query
phone_number	campaign_id	account_id	hour(`repo_phone_time_use`.`time_of_call`)
+841234567811	11	1	0
+841234567811	11	1	1
+841234567811	11	1	2
+841234567811	11	1	3
+841234567811	11	1	4
+841234567811	11	1	7
+841234567811	11	1	8
+841234567811	11	1	10
+841234567811	11	1	11
+841234567811	11	1	12
+841234567811	11	1	15
+841234567811	11	1	16
+841234567811	11	1	18
+841234567811	11	1	20
+841234567811	11	1	22
+841234567811	11	1	23
*/

SELECT
  hourOfDay,
  SUM(`cv_1`) as 'Conversion Name 1 CV',
  SUM(`cv_2`) as 'Conversion Name 2 CV',
  SUM(`cv_3`) as 'Conversion Name 3 CV',
  SUM(`cv_4`) as 'Campaign Name +841234567811 CV'
FROM
(
  select
  `total`.`hourOfDay` as hourOfDay,
  SUM(`conv1`.`conversions`) as cv_1,
  SUM(`conv2`.`conversions`) as cv_2,
  SUM(`conv3`.`conversions`) as cv_3,
  COUNT(`ptu1`.`id`) as cv_4
from
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
      `total`.`hourOfDay` = `conv1`.`hourOfDay`
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
      `total`.`hourOfDay` = `conv2`.`hourOfDay`
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
      `total`.`hourOfDay` = `conv3`.`hourOfDay`
    AND
      `conv3`.`conversionName` = 'Conversion Name 3'
LEFT JOIN
  (`phone_time_use` as ptu1, `campaigns` as c1)
  ON
      `c1`.`account_id` = `total`.`account_id`
    AND
      `c1`.`campaign_id` = `total`.`campaign_id`
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
      hour(`ptu1`.`time_of_call`) = `total`.`hourOfDay`
    AND
      `ptu1`.`phone_number` = '+841234567811'
    AND
      (
        (
          `c1`.`camp_custom1` = 'adgroupid'
        AND
          `ptu1`.`custom1` = `total`.`adGroupId`
        )
      OR
        (
          `c1`.`camp_custom2` = 'adgroupid'
        AND
          `ptu1`.`custom2` = `total`.`adGroupId`
        )
      OR
        (
          `c1`.`camp_custom3` = 'adgroupid'
        AND
          `ptu1`.`custom3` = `total`.`adGroupId`
        )
      OR
        (
          `c1`.`camp_custom4` = 'adgroupid'
        AND
          `ptu1`.`custom4` = `total`.`adGroupId`
        )
      OR
        (
          `c1`.`camp_custom5` = 'adgroupid'
        AND
          `ptu1`.`custom5` = `total`.`adGroupId`
        )
      OR
        (
          `c1`.`camp_custom6` = 'adgroupid'
        AND
          `ptu1`.`custom6` = `total`.`adGroupId`
        )
      OR
        (
          `c1`.`camp_custom7` = 'adgroupid'
        AND
          `ptu1`.`custom7` = `total`.`adGroupId`
        )
      OR
        (
          `c1`.`camp_custom8` = 'adgroupid'
        AND
          `ptu1`.`custom8` = `total`.`adGroupId`
        )
      OR
        (
          `c1`.`camp_custom9` = 'adgroupid'
        AND
          `ptu1`.`custom9` = `total`.`adGroupId`
        )
      OR
        (
          `c1`.`camp_custom10` = 'adgroupid'
        AND
          `ptu1`.`custom10` = `total`.`adGroupId`
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
  `total`.`day` <= '2017-12-01'
AND
  (
    `total`.`network` = 'SEARCH'
  OR
    `total`.`network` = 'CONTENT'
  )
GROUP BY
  `total`.`account_id`,
  `total`.`campaign_id`,
  `total`.`customerID`,
  `total`.`campaignID`,
  `total`.`hourofday`

UNION

select
  hour(`total`.`day`) as hourOfDay,
  IFNULL(SUM(`conv1`.`conversions`), 0) as cv_1,
  IFNULL(SUM(`conv2`.`conversions`), 0) as cv_2,
  IFNULL(SUM(`conv3`.`conversions`), 0) as cv_3,
  COUNT(`ptu1`.`id`) as cv_4
from
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
      hour(`total`.`day`) = hour(`conv1`.`day`)
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
      hour(`total`.`day`) = hour(`conv2`.`day`)
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
      hour(`total`.`day`) = hour(`conv3`.`day`)
    AND
      `conv3`.`conversionName` = 'Conversion Name 3'
LEFT JOIN
  (`phone_time_use` as ptu1, `campaigns` as c1)
  ON
      `c1`.`account_id` = `total`.`account_id`
    AND
      `c1`.`campaign_id` = `total`.`campaign_id`
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
      hour(`ptu1`.`time_of_call`) = hour(`total`.`day`)
    AND
      `ptu1`.`phone_number` = '+841234567811'
    AND
      (
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
  `total`.`day` <= '2017-12-01'
AND
  (
    `total`.`network` = 'SEARCH'
  OR
    `total`.`network` = 'CONTENT'
  )
GROUP BY
  `total`.`account_id`,
  `total`.`campaign_id`,
  `total`.`customerID`,
  `total`.`campaignID`,
  hour(`total`.`day`)
) as tbl GROUP BY hourOfDay;
  
