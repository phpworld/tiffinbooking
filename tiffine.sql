-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 01:54 PM
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
-- Database: `tiffine`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$EO9VAII07V6DCcNzWV0taelIJd2MNJUxXLWpT7qU8Vm45sVtpJlIC', 'Administrator', '2025-05-16 12:29:10', '2025-05-16 12:29:10');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `delivery_slot_id` int(11) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','confirmed','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` enum('wallet','cash') NOT NULL DEFAULT 'wallet',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `booking_date`, `delivery_slot_id`, `total_amount`, `status`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-05-18', 8, 140.00, 'delivered', 'cash', '2025-05-17 07:11:55', '2025-05-17 07:14:02'),
(2, 1, '2025-05-19', 7, 70.00, 'cancelled', 'wallet', '2025-05-17 07:48:13', '2025-05-17 07:49:12'),
(3, 1, '2025-05-18', 7, 140.00, 'delivered', 'wallet', '2025-05-17 07:50:26', '2025-05-17 07:51:00'),
(4, 1, '2025-05-18', 8, 70.00, 'delivered', 'wallet', '2025-05-17 09:34:27', '2025-05-17 09:35:17'),
(5, 1, '2025-05-18', 8, 70.00, 'delivered', 'wallet', '2025-05-17 09:48:17', '2025-05-17 09:48:57');

-- --------------------------------------------------------

--
-- Table structure for table `booking_items`
--

CREATE TABLE `booking_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `dish_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_items`
--

INSERT INTO `booking_items` (`id`, `booking_id`, `dish_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 70.00, '2025-05-17 07:11:55', '2025-05-17 07:11:55'),
(2, 1, 1, 1, 70.00, '2025-05-17 07:11:55', '2025-05-17 07:11:55'),
(3, 2, 2, 1, 70.00, '2025-05-17 07:48:13', '2025-05-17 07:48:13'),
(4, 3, 2, 2, 70.00, '2025-05-17 07:50:26', '2025-05-17 07:50:26'),
(5, 4, 1, 1, 70.00, '2025-05-17 09:34:27', '2025-05-17 09:34:27'),
(6, 5, 2, 1, 70.00, '2025-05-17 09:48:17', '2025-05-17 09:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_slots`
--

CREATE TABLE `delivery_slots` (
  `id` int(11) UNSIGNED NOT NULL,
  `slot_time` varchar(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_slots`
--

INSERT INTO `delivery_slots` (`id`, `slot_time`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '8:00 AM - 9:00 AM', 1, '2025-05-16 12:29:10', '2025-05-16 12:29:10'),
(2, '9:00 AM - 10:00 AM', 1, '2025-05-16 12:29:10', '2025-05-16 12:29:10'),
(3, '10:00 AM - 11:00 AM', 1, '2025-05-16 12:29:10', '2025-05-16 12:29:10'),
(4, '11:00 AM - 12:00 PM', 1, '2025-05-16 12:29:10', '2025-05-16 12:29:10'),
(5, '12:00 PM - 1:00 PM', 1, '2025-05-16 12:29:10', '2025-05-16 12:29:10'),
(6, '1:00 PM - 2:00 PM', 1, '2025-05-16 12:29:10', '2025-05-16 12:29:10'),
(7, '6:00 PM - 7:00 PM', 1, '2025-05-16 12:29:10', '2025-05-16 12:29:10'),
(8, '7:00 PM - 8:00 PM', 1, '2025-05-16 12:29:10', '2025-05-16 12:29:10'),
(9, '8:00 PM - 9:00 PM', 1, '2025-05-16 12:29:10', '2025-05-16 12:29:10');

-- --------------------------------------------------------

--
-- Table structure for table `dishes`
--

CREATE TABLE `dishes` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 1,
  `is_vegetarian` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dishes`
--

INSERT INTO `dishes` (`id`, `name`, `description`, `price`, `image`, `available`, `is_vegetarian`, `created_at`, `updated_at`) VALUES
(1, 'Rice + Dal + Roti + Sabji ', 'Rice + Dal + Roti + Sabji ', 70.00, '1747479007_0f8ba2bca1f0112e6c8f.jpeg', 1, 1, '2025-05-16 13:00:11', '2025-05-17 10:50:07'),
(2, 'Egg Curry + Roti + Rice', 'Egg Curry + Roti + Rice', 70.00, '1747478985_427ef2d30e06c73181c7.jpg', 1, 0, '2025-05-16 13:01:11', '2025-05-17 11:36:48'),
(3, 'Sahi Paneer + Roti 4 + Salad ', 'Sahi Paneer + Roti 4 + Salad ', 80.00, '1747478891_78cd1375a89b3368b3a6.jpg', 1, 1, '2025-05-17 10:48:11', '2025-05-17 10:48:11');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-05-16-113610', 'App\\Database\\Migrations\\CreateTiffineTables', 'default', 'App', 1747395452, 1),
(2, '2023_06_15_000001', 'App\\Database\\Migrations\\CreateReviewsTable', 'default', 'App', 1747478435, 2),
(3, '2025-05-17-112953', 'App\\Database\\Migrations\\AddVegetarianFieldToDishes', 'default', 'App', 1747481496, 3);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `rating` int(1) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `booking_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, 'very nice ', '2025-05-17 10:58:04', '2025-05-17 10:58:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Vinay', 'vinaysingh43@gmail.com', '$2y$10$7urgCqHfKBqF42/mXfJJrusNoWlm5roXNVjhHI8S2b7hSZ2cIbcMq', 'LGF 10 ANORA KALA', '9457790679', '2025-05-16 13:03:22', '2025-05-16 13:03:22');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `created_at`, `updated_at`) VALUES
(1, 1, 20.00, '2025-05-16 13:03:22', '2025-05-17 09:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` enum('credit','debit') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`id`, `user_id`, `type`, `amount`, `description`, `created_at`) VALUES
(1, 1, 'credit', 100.00, 'Wallet Recharge', '2025-05-17 07:47:40'),
(2, 1, 'debit', 70.00, 'Payment for tiffin booking', '2025-05-17 07:48:13'),
(3, 1, 'credit', 70.00, 'Refund for cancelled booking #2', '2025-05-17 07:49:12'),
(4, 1, 'debit', 140.00, 'Payment for tiffin booking', '2025-05-17 07:50:26'),
(5, 1, 'credit', 100.00, 'Wallet Recharge', '2025-05-17 09:33:19'),
(6, 1, 'debit', 70.00, 'Payment for tiffin booking', '2025-05-17 09:34:27'),
(7, 1, 'debit', 70.00, 'Payment for tiffin booking', '2025-05-17 09:48:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_delivery_slot_id_foreign` (`delivery_slot_id`);

--
-- Indexes for table `booking_items`
--
ALTER TABLE `booking_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_items_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_items_dish_id_foreign` (`dish_id`);

--
-- Indexes for table `delivery_slots`
--
ALTER TABLE `delivery_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_user_id_foreign` (`user_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_transactions_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking_items`
--
ALTER TABLE `booking_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `delivery_slots`
--
ALTER TABLE `delivery_slots`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `dishes`
--
ALTER TABLE `dishes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_delivery_slot_id_foreign` FOREIGN KEY (`delivery_slot_id`) REFERENCES `delivery_slots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking_items`
--
ALTER TABLE `booking_items`
  ADD CONSTRAINT `booking_items_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_items_dish_id_foreign` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
