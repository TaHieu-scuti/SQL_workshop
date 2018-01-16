SELECT
  'UNKNOWN' as device,
  SUM(`repo_adw_campaign_report_cost`.`clicks`) as clicks,
  SUM(`repo_adw_campaign_report_cost`.`cost`) as cost,
  COUNT(`repo_phone_time_use`.`id`) as call_cv,
  SUM(`repo_adw_campaign_report_cost`.`conversions`) as web_cv
FROM
  `repo_adw_campaign_report_cost`
JOIN
  `repo_phone_time_use`
  ON
      `repo_phone_time_use`.`account_id` = `repo_adw_campaign_report_cost`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_adw_campaign_report_cost`.`campaign_id`
    AND
      `repo_adw_campaign_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`source` = 'adw'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
    AND
      `repo_phone_time_use`.`platform` LIKE 'Unknown Platform%'
WHERE
  `repo_adw_campaign_report_cost`.`account_id` = 1
AND
  `repo_adw_campaign_report_cost`.`campaign_id` = 11
AND
  `repo_adw_campaign_report_cost`.`customerID` = 11
AND
  `repo_adw_campaign_report_cost`.`device` = 'UNKNOWN'
AND
  `repo_adw_campaign_report_cost`.`day` >= '2017-01-01'
AND
  `repo_adw_campaign_report_cost`.`day` <= '2017-12-01'
AND
  (
    `repo_adw_campaign_report_cost`.`network` = 'SEARCH'
  AND
    `repo_adw_campaign_report_cost`.`network` = 'CONTENT'
  )
GROUP BY
  device

UNION

SELECT
  'DESKTOP' as device,
  SUM(`repo_adw_campaign_report_cost`.`clicks`) as clicks,
  SUM(`repo_adw_campaign_report_cost`.`cost`) as cost,
  COUNT(`repo_phone_time_use`.`id`) as call_cv,
  SUM(`repo_adw_campaign_report_cost`.`conversions`) as web_cv

FROM
  `repo_adw_campaign_report_cost`
JOIN
  `repo_phone_time_use`
  ON
      `repo_phone_time_use`.`account_id` = `repo_adw_campaign_report_cost`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_adw_campaign_report_cost`.`campaign_id`
    AND
      `repo_adw_campaign_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`source` = 'adw'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
    AND
      (
          `repo_phone_time_use`.`mobile` = 'No'
        AND
          `repo_phone_time_use`.`platform` NOT LIKE 'Window Phone%'
        AND
          (
              `repo_phone_time_use`.`platform` LIKE 'Windows%'
            OR
              `repo_phone_time_use`.`platform` LIKE 'Linux%'
            OR
              `repo_phone_time_use`.`platform` LIKE 'Mac OS%'
            OR
              `repo_phone_time_use`.`platform` LIKE 'FreeBSD%'
            OR
              `repo_phone_time_use`.`platform` LIKE 'NetBSD%'
            OR
              `repo_phone_time_use`.`platform` LIKE 'Unknown Windows OS%'
          )
      )
WHERE
  `repo_adw_campaign_report_cost`.`device` = 'DESKTOP'
AND
  `repo_adw_campaign_report_cost`.`account_id` = 1
AND
  `repo_adw_campaign_report_cost`.`campaign_id` = 11
AND
  `repo_adw_campaign_report_cost`.`customerID` = 11
AND
  `repo_adw_campaign_report_cost`.`day` >= '2017-01-01'
AND
  `repo_adw_campaign_report_cost`.`day` <= '2017-12-01'
AND
  (
    `repo_adw_campaign_report_cost`.`network` = 'SEARCH'
  AND
    `repo_adw_campaign_report_cost`.`network` = 'CONTENT'
  )
GROUP BY
  device

UNION

SELECT
  'HIGH_END_MOBILE' as device,
  SUM(`repo_adw_campaign_report_cost`.`clicks`) as clicks,
  SUM(`repo_adw_campaign_report_cost`.`cost`) as cost,
  COUNT(`repo_phone_time_use`.`id`) as call_cv,
  SUM(`repo_adw_campaign_report_cost`.`conversions`) as web_cv

FROM
  `repo_adw_campaign_report_cost`
JOIN
  `repo_phone_time_use`
  ON
      `repo_phone_time_use`.`account_id` = `repo_adw_campaign_report_cost`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_adw_campaign_report_cost`.`campaign_id`
    AND
      `repo_adw_campaign_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`source` = 'adw'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
    AND
      `repo_phone_time_use`.`mobile` = 'Yes'
WHERE
  `repo_adw_campaign_report_cost`.`device` = 'HIGH_END_MOBILE'
AND
  `repo_adw_campaign_report_cost`.`account_id` = 1
AND
  `repo_adw_campaign_report_cost`.`campaign_id` = 11
AND
  `repo_adw_campaign_report_cost`.`customerID` = 11
AND
  `repo_adw_campaign_report_cost`.`day` >= '2017-01-01'
AND
  `repo_adw_campaign_report_cost`.`day` <= '2017-12-01'
AND
  (
    `repo_adw_campaign_report_cost`.`network` = 'SEARCH'
  AND
    `repo_adw_campaign_report_cost`.`network` = 'CONTENT'
  )
GROUP BY
  device

UNION

SELECT
  'TABLET' as device,
  SUM(`repo_adw_campaign_report_cost`.`clicks`) as clicks,
  SUM(`repo_adw_campaign_report_cost`.`cost`) as cost,
  COUNT(`repo_phone_time_use`.`id`) as call_cv,
  SUM(`repo_adw_campaign_report_cost`.`conversions`) as web_cv

FROM
  `repo_adw_campaign_report_cost`
JOIN
  `repo_phone_time_use`
  ON
      `repo_phone_time_use`.`account_id` = `repo_adw_campaign_report_cost`.`account_id`
    AND
      `repo_phone_time_use`.`campaign_id` = `repo_adw_campaign_report_cost`.`campaign_id`
    AND
      `repo_adw_campaign_report_cost`.`day` = STR_TO_DATE(`repo_phone_time_use`.`time_of_call`, '%Y-%m-%d')
    AND
      `repo_phone_time_use`.`source` = 'adw'
    AND
      `repo_phone_time_use`.`traffic_type` = 'AD'
    AND
      `repo_phone_time_use`.`mobile` = 'No'
    AND
      `repo_phone_time_use`.`platform` LIKE 'Android%'
    AND
      (
          `repo_phone_time_use`.`mobile` = 'No'
        AND
          (
              `repo_phone_time_use`.`platform` LIKE 'iOS%'
            OR
              `repo_phone_time_use`.`platform` LIKE 'Android%'
            OR
              `repo_phone_time_use`.`platform` LIKE 'Blackberry'
          )
      )
WHERE
  `repo_adw_campaign_report_cost`.`device` = 'TABLET'
AND
  `repo_adw_campaign_report_cost`.`account_id` = 1
AND
  `repo_adw_campaign_report_cost`.`campaign_id` = 11
AND
  `repo_adw_campaign_report_cost`.`customerID` = 11
AND
  `repo_adw_campaign_report_cost`.`day` >= '2017-01-01'
AND
  `repo_adw_campaign_report_cost`.`day` <= '2017-12-01'
AND
  (
    `repo_adw_campaign_report_cost`.`network` = 'SEARCH'
  AND
    `repo_adw_campaign_report_cost`.`network` = 'CONTENT'
  )
GROUP BY
  device;
