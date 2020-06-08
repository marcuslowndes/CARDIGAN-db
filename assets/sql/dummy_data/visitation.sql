-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Feb 25, 2020 at 08:54 PM
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
-- Table structure for table `visitation`
--

CREATE TABLE IF NOT EXISTS `visitation` (
  `idVisitation` int(11) NOT NULL,
  `Date_Start` date NOT NULL,
  `Date_End` date NOT NULL,
  `Preconsultation` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idVisitation`),
  UNIQUE KEY `idVisitation_UNIQUE` (`idVisitation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `visitation`
--

INSERT INTO `visitation` (`idVisitation`, `Date_Start`, `Date_End`, `Preconsultation`) VALUES
(1, '2019-01-21', '2019-06-25', 1),
(2, '2019-06-24', '2019-06-26', 0),
(3, '2019-07-01', '2019-07-03', 0),
(4, '2019-07-08', '2019-07-10', 0),
(5, '2019-07-15', '2019-07-17', 0),
(6, '2019-07-22', '2019-07-24', 0),
(7, '2019-07-29', '2019-07-31', 0),
(8, '2019-10-28', '2019-11-01', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
