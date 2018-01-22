select
  distinct
  conversionName,
  campaignID,
  hour(day)
from
  `repo_yss_campaign_report_conv`
where
  `account_id` = 1
AND
  `campaign_id` = 11
AND
  `accountid` = 11
ORDER BY
  `hour(day)`
ASC;

/* result of query
conversionName	campaignID	hour(day)
YSS conversion 111110	11	0
YSS conversion 111111	11	0
YSS conversion 111112	11	0
YSS conversion 111110	11	1
YSS conversion 111111	11	1
YSS conversion 111112	11	1
YSS conversion 111110	11	2
YSS conversion 111111	11	2
YSS conversion 111112	11	2
YSS conversion 111110	11	3
YSS conversion 111111	11	3
YSS conversion 111112	11	3
YSS conversion 111110	11	4
YSS conversion 111111	11	4
YSS conversion 111112	11	4
YSS conversion 111110	11	5
YSS conversion 111111	11	5
YSS conversion 111112	11	5
YSS conversion 111110	11	6
YSS conversion 111111	11	6
YSS conversion 111112	11	6
YSS conversion 111110	11	7
YSS conversion 111111	11	7
YSS conversion 111112	11	7
YSS conversion 111110	11	8
YSS conversion 111111	11	8
YSS conversion 111112	11	8
YSS conversion 111110	11	9
YSS conversion 111111	11	9
YSS conversion 111112	11	9
YSS conversion 111110	11	10
YSS conversion 111111	11	10
YSS conversion 111112	11	10
YSS conversion 111110	11	11
YSS conversion 111111	11	11
YSS conversion 111112	11	11
YSS conversion 111110	11	12
YSS conversion 111111	11	12
YSS conversion 111112	11	12
YSS conversion 111110	11	13
YSS conversion 111111	11	13
YSS conversion 111112	11	13
YSS conversion 111110	11	14
YSS conversion 111111	11	14
YSS conversion 111112	11	14
YSS conversion 111110	11	15
YSS conversion 111111	11	15
YSS conversion 111112	11	15
YSS conversion 111110	11	16
YSS conversion 111111	11	16
YSS conversion 111112	11	16
YSS conversion 111110	11	17
YSS conversion 111111	11	17
YSS conversion 111112	11	17
YSS conversion 111110	11	18
YSS conversion 111111	11	18
YSS conversion 111112	11	18
YSS conversion 111110	11	19
YSS conversion 111111	11	19
YSS conversion 111112	11	19
YSS conversion 111110	11	20
YSS conversion 111111	11	20
YSS conversion 111112	11	20
YSS conversion 111110	11	21
YSS conversion 111111	11	21
YSS conversion 111112	11	21
YSS conversion 111110	11	22
YSS conversion 111111	11	22
YSS conversion 111112	11	22
YSS conversion 111110	11	23
YSS conversion 111111	11	23
YSS conversion 111112	11	23
*/

SELECT
  distinct
  `reptu`.`phone_number`,
  `reptu`.`account_id`,
  `reptu`.`campaign_id`,
  hour(`reptu`.`time_of_call`)
FROM
  `repo_phone_time_use` as `reptu`
WHERE
  `reptu`.`account_id` = 1
AND
  `reptu`.`campaign_id` = 11
AND
  `reptu`.`utm_campaign` IN (11)
AND
  `reptu`.`source` = 'YSS'
AND
  `reptu`.`traffic_type` = 'AD'
ORDER BY
  hour(`reptu`.`time_of_call`)
ASC;
/* result of query
phone_number	account_id	campaign_id	hour(`reptu`.`time_of_call`)
+841234567811	1	11	0
+841234567811	1	11	1
+841234567811	1	11	3
+841234567811	1	11	5
+841234567811	1	11	6
+841234567811	1	11	8
+841234567811	1	11	9
+841234567811	1	11	10
+841234567811	1	11	11
+841234567811	1	11	12
+841234567811	1	11	13
+841234567811	1	11	14
+841234567811	1	11	15
+841234567811	1	11	19
*/

select
  `total`.`hourofday`,
  SUM(`conv1`.`conversions`) as 'YSS conversion 111110 CV',
  SUM(`conv2`.`conversions`) as 'YSS conversion 111111 CV',
  SUM(`conv3`.`conversions`) as 'YSS conversion 111112 CV',
  COUNT(`conv4`.`id`) as 'Campaign Name +841234567811 CV',
  SUM(`total`.`conversions`) as web_cv
from
  `repo_yss_campaign_report_cost` as `total`
LEFT JOIN
  `repo_yss_campaign_report_conv` as `conv1`
  ON
      `conv1`.`account_id` = `total`.`account_id`
    AND
      `conv1`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv1`.`accountid` = `total`.`accountid`
    AND
      `conv1`.`campaignID` = `total`.`campaignID`
    AND
      `conv1`.`day` = `total`.`day`
    AND
      hour(`conv1`.`day`) = `total`.`hourofday`
    AND
      `conv1`.`conversionName` = 'YSS conversion 111110'
LEFT JOIN
  `repo_yss_campaign_report_conv` as `conv2`
  ON
      `conv2`.`account_id` = `total`.`account_id`
    AND
      `conv2`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv2`.`accountid` = `total`.`accountid`
    AND
      `conv2`.`campaignID` = `total`.`campaignID`
    AND
      `conv2`.`day` = `total`.`day`
    AND
      hour(`conv2`.`day`) = `total`.`hourofday`
    AND
      `conv2`.`conversionName` = 'YSS conversion 111111'
LEFT JOIN
  `repo_yss_campaign_report_conv` as `conv3`
  ON
      `conv3`.`account_id` = `total`.`account_id`
    AND
      `conv3`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv3`.`accountid` = `total`.`accountid`
    AND
      `conv3`.`campaignID` = `total`.`campaignID`
    AND
      `conv3`.`day` = `total`.`day`
    AND
      hour(`conv3`.`day`) = `total`.`hourofday`
    AND
      `conv3`.`conversionName` = 'YSS conversion 111112'
LEFT JOIN
  `repo_phone_time_use` as `conv4`
  ON
      `conv4`.`account_id` = `total`.`account_id`
    AND
      `conv4`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv4`.`utm_campaign` IN (11)
    AND
      `conv4`.`time_of_call` LIKE CONCAT(`total`.`day`, '%')
    AND
      hour(`conv4`.`time_of_call`) = `total`.`hourofday`
    AND
      `conv4`.`phone_number` = '+841234567811'
    AND
      `conv4`.`source` = 'yss'
    AND
      `conv4`.`traffic_type` = 'AD'
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
  `total`.`account_id`,
  `total`.`hourofday`
