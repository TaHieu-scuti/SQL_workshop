/* Get all unique conversion names */
SELECT
  DISTINCT
  campaignID,
  conversionName
FROM
  `repo_adw_campaign_report_conv`
WHERE
  account_id = 1
  AND
  customerID = 11;
/* Result from query above:
|campaignID|conversionName  |
|11        |Conversion name1|
|11        |Conversion name2|
|11        |Conversion name3|
|12        |Conversion name1|
|12        |Conversion name2|
|12        |Conversion name3|
|13        |Conversion name1|
|13        |Conversion name2|
|13        |Conversion name3|
|14        |Conversion name1|
|14        |Conversion name2|
|14        |Conversion name3|
|15        |Conversion name1|
|15        |Conversion name2|
|15        |Conversion name3|
|16        |Conversion name1|
|16        |Conversion name2|
|16        |Conversion name3|*/


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
  `ptu`.`utm_campaign` IN (11, 12, 13, 14, 15, 16)
  AND
  `ptu`.`source` = 'adw';
/* Result from query above:
|campaign_id|campaign_name|utm_campaign|phone_number |
|11         |Campaign Name|11          |+841234567811|
|12         |Campaign Name|12          |+841234567812|
|13         |Campaign Name|13          |+841234567813|
|14         |Campaign Name|14          |+841234567814|
|15         |Campaign Name|15          |+841234567815|
|16         |Campaign Name|16          |+841234567816|
*/


SELECT
  `total`.`account_id`,
  `total`.`customerID`,
  `total`.`campaignID`,
  SUM(`total`.`impressions`) AS impressions,
  SUM(`total`.`clicks`) AS clicks,
  SUM(`total`.`cost`) AS cost,
  AVG(`total`.`ctr`) AS ctr,
  AVG(`total`.`avgCPC`) AS cpc,
  /* add the expressions for the conversionName columns */
  SUM(`conv1`.`conversions`) AS 'Conversion name1 CV',
  SUM(`conv2`.`conversions`) AS 'Conversion name2 CV',
  SUM(`conv3`.`conversions`) AS 'Conversion name3 CV',
  /* add the expressions for the AG campaign_name/phone_number columns */
  COUNT(`call1`.`id`) AS 'Campaign Name +841234567811 CV',
  COUNT(`call2`.`id`) AS 'Campaign Name +841234567812 CV',
  COUNT(`call3`.`id`) AS 'Campaign Name +841234567813 CV',
  COUNT(`call4`.`id`) AS 'Campaign Name +841234567814 CV',
  COUNT(`call5`.`id`) AS 'Campaign Name +841234567815 CV',
  COUNT(`call6`.`id`) AS 'Campaign Name +841234567816 CV',
  COUNT(`call1`.`id`) + COUNT(`call2`.`id`) + COUNT(`call3`.`id`) +
  COUNT(`call4`.`id`) + COUNT(`call5`.`id`) + COUNT(`call6`.`id`) AS call_cv,
  SUM(`total`.`conversions`) AS webcv,
  SUM(`total`.`conversions`) + COUNT(`call1`.`id`) + COUNT(`call2`.`id`) +
  COUNT(`call3`.`id`) +  COUNT(`call4`.`id`) + COUNT(`call5`.`id`) + COUNT(`call6`.`id`) AS cv,
  ((SUM(`total`.`conversions`) + COUNT(`call1`.`id`) + COUNT(`call2`.`id`) +
    COUNT(`call3`.`id`) +  COUNT(`call4`.`id`) + COUNT(`call5`.`id`) + COUNT(`call6`.`id`)) /
   SUM(`total`.`clicks`)) * 100 AS cvr,
  SUM(`total`.`cost`) / (SUM(`total`.`conversions`) + COUNT(`call1`.`id`) + COUNT(`call2`.`id`) +
                         COUNT(`call3`.`id`) +  COUNT(`call4`.`id`) + COUNT(`call5`.`id`) + COUNT(`call6`.`id`)) AS cpa,
  AVG(`total`.`avgPosition`) AS avgPosition
