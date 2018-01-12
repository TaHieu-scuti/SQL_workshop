select
  DISTINCT
  `repo_adw_keywords_report_conv`.`keywordID`,
  `repo_adw_keywords_report_conv`.`conversionName`
from
  `repo_adw_keywords_report_conv`
where
  `repo_adw_keywords_report_conv`.`account_id` = 1
and
  `repo_adw_keywords_report_conv`.`customerID` = 11
and
  `repo_adw_keywords_report_conv`.`campaignId` = 11
and
  `repo_adw_keywords_report_conv`.`adGroupId` = 1;

/* result of above query
keywordID	conversionName
1	Conversion Name 1
1	Conversion Name 2
2	Conversion Name 1
2	Conversion Name 2
3	Conversion Name 1
3	Conversion Name 2
*/

select
  DISTINCT
  `c`.`campaign_id`,
  `c`.`campaign_name`,
  `ptu`.`utm_campaign`,
  `ptu`.`phone_number`
from
  `phone_time_use` as ptu, `campaigns` as c
WHERE
  `ptu`.`account_id` = `c`.`account_id`
AND
  `ptu`.`campaign_id` = `c`.`campaign_id`
AND
  `ptu`.`source` = 'adw'
AND
  `ptu`.`traffic_type` = 'AD'
AND
  `ptu`.`utm_campaign` = 11
AND
  (
    (
      `c`.`camp_custom1` = 'adgroupid'
    AND
      `ptu`.`custom1` = 1
    )
    OR
    (
      `c`.`camp_custom2` = 'adgroupid'
    AND
      `ptu`.`custom2` = 1
    )
    OR
    (
      `c`.`camp_custom3` = 'adgroupid'
    AND
      `ptu`.`custom3` = 1
    )
    OR
    (
      `c`.`camp_custom4` = 'adgroupid'
    AND
      `ptu`.`custom4` = 1
    )
    OR
    (
      `c`.`camp_custom5` = 'adgroupid'
    AND
      `ptu`.`custom5` = 1
    )
    OR
    (
      `c`.`camp_custom6` = 'adgroupid'
    AND
      `ptu`.`custom6` = 1
    )
    OR
    (
      `c`.`camp_custom7` = 'adgroupid'
    AND
      `ptu`.`custom7` = 1
    )
    OR
    (
      `c`.`camp_custom8` = 'adgroupid'
    AND
      `ptu`.`custom8` = 1
    )
    OR
    (
      `c`.`camp_custom9` = 'adgroupid'
    AND
      `ptu`.`custom9` = 1
    )
    OR
    (
      `c`.`camp_custom10` = 'adgroupid'
    AND
      `ptu`.`custom10` = 1
    )
  );
/* result of above query
campaign_id	campaign_name	utm_campaign	phone_number
11	Campaign Name	11	+841234567811
*/

SELECT
  `total`.`account_id`,
  `total`.`customerId`,
  `total`.`campaignId`,
  `total`.`adGroupId`,
  `total`.`keywordID`,
  `total`.`keyword`,
  SUM(`conv1`.`conversions`) as 'Conversion Name 1 CV',
  SUM(`conv2`.`conversions`) as 'Conversion Name 2 CV',
  SUM(`total`.`conversions`) as web_cv,
  COUNT(`ptu1`.`id`) as 'Campaign Name +841234567811 CV',
  COUNT(`ptu1`.`id`) as call_cv
FROM
  `repo_adw_keywords_report_cost` as total
LEFT JOIN
  `repo_adw_keywords_report_conv` as conv1
ON
  (`total`.`account_id` = `conv1`.`account_id` /* we can remove this when keywordId is unique */
AND
  `total`.`campaign_id` = `conv1`.`campaign_id` /* we can remove this when keywordId is unique */
AND
  `total`.`customerId` = `conv1`.`customerID` /* we can remove this when keywordId is unique */
AND
  `total`.`campaignID` = `conv1`.`campaignID` /* we can remove this when keywordId is unique */
AND
  `total`.`adGroupID` = `conv1`.`adGroupID` /* we can remove this when keywordId is unique */
AND
  `total`.`day` = `conv1`.`day`
AND
  `total`.`keywordID` = `conv1`.`keywordID`
AND
  `conv1`.`conversionName` = 'Conversion Name 1')
LEFT JOIN
  `repo_adw_keywords_report_conv` as conv2
ON
  (`total`.`account_id` = `conv2`.`account_id` /* we can remove this when keywordId is unique */
AND
  `total`.`campaign_id` = `conv2`.`campaign_id` /* we can remove this when keywordId is unique */
AND
  `total`.`customerId` = `conv2`.`customerID` /* we can remove this when keywordId is unique */
AND
  `total`.`campaignID` = `conv2`.`campaignID` /* we can remove this when keywordId is unique */
AND
  `total`.`adGroupID` = `conv2`.`adGroupID` /* we can remove this when keywordId is unique */
AND
  `total`.`day` = `conv2`.`day`
AND
  `total`.`keywordID` = `conv2`.`keywordID`
AND
  `conv2`.`conversionName` = 'Conversion Name 2')
LEFT JOIN
  (`phone_time_use` as ptu1, `campaigns` as c1)
ON
(
  `ptu1`.`account_id` = `c1`.`account_id`
AND
  `ptu1`.`campaign_id` = `c1`.`campaign_id`
AND
  `ptu1`.`utm_campaign` = `total`.`campaignID`
AND
  `ptu1`.`phone_number` = '+841234567811'
AND
  `ptu1`.`source` = 'adw'
AND
  `ptu1`.`traffic_type` = 'AD'
AND
  `ptu1`.`account_id` = 1
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
AND
  `ptu1`.`j_keyword` = `total`.`keyword`
AND
  `ptu1`.`matchtype` = `total`.`matchType`
)
WHERE
  `total`.`account_id` = 1
AND
  `total`.`customerId` = 11
AND
  `total`.`campaignId` = 11
AND
  `total`.`adGroupId` = 2
AND
  `total`.`network` = 'SEARCH'
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <=	'2017-02-01'
GROUP BY
  `total`.`account_id`,
  `total`.`customerId`,
  `total`.`campaignId`,
  `total`.`adGroupId`,
  `total`.`keywordID`,
  `total`.`keyword`
