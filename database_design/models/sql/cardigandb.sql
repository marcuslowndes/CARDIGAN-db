-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 09, 2020 at 01:18 AM
-- Server version: 5.6.13
-- PHP Version: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cardigandb`
--
CREATE DATABASE IF NOT EXISTS `cardigandb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `cardigandb`;

-- --------------------------------------------------------

--
-- Table structure for table `attribute`
--

CREATE TABLE IF NOT EXISTS `attribute` (
  `idAttribute` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  PRIMARY KEY (`idAttribute`),
  UNIQUE KEY `idAttribute_UNIQUE` (`idAttribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `data type`
--

CREATE TABLE IF NOT EXISTS `data type` (
  `idData Type` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  PRIMARY KEY (`idData Type`),
  UNIQUE KEY `idData Type_UNIQUE` (`idData Type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `entity`
--

CREATE TABLE IF NOT EXISTS `entity` (
  `idEntity` int(11) NOT NULL AUTO_INCREMENT,
  `idDataType` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  PRIMARY KEY (`idEntity`),
  UNIQUE KEY `Entity_ID_UNIQUE` (`idEntity`),
  KEY `fk_Entity_Data Type1_idx` (`idDataType`),
  KEY `idDataType` (`idDataType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `Username` varchar(45) NOT NULL,
  `Password` varchar(45) NOT NULL,
  `Salt` varchar(45) NOT NULL,
  `User_Type` enum('Unverified','Verified','Admin') NOT NULL DEFAULT 'Unverified',
  `Forename` varchar(45) NOT NULL,
  `Surname` varchar(45) NOT NULL,
  `Email` varchar(80) NOT NULL,
  PRIMARY KEY (`Username`),
  UNIQUE KEY `Username_UNIQUE` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `value_boolean`
--

CREATE TABLE IF NOT EXISTS `value_boolean` (
  `idValue_Boolean` int(11) NOT NULL AUTO_INCREMENT,
  `idEntity` int(11) NOT NULL,
  `idAttribute` int(11) NOT NULL,
  `idVisitation` int(11) NOT NULL,
  `Value` tinyint(1) NOT NULL,
  PRIMARY KEY (`idValue_Boolean`),
  UNIQUE KEY `idValue_Boolean_UNIQUE` (`idValue_Boolean`),
  KEY `fk_Value_Boolean_Entity_idx` (`idEntity`),
  KEY `fk_Value_Boolean_Attribute1_idx` (`idAttribute`),
  KEY `fk_Value_Boolean_Visitation1_idx` (`idVisitation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `value_decimal`
--

CREATE TABLE IF NOT EXISTS `value_decimal` (
  `idValue_Decimal` int(11) NOT NULL AUTO_INCREMENT,
  `idEntity` int(11) NOT NULL,
  `idAttribute` int(11) NOT NULL,
  `idVisitation` int(11) NOT NULL,
  `Value` decimal(10,0) NOT NULL,
  PRIMARY KEY (`idValue_Decimal`),
  UNIQUE KEY `idValue_Decimal_UNIQUE` (`idValue_Decimal`),
  KEY `fk_Value_Decimal_Entity_idx` (`idEntity`),
  KEY `fk_Value_Decimal_Attribute1_idx` (`idAttribute`),
  KEY `fk_Value_Decimal_Visitation1_idx` (`idVisitation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `value_integer`
--

CREATE TABLE IF NOT EXISTS `value_integer` (
  `idValue_Integer` int(11) NOT NULL AUTO_INCREMENT,
  `idEntity` int(11) NOT NULL,
  `idAttribute` int(11) NOT NULL,
  `idVisitation` int(11) NOT NULL,
  `Value` int(11) NOT NULL,
  PRIMARY KEY (`idValue_Integer`),
  UNIQUE KEY `idValue_Integer_UNIQUE` (`idValue_Integer`),
  KEY `fk_Value_Integer_Entity_idx` (`idEntity`),
  KEY `fk_Value_Integer_Attribute1_idx` (`idAttribute`),
  KEY `fk_Value_Integer_Visitation1_idx` (`idVisitation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `value_text`
--

CREATE TABLE IF NOT EXISTS `value_text` (
  `idValue_Text` int(11) NOT NULL AUTO_INCREMENT,
  `idEntity` int(11) NOT NULL,
  `idAttribute` int(11) NOT NULL,
  `idVisitation` int(11) NOT NULL,
  `Value` text NOT NULL,
  PRIMARY KEY (`idValue_Text`),
  UNIQUE KEY `idValue_Text_UNIQUE` (`idValue_Text`),
  KEY `fk_Value_Text_Entity_idx` (`idEntity`),
  KEY `fk_Value_Text_Attribute1_idx` (`idAttribute`),
  KEY `fk_Value_Text_Visitation1_idx` (`idVisitation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `value_varchar`
--

CREATE TABLE IF NOT EXISTS `value_varchar` (
  `idValue_Varchar` int(11) NOT NULL AUTO_INCREMENT,
  `idEntity` int(11) NOT NULL,
  `idAttribute` int(11) NOT NULL,
  `idVisitation` int(11) NOT NULL,
  `Value` varchar(45) NOT NULL,
  PRIMARY KEY (`idValue_Varchar`),
  UNIQUE KEY `idValue_Varchar_UNIQUE` (`idValue_Varchar`),
  KEY `fk_Value_Varchar_Entity_idx` (`idEntity`),
  KEY `fk_Value_Varchar_Attribute1_idx` (`idAttribute`),
  KEY `fk_Value_Varchar_Visitation1_idx` (`idVisitation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `visitation`
--

CREATE TABLE IF NOT EXISTS `visitation` (
  `idVisitation` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Preconsultation` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idVisitation`),
  UNIQUE KEY `idVisitation_UNIQUE` (`idVisitation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `entity`
--
ALTER TABLE `entity`
  ADD CONSTRAINT `fk_Entity_Data Type1` FOREIGN KEY (`idDataType`) REFERENCES `data type` (`idData Type`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `value_boolean`
--
ALTER TABLE `value_boolean`
  ADD CONSTRAINT `fk_Value_Boolean_Attribute1` FOREIGN KEY (`idAttribute`) REFERENCES `attribute` (`idAttribute`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Value_Boolean_Entity` FOREIGN KEY (`idEntity`) REFERENCES `entity` (`idEntity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Value_Boolean_Visitation1` FOREIGN KEY (`idVisitation`) REFERENCES `visitation` (`idVisitation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `value_decimal`
--
ALTER TABLE `value_decimal`
  ADD CONSTRAINT `fk_Value_Decimal_Attribute1` FOREIGN KEY (`idAttribute`) REFERENCES `attribute` (`idAttribute`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Value_Decimal_Entity` FOREIGN KEY (`idEntity`) REFERENCES `entity` (`idEntity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Value_Decimal_Visitation1` FOREIGN KEY (`idVisitation`) REFERENCES `visitation` (`idVisitation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `value_integer`
--
ALTER TABLE `value_integer`
  ADD CONSTRAINT `fk_Value_Integer_Attribute1` FOREIGN KEY (`idAttribute`) REFERENCES `attribute` (`idAttribute`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Value_Integer_Entity` FOREIGN KEY (`idEntity`) REFERENCES `entity` (`idEntity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Value_Integer_Visitation1` FOREIGN KEY (`idVisitation`) REFERENCES `visitation` (`idVisitation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `value_text`
--
ALTER TABLE `value_text`
  ADD CONSTRAINT `fk_Value_Text_Attribute1` FOREIGN KEY (`idAttribute`) REFERENCES `attribute` (`idAttribute`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Value_Text_Entity` FOREIGN KEY (`idEntity`) REFERENCES `entity` (`idEntity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Value_Text_Visitation1` FOREIGN KEY (`idVisitation`) REFERENCES `visitation` (`idVisitation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `value_varchar`
--
ALTER TABLE `value_varchar`
  ADD CONSTRAINT `fk_Value_Varchar_Attribute1` FOREIGN KEY (`idAttribute`) REFERENCES `attribute` (`idAttribute`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Value_Varchar_Entity` FOREIGN KEY (`idEntity`) REFERENCES `entity` (`idEntity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Value_Varchar_Visitation1` FOREIGN KEY (`idVisitation`) REFERENCES `visitation` (`idVisitation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
