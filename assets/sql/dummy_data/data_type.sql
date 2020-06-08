-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Feb 26, 2020 at 12:30 AM
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

--
-- Dumping data for table `data_type`
--

INSERT INTO `data_type` (`idData_Type`, `Type`, `Walk_Type`, `Subtype`, `Weekly`) VALUES
(1, 'Clinical', NULL, 'General', 0),
(2, 'Clinical', NULL, 'General', 1),
(3, 'Clinical', NULL, 'Activity', 1),
(4, 'Clinical', NULL, 'Biochemical Levels', 1),
(5, 'Clinical', NULL, 'Diet', 1),
(6, 'Gait', '6 Minute', 'Up', 1),
(7, 'Gait', '6 Minute', 'Down', 1),
(8, 'Gait', '10 Metre', 'Up', 1),
(9, 'Gait', '10 Metre', 'Down', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
