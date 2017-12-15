/**
 * ADgaienr Solutions Reporting System
 * Schema : ADGAINER_db_SECURE
 * Table Name : repo_yss_accounts
 * Auther : Tetsuya Takiguchi
 */
CREATE TABLE IF NOT EXISTS `ADGAINER_db_SECURE`.`repo_yss_accounts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `accountid` INT(20) NULL DEFAULT NULL COMMENT 'アカウントID',
  `account_id` VARCHAR(50) NULL DEFAULT NULL COMMENT 'ADgainerシステムのアカウントID',
  `accountName` VARCHAR(255) NULL DEFAULT NULL COMMENT 'アカウント名',
  `accountType` VARCHAR(20) NULL DEFAULT NULL COMMENT '料金の支払い方法\nhttps://github.com/yahoojp-marketing/sponsored-search-api-documents/blob/master/docs/ja/api_reference/data/AccountType.md',
  `accountStatus` VARCHAR(20) NULL DEFAULT NULL COMMENT 'アカウントの契約状況\nhttps://github.com/yahoojp-marketing/sponsored-search-api-documents/blob/master/docs/ja/api_reference/data/AccountStatus.md',
  `deliveryStatus` VARCHAR(20) NULL DEFAULT NULL COMMENT '広告の配信状況\nhttps://github.com/yahoojp-marketing/sponsored-search-api-documents/blob/master/docs/ja/api_reference/data/DeliveryStatus.md',
  `created_at` DATETIME NULL DEFAULT NULL COMMENT '作成日時',
  `updated_at` DATETIME NULL DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `repo_yss_accounts_idx1` (`account_id` ASC),
  INDEX `repo_yss_accounts_idx2` (`accountid` ASC),
  INDEX `repo_yss_accounts_idx3` (`updated_at` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = ujis
COMMENT = 'YSSのアカウント情報\nhttps://github.com/yahoojp-marke' /* comment truncated */ /*ting/sponsored-search-api-documents/blob/master/docs/ja/api_reference/data/Account.md*/
