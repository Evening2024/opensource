-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 19, 2024 at 05:36 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `safari`
--

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

DROP TABLE IF EXISTS `agents`;
CREATE TABLE IF NOT EXISTS `agents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `agent_name` varchar(100) NOT NULL,
  `agent_address` varchar(255) NOT NULL,
  `agent_email` varchar(255) NOT NULL,
  `agent_phone` varchar(20) NOT NULL,
  `agent_logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `agent_name`, `agent_address`, `agent_email`, `agent_phone`, `agent_logo`, `created_at`, `updated_at`) VALUES
(6, 'Sisi kwa sisi', 'Sakina Arusha', 'sisi@gmail.com', '12345', 'agentslogos/sisikwasisi.png', '2024-04-16 11:13:09', '2024-04-16 11:27:35'),
(8, 'Sunny safaris', 'Njiro, P.O.Box 12 Arusha', 'sun@gmail.com', '1234', 'agentslogos/sunny safaris.jpg', '2024-04-16 11:28:12', '2024-04-16 11:28:12'),
(9, 'Leopard', 'Arusha', 'leopard@gmail.com', '1234', 'agentslogos/leopard.png', '2024-04-16 11:28:56', '2024-04-16 11:29:10'),
(10, 'mawe', 'Arusha', 'arusha@gmail.com', '123456', 'agentslogos/mawe.png', '2024-04-16 11:29:36', '2024-04-16 11:29:36'),
(11, 'tanganyika', 'Dar', 'tanganyika@gmail.com', '12345', 'agentslogos/wilderness camps.png', '2024-04-16 11:30:30', '2024-04-16 11:30:30');

-- --------------------------------------------------------

--
-- Table structure for table `bank_details`
--

DROP TABLE IF EXISTS `bank_details`;
CREATE TABLE IF NOT EXISTS `bank_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_holder_name` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `bank_branch` varchar(255) NOT NULL,
  `routing_number` varchar(50) NOT NULL,
  `iban` varchar(50) NOT NULL,
  `swift_bic_code` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bank_details`
--

INSERT INTO `bank_details` (`id`, `account_holder_name`, `bank_name`, `account_number`, `bank_branch`, `routing_number`, `iban`, `swift_bic_code`, `created_at`) VALUES
(1, 'Jane DoeS', 'Updated Bank', '0987654321', 'Updated Branch', '876543210', 'GB29NWBK60161331926819', 'NEWGB2L', '2024-04-17 11:27:44');

-- --------------------------------------------------------

--
-- Table structure for table `company_details`
--

DROP TABLE IF EXISTS `company_details`;
CREATE TABLE IF NOT EXISTS `company_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `pobox` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `website` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `company_details`
--

INSERT INTO `company_details` (`id`, `company_name`, `pobox`, `email`, `phone`, `website`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Imma Safaris', 'P.O.box 296 Arusha', 'mlelwaimma99@gmail.com', '+255 683 299 040', 'mlelwaimma.com', 'logos/person_1.jpeg', '2024-04-16 09:40:18', '2024-04-16 09:40:18');

-- --------------------------------------------------------

--
-- Table structure for table `national_parks`
--

DROP TABLE IF EXISTS `national_parks`;
CREATE TABLE IF NOT EXISTS `national_parks` (
  `park_id` int NOT NULL AUTO_INCREMENT,
  `park_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `owned_by` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`park_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `national_parks`
--

INSERT INTO `national_parks` (`park_id`, `park_name`, `location`, `owned_by`, `description`) VALUES
(3, 'Arusha National Park', 'Arusha', '', 'very GOOD');

-- --------------------------------------------------------

--
-- Table structure for table `national_park_details`
--

DROP TABLE IF EXISTS `national_park_details`;
CREATE TABLE IF NOT EXISTS `national_park_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `park_name` varchar(255) NOT NULL,
  `seasonname` varchar(255) NOT NULL,
  `seasonduration` varchar(255) NOT NULL,
  `ea_citizen_adult` varchar(255) NOT NULL,
  `ea_citizen_children` varchar(255) NOT NULL,
  `ea_citizen_below_5` varchar(255) NOT NULL,
  `non_ea_citizen_adult` varchar(255) NOT NULL,
  `non_ea_citizen_children` varchar(255) NOT NULL,
  `non_ea_citizen_below_5` varchar(255) NOT NULL,
  `tz_resident_above_16` varchar(255) NOT NULL,
  `tz_resident_children` varchar(255) NOT NULL,
  `tz_resident_below_5` varchar(255) NOT NULL,
  `guide_entry_fee` varchar(255) NOT NULL,
  `vehicle_fee` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `national_park_details`
--

INSERT INTO `national_park_details` (`id`, `park_name`, `seasonname`, `seasonduration`, `ea_citizen_adult`, `ea_citizen_children`, `ea_citizen_below_5`, `non_ea_citizen_adult`, `non_ea_citizen_children`, `non_ea_citizen_below_5`, `tz_resident_above_16`, `tz_resident_children`, `tz_resident_below_5`, `guide_entry_fee`, `vehicle_fee`, `created_at`, `updated_at`) VALUES
(1, 'Arusha National Park', 'sasssssssssssssssssss', 'sasa', '4', '4', '4', '4', '4', '4', '4', '4', '4', '4', '4', '2024-04-18 07:31:58', '2024-04-18 19:03:47'),
(2, 'Arusha National Park', 'sasssssssssssssssssss', 'salasasa', '4', '4', '4', '4', '4', '4', '4', '4', '4', '4', '4', '2024-04-18 07:42:22', '2024-04-18 19:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
CREATE TABLE IF NOT EXISTS `partners` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partner_name` varchar(255) NOT NULL,
  `partner_logo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `partner_name`, `partner_logo`) VALUES
(6, 'Tanapa', 'logos/tanapa.jpg'),
(7, 'Amref', 'logos/amref.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
