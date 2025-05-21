-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 02:06 PM
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
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `subtitle` varchar(200) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `button_text` varchar(50) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `button_text`, `button_link`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tiffn Delight', 'Home Made Meals', '1747739157_f30e716f54a1b05ce6eb.webp', 'Order Now', '#', 0, 1, '2025-05-20 11:05:57', '2025-05-20 11:05:57'),
(2, 'South Indian Home Meals', 'Order Online ', '1747739264_73fc4096220e04b305a9.png', 'Order Now', '#', 1, 1, '2025-05-20 11:07:44', '2025-05-20 11:07:44');

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
  `payment_id` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `booking_date`, `delivery_slot_id`, `total_amount`, `status`, `payment_method`, `payment_id`, `created_at`, `updated_at`) VALUES
(8, 1, '2025-05-19', 1, 70.00, 'cancelled', 'cash', NULL, '2025-05-18 09:49:36', '2025-05-18 09:52:09'),
(9, 1, '2025-05-19', 1, 80.00, 'delivered', 'wallet', NULL, '2025-05-18 10:02:36', '2025-05-18 10:05:39'),
(10, 3, '2025-05-19', 1, 150.00, 'delivered', 'cash', NULL, '2025-05-18 10:07:08', '2025-05-19 05:27:34'),
(11, 1, '2025-05-19', 1, 150.00, 'delivered', 'wallet', NULL, '2025-05-18 10:45:28', '2025-05-19 05:17:58'),
(12, 3, '2025-05-19', 1, 220.00, 'delivered', 'cash', NULL, '2025-05-18 10:48:32', '2025-05-19 05:17:29'),
(13, 1, '2025-05-19', 1, 70.00, 'delivered', 'wallet', NULL, '2025-05-18 10:49:53', '2025-05-19 05:18:04'),
(14, 2, '2025-05-20', 1, 70.00, 'cancelled', 'cash', NULL, '2025-05-19 05:07:54', '2025-05-19 05:21:17'),
(15, 3, '2025-05-20', 1, 220.00, 'delivered', 'wallet', NULL, '2025-05-19 05:16:32', '2025-05-19 05:17:21'),
(16, 2, '2025-05-20', 1, 70.00, 'delivered', 'wallet', NULL, '2025-05-19 05:27:07', '2025-05-19 05:27:29'),
(17, 2, '2025-05-20', 1, 80.00, 'cancelled', 'wallet', NULL, '2025-05-19 09:36:13', '2025-05-20 09:31:58'),
(18, 1, '2025-05-21', 1, 70.00, 'delivered', 'wallet', NULL, '2025-05-20 06:09:53', '2025-05-20 06:11:10'),
(19, 3, '2025-05-21', 1, 80.00, 'delivered', 'cash', NULL, '2025-05-20 06:25:40', '2025-05-21 08:11:13'),
(20, 2, '2025-05-21', 1, 70.00, 'cancelled', '', 'pay_QX7ljIRK2EXgH2', '2025-05-20 08:58:26', '2025-05-20 09:31:02'),
(21, 3, '2025-05-22', 1, 80.00, 'delivered', 'wallet', NULL, '2025-05-21 05:18:31', '2025-05-21 08:10:49'),
(22, 3, '2025-05-22', 1, 70.00, 'delivered', 'wallet', NULL, '2025-05-21 05:32:02', '2025-05-21 08:10:56'),
(23, 2, '2025-05-22', 1, 70.00, 'delivered', 'wallet', NULL, '2025-05-21 08:03:06', '2025-05-21 08:11:01'),
(24, 2, '2025-05-22', 1, 140.00, 'delivered', 'wallet', NULL, '2025-05-21 08:10:12', '2025-05-21 08:11:06');

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
(10, 8, 1, 1, 70.00, '2025-05-18 09:49:37', '2025-05-18 09:49:37'),
(11, 9, 3, 1, 80.00, '2025-05-18 10:02:36', '2025-05-18 10:02:36'),
(12, 10, 3, 1, 80.00, '2025-05-18 10:07:08', '2025-05-18 10:07:08'),
(13, 10, 1, 1, 70.00, '2025-05-18 10:07:08', '2025-05-18 10:07:08'),
(14, 11, 3, 1, 80.00, '2025-05-18 10:45:28', '2025-05-18 10:45:28'),
(15, 11, 2, 1, 70.00, '2025-05-18 10:45:28', '2025-05-18 10:45:28'),
(16, 12, 1, 1, 70.00, '2025-05-18 10:48:32', '2025-05-18 10:48:32'),
(17, 12, 2, 1, 70.00, '2025-05-18 10:48:32', '2025-05-18 10:48:32'),
(18, 12, 3, 1, 80.00, '2025-05-18 10:48:32', '2025-05-18 10:48:32'),
(19, 13, 1, 1, 70.00, '2025-05-18 10:49:53', '2025-05-18 10:49:53'),
(20, 14, 1, 1, 70.00, '2025-05-19 05:07:54', '2025-05-19 05:07:54'),
(21, 15, 2, 1, 70.00, '2025-05-19 05:16:32', '2025-05-19 05:16:32'),
(22, 15, 1, 1, 70.00, '2025-05-19 05:16:32', '2025-05-19 05:16:32'),
(23, 15, 3, 1, 80.00, '2025-05-19 05:16:32', '2025-05-19 05:16:32'),
(24, 16, 1, 1, 70.00, '2025-05-19 05:27:07', '2025-05-19 05:27:07'),
(25, 17, 3, 1, 80.00, '2025-05-19 09:36:13', '2025-05-19 09:36:13'),
(26, 18, 1, 1, 70.00, '2025-05-20 06:09:53', '2025-05-20 06:09:53'),
(27, 19, 3, 1, 80.00, '2025-05-20 06:25:40', '2025-05-20 06:25:40'),
(28, 20, 2, 1, 70.00, '2025-05-20 08:58:26', '2025-05-20 08:58:26'),
(29, 21, 3, 1, 80.00, '2025-05-21 05:18:31', '2025-05-21 05:18:31'),
(30, 22, 1, 1, 70.00, '2025-05-21 05:32:02', '2025-05-21 05:32:02'),
(31, 23, 1, 1, 70.00, '2025-05-21 08:03:06', '2025-05-21 08:03:06'),
(32, 24, 1, 1, 70.00, '2025-05-21 08:10:12', '2025-05-21 08:10:12'),
(33, 24, 2, 1, 70.00, '2025-05-21 08:10:12', '2025-05-21 08:10:12');

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
(1, '8:00 AM - 9:30 AM', 1, '2025-05-16 12:29:10', '2025-05-18 09:45:08'),
(10, ' 7:00 PM - 9:00 PM', 1, '2025-05-18 09:45:31', '2025-05-18 09:45:31');

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
(2, 'Egg Curry + Roti + Rice', 'Egg Curry + Roti + Rice', 70.00, '1747478985_427ef2d30e06c73181c7.jpg', 1, 0, '2025-05-16 13:01:11', '2025-05-20 06:12:15'),
(3, 'Sahi Paneer + Roti 4 + Salad ', 'Sahi Paneer + Roti 4 + Salad ', 80.00, '1747478891_78cd1375a89b3368b3a6.jpg', 1, 1, '2025-05-17 10:48:11', '2025-05-19 05:14:49');

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
(3, '2025-05-17-112953', 'App\\Database\\Migrations\\AddVegetarianFieldToDishes', 'default', 'App', 1747481496, 3),
(4, '2023-11-15-123456', 'App\\Database\\Migrations\\UpdateWalletTransactions', 'default', 'App', 1747730662, 4),
(5, '2023-11-15-123457', 'App\\Database\\Migrations\\UpdateWalletTransactionsData', 'default', 'App', 1747730789, 5),
(6, '2023-11-15-123458', 'App\\Database\\Migrations\\AddPaymentIdToBookings', 'default', 'App', 1747730960, 6),
(7, '2023-11-15-123459', 'App\\Database\\Migrations\\ModifyWalletTransactions', 'default', 'App', 1747734127, 7),
(8, '2023-11-15-123460', 'App\\Database\\Migrations\\CreateBannersTable', 'default', 'App', 1747734127, 7);

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
(3, 2, 16, 5, 'Very Nice Meal', '2025-05-19 05:28:02', '2025-05-19 05:28:02');

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
(1, 'Vinay', 'vinaysingh43@gmail.com', '$2y$10$7urgCqHfKBqF42/mXfJJrusNoWlm5roXNVjhHI8S2b7hSZ2cIbcMq', 'LGF 10 ANORA KALA', '9457790679', '2025-05-16 13:03:22', '2025-05-16 13:03:22'),
(2, 'Anika Singh', 'anikasingh33@gmail.com', '$2y$10$nbn/tH0FBpbMt6HMio0g2.DK4nhrs./RXwCVmO9aigh/qTtRn9oMG', 'LGF 10 Anora Kalan , Lucknow Utter Pradesh 226028', '9457790679', '2025-05-18 07:03:01', '2025-05-18 07:03:01'),
(3, 'Vaishnavi Singh', 'viashnavisingh@gmail.com', '$2y$10$nISm838e15SiyT91IMm9/.EYcIiX5eEIHgCzyMDj7V.IYuSWaXchW', 'LGF 10 ANORA KALAN LUCKNOW UP 226028', '9457790679', '2025-05-18 07:07:19', '2025-05-18 07:07:19');

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
(1, 1, 650.00, '2025-05-16 13:03:22', '2025-05-20 06:09:53'),
(2, 2, 970.00, '2025-05-18 07:03:01', '2025-05-21 08:10:12'),
(3, 3, 1330.00, '2025-05-18 07:07:19', '2025-05-21 05:32:02');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `wallet_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('credit','debit') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`id`, `wallet_id`, `user_id`, `type`, `amount`, `description`, `created_at`) VALUES
(1, 1, 1, 'credit', 100.00, 'Wallet Recharge', '2025-05-17 07:47:40'),
(2, 1, 1, 'debit', 70.00, 'Payment for tiffin booking', '2025-05-17 07:48:13'),
(3, 1, 1, 'credit', 70.00, 'Refund for cancelled booking #2', '2025-05-17 07:49:12'),
(4, 1, 1, 'debit', 140.00, 'Payment for tiffin booking', '2025-05-17 07:50:26'),
(5, 1, 1, 'credit', 100.00, 'Wallet Recharge', '2025-05-17 09:33:19'),
(6, 1, 1, 'debit', 70.00, 'Payment for tiffin booking', '2025-05-17 09:34:27'),
(7, 1, 1, 'debit', 70.00, 'Payment for tiffin booking', '2025-05-17 09:48:17'),
(8, 2, 2, 'credit', 1000.00, 'Wallet Recharge', '2025-05-18 07:03:29'),
(9, 2, 2, 'debit', 70.00, 'Payment for tiffin booking', '2025-05-18 07:03:36'),
(10, 3, 3, 'credit', 500.00, 'Wallet Recharge', '2025-05-18 07:07:30'),
(11, 3, 3, 'credit', 1000.00, 'Wallet Recharge', '2025-05-18 07:07:35'),
(12, 1, 1, 'credit', 1000.00, 'Wallet Recharge', '2025-05-18 10:02:01'),
(13, 1, 1, 'debit', 80.00, 'Payment for tiffin booking', '2025-05-18 10:02:36'),
(17, 2, 2, 'credit', 1000.00, 'Wallet Recharge via Razorpay (Payment ID: pay_QX7jE7mLrjEWOV)', '2025-05-20 08:56:05'),
(18, 2, 2, 'credit', 80.00, 'Refund for cancelled booking #17', '2025-05-20 09:31:58'),
(19, 3, 3, 'debit', 80.00, 'Payment for order #21', '2025-05-21 05:18:31'),
(20, 3, 3, 'debit', 70.00, 'Payment for order #22', '2025-05-21 05:32:02'),
(21, 2, 2, 'debit', 70.00, 'Payment for order #23', '2025-05-21 08:03:06'),
(22, 2, 2, 'debit', 140.00, 'Payment for order #24', '2025-05-21 08:10:12');

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
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `booking_items`
--
ALTER TABLE `booking_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `delivery_slots`
--
ALTER TABLE `delivery_slots`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dishes`
--
ALTER TABLE `dishes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
