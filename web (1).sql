-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 06:46 AM
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
-- Database: `web`
--

-- --------------------------------------------------------

--
-- Table structure for table `child_profiles`
--

CREATE TABLE `child_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `house_lot_no` varchar(255) NOT NULL,
  `purok` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(255) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `mothers_name` varchar(255) NOT NULL,
  `fathers_name` varchar(255) NOT NULL,
  `birth_weight` decimal(5,2) NOT NULL,
  `birth_height` decimal(5,2) NOT NULL,
  `created_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `child_profiles`
--

INSERT INTO `child_profiles` (`id`, `house_lot_no`, `purok`, `barangay`, `city`, `last_name`, `first_name`, `middle_name`, `birthdate`, `birthplace`, `sex`, `mothers_name`, `fathers_name`, `birth_weight`, `birth_height`, `created_by_user_id`, `created_at`, `updated_at`) VALUES
(1, 'q', '1', 'San Francisco', 'Panabo', 'Oldindo', 'Tikoy', 'Uy', '2025-05-21', 'Panabo', 'Male', 'Mama Oldindo', 'Papa Oldindo', 2.00, 164.00, 5, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(2, 'q', '1', 'San Francisco', 'Panabo', 's', 's', 's', '2023-05-21', 'Panabo', 'Female', 'Mama Oldindo', 'Papa Oldindo', 21.00, 164.00, 4, '2025-05-24 00:29:12', '2025-05-24 22:39:22');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `family_plannings`
--

CREATE TABLE `family_plannings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `house_lot_no` varchar(255) NOT NULL,
  `purok` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `fp_method` varchar(255) DEFAULT NULL,
  `intended_method` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date_served` date DEFAULT NULL,
  `provider_category` varchar(255) DEFAULT NULL,
  `provider_name` varchar(255) DEFAULT NULL,
  `mode_of_service_delivery` varchar(255) DEFAULT NULL,
  `date_counselled_pregnant` date DEFAULT NULL,
  `other_notes` text DEFAULT NULL,
  `date_encoded` date DEFAULT NULL,
  `intended_fp_method` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `family_plannings`
--

INSERT INTO `family_plannings` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `birthdate`, `sex`, `house_lot_no`, `purok`, `barangay`, `city`, `contact_number`, `fp_method`, `intended_method`, `remarks`, `created_by_user_id`, `created_at`, `updated_at`, `date_served`, `provider_category`, `provider_name`, `mode_of_service_delivery`, `date_counselled_pregnant`, `other_notes`, `date_encoded`, `intended_fp_method`) VALUES
(1, 4, 'Tikoy', 'Uy', 'Oldindo', '1999-05-20', 'Male', 'blk 3 lot 8', '1', 'San Francisco', 'Panabo', '09999999999', 'Injectable', 'Condom', 'TAHBSO', 4, '2025-05-24 00:36:17', '2025-05-24 00:36:43', '2025-05-20', 'BHS', 'asd', 'Regular facility-based', '2025-05-20', 's', '2025-05-24', NULL),
(2, 4, 'Tikoy', 'Uy', 's', '1999-06-09', 'Female', 'blk 3 lot 8', '6', 'San Francisco', 'Panabo', '09999999999', 'Injectable', 'Condom', 'Bil. oophorectomy', 4, '2025-05-24 00:58:22', '2025-05-24 10:08:04', '2025-05-24', 'BHS', 'asd', 'Regular facility-based', '2025-05-24', 's', '2025-05-24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `family_planning_edits`
--

CREATE TABLE `family_planning_edits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `family_planning_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `changes` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `family_planning_edits`
--

INSERT INTO `family_planning_edits` (`id`, `family_planning_id`, `user_id`, `changes`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '{\"birthdate\":{\"from\":\"1999-05-20T00:00:00.000000Z\",\"to\":\"1999-05-20\"},\"intended_method\":{\"from\":null,\"to\":\"Injectable\"},\"date_served\":{\"from\":\"2025-05-20T00:00:00.000000Z\",\"to\":\"2025-05-20\"},\"date_counselled_pregnant\":{\"from\":\"2025-05-20T00:00:00.000000Z\",\"to\":\"2025-05-20\"},\"other_notes\":{\"from\":null,\"to\":\"s\"},\"date_encoded\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"}}', '2025-05-24 00:36:43', '2025-05-24 00:36:43'),
(2, 1, 4, '{\"birthdate\":{\"from\":\"1999-05-20T00:00:00.000000Z\",\"to\":\"1999-05-20\"},\"intended_method\":{\"from\":null,\"to\":\"SDM\"},\"date_served\":{\"from\":\"2025-05-20T00:00:00.000000Z\",\"to\":\"2025-05-20\"},\"date_counselled_pregnant\":{\"from\":\"2025-05-20T00:00:00.000000Z\",\"to\":\"2025-05-20\"},\"date_encoded\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"}}', '2025-05-24 00:37:06', '2025-05-24 00:37:06'),
(3, 2, 4, '{\"birthdate\":{\"from\":\"1999-06-09T00:00:00.000000Z\",\"to\":\"1999-06-09\"},\"date_served\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"},\"date_counselled_pregnant\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"},\"date_encoded\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"}}', '2025-05-24 02:47:03', '2025-05-24 02:47:03'),
(4, 2, 4, '{\"purok\":{\"from\":\"1\",\"to\":\"6\"},\"birthdate\":{\"from\":\"1999-06-09T00:00:00.000000Z\",\"to\":\"1999-06-09\"},\"date_served\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"},\"date_counselled_pregnant\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"},\"date_encoded\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"}}', '2025-05-24 02:47:24', '2025-05-24 02:47:24'),
(5, 2, 4, '{\"birthdate\":{\"from\":\"1999-06-09T00:00:00.000000Z\",\"to\":\"1999-06-09\"},\"date_served\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"},\"fp_method\":{\"from\":\"CMM\\/Billings\",\"to\":\"Injectable\"},\"provider_category\":{\"from\":\"Pharmacy\",\"to\":\"BHS\"},\"date_counselled_pregnant\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"},\"date_encoded\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"}}', '2025-05-24 03:05:56', '2025-05-24 03:05:56'),
(6, 2, 4, '{\"birthdate\":{\"from\":\"1999-06-09T00:00:00.000000Z\",\"to\":\"1999-06-09\"},\"intended_method\":{\"from\":\"SDM\",\"to\":\"Condom\"},\"date_served\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"},\"date_counselled_pregnant\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"},\"date_encoded\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"}}', '2025-05-24 03:06:13', '2025-05-24 03:06:13'),
(7, 2, 4, '{\"birthdate\":{\"from\":\"1999-06-09T00:00:00.000000Z\",\"to\":\"1999-06-09\"},\"sex\":{\"from\":\"Male\",\"to\":\"Female\"},\"date_served\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"},\"date_counselled_pregnant\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"},\"date_encoded\":{\"from\":\"2025-05-24T00:00:00.000000Z\",\"to\":\"2025-05-24\"}}', '2025-05-24 10:08:04', '2025-05-24 10:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `immunization_records`
--

CREATE TABLE `immunization_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vaccination_date` date NOT NULL,
  `vaccine_name` varchar(255) NOT NULL,
  `dose_number` varchar(255) NOT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `administered_by` varchar(255) NOT NULL,
  `next_dose_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_05_20_014617_create_family_plannings_table', 1),
(6, '2023_05_20_014618_add_admin_field_to_users_table', 1),
(7, '2023_11_28_033000_create_immunization_records_table', 1),
(8, '2023_11_28_040000_update_immunization_records_structure', 1),
(9, '2024_03_19_000000_update_family_plannings_table', 1),
(10, '2025_05_20_014955_add_admin_and_tracking_fields', 1),
(11, '2025_05_20_055922_create_immunization_records_table', 1),
(12, '2025_05_20_095648_add_intended_fp_method_to_family_planning_table', 1),
(13, '2025_05_20_131430_add_login_count_to_users_table', 1),
(14, '2025_05_20_133911_add_login_count_to_users_table', 1),
(15, '2025_05_24_074001_add_sex_to_family_plannings_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_logout_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `sex`, `address`, `contact_number`, `birthdate`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `isAdmin`, `last_login_at`, `last_logout_at`) VALUES
(1, 'Admin', 'User', 'Male', '123 Main St, City', '123-456-7890', '1990-01-01', 'admin@example.com', NULL, '$2y$10$I.909bwWycuYCe25fOQqMOph.316tm2i68kUwrbmKHDK/cT002bl2', NULL, '2025-05-22 15:53:00', '2025-05-22 15:53:00', 0, NULL, NULL),
(2, 'John', 'Doe', 'Male', '456 Oak St, City', '987-654-3210', '1985-05-15', 'john@example.com', NULL, '$2y$10$v9zhoOdxH1iPXBNIXvJ6IOUz3eXd5/27JY9l9PnjPU2uN7FUs3BC6', NULL, '2025-05-22 15:53:00', '2025-05-22 15:53:00', 0, NULL, NULL),
(3, 'Jane', 'Smith', 'Female', '789 Pine St, City', '555-123-4567', '1992-08-20', 'jane@example.com', NULL, '$2y$10$bW94RG7.PrPgpBdgfQ1mYOMx3himCelRS5jVjdIek499uz9ADr0V.', NULL, '2025-05-22 15:53:00', '2025-05-22 15:53:00', 0, NULL, NULL),
(4, 'Admin', 'User', 'Male', 'Health Center', '09123456789', '1995-05-22', 'admin@bhw', NULL, '$2y$10$NZTU0rcs/n49HvVa1Rg09Oa8sKKRCfYw.jAq1MaK3QQMVDWixCMJe', NULL, '2025-05-22 15:53:00', '2025-05-25 02:35:30', 1, '2025-05-25 02:34:27', '2025-05-25 02:35:30'),
(5, 'Tikoy', 'Oldindo', 'Male', 'Baraccatan, Toril', '09999999999', '2025-05-23', 'tikoyoldindo@gmail.com', NULL, '$2y$10$UM85sC/Tk1Kw5TCGk37SOuVxQ2Fcns4CnUAz1X5C74YE93sQ3JjU.', NULL, '2025-05-22 16:00:31', '2025-05-25 02:54:49', 0, '2025-05-25 02:35:36', '2025-05-25 02:54:49');

-- --------------------------------------------------------

--
-- Table structure for table `vaccinations`
--

CREATE TABLE `vaccinations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `vaccine_type` enum('BCG','Hepatitis B','Pentavalent Vaccine','Oral Polio Vaccine','Inactivated Polio Vaccine','Pneumococcal Conjugate Vaccine','Measles,Mumps,&Rubella') NOT NULL,
  `dose_number` int(11) NOT NULL,
  `date_vaccinated` date DEFAULT NULL,
  `status` enum('Completed','Not Completed','Scheduled') NOT NULL DEFAULT 'Not Completed',
  `next_schedule` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `administered_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vaccinations`
--

INSERT INTO `vaccinations` (`id`, `child_id`, `vaccine_type`, `dose_number`, `date_vaccinated`, `status`, `next_schedule`, `remarks`, `administered_by_user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'BCG', 1, '2025-05-24', 'Completed', '2025-05-21', NULL, 4, '2025-05-24 00:10:29', '2025-05-24 03:40:45'),
(2, 1, 'Hepatitis B', 1, '2025-05-24', 'Completed', '2025-05-21', NULL, 4, '2025-05-24 00:10:29', '2025-05-24 03:40:47'),
(3, 1, 'Pentavalent Vaccine', 1, NULL, 'Not Completed', '2025-07-06', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(4, 1, 'Pentavalent Vaccine', 2, NULL, 'Not Completed', '2025-08-05', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(5, 1, 'Pentavalent Vaccine', 3, NULL, 'Not Completed', '2025-09-05', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(6, 1, 'Oral Polio Vaccine', 1, NULL, 'Not Completed', '2025-07-06', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(7, 1, 'Oral Polio Vaccine', 2, NULL, 'Not Completed', '2025-08-05', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(8, 1, 'Oral Polio Vaccine', 3, NULL, 'Not Completed', '2025-09-05', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(9, 1, 'Inactivated Polio Vaccine', 1, NULL, 'Not Completed', '2025-09-05', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(10, 1, 'Inactivated Polio Vaccine', 2, NULL, 'Not Completed', '2025-09-05', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(11, 1, 'Pneumococcal Conjugate Vaccine', 1, NULL, 'Not Completed', '2025-07-06', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(12, 1, 'Pneumococcal Conjugate Vaccine', 2, NULL, 'Not Completed', '2025-08-05', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(13, 1, 'Pneumococcal Conjugate Vaccine', 3, NULL, 'Not Completed', '2025-09-05', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(14, 1, 'Measles,Mumps,&Rubella', 1, NULL, 'Not Completed', '2026-02-21', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(15, 1, 'Measles,Mumps,&Rubella', 2, NULL, 'Not Completed', '2026-05-21', NULL, NULL, '2025-05-24 00:10:29', '2025-05-24 00:10:29'),
(16, 2, 'BCG', 1, '2025-05-24', 'Completed', '2025-05-21', NULL, 4, '2025-05-24 00:29:12', '2025-05-24 03:40:22'),
(17, 2, 'Hepatitis B', 1, NULL, 'Not Completed', '2025-05-21', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(18, 2, 'Pentavalent Vaccine', 1, NULL, 'Not Completed', '2025-07-06', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(19, 2, 'Pentavalent Vaccine', 2, NULL, 'Not Completed', '2025-08-05', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(20, 2, 'Pentavalent Vaccine', 3, NULL, 'Not Completed', '2025-09-05', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(21, 2, 'Oral Polio Vaccine', 1, NULL, 'Not Completed', '2025-07-06', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(22, 2, 'Oral Polio Vaccine', 2, NULL, 'Not Completed', '2025-08-05', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(23, 2, 'Oral Polio Vaccine', 3, NULL, 'Not Completed', '2025-09-05', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(24, 2, 'Inactivated Polio Vaccine', 1, NULL, 'Not Completed', '2025-09-05', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(25, 2, 'Inactivated Polio Vaccine', 2, NULL, 'Not Completed', '2025-09-05', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(26, 2, 'Pneumococcal Conjugate Vaccine', 1, NULL, 'Not Completed', '2025-07-06', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(27, 2, 'Pneumococcal Conjugate Vaccine', 2, NULL, 'Not Completed', '2025-08-05', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(28, 2, 'Pneumococcal Conjugate Vaccine', 3, NULL, 'Not Completed', '2025-09-05', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(29, 2, 'Measles,Mumps,&Rubella', 1, NULL, 'Not Completed', '2026-02-21', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12'),
(30, 2, 'Measles,Mumps,&Rubella', 2, NULL, 'Not Completed', '2026-05-21', NULL, NULL, '2025-05-24 00:29:12', '2025-05-24 00:29:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `child_profiles`
--
ALTER TABLE `child_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `child_profiles_created_by_user_id_foreign` (`created_by_user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `family_plannings`
--
ALTER TABLE `family_plannings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `family_plannings_user_id_foreign` (`user_id`),
  ADD KEY `family_plannings_created_by_user_id_foreign` (`created_by_user_id`);

--
-- Indexes for table `family_planning_edits`
--
ALTER TABLE `family_planning_edits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `family_planning_edits_family_planning_id_foreign` (`family_planning_id`),
  ADD KEY `family_planning_edits_user_id_foreign` (`user_id`);

--
-- Indexes for table `immunization_records`
--
ALTER TABLE `immunization_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `immunization_records_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vaccinations`
--
ALTER TABLE `vaccinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vaccinations_child_id_foreign` (`child_id`),
  ADD KEY `vaccinations_administered_by_user_id_foreign` (`administered_by_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `child_profiles`
--
ALTER TABLE `child_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `family_plannings`
--
ALTER TABLE `family_plannings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `family_planning_edits`
--
ALTER TABLE `family_planning_edits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `immunization_records`
--
ALTER TABLE `immunization_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vaccinations`
--
ALTER TABLE `vaccinations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `child_profiles`
--
ALTER TABLE `child_profiles`
  ADD CONSTRAINT `child_profiles_created_by_user_id_foreign` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `family_plannings`
--
ALTER TABLE `family_plannings`
  ADD CONSTRAINT `family_plannings_created_by_user_id_foreign` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `family_plannings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `family_planning_edits`
--
ALTER TABLE `family_planning_edits`
  ADD CONSTRAINT `family_planning_edits_family_planning_id_foreign` FOREIGN KEY (`family_planning_id`) REFERENCES `family_plannings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `family_planning_edits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `immunization_records`
--
ALTER TABLE `immunization_records`
  ADD CONSTRAINT `immunization_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vaccinations`
--
ALTER TABLE `vaccinations`
  ADD CONSTRAINT `vaccinations_administered_by_user_id_foreign` FOREIGN KEY (`administered_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vaccinations_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `child_profiles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
