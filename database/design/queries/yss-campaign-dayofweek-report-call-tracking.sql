/* Day of week conversion point */
SELECT
  DISTINCT
  `repo_yss_campaign_report_conv`.`dayOfWeek`,
  `repo_yss_campaign_report_conv`.`conversionName`,
  `repo_yss_campaign_report_conv`.`campaignID`
FROM
  `repo_yss_campaign_report_conv`
WHERE
  `repo_yss_campaign_report_conv`.`account_id` = 1
AND
  `repo_yss_campaign_report_conv`.`campaign_id` = 11
AND
  `repo_yss_campaign_report_conv`.`accountid` = 11;

/*
dayOfWeek	conversionName	campaignID
Sunday	YSS conversion 111110	11
Sunday	YSS conversion 111111	11
Sunday	YSS conversion 111112	11
Monday	YSS conversion 111110	11
Monday	YSS conversion 111111	11
Monday	YSS conversion 111112	11
Tuesday	YSS conversion 111110	11
Tuesday	YSS conversion 111111	11
Tuesday	YSS conversion 111112	11
Wednesday	YSS conversion 111110	11
Wednesday	YSS conversion 111111	11
Wednesday	YSS conversion 111112	11
Thursday	YSS conversion 111110	11
Thursday	YSS conversion 111111	11
Thursday	YSS conversion 111112	11
Friday	YSS conversion 111110	11
Friday	YSS conversion 111111	11
Friday	YSS conversion 111112	11
Saturday	YSS conversion 111110	11
Saturday	YSS conversion 111111	11
Saturday	YSS conversion 111112	11
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
/*
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
  SUM(`conv2`.`conversions`) as 'Yss conversion 111111 CV',
  SUM(`conv3`.`conversions`) as 'Yss conversion 111112 CV',
  COUNT(`conv4`.`id`) as 'Campaign name +841234567811 CV',
  SUM(`total`.`conversions`) as web_cv,
  COUNT(`conv4`.`id`) as call_cv
FROM
  `repo_yss_campaign_report_cost` as total
LEFT JOIN
  `repo_yss_campaign_report_conv` as conv1
  ON
    `conv1`.`account_id` = `total`.`account_id`
  AND
    `conv1`.`campaign_id` = `total`.`campaign_id`
  AND
    `conv1`.`accountid` = `total`.`accountid`
  AND
    `conv1`.`day` = `total`.`day`
  AND
    `conv1`.`dayOfWeek` = `total`.`dayOfWeek`
  AND
    `conv1`.`conversionName` = 'YSS conversion 111110'
LEFT JOIN
  `repo_yss_campaign_report_conv` as conv2
  ON
    `conv2`.`account_id` = `total`.`account_id`
  AND
    `conv2`.`campaign_id` = `total`.`campaign_id`
  AND
    `conv2`.`accountid` = `total`.`accountid`
  AND
    `conv2`.`day` = `total`.`day`
  AND
    `conv2`.`dayOfWeek` = `total`.`dayOfWeek`
  AND
    `conv2`.`conversionName` = 'YSS conversion 111111'
LEFT JOIN
  `repo_yss_campaign_report_conv` as conv3
  ON
    `conv3`.`account_id` = `total`.`account_id`
  AND
    `conv3`.`campaign_id` = `total`.`campaign_id`
  AND
    `conv3`.`accountid` = `total`.`accountid`
  AND
    `conv3`.`day` = `total`.`day`
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
      `conv4`.`utm_campaign` IN (11)
    AND
      `conv4`.`source` = 'yss'
    AND
      `total`.`day` = STR_TO_DATE(`conv4`.`time_of_call`, '%Y-%m-%d')
    AND
      `total`.`dayOfWeek` = DAYNAME(`conv4`.`time_of_call`)
    AND
      `conv4`.`phone_number` = '+841234567811'
WHERE
  `total`.`account_id` = 1
AND
  `total`.`campaign_id` = 11
AND
  `total`.`accountid` = 11
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <= '2017-12-01'
GROUP BY
  `total`.`account_id`,
  `total`.`campaign_id`,
  `total`.`campaignID`,
  `total`.`dayOfWeek`
