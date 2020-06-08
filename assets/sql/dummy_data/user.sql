-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Feb 24, 2020 at 11:15 PM
-- Server version: 8.0.18
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cardigandb`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Password` varchar(45) NOT NULL,
  `Salt` varchar(45) NOT NULL,
  `User_Type` enum('Unverified','Verified','Admin') NOT NULL DEFAULT 'Unverified',
  `Forename` varchar(45) NOT NULL,
  `Surname` varchar(45) NOT NULL,
  `Email` varchar(80) NOT NULL,
  `Account_Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Last_Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Last_Logged_In` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username_UNIQUE` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `Password`, `Salt`, `User_Type`, `Forename`, `Surname`, `Email`, `Account_Created`, `Last_Logged_In`) VALUES
(1, '3065f358917daab75cb487204e1b147886e171b5cdee6', 'GUXTZy59GNqZEq6TKaYLGQ==', 'Admin', 'Marcus', 'Lowndes', 'marcus1@cardiganproject.com', '2020-02-24 21:30:09', '2020-02-24 22:40:56'),
(2, '590219911fb019bc73ef17b675fc175be10e848da4b63', '38+XIgzpilL248KX90FOEw==', 'Verified', 'Marcus', 'Lowndes', 'marcus2@cardiganproject.com', '2020-02-24 21:30:43', '2020-02-24 22:11:58'),
(3, '95e43247b7f6b9b7934f6433626e8b460d68c059988b6', 'WCechDiMCLdzs3XKk2UxwA==', 'Unverified', 'Marcus', 'Lowndes', 'marcus3@cardiganproject.com', '2020-02-24 21:31:01', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
