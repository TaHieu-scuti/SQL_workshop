SELECT
	`repo_yss_keyword_report_cost`.`account_id`,
	`repo_yss_keyword_report_cost`.`campaign_id`,
	#`repo_yss_keyword_report_cost`.`customerID`,
	`repo_yss_keyword_report_cost`.`campaignID`,
	`repo_yss_keyword_report_cost`.`adGroupId`,
	`repo_yss_keyword_report_cost`.`keyword`,
	SUM(`repo_yss_keyword_report_cost`.`impressions`) AS impressions,
	SUM(`repo_yss_keyword_report_cost`.`clicks`) AS clicks,
	SUM(`repo_yss_keyword_report_cost`.`cost`) AS cost,
	AVG(`repo_yss_keyword_report_cost`.`ctr`) AS ctr,
	AVG(`repo_yss_keyword_report_cost`.`averageCPC`) AS avgCPC,
	COUNT(`phone_time_use`.`id`) AS call_tracking,
	SUM(`repo_yss_keyword_report_cost`.`conversions`) AS webcv,
	SUM(`repo_yss_keyword_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`) AS cv,
	((SUM(`repo_yss_keyword_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) / SUM(`repo_yss_keyword_report_cost`.`clicks`)) * 100 AS cvr,
	SUM(`repo_yss_keyword_report_cost`.`cost`) / (SUM(`repo_yss_keyword_report_cost`.`conversions`) + COUNT(`phone_time_use`.`id`)) AS cpa,
	AVG(`repo_yss_keyword_report_cost`.`averagePosition`) AS avgPosition
FROM
	`repo_yss_keyword_report_cost`
		LEFT JOIN (`campaigns`, `phone_time_use`)
		ON (
				`campaigns`.`account_id` = `repo_yss_keyword_report_cost`.`account_id`
			AND
				`campaigns`.`campaign_id` = `repo_yss_keyword_report_cost`.`campaign_id`
			AND
				(
					(
						`campaigns`.`camp_custom1` = 'adgroupid'
					AND
						`phone_time_use`.`custom1` = `repo_yss_keyword_report_cost`.`adGroupId`
					)
				OR
					(
						`campaigns`.`camp_custom2` = 'adgroupid'
					AND
						`phone_time_use`.`custom2` = `repo_yss_keyword_report_cost`.`adGroupId`
					)
				OR
					(
						`campaigns`.`camp_custom3` = 'adgroupid'
					AND
						`phone_time_use`.`custom3` = `repo_yss_keyword_report_cost`.`adGroupId`
					)
				OR
					(
						`campaigns`.`camp_custom4` = 'adgroupid'
					AND
						`phone_time_use`.`custom4` = `repo_yss_keyword_report_cost`.`adGroupId`
					)
				OR
					(
						`campaigns`.`camp_custom5` = 'adgroupid'
					AND
						`phone_time_use`.`custom5` = `repo_yss_keyword_report_cost`.`adGroupId`
					)
				OR
					(
						`campaigns`.`camp_custom6` = 'adgroupid'
					AND
						`phone_time_use`.`custom6` = `repo_yss_keyword_report_cost`.`adGroupId`
					)
				OR
					(
						`campaigns`.`camp_custom7` = 'adgroupid'
					AND
						`phone_time_use`.`custom7` = `repo_yss_keyword_report_cost`.`adGroupId`
					)
				OR
					(
						`campaigns`.`camp_custom8` = 'adgroupid'
					AND
						`phone_time_use`.`custom8` = `repo_yss_keyword_report_cost`.`adGroupId`
					)
				OR
					(
						`campaigns`.`camp_custom9` = 'adgroupid'
					AND
						`phone_time_use`.`custom9` = `repo_yss_keyword_report_cost`.`adGroupId`
					)
				OR
					(
						`campaigns`.`camp_custom10` = 'adgroupid'
					AND
						`phone_time_use`.`custom10` = `repo_yss_keyword_report_cost`.`adGroupId`
					)
				)
			AND
				`phone_time_use`.`utm_campaign` = `repo_yss_keyword_report_cost`.`campaignID`
			AND
				`phone_time_use`.`time_of_call` >= '2017-04-01'
			AND
				`phone_time_use`.`time_of_call` <= '2017-07-01'
			AND
				`phone_time_use`.`source` = 'yss'
			AND
				`phone_time_use`.`matchtype` = `repo_yss_keyword_report_cost`.`keywordMatchType`
			AND
				`phone_time_use`.`j_keyword` = `repo_yss_keyword_report_cost`.`keyword`
		)
WHERE
	`repo_yss_keyword_report_cost`.`account_id` = 1
AND
	`repo_yss_keyword_report_cost`.`campaign_id` = 11
AND
	`repo_yss_keyword_report_cost`.`campaignID` = 11
AND
	`repo_yss_keyword_report_cost`.`day` >= '2017-04-01'
AND
	`repo_yss_keyword_report_cost`.`day` <= '2017-07-01'
GROUP BY
	`repo_yss_keyword_report_cost`.`account_id`,
	`repo_yss_keyword_report_cost`.`campaign_id`,
	#`repo_yss_keyword_report_cost`.`customerID`,
	`repo_yss_keyword_report_cost`.`campaignID`,
	`repo_yss_keyword_report_cost`.`adGroupId`,
	`repo_yss_keyword_report_cost`.`keyword`
