-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 08, 2024 at 10:59 AM
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
-- Table structure for table `accommodations`
--

DROP TABLE IF EXISTS `accommodations`;
CREATE TABLE IF NOT EXISTS `accommodations` (
  `property_id` int NOT NULL AUTO_INCREMENT,
  `property_name` varchar(255) NOT NULL,
  `property_chain` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `description` text,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accommodations`
--

INSERT INTO `accommodations` (`property_id`, `property_name`, `property_chain`, `location`, `description`, `email`) VALUES
(1, 'Mount Meru Hotel', '', 'Arusha', 'The best hotel that will give you what you deserve always', 'mountmeru@hotel.com'),
(3, 'Morena Hotel', 'Morenas', 'Dodoma', 'DVFSGFB', 'morena@gmail.com');

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
-- Table structure for table `hotel_images`
--

DROP TABLE IF EXISTS `hotel_images`;
CREATE TABLE IF NOT EXISTS `hotel_images` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `hotel_id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `upload_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`),
  KEY `fk_hotel_id` (`hotel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hotel_images`
--

INSERT INTO `hotel_images` (`image_id`, `hotel_id`, `image_path`, `upload_date`) VALUES
(1, 1, 'hoteluploads/6635009ee4504.jpg', '2024-05-03 15:19:58'),
(7, 1, 'hoteluploads/663784d89370c.jpg', '2024-05-05 13:08:40'),
(8, 1, 'hoteluploads/6637865a9b776.jpg', '2024-05-05 13:15:06');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `national_parks`
--

INSERT INTO `national_parks` (`park_id`, `park_name`, `location`, `owned_by`, `description`) VALUES
(3, 'Arusha National Park', 'Arusha', '', 'very GOOD kwa kweli'),
(6, 'Manyara National Park', 'Manyara', '', 'Manyara');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `national_park_details`
--

INSERT INTO `national_park_details` (`id`, `park_name`, `seasonname`, `seasonduration`, `ea_citizen_adult`, `ea_citizen_children`, `ea_citizen_below_5`, `non_ea_citizen_adult`, `non_ea_citizen_children`, `non_ea_citizen_below_5`, `tz_resident_above_16`, `tz_resident_children`, `tz_resident_below_5`, `guide_entry_fee`, `vehicle_fee`, `created_at`, `updated_at`) VALUES
(1, 'Arusha National Park', 'sasssssssssssssssssss', 'sasasad', '4', '4', '40', '4', '4', '4', '4', '4', '4', '4', '4', '2024-04-18 07:31:58', '2024-05-05 13:46:01'),
(2, 'Arusha National Park', 'sasssssssssssssssssss', 'salasasa', '4', '4', '4', '4', '4', '4', '4', '4', '4', '4', '4', '2024-04-18 07:42:22', '2024-04-18 19:20:06'),
(3, 'Mnayara National Park', 'assa', 'adsad', '23', '23', '23', '23', '23', '4', '54', '23', '56', '2', '2', '2024-05-05 13:40:27', '2024-05-05 13:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `park_images`
--

DROP TABLE IF EXISTS `park_images`;
CREATE TABLE IF NOT EXISTS `park_images` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `park_id` int NOT NULL,
  `park_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `image_name` varchar(255) NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `fk_park_images_park_id` (`park_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `park_images`
--

INSERT INTO `park_images` (`image_id`, `park_id`, `park_name`, `image_name`) VALUES
(34, 3, 'Arusha National Park', 'serengeti4.jpg'),
(37, 3, 'Arusha National Park', 'serengeti.jpg'),
(38, 6, 'Manyara National Park', 'serengeti4.jpg'),
(36, 3, 'Arusha National Park', 'serengeti3.jpg'),
(32, 6, 'Mnayara National Park', 'serengeti3.jpg');

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

-- --------------------------------------------------------

--
-- Table structure for table `planes`
--

DROP TABLE IF EXISTS `planes`;
CREATE TABLE IF NOT EXISTS `planes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `registration_number` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `planes`
--

INSERT INTO `planes` (`id`, `company_name`, `registration_number`, `email`) VALUES
(2, 'Ass\'alam alleykumss', '123', 'asssalam@gmail.com'),
(4, 'aSADSDSA', '1213', 'ASA@GMAIL.COM'),
(5, 'SAs', '1', 'AS@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `plane_rates`
--

DROP TABLE IF EXISTS `plane_rates`;
CREATE TABLE IF NOT EXISTS `plane_rates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plane_id` int NOT NULL,
  `season` varchar(255) NOT NULL,
  `season_start` date NOT NULL,
  `season_end` date NOT NULL,
  `source` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `adults_price` decimal(10,2) NOT NULL,
  `children_price` decimal(10,2) NOT NULL,
  `children_age` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `plane_rates_ibfk_1` (`plane_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `plane_rates`
--

INSERT INTO `plane_rates` (`id`, `plane_id`, `season`, `season_start`, `season_end`, `source`, `destination`, `adults_price`, `children_price`, `children_age`, `created_at`, `updated_at`) VALUES
(3, 2, '2wqr', '2024-05-23', '2024-07-18', 'ewwe', 'ewrew', '23.00', '22.00', '0-3', '2024-05-08 07:33:18', '2024-05-08 08:30:22'),
(8, 2, 'a', '2024-05-08', '2024-05-22', 'as', 'as', '1.00', '11.00', '1-2', '2024-05-08 08:30:51', '2024-05-08 08:30:59');

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

DROP TABLE IF EXISTS `policies`;
CREATE TABLE IF NOT EXISTS `policies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `policy_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `description`, `policy_type`) VALUES
(1, '• Full board accommodation whilst on safari in the mentioned en suite campsites, tented camps or lodges\r\n• Full meal breakfast, lunch andamp;amp;amp; dinner while on safari\r\n• All game drives viewing in a custom built 4x4 Safari Land Cruiser\r\n• Unlimited bottled drinking water and refrigerated cool box with soft drinks\r\n• All park and entrance fees\r\n• Office Assistance\r\n• Professional English speaking driverguide during the s', 'Price includes'),
(98, '• Flights to and from Tanzania\n• Visa fees for Tanzania approx $50 to $100\n• Gratuities andamp;amp;amp; tips for your safari cookguide or Driver , USD 20day\n• Any other items of a personal nature.\n• Optional Tours balloon rides USD 540 per person etc.', 'Price excludes');

-- --------------------------------------------------------

--
-- Table structure for table `rates`
--

DROP TABLE IF EXISTS `rates`;
CREATE TABLE IF NOT EXISTS `rates` (
  `rate_id` int NOT NULL AUTO_INCREMENT,
  `room_id` int NOT NULL,
  `season_name` varchar(255) NOT NULL,
  `season_start` date NOT NULL,
  `season_end` date NOT NULL,
  `price_usd` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rate_id`),
  KEY `room_id` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rates`
--

INSERT INTO `rates` (`rate_id`, `room_id`, `season_name`, `season_start`, `season_end`, `price_usd`, `created_at`, `updated_at`) VALUES
(12, 1, 'fafsa', '2024-05-24', '2024-05-13', '42342.00', '2024-05-03 14:20:04', '2024-05-03 14:20:04');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id` int NOT NULL AUTO_INCREMENT,
  `property_id` int NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `capacity` int NOT NULL,
  `menu_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`room_id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `property_id`, `room_type`, `capacity`, `menu_type`, `created_at`, `updated_at`) VALUES
(1, 1, 'Standard', 30, 'BB', '2024-05-03 09:58:58', '2024-05-05 13:02:31'),
(11, 1, 'Standard', 3, 'BB', '2024-05-05 12:55:21', '2024-05-05 12:55:21'),
(13, 3, 'Presidential Suite', 2, 'FB', '2024-05-05 13:08:07', '2024-05-05 13:08:07');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD CONSTRAINT `fk_hotel_id` FOREIGN KEY (`hotel_id`) REFERENCES `accommodations` (`property_id`);

--
-- Constraints for table `plane_rates`
--
ALTER TABLE `plane_rates`
  ADD CONSTRAINT `plane_rates_ibfk_1` FOREIGN KEY (`plane_id`) REFERENCES `planes` (`id`);

--
-- Constraints for table `rates`
--
ALTER TABLE `rates`
  ADD CONSTRAINT `rates_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `accommodations` (`property_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
