SELECT
  DISTINCT
  `repo_adw_campaign_report_conv`.`conversionName`,
  `repo_adw_campaign_report_conv`.`hourOfDay`,
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
|conversionName  |hourOfDay |campaignID	|
|Conversion name1|15		|11			|
|Conversion name2|15		|11			|
|Conversion name3|15		|11			|
|Conversion name1|16		|11			|
|Conversion name2|16		|11			|
|Conversion name3|16		|11			|
|Conversion name1|0			|11			|
|Conversion name2|0			|11			|
|Conversion name3|0			|11			|
|Conversion name1|20		|11			|
|Conversion name2|20		|11			|
|Conversion name3|20		|11			|
|Conversion name1|11		|11			|
|Conversion name2|11		|11			|
|Conversion name3|11		|11			|
|Conversion name1|1			|11			|
|Conversion name2|1			|11			|
|Conversion name3|1			|11			|
|Conversion name1|2			|11			|
|Conversion name2|2			|11			|
|Conversion name3|2			|11			|
|Conversion name1|22		|11			|
|Conversion name2|22		|11			|
|Conversion name3|22		|11			|
|Conversion name1|21		|11			|
*/
SELECT
  DISTINCT
  `repo_phone_time_use`.`phone_number`,
  `repo_phone_time_use`.`campaign_id`,
  `repo_phone_time_use`.`account_id`,
  HOUR(`repo_phone_time_use`.`time_of_call`) as hourOfDay
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

/* result of query above
|phone_number |campaign_id 	|account_id |hourOfDay	|
|+841234567811|11			|1			|20			|
|+841234567811|11			|1			|16			|
|+841234567811|11			|1			|2			|
|+841234567811|11			|1			|0			|
|+841234567811|11			|1			|10			|
|+841234567811|11			|1			|11			|
|+841234567811|11			|1			|8			|
|+841234567811|11			|1			|23			|
|+841234567811|11			|1			|3			|
|+841234567811|11			|1			|7			|
|+841234567811|11			|1			|18			|
|+841234567811|11			|1			|4			|
|+841234567811|11			|1			|1			|
|+841234567811|11			|1			|12			|
|+841234567811|11			|1			|15			|
|+841234567811|11			|1			|22			|
*/
SELECT
  `total`.`hourOfDay`,
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
  COUNT(`call1`.`id`) AS call_cv,
  SUM(`total`.`conversions`) AS webcv,
  SUM(`total`.`conversions`) + COUNT(`call1`.`id`) AS cv,
  ((SUM(`total`.`conversions`) + COUNT(`call1`.`id`)) / SUM(`total`.`clicks`)) * 100 AS cvr,
  SUM(`total`.`cost`) / (SUM(`total`.`conversions`) + COUNT(`call1`.`id`)) AS cpa,
  AVG(`total`.`avgPosition`) AS avgPosition
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
      `total`.`hourOfDay` = `conv1`.`hourOfDay`
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
      `total`.`hourOfDay` = `conv2`.`hourOfDay`
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
      `total`.`hourOfDay` = `conv3`.`hourOfDay`
    AND
      `total`.`day` = `conv3`.`day`
    AND
      `conv3`.`conversionName` = 'Conversion name3'
LEFT JOIN
  `repo_phone_time_use` as call1
  ON
      `total`.`account_id` = `call1`.`account_id`
    AND
      `total`.`campaign_id` = `call1`.`campaign_id`
    AND
      `call1`.`utm_campaign` IN (11)
    AND
      `call1`.`source` = 'adw'
    AND
      `call1`.`traffic_type` = 'AD'
    AND
      `call1`.`phone_number` = '+841234567811'
    AND
      HOUR(`call1`.`time_of_call`) = `total`.`hourOfDay`
    AND
      `call1`.`time_of_call` LIKE CONCAT(`total`.`day`, '%')
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
  `total`.`hourOfDay`