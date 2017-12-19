CREATE TABLE `ADGAINER_db_SECURE`.`repo_adw_geotarget` (
  `criteriaId` INT(20) NOT NULL COMMENT 'adwords api の Geotargets で定義された id',
  `name` VARCHAR(100) NOT NULL COMMENT 'adwords api の Geotargets で定義された都市名',
  `canonicalName` VARCHAR(200) NOT NULL COMMENT 'adwords api の Geotargets で定義された正式都市名',
  `parentId` INT(20) NOT NULL COMMENT 'adwords api の Geotargets で定義された都道府県名の criteriaId',
  `countryCode` VARCHAR(20) NOT NULL COMMENT 'adwords api の Geotargets で定義された国識別文字列',
  `targetType` VARCHAR(50) NOT NULL COMMENT 'adwords api の Geotargets で定義されたランドマーク名\nsee: https://goo.gl/TdFrq : Target Type',
  `status` VARCHAR(20) NOT NULL COMMENT 'adwords api の Geotargets で定義されたステータス\nsee: https://goo.gl/TdFrq : Status',
  `latest` DATE NOT NULL,
  PRIMARY KEY (`criteriaId`),
  INDEX `repo_adw_geotarget_idx1` (`latest` ASC),
  INDEX `repo_adw_geotarget_idx2` (`status` ASC),
  INDEX `repo_adw_geotarget_idx3` (`name` ASC),
  INDEX `repo_adw_geotarget_idx4` (`canonicalName` ASC),
  INDEX `repo_adw_geotarget_idx5` (`parentId` ASC),
  INDEX `repo_adw_geotarget_idx6` (`countryCode` ASC),
  INDEX `repo_adw_geotarget_idx7` (`targetType` ASC))
COMMENT = 'adwords api の Geotargets で定義された位置基準';
