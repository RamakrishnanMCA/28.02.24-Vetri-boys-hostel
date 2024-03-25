-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 25, 2024 at 09:33 AM
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
-- Database: `hostel`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(35) NOT NULL,
  `email` varchar(35) NOT NULL,
  `password` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`) VALUES
(1, 'Ramki', 'ramki@gmail.com', 'Ramki@2002');

-- --------------------------------------------------------

--
-- Table structure for table `archived_users`
--

DROP TABLE IF EXISTS `archived_users`;
CREATE TABLE IF NOT EXISTS `archived_users` (
  `id` int DEFAULT NULL,
  `name` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `father_name` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `booking_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `food_status` int DEFAULT NULL,
  `guardian_name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `relation` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `guardian_contact` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `emergency_contact` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `total_fees` int DEFAULT NULL,
  `selected_room` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `transaction_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `photo_filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `id_proof_filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `archived_users`
--

INSERT INTO `archived_users` (`id`, `name`, `father_name`, `dob`, `address`, `phone_number`, `email`, `password`, `booking_id`, `start_date`, `duration`, `food_status`, `guardian_name`, `relation`, `guardian_contact`, `emergency_contact`, `total_fees`, `selected_room`, `created_at`, `transaction_id`, `payment_date`, `photo_filename`, `id_proof_filename`) VALUES
(3, 'ramki', 'das', '1996-05-17', 'valayazepettai', '9654871231', 'ram@gmail.com', 'Ramki123@', 0, '0000-00-00', 0, 0, '', '', '', '', 0, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `booking_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `start_date` date NOT NULL,
  `duration` int NOT NULL,
  `food_status` int NOT NULL,
  `guardian_name` varchar(45) NOT NULL,
  `relation` varchar(45) NOT NULL,
  `guardian_contact` varchar(12) NOT NULL,
  `emergency_contact` varchar(12) NOT NULL,
  `total_fees` int NOT NULL,
  `selected_room` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`booking_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `start_date`, `duration`, `food_status`, `guardian_name`, `relation`, `guardian_contact`, `emergency_contact`, `total_fees`, `selected_room`, `created_at`) VALUES
(8, 2, '2024-02-21', 5, 1, 'rear', 'rewrwer', '56', '9894056854', 30000, 'room2', '2024-02-19 13:28:46');
-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

DROP TABLE IF EXISTS `foods`;
CREATE TABLE IF NOT EXISTS `foods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `foodname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `price` int NOT NULL,
  `photo_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`id`, `foodname`, `price`, `photo_name`) VALUES
(1, 'omlette', 15, '65eebfd6ae055_omelette.jpg'),
(2, 'chicken 65 \r\n(only for lunch)', 50, '65eec069986fb_chicken65.jpg'),
(3, 'plain egg varieties', 10, '65eec1e5d33d1_egg varieties.png');

-- --------------------------------------------------------

--
-- Table structure for table `food_order`
--

DROP TABLE IF EXISTS `food_order`;
CREATE TABLE IF NOT EXISTS `food_order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `foodname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `foodcount` int NOT NULL,
  `price` int NOT NULL,
  `bf_lun_din` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `userid` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `food_order`
--

INSERT INTO `food_order` (`id`, `user_id`, `foodname`, `foodcount`, `price`, `bf_lun_din`, `created_at`) VALUES
(24, 2, 'omlette', 1, 15, 'lunch', '2024-02-13 04:29:47'),
(20, 2, 'omlette', 2, 30, 'breakfast', '2024-03-12 14:29:33'),
(23, 2, 'chicken 65 \r\n(only for lunch)', 1, 50, 'lunch', '2024-03-13 04:08:19'),
(22, 2, 'plain egg varieties', 2, 20, 'dinner', '2024-03-12 14:29:33'),
(25, 2, 'chicken 65 \r\n(only for lunch)', 1, 50, 'lunch', '2024-02-13 04:29:47'),
(26, 2, 'plain egg varieties', 1, 10, 'dinner', '2024-02-13 04:29:47'),
(27, 5, 'omlette', 1, 15, 'breakfast', '2024-03-15 16:24:35');

-- --------------------------------------------------------

--
-- Table structure for table `leave_form`
--

DROP TABLE IF EXISTS `leave_form`;
CREATE TABLE IF NOT EXISTS `leave_form` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `room` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `return_date` datetime NOT NULL,
  `place` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `purpose` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `parents_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `leave_form`
