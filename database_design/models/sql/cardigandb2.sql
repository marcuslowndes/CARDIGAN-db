-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema cardigandb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema cardigandb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `cardigandb` DEFAULT CHARACTER SET utf8 ;
USE `cardigandb` ;

-- -----------------------------------------------------
-- Table `cardigandb`.`attribute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`attribute` (
  `idAttribute` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idAttribute`),
  UNIQUE INDEX `idAttribute_UNIQUE` (`idAttribute` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cardigandb`.`data type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`data type` (
  `idData Type` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idData Type`),
  UNIQUE INDEX `idData Type_UNIQUE` (`idData Type` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cardigandb`.`entity`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`entity` (
  `idEntity` INT(11) NOT NULL AUTO_INCREMENT,
  `idDataType` INT(11) NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idEntity`),
  UNIQUE INDEX `Entity_ID_UNIQUE` (`idEntity` ASC) VISIBLE,
  INDEX `fk_Entity_Data Type1_idx` (`idDataType` ASC) VISIBLE,
  INDEX `idDataType` (`idDataType` ASC) VISIBLE,
  CONSTRAINT `fk_Entity_Data Type1`
    FOREIGN KEY (`idDataType`)
    REFERENCES `cardigandb`.`data type` (`idData Type`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cardigandb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`user` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Password` VARCHAR(45) NOT NULL,
  `Salt` VARCHAR(45) NOT NULL,
  `User_Type` ENUM('Unverified', 'Verified', 'Admin') NOT NULL DEFAULT 'Unverified',
  `Forename` VARCHAR(45) NOT NULL,
  `Surname` VARCHAR(45) NOT NULL,
  `Email` VARCHAR(80) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `Username_UNIQUE` (`ID` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cardigandb`.`visitation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`visitation` (
  `idVisitation` INT(11) NOT NULL,
  `Timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  `Preconsultation` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idVisitation`),
  UNIQUE INDEX `idVisitation_UNIQUE` (`idVisitation` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cardigandb`.`value`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`value` (
  `idValue` INT NOT NULL AUTO_INCREMENT,
  `entity_idEntity` INT(11) NOT NULL,
  `attribute_idAttribute` INT(11) NOT NULL,
  `visitation_idVisitation` INT(11) NOT NULL,
  PRIMARY KEY (`idValue`),
  UNIQUE INDEX `idValue_UNIQUE` (`idValue` ASC) VISIBLE,
  INDEX `fk_value_visitation1_idx` (`visitation_idVisitation` ASC) VISIBLE,
  INDEX `fk_value_entity1_idx` (`entity_idEntity` ASC) VISIBLE,
  INDEX `fk_value_attribute1_idx` (`attribute_idAttribute` ASC) VISIBLE,
  CONSTRAINT `fk_value_visitation1`
    FOREIGN KEY (`visitation_idVisitation`)
    REFERENCES `cardigandb`.`visitation` (`idVisitation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_value_entity1`
    FOREIGN KEY (`entity_idEntity`)
    REFERENCES `cardigandb`.`entity` (`idEntity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_value_attribute1`
    FOREIGN KEY (`attribute_idAttribute`)
    REFERENCES `cardigandb`.`attribute` (`idAttribute`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `cardigandb`.`value_boolean`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`value_boolean` (
  `idValue_Boolean` INT(11) NOT NULL AUTO_INCREMENT,
  `Value` TINYINT(1) NOT NULL,
  `value_idValue` INT NOT NULL,
  PRIMARY KEY (`idValue_Boolean`),
  UNIQUE INDEX `idValue_Boolean_UNIQUE` (`idValue_Boolean` ASC) VISIBLE,
  INDEX `fk_value_boolean_value1_idx` (`value_idValue` ASC) VISIBLE,
  CONSTRAINT `fk_value_boolean_value1`
    FOREIGN KEY (`value_idValue`)
    REFERENCES `cardigandb`.`value` (`idValue`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cardigandb`.`value_decimal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`value_decimal` (
  `idValue_Decimal` INT(11) NOT NULL AUTO_INCREMENT,
  `Value` DECIMAL(10,0) NOT NULL,
  `value_idValue` INT NOT NULL,
  PRIMARY KEY (`idValue_Decimal`),
  UNIQUE INDEX `idValue_Decimal_UNIQUE` (`idValue_Decimal` ASC) VISIBLE,
  INDEX `fk_value_decimal_value1_idx` (`value_idValue` ASC) VISIBLE,
  CONSTRAINT `fk_value_decimal_value1`
    FOREIGN KEY (`value_idValue`)
    REFERENCES `cardigandb`.`value` (`idValue`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cardigandb`.`value_integer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`value_integer` (
  `idValue_Integer` INT(11) NOT NULL AUTO_INCREMENT,
  `Value` INT(11) NOT NULL,
  `value_idValue` INT NOT NULL,
  PRIMARY KEY (`idValue_Integer`),
  UNIQUE INDEX `idValue_Integer_UNIQUE` (`idValue_Integer` ASC) VISIBLE,
  INDEX `fk_value_integer_value1_idx` (`value_idValue` ASC) VISIBLE,
  CONSTRAINT `fk_value_integer_value1`
    FOREIGN KEY (`value_idValue`)
    REFERENCES `cardigandb`.`value` (`idValue`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cardigandb`.`value_text`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`value_text` (
  `idValue_Text` INT(11) NOT NULL AUTO_INCREMENT,
  `Value` TEXT NOT NULL,
  `value_idValue` INT NOT NULL,
  PRIMARY KEY (`idValue_Text`),
  UNIQUE INDEX `idValue_Text_UNIQUE` (`idValue_Text` ASC) VISIBLE,
  INDEX `fk_value_text_value1_idx` (`value_idValue` ASC) VISIBLE,
  CONSTRAINT `fk_value_text_value1`
    FOREIGN KEY (`value_idValue`)
    REFERENCES `cardigandb`.`value` (`idValue`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `cardigandb`.`value_varchar`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cardigandb`.`value_varchar` (
  `idValue_Varchar` INT(11) NOT NULL AUTO_INCREMENT,
  `Value` VARCHAR(45) NOT NULL,
  `value_idValue` INT NOT NULL,
  PRIMARY KEY (`idValue_Varchar`),
  UNIQUE INDEX `idValue_Varchar_UNIQUE` (`idValue_Varchar` ASC) VISIBLE,
  INDEX `fk_value_varchar_value1_idx` (`value_idValue` ASC) VISIBLE,
  CONSTRAINT `fk_value_varchar_value1`
    FOREIGN KEY (`value_idValue`)
    REFERENCES `cardigandb`.`value` (`idValue`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
