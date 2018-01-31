/* adw campaign dayOfWeek */

SELECT
  DISTINCT
  `repo_adw_campaign_report_conv`.`conversionName`,
  `repo_adw_campaign_report_conv`.`dayOfWeek`,
  `repo_adw_campaign_report_conv`.`campaignID`
FROM
  `repo_adw_campaign_report_conv`
WHERE
  `repo_adw_campaign_report_conv`.`account_id` = 1
AND
  `repo_adw_campaign_report_conv`.`campaign_id` = 11
AND
  `repo_adw_campaign_report_conv`.`customerID` = 11;
/* result of query above
conversionName  dayOfWeek campaignID
Conversion name1  Sunday  11
Conversion name2  Sunday  11
Conversion name3  Sunday  11
Conversion name1  Monday  11
Conversion name2  Monday  11
Conversion name3  Monday  11
Conversion name1  Tuesday 11
Conversion name2  Tuesday 11
Conversion name3  Tuesday 11
Conversion name1  Wednesday 11
Conversion name2  Wednesday 11
Conversion name3  Wednesday 11
Conversion name1  Thursday  11
Conversion name2  Thursday  11
Conversion name3  Thursday  11
Conversion name1  Friday  11
Conversion name2  Friday  11
Conversion name3  Friday  11
Conversion name1  Saturday  11
Conversion name2  Saturday  11
Conversion name3  Saturday  11
*/

SELECT
  DISTINCT
  `repo_phone_time_use`.`phone_number`,
  `repo_phone_time_use`.`campaign_id`,
  `repo_phone_time_use`.`account_id`,
  DAYNAME(`repo_phone_time_use`.`time_of_call`) as dayOfWeek
FROM
  `repo_phone_time_use`
WHERE
  `repo_phone_time_use`.`account_id` = 1
AND
  `repo_phone_time_use`.`campaign_id` = 11
AND
  `repo_phone_time_use`.`utm_campaign` IN (11)
AND
  `repo_phone_time_use`.`source` = 'adw'
AND
  `repo_phone_time_use`.`traffic_type` = 'AD'

/*
  result of query
phone_number  campaign_id account_id  dayOfWeek
+841234567811 11  1 Thursday
+841234567811 11  1 Sunday
+841234567811 11  1 Friday
+841234567811 11  1 Wednesday
+841234567811 11  1 Tuesday
+841234567811 11  1 Saturday
+841234567811 11  1 Monday
+841234567813 11  1 Saturday
+841234567815 11  1 Saturday
*/

SELECT
  `total`.`dayOfWeek`,
  SUM(`conv1`.`conversions`) as 'Conversion name1 CV',
  SUM(`conv2`.`conversions`) as 'Conversion name2 CV',
  SUM(`conv3`.`conversions`) as 'Conversion name3 CV',
  COUNT(`conv4`.`id`) as 'Campaign Name +841234567811 CV',
  COUNT(`conv5`.`id`) as 'Campaign Name +841234567813 CV',
  COUNT(`conv6`.`id`) as 'Campaign Name +841234567815 CV',
  SUM(`total`.`conversions`) as web_cv,
  COUNT(`conv4`.`id`) + COUNT(`conv5`.`id`) + COUNT(`conv6`.`id`) as call_cv
FROM
  `repo_adw_campaign_report_cost` as total
LEFT JOIN
  `repo_adw_campaign_report_conv` as conv1
  ON
      `total`.`account_id` = `conv1`.`account_id`
    AND
      `total`.`campaign_id` = `conv1`.`campaign_id`
    AND
      `total`.`customerID` = `conv1`.`customerID`
    AND
      `total`.`dayOfWeek` = `conv1`.`dayOfWeek`
    AND
      `total`.`day` = `conv1`.`day`
    AND
      `conv1`.`conversionName` = 'Conversion name1'
LEFT JOIN
  `repo_adw_campaign_report_conv` as conv2
  ON
      `total`.`account_id` = `conv2`.`account_id`
    AND
      `total`.`campaign_id` = `conv2`.`campaign_id`
    AND
      `total`.`customerID` = `conv2`.`customerID`
    AND
      `total`.`dayOfWeek` = `conv2`.`dayOfWeek`
    AND
      `total`.`day` = `conv2`.`day`
    AND
      `conv2`.`conversionName` = 'Conversion name2'
LEFT JOIN
  `repo_adw_campaign_report_conv` as conv3
  ON
      `total`.`account_id` = `conv3`.`account_id`
    AND
      `total`.`campaign_id` = `conv3`.`campaign_id`
    AND
      `total`.`customerID` = `conv3`.`customerID`
    AND
      `total`.`dayOfWeek` = `conv3`.`dayOfWeek`
    AND
      `total`.`day` = `conv3`.`day`
    AND
      `conv3`.`conversionName` = 'Conversion name3'
LEFT JOIN
  `repo_phone_time_use` as conv4
  ON
      `total`.`account_id` = `conv4`.`account_id`
    AND
      `total`.`campaign_id` = `conv4`.`campaign_id`
    AND
      `conv4`.`utm_campaign` IN (11)
    AND
      `conv4`.`source` = 'adw'
    AND
      `conv4`.`traffic_type` = 'AD'
    AND
      `conv4`.`phone_number` = '+841234567811'
    AND
      DAYNAME(`conv4`.`time_of_call`) = `total`.`dayOfWeek`
    AND
      `conv4`.`time_of_call` LIKE CONCAT(`total`.`day`, '%')
LEFT JOIN
  `repo_phone_time_use` as conv5
  ON
      `total`.`account_id` = `conv5`.`account_id`
    AND
      `total`.`campaign_id` = `conv5`.`campaign_id`
    AND
      `conv5`.`utm_campaign` IN (11)
    AND
      `conv5`.`source` = 'adw'
    AND
      `conv5`.`traffic_type` = 'AD'
    AND
      `conv5`.`phone_number` = '+841234567813'
    AND
      DAYNAME(`conv5`.`time_of_call`) = `total`.`dayOfWeek`
    AND
      `conv5`.`time_of_call` LIKE CONCAT(`total`.`day`, '%')
LEFT JOIN
  `repo_phone_time_use` as conv6
  ON
      `total`.`account_id` = `conv6`.`account_id`
    AND
      `total`.`campaign_id` = `conv6`.`campaign_id`
    AND
      `conv6`.`utm_campaign` IN (11)
    AND
      `conv6`.`source` = 'adw'
    AND
      `conv6`.`traffic_type` = 'AD'
    AND
      `conv6`.`phone_number` = '+841234567815'
    AND
      DAYNAME(`conv6`.`time_of_call`) = `total`.`dayOfWeek`
    AND
      `conv6`.`time_of_call` LIKE CONCAT(`total`.`day`, '%')
WHERE
  `total`.`account_id` = 1
AND
  `total`.`campaign_id` = 11
AND
  `total`.`customerID` = 11
AND
  `total`.`day` >= '2017-01-01'
AND
  `total`.`day` <= '2017-12-01'
AND
  (
    `total`.`network` = 'CONTENT'
    OR
    `total`.`network` = 'SEARCH'
  )
GROUP BY
  `total`.`account_id`,
  `total`.`campaign_id`,
  `total`.`customerID`,
  `total`.`dayOfWeek`