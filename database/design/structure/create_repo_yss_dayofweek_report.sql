/**
 * ADgaienr Solutions Reporting System
 * Schema : ADGAINER_db_SECURE
 * Table Name : repo_yss_dayofweek_report
 * Auther : Tetsuya Takiguchi
 */
CREATE TABLE IF NOT EXISTS `ADGAINER_db_SECURE`.`repo_yss_dayofweek_report` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `exeDate` DATE NOT NULL COMMENT 'YSSレポートAPI実行日',
  `startDate` DATE NOT NULL COMMENT 'YSSレポートAPIで指定したレポートの開始日',
  `endDate` DATE NOT NULL COMMENT 'YSSレポートAPIで指定したレポートの終了日',
  `account_id` VARCHAR(50) NULL DEFAULT NULL COMMENT 'ADgainerシステムのアカウントID',
  `campaign_id` VARCHAR(50) NULL DEFAULT NULL COMMENT 'ADgainerシステムのキャンペーンID\ndestinationURLのクエリパラメータを分解して取得',
  `accountid` INT(20) NULL DEFAULT NULL COMMENT 'YSSのアカウントID。レポートのダウンロードURL取得時のアカウントIDを入れます。',
  `campaignID` INT(20) NULL DEFAULT NULL COMMENT 'キャンペーンID',
  `campaignName` TEXT NULL DEFAULT NULL COMMENT 'キャンペーン名',
  `cost` INT(20) NULL DEFAULT NULL COMMENT 'コスト',
  `impressions` INT(20) NULL DEFAULT NULL COMMENT 'インプレッション数',
  `clicks` INT(20) NULL DEFAULT NULL COMMENT 'クリック数',
  `ctr` DOUBLE NULL DEFAULT NULL COMMENT 'クリック率',
  `averageCpc` DOUBLE NULL DEFAULT NULL COMMENT '平均CPC',
  `averagePosition` DOUBLE NULL DEFAULT NULL COMMENT '平均掲載順位',
  `bidAdjustment` INT(20) NULL DEFAULT NULL COMMENT '入札価格調整率(％)',
  `targetScheduleID` INT(20) NULL DEFAULT NULL COMMENT '曜日・時間帯ID',
  `targetSchedule` VARCHAR(50) NULL DEFAULT NULL COMMENT '曜日・時間帯',
  `conversions` DOUBLE NULL DEFAULT NULL COMMENT 'コンバージョン数',
  `convRate` DOUBLE NULL DEFAULT NULL COMMENT 'コンバージョン率',
  `convValue` DOUBLE NULL DEFAULT NULL COMMENT 'コンバージョンの価値',
  `costPerConv` DOUBLE NULL DEFAULT NULL COMMENT 'コスト/コンバージョン数',
  `valuePerConv` DOUBLE NULL DEFAULT NULL COMMENT '価値/コンバージョン数',
  `allConv` DOUBLE NULL DEFAULT NULL COMMENT 'すべてのコンバージョン数',
  `allConvRate` DOUBLE NULL DEFAULT NULL COMMENT 'すべてのコンバージョン率',
  `allConvValue` DOUBLE NULL DEFAULT NULL COMMENT 'すべてのコンバージョンの価値',
  `costPerAllConv` DOUBLE NULL DEFAULT NULL COMMENT 'コスト/すべてのコンバージョン数',
  `valuePerAllConv` DOUBLE NULL DEFAULT NULL COMMENT '価値/すべてのコンバージョン数',
  `day` DATETIME NULL DEFAULT NULL COMMENT 'レコードの対象日：年（year）、月（monthofYear）、日（day）。左項目を加工してDATETIMEに変換',
  `quarter` VARCHAR(50) NULL DEFAULT NULL COMMENT '四半期',
  `month` VARCHAR(50) NULL DEFAULT NULL COMMENT '毎月',
  `week` VARCHAR(50) NULL DEFAULT NULL COMMENT '毎週',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `repo_yss_dayofweek_report_idx1` (`account_id` ASC),
  INDEX `repo_yss_dayofweek_report_idx2` (`campaign_id` ASC),
  INDEX `repo_yss_dayofweek_report_idx3` (`campaignID` ASC),
  INDEX `repo_yss_dayofweek_report_idx4` (`day` ASC),
  INDEX `repo_yss_dayofweek_report_idx5` (`quarter` ASC),
  INDEX `repo_yss_dayofweek_report_idx6` (`month` ASC),
  INDEX `repo_yss_dayofweek_report_idx7` (`week` ASC),
  INDEX `repo_yss_dayofweek_report_idx8` (`exeDate` ASC),
  INDEX `repo_yss_dayofweek_report_idx9` (`startDate` ASC),
  INDEX `repo_yss_dayofweek_report_idx10` (`endDate` ASC),
  INDEX `repo_yss_dayofweek_report_idx11` (`accountid` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'YSS曜日別レポート';
