select
  distinct
  hour(`repo_yss_adgroup_report_conv`.`day`),
  `repo_yss_adgroup_report_conv`.`conversionName`
from
  `repo_yss_adgroup_report_conv`
WHERE
  `repo_yss_adgroup_report_conv`.`account_id` = 1
AND
  `repo_yss_adgroup_report_conv`.`campaign_id` = 11
AND
  `repo_yss_adgroup_report_conv`.`accountid` = 11
AND
  `repo_yss_adgroup_report_conv`.`campaignID` = 11;

/* result of query
hour(`repo_yss_adgroup_report_conv`.`day`)	conversionName
18	YSS conversion 111110
18	YSS conversion 111111
18	YSS conversion 111112
8	YSS conversion 111110
8	YSS conversion 111111
8	YSS conversion 111112
14	YSS conversion 111110
14	YSS conversion 111111
14	YSS conversion 111112
16	YSS conversion 111110
16	YSS conversion 111111
16	YSS conversion 111112
22	YSS conversion 111110
22	YSS conversion 111111
22	YSS conversion 111112
19	YSS conversion 111110
19	YSS conversion 111111
19	YSS conversion 111112
13	YSS conversion 111110
13	YSS conversion 111111
13	YSS conversion 111112
12	YSS conversion 111110
12	YSS conversion 111111
12	YSS conversion 111112
1	YSS conversion 111110
1	YSS conversion 111111
1	YSS conversion 111112
11	YSS conversion 111110
11	YSS conversion 111111
11	YSS conversion 111112
7	YSS conversion 111110
7	YSS conversion 111111
7	YSS conversion 111112
0	YSS conversion 111110
0	YSS conversion 111111
0	YSS conversion 111112
3	YSS conversion 111110
3	YSS conversion 111111
3	YSS conversion 111112
2	YSS conversion 111110
2	YSS conversion 111111
2	YSS conversion 111112
10	YSS conversion 111110
10	YSS conversion 111111
10	YSS conversion 111112
9	YSS conversion 111110
9	YSS conversion 111111
9	YSS conversion 111112
17	YSS conversion 111110
17	YSS conversion 111111
17	YSS conversion 111112
20	YSS conversion 111110
20	YSS conversion 111111
20	YSS conversion 111112
21	YSS conversion 111110
21	YSS conversion 111111
21	YSS conversion 111112
6	YSS conversion 111110
6	YSS conversion 111111
6	YSS conversion 111112
4	YSS conversion 111110
4	YSS conversion 111111
4	YSS conversion 111112
23	YSS conversion 111110
23	YSS conversion 111111
23	YSS conversion 111112
5	YSS conversion 111110
5	YSS conversion 111111
5	YSS conversion 111112
15	YSS conversion 111110
15	YSS conversion 111111
15	YSS conversion 111112
*/

select
  distinct
  hour(`repo_phone_time_use`.`time_of_call`),
  `repo_phone_time_use`.`campaign_id`,
  `repo_phone_time_use`.`phone_number`
from
  repo_phone_time_use
where
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
hour(`repo_phone_time_use`.`time_of_call`)	campaign_id	phone_number
9	11	+841234567811
6	11	+841234567811
12	11	+841234567811
15	11	+841234567811
0	11	+841234567811
11	11	+841234567811
14	11	+841234567811
5	11	+841234567811
13	11	+841234567811
10	11	+841234567811
1	11	+841234567811
3	11	+841234567811
19	11	+841234567811
8	11	+841234567811
*/

select
  `total`.`hourofday`,
  SUM(`conv1`.`conversions`) as 'YSS conversion 111110 CV',
  SUM(`conv2`.`conversions`) as 'YSS conversion 111111 CV',
  SUM(`conv3`.`conversions`) as 'YSS conversion 111112 CV',
  COUNT(`conv4`.`id`) as 'Campaign Name +841234567811 CV'
from
  `repo_yss_adgroup_report_cost` as total
left join
  `repo_yss_adgroup_report_conv` as conv1
  ON
      `conv1`.`account_id` = `total`.`account_id`
    AND
      `conv1`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv1`.`accountid` = `total`.`accountid`
    AND
      `conv1`.`campaignID` = `total`.`campaignID`
    AND
      `conv1`.`adGroupID` = `total`.`adGroupID`
    AND
      `conv1`.`day` = `total`.`day`
    AND
      hour(`conv1`.`day`) = `total`.`hourofday`
    AND
      `conv1`.`conversionName` = 'YSS conversion 111110'
left join
  `repo_yss_adgroup_report_conv` as conv2
  ON
      `conv2`.`account_id` = `total`.`account_id`
    AND
      `conv2`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv2`.`accountid` = `total`.`accountid`
    AND
      `conv2`.`campaignID` = `total`.`campaignID`
    AND
      `conv2`.`adGroupID` = `total`.`adGroupID`
    AND
      `conv2`.`day` = `total`.`day`
    AND
      hour(`conv2`.`day`) = `total`.`hourofday`
    AND
      `conv2`.`conversionName` = 'YSS conversion 111111'
left join
  `repo_yss_adgroup_report_conv` as conv3
  ON
      `conv3`.`account_id` = `total`.`account_id`
    AND
      `conv3`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv3`.`accountid` = `total`.`accountid`
    AND
      `conv3`.`campaignID` = `total`.`campaignID`
    AND
      `conv3`.`adGroupID` = `total`.`adGroupID`
    AND
      `conv3`.`day` = `total`.`day`
    AND
      hour(`conv3`.`day`) = `total`.`hourofday`
    AND
      `conv3`.`conversionName` = 'YSS conversion 111112'
left join
  `repo_phone_time_use` as `conv4`
  ON
      `conv4`.`account_id` = `total`.`account_id`
    AND
      `conv4`.`campaign_id` = `total`.`campaign_id`
    AND
      `conv4`.`utm_campaign` = `total`.`campaignID`
    AND
      `conv4`.`time_of_call` LIKE CONCAT(`total`.`day`)
    AND
      hour(`conv4`.`time_of_call`) = `total`.`hourofday`
    AND
      `conv4`.`source` = 'yss'
    AND
      `conv4`.`traffic_type` = 'AD'
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
  `total`.`day` <= '2017-12-01'
GROUP BY
  `total`.`account_id`,
  `total`.`campaign_id`,
  `total`.`accountid`,
  `total`.`campaignID`,
  `total`.`hourofday`
