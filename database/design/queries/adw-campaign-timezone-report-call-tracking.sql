SELECT
	`repo_adw_campaign_report_cost`.`account_id`,
	`repo_adw_campaign_report_cost`.`campaign_id`,
	`repo_adw_campaign_report_cost`.`hourOfDay` AS timezone,
	SUM(`repo_adw_campaign_report_cost`.`impressions`) AS impressions,
	SUM(`repo_adw_campaign_report_cost`.`clicks`) AS clicks,
	SUM(`repo_adw_campaign_report_cost`.`cost`) AS cost,
	AVG(`repo_adw_campaign_report_cost`.`ctr`) AS ctr,
	AVG(`repo_adw_campaign_report_cost`.`avgCPC`) AS avgCPC,
	COUNT(`phone_time_use`.`id`) AS call_tracking,
	SUM(`repo_adw_campaign_report_cost`.`conversions`) AS webcv,
	SUM(`repo_adw_campaign_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`) AS cv,
	((SUM(`repo_adw_campaign_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) / SUM(`repo_adw_campaign_report_cost`.`clicks`)) * 100 AS cvr,
	SUM(`repo_adw_campaign_report_cost`.`cost`) / (SUM(`repo_adw_campaign_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) AS cpa,
	AVG(`repo_adw_campaign_report_cost`.`avgPosition`) AS avgPosition
FROM
	`repo_adw_campaign_report_cost`
		LEFT JOIN `phone_time_use`
		ON (
				`phone_time_use`.`account_id` = `repo_adw_campaign_report_cost`.`account_id`
			AND
				`phone_time_use`.`campaign_id` = `repo_adw_campaign_report_cost`.`campaign_id`
			AND
				`phone_time_use`.`utm_campaign` = `repo_adw_campaign_report_cost`.`campaignID`
			AND
				`phone_time_use`.`time_of_call` >= '2017-01-01'
			AND
				`phone_time_use`.`time_of_call` <= '2017-12-01'
			AND
				`phone_time_use`.`source` = 'adw'
			AND
				`phone_time_use`.`traffic_type` = 'AD'
			AND
				HOUR(`phone_time_use`.`time_of_call`) = `repo_adw_campaign_report_cost`.`hourOfDay`
		)
WHERE
	`repo_adw_campaign_report_cost`.`account_id` = 1
AND
	`repo_adw_campaign_report_cost`.`campaign_id` = 11
#AND
	#`repo_adw_campaign_report_cost`.`customerID` = 11
AND
	`repo_adw_campaign_report_cost`.`day` >= '2017-01-01'
AND
	`repo_adw_campaign_report_cost`.`day` <= '2017-12-01'
AND
	(
		`repo_adw_campaign_report_cost`.`network` = 'SEARCH'
	OR
		`repo_adw_campaign_report_cost`.`network` = 'CONTENT'
	)
GROUP BY
	`repo_adw_campaign_report_cost`.`account_id`,
	`repo_adw_campaign_report_cost`.`campaign_id`,
	#`repo_adw_campaign_report_cost`.`customerID`,
	`repo_adw_campaign_report_cost`.`campaignID`,
	`repo_adw_campaign_report_cost`.`hourOfDay`
