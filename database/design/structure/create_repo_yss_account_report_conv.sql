/**
 * ADgaienr Solutions Reporting System
 * Schema : ADGAINER_db_SECURE
 * Table Name : repo_yss_account_report_conv
 * Auther : Tetsuya Takiguchi
 * Update : 2017/08/03 Modify columns & index
 */
CREATE TABLE IF NOT EXISTS `ADGAINER_db_SECURE`.`repo_yss_account_report_conv` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `exeDate` DATE NOT NULL COMMENT 'YSSレポートAPI実行日',
  `startDate` DATE NOT NULL COMMENT 'YSSレポートAPIで指定したレポートの開始日',
  `endDate` DATE NOT NULL COMMENT 'YSSレポートAPIで指定したレポートの終了日',
  `account_id` VARCHAR(50) NULL DEFAULT NULL COMMENT 'ADgainerシステムのアカウントID',
  `campaign_id` VARCHAR(50) NULL DEFAULT NULL COMMENT 'ADgainerシステムのキャンペーンID\ndestinationURLのクエリパラメータを分解して取得',
  `accountid` INT(20) NULL DEFAULT NULL COMMENT 'YSSのアカウントID。レポートのダウンロードURL取得時のアカウントIDを入れます。',
  `trackingURL` TEXT NULL COMMENT 'トラッキングURL',
  `conversions` DOUBLE NULL COMMENT 'コンバージョン数',
  `convValue` DOUBLE NULL COMMENT 'コンバージョンの価値',
  `valuePerConv` DOUBLE NULL COMMENT '価値/コンバージョン数',
  `allConv` DOUBLE NULL COMMENT 'すべてのコンバージョン数',
  `allConvValue` DOUBLE NULL COMMENT 'すべてのコンバージョンの価値',
  `valuePerAllConv` DOUBLE NULL COMMENT '価値/すべてのコンバージョン数',
  `network` VARCHAR(50) NULL COMMENT '広告掲載方式の指定',
  `clickType` VARCHAR(50) NULL COMMENT 'クリック種別',
  `device` VARCHAR(50) NULL COMMENT 'デバイス',
  `day` DATETIME NULL COMMENT 'レコードの対象日：年（year）、月（monthofYear）、日（day）。左項目を加工してDATETIMEに変換',
  `dayOfWeek` VARCHAR(50) NULL COMMENT '曜日',
  `quarter` VARCHAR(50) NULL COMMENT '四半期',
  `month` VARCHAR(50) NULL COMMENT '毎月',
  `week` VARCHAR(50) NULL COMMENT '毎週',
  `objectiveOfConversionTracking` VARCHAR(50) NULL COMMENT 'コンバージョン測定の目的',
  `conversionName` VARCHAR(50) NULL COMMENT 'コンバージョン名',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `repo_yss_account_report_conv_idx1` (`account_id` ASC),
  INDEX `repo_yss_account_report_conv_idx2` (`campaign_id` ASC),
  INDEX `repo_yss_account_report_conv_idx3` (`network` ASC),
  INDEX `repo_yss_account_report_conv_idx4` (`day` ASC),
  INDEX `repo_yss_account_report_conv_idx5` (`dayOfWeek` ASC),
  INDEX `repo_yss_account_report_conv_idx6` (`quarter` ASC),
  INDEX `repo_yss_account_report_conv_idx7` (`month` ASC),
  INDEX `repo_yss_account_report_conv_idx8` (`week` ASC),
  INDEX `repo_yss_account_report_conv_idx9` (`device` ASC),
  INDEX `repo_yss_account_report_conv_idx10` (`clickType` ASC),
  INDEX `repo_yss_account_report_conv_idx11` (`objectiveOfConversionTracking` ASC),
  INDEX `repo_yss_account_report_conv_idx12` (`conversionName` ASC),
  INDEX `repo_yss_account_report_conv_idx13` (`exeDate` ASC),
  INDEX `repo_yss_account_report_conv_idx14` (`startDate` ASC),
  INDEX `repo_yss_account_report_conv_idx15` (`endDate` ASC),
  INDEX `repo_yss_account_report_conv_idx16` (`accountid` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'YSSアカウントレポート（コンバージョン）';
