-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 01:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newrent`
--

-- --------------------------------------------------------

--
-- Table structure for table `cmps`
--

CREATE TABLE `cmps` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `cmp` varchar(200) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `fullname` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cmps`
--

INSERT INTO `cmps` (`id`, `name`, `cmp`, `username`, `fullname`) VALUES
(1, 'f', 'f', 'admin', 'Mahantesh Kumbar');

-- --------------------------------------------------------

--
-- Table structure for table `kyc_verifications`
--

CREATE TABLE `kyc_verifications` (
  `id` int(11) NOT NULL,
  `mobile` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `mother_name` varchar(255) NOT NULL,
  `residential_proof_path` varchar(255) NOT NULL,
  `id_proof_path` varchar(255) NOT NULL,
  `selfie_path` varchar(255) NOT NULL,
  `status` enum('Pending','Verified','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kyc_verifications`
--

INSERT INTO `kyc_verifications` (`id`, `mobile`, `user_name`, `father_name`, `mother_name`, `residential_proof_path`, `id_proof_path`, `selfie_path`, `status`, `created_at`) VALUES
(1, '9879879787', 'Amit Raut', 'राम गोविन्द राउत', 'सकुन्तला देवी राउत', 'uploads/kyc/6782f8ae2db5c.png', 'uploads/kyc/6782f8ae2dcbc.png', 'uploads/kyc/6782f8ae2de09.png', 'Verified', '2025-01-11 23:03:10'),
(7, '1234567890', 'Ashish Raut', 'kishun', 'gita', 'uploads/kyc/6782f32b52252.jpg', 'uploads/kyc/6782f32b52391.jpg', 'uploads/kyc/6782f32b5249a.jpg', 'Verified', '2025-01-11 22:39:39'),
(8, '9999999999', '\r\nSamip Chhetri', 'xyz', 'xym', 'uploads/kyc/678362c0ef93f.png', 'uploads/kyc/678362c0efa94.png', 'uploads/kyc/678362c0f34bb.png', 'Verified', '2025-01-12 06:35:44');

-- --------------------------------------------------------

--
-- Table structure for table `room_rental_registrations`
--

CREATE TABLE `room_rental_registrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `fullname` varchar(191) NOT NULL,
  `mobile` varchar(191) NOT NULL,
  `alternat_mobile` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `country` varchar(191) NOT NULL,
  `state` varchar(191) NOT NULL,
  `city` varchar(191) NOT NULL,
  `landmark` varchar(191) NOT NULL,
  `rent` varchar(191) NOT NULL,
  `sale` varchar(190) DEFAULT NULL,
  `deposit` varchar(191) NOT NULL,
  `plot_number` varchar(191) NOT NULL,
  `rooms` varchar(100) DEFAULT NULL,
  `address` varchar(191) NOT NULL,
  `accommodation` varchar(191) NOT NULL,
  `description` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `open_for_sharing` varchar(191) DEFAULT NULL,
  `other` varchar(191) DEFAULT NULL,
  `vacant` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `user_id` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_rental_registrations`
--

INSERT INTO `room_rental_registrations` (`id`, `fullname`, `mobile`, `alternat_mobile`, `email`, `country`, `state`, `city`, `landmark`, `rent`, `sale`, `deposit`, `plot_number`, `rooms`, `address`, `accommodation`, `description`, `image`, `open_for_sharing`, `other`, `vacant`, `created_at`, `updated_at`, `user_id`) VALUES
(14, 'jelly fish', '2345676997', '', 'chet@gmrail.com', 'india', 'karnataka', 'Belagavi', '', '1232', '12', '33333', '78 nh', '1bhk', 'port road bgm', '', '', 'uploads/', NULL, NULL, 1, '2018-03-09 05:06:43', '2018-03-09 05:06:43', 2);

-- --------------------------------------------------------

--
-- Table structure for table `room_rental_registrations_apartment`
--

CREATE TABLE `room_rental_registrations_apartment` (
  `id` int(10) UNSIGNED NOT NULL,
  `fullname` varchar(191) NOT NULL,
  `mobile` varchar(191) NOT NULL,
  `alternat_mobile` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `country` varchar(191) NOT NULL,
  `state` varchar(191) NOT NULL,
  `city` varchar(191) NOT NULL,
  `landmark` varchar(191) NOT NULL,
  `rent` varchar(191) NOT NULL,
  `deposit` varchar(191) NOT NULL,
  `plot_number` varchar(191) NOT NULL,
  `apartment_name` varchar(100) DEFAULT NULL,
  `ap_number_of_plats` varchar(100) DEFAULT NULL,
  `rooms` varchar(100) DEFAULT NULL,
  `floor` varchar(100) DEFAULT NULL,
  `purpose` varchar(100) DEFAULT NULL,
  `own` varchar(100) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `address` varchar(191) NOT NULL,
  `accommodation` varchar(191) NOT NULL,
  `description` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `open_for_sharing` varchar(191) DEFAULT NULL,
  `other` varchar(191) DEFAULT NULL,
  `vacant` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `user_id` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_rental_registrations_apartment`
--

INSERT INTO `room_rental_registrations_apartment` (`id`, `fullname`, `mobile`, `alternat_mobile`, `email`, `country`, `state`, `city`, `landmark`, `rent`, `deposit`, `plot_number`, `apartment_name`, `ap_number_of_plats`, `rooms`, `floor`, `purpose`, `own`, `area`, `address`, `accommodation`, `description`, `image`, `open_for_sharing`, `other`, `vacant`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'apart', '2345676567', '', 'jhbdah@gmail.com', 'nepal', 'lumbini', 'nepalgunj', 'nere sanima bank', '3000', '500', '', 'mant apartment', '101', 'single', '2nd', 'Residential', 'rented', '1sqr feet', 'npj 12', 'wifi', 'well ', 'uploads/Jellyfish.jpg', NULL, NULL, 1, '2018-04-04 11:20:56', '2018-04-04 11:20:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(191) NOT NULL,
  `mobile` varchar(191) NOT NULL,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `role` varchar(100) DEFAULT 'user',
  `status` int(1) NOT NULL DEFAULT 1,
  `kyc_status` enum('Pending','Verified','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `mobile`, `username`, `email`, `password`, `created_at`, `updated_at`, `role`, `status`, `kyc_status`) VALUES
(1, 'Amit Raut', '9879879787', 'admin', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3', NULL, NULL, 'admin', 1, 'Pending'),
(2, '\r\nSamip Chhetri', '9999999999', 'samip', 'samip@gmail.com', 'ee11cbb19052e40b07aac0ca060c23ee', '2018-02-08 06:53:53', '2018-02-08 06:53:53', 'user', 1, 'Pending'),
(7, 'Ashish Raut', '1234567890', 'Ashish', 'rautnilan@gmail.com', 'ee11cbb19052e40b07aac0ca060c23ee', '2025-01-11 20:15:24', '2025-01-11 20:15:24', 'user', 1, 'Pending'),
(9, 'Ashish Raut', '1234567892', 'user', 'shailendra_karn@yahoo.com', 'ee11cbb19052e40b07aac0ca060c23ee', '2025-01-11 22:14:03', '2025-01-11 22:14:03', 'user', 1, 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cmps`
--
ALTER TABLE `cmps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mobile` (`mobile`);

--
-- Indexes for table `room_rental_registrations`
--
ALTER TABLE `room_rental_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_rental_registrations_mobile_unique` (`mobile`),
  ADD UNIQUE KEY `room_rental_registrations_email_unique` (`email`);

--
-- Indexes for table `room_rental_registrations_apartment`
--
ALTER TABLE `room_rental_registrations_apartment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_mobile_unique` (`mobile`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD UNIQUE KEY `mobile_2` (`mobile`),
  ADD UNIQUE KEY `mobile_3` (`mobile`),
  ADD KEY `id_3` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cmps`
--
ALTER TABLE `cmps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `room_rental_registrations`
--
ALTER TABLE `room_rental_registrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `room_rental_registrations_apartment`
--
ALTER TABLE `room_rental_registrations_apartment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  ADD CONSTRAINT `fk_mobile` FOREIGN KEY (`mobile`) REFERENCES `users` (`mobile`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