FROM
  `repo_adw_campaign_report_cost` AS total
  LEFT JOIN
    `repo_adw_campaign_report_conv` AS conv1
    ON
    `total`.`account_id` = `conv1`.`account_id`
    AND
    `total`.`customerID` = `conv1`.`customerID`
    AND
    `total`.`day` = `conv1`.`day`
    AND
    `total`.`campaignID` = `conv1`.`campaignID`
    AND
    `conv1`.`conversionName` = 'Conversion name1'
  LEFT JOIN
    `repo_adw_campaign_report_conv` AS conv2
    ON
    `total`.`account_id` = `conv2`.`account_id`
    AND
    `total`.`customerID` = `conv2`.`customerID`
    AND
    `total`.`day` = `conv2`.`day`
    AND
    `total`.`campaignID` = `conv2`.`campaignID`
    AND
    `conv2`.`conversionName` = 'Conversion name2'
  LEFT JOIN
    `repo_adw_campaign_report_conv` AS conv3
    ON
    `total`.`account_id` = `conv3`.`account_id`
    AND
    `total`.`customerID` = `conv3`.`customerID`
    AND
    `total`.`day` = `conv3`.`day`
    AND
    `total`.`campaignID` = `conv3`.`campaignID`
    AND
    `conv3`.`conversionName` = 'Conversion name3'
  LEFT JOIN
    `repo_phone_time_use` AS call1
    ON
    `total`.`account_id` = `call1`.`account_id`
    AND
    `total`.`campaign_id` = `call1`.`campaign_id`
    AND
    `total`.`campaignID` = `call1`.`utm_campaign`
    AND
    `total`.`day` = STR_TO_DATE(`call1`.`time_of_call`, '%Y-%m-%d')
    AND
    `call1`.`utm_campaign` = 11
    AND
    `call1`.`phone_number` = '+841234567811'
    AND
    `call1`.`source` = 'adw'
  LEFT JOIN
    `repo_phone_time_use` AS call2
    ON
    `total`.`account_id` = `call2`.`account_id`
    AND
    `total`.`campaign_id` = `call2`.`campaign_id`
    AND
    `total`.`campaignID` = `call2`.`utm_campaign`
    AND
    `total`.`day` = STR_TO_DATE(`call2`.`time_of_call`, '%Y-%m-%d')
    AND
    `call2`.`utm_campaign` = 12
    AND
    `call2`.`phone_number` = '+841234567812'
    AND
    `call2`.`source` = 'adw'
  LEFT JOIN
    `repo_phone_time_use` AS call3
    ON
    `total`.`account_id` = `call3`.`account_id`
    AND
    `total`.`campaign_id` = `call3`.`campaign_id`
    AND
    `total`.`campaignID` = `call3`.`utm_campaign`
    AND
    `total`.`day` = STR_TO_DATE(`call3`.`time_of_call`, '%Y-%m-%d')
    AND
    `call3`.`utm_campaign` = 13
    AND
    `call3`.`phone_number` = '+841234567813'
    AND
    `call3`.`source` = 'adw'
  LEFT JOIN
    `repo_phone_time_use` AS call4
    ON
    `total`.`account_id` = `call4`.`account_id`
    AND
    `total`.`campaign_id` = `call4`.`campaign_id`
    AND
    `total`.`campaignID` = `call4`.`utm_campaign`
    AND
    `total`.`day` = STR_TO_DATE(`call4`.`time_of_call`, '%Y-%m-%d')
    AND
    `call4`.`utm_campaign` = 14
    AND
    `call4`.`phone_number` = '+841234567814'
    AND
    `call4`.`source` = 'adw'
  LEFT JOIN
    `repo_phone_time_use` AS call5
    ON
    `total`.`account_id` = `call5`.`account_id`
    AND
    `total`.`campaign_id` = `call5`.`campaign_id`
    AND
    `total`.`campaignID` = `call5`.`utm_campaign`
    AND
    `total`.`day` = STR_TO_DATE(`call5`.`time_of_call`, '%Y-%m-%d')
    AND
    `call5`.`utm_campaign` = 15
    AND
    `call5`.`phone_number` = '+841234567815'
    AND
    `call5`.`source` = 'adw'
  LEFT JOIN
    `repo_phone_time_use` AS call6
    ON
    `total`.`account_id` = `call6`.`account_id`
    AND
    `total`.`campaign_id` = `call6`.`campaign_id`
    AND
    `total`.`campaignID` = `call6`.`utm_campaign`
    AND
    `total`.`day` = STR_TO_DATE(`call6`.`time_of_call`, '%Y-%m-%d')
    AND
    `call6`.`utm_campaign` = 16
    AND
    `call6`.`phone_number` = '+841234567816'
    AND
    `call6`.`source` = 'adw'
WHERE
  `total`.`account_id` = 1
  AND
  `total`.`customerID` = 11
  AND
  `total`.`campaignID` IN (11, 12, 13, 14, 15, 16)
  AND
  `total`.`day` >= '2017-01-01'
  AND
  `total`.`day` <= '2017-04-01'
  AND
  (
    `total`.`network` = 'CONTENT'
    OR
    `total`.`network` = 'SEARCH'
  )
GROUP BY
  `total`.`account_id`,
  `total`.`customerID`,
  `total`.`campaignID`;
