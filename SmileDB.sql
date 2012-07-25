SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `tavakoli` ;
CREATE SCHEMA IF NOT EXISTS `tavakoli` DEFAULT CHARACTER SET latin1 ;
USE `tavakoli` ;

-- -----------------------------------------------------
-- Table `tavakoli`.`smile_person`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tavakoli`.`smile_person` (
  `per_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(255) NULL DEFAULT NULL ,
  `password` VARCHAR(255) NULL DEFAULT NULL ,
  `date` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`per_id`) ,
  UNIQUE INDEX `email_UNIQUE` (`username` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 37
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `tavakoli`.`smile_profile`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tavakoli`.`smile_profile` (
  `idstats` INT(11) NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(255) NULL DEFAULT NULL ,
  `happy` TINYINT(1) NULL DEFAULT NULL ,
  `timestamp` TIMESTAMP NULL DEFAULT NULL ,
  PRIMARY KEY (`idstats`) )
ENGINE = InnoDB
AUTO_INCREMENT = 70
DEFAULT CHARACTER SET = latin1;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
