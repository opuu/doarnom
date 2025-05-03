-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250110.17c477b7c1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 06:27 PM
-- Server version: 8.0.41-0ubuntu0.24.04.1
-- PHP Version: 8.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adoption`
--

-- --------------------------------------------------------

--
-- Table structure for table `adoption_requests`
--

CREATE TABLE `adoption_requests` (
  `id` int NOT NULL,
  `pet_id` int NOT NULL,
  `user_id` int NOT NULL,
  `shelter_id` int DEFAULT NULL,
  `adoption_date` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'submitted',
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `breeds`
--

CREATE TABLE `breeds` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `breeds`
--

INSERT INTO `breeds` (`id`, `name`, `description`, `category_id`, `custom_fields`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Persian', NULL, 4, NULL, '2025-05-03 17:34:04', '2025-05-03 17:34:04', NULL),
(3, 'Siamese', NULL, 4, NULL, '2025-05-03 17:34:37', '2025-05-03 17:34:37', NULL),
(4, 'Bengal', NULL, 4, NULL, '2025-05-03 17:34:47', '2025-05-03 17:34:47', NULL),
(5, 'Maine Coon', NULL, 4, NULL, '2025-05-03 17:34:56', '2025-05-03 17:34:56', NULL),
(6, 'British Shorthair', NULL, 4, NULL, '2025-05-03 17:35:04', '2025-05-03 17:35:04', NULL),
(7, 'Ragdoll', NULL, 4, NULL, '2025-05-03 17:35:19', '2025-05-03 17:35:19', NULL),
(8, 'Sphynx', NULL, 4, NULL, '2025-05-03 17:35:29', '2025-05-03 17:35:29', NULL),
(9, 'Scottish Fold', NULL, 4, NULL, '2025-05-03 17:35:37', '2025-05-03 17:35:37', NULL),
(10, 'Labrador Retriever', NULL, 5, NULL, '2025-05-03 17:35:58', '2025-05-03 17:35:58', NULL),
(11, 'German Shepherd', NULL, 5, NULL, '2025-05-03 17:36:09', '2025-05-03 17:36:09', NULL),
(12, 'Pomeranian', NULL, 5, NULL, '2025-05-03 17:36:18', '2025-05-03 17:36:18', NULL),
(13, 'Beagle', NULL, 4, NULL, '2025-05-03 17:36:26', '2025-05-03 17:36:26', NULL),
(14, 'Siberian Husky', NULL, 5, NULL, '2025-05-03 17:36:35', '2025-05-03 17:36:35', NULL),
(15, 'Dachshund', NULL, 5, NULL, '2025-05-03 17:36:43', '2025-05-03 17:36:43', NULL),
(16, 'Golden Retriever', NULL, 4, NULL, '2025-05-03 17:36:52', '2025-05-03 17:36:52', NULL),
(17, 'Shih Tzu', NULL, 5, NULL, '2025-05-03 17:37:02', '2025-05-03 17:37:02', NULL),
(18, 'Others', NULL, 4, NULL, '2025-05-03 17:37:31', '2025-05-03 17:37:31', NULL),
(19, 'Others', NULL, 5, NULL, '2025-05-03 17:37:43', '2025-05-03 17:37:43', NULL),
(20, 'Budgerigar (Parakeet)', NULL, 6, NULL, '2025-05-03 17:38:01', '2025-05-03 17:38:01', NULL),
(21, 'Cockatiel', NULL, 6, NULL, '2025-05-03 17:38:08', '2025-05-03 17:38:08', NULL),
(22, 'African Grey', NULL, 6, NULL, '2025-05-03 17:38:17', '2025-05-03 17:38:17', NULL),
(23, 'Lovebird', NULL, 6, NULL, '2025-05-03 17:38:25', '2025-05-03 17:38:25', NULL),
(24, 'Macaw', NULL, 6, NULL, '2025-05-03 17:38:34', '2025-05-03 17:38:34', NULL),
(25, 'Finch', NULL, 6, NULL, '2025-05-03 17:38:42', '2025-05-03 17:38:42', NULL),
(26, 'Canary', NULL, 6, NULL, '2025-05-03 17:38:51', '2025-05-03 17:38:51', NULL),
(27, 'Others', NULL, 6, NULL, '2025-05-03 17:39:00', '2025-05-03 17:39:00', NULL),
(28, 'Others', NULL, 7, NULL, '2025-05-03 17:39:07', '2025-05-03 17:39:07', NULL),
(29, 'Others', NULL, 8, NULL, '2025-05-03 17:39:16', '2025-05-03 17:39:16', NULL),
(30, 'Others', NULL, 9, NULL, '2025-05-03 17:39:26', '2025-05-03 17:39:26', NULL),
(31, 'Others', NULL, 10, NULL, '2025-05-03 17:39:32', '2025-05-03 17:39:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `custom_fields`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Cats', NULL, NULL, '2025-05-03 17:29:23', '2025-05-03 17:29:23', NULL),
(5, 'Dogs', NULL, NULL, '2025-05-03 17:29:37', '2025-05-03 17:29:37', NULL),
(6, 'Birds', NULL, NULL, '2025-05-03 17:29:44', '2025-05-03 17:29:44', NULL),
(7, 'Rabbits', NULL, NULL, '2025-05-03 17:29:51', '2025-05-03 17:29:51', NULL),
(8, 'Hamsters', NULL, NULL, '2025-05-03 17:30:09', '2025-05-03 17:30:09', NULL),
(9, 'Fish', NULL, NULL, '2025-05-03 17:30:15', '2025-05-03 17:30:15', NULL),
(10, 'Others', NULL, NULL, '2025-05-03 17:30:20', '2025-05-03 17:30:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int NOT NULL,
  `shelter_id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'info',
  `is_read` tinyint NOT NULL DEFAULT '0',
  `custom_fields` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shelter_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `breed_id` int DEFAULT NULL,
  `trait_ids` json DEFAULT NULL,
  `age` int DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `height` int DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `user_id` int DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `name`, `description`, `shelter_id`, `category_id`, `breed_id`, `trait_ids`, `age`, `gender`, `weight`, `height`, `location`, `status`, `user_id`, `custom_fields`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Cat', 'Cat', NULL, 4, 2, '[19, 17, 15]', 1, 'male', 3, NULL, 'Dhaka', NULL, 1, NULL, '2025-05-03 23:36:28', '2025-05-03 23:36:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `shelter_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL DEFAULT '0',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `notification_token` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `token`, `user_id`, `notification_token`, `ip`, `user_agent`, `created_at`, `updated_at`) VALUES
(2, '3afe08cee9645e97419033ae7e7121519e938e6f', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 16:27:32', '2025-05-03 16:27:32'),
(3, '3f011a02cb151bb3ed991acdeab36f1b695a5b16', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 16:28:58', '2025-05-03 16:28:58'),
(4, 'f3b58f1ca1ee0c56bae9cea119be1456867c2c44', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 16:31:43', '2025-05-03 16:31:43'),
(5, '0971122f7832b56810350699375a6a8717e8b573', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 16:33:17', '2025-05-03 16:35:35'),
(6, '193146bd632add1e14394eef92b0bbaf84d66112', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 16:35:35', '2025-05-03 16:49:02'),
(7, '8557ca93b9e8d1fedca26191c27ef6107821525a', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 16:49:02', '2025-05-03 17:19:22'),
(8, '9f88fee72ff9a88c32da02483242df51bdc4ac39', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 17:27:34', '2025-05-03 17:59:55'),
(9, '1538c001857dd842b6cee993df5134882762d7d0', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 18:00:14', '2025-05-03 18:04:04'),
(10, 'cc0e2a824e44d52b2dec77a58b12406b76a56623', 4, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 18:05:04', '2025-05-03 18:05:04'),
(11, 'b55d3b10e6e5e87402c867e1045353dce027eae1', 4, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 18:08:46', '2025-05-03 23:06:43'),
(12, '4628ca46d572f55605d98fee13b52a106b5b0bda', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-05-03 23:07:02', '2025-05-04 00:23:57');

-- --------------------------------------------------------

--
-- Table structure for table `shelters`
--

CREATE TABLE `shelters` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_id` int NOT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `traits`
--

