/* Get all unique conversion point for Ad report */
SELECT
	DISTINCT
	`adID`,
	`conversionName`
FROM
	`repo_ydn_reports`
WHERE
  `repo_ydn_reports`.`account_id` = 1
AND
  `repo_ydn_reports`.`accountId` = 11
AND
  `repo_ydn_reports`.`campaignID` = 11
AND
  `repo_ydn_reports`.`adGroupID` = 11111111;

/*
	result of that query

    adID	conversionName
    1111111111111111	YDN conversion 111110
    1111111111111111	YDN conversion 111111
    1111111111111112	YDN conversion 111110
    1111111111111112	YDN conversion 111111
    1111111111111113	YDN conversion 111110
    1111111111111113	YDN conversion 111111
*/

/* Get all phone number */
SELECT
	DISTINCT
	`c`.`campaign_id`,
  	`c`.`campaign_name`,
  	`ptu`.`utm_campaign`,
  	`ptu`.`phone_number`
FROM
	`campaigns` as c,
	`phone_time_use` as ptu
WHERE
  	`c`.`campaign_id` = `ptu`.`campaign_id`
AND
  	`c`.`account_id` = `ptu`.`account_id`
AND
  	`c`.`account_id` = 1
AND
  	`ptu`.`utm_campaign` = 11
AND
  	`ptu`.`source` = 'ydn'
AND
	`ptu`.`traffic_type` = 'AD'
AND
	(
		(
			`c`.`camp_custom1` = 'creative'
		AND
			`ptu`.`custom1` IN (1111111111111111, 1111111111111112, 1111111111111113)
		)
	OR
		(
			`c`.`camp_custom2` = 'creative'
		AND
			`ptu`.`custom2` IN (1111111111111111, 1111111111111112, 1111111111111113)
		)
	OR
		(
			`c`.`camp_custom3` = 'creative'
		AND
			`ptu`.`custom3` IN (1111111111111111, 1111111111111112, 1111111111111113)
		)
	OR
		(
			`c`.`camp_custom4` = 'creative'
		AND
			`ptu`.`custom4` IN ('1111111111111111', '1111111111111112', '1111111111111113')
		)
OR
		(
			`c`.`camp_custom5` = 'creative'
		AND
			`ptu`.`custom5` IN (1111111111111111, 1111111111111112, 1111111111111113)
		)
	OR
		(
			`c`.`camp_custom6` = 'creative'
		AND
			`ptu`.`custom6` IN (1111111111111111, 1111111111111112, 1111111111111113)
		)
	OR
		(
			`c`.`camp_custom7` = 'creative'
		AND
			`ptu`.`custom7` IN (1111111111111111, 1111111111111112, 1111111111111113)
		)
	OR
		(
			`c`.`camp_custom8` = 'creative'
		AND
			`ptu`.`custom8` IN (1111111111111111, 1111111111111112, 1111111111111113)
		)
	OR
		(
			`c`.`camp_custom9` = 'creative'
		AND
			`ptu`.`custom9` IN (1111111111111111, 1111111111111112, 1111111111111113)
		)
	OR
		(
			`c`.`camp_custom10` = 'creative'
		AND
			`ptu`.`custom10` IN (1111111111111111, 1111111111111112, 1111111111111113)
		)
	);
/* result of the query

campaign_id	campaign_name	utm_campaign	phone_number
11	Campaign Name	11	+841234567811
*/

SELECT
	`total`.`account_id`,
	`total`.`accountId`,
	`total`.`campaignId`,
	`total`.`adGroupId`,
	`total`.`adId`,
	SUM(`conv1`.`conversions`) AS 'YDN conversion 111110 CV',
	SUM(`conv2`.`conversions`) AS 'YDN conversion 111111 CV',
	COUNT(`ptu1`.`id`) AS 'Adgroup Name +841234567811 CV',
	COUNT(`ptu1`.`id`) AS call_cv,
	SUM(`total`.`conversions`) as web_cv
FROM
	`repo_ydn_reports` as total
LEFT JOIN
	`repo_ydn_reports` as conv1
