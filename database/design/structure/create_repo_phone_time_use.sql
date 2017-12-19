CREATE TABLE `ADGAINER_db_SECURE`.`repo_phone_time_use` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `phone_time_use_id` INT NOT NULL COMMENT 'phone_time_useテーブルのid',
  `visitor_city_state` VARCHAR(255) NULL COMMENT 'phone_time_useテーブルから割り出した、都道府県の情報',
  `platform` VARCHAR(255) NULL COMMENT 'phone_time_useテーブルのplatformとmobileフィールドから割り出した、device情報',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `repo_phone_time_use_idx1` (`visitor_city_state` ASC),
  INDEX `repo_phone_time_use_idx2` (`platform` ASC),
  INDEX `fk_repo_phone_time_use_1_idx` (`phone_time_use_id` ASC),
  CONSTRAINT `fk_repo_phone_time_use_1`
    FOREIGN KEY (`phone_time_use_id`)
    REFERENCES `ADGAINER_db_SECURE`.`phone_time_use` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'phone_time_use の複製テーブル。\nレポーティングシステムで利用します。';
