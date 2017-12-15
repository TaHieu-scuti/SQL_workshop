SELECT
	`repo_yss_campaign_report_cost`.`account_id`,
	`repo_yss_campaign_report_cost`.`campaign_id`,
	`repo_yss_campaign_report_cost`.`accountid`,
	`repo_yss_campaign_report_cost`.`campaignID`,
	SUM(`repo_yss_campaign_report_cost`.`impressions`) AS impressions,
	SUM(`repo_yss_campaign_report_cost`.`clicks`) AS clicks,
	SUM(`repo_yss_campaign_report_cost`.`cost`) AS cost,
	AVG(`repo_yss_campaign_report_cost`.`ctr`) AS ctr,
	AVG(`repo_yss_campaign_report_cost`.`averageCPC`) AS avgCPC,
	COUNT(`phone_time_use`.`id`) AS call_tracking,
	SUM(`repo_yss_campaign_report_cost`.`conversions`) AS webcv,
	SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`) AS cv,
	((SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) / SUM(`repo_yss_campaign_report_cost`.`clicks`)) * 100 AS cvr,
	SUM(`repo_yss_campaign_report_cost`.`cost`) / (SUM(`repo_yss_campaign_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) AS cpa,
	AVG(`repo_yss_campaign_report_cost`.`averagePosition`) AS avgPosition
FROM
	`repo_yss_campaign_report_cost`
		LEFT JOIN (`campaigns`, `phone_time_use`)
		ON (
				`phone_time_use`.`utm_campaign` = `repo_yss_campaign_report_cost`.`campaignID`
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
	`repo_yss_campaign_report_cost`.`account_id` = 1
AND
	`repo_yss_campaign_report_cost`.`campaign_id` = 11
AND
	`repo_yss_campaign_report_cost`.`accountid` = 11
AND
	`repo_yss_campaign_report_cost`.`day` >= '2017-01-01'
AND
	`repo_yss_campaign_report_cost`.`day` <= '2017-12-01'
GROUP BY
	`repo_yss_campaign_report_cost`.`account_id`,
	`repo_yss_campaign_report_cost`.`campaign_id`,
	`repo_yss_campaign_report_cost`.`accountid`,
	`repo_yss_campaign_report_cost`.`campaignID`