ON
	(
		`total`.`account_id` = `conv1`.`account_id`
	AND
		`total`.`accountId` = `conv1`.`accountId`
	AND
		`total`.`campaignID` = `conv1`.`campaignID`
	AND
		`conv1`.`campaignID` = 11
	AND
		`conv1`.`adGroupID` = 11111111
	AND
		`conv1`.`adID` IN (1111111111111111, 1111111111111112, 1111111111111113)
	AND
		`total`.`day` = `conv1`.`day`
	AND
		`conv1`.`conversionName` = 'YDN conversion 111110'
	)
LEFT JOIN
	`repo_ydn_reports` as conv2
ON
	(
		`total`.`account_id` = `conv2`.`account_id`
	AND
		`total`.`accountId` = `conv2`.`accountId`
	AND
		`total`.`campaignID` = `conv2`.`campaignID`
	AND
		`conv2`.`campaignID` = 11
	AND
		`conv2`.`adGroupID` = 11111111
	AND
		`conv2`.`adID` IN (1111111111111111, 1111111111111112, 1111111111111113)
	AND
		`total`.`day` = `conv2`.`day`
	AND
		`conv2`.`conversionName` = 'YDN conversion 111111'
	)
LEFT JOIN
	(`phone_time_use` as ptu1, `campaigns` as c1)
ON
	(
		`c1`.`campaign_id` = `ptu1`.`campaign_id`
	AND
  		`c1`.`account_id` = `ptu1`.`account_id`
	AND
  		`c1`.`account_id` = 1
	AND
  		`ptu1`.`utm_campaign` = 11
	AND
  		`ptu1`.`source` = 'ydn'
	AND
		`ptu1`.`traffic_type` = 'AD'
	AND
		(
			(
				`c1`.`camp_custom1` = 'creative'
			AND
				`ptu1`.`custom1` IN (1111111111111111, 1111111111111112, 1111111111111113)
			)
		OR
			(
				`c1`.`camp_custom2` = 'creative'
			AND
				`ptu1`.`custom2` IN (1111111111111111, 1111111111111112, 1111111111111113)
			)
		OR
			(
				`c1`.`camp_custom3` = 'creative'
			AND
				`ptu1`.`custom3` IN (1111111111111111, 1111111111111112, 1111111111111113)
			)
		OR
			(
				`c1`.`camp_custom4` = 'creative'
			AND
				`ptu1`.`custom4` IN (1111111111111111, 1111111111111112, 1111111111111113)
			)
		OR
			(
				`c1`.`camp_custom5` = 'creative'
			AND
				`ptu1`.`custom5` IN (1111111111111111, 1111111111111112, 1111111111111113)
			)
		OR
			(
				`c1`.`camp_custom6` = 'creative'
			AND
				`ptu1`.`custom6` IN (1111111111111111, 1111111111111112, 1111111111111113)
			)
		OR
			(
				`c1`.`camp_custom7` = 'creative'
			AND
				`ptu1`.`custom7` IN (1111111111111111, 1111111111111112, 1111111111111113)
			)
		OR
			(
				`c1`.`camp_custom8` = 'creative'
			AND
				`ptu1`.`custom8` IN (1111111111111111, 1111111111111112, 1111111111111113)
			)
		OR
			(
				`c1`.`camp_custom9` = 'creative'
			AND
				`ptu1`.`custom9` IN (1111111111111111, 1111111111111112, 1111111111111113)
			)
		OR
			(
				`c1`.`camp_custom10` = 'creative'
			AND
				`ptu1`.`custom10` IN (1111111111111111, 1111111111111112, 1111111111111113)
			)
		)
	)
WHERE
	`total`.`account_id` = 1
AND
	`total`.`accountId` = 11
AND
	`total`.`campaignId` = 11
AND
	`total`.`adGroupId` = 11111111
AND
	`total`.`day` >= '2017-01-01'
AND
	`total`.`day` <= '2017-12-31'
GROUP BY
  	`total`.`account_id`,
  	`total`.`accountId`,
  	`total`.`campaignID`,
  	`total`.`adGroupId`,
  	`total`.`adId`
