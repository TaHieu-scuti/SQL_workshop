/**
 * ADgaienr Solutions Reporting System
 * Schema : ADGAINER_db_SECURE
 * Table Name : repo_ydn_accounts
 * Auther : Tetsuya Takiguchi
 */
CREATE TABLE IF NOT EXISTS `ADGAINER_db_SECURE`.`repo_ydn_accounts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `accountId` INT(20) NULL DEFAULT NULL COMMENT 'アカウントID',
  `account_id` VARCHAR(50) NULL DEFAULT NULL COMMENT 'ADgainerシステムのアカウントID',
  `accountName` VARCHAR(255) NULL DEFAULT NULL COMMENT 'アカウント名',
  `accountType` VARCHAR(50) NULL DEFAULT NULL COMMENT 'アカウントの種別\nhttps://github.com/yahoojp-marketing/ydn-api-documents/blob/master/docs/ja/api_reference/data/AccountType.md',
  `accountStatus` VARCHAR(50) NULL DEFAULT NULL COMMENT 'アカウント登録状況\nhttps://github.com/yahoojp-marketing/ydn-api-documents/blob/master/docs/ja/api_reference/data/AccountStatus.md',
  `deliveryStatus` VARCHAR(45) NULL DEFAULT NULL COMMENT '配信状況\nhttps://github.com/yahoojp-marketing/ydn-api-documents/blob/master/docs/ja/api_reference/data/DeliveryStatus.md',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '作成日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `ydn_accounts_idx1` (`accountId` ASC),
  INDEX `ydn_accounts_idx2` (`accountName` ASC),
  INDEX `ydn_accounts_idx3` (`accountType` ASC),
  INDEX `ydn_accounts_idx4` (`accountStatus` ASC),
  INDEX `ydn_accounts_idx5` (`deliveryStatus` ASC),
  INDEX `ydn_accounts_idx6` (`updated_at` ASC),
  INDEX `ydn_accounts_idx7` (`account_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Yahoo! Display Searchのアカウント情報を管理します';