--

INSERT INTO `leave_form` (`id`, `user_id`, `name`, `room`, `start_date`, `return_date`, `place`, `purpose`, `parents_number`, `message`, `created_at`) VALUES
(1, 2, 'Ramakrishnan', 'room2', '2024-03-16 21:06:00', '2024-03-19 21:06:00', 'Kumbakonam', 'Holiday', '+916374125501', 'Your son Mr. Ramakrishnan (room2) is leaving the hostel at 2024-03-06T21:06. He mentioned that he will return to the hostel at 2024-03-12T21:06. He is going to Kumbakonam because of Holiday. Thank you. By V-Boys Hostel.', '2024-03-05 13:37:05');

-- --------------------------------------------------------

--
-- Table structure for table `month_payment`
--

DROP TABLE IF EXISTS `month_payment`;
CREATE TABLE IF NOT EXISTS `month_payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `monthlyfees` int DEFAULT NULL,
  `total_mess_food_price` int DEFAULT NULL,
  `total_amount` int DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `month_payment`
--

INSERT INTO `month_payment` (`id`, `user_id`, `name`, `monthlyfees`, `total_mess_food_price`, `total_amount`, `transaction_id`, `created_at`) VALUES
(2, 2, 'Ramakrishnan', 6000, 75, 6075, 'Tjugy234RTgu', '2024-03-13 04:43:13');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `transaction_id` (`transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `user_id`, `transaction_id`, `payment_date`) VALUES
(2, 2, '54rtewrdfg', '2024-02-28 09:56:47');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `room_number` varchar(50) NOT NULL,
  `total_seats` int NOT NULL,
  `occupied_seats` int NOT NULL,
  `available_seats` int NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_number`, `total_seats`, `occupied_seats`, `available_seats`, `image_path`) VALUES
('room1', 6, 0, 6, 'uploads/65e2d04c30aca_Room1.png'),
('room2', 6, 3, 3, 'uploads/65e2d1653f5be_Room2.png'),
('room3', 6, 2, 4, 'uploads/65e541deab09e_Room3.png'),
('room4', 6, 1, 5, 'uploads/65e2d17ba4d6d_Room4.png'),
('room5', 6, 1, 5, 'uploads/65e2d186c2a5b_Room5.png'),
('room6', 6, 0, 6, 'uploads/65e2d33d36c77_commonarea.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(35) NOT NULL,
  `father_name` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dob` date NOT NULL,
  `address` varchar(75) NOT NULL,
  `phone_number` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `father_name`, `dob`, `address`, `phone_number`, `email`, `password`) VALUES
(2, 'Ramakrishnan', 'M', '1999-05-17', 'Valayzeapettai', '6374161088', 'ramki@gmail.com', 'Ramki@20022');

-- --------------------------------------------------------

--
-- Table structure for table `user_files`
--

DROP TABLE IF EXISTS `user_files`;
CREATE TABLE IF NOT EXISTS `user_files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `photo_filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_proof_filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

---
--- use user details am deleted this because of privacy
---

-- --------------------------------------------------------

--
-- Table structure for table `user_query`
--

DROP TABLE IF EXISTS `user_query`;
CREATE TABLE IF NOT EXISTS `user_query` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `query_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `admin_reply` varchar(255) DEFAULT 'Admin will reply soon',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_query`
--

INSERT INTO `user_query` (`id`, `user_id`, `query_text`, `admin_reply`) VALUES
(74, 2, 'Good and nice Hostel. But mosquito problem.\r\n', 'Admin will reply soon');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
