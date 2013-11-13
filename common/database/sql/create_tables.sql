-- common/database/sql/create_tables.sql
 
DROP SCHEMA IF EXISTS `board` ;
CREATE SCHEMA IF NOT EXISTS `board` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `board` ;
 
-- -----------------------------------------------------
-- Table `board`.`genre`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `board`.`genre` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `sequence` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;
 
 
-- -----------------------------------------------------
-- Table `board`.`thread`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `board`.`thread` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `genre_id` INT UNSIGNED NOT NULL ,
  `title` VARCHAR(80) NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_thread_genre1_idx` (`genre_id` ASC) ,
  CONSTRAINT `fk_thread_genre1`
    FOREIGN KEY (`genre_id` )
    REFERENCES `board`.`genre` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
 
 
-- -----------------------------------------------------
-- Table `board`.`account`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `board`.`account` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `login_id` VARCHAR(120) NOT NULL ,
  `password` VARCHAR(255) NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `login_id_UNIQUE` (`login_id` ASC) )
ENGINE = InnoDB;
 
 
-- -----------------------------------------------------
-- Table `board`.`entry`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `board`.`entry` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `thread_id` INT NOT NULL ,
  `account_id` INT NOT NULL ,
  `body` VARCHAR(255) NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_entry_thread_idx` (`thread_id` ASC) ,
  INDEX `fk_entry_account1_idx` (`account_id` ASC) ,
  CONSTRAINT `fk_entry_thread`
    FOREIGN KEY (`thread_id` )
    REFERENCES `board`.`thread` (`id` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_entry_account1`
    FOREIGN KEY (`account_id` )
    REFERENCES `board`.`account` (`id` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT)
ENGINE = InnoDB;
 
 
-- -----------------------------------------------------
-- Table `board`.`auto_login`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `board`.`auto_login` (
  `hash` VARCHAR(190) NOT NULL ,
  `account_id` INT NOT NULL ,
  `expire_date` DATETIME NOT NULL ,
  PRIMARY KEY (`hash`) ,
  INDEX `fk_auto_login_account1_idx` (`account_id` ASC) ,
  CONSTRAINT `fk_auto_login_account1`
    FOREIGN KEY (`account_id` )
    REFERENCES `board`.`account` (`id` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT)
ENGINE = InnoDB;
 
 
-- -----------------------------------------------------
-- Table `board`.`tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `board`.`tag` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;
 
 
-- -----------------------------------------------------
-- Table `board`.`thread_tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `board`.`thread_tag` (
  `thread_id` INT NOT NULL ,
  `tag_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`thread_id`, `tag_id`) ,
  INDEX `fk_thread_has_tag_tag1_idx` (`tag_id` ASC) ,
  INDEX `fk_thread_has_tag_thread1_idx` (`thread_id` ASC) ,
  CONSTRAINT `fk_thread_has_tag_thread1`
    FOREIGN KEY (`thread_id` )
    REFERENCES `board`.`thread` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_thread_has_tag_tag1`
    FOREIGN KEY (`tag_id` )
    REFERENCES `board`.`tag` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;