CREATE TABLE `traits` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `traits`
--

INSERT INTO `traits` (`id`, `name`, `description`, `category_id`, `custom_fields`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Small', NULL, NULL, NULL, '2025-05-03 17:39:57', '2025-05-03 17:39:57', NULL),
(3, 'Medium', NULL, NULL, NULL, '2025-05-03 17:40:03', '2025-05-03 17:40:03', NULL),
(4, 'Large', NULL, NULL, NULL, '2025-05-03 17:40:08', '2025-05-03 17:40:08', NULL),
(5, 'Baby', NULL, NULL, NULL, '2025-05-03 17:40:12', '2025-05-03 17:40:12', NULL),
(6, 'Young', NULL, NULL, NULL, '2025-05-03 17:40:18', '2025-05-03 17:40:18', NULL),
(7, 'Adult', NULL, NULL, NULL, '2025-05-03 17:40:23', '2025-05-03 17:40:23', NULL),
(8, 'Senior', NULL, NULL, NULL, '2025-05-03 17:40:29', '2025-05-03 17:40:29', NULL),
(9, 'Friendly', NULL, NULL, NULL, '2025-05-03 17:40:37', '2025-05-03 17:40:37', NULL),
(10, 'Shy', NULL, NULL, NULL, '2025-05-03 17:40:44', '2025-05-03 17:40:44', NULL),
(11, 'Energetic', NULL, NULL, NULL, '2025-05-03 17:40:56', '2025-05-03 17:40:56', NULL),
(12, 'Calm', NULL, NULL, NULL, '2025-05-03 17:41:01', '2025-05-03 17:41:01', NULL),
(13, 'Protective', NULL, NULL, NULL, '2025-05-03 17:41:07', '2025-05-03 17:41:07', NULL),
(14, 'Playful', NULL, NULL, NULL, '2025-05-03 17:41:12', '2025-05-03 17:41:12', NULL),
(15, 'Independent', NULL, NULL, NULL, '2025-05-03 17:41:18', '2025-05-03 17:41:18', NULL),
(16, 'Vaccinated', NULL, NULL, NULL, '2025-05-03 17:41:25', '2025-05-03 17:41:25', NULL),
(17, 'Neutered/Spayed', NULL, NULL, NULL, '2025-05-03 17:41:37', '2025-05-03 17:41:37', NULL),
(18, 'House Trained', NULL, NULL, NULL, '2025-05-03 17:41:47', '2025-05-03 17:41:47', NULL),
(19, 'Special needs', NULL, NULL, NULL, '2025-05-03 17:41:54', '2025-05-03 17:41:54', NULL),
(20, 'Good with kids', NULL, NULL, NULL, '2025-05-03 17:42:02', '2025-05-03 17:42:02', NULL),
(21, 'Good with other pets', NULL, NULL, NULL, '2025-05-03 17:42:11', '2025-05-03 17:42:11', NULL),
(22, 'Not Active', NULL, NULL, NULL, '2025-05-03 17:42:25', '2025-05-03 17:42:25', NULL),
(23, 'Very Active', NULL, NULL, NULL, '2025-05-03 17:42:32', '2025-05-03 17:42:32', NULL),
(24, 'Moderately Active', NULL, NULL, NULL, '2025-05-03 17:42:44', '2025-05-03 17:42:44', NULL),
(25, 'Indoor', NULL, NULL, NULL, '2025-05-03 17:42:49', '2025-05-03 17:42:49', NULL),
(26, 'Outdoor', NULL, NULL, NULL, '2025-05-03 17:42:58', '2025-05-03 17:42:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_login` tinyint NOT NULL DEFAULT '0',
  `can_delete` tinyint NOT NULL DEFAULT '0',
  `can_manage` json DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `package` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'free',
  `shelter_id` int DEFAULT NULL,
  `custom_fields` json DEFAULT NULL,
  `failed_login_attempts` int NOT NULL DEFAULT '0',
  `last_login_attempt_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `can_login`, `can_delete`, `can_manage`, `role`, `package`, `shelter_id`, `custom_fields`, `failed_login_attempts`, `last_login_attempt_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Obaydur Rahman', 'hello@opu.rocks', '01304461899', '$2y$10$XS1n8cl9pDzd7oP1gcBrcuOLmPpKarR9rd.acKbdtNMZ7SJUzNpXG', 1, 1, NULL, 'superadmin', 'free', NULL, NULL, 0, NULL, '2025-03-20 15:29:09', '2025-05-03 23:07:02', NULL),
(4, 'Tanjim', 'tanjim@example.com', '0123456789', '$2y$12$QnPWb2d1CVO5EW9dzmczNurLOuMAhdQ6w19V1aZOW/t4IDMzeCzju', 1, 1, NULL, 'user', 'free', NULL, NULL, 0, NULL, '2025-05-03 18:04:41', '2025-05-03 18:09:47', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adoption_requests`
--
ALTER TABLE `adoption_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adoption_requests_ibfk_1` (`pet_id`),
  ADD KEY `adoption_requests_ibfk_2` (`user_id`),
  ADD KEY `adoption_requests_ibfk_3` (`shelter_id`);

--
-- Indexes for table `breeds`
--
ALTER TABLE `breeds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `breeds_ibfk_1` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donations_ibfk_1` (`shelter_id`),
  ADD KEY `donations_ibfk_2` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_ibfk_1` (`user_id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pets_ibfk_1` (`shelter_id`),
  ADD KEY `pets_ibfk_2` (`category_id`),
  ADD KEY `pets_ibfk_3` (`breed_id`),
  ADD KEY `pets_ibfk_4` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_ibfk_1` (`shelter_id`),
  ADD KEY `reviews_ibfk_2` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_ibfk_1` (`user_id`);

--
-- Indexes for table `shelters`
--
ALTER TABLE `shelters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `traits`
--
ALTER TABLE `traits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traits_ibfk_1` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_ibfk_3` (`shelter_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adoption_requests`
--
ALTER TABLE `adoption_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `breeds`
--
ALTER TABLE `breeds`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `shelters`
--
ALTER TABLE `shelters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `traits`
--
ALTER TABLE `traits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adoption_requests`
--
ALTER TABLE `adoption_requests`
  ADD CONSTRAINT `adoption_requests_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adoption_requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adoption_requests_ibfk_3` FOREIGN KEY (`shelter_id`) REFERENCES `shelters` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `breeds`
--
ALTER TABLE `breeds`
  ADD CONSTRAINT `breeds_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`shelter_id`) REFERENCES `shelters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`shelter_id`) REFERENCES `shelters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pets_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pets_ibfk_3` FOREIGN KEY (`breed_id`) REFERENCES `breeds` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pets_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`shelter_id`) REFERENCES `shelters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `traits`
--
ALTER TABLE `traits`
  ADD CONSTRAINT `traits_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`shelter_id`) REFERENCES `shelters` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
