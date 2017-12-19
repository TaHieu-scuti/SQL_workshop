<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreateTableRepoPhoneTimeUse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<EOS
CREATE TABLE `repo_phone_time_use` (
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
    REFERENCES `phone_time_use` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'phone_time_use の複製テーブル。\nレポーティングシステムで利用します。'
EOS
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repo_phone_time_use');
    }
}
