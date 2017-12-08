SELECT
	`repo_ydn_reports`.`account_id`,
	`repo_ydn_reports`.`campaign_id`,
	`repo_ydn_reports`.`accountid`,
	`repo_ydn_reports`.`campaignID`,
	SUM(`repo_ydn_reports`.`impressions`) AS impressions,
	SUM(`repo_ydn_reports`.`clicks`) AS clicks,
	SUM(`repo_ydn_reports`.`cost`) AS cost,
	AVG(`repo_ydn_reports`.`ctr`) AS ctr,
	AVG(`repo_ydn_reports`.`averageCPC`) AS avgCPC,
	COUNT(`phone_time_use`.`id`) AS call_tracking,
	SUM(`repo_ydn_reports`.`conversions`) AS webcv,
	SUM(`repo_ydn_reports`.`conversions`) + COUNT(`phone_time_use`.`id`) AS cv,
	((SUM(`repo_ydn_reports`.`conversions`) + COUNT(`phone_time_use`.`id`)) / SUM(`repo_ydn_reports`.`clicks`)) * 100 AS cvr,
	SUM(`repo_ydn_reports`.`cost`) / (SUM(`repo_ydn_reports`.`conversions`) + COUNT(`phone_time_use`.`id`)) AS cpa,
	AVG(`repo_ydn_reports`.`averagePosition`) AS avgPosition
FROM
	`repo_ydn_reports`
		LEFT JOIN (`campaigns`, `phone_time_use`)
		ON (
				`phone_time_use`.`utm_campaign` = `repo_ydn_reports`.`campaignID`
			AND
				`phone_time_use`.`time_of_call` >= '2017-01-01'
			AND
				`phone_time_use`.`time_of_call` <= '2017-12-01'
			AND
				`phone_time_use`.`source` = 'yss'
			AND
				`phone_time_use`.`traffic_type` = 'AD'
		)
WHERE
	`repo_ydn_reports`.`account_id` = 1
AND
	`repo_ydn_reports`.`campaign_id` = 11
AND
	`repo_ydn_reports`.`accountid` = 11
AND
	`repo_ydn_reports`.`day` >= '2017-01-01'
AND
	`repo_ydn_reports`.`day` <= '2017-12-01'
GROUP BY
	`repo_ydn_reports`.`account_id`,
	`repo_ydn_reports`.`campaign_id`,
	`repo_ydn_reports`.`accountid`,
	`repo_ydn_reports`.`campaignID`
