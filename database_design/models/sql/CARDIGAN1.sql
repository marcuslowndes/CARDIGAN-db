-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema cardiganDB
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema cardiganDB
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `cardiganDB` DEFAULT CHARACTER SET utf8 ;
USE `cardiganDB` ;

-- -----------------------------------------------------
-- Table `cardiganDB`.`Data Type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cardiganDB`.`Data Type` ;

CREATE TABLE IF NOT EXISTS `cardiganDB`.`Data Type` (
  `idData Type` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idData Type`),
  UNIQUE INDEX `idData Type_UNIQUE` (`idData Type` ASC));


-- -----------------------------------------------------
-- Table `cardiganDB`.`Entity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cardiganDB`.`Entity` ;

CREATE TABLE IF NOT EXISTS `cardiganDB`.`Entity` (
  `idEntity` INT NOT NULL AUTO_INCREMENT,
  `idDataType` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idEntity`),
  UNIQUE INDEX `Entity_ID_UNIQUE` (`idEntity` ASC),
  INDEX `fk_Entity_Data Type1_idx` (`idDataType` ASC),
  CONSTRAINT `fk_Entity_Data Type1`
    FOREIGN KEY (`idDataType`)
    REFERENCES `cardiganDB`.`Data Type` (`idData Type`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `cardiganDB`.`Attribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cardiganDB`.`Attribute` ;

CREATE TABLE IF NOT EXISTS `cardiganDB`.`Attribute` (
  `idAttribute` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idAttribute`),
  UNIQUE INDEX `idAttribute_UNIQUE` (`idAttribute` ASC));


-- -----------------------------------------------------
-- Table `cardiganDB`.`Visitation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cardiganDB`.`Visitation` ;

CREATE TABLE IF NOT EXISTS `cardiganDB`.`Visitation` (
  `idVisitation` INT NOT NULL,
  `Timestamp` TIMESTAMP NOT NULL,
  `Preconsultation` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`idVisitation`),
  UNIQUE INDEX `idVisitation_UNIQUE` (`idVisitation` ASC));


-- -----------------------------------------------------
-- Table `cardiganDB`.`Value_Varchar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cardiganDB`.`Value_Varchar` ;

CREATE TABLE IF NOT EXISTS `cardiganDB`.`Value_Varchar` (
  `idValue_Varchar` INT NOT NULL AUTO_INCREMENT,
  `idEntity` INT NOT NULL,
  `idAttribute` INT NOT NULL,
  `idVisitation` INT NOT NULL,
  `Value` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idValue_Varchar`),
  UNIQUE INDEX `idValue_Varchar_UNIQUE` (`idValue_Varchar` ASC),
  INDEX `fk_Value_Varchar_Entity_idx` (`idEntity` ASC),
  INDEX `fk_Value_Varchar_Attribute1_idx` (`idAttribute` ASC),
  INDEX `fk_Value_Varchar_Visitation1_idx` (`idVisitation` ASC),
  CONSTRAINT `fk_Value_Varchar_Entity`
    FOREIGN KEY (`idEntity`)
    REFERENCES `cardiganDB`.`Entity` (`idEntity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Value_Varchar_Attribute1`
    FOREIGN KEY (`idAttribute`)
    REFERENCES `cardiganDB`.`Attribute` (`idAttribute`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Value_Varchar_Visitation1`
    FOREIGN KEY (`idVisitation`)
    REFERENCES `cardiganDB`.`Visitation` (`idVisitation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `cardiganDB`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cardiganDB`.`User` ;

CREATE TABLE IF NOT EXISTS `cardiganDB`.`User` (
  `idUser` INT NOT NULL AUTO_INCREMENT,
  `Username` VARCHAR(45) NOT NULL,
  `Password` VARCHAR(45) NOT NULL,
  `Salt` VARCHAR(45) NOT NULL,
  `Email` VARCHAR(80) NOT NULL,
  `isUser` TINYINT NOT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE INDEX `idUser_UNIQUE` (`idUser` ASC),
  UNIQUE INDEX `Username_UNIQUE` (`Username` ASC));


-- -----------------------------------------------------
-- Table `cardiganDB`.`Value_Integer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cardiganDB`.`Value_Integer` ;

CREATE TABLE IF NOT EXISTS `cardiganDB`.`Value_Integer` (
  `idValue_Integer` INT NOT NULL AUTO_INCREMENT,
  `idEntity` INT NOT NULL,
  `idAttribute` INT NOT NULL,
  `idVisitation` INT NOT NULL,
  `Value` INT NOT NULL,
  PRIMARY KEY (`idValue_Integer`),
  UNIQUE INDEX `idValue_Integer_UNIQUE` (`idValue_Integer` ASC),
  INDEX `fk_Value_Integer_Entity_idx` (`idEntity` ASC),
  INDEX `fk_Value_Integer_Attribute1_idx` (`idAttribute` ASC),
  INDEX `fk_Value_Integer_Visitation1_idx` (`idVisitation` ASC),
  CONSTRAINT `fk_Value_Integer_Entity`
    FOREIGN KEY (`idEntity`)
    REFERENCES `cardiganDB`.`Entity` (`idEntity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Value_Integer_Attribute1`
    FOREIGN KEY (`idAttribute`)
    REFERENCES `cardiganDB`.`Attribute` (`idAttribute`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Value_Integer_Visitation1`
    FOREIGN KEY (`idVisitation`)
    REFERENCES `cardiganDB`.`Visitation` (`idVisitation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `cardiganDB`.`Value_Text`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cardiganDB`.`Value_Text` ;

CREATE TABLE IF NOT EXISTS `cardiganDB`.`Value_Text` (
  `idValue_Text` INT NOT NULL AUTO_INCREMENT,
  `idEntity` INT NOT NULL,
  `idAttribute` INT NOT NULL,
  `idVisitation` INT NOT NULL,
  `Value` TEXT NOT NULL,
  PRIMARY KEY (`idValue_Text`),
  UNIQUE INDEX `idValue_Text_UNIQUE` (`idValue_Text` ASC),
  INDEX `fk_Value_Text_Entity_idx` (`idEntity` ASC),
  INDEX `fk_Value_Text_Attribute1_idx` (`idAttribute` ASC),
  INDEX `fk_Value_Text_Visitation1_idx` (`idVisitation` ASC),
  CONSTRAINT `fk_Value_Text_Entity`
    FOREIGN KEY (`idEntity`)
    REFERENCES `cardiganDB`.`Entity` (`idEntity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Value_Text_Attribute1`
    FOREIGN KEY (`idAttribute`)
    REFERENCES `cardiganDB`.`Attribute` (`idAttribute`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Value_Text_Visitation1`
    FOREIGN KEY (`idVisitation`)
    REFERENCES `cardiganDB`.`Visitation` (`idVisitation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `cardiganDB`.`Value_Boolean`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cardiganDB`.`Value_Boolean` ;

CREATE TABLE IF NOT EXISTS `cardiganDB`.`Value_Boolean` (
  `idValue_Boolean` INT NOT NULL AUTO_INCREMENT,
  `idEntity` INT NOT NULL,
  `idAttribute` INT NOT NULL,
  `idVisitation` INT NOT NULL,
  `Value` TINYINT NOT NULL,
  PRIMARY KEY (`idValue_Boolean`),
  UNIQUE INDEX `idValue_Boolean_UNIQUE` (`idValue_Boolean` ASC),
  INDEX `fk_Value_Boolean_Entity_idx` (`idEntity` ASC),
  INDEX `fk_Value_Boolean_Attribute1_idx` (`idAttribute` ASC),
  INDEX `fk_Value_Boolean_Visitation1_idx` (`idVisitation` ASC),
  CONSTRAINT `fk_Value_Boolean_Entity`
    FOREIGN KEY (`idEntity`)
    REFERENCES `cardiganDB`.`Entity` (`idEntity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Value_Boolean_Attribute1`
    FOREIGN KEY (`idAttribute`)
    REFERENCES `cardiganDB`.`Attribute` (`idAttribute`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Value_Boolean_Visitation1`
    FOREIGN KEY (`idVisitation`)
    REFERENCES `cardiganDB`.`Visitation` (`idVisitation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `cardiganDB`.`Value_Decimal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cardiganDB`.`Value_Decimal` ;

CREATE TABLE IF NOT EXISTS `cardiganDB`.`Value_Decimal` (
  `idValue_Decimal` INT NOT NULL AUTO_INCREMENT,
  `idEntity` INT NOT NULL,
  `idAttribute` INT NOT NULL,
  `idVisitation` INT NOT NULL,
  `Value` DECIMAL NOT NULL,
  PRIMARY KEY (`idValue_Decimal`),
  UNIQUE INDEX `idValue_Decimal_UNIQUE` (`idValue_Decimal` ASC),
  INDEX `fk_Value_Decimal_Entity_idx` (`idEntity` ASC),
  INDEX `fk_Value_Decimal_Attribute1_idx` (`idAttribute` ASC),
  INDEX `fk_Value_Decimal_Visitation1_idx` (`idVisitation` ASC),
  CONSTRAINT `fk_Value_Decimal_Entity`
    FOREIGN KEY (`idEntity`)
    REFERENCES `cardiganDB`.`Entity` (`idEntity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Value_Decimal_Attribute1`
    FOREIGN KEY (`idAttribute`)
    REFERENCES `cardiganDB`.`Attribute` (`idAttribute`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Value_Decimal_Visitation1`
    FOREIGN KEY (`idVisitation`)
    REFERENCES `cardiganDB`.`Visitation` (`idVisitation`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
