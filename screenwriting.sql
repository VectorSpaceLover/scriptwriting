-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 02, 2021 at 08:42 AM
-- Server version: 8.0.26-0ubuntu0.20.04.2
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `screenwriting`
--

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int NOT NULL,
  `coupon_code` varchar(100) NOT NULL,
  `coupon_price` varchar(100) NOT NULL,
  `used` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `coupon_code`, `coupon_price`, `used`) VALUES
(1, 'test', '15', 0),
(2, 'John Doe', '1', 1),
(3, 'Gary Riley', '2', 1),
(4, 'Edward Siu', '3', 1),
(5, 'Betty Simons', '4', 1),
(6, 'Frances Lieberman', '5', 1),
(7, 'Jason Gregson', '6', 1);

-- --------------------------------------------------------

--
-- Table structure for table `paypal_payments`
--

CREATE TABLE `paypal_payments` (
  `payment_id` int NOT NULL,
  `txn_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `payment_gross` float(10,2) DEFAULT NULL,
  `currency_code` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `payer_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `paypal_payments`
--

INSERT INTO `paypal_payments` (`payment_id`, `txn_id`, `created`, `payment_gross`, `currency_code`, `payment_status`, `name`, `payer_email`) VALUES
(1, 'txn_3JRSwrFmPwgSi4LO0hNBgE9O', '2021-08-23 08:01:42', 15.00, 'usd', 'succeeded', 'Dinesh Karthik', 'admin@admin.com'),
(2, 'txn_3JRXcwFmPwgSi4LO0H6s7RL3', '2021-08-23 13:01:27', 15.00, 'usd', 'succeeded', 'Dinesh Karthik', '');

-- --------------------------------------------------------

--
-- Table structure for table `scripts`
--

CREATE TABLE `scripts` (
  `id` int NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  `contents` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Table structure for table `square_payments`
--

CREATE TABLE `square_payments` (
  `payment_id` int NOT NULL,
  `txn_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `payment_gross` float(10,2) DEFAULT NULL,
  `currency_code` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `payer_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `square_payments`
--

INSERT INTO `square_payments` (`payment_id`, `txn_id`, `created`, `payment_gross`, `currency_code`, `payment_status`, `name`, `payer_email`) VALUES
(1, 'txn_3JRSwrFmPwgSi4LO0hNBgE9O', '2021-08-23 08:01:42', 15.00, 'usd', 'succeeded', 'Dinesh Karthik', 'admin@admin.com'),
(2, 'txn_3JRXcwFmPwgSi4LO0H6s7RL3', '2021-08-23 13:01:27', 15.00, 'usd', 'succeeded', 'Dinesh Karthik', '');

-- --------------------------------------------------------

--
-- Table structure for table `stripe_payments`
--

CREATE TABLE `stripe_payments` (
  `payment_id` int NOT NULL,
  `txn_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `payment_gross` float(10,2) DEFAULT NULL,
  `currency_code` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `payer_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stripe_payments`
--

INSERT INTO `stripe_payments` (`payment_id`, `txn_id`, `created`, `payment_gross`, `currency_code`, `payment_status`, `name`, `payer_email`) VALUES
(1, 'txn_3JRSwrFmPwgSi4LO0hNBgE9O', '2021-08-23 08:01:42', 15.00, 'usd', 'succeeded', 'Dinesh Karthik', 'admin@admin.com'),
(2, 'txn_3JRXcwFmPwgSi4LO0H6s7RL3', '2021-08-23 13:01:27', 15.00, 'usd', 'succeeded', 'Dinesh Karthik', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `user_type` int DEFAULT '4',
  `email` varchar(255) DEFAULT NULL,
  `email_verify` int NOT NULL DEFAULT '0',
  `password` text,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `gender` varchar(100) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `modified` datetime DEFAULT CURRENT_TIMESTAMP,
  `access_code` text,
  `activetime` datetime DEFAULT NULL,
  `expiry_date` varchar(10) DEFAULT NULL,
  `txn_id` varchar(255) DEFAULT NULL,
  `payment_gross` float(10,2) DEFAULT NULL,
  `currency_code` varchar(10) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `payment_plan` text,
  `deviceid` varchar(255) DEFAULT NULL,
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `payment_charge` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `email`, `email_verify`, `password`, `firstname`, `lastname`, `active`, `created`, `gender`, `picture`, `modified`, `access_code`, `activetime`, `expiry_date`, `txn_id`, `payment_gross`, `currency_code`, `payment_status`, `payment_plan`, `deviceid`, `address1`, `address2`, `city`, `state`, `zip`, `phone`, `payment_charge`) VALUES
(1, 1, 'admin@admin.com', 1, 'dDRHcHlsd0NrdENlVVozY29obUI0QT09', 'Dinesh', 'Karthik', 1, '2021-08-21 08:57:18', 'male', NULL, '2021-08-21 08:57:18', NULL, '2021-08-21 05:26:48', NULL, 'txn_3JRSwrFmPwgSi4LO0hNBgE9O', 15.00, 'usd', 'succeeded', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL),
(4, 4, 'vsc.india01@gmail.com', 0, 'V3hxKzQ0UEFWRXYzM056dFpYU0dKUT09', 'Dinesh', 'Karthik', 0, '2021-08-21 14:22:04', NULL, NULL, '2021-08-21 14:22:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '9524356849', NULL),
(9, 1, 'vsc.india011@gmail.com', 0, 'eERRV2tRNnV4V3pvVVliNFN3TXE0dz09', 'Dinesh', '', 0, '2021-09-01 10:03:44', NULL, NULL, '2021-09-01 10:03:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9524356849', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` int NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `scripts`
--
ALTER TABLE `scripts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `square_payments`
--
ALTER TABLE `square_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `stripe_payments`
--
ALTER TABLE `stripe_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `scripts`
--
ALTER TABLE `scripts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `square_payments`
--
ALTER TABLE `square_payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stripe_payments`
--
ALTER TABLE `stripe_payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
