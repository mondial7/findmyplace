-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2017 at 10:46 PM
-- Server version: 5.7.11
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `findmyplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `findmyplace__account`
--

CREATE TABLE `findmyplace__account` (
  `id` int(11) NOT NULL,
  `about` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `avatar` longblob,
  `username` varchar(65) NOT NULL,
  `email` varchar(63) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstName` varchar(63) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastName` varchar(63) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(63) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `findmyplace__account_logged`
--

CREATE TABLE `findmyplace__account_logged` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `cookie_token` varchar(63) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `findmyplace__places`
--

CREATE TABLE `findmyplace__places` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `address` tinytext NOT NULL,
  `latitude` decimal(12,9) NOT NULL,
  `longitude` decimal(12,9) NOT NULL,
  `about` mediumtext NOT NULL,
  `ownership` enum('1','2','3') NOT NULL,
  `status` enum('1','2','3','4','5') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `findmyplace__projects`
--

CREATE TABLE `findmyplace__projects` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `title` tinytext,
  `about` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `findmyplace__account`
--
ALTER TABLE `findmyplace__account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `findmyplace__account_logged`
--
ALTER TABLE `findmyplace__account_logged`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cookie_token_UNIQUE` (`cookie_token`);

--
-- Indexes for table `findmyplace__places`
--
ALTER TABLE `findmyplace__places`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `findmyplace__projects`
--
ALTER TABLE `findmyplace__projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `findmyplace__projects_ibfk_2` (`place_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `findmyplace__account`
--
ALTER TABLE `findmyplace__account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT for table `findmyplace__account_logged`
--
ALTER TABLE `findmyplace__account_logged`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `findmyplace__places`
--
ALTER TABLE `findmyplace__places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `findmyplace__projects`
--
ALTER TABLE `findmyplace__projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `findmyplace__places`
--
ALTER TABLE `findmyplace__places`
  ADD CONSTRAINT `findmyplace__places_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `findmyplace__account` (`id`);

--
-- Constraints for table `findmyplace__projects`
--
ALTER TABLE `findmyplace__projects`
  ADD CONSTRAINT `findmyplace__projects_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `findmyplace__account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `findmyplace__projects_ibfk_2` FOREIGN KEY (`place_id`) REFERENCES `findmyplace__places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
