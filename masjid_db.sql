-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2025 at 02:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `masjid_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `masjid_info`
--

CREATE TABLE `masjid_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `masjid_info`
--

INSERT INTO `masjid_info` (`id`, `name`, `address`, `contact_info`) VALUES
(2, 'Anuradhapura Mohideen Jummah Grand Masjid', 'Main Street, Anuradhapura', '0252 222 822');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `defined_amount` decimal(10,2) DEFAULT NULL,
  `family_count` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `name`, `defined_amount`, `family_count`, `region_id`) VALUES
(100000, 'mjm', 1.00, 2, NULL),
(100001, 'jiffry', 150.00, 2, 20),
(100002, 'Mohamed', 800.00, 3, 18),
(100003, 'ibrahim', 1200.00, 4, 18),
(100004, 'Hafiz', 2000.00, 4, 20),
(100005, 'Munzir', 2000.00, 5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `member_info`
--

CREATE TABLE `member_info` (
  `id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `info_key` varchar(255) DEFAULT NULL,
  `info_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `region_id` int(11) NOT NULL,
  `region_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`region_id`, `region_name`) VALUES
(1, 'Jaffna Junction'),
(2, 'Market Side'),
(3, 'Lane'),
(4, 'Elacut Road'),
(5, 'Malwathu Oya'),
(6, 'Jaynthi Mawatha'),
(7, 'Udaya Mawatha'),
(8, 'UCQ'),
(9, 'k50'),
(10, 'Mailagas Junction'),
(11, 'Baudaloka Mawatha'),
(12, 'Nagasena Mawatha'),
(13, 'SOS'),
(14, 'Dahaiyagama Junction'),
(15, 'Godage Mawatha'),
(16, 'Airport Road'),
(17, 'Gewal 55'),
(18, 'K30'),
(19, 'Jayasiripura'),
(20, 'Yasasiripura'),
(21, 'Pubudupura'),
(22, 'Bank Town'),
(23, 'New Bus Stand'),
(24, 'K12');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `member_id`, `paid_date`, `amount`, `remarks`) VALUES
(3, 100001, '2025-07-27', 1500.00, ''),
(4, 100001, '2025-07-28', 1500.00, ''),
(5, 100002, '2025-07-29', 950.00, ''),
(6, 100000, '2025-07-29', 1400.00, ''),
(7, 100004, '2025-07-29', 2500.00, 'above the amount'),
(8, 100005, '2025-07-29', 2000.00, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `masjid_info`
--
ALTER TABLE `masjid_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `member_info`
--
ALTER TABLE `member_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`region_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `masjid_info`
--
ALTER TABLE `masjid_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100006;

--
-- AUTO_INCREMENT for table `member_info`
--
ALTER TABLE `member_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`);

--
-- Constraints for table `member_info`
--
ALTER TABLE `member_info`
  ADD CONSTRAINT `member_info_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
