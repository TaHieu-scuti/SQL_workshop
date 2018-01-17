SELECT
  DISTINCT
  `repo_yss_adgroup_report_conv`.`dayOfWeek`,
  `repo_yss_adgroup_report_conv`.`conversionName`
FROM
  `repo_yss_adgroup_report_conv`
WHERE
  `repo_yss_adgroup_report_conv`.`account_id` = 1
AND
  `repo_yss_adgroup_report_conv`.`campaign_id` = 11
AND
  `repo_yss_adgroup_report_conv`.`accountid` = 11
AND
  `repo_yss_adgroup_report_conv`.`campaignId` = 11;

/* result of query
dayOfWeek	conversionName
Sunday	YSS conversion 111110
Sunday	YSS conversion 111111
Sunday	YSS conversion 111112
Monday	YSS conversion 111110
Monday	YSS conversion 111111
Monday	YSS conversion 111112
Tuesday	YSS conversion 111110
Tuesday	YSS conversion 111111
Tuesday	YSS conversion 111112
Wednesday	YSS conversion 111110
Wednesday	YSS conversion 111111
Wednesday	YSS conversion 111112
Thursday	YSS conversion 111110
Thursday	YSS conversion 111111
Thursday	YSS conversion 111112
Friday	YSS conversion 111110
Friday	YSS conversion 111111
Friday	YSS conversion 111112
Saturday	YSS conversion 111110
Saturday	YSS conversion 111111
Saturday	YSS conversion 111112
*/

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
  `repo_phone_time_use`.`source` = 'yss'
AND
  `repo_phone_time_use`.`traffic_type` = 'AD';
/*result of query
account_id	utm_campaign	phone_number	dayOfWeek
1	11	+841234567811	Thursday
1	11	+841234567811	Friday
1	11	+841234567811	Tuesday
1	11	+841234567811	Wednesday
1	11	+841234567811	Saturday
1	11	+841234567811	Monday
1	11	+841234567811	Sunday
*/

SELECT
  `total`.`dayOfWeek`,
  SUM(`conv1`.`conversions`) as 'YSS conversion 111110 CV',
  SUM(`conv2`.`conversions`) as 'YSS conversion 111111 CV',
  SUM(`conv3`.`conversions`) as 'YSS conversion 111112 CV',
  SUM(`total`.`conversions`) as web_cv,
  COUNT(`conv4`.`id`) as 'Campaign name +841234567811 CV',
  COUNT(`conv4`.`id`) as call_cv
FROM
  `repo_yss_adgroup_report_cost` as total
LEFT JOIN
  `repo_yss_adgroup_report_conv` as conv1
  ON
      `conv1`.`account_id` = `total`.`account_id`
    AND
      `conv1`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv1`.`accountid` = `total`.`accountid`
    AND
      `conv1`.`campaignId` = `total`.`campaignId`
    AND
      `conv1`.`day` = `conv1`.`day`
    AND
      `conv1`.`dayOfWeek` = `total`.`dayOfWeek`
    AND
      `conv1`.`conversionName` = 'YSS conversion 111110'
LEFT JOIN
  `repo_yss_adgroup_report_conv` as conv2
  ON
      `conv2`.`account_id` = `total`.`account_id`
    AND
      `conv2`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv2`.`accountid` = `total`.`accountid`
    AND
      `conv2`.`campaignId` = `total`.`campaignId`
    AND
      `conv2`.`day` = `conv2`.`day`
    AND
      `conv2`.`dayOfWeek` = `total`.`dayOfWeek`
    AND
      `conv2`.`conversionName` = 'YSS conversion 111111'
LEFT JOIN
  `repo_yss_adgroup_report_conv` as conv3
  ON
      `conv3`.`account_id` = `total`.`account_id`
    AND
      `conv3`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv3`.`accountid` = `total`.`accountid`
    AND
      `conv3`.`campaignId` = `total`.`campaignId`
    AND
      `conv3`.`day` = `conv3`.`day`
    AND
      `conv3`.`dayOfWeek` = `total`.`dayOfWeek`
    AND
      `conv3`.`conversionName` = 'YSS conversion 111112'
LEFT JOIN
  `repo_phone_time_use` as conv4
  ON
      `conv4`.`account_id` = `total`.`account_id`
    AND
      `conv4`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv4`.`utm_campaign` = `total`.`campaignID`
    AND
      `conv4`.`source` = 'yss'
    AND
      `conv4`.`traffic_type` = 'AD'
    AND
      `total`.`day` = STR_TO_DATE(`conv4`.`time_of_call`, '%Y-%m-%d')
    AND
      `conv4`.`time_of_call` LIKE CONCAT(`total`.`day`, '%')
    AND
      `conv4`.`phone_number` = '+841234567811'
WHERE
  `total`.`account_id` = 1
AND
  `total`.`campaign_id` = 11
AND
  `total`.`accountid` = 11
AND
  `total`.`campaignID` = 11
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <= '2017-02-01'
GROUP BY
  `total`.`account_id`,
  `total`.`campaign_id`,
  `total`.`accountid`,
  `total`.`campaignID`,
  `total`.`dayOfWeek`
