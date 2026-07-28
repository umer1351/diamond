-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 31, 2024 at 04:26 AM
-- Server version: 8.0.39-cll-lve
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `softzamstudios_jeweler`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int NOT NULL DEFAULT '0',
  `account_type_id` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `opening_balance` decimal(8,2) NOT NULL DEFAULT '0.00',
  `opening_cr` decimal(8,2) NOT NULL DEFAULT '0.00',
  `opening_dr` decimal(8,2) NOT NULL DEFAULT '0.00',
  `is_cash_account` int NOT NULL DEFAULT '0',
  `level` int DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `code`, `name`, `parent_id`, `account_type_id`, `is_active`, `opening_balance`, `opening_cr`, `opening_dr`, `is_cash_account`, `level`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, '002-001', 'Main Capital', 0, 11, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, 1, 1, '2024-12-18 17:00:09', '2024-12-18 18:30:16'),
(2, '002-002', 'Retained Earnings', 0, 11, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, 1, 1, '2024-12-18 17:00:56', '2024-12-18 18:30:19'),
(3, '001-001-001', 'Furniture Fixtures and Fittings', 0, 1, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, 1, 1, '2024-12-18 17:01:38', '2024-12-18 18:29:58'),
(4, '001-001-002', 'Electric Appliances', 0, 1, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, 1, 1, '2024-12-18 17:02:29', '2024-12-18 18:30:02'),
(5, '001-001-003', 'Tools and Equipments', 0, 1, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, 1, 1, '2024-12-18 17:03:18', '2024-12-18 18:30:05'),
(6, '001-003-001', 'Customers', 0, 5, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, 1, 1, '2024-12-18 17:24:18', '2024-12-18 18:30:08'),
(7, '001-003-002', 'Other Recieveables', 0, 2, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, 1, 1, '2024-12-18 17:29:10', '2024-12-18 18:30:10'),
(8, '001-002-003', 'Gold Stocks', 0, 2, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, NULL, 1, '2024-12-18 17:33:05', '2024-12-18 18:30:07'),
(9, '003-001-001', 'Karigar/Supplier', 0, 12, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, 1, 1, '2024-12-18 17:34:45', '2024-12-18 18:30:25'),
(10, '003-001-002', 'Karigar/Supplier AU', 0, 12, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, NULL, 1, '2024-12-18 17:35:17', '2024-12-18 18:21:30'),
(11, '003-001-003', 'Karigar/Supplier $', 0, 12, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, NULL, 1, '2024-12-18 17:35:45', '2024-12-18 18:21:34'),
(12, '001-004-001', 'Cash in Hand', 0, 6, 1, 0.00, 0.00, 0.00, 0, 1, 1, 1, NULL, 1, '2024-12-18 18:04:30', '2024-12-18 18:30:12'),
(13, '0001', 'Liberty 1', 12, 6, 1, 0.00, 0.00, 0.00, 1, 2, 0, 1, 1, NULL, '2024-12-18 18:06:20', '2024-12-18 18:08:33'),
(14, '0002', 'Liberty 2', 12, 6, 1, 0.00, 0.00, 0.00, 1, 2, 0, 1, 1, NULL, '2024-12-18 18:07:04', '2024-12-18 18:08:52'),
(15, '001', 'Investment', 1, 11, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:11:47', '2024-12-18 18:11:47'),
(16, '003-001-001-001', 'Karigar and Supplier PK', 9, 12, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:22:23', '2024-12-18 18:22:23'),
(17, '003-001-001-002', 'Karigar/Supplier AU', 9, 12, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:22:50', '2024-12-18 18:22:50'),
(18, '001-001', 'Non Current Assets', 0, 1, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 18:31:44', '2024-12-18 18:31:44'),
(19, '001-001-001', 'Furniture Fixtures and Fittings', 18, 1, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:32:11', '2024-12-18 18:32:11'),
(20, '001-001-002', 'Electrical Appliances', 18, 1, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:32:40', '2024-12-18 18:32:40'),
(21, '001-001-003', 'Tools and Equipments', 18, 1, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:33:10', '2024-12-18 18:33:10'),
(22, '001-002', 'Current Assets', 0, 2, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 18:33:57', '2024-12-18 18:33:57'),
(23, '001-002-001', 'Display and Others', 22, 2, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:34:58', '2024-12-18 18:34:58'),
(24, '001-003', 'Recievables', 0, 5, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 18:35:54', '2024-12-18 18:35:54'),
(25, '001-003-001', 'Customers', 24, 5, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, 1, NULL, '2024-12-18 18:36:23', '2024-12-18 18:37:07'),
(26, '001-003-002', 'Other Recievables', 24, 5, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:36:56', '2024-12-18 18:36:56'),
(27, '001-004', 'Stocks-In-Trade', 0, 2, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 18:37:59', '2024-12-18 18:37:59'),
(28, '001-005', 'Cash and Cash Equivalents', 0, 6, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 18:38:21', '2024-12-18 18:38:21'),
(29, '001-005-001', 'Liberty 1', 28, 6, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:38:38', '2024-12-18 18:38:38'),
(30, '001-005-002', 'Liberty 2', 28, 6, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:39:00', '2024-12-18 18:39:00'),
(31, '001-005-003', 'Liberty 3', 28, 6, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:39:19', '2024-12-18 18:39:19'),
(32, '001-005-004', 'Meezan Bank - Al-Saeed Jewellers', 28, 6, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, 1, NULL, '2024-12-18 18:39:50', '2024-12-18 18:40:06'),
(33, '001-005-005', 'Bank Al Habib - Liberty - Al-Saeed Jewellers', 28, 6, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:40:42', '2024-12-18 18:40:42'),
(34, '001-005-006', 'Ichra 1', 28, 6, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 18:41:13', '2024-12-18 18:41:13'),
(35, '002-001', 'Capital', 0, 11, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 20:49:07', '2024-12-18 20:49:07'),
(36, '002-001-001', 'Main Capital', 35, 11, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 20:49:24', '2024-12-18 20:49:24'),
(37, '002-001-002', 'Retained Earnings', 35, 11, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 20:49:51', '2024-12-18 20:49:51'),
(38, '003-001', 'Non-Current Liabilities', 0, 3, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 20:50:27', '2024-12-18 20:50:27'),
(39, '003-002', 'Current Liabilities', 0, 12, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 20:51:01', '2024-12-18 20:51:01'),
(40, '003-002-001', 'Supplier/Karigar Gold', 39, 12, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 20:51:29', '2024-12-18 20:51:29'),
(41, '003-002-002', 'Supplier/Karigar PKR', 39, 12, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 20:51:50', '2024-12-18 20:51:50'),
(42, '003-002-003', 'Supplier/Karigar $', 39, 12, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 20:52:12', '2024-12-18 20:52:12'),
(43, '003-002-004', 'Other Payables', 39, 12, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 20:52:56', '2024-12-18 20:52:56'),
(44, '004-001', 'Sales', 0, 7, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 20:53:24', '2024-12-18 20:53:24'),
(45, '004-001-001', 'Gold Jewellery Sales', 44, 7, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 20:53:54', '2024-12-18 20:53:54'),
(46, '004-001-002', 'Diamonds Jewellery Sales', 44, 7, 1, 0.00, 0.00, 0.00, 0, 2, 1, 1, NULL, 1, '2024-12-18 20:54:18', '2024-12-19 17:04:25'),
(47, '004-001-003', 'Palladium Sales', 44, 7, 1, 0.00, 0.00, 0.00, 0, 2, 1, 1, NULL, 1, '2024-12-18 20:54:45', '2024-12-19 17:04:41'),
(48, '004-001-004', 'White Gold Sales', 44, 7, 1, 0.00, 0.00, 0.00, 0, 2, 1, 1, NULL, 1, '2024-12-18 20:55:07', '2024-12-19 17:04:35'),
(49, '004-001-005', 'Platinum Sales', 44, 7, 1, 0.00, 0.00, 0.00, 0, 2, 1, 1, NULL, 1, '2024-12-18 20:56:33', '2024-12-19 17:04:18'),
(50, '004-001-006', 'Impure Encashed', 44, 7, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 20:57:10', '2024-12-18 20:57:10'),
(51, '005-001', 'Cost Of Sales', 0, 9, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 20:57:56', '2024-12-18 20:57:56'),
(52, '006-001', 'Admin Expenses', 0, 10, 1, 0.00, 0.00, 0.00, 0, 1, 0, 1, NULL, NULL, '2024-12-18 21:00:54', '2024-12-18 21:00:54'),
(53, '006-001-001', 'Rent Expense', 52, 10, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:01:32', '2024-12-18 21:01:32'),
(54, '006-001-002', 'Salaries and Wages', 52, 10, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:01:49', '2024-12-18 21:01:49'),
(55, '006-001-003', 'Electiricity', 52, 10, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:02:09', '2024-12-18 21:02:09'),
(56, '006-001-004', 'Communication', 52, 10, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:02:40', '2024-12-18 21:02:40'),
(57, '006-001-005', 'Entertainment of Staff', 52, 10, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:15:26', '2024-12-18 21:15:26'),
(58, '006-001-006', 'Entertainment of Customers', 52, 10, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:17:08', '2024-12-18 21:17:08'),
(59, '006-001-007', 'Repair and Maintenance', 52, 10, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:17:36', '2024-12-18 21:17:36'),
(60, '006-001-008', 'Travelling and Conveyance', 52, 10, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:17:52', '2024-12-18 21:17:52'),
(61, '001-004-001', 'Gold Jewellery', 27, 2, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:26:09', '2024-12-18 21:26:09'),
(62, '001-004-002', 'Diamond Jewellery', 27, 2, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:26:36', '2024-12-18 21:26:36'),
(63, '001-004-003', 'Palladium Jewellery', 27, 2, 1, 0.00, 0.00, 0.00, 0, 2, 0, 1, NULL, NULL, '2024-12-18 21:26:57', '2024-12-18 21:26:57');

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`id`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Non Current Assets', NULL, NULL),
(2, 'Current Assets', NULL, NULL),
(3, 'Non Current Liabilities', NULL, NULL),
(4, 'Current Liabilities', NULL, NULL),
(5, 'Receivable', NULL, NULL),
(6, 'Bank and Cash', NULL, NULL),
(7, 'Revenue/Incom', NULL, NULL),
(8, 'Expense', NULL, NULL),
(9, 'Direct Expense', NULL, NULL),
(10, 'Indirect Expense', NULL, NULL),
(11, 'Equity', NULL, NULL),
(12, 'Payable', NULL, NULL),
(13, 'Prepaments', NULL, NULL),
(14, 'Other Income', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bead_types`
--

CREATE TABLE `bead_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bead_types`
--

INSERT INTO `bead_types` (`id`, `name`, `is_active`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'Natural', 1, 0, 1, NULL, NULL, '2024-12-23 18:45:35', '2024-12-23 18:45:35'),
(2, 'Synthetic', 1, 0, 1, NULL, NULL, '2024-12-23 18:45:56', '2024-12-23 18:45:56'),
(3, 'Semi Precious', 1, 0, 1, NULL, NULL, '2024-12-23 18:46:04', '2024-12-23 18:46:04'),
(4, 'Artificial', 1, 0, 1, NULL, NULL, '2024-12-23 18:46:27', '2024-12-23 18:46:27'),
(5, 'Chinese', 1, 0, 1, NULL, NULL, '2024-12-23 18:52:29', '2024-12-23 18:52:29');

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` int DEFAULT NULL,
  `purchase_account_id` int DEFAULT NULL,
  `sale_account_id` int DEFAULT NULL,
  `cash_account_id` int DEFAULT NULL,
  `revenue_account_id` int DEFAULT NULL,
  `bank_account_id` int DEFAULT NULL,
  `card_account_id` int DEFAULT NULL,
  `advance_account_id` int DEFAULT NULL,
  `gold_impurity_account_id` int DEFAULT NULL,
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `company_id`, `purchase_account_id`, `sale_account_id`, `cash_account_id`, `revenue_account_id`, `bank_account_id`, `card_account_id`, `advance_account_id`, `gold_impurity_account_id`, `createdby_id`, `updatedby_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 61, 45, NULL, NULL, NULL, 61, NULL, NULL, 1, 1, '2024-12-20 22:37:57', '2024-12-23 19:19:52');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `anniversary_date` date DEFAULT NULL,
  `ring_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bangle_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnic_images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `account_id` int DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `contact`, `email`, `cnic`, `address`, `date_of_birth`, `anniversary_date`, `ring_size`, `bangle_size`, `reference`, `comment`, `bank_name`, `account_title`, `account_no`, `cnic_images`, `is_active`, `account_id`, `is_deleted`, `createdby_id`, `updatedby_id`, `created_at`, `updated_at`, `deletedby_id`) VALUES
(1, 'Usman Afzal', '03214117616', NULL, NULL, 'Jail Road Farooq Motors', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 25, 0, 1, NULL, '2024-12-19 15:30:19', '2024-12-19 15:30:19', NULL),
(2, 'Ali Mahmood', '03064555600', NULL, NULL, 'W/4D DHA Lahore', NULL, '2024-12-21', NULL, NULL, NULL, NULL, 'HBL', NULL, NULL, NULL, 1, 25, 0, 1, NULL, '2024-12-23 15:05:56', '2024-12-23 15:05:56', NULL),
(3, 'Mrs Raheela Tahir', '03334471904', NULL, NULL, 'Johar Town', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 25, 0, 1, NULL, '2024-12-26 15:09:18', '2024-12-26 15:09:18', NULL),
(4, 'Mrs Masood', '03480006666', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 1, NULL, '2024-12-26 15:10:43', '2024-12-26 15:10:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `diamond_clarities`
--

CREATE TABLE `diamond_clarities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diamond_colors`
--

CREATE TABLE `diamond_colors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diamond_cuts`
--

CREATE TABLE `diamond_cuts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diamond_types`
--

CREATE TABLE `diamond_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dollar_rates`
--

CREATE TABLE `dollar_rates` (
  `id` bigint UNSIGNED NOT NULL,
  `rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `createdby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dollar_rates`
--

INSERT INTO `dollar_rates` (`id`, `rate`, `createdby_id`, `created_at`, `updated_at`) VALUES
(1, 277.320, 1, '2024-09-27 08:19:04', '2024-09-27 08:19:04'),
(2, 280.000, 1, '2024-09-27 18:19:30', '2024-09-27 18:19:30'),
(3, 277.750, 1, '2024-10-01 15:02:45', '2024-10-01 15:02:45'),
(4, 277.750, 1, '2024-10-01 15:02:46', '2024-10-01 15:02:46'),
(5, 277.600, 1, '2024-10-11 17:56:15', '2024-10-11 17:56:15'),
(6, 277.000, 1, '2024-10-26 19:11:28', '2024-10-26 19:11:28');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `emergency_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_relation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Full-time, Part-time, Contract',
  `date_of_joining` date DEFAULT NULL,
  `shift` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'shift time in string',
  `salary` decimal(8,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_overtime` tinyint(1) NOT NULL DEFAULT '0',
  `sick_leave` decimal(8,2) NOT NULL DEFAULT '0.00',
  `casual_leave` decimal(8,2) NOT NULL DEFAULT '0.00',
  `annual_leave` decimal(8,2) NOT NULL DEFAULT '0.00',
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 for active, 0 for inactive',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finish_products`
--

CREATE TABLE `finish_products` (
  `id` bigint UNSIGNED NOT NULL,
  `ratti_kaat_id` int DEFAULT NULL,
  `job_purchase_id` int DEFAULT NULL,
  `ratti_kaat_detail_id` int DEFAULT NULL,
  `job_purchase_detail_id` int DEFAULT NULL,
  `tag_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gold_carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `scale_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `bead_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `stones_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `diamond_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `net_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `waste_per` decimal(18,3) NOT NULL DEFAULT '0.000',
  `waste` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gross_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `making_gram` decimal(18,3) NOT NULL DEFAULT '0.000',
  `making` decimal(18,3) NOT NULL DEFAULT '0.000',
  `laker` decimal(18,3) NOT NULL DEFAULT '0.000',
  `bead_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `stones_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `diamond_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_bead_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_stones_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_diamond_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `other_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gold_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_gold_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_saled` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `finish_products`
--

INSERT INTO `finish_products` (`id`, `ratti_kaat_id`, `job_purchase_id`, `ratti_kaat_detail_id`, `job_purchase_detail_id`, `tag_no`, `barcode`, `product_id`, `warehouse_id`, `picture`, `gold_carat`, `scale_weight`, `bead_weight`, `stones_weight`, `diamond_weight`, `net_weight`, `waste_per`, `waste`, `gross_weight`, `making_gram`, `making`, `laker`, `bead_price`, `stones_price`, `diamond_price`, `total_bead_price`, `total_stones_price`, `total_diamond_price`, `other_amount`, `gold_rate`, `total_gold_price`, `total_amount`, `is_saled`, `is_active`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 3, NULL, 'LR49229', 'barcodes/LR49229.png', 6, 1, 'pictures/17345979246763dd2486e88.png', 21.000, 2.930, 0.000, 0.000, 0.000, 2.930, 34.130, 1.000, 3.930, 250.000, 982.500, 250.000, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 21004.801, 82548.868, 83781.368, 0, 1, 0, 1, NULL, NULL, '2024-12-19 15:45:24', '2024-12-19 15:45:24'),
(2, 11, NULL, 21, NULL, 'NS88565', 'barcodes/NS88565.png', 15, 1, 'pictures/173495744567695985af4b8.jpeg', 21.000, 52.533, 47.623, 0.000, 0.000, 4.910, 20.367, 1.000, 5.910, 750.000, 3719.250, 1500.000, 0.000, 0.000, 0.000, 10000.116, 0.000, 0.000, 1000.000, 21004.801, 124138.374, 140357.740, 0, 1, 0, 1, NULL, NULL, '2024-12-23 19:37:25', '2024-12-23 19:37:25'),
(3, 12, NULL, 22, NULL, 'NS82380', 'barcodes/NS82380.png', 15, 1, 'pictures/17349599196769632f8fe26.jpeg', 21.000, 56.273, 50.273, 0.000, 0.000, 6.000, 20.000, 1.208, 7.248, 750.000, 5436.000, 1500.000, 0.000, 0.000, 0.000, 12568.250, 0.000, 0.000, 1000.000, 21004.801, 152242.798, 172747.048, 0, 1, 0, 1, NULL, NULL, '2024-12-23 20:18:39', '2024-12-23 20:18:39'),
(4, 13, NULL, 23, NULL, 'NS10770', 'barcodes/NS10770.png', 15, 1, 'pictures/173496075567696673ae70c.jpeg', 21.000, 42.628, 36.568, 0.000, 0.000, 6.060, 20.462, 1.240, 7.300, 750.000, 5475.000, 1500.000, 0.000, 0.000, 0.000, 9142.000, 0.000, 0.000, 1000.000, 21004.801, 153335.047, 170452.047, 0, 1, 0, 1, NULL, NULL, '2024-12-23 20:32:35', '2024-12-23 20:32:35'),
(5, 19, NULL, 25, NULL, 'GR30381', 'barcodes/GR30381.png', 8, 1, 'pictures/1735213868676d432c05407.png', 22.000, 5.070, 0.000, 0.000, 0.000, 5.070, 19.724, 1.000, 6.070, 1000.000, 6070.000, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 22005.030, 133570.532, 139640.532, 1, 1, 0, 1, 1, NULL, '2024-12-26 18:51:08', '2024-12-26 18:55:10');

-- --------------------------------------------------------

--
-- Table structure for table `finish_product_beads`
--

CREATE TABLE `finish_product_beads` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finish_product_id` int DEFAULT NULL,
  `beads` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `finish_product_beads`
--

INSERT INTO `finish_product_beads` (`id`, `type`, `finish_product_id`, `beads`, `gram`, `carat`, `gram_rate`, `carat_rate`, `total_amount`, `is_deleted`, `createdby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'Chinese', 2, 1.000, 47.623, 238.115, 209.985, 41.997, 10000.116, 0, NULL, NULL, '2024-12-23 19:37:25', '2024-12-23 19:37:25'),
(2, 'Artificial', 3, 1.000, 50.273, 251.365, 250.000, 50.000, 12568.250, 0, NULL, NULL, '2024-12-23 20:18:39', '2024-12-23 20:18:39'),
(3, 'Chinese', 4, 1.000, 36.568, 182.840, 250.000, 50.000, 9142.000, 0, NULL, NULL, '2024-12-23 20:32:35', '2024-12-23 20:32:35');

-- --------------------------------------------------------

--
-- Table structure for table `finish_product_diamonds`
--

CREATE TABLE `finish_product_diamonds` (
  `id` bigint UNSIGNED NOT NULL,
  `finish_product_id` int DEFAULT NULL,
  `diamonds` decimal(18,3) NOT NULL DEFAULT '0.000',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clarity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_dollar` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finish_product_stones`
--

CREATE TABLE `finish_product_stones` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finish_product_id` int DEFAULT NULL,
  `stones` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gold_impurity_purchases`
--

CREATE TABLE `gold_impurity_purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `gold_impurity_purchase_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `total_qty` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total` decimal(18,2) NOT NULL DEFAULT '0.00',
  `cash_payment` decimal(18,2) NOT NULL DEFAULT '0.00',
  `bank_payment` decimal(18,2) NOT NULL DEFAULT '0.00',
  `total_payment` decimal(18,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(18,2) NOT NULL DEFAULT '0.00',
  `jv_id` int DEFAULT NULL,
  `cash_jv_id` int DEFAULT NULL,
  `bank_jv_id` int DEFAULT NULL,
  `is_posted` tinyint(1) NOT NULL DEFAULT '0',
  `is_mix_stock` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gold_impurity_purchases`
--

INSERT INTO `gold_impurity_purchases` (`id`, `gold_impurity_purchase_no`, `customer_id`, `total_qty`, `total_weight`, `total`, `cash_payment`, `bank_payment`, `total_payment`, `balance`, `jv_id`, `cash_jv_id`, `bank_jv_id`, `is_posted`, `is_mix_stock`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'GIP-23122024-0001', NULL, 0.000, 0.000, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, 0, 0, 1, NULL, NULL, '2024-12-23 15:25:43', '2024-12-23 15:25:43');

-- --------------------------------------------------------

--
-- Table structure for table `gold_impurity_purchase_details`
--

CREATE TABLE `gold_impurity_purchase_details` (
  `id` bigint UNSIGNED NOT NULL,
  `gold_impurity_purchase_id` int DEFAULT NULL,
  `scale_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `bead_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `stone_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `net_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `point` decimal(18,3) NOT NULL DEFAULT '0.000',
  `pure_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gold_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gold_rates`
--

CREATE TABLE `gold_rates` (
  `id` bigint UNSIGNED NOT NULL,
  `carat` int NOT NULL DEFAULT '24',
  `gold` decimal(18,3) NOT NULL DEFAULT '100.000',
  `impurity` decimal(18,3) NOT NULL DEFAULT '0.000',
  `ratti` int NOT NULL DEFAULT '96',
  `ratti_impurity` int NOT NULL DEFAULT '0',
  `rate_tola` decimal(18,3) NOT NULL DEFAULT '0.000',
  `rate_gram` decimal(18,3) NOT NULL DEFAULT '0.000',
  `createdby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gold_rates`
--

INSERT INTO `gold_rates` (`id`, `carat`, `gold`, `impurity`, `ratti`, `ratti_impurity`, `rate_tola`, `rate_gram`, `createdby_id`, `created_at`, `updated_at`) VALUES
(1, 24, 100.000, 0.000, 96, 0, 263700.000, 22608.025, 1, '2024-09-27 08:18:40', '2024-09-27 08:18:40'),
(2, 24, 100.000, 0.000, 96, 0, 285000.000, 24434.156, 1, '2024-09-27 17:40:33', '2024-09-27 17:40:33'),
(3, 24, 100.000, 0.000, 96, 0, 280000.000, 24005.487, 1, '2024-10-01 15:02:02', '2024-10-01 15:02:02'),
(4, 24, 100.000, 0.000, 96, 0, 281100.000, 24099.794, 1, '2024-10-04 18:39:57', '2024-10-04 18:39:57'),
(5, 24, 100.000, 0.000, 96, 0, 281000.000, 24091.221, 1, '2024-10-05 17:57:30', '2024-10-05 17:57:30'),
(6, 24, 100.000, 0.000, 96, 0, 278000.000, 23834.019, 1, '2024-10-11 17:56:05', '2024-10-11 17:56:05'),
(7, 24, 100.000, 0.000, 96, 0, 280800.000, 24074.074, 1, '2024-10-17 01:01:10', '2024-10-17 01:01:10'),
(8, 24, 100.000, 0.000, 96, 0, 286300.000, 24545.610, 1, '2024-10-26 19:03:45', '2024-10-26 19:03:45'),
(9, 24, 100.000, 0.000, 96, 0, 270300.000, 23173.868, 1, '2024-11-14 20:26:59', '2024-11-14 20:26:59'),
(10, 24, 100.000, 0.000, 96, 0, 270300.000, 23173.868, 1, '2024-11-14 20:26:59', '2024-11-14 20:26:59'),
(11, 24, 100.000, 0.000, 96, 0, 280000.000, 24005.487, 1, '2024-11-19 20:25:17', '2024-11-19 20:25:17'),
(12, 24, 100.000, 0.000, 96, 0, 282300.000, 24202.675, 1, '2024-12-09 18:39:06', '2024-12-09 18:39:06'),
(13, 24, 100.000, 0.000, 96, 0, 280000.000, 24005.487, 1, '2024-12-19 15:29:01', '2024-12-19 15:29:01');

-- --------------------------------------------------------

--
-- Table structure for table `gold_rate_types`
--

CREATE TABLE `gold_rate_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gold_rate_types`
--

INSERT INTO `gold_rate_types` (`id`, `name`, `is_active`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'Gold Rate Fixed', 1, 0, NULL, NULL, NULL, NULL, NULL),
(2, 'Gold Rate Open', 1, 0, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_purchases`
--

CREATE TABLE `job_purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `job_task_id` int DEFAULT NULL,
  `job_purchase_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_purchase_date` date DEFAULT NULL,
  `purchase_order_id` int DEFAULT NULL,
  `sale_order_id` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `purchase_account_id` int DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_recieved_au` decimal(18,3) DEFAULT NULL,
  `total` decimal(18,3) DEFAULT NULL,
  `total_au` decimal(18,3) DEFAULT NULL,
  `total_dollar` decimal(18,3) DEFAULT NULL,
  `jv_id` int DEFAULT NULL,
  `jv_au_id` int DEFAULT NULL,
  `jv_dollar_id` int DEFAULT NULL,
  `jv_recieved_id` int DEFAULT NULL,
  `supplier_au_payment_id` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 for active, 0 for inactive',
  `is_posted` tinyint(1) NOT NULL DEFAULT '0',
  `is_saled` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_purchase_details`
--

CREATE TABLE `job_purchase_details` (
  `id` bigint UNSIGNED NOT NULL,
  `job_purchase_id` int DEFAULT NULL,
  `purchase_order_detail_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `design_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waste_ratti` decimal(18,3) NOT NULL DEFAULT '0.000',
  `waste` decimal(18,3) NOT NULL DEFAULT '0.000',
  `polish_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `stone_waste` decimal(18,3) NOT NULL DEFAULT '0.000' COMMENT '0.25/100 stones',
  `mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Upper, Inner',
  `mail_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `stone_waste_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `recieved_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_recieved_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `bead_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `stones_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `diamond_carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `with_stone_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `pure_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `payable_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `stone_adjustement` decimal(18,3) NOT NULL DEFAULT '0.000',
  `final_pure_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `pure_payable` decimal(18,3) NOT NULL DEFAULT '0.000',
  `laker` decimal(18,3) NOT NULL DEFAULT '0.000',
  `rp` decimal(18,3) NOT NULL DEFAULT '0.000',
  `wax` decimal(18,3) NOT NULL DEFAULT '0.000',
  `other` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_bead_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_stones_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_diamond_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_dollar` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_finish_product` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `approvedby_id` int DEFAULT NULL,
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_purchase_detail_beads`
--

CREATE TABLE `job_purchase_detail_beads` (
  `id` bigint UNSIGNED NOT NULL,
  `job_purchase_detail_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `beads` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_purchase_detail_diamonds`
--

CREATE TABLE `job_purchase_detail_diamonds` (
  `id` bigint UNSIGNED NOT NULL,
  `job_purchase_detail_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `diamonds` decimal(18,3) NOT NULL DEFAULT '0.000',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clarity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_dollar` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_purchase_detail_stones`
--

CREATE TABLE `job_purchase_detail_stones` (
  `id` bigint UNSIGNED NOT NULL,
  `job_purchase_detail_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stones` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_tasks`
--

CREATE TABLE `job_tasks` (
  `id` bigint UNSIGNED NOT NULL,
  `job_task_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_task_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `purchase_order_id` int DEFAULT NULL,
  `sale_order_id` int DEFAULT NULL,
  `total_qty` decimal(18,3) DEFAULT NULL,
  `is_complete` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_tasks`
--

INSERT INTO `job_tasks` (`id`, `job_task_no`, `job_task_date`, `delivery_date`, `supplier_id`, `warehouse_id`, `purchase_order_id`, `sale_order_id`, `total_qty`, `is_complete`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'JT-23122024-0001', '2024-12-23 08:15:17', '2024-12-21T13:11', 8, 1, 2, 2, 1.000, 0, 0, 1, NULL, NULL, '2024-12-23 15:15:17', '2024-12-23 15:15:17');

-- --------------------------------------------------------

--
-- Table structure for table `job_task_activities`
--

CREATE TABLE `job_task_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `job_task_id` int DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `design_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_task_activities`
--

INSERT INTO `job_task_activities` (`id`, `job_task_id`, `category`, `design_no`, `weight`, `picture`, `description`, `is_deleted`, `createdby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Chokar Set', 'NS', 44.440, 'undefined', 'After polish Weight', 0, 3, NULL, '2024-12-24 16:08:16', '2024-12-24 16:08:16'),
(2, 1, 'Chokar Set Small Stones', 'NS', 4.400, 'undefined', 'Small Stones weight', 0, 3, NULL, '2024-12-24 16:09:30', '2024-12-24 16:09:30'),
(3, 1, 'Chokar Set Large Stones', 'NS', 2.700, 'undefined', 'Large Stones Zircons', 0, 3, NULL, '2024-12-24 16:10:27', '2024-12-24 16:10:27'),
(4, 1, 'Chokar Set', 'NS', 15.000, 'undefined', 'Beads hanging in Necklace Set', 0, 3, NULL, '2024-12-24 16:11:21', '2024-12-24 16:11:21');

-- --------------------------------------------------------

--
-- Table structure for table `job_task_details`
--

CREATE TABLE `job_task_details` (
  `id` bigint UNSIGNED NOT NULL,
  `job_task_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `design_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `net_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_task_details`
--

INSERT INTO `job_task_details` (`id`, `job_task_id`, `product_id`, `category`, `design_no`, `net_weight`, `description`, `is_deleted`, `createdby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 1, 15, '21K Chokar', 'NS000159', 44.900, 'Chokar Set addition of Center Flower', 0, 1, NULL, '2024-12-23 15:15:17', '2024-12-23 15:15:17');

-- --------------------------------------------------------

--
-- Table structure for table `journals`
--

CREATE TABLE `journals` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefix` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journals`
--

INSERT INTO `journals` (`id`, `name`, `prefix`, `is_active`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'Bank Payment Voucher', 'BPV', 1, 0, 1, NULL, NULL, '2024-09-23 19:46:24', '2024-09-23 19:46:24'),
(2, 'Bank Receipt Voucher', 'BRV', 1, 0, 1, NULL, NULL, '2024-09-23 19:47:25', '2024-09-23 19:47:25'),
(3, 'Cash Payment Voucher', 'CPV', 1, 0, 1, NULL, NULL, '2024-09-23 19:47:42', '2024-09-23 19:47:42'),
(4, 'Cash Receipt Voucher', 'CRV', 1, 0, 1, NULL, NULL, '2024-09-23 19:48:02', '2024-09-23 19:48:02'),
(5, 'Journal Voucher', 'JV', 1, 0, 1, NULL, NULL, '2024-09-23 19:48:18', '2024-09-23 19:48:18'),
(6, 'PMU Sales JV', 'PSJ', 1, 0, 1, NULL, NULL, '2024-09-23 19:48:49', '2024-09-23 19:48:49'),
(7, 'Purchase Return Voucher', 'PRV', 1, 0, 1, NULL, NULL, '2024-09-23 19:49:04', '2024-09-23 19:49:04'),
(8, 'Purchase Voucher', 'PV', 1, 0, 1, NULL, NULL, '2024-09-23 19:49:15', '2024-09-23 19:49:15'),
(9, 'Sales Return Voucher', 'SRV', 1, 0, 1, NULL, NULL, '2024-09-23 19:49:29', '2024-09-23 19:49:29'),
(10, 'Sales Voucher', 'SV', 1, 0, 1, NULL, NULL, '2024-09-23 19:49:42', '2024-09-23 19:49:42'),
(11, 'Store Transaction Voucher', 'STV', 1, 0, 1, NULL, NULL, '2024-09-23 19:49:57', '2024-09-23 19:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint UNSIGNED NOT NULL,
  `entryNum` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_id` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `date_post` date DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_in_words` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `entryNum`, `journal_id`, `supplier_id`, `customer_id`, `date_post`, `reference`, `amount_in_words`, `createdby_id`, `updatedby_id`, `is_deleted`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'PV-2024-12-0001', 8, 16, NULL, '2024-12-19', 'Date :2024-12-19 Against RK-19122024-0005. From Opening', NULL, 1, NULL, 0, NULL, '2024-12-19 15:44:13', '2024-12-19 15:44:13'),
(2, 'PV-2024-12-0002', 8, 8, NULL, '2024-12-23', 'Date :2024-12-23 Against RK-23122024-0011. From Abdul Majeed', NULL, 1, NULL, 0, NULL, '2024-12-23 19:21:14', '2024-12-23 19:21:14'),
(3, 'PV-2024-12-0003', 8, 8, NULL, '2024-12-23', 'Date :2024-12-23 Against RK-23122024-0012. From Abdul Majeed', NULL, 1, NULL, 0, NULL, '2024-12-23 20:03:41', '2024-12-23 20:03:41'),
(4, 'PV-2024-12-0004', 8, 8, NULL, '2024-12-23', 'Date :2024-12-23 Against RK-23122024-0013. From Abdul Majeed', NULL, 1, NULL, 0, NULL, '2024-12-23 20:25:56', '2024-12-23 20:25:56'),
(5, 'PV-2024-12-0005', 8, 12, NULL, '2024-12-23', 'Date :2024-12-23 Against RK-23122024-0014. From Shahzad', NULL, 1, NULL, 0, NULL, '2024-12-23 20:46:26', '2024-12-23 20:46:26'),
(6, 'PV-2024-12-0006', 8, 8, NULL, '2024-12-21', 'Date :2024-12-21 Against RK-26122024-0019. From Abdul Majeed', NULL, 1, NULL, 0, NULL, '2024-12-26 18:49:04', '2024-12-26 18:49:04');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entry_details`
--

CREATE TABLE `journal_entry_details` (
  `id` bigint UNSIGNED NOT NULL,
  `journal_entry_id` int DEFAULT NULL,
  `explanation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bill_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_date` date DEFAULT NULL,
  `credit` decimal(18,3) NOT NULL DEFAULT '0.000',
  `debit` decimal(18,3) NOT NULL DEFAULT '0.000',
  `currency` int NOT NULL DEFAULT '0' COMMENT '0 for pkr, 1 for AU, 2 for dollar',
  `doc_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `amount_in_words` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entry_details`
--

INSERT INTO `journal_entry_details` (`id`, `journal_entry_id`, `explanation`, `bill_no`, `check_no`, `check_date`, `credit`, `debit`, `currency`, `doc_date`, `account_id`, `amount`, `amount_in_words`, `account_code`, `createdby_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ratti Kaat Gold(AU) Debit Entry', '5', '0', '2024-12-19', 0.000, 2.197, 1, NULL, 61, 2.197, 'Two', '001-004-001', 1, NULL, NULL),
(2, 1, 'Ratti Kaat Gold(AU) Supplier/Karigar Credit Entry', '5', '0', '2024-12-19', 2.197, 0.000, 1, NULL, 40, 2.197, 'Two', '003-002-001', 1, NULL, NULL),
(3, 2, 'Ratti Kaat PKR Debit Entry', '11', '0', '2024-12-23', 0.000, 16799.436, 0, NULL, 61, 16799.436, 'Sixteen  Thousand and  Seven Hundreds  Ninety  Nine', '001-004-001', 1, NULL, NULL),
(4, 2, 'Ratti Kaat PKR Supplier/Karigar Credit Entry', '11', '0', '2024-12-23', 16799.436, 0.000, 0, NULL, 41, 16799.436, 'Sixteen  Thousand and  Seven Hundreds  Ninety  Nine', '003-002-002', 1, NULL, NULL),
(5, 2, 'Ratti Kaat Gold(AU) Debit Entry', '11', '0', '2024-12-23', 0.000, 23.381, 1, NULL, 61, 23.381, 'Twenty  Three', '001-004-001', 1, NULL, NULL),
(6, 2, 'Ratti Kaat Gold(AU) Supplier/Karigar Credit Entry', '11', '0', '2024-12-23', 23.381, 0.000, 1, NULL, 40, 23.381, 'Twenty  Three', '003-002-001', 1, NULL, NULL),
(7, 3, 'Ratti Kaat PKR Debit Entry', '12', '0', '2024-12-23', 0.000, 3767.475, 0, NULL, 61, 3767.475, 'Three  Thousand and  Seven Hundreds  Sixty  Seven', '001-004-001', 1, NULL, NULL),
(8, 3, 'Ratti Kaat PKR Supplier/Karigar Credit Entry', '12', '0', '2024-12-23', 3767.475, 0.000, 0, NULL, 41, 3767.475, 'Three  Thousand and  Seven Hundreds  Sixty  Seven', '003-002-002', 1, NULL, NULL),
(9, 3, 'Ratti Kaat Gold(AU) Debit Entry', '12', '0', '2024-12-23', 0.000, 5.537, 1, NULL, 61, 5.537, 'Five', '001-004-001', 1, NULL, NULL),
(10, 3, 'Ratti Kaat Gold(AU) Supplier/Karigar Credit Entry', '12', '0', '2024-12-23', 5.537, 0.000, 1, NULL, 40, 5.537, 'Five', '003-002-001', 1, NULL, NULL),
(11, 4, 'Ratti Kaat PKR Debit Entry', '13', '0', '2024-12-23', 0.000, 3182.600, 0, NULL, 61, 3182.600, 'Three  Thousand and  One Hundred  Eighty  Two', '001-004-001', 1, NULL, NULL),
(12, 4, 'Ratti Kaat PKR Supplier/Karigar Credit Entry', '13', '0', '2024-12-23', 3182.600, 0.000, 0, NULL, 41, 3182.600, 'Three  Thousand and  One Hundred  Eighty  Two', '003-002-002', 1, NULL, NULL),
(13, 4, 'Ratti Kaat Gold(AU) Debit Entry', '13', '0', '2024-12-23', 0.000, 3.942, 1, NULL, 61, 3.942, 'Three', '001-004-001', 1, NULL, NULL),
(14, 4, 'Ratti Kaat Gold(AU) Supplier/Karigar Credit Entry', '13', '0', '2024-12-23', 3.942, 0.000, 1, NULL, 40, 3.942, 'Three', '003-002-001', 1, NULL, NULL),
(15, 5, 'Ratti Kaat PKR Debit Entry', '14', '0', '2024-12-23', 0.000, 8444.500, 0, NULL, 61, 8444.500, 'Eight  Thousand and  Four Hundreds  Forty  Four', '001-004-001', 1, NULL, NULL),
(16, 5, 'Ratti Kaat PKR Supplier/Karigar Credit Entry', '14', '0', '2024-12-23', 8444.500, 0.000, 0, NULL, 41, 8444.500, 'Eight  Thousand and  Four Hundreds  Forty  Four', '003-002-002', 1, NULL, NULL),
(17, 5, 'Ratti Kaat Gold(AU) Debit Entry', '14', '0', '2024-12-23', 0.000, 10.725, 1, NULL, 61, 10.725, 'Ten', '001-004-001', 1, NULL, NULL),
(18, 5, 'Ratti Kaat Gold(AU) Supplier/Karigar Credit Entry', '14', '0', '2024-12-23', 10.725, 0.000, 1, NULL, 40, 10.725, 'Ten', '003-002-001', 1, NULL, NULL),
(19, 6, 'Ratti Kaat Gold(AU) Debit Entry', '19', '0', '2024-12-21', 0.000, 5.017, 1, NULL, 61, 5.017, 'Five', '001-004-001', 1, NULL, NULL),
(20, 6, 'Ratti Kaat Gold(AU) Supplier/Karigar Credit Entry', '19', '0', '2024-12-21', 5.017, 0.000, 1, NULL, 40, 5.017, 'Five', '003-002-001', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_09_02_194924_create_permission_tables', 1),
(6, '2024_09_04_212533_create_warehouses_table', 2),
(7, '2024_09_05_000951_create_accounts_table', 3),
(8, '2024_09_05_001923_create_account_types_table', 3),
(9, '2024_09_05_230824_create_suppliers_table', 4),
(10, '2024_09_06_082046_create_journals_table', 5),
(11, '2024_09_06_182838_create_journal_entries_table', 6),
(12, '2024_09_06_183423_create_journal_entry_details_table', 6),
(13, '2024_09_09_230457_create_customers_table', 7),
(14, '2024_09_09_234727_create_products_table', 8),
(15, '2024_09_11_224744_create_employees_table', 9),
(20, '2024_09_14_193707_create_ratti_kaats_table', 10),
(21, '2024_09_15_173639_create_ratti_kaat_details_table', 11),
(22, '2024_09_15_213003_create_ratti_kaat_beads_table', 12),
(23, '2024_09_15_213235_create_ratti_kaat_stones_table', 13),
(24, '2024_09_18_222641_create_ratti_kaat_diamonds_table', 14),
(25, '2024_09_25_053101_create_supplier_payments_table', 15),
(26, '2024_09_26_223244_create_gold_rates_table', 16),
(27, '2024_09_27_005629_create_dollar_rates_table', 16),
(28, '2024_09_30_213559_create_finish_products_table', 17),
(29, '2024_10_05_210049_create_finish_product_beads_table', 18),
(30, '2024_10_05_211105_create_finish_product_stones_table', 18),
(31, '2024_10_05_211222_create_finish_product_diamonds_table', 18),
(32, '2024_10_04_220010_create_sales_table', 19),
(33, '2024_10_04_223105_create_sale_details_table', 19),
(34, '2024_10_04_230549_create_sale_detail_beads_table', 19),
(35, '2024_10_04_231850_create_sale_detail_stones_table', 19),
(36, '2024_10_04_232014_create_sale_detail_diamonds_table', 19),
(37, '2024_10_24_133042_create_bead_types_table', 20),
(38, '2024_10_24_133652_create_stone_categories_table', 20),
(39, '2024_10_24_133806_create_diamond_types_table', 20),
(40, '2024_10_24_133844_create_diamond_colors_table', 20),
(41, '2024_10_24_133905_create_diamond_cuts_table', 20),
(42, '2024_10_24_133945_create_diamond_clarities_table', 20),
(43, '2024_10_26_215001_create_other_sales_table', 21),
(44, '2024_10_26_215627_create_other_sale_details_table', 21),
(45, '2024_10_26_231811_create_other_products_table', 21),
(46, '2024_10_26_232159_create_other_product_units_table', 21),
(47, '2024_10_26_233238_create_transactions_table', 21),
(48, '2024_10_27_173619_create_other_purchases_table', 22),
(49, '2024_10_27_182503_create_other_purchase_details_table', 22),
(50, '2024_10_29_002339_create_stock_takings_table', 22),
(51, '2024_10_29_002813_create_stock_taking_details_table', 22),
(52, '2024_10_31_225107_create_company_settings_table', 23),
(53, '2024_11_01_221029_create_sale_orders_table', 23),
(54, '2024_11_01_221955_create_gold_rate_types_table', 23),
(55, '2024_11_01_222120_create_sale_order_details_table', 23),
(56, '2024_11_05_141632_create_purchase_orders_table', 23),
(57, '2024_11_05_141949_create_purchase_order_details_table', 23),
(58, '2024_11_06_165508_create_job_tasks_table', 23),
(59, '2024_11_06_165907_create_job_task_details_table', 23),
(60, '2024_11_06_165934_create_job_task_activities_table', 23),
(61, '2024_11_08_223900_create_job_purchases_table', 23),
(62, '2024_11_08_224031_create_job_purchase_details_table', 23),
(63, '2024_11_08_224144_create_job_purchase_detail_beads_table', 23),
(64, '2024_11_08_224203_create_job_purchase_detail_stones_table', 23),
(65, '2024_11_08_224222_create_job_purchase_detail_diamonds_table', 23),
(68, '2024_11_15_064104_create_gold_impurity_purchases_table', 24),
(69, '2024_11_15_065344_create_gold_impurity_purchase_details_table', 24);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Table structure for table `other_products`
--

CREATE TABLE `other_products` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_product_unit_id` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_product_units`
--

CREATE TABLE `other_product_units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `other_product_units`
--

INSERT INTO `other_product_units` (`id`, `name`, `is_active`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'Piece', 1, 0, 1, NULL, NULL, NULL, NULL),
(2, 'KG', 1, 0, 1, NULL, NULL, NULL, NULL),
(3, 'Liter', 1, 0, 1, NULL, NULL, NULL, NULL),
(4, 'Meter', 1, 0, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `other_purchases`
--

CREATE TABLE `other_purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `other_purchase_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_purchase_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `reference` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_qty` decimal(18,3) DEFAULT NULL,
  `tax` decimal(18,3) DEFAULT NULL,
  `tax_amount` decimal(18,3) DEFAULT NULL,
  `sub_total` decimal(18,3) DEFAULT NULL,
  `total` decimal(18,3) DEFAULT NULL,
  `paid` decimal(18,3) DEFAULT NULL,
  `purchase_account_id` int DEFAULT NULL,
  `paid_account_id` int DEFAULT NULL,
  `supplier_payment_id` int DEFAULT NULL,
  `jv_id` int DEFAULT NULL,
  `paid_jv_id` int DEFAULT NULL,
  `posted` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_purchase_details`
--

CREATE TABLE `other_purchase_details` (
  `id` bigint UNSIGNED NOT NULL,
  `other_purchase_id` int DEFAULT NULL,
  `other_product_id` int DEFAULT NULL,
  `qty` decimal(18,3) NOT NULL DEFAULT '0.000',
  `unit_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_sales`
--

CREATE TABLE `other_sales` (
  `id` bigint UNSIGNED NOT NULL,
  `other_sale_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_sale_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_qty` decimal(18,3) DEFAULT NULL,
  `tax` decimal(18,3) DEFAULT NULL,
  `tax_amount` decimal(18,3) DEFAULT NULL,
  `sub_total` decimal(18,3) DEFAULT NULL,
  `total` decimal(18,3) DEFAULT NULL,
  `is_credit` tinyint(1) NOT NULL DEFAULT '0',
  `cash_amount` decimal(18,3) DEFAULT NULL,
  `bank_transfer_amount` decimal(18,3) DEFAULT NULL,
  `card_amount` decimal(18,3) DEFAULT NULL,
  `advance_amount` decimal(18,3) DEFAULT NULL,
  `total_received` decimal(18,3) DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `jv_id` int DEFAULT NULL,
  `posted` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_sale_details`
--

CREATE TABLE `other_sale_details` (
  `id` bigint UNSIGNED NOT NULL,
  `other_sale_id` int DEFAULT NULL,
  `other_product_id` int DEFAULT NULL,
  `qty` decimal(18,3) NOT NULL DEFAULT '0.000',
  `unit_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard_access', 'web', '2024-09-03 15:09:01', '2024-09-03 15:09:01'),
(2, 'logout_access', 'web', '2024-09-03 15:35:18', '2024-09-03 18:45:59'),
(3, 'user_management_access', 'web', '2024-09-03 16:44:56', '2024-09-03 16:44:56'),
(4, 'permissions_access', 'web', '2024-09-03 16:45:15', '2024-09-03 18:43:18'),
(5, 'permissions_create', 'web', '2024-09-03 16:45:28', '2024-09-03 18:43:52'),
(6, 'permissions_edit', 'web', '2024-09-03 16:45:40', '2024-09-04 18:09:02'),
(7, 'roles_access', 'web', '2024-09-03 16:46:06', '2024-09-03 16:46:18'),
(8, 'roles_create', 'web', '2024-09-03 16:46:30', '2024-09-03 16:46:30'),
(9, 'roles_edit', 'web', '2024-09-03 16:46:44', '2024-09-03 16:46:44'),
(10, 'users_access', 'web', '2024-09-03 18:46:17', '2024-09-03 18:46:17'),
(11, 'users_create', 'web', '2024-09-03 18:46:26', '2024-09-03 18:46:26'),
(12, 'users_edit', 'web', '2024-09-03 18:46:37', '2024-09-03 18:46:37'),
(13, 'inventory_access', 'web', '2024-09-04 18:10:16', '2024-09-04 18:10:16'),
(14, 'warehouses_access', 'web', '2024-09-04 18:10:32', '2024-09-04 18:10:32'),
(15, 'warehouses_create', 'web', '2024-09-04 18:10:43', '2024-09-04 18:10:43'),
(16, 'warehouses_edit', 'web', '2024-09-04 18:10:54', '2024-09-04 18:10:54'),
(17, 'warehouses_delete', 'web', '2024-09-04 18:12:49', '2024-09-04 18:12:49'),
(18, 'accounting_access', 'web', '2024-09-05 17:49:20', '2024-09-05 17:49:20'),
(19, 'accounts_access', 'web', '2024-09-05 17:49:32', '2024-09-05 17:49:32'),
(20, 'accounts_create', 'web', '2024-09-05 17:49:41', '2024-09-05 17:49:41'),
(21, 'accounts_edit', 'web', '2024-09-05 17:49:50', '2024-09-05 17:49:50'),
(22, 'accounts_delete', 'web', '2024-09-05 17:49:59', '2024-09-05 17:49:59'),
(23, 'accounts_status', 'web', '2024-09-05 17:50:09', '2024-09-05 17:50:09'),
(24, 'suppliers_access', 'web', '2024-09-05 18:31:05', '2024-09-05 18:31:05'),
(25, 'suppliers_create', 'web', '2024-09-05 18:31:14', '2024-09-05 18:31:14'),
(26, 'suppliers_edit', 'web', '2024-09-05 18:31:21', '2024-09-05 18:31:21'),
(27, 'suppliers_delete', 'web', '2024-09-05 18:31:35', '2024-09-05 18:31:35'),
(28, 'suppliers_status', 'web', '2024-09-05 18:31:45', '2024-09-05 18:31:45'),
(29, 'journals_access', 'web', '2024-09-06 04:08:19', '2024-09-06 04:08:19'),
(30, 'journals_create', 'web', '2024-09-06 04:08:30', '2024-09-06 04:08:30'),
(31, 'journals_edit', 'web', '2024-09-06 04:08:44', '2024-09-06 04:08:44'),
(32, 'journals_delete', 'web', '2024-09-06 04:08:54', '2024-09-06 04:08:54'),
(33, 'journals_status', 'web', '2024-09-06 04:09:05', '2024-09-06 04:09:05'),
(34, 'journal_entries_access', 'web', '2024-09-06 15:22:33', '2024-09-06 15:22:33'),
(35, 'journal_entries_create', 'web', '2024-09-06 15:22:44', '2024-09-06 15:22:44'),
(36, 'journal_entries_edit', 'web', '2024-09-06 15:22:57', '2024-09-06 15:22:57'),
(37, 'journal_entries_print', 'web', '2024-09-06 15:23:10', '2024-09-06 15:23:10'),
(38, 'journal_entries_delete', 'web', '2024-09-06 15:23:22', '2024-09-06 15:23:22'),
(39, 'customers_access', 'web', '2024-09-09 18:32:21', '2024-09-09 18:32:21'),
(40, 'customers_create', 'web', '2024-09-09 18:32:33', '2024-09-09 18:32:33'),
(41, 'customers_edit', 'web', '2024-09-09 18:32:42', '2024-09-09 18:32:42'),
(42, 'customers_status', 'web', '2024-09-09 18:32:53', '2024-09-09 18:32:53'),
(43, 'customers_delete', 'web', '2024-09-09 18:33:03', '2024-09-09 18:33:03'),
(44, 'products_access', 'web', '2024-09-09 18:55:23', '2024-09-09 18:55:23'),
(45, 'products_create', 'web', '2024-09-09 18:55:32', '2024-09-09 18:55:32'),
(46, 'products_edit', 'web', '2024-09-09 18:55:40', '2024-09-09 18:55:40'),
(47, 'products_status', 'web', '2024-09-09 18:55:50', '2024-09-09 18:55:50'),
(48, 'products_delete', 'web', '2024-09-09 18:55:59', '2024-09-09 18:55:59'),
(49, 'hrm_access', 'web', '2024-09-11 19:10:46', '2024-09-11 19:10:46'),
(50, 'employees_access', 'web', '2024-09-11 19:10:53', '2024-09-11 19:10:53'),
(51, 'employees_create', 'web', '2024-09-11 19:11:09', '2024-09-11 19:11:09'),
(52, 'employees_edit', 'web', '2024-09-11 19:11:18', '2024-09-11 19:11:18'),
(53, 'employees_status', 'web', '2024-09-11 19:11:28', '2024-09-11 19:11:28'),
(54, 'employees_delete', 'web', '2024-09-11 19:11:38', '2024-09-11 19:11:38'),
(55, 'ratti_kaat_access', 'web', '2024-09-19 19:42:15', '2024-09-19 19:42:15'),
(56, 'ratti_kaat_create', 'web', '2024-09-19 19:42:24', '2024-09-19 19:42:24'),
(57, 'ratti_kaat_edit', 'web', '2024-09-19 19:42:31', '2024-09-19 19:42:31'),
(58, 'ratti_kaat_delete', 'web', '2024-09-19 19:42:40', '2024-09-19 19:42:40'),
(59, 'ratti_kaat_post', 'web', '2024-09-19 19:42:48', '2024-09-19 19:42:48'),
(60, 'purchase_access', 'web', '2024-09-19 20:25:46', '2024-09-19 20:25:46'),
(61, 'supplier_payment_access', 'web', '2024-09-25 16:25:17', '2024-09-25 16:25:17'),
(62, 'supplier_payment_create', 'web', '2024-09-25 16:25:31', '2024-09-25 16:25:31'),
(63, 'supplier_payment_view', 'web', '2024-09-25 16:25:44', '2024-12-03 13:23:44'),
(64, 'supplier_payment_delete', 'web', '2024-09-25 16:25:56', '2024-09-25 16:25:56'),
(65, 'supplier_payment_jvs', 'web', '2024-09-25 17:35:50', '2024-12-03 13:23:17'),
(66, 'tagging_product_access', 'web', '2024-12-02 18:40:38', '2024-12-02 18:47:49'),
(67, 'tagging_product_create', 'web', '2024-12-02 18:40:53', '2024-12-02 18:48:01'),
(68, 'tagging_product_view', 'web', '2024-12-02 18:41:04', '2024-12-02 18:48:38'),
(69, 'tagging_product_delete', 'web', '2024-12-02 18:41:14', '2024-12-02 18:48:14'),
(70, 'tagging_product_status', 'web', '2024-12-02 18:41:32', '2024-12-02 18:48:26'),
(71, 'other_product_access', 'web', '2024-12-02 18:42:12', '2024-12-02 18:48:52'),
(72, 'other_product_create', 'web', '2024-12-02 18:42:25', '2024-12-02 18:42:25'),
(73, 'other_product_edit', 'web', '2024-12-02 18:42:37', '2024-12-02 18:42:37'),
(74, 'other_product_delete', 'web', '2024-12-02 18:42:46', '2024-12-02 18:42:46'),
(75, 'stock_access', 'web', '2024-12-02 18:43:23', '2024-12-02 18:49:10'),
(76, 'stock_taking_access', 'web', '2024-12-02 18:44:15', '2024-12-02 18:49:23'),
(77, 'stock_taking_create', 'web', '2024-12-02 18:44:26', '2024-12-02 18:44:26'),
(78, 'stock_taking_edit', 'web', '2024-12-02 18:44:36', '2024-12-02 18:44:36'),
(79, 'stock_taking_delete', 'web', '2024-12-02 18:44:47', '2024-12-02 18:44:47'),
(80, 'bead_type_access', 'web', '2024-12-02 18:49:39', '2024-12-02 18:49:39'),
(81, 'bead_type_create', 'web', '2024-12-02 18:51:07', '2024-12-02 18:51:07'),
(82, 'bead_type_edit', 'web', '2024-12-02 18:51:21', '2024-12-02 18:51:21'),
(83, 'bead_type_status', 'web', '2024-12-02 18:51:33', '2024-12-02 18:51:33'),
(84, 'bead_type_delete', 'web', '2024-12-02 18:51:46', '2024-12-02 18:51:46'),
(85, 'setting_access', 'web', '2024-12-02 18:54:10', '2024-12-02 18:54:10'),
(86, 'setting_update', 'web', '2024-12-02 18:54:20', '2024-12-02 18:54:20'),
(87, 'diamond_clarity_access', 'web', '2024-12-02 18:55:47', '2024-12-02 18:55:47'),
(88, 'diamond_clarity_create', 'web', '2024-12-02 18:55:59', '2024-12-02 18:55:59'),
(89, 'diamond_clarity_edit', 'web', '2024-12-02 18:56:09', '2024-12-02 18:56:09'),
(90, 'diamond_clarity_status', 'web', '2024-12-02 18:56:19', '2024-12-02 18:56:19'),
(91, 'diamond_clarity_delete', 'web', '2024-12-02 18:56:28', '2024-12-02 18:56:28'),
(92, 'diamond_color_access', 'web', '2024-12-02 19:04:42', '2024-12-02 19:04:42'),
(93, 'diamond_color_create', 'web', '2024-12-02 19:04:53', '2024-12-02 19:04:53'),
(94, 'diamond_color_edit', 'web', '2024-12-02 19:05:02', '2024-12-02 19:05:02'),
(95, 'diamond_color_status', 'web', '2024-12-02 19:05:13', '2024-12-02 19:05:13'),
(96, 'diamond_color_delete', 'web', '2024-12-02 19:05:24', '2024-12-02 19:05:24'),
(97, 'diamond_cut_access', 'web', '2024-12-02 19:05:42', '2024-12-02 19:05:42'),
(98, 'diamond_cut_create', 'web', '2024-12-02 19:05:52', '2024-12-02 19:05:52'),
(99, 'diamond_cut_edit', 'web', '2024-12-02 19:06:05', '2024-12-02 19:06:05'),
(100, 'diamond_cut_delete', 'web', '2024-12-02 19:06:15', '2024-12-02 19:06:15'),
(101, 'diamond_cut_status', 'web', '2024-12-02 19:06:31', '2024-12-02 19:06:31'),
(102, 'diamond_type_access', 'web', '2024-12-02 19:06:43', '2024-12-02 19:06:43'),
(103, 'diamond_type_create', 'web', '2024-12-02 19:06:53', '2024-12-02 19:06:53'),
(104, 'diamond_type_edit', 'web', '2024-12-02 19:07:04', '2024-12-02 19:07:04'),
(105, 'diamond_type_status', 'web', '2024-12-02 19:07:15', '2024-12-02 19:07:15'),
(106, 'diamond_type_delete', 'web', '2024-12-02 19:07:25', '2024-12-02 19:07:25'),
(107, 'gold_chart_access', 'web', '2024-12-03 03:51:49', '2024-12-03 03:51:49'),
(108, 'gold_rate_log_access', 'web', '2024-12-03 03:53:58', '2024-12-03 03:53:58'),
(109, 'gold_rate_log_create', 'web', '2024-12-03 03:55:16', '2024-12-03 03:55:16'),
(110, 'dollar_rate_log_create', 'web', '2024-12-03 03:55:26', '2024-12-03 03:55:26'),
(111, 'dollar_rate_log_access', 'web', '2024-12-03 03:55:41', '2024-12-03 03:55:41'),
(112, 'job_purchase_access', 'web', '2024-12-03 04:00:55', '2024-12-03 04:00:55'),
(113, 'job_purchase_create', 'web', '2024-12-03 04:01:06', '2024-12-03 04:01:06'),
(114, 'job_purchase_print', 'web', '2024-12-03 04:01:24', '2024-12-03 04:01:24'),
(115, 'job_purchase_unpost', 'web', '2024-12-03 04:01:52', '2024-12-03 04:01:52'),
(116, 'job_purchase_post', 'web', '2024-12-03 04:02:04', '2024-12-03 04:02:04'),
(117, 'job_purchase_delete', 'web', '2024-12-03 04:02:16', '2024-12-03 04:02:16'),
(118, 'job_purchase_jvs', 'web', '2024-12-03 04:03:10', '2024-12-03 04:03:10'),
(119, 'job_task_activity_access', 'web', '2024-12-03 04:06:28', '2024-12-03 04:07:35'),
(120, 'job_task_activity_create', 'web', '2024-12-03 04:06:38', '2024-12-03 04:07:47'),
(121, 'job_task_activity_delete', 'web', '2024-12-03 04:06:48', '2024-12-03 04:07:58'),
(122, 'job_task_access', 'web', '2024-12-03 04:11:10', '2024-12-03 04:11:10'),
(123, 'job_task_create', 'web', '2024-12-03 04:11:20', '2024-12-03 04:11:20'),
(124, 'job_task_print', 'web', '2024-12-03 04:11:38', '2024-12-03 04:11:38'),
(125, 'job_task_delete', 'web', '2024-12-03 04:11:49', '2024-12-03 04:11:49'),
(126, 'job_task_complete', 'web', '2024-12-03 04:14:02', '2024-12-03 04:14:02'),
(131, 'other_product_status', 'web', '2024-12-03 04:49:48', '2024-12-03 04:49:48'),
(132, 'other_purchase_access', 'web', '2024-12-03 04:55:04', '2024-12-03 04:55:04'),
(133, 'other_purchase_create', 'web', '2024-12-03 04:55:13', '2024-12-03 04:55:13'),
(134, 'other_purchase_print', 'web', '2024-12-03 04:55:29', '2024-12-03 04:55:29'),
(135, 'other_purchase_unpost', 'web', '2024-12-03 04:56:04', '2024-12-03 04:56:04'),
(136, 'other_purchase_post', 'web', '2024-12-03 04:56:36', '2024-12-03 04:56:36'),
(137, 'other_purchase_jvs', 'web', '2024-12-03 04:56:49', '2024-12-03 04:56:49'),
(138, 'other_purchase_delete', 'web', '2024-12-03 04:56:59', '2024-12-03 04:56:59'),
(139, 'other_sale_access', 'web', '2024-12-03 05:00:12', '2024-12-03 05:00:12'),
(140, 'other_sale_create', 'web', '2024-12-03 05:00:22', '2024-12-03 05:00:22'),
(141, 'other_sale_print', 'web', '2024-12-03 05:00:40', '2024-12-03 05:00:40'),
(142, 'other_sale_unpost', 'web', '2024-12-03 05:00:53', '2024-12-03 05:00:53'),
(143, 'other_sale_post', 'web', '2024-12-03 05:01:02', '2024-12-03 05:01:02'),
(144, 'other_sale_delete', 'web', '2024-12-03 05:01:18', '2024-12-03 05:01:18'),
(145, 'purchase_order_access', 'web', '2024-12-03 12:41:22', '2024-12-03 12:41:22'),
(146, 'purchase_order_create', 'web', '2024-12-03 12:41:34', '2024-12-03 12:41:34'),
(147, 'purchase_order_print', 'web', '2024-12-03 12:41:50', '2024-12-03 12:41:50'),
(148, 'purchase_order_approve', 'web', '2024-12-03 12:42:01', '2024-12-03 12:42:01'),
(149, 'purchase_order_reject', 'web', '2024-12-03 12:42:13', '2024-12-03 12:42:13'),
(150, 'purchase_order_delete', 'web', '2024-12-03 12:42:23', '2024-12-03 12:42:23'),
(151, 'ratti_kaat_jvs', 'web', '2024-12-03 12:52:09', '2024-12-03 12:52:09'),
(152, 'ledger_report', 'web', '2024-12-03 13:00:35', '2024-12-03 13:00:35'),
(153, 'tag_history_report', 'web', '2024-12-03 13:00:48', '2024-12-03 13:00:48'),
(154, 'profit_loss_report', 'web', '2024-12-03 13:01:02', '2024-12-03 13:01:02'),
(155, 'stock_ledger_report', 'web', '2024-12-03 13:01:12', '2024-12-03 13:01:12'),
(156, 'product_ledger_report', 'web', '2024-12-03 13:01:21', '2024-12-03 13:01:21'),
(157, 'customer_list_report', 'web', '2024-12-03 13:01:29', '2024-12-03 13:01:29'),
(158, 'product_consumption_report', 'web', '2024-12-03 13:01:37', '2024-12-03 13:01:37'),
(159, 'financial_report', 'web', '2024-12-03 13:01:46', '2024-12-03 13:01:46'),
(160, 'sale_access', 'web', '2024-12-03 13:06:43', '2024-12-03 13:06:43'),
(161, 'sale_create', 'web', '2024-12-03 13:06:54', '2024-12-03 13:06:54'),
(162, 'sale_print', 'web', '2024-12-03 13:07:16', '2024-12-03 13:07:16'),
(163, 'sale_unpost', 'web', '2024-12-03 13:07:26', '2024-12-03 13:07:26'),
(164, 'sale_post', 'web', '2024-12-03 13:07:35', '2024-12-03 13:07:35'),
(165, 'sale_delete', 'web', '2024-12-03 13:07:48', '2024-12-03 13:07:48'),
(166, 'sale_jvs', 'web', '2024-12-03 13:07:59', '2024-12-03 13:07:59'),
(167, 'sale_order_access', 'web', '2024-12-03 13:10:15', '2024-12-03 13:10:15'),
(168, 'sale_order_create', 'web', '2024-12-03 13:10:25', '2024-12-03 13:10:25'),
(169, 'sale_order_print', 'web', '2024-12-03 13:10:39', '2024-12-03 13:10:39'),
(170, 'sale_order_delete', 'web', '2024-12-03 13:10:55', '2024-12-03 13:10:55'),
(171, 'stock_taking_view', 'web', '2024-12-03 13:15:24', '2024-12-03 13:15:24'),
(172, 'stock_taking_print', 'web', '2024-12-03 13:15:41', '2024-12-03 13:15:41'),
(173, 'stone_category_access', 'web', '2024-12-03 13:19:39', '2024-12-03 13:19:39'),
(174, 'stone_category_create', 'web', '2024-12-03 13:19:49', '2024-12-03 13:19:49'),
(175, 'stone_category_edit', 'web', '2024-12-03 13:20:04', '2024-12-03 13:20:04'),
(176, 'stone_category_status', 'web', '2024-12-03 13:20:50', '2024-12-03 13:20:50'),
(177, 'stone_category_delete', 'web', '2024-12-03 13:21:25', '2024-12-03 13:21:25'),
(178, 'transaction_log_access', 'web', '2024-12-03 13:25:16', '2024-12-03 13:25:16'),
(179, 'transaction_log_delete', 'web', '2024-12-03 13:25:28', '2024-12-03 13:25:28'),
(180, 'gold_impurity_access', 'web', '2024-12-08 05:31:22', '2024-12-08 05:31:22'),
(181, 'gold_impurity_create', 'web', '2024-12-08 05:31:34', '2024-12-08 05:31:34'),
(182, 'gold_impurity_print', 'web', '2024-12-08 05:32:01', '2024-12-08 05:32:01'),
(183, 'gold_impurity_post', 'web', '2024-12-08 05:32:14', '2024-12-08 05:32:14'),
(184, 'gold_impurity_unpost', 'web', '2024-12-08 05:32:27', '2024-12-08 05:32:27'),
(185, 'gold_impurity_jvs', 'web', '2024-12-08 05:32:45', '2024-12-08 05:32:45'),
(186, 'gold_impurity_delete', 'web', '2024-12-08 05:32:58', '2024-12-08 05:32:58'),
(187, 'common_access', 'web', '2024-12-08 05:39:29', '2024-12-08 05:39:29'),
(188, 'sales_access', 'web', '2024-12-08 05:41:22', '2024-12-08 05:41:22'),
(189, 'gold_rate_access', 'web', '2024-12-08 05:43:13', '2024-12-08 05:43:13'),
(190, 'report_access', 'web', '2024-12-08 05:44:13', '2024-12-08 05:44:13'),
(191, 'dollar_rate_access', 'web', '2024-12-08 05:46:33', '2024-12-08 05:46:33');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefix` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `prefix`, `is_active`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'Ladies Rings', 'LR', 1, 1, 1, 1, 1, '2024-09-09 19:13:26', '2024-09-09 19:13:38'),
(2, 'Ladies Rings', 'LR', 1, 1, 1, 1, 1, '2024-09-16 11:27:42', '2024-11-19 20:26:20'),
(3, 'Necklace Set', 'NS', 1, 1, 1, NULL, 1, '2024-10-07 15:56:49', '2024-11-19 20:26:07'),
(4, 'Diamond Ladie Rings', 'DLR', 1, 1, 1, NULL, 1, '2024-11-07 16:29:30', '2024-11-19 20:25:59'),
(5, 'Ladies Rings', 'LR', 1, 1, 1, NULL, 1, '2024-11-14 21:05:10', '2024-11-19 20:26:04'),
(6, 'Ladies Rings', 'LR', 1, 0, 1, NULL, NULL, '2024-11-19 20:26:45', '2024-11-19 20:26:45'),
(7, 'Diamond Ladies Rings', 'DLR', 1, 0, 1, NULL, NULL, '2024-11-19 20:27:02', '2024-11-19 20:27:02'),
(8, 'Gents Rings', 'GR', 1, 0, 1, NULL, NULL, '2024-11-19 20:27:14', '2024-11-19 20:27:14'),
(9, 'Diamond Gents Ring', 'DGR', 1, 0, 1, NULL, NULL, '2024-11-19 20:27:31', '2024-11-19 20:27:31'),
(10, 'Bangles', 'B', 1, 0, 1, NULL, NULL, '2024-11-19 20:27:46', '2024-11-19 20:27:46'),
(11, 'Loose Bracelet', 'LBR', 1, 0, 1, NULL, NULL, '2024-11-19 20:28:12', '2024-11-19 20:28:12'),
(12, 'Crown', 'CR', 1, 0, 1, 1, NULL, '2024-11-19 20:28:35', '2024-11-19 20:35:20'),
(13, 'Kara Bangle', 'KB', 1, 0, 1, NULL, NULL, '2024-11-19 20:28:59', '2024-11-19 20:28:59'),
(14, 'Baliya', 'BL', 1, 1, 1, NULL, 1, '2024-11-21 04:08:37', '2024-11-21 04:08:45'),
(15, 'Necklace Set', 'NS', 1, 0, 1, NULL, NULL, '2024-12-17 20:42:14', '2024-12-17 20:42:14'),
(16, 'Mala Set', 'MS', 1, 0, 1, NULL, NULL, '2024-12-17 20:42:30', '2024-12-17 20:42:30'),
(17, 'Locket Set', 'LS', 1, 0, 1, NULL, NULL, '2024-12-17 20:42:44', '2024-12-17 20:42:44'),
(18, 'Chain', 'CH', 1, 0, 1, NULL, NULL, '2024-12-18 21:28:33', '2024-12-18 21:28:33'),
(19, 'Nose Pin', 'NP', 1, 0, 1, NULL, NULL, '2024-12-23 15:22:28', '2024-12-23 15:22:28'),
(20, 'Diamond Nose Pin', 'DNP', 1, 0, 1, NULL, NULL, '2024-12-23 15:22:46', '2024-12-23 15:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_order_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `reference_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `sale_order_id` int DEFAULT NULL,
  `total_qty` decimal(18,3) DEFAULT NULL,
  `is_complete` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `approvedby_id` int DEFAULT NULL,
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `purchase_order_no`, `purchase_order_date`, `delivery_date`, `status`, `reference_no`, `supplier_id`, `warehouse_id`, `sale_order_id`, `total_qty`, `is_complete`, `is_deleted`, `approvedby_id`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'POO-23122024-0001', NULL, NULL, 'Pending', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 1, NULL, NULL, '2024-12-23 15:08:09', '2024-12-23 15:08:09'),
(2, 'POO-23122024-0002', '2024-12-11', '2024-12-21T13:11', 'Approved', 'SO-23122024-0002', 8, 1, 2, 1.000, 0, 0, NULL, 1, 1, NULL, '2024-12-23 15:11:08', '2024-12-23 15:15:17');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_details`
--

CREATE TABLE `purchase_order_details` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `design_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `net_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_details`
--

INSERT INTO `purchase_order_details` (`id`, `purchase_order_id`, `product_id`, `category`, `design_no`, `net_weight`, `description`, `is_deleted`, `createdby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 2, 15, '21K Chokar', 'NS000159', 44.900, 'Chokar Set addition of Center Flower', 0, 1, NULL, '2024-12-23 15:11:43', '2024-12-23 15:11:43');

-- --------------------------------------------------------

--
-- Table structure for table `ratti_kaats`
--

CREATE TABLE `ratti_kaats` (
  `id` bigint UNSIGNED NOT NULL,
  `ratti_kaat_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  `purchase_account` int DEFAULT NULL,
  `paid` decimal(18,3) NOT NULL DEFAULT '0.000',
  `paid_account` int DEFAULT NULL,
  `paid_au` decimal(18,3) NOT NULL DEFAULT '0.000',
  `paid_account_au` int DEFAULT NULL,
  `paid_dollar` decimal(18,3) NOT NULL DEFAULT '0.000',
  `paid_account_dollar` int DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pictures` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tax_amount` decimal(18,3) DEFAULT NULL,
  `tax_account` int DEFAULT NULL,
  `sub_total` decimal(18,3) DEFAULT '0.000',
  `total` decimal(18,3) DEFAULT '0.000',
  `total_au` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_dollar` decimal(18,3) NOT NULL DEFAULT '0.000',
  `jv_id` int DEFAULT NULL,
  `paid_jv_id` int DEFAULT NULL,
  `paid_au_jv_id` int DEFAULT NULL,
  `paid_dollar_jv_id` int DEFAULT NULL,
  `supplier_payment_id` int DEFAULT NULL,
  `supplier_au_payment_id` int DEFAULT NULL,
  `supplier_dollar_payment_id` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 for active, 0 for inactive',
  `is_posted` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratti_kaats`
--

INSERT INTO `ratti_kaats` (`id`, `ratti_kaat_no`, `purchase_date`, `supplier_id`, `purchase_account`, `paid`, `paid_account`, `paid_au`, `paid_account_au`, `paid_dollar`, `paid_account_dollar`, `reference`, `pictures`, `tax_amount`, `tax_account`, `sub_total`, `total`, `total_au`, `total_dollar`, `jv_id`, `paid_jv_id`, `paid_au_jv_id`, `paid_dollar_jv_id`, `supplier_payment_id`, `supplier_au_payment_id`, `supplier_dollar_payment_id`, `is_active`, `is_posted`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'RK-18122024-0001', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-18 21:24:17', '2024-12-18 21:24:17'),
(2, 'RK-18122024-0002', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-18 21:27:05', '2024-12-18 21:27:05'),
(3, 'RK-18122024-0003', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-18 21:28:53', '2024-12-18 21:28:53'),
(4, 'RK-19122024-0004', '2024-12-19', 16, 40, 0.000, NULL, 0.000, NULL, 0.000, NULL, 'Opening Inventory', '[]', NULL, NULL, 0.000, 0.000, 2.197, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 1, 1, 1, '2024-12-19 15:33:45', '2024-12-19 15:40:36'),
(5, 'RK-19122024-0005', '2024-12-19', 16, 61, 0.000, NULL, 0.000, NULL, 0.000, NULL, 'Opening Inventory', '[]', NULL, NULL, 0.000, 0.000, 2.197, 0.000, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 1, NULL, '2024-12-19 15:40:39', '2024-12-19 15:44:13'),
(6, 'RK-23122024-0006', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-23 15:21:42', '2024-12-23 15:21:42'),
(7, 'RK-23122024-0007', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-23 15:23:31', '2024-12-23 15:23:31'),
(8, 'RK-23122024-0008', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-23 18:41:49', '2024-12-23 18:41:49'),
(9, 'RK-23122024-0009', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-23 18:43:20', '2024-12-23 18:43:20'),
(10, 'RK-23122024-0010', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-23 18:46:34', '2024-12-23 18:46:34'),
(11, 'RK-23122024-0011', '2024-12-23', 8, 61, 0.000, 29, 0.000, 29, 0.000, 29, 'SS-Dec-2024', '[]', NULL, NULL, 0.000, 16799.436, 23.381, 0.000, 2, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 1, NULL, '2024-12-23 18:52:38', '2024-12-23 19:21:14'),
(12, 'RK-23122024-0012', '2024-12-23', 8, 61, 0.000, 0, 0.000, 0, 0.000, 0, 'SS-Nov-2024', '[]', NULL, NULL, 0.000, 3767.475, 5.537, 0.000, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 1, NULL, '2024-12-23 20:01:01', '2024-12-23 20:03:41'),
(13, 'RK-23122024-0013', '2024-12-23', 8, 61, 0.000, 0, 0.000, 0, 0.000, 0, 'SS-Nov-2024', '[]', NULL, NULL, 0.000, 3182.600, 3.942, 0.000, 4, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 1, NULL, '2024-12-23 20:21:41', '2024-12-23 20:25:56'),
(14, 'RK-23122024-0014', '2024-12-23', 12, 61, 0.000, 0, 0.000, 0, 0.000, 0, 'SS-OPN-2024', '[]', NULL, NULL, 0.000, 8444.500, 10.725, 0.000, 5, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 1, NULL, '2024-12-23 20:40:59', '2024-12-23 20:46:26'),
(15, 'RK-26122024-0015', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-26 14:57:37', '2024-12-26 14:57:37'),
(16, 'RK-26122024-0016', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-26 15:12:48', '2024-12-26 15:12:48'),
(17, 'RK-26122024-0017', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-26 15:15:38', '2024-12-26 15:15:38'),
(18, 'RK-26122024-0018', NULL, NULL, NULL, 0.000, NULL, 0.000, NULL, 0.000, NULL, NULL, NULL, NULL, NULL, 0.000, 0.000, 0.000, 0.000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, NULL, NULL, '2024-12-26 18:47:23', '2024-12-26 18:47:23'),
(19, 'RK-26122024-0019', '2024-12-21', 8, 61, 0.000, 0, 0.000, 0, 0.000, 0, 'SO-26122024-0005', '[]', NULL, NULL, 0.000, 0.000, 5.017, 0.000, 6, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 1, 1, NULL, '2024-12-26 18:47:55', '2024-12-26 18:49:04');

-- --------------------------------------------------------

--
-- Table structure for table `ratti_kaat_beads`
--

CREATE TABLE `ratti_kaat_beads` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ratti_kaat_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `beads` decimal(8,3) NOT NULL DEFAULT '0.000',
  `gram` decimal(8,3) NOT NULL DEFAULT '0.000',
  `carat` decimal(8,3) NOT NULL DEFAULT '0.000',
  `gram_rate` decimal(8,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(8,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(8,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratti_kaat_beads`
--

INSERT INTO `ratti_kaat_beads` (`id`, `type`, `ratti_kaat_id`, `product_id`, `beads`, `gram`, `carat`, `gram_rate`, `carat_rate`, `total_amount`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'Artificial', 10, 15, 10000.000, 45.010, 225.050, 60.570, 12.114, 2726.256, 0, 1, NULL, NULL, '2024-12-23 18:48:15', '2024-12-23 18:48:15'),
(2, 'Chinese', 11, 15, 0.000, 45.010, 225.050, 60.560, 12.112, 2725.806, 1, 1, NULL, 1, '2024-12-23 18:53:48', '2024-12-23 19:00:43'),
(3, 'Chinese', 11, 15, 0.000, 48.939, 244.695, 55.845, 11.169, 2732.998, 1, 1, NULL, 1, '2024-12-23 18:57:22', '2024-12-23 19:05:16'),
(4, 'Chinese', 11, 15, 0.000, 33.906, 169.530, 79.940, 15.988, 2710.446, 1, 1, NULL, 1, '2024-12-23 19:05:11', '2024-12-23 19:08:56'),
(5, 'Chinese', 11, 15, 0.000, 47.504, 237.520, 57.740, 11.548, 2742.881, 1, 1, NULL, 1, '2024-12-23 19:08:52', '2024-12-23 19:12:24'),
(6, 'Chinese', 11, 15, 0.000, 40.228, 201.140, 68.260, 13.652, 2745.963, 1, 1, NULL, 1, '2024-12-23 19:12:20', '2024-12-23 19:16:06'),
(7, 'Chinese', 11, 15, 0.000, 47.623, 238.115, 57.820, 11.564, 2753.562, 0, 1, NULL, NULL, '2024-12-23 19:16:02', '2024-12-23 19:16:02'),
(8, 'Artificial', 12, 15, 1.000, 50.233, 251.165, 75.000, 15.000, 3767.475, 0, 1, NULL, NULL, '2024-12-23 20:02:57', '2024-12-23 20:02:57'),
(9, 'Artificial', 13, 15, 1.000, 36.568, 182.840, 75.000, 15.000, 2742.600, 0, 1, NULL, NULL, '2024-12-23 20:24:14', '2024-12-23 20:24:14'),
(10, 'Chinese', 14, 15, 1.000, 55.260, 276.300, 75.000, 15.000, 4144.500, 0, 1, NULL, NULL, '2024-12-23 20:44:52', '2024-12-23 20:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `ratti_kaat_details`
--

CREATE TABLE `ratti_kaat_details` (
  `id` bigint UNSIGNED NOT NULL,
  `ratti_kaat_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scale_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `bead_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `stones_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `diamond_carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `net_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `supplier_kaat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `kaat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `approved_by` int DEFAULT NULL,
  `pure_payable` decimal(18,3) NOT NULL DEFAULT '0.000',
  `other_charge` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_bead_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_stones_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_diamond_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_dollar` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_finish_product` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratti_kaat_details`
--

INSERT INTO `ratti_kaat_details` (`id`, `ratti_kaat_id`, `product_id`, `description`, `scale_weight`, `bead_weight`, `stones_weight`, `diamond_carat`, `net_weight`, `supplier_kaat`, `kaat`, `approved_by`, `pure_payable`, `other_charge`, `total_bead_amount`, `total_stones_amount`, `total_diamond_amount`, `total_amount`, `total_dollar`, `is_finish_product`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 4, 6, 'Impure Purchase', 2.930, 0.000, 0.000, 0.000, 2.930, 24.000, 0.733, 1, 2.197, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 0, 1, 1, NULL, 1, '2024-12-19 15:38:57', '2024-12-19 15:39:42'),
(2, 4, 6, 'Impure Purchase', 2.930, 0.000, 0.000, 0.000, 2.930, 24.000, 0.733, 1, 2.197, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 0, 0, 1, NULL, NULL, '2024-12-19 15:39:42', NULL),
(3, 5, 6, 'From Impure Purchase', 2.930, 0.000, 0.000, 0.000, 2.930, 24.000, 0.733, 1, 2.197, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 1, 0, 1, 1, NULL, '2024-12-19 15:43:26', '2024-12-19 15:45:24'),
(4, 11, 15, 'Light Weight Short Sets', 50.639, 45.010, 1.220, 0.000, 4.409, 7.000, 0.321, 1, 4.088, 0.000, 2725.806, 73.889, 0.000, 2799.695, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:33', '2024-12-23 19:19:25'),
(5, 11, 15, 'Light Weight Short Set with Off white poot', 54.129, 48.939, 1.200, 0.000, 3.990, 7.000, 0.291, 1, 3.699, 0.000, 2732.998, 67.014, 0.000, 2800.012, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:33', '2024-12-23 19:19:25'),
(6, 11, 15, 'Light weight Short Set with white poot', 39.516, 33.906, 1.120, 0.000, 4.490, 7.000, 0.327, 1, 4.163, 0.000, 2710.446, 89.533, 0.000, 2799.979, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:33', '2024-12-23 19:19:25'),
(7, 11, 15, 'Light weight short set in Blue ', 53.174, 47.504, 0.990, 0.000, 4.680, 7.000, 0.341, 1, 4.339, 0.000, 2742.881, 57.163, 0.000, 2800.044, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:33', '2024-12-23 19:19:25'),
(8, 11, 15, 'Light Weight Short Set in Pearl Colour', 44.558, 40.228, 0.790, 0.000, 3.540, 7.000, 0.258, 1, 3.282, 0.000, 2745.963, 53.925, 0.000, 2799.888, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:33', '2024-12-23 19:19:25'),
(9, 11, 15, 'Light Weight Short Set in Emerald Green Colour', 52.533, 47.623, 0.800, 0.000, 4.110, 7.000, 0.300, 1, 3.810, 0.000, 2753.562, 46.256, 0.000, 2799.818, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:33', '2024-12-23 19:19:25'),
(10, 11, 15, 'Light Weight Short Set in Emerald Green Colour', 52.533, 47.623, 0.800, 0.000, 4.110, 7.000, 0.300, 1, 3.810, 0.000, 2753.562, 46.256, 0.000, 2799.818, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:56', '2024-12-23 19:19:25'),
(11, 11, 15, 'Light Weight Short Set in Pearl Colour', 44.558, 40.228, 0.790, 0.000, 3.540, 7.000, 0.258, 1, 3.282, 0.000, 2745.963, 53.925, 0.000, 2799.888, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:56', '2024-12-23 19:19:25'),
(12, 11, 15, 'Light weight short set in Blue ', 53.174, 47.504, 0.990, 0.000, 4.680, 7.000, 0.341, 1, 4.339, 0.000, 2742.881, 57.163, 0.000, 2800.044, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:56', '2024-12-23 19:19:25'),
(13, 11, 15, 'Light weight Short Set with white poot', 39.516, 33.906, 1.120, 0.000, 4.490, 7.000, 0.327, 1, 4.163, 0.000, 2710.446, 89.533, 0.000, 2799.979, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:56', '2024-12-23 19:19:25'),
(14, 11, 15, 'Light Weight Short Set with Off white poot', 54.129, 48.939, 1.200, 0.000, 3.990, 7.000, 0.291, 1, 3.699, 0.000, 2732.998, 67.014, 0.000, 2800.012, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:56', '2024-12-23 19:19:25'),
(15, 11, 15, 'Light Weight Short Sets', 50.639, 45.010, 1.220, 0.000, 4.409, 7.000, 0.321, 1, 4.088, 0.000, 2725.806, 73.889, 0.000, 2799.695, 0.000, 0, 1, 1, NULL, 1, '2024-12-23 19:17:56', '2024-12-23 19:19:25'),
(16, 11, 15, 'Light Weight Short Sets', 50.639, 45.010, 1.220, 0.000, 4.409, 7.000, 0.321, 1, 4.088, 0.000, 2725.806, 73.889, 0.000, 2799.695, 0.000, 0, 0, 1, NULL, NULL, '2024-12-23 19:19:25', NULL),
(17, 11, 15, 'Light Weight Short Set with Off white poot', 54.129, 48.939, 1.200, 0.000, 3.990, 7.000, 0.291, 1, 3.699, 0.000, 2732.998, 67.014, 0.000, 2800.012, 0.000, 0, 0, 1, NULL, NULL, '2024-12-23 19:19:25', NULL),
(18, 11, 15, 'Light weight Short Set with white poot', 39.516, 33.906, 1.120, 0.000, 4.490, 7.000, 0.327, 1, 4.163, 0.000, 2710.446, 89.533, 0.000, 2799.979, 0.000, 0, 0, 1, NULL, NULL, '2024-12-23 19:19:25', NULL),
(19, 11, 15, 'Light weight short set in Blue ', 53.174, 47.504, 0.990, 0.000, 4.680, 7.000, 0.341, 1, 4.339, 0.000, 2742.881, 57.163, 0.000, 2800.044, 0.000, 0, 0, 1, NULL, NULL, '2024-12-23 19:19:25', NULL),
(20, 11, 15, 'Light Weight Short Set in Pearl Colour', 44.558, 40.228, 0.790, 0.000, 3.540, 7.000, 0.258, 1, 3.282, 0.000, 2745.963, 53.925, 0.000, 2799.888, 0.000, 0, 0, 1, NULL, NULL, '2024-12-23 19:19:25', NULL),
(21, 11, 15, 'Light Weight Short Set in Emerald Green Colour', 52.533, 47.623, 0.800, 0.000, 4.110, 7.000, 0.300, 1, 3.810, 0.000, 2753.562, 46.256, 0.000, 2799.818, 0.000, 1, 0, 1, 1, NULL, '2024-12-23 19:19:25', '2024-12-23 19:37:25'),
(22, 12, 15, 'Short Set with Ruby and shampion proi', 56.273, 50.233, 0.000, 0.000, 6.040, 8.000, 0.503, 1, 5.537, 0.000, 3767.475, 0.000, 0.000, 3767.475, 0.000, 1, 0, 1, 1, NULL, '2024-12-23 20:03:32', '2024-12-23 20:18:39'),
(23, 13, 15, 'Purple short set', 42.628, 36.568, 1.760, 0.000, 4.300, 8.000, 0.358, 1, 3.942, 0.000, 2742.600, 440.000, 0.000, 3182.600, 0.000, 1, 0, 1, 1, NULL, '2024-12-23 20:25:47', '2024-12-23 20:32:35'),
(24, 14, 15, 'Eyes Set', 68.700, 55.260, 2.000, 0.000, 11.440, 6.000, 0.715, 1, 10.725, 1500.000, 4144.500, 2800.000, 0.000, 8444.500, 0.000, 0, 0, 1, NULL, NULL, '2024-12-23 20:46:16', NULL),
(25, 19, 8, '22K Gents ring', 5.070, 0.000, 0.000, 0.000, 5.070, 1.000, 0.053, 1, 5.017, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 1, 0, 1, 1, NULL, '2024-12-26 18:48:42', '2024-12-26 18:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `ratti_kaat_diamonds`
--

CREATE TABLE `ratti_kaat_diamonds` (
  `id` bigint UNSIGNED NOT NULL,
  `ratti_kaat_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `diamonds` decimal(8,3) NOT NULL DEFAULT '0.000',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clarity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carat` decimal(8,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_dollar` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratti_kaat_stones`
--

CREATE TABLE `ratti_kaat_stones` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ratti_kaat_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `stones` decimal(8,3) NOT NULL DEFAULT '0.000',
  `gram` decimal(8,3) NOT NULL DEFAULT '0.000',
  `carat` decimal(8,3) NOT NULL DEFAULT '0.000',
  `gram_rate` decimal(8,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(8,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(8,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratti_kaat_stones`
--

INSERT INTO `ratti_kaat_stones` (`id`, `category`, `type`, `ratti_kaat_id`, `product_id`, `stones`, `gram`, `carat`, `gram_rate`, `carat_rate`, `total_amount`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'Chinese Ruby', 'Artificial', 11, 15, 3.000, 1.220, 6.100, 60.565, 12.113, 73.889, 1, 1, NULL, 1, '2024-12-23 18:54:31', '2024-12-23 19:01:09'),
(2, 'Chinese Ruby', 'Chines Ruby', 11, 15, 3.000, 1.200, 6.000, 55.845, 11.169, 67.014, 1, 1, NULL, 1, '2024-12-23 19:00:15', '2024-12-23 19:05:59'),
(3, 'Chinese Sapphire', 'Artificial', 11, 15, 3.000, 1.120, 5.600, 79.940, 15.988, 89.533, 1, 1, NULL, 1, '2024-12-23 19:05:55', '2024-12-23 19:09:31'),
(4, 'Chinese Sapphire', 'Chinese Sapphire', 11, 15, 3.000, 0.990, 4.950, 57.740, 11.548, 57.163, 1, 1, NULL, 1, '2024-12-23 19:09:27', '2024-12-23 19:13:01'),
(5, 'Pearl', 'Artificial', 11, 15, 3.000, 0.790, 3.950, 68.260, 13.652, 53.925, 1, 1, NULL, 1, '2024-12-23 19:12:58', '2024-12-23 19:16:41'),
(6, 'Chinese Emerald', 'Chinese Emerald', 11, 15, 3.000, 0.800, 4.000, 57.820, 11.564, 46.256, 0, 1, NULL, NULL, '2024-12-23 19:16:37', '2024-12-23 19:16:37'),
(7, 'Chinese Ruby', 'Purple', 13, 15, 3.000, 1.760, 8.800, 250.000, 50.000, 440.000, 0, 1, NULL, NULL, '2024-12-23 20:25:21', '2024-12-23 20:25:21'),
(8, 'Chinese Ruby', 'Chinese', 14, 15, 100.000, 2.000, 10.000, 1400.000, 280.000, 2800.000, 0, 1, NULL, NULL, '2024-12-23 20:45:52', '2024-12-23 20:45:52');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2024-09-03 17:04:50', '2024-09-03 17:04:50'),
(2, 'Accountant', 'web', '2024-11-14 20:30:40', '2024-11-14 20:30:40'),
(3, 'Supplier/Karigar User', 'web', '2024-11-26 17:03:43', '2024-11-26 17:03:43'),
(4, 'Salesman', 'web', '2024-12-18 21:42:17', '2024-12-18 21:42:17');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(159, 1),
(160, 1),
(161, 1),
(162, 1),
(163, 1),
(164, 1),
(165, 1),
(166, 1),
(167, 1),
(168, 1),
(169, 1),
(170, 1),
(171, 1),
(172, 1),
(173, 1),
(174, 1),
(175, 1),
(176, 1),
(177, 1),
(178, 1),
(179, 1),
(180, 1),
(181, 1),
(182, 1),
(183, 1),
(184, 1),
(185, 1),
(186, 1),
(187, 1),
(188, 1),
(189, 1),
(190, 1),
(191, 1),
(20, 2),
(34, 2),
(40, 2),
(41, 2),
(56, 2),
(60, 2),
(61, 2),
(1, 3),
(113, 3),
(119, 3),
(120, 3),
(121, 3),
(122, 3),
(123, 3),
(124, 3),
(126, 3),
(188, 4);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_qty` decimal(18,3) DEFAULT NULL,
  `tax` decimal(18,3) DEFAULT NULL,
  `tax_amount` decimal(18,3) DEFAULT NULL,
  `sub_total` decimal(18,3) DEFAULT NULL,
  `total` decimal(18,3) DEFAULT NULL,
  `is_credit` tinyint(1) NOT NULL DEFAULT '0',
  `cash_amount` decimal(18,3) DEFAULT NULL,
  `bank_transfer_amount` decimal(18,3) DEFAULT NULL,
  `card_amount` decimal(18,3) DEFAULT NULL,
  `advance_amount` decimal(18,3) DEFAULT NULL,
  `gold_impure_amount` decimal(18,3) DEFAULT NULL,
  `total_received` decimal(18,3) DEFAULT NULL,
  `jv_id` int DEFAULT NULL,
  `posted` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `sale_no`, `sale_date`, `customer_id`, `customer_name`, `customer_cnic`, `customer_contact`, `customer_email`, `customer_address`, `total_qty`, `tax`, `tax_amount`, `sub_total`, `total`, `is_credit`, `cash_amount`, `bank_transfer_amount`, `card_amount`, `advance_amount`, `gold_impure_amount`, `total_received`, `jv_id`, `posted`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'SL-19122024-0001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, NULL, NULL, '2024-12-19 15:30:33', '2024-12-19 15:30:33'),
(2, 'SL-19122024-0002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, NULL, NULL, '2024-12-19 15:45:37', '2024-12-19 15:45:37'),
(3, 'SL-20122024-0003', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, NULL, NULL, '2024-12-21 00:52:42', '2024-12-21 00:52:42'),
(4, 'SL-23122024-0004', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 1, NULL, NULL, '2024-12-23 19:42:34', '2024-12-23 19:42:34'),
(5, 'SL-26122024-0005', '2024-12-24', 4, 'Mrs Masood', '', '03480006666', NULL, '', 1.000, NULL, NULL, NULL, 139000.526, 0, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, NULL, 0, 0, 1, 1, NULL, '2024-12-26 18:51:23', '2024-12-26 18:55:10');

-- --------------------------------------------------------

--
-- Table structure for table `sale_details`
--

CREATE TABLE `sale_details` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_id` int DEFAULT NULL,
  `job_purchase_detail_id` int DEFAULT NULL,
  `finish_product_id` int DEFAULT NULL,
  `ratti_kaat_id` int DEFAULT NULL,
  `ratti_kaat_detail_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `gold_carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `scale_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `bead_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `stones_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `diamond_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `net_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `waste` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gross_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `making` decimal(18,3) NOT NULL DEFAULT '0.000',
  `bead_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `stones_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `diamond_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_bead_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_stones_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_diamond_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `other_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gold_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_gold_price` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_details`
--

INSERT INTO `sale_details` (`id`, `sale_id`, `job_purchase_detail_id`, `finish_product_id`, `ratti_kaat_id`, `ratti_kaat_detail_id`, `product_id`, `gold_carat`, `scale_weight`, `bead_weight`, `stones_weight`, `diamond_weight`, `net_weight`, `waste`, `gross_weight`, `making`, `bead_price`, `stones_price`, `diamond_price`, `total_bead_price`, `total_stones_price`, `total_diamond_price`, `other_amount`, `gold_rate`, `total_gold_price`, `total_amount`, `is_deleted`, `createdby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, 5, 19, 25, 8, 22.000, 5.070, 0.000, 0.000, 0.000, 5.070, 1.000, 6.070, 5430.000, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 0.000, 22005.029, 133570.526, 139000.526, 0, 1, NULL, '2024-12-26 18:55:10', '2024-12-26 18:55:10');

-- --------------------------------------------------------

--
-- Table structure for table `sale_detail_beads`
--

CREATE TABLE `sale_detail_beads` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_detail_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `beads` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_detail_diamonds`
--

CREATE TABLE `sale_detail_diamonds` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_detail_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `diamonds` decimal(18,3) NOT NULL DEFAULT '0.000',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clarity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_detail_stones`
--

CREATE TABLE `sale_detail_stones` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_detail_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `stones` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gram_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `carat_rate` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total_amount` decimal(18,3) NOT NULL DEFAULT '0.000',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_orders`
--

CREATE TABLE `sale_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_order_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `total_qty` decimal(18,3) DEFAULT NULL,
  `gold_rate` decimal(18,3) DEFAULT NULL,
  `gold_rate_type_id` int DEFAULT NULL,
  `is_purchased` tinyint(1) NOT NULL DEFAULT '0',
  `is_saled` tinyint(1) NOT NULL DEFAULT '0',
  `is_complete` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_orders`
--

INSERT INTO `sale_orders` (`id`, `sale_order_no`, `sale_order_date`, `customer_id`, `warehouse_id`, `total_qty`, `gold_rate`, `gold_rate_type_id`, `is_purchased`, `is_saled`, `is_complete`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'SO-23122024-0001', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, '2024-12-23 15:00:12', '2024-12-23 15:00:12'),
(2, 'SO-23122024-0002', '2024-12-11', 2, 1, 1.000, 21300.000, 1, 1, 0, 0, 0, 1, 1, NULL, '2024-12-23 15:06:08', '2024-12-23 15:11:43'),
(3, 'SO-26122024-0003', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, '2024-12-26 14:58:19', '2024-12-26 14:58:19'),
(4, 'SO-26122024-0004', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 1, NULL, NULL, '2024-12-26 14:58:42', '2024-12-26 14:58:42'),
(5, 'SO-26122024-0005', '2024-12-19', 4, 1, 1.000, 24005.487, 1, 0, 0, 0, 0, 1, 1, NULL, '2024-12-26 15:10:50', '2024-12-26 15:12:41');

-- --------------------------------------------------------

--
-- Table structure for table `sale_order_details`
--

CREATE TABLE `sale_order_details` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `design_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `net_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `waste` decimal(18,3) NOT NULL DEFAULT '0.000',
  `gross_weight` decimal(18,3) NOT NULL DEFAULT '0.000',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_order_details`
--

INSERT INTO `sale_order_details` (`id`, `sale_order_id`, `product_id`, `category`, `design_no`, `net_weight`, `waste`, `gross_weight`, `description`, `is_deleted`, `createdby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 2, 15, '21K Chokar', 'NS000159', 44.900, 7.920, 52.820, 'Chokar Set addition of Center Flower', 0, 1, NULL, '2024-12-23 15:07:57', '2024-12-23 15:07:57'),
(2, 5, 8, '22K', 'GR0000', 5.000, 1.000, 6.000, 'Gents ring as picture shared', 0, 1, NULL, '2024-12-26 15:12:41', '2024-12-26 15:12:41');

-- --------------------------------------------------------

--
-- Table structure for table `stock_takings`
--

CREATE TABLE `stock_takings` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `posted` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_taking_details`
--

CREATE TABLE `stock_taking_details` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_taking_id` int DEFAULT NULL,
  `other_product_id` int DEFAULT NULL,
  `quantity_in_stock` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actual_quantity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stone_categories`
--

CREATE TABLE `stone_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stone_categories`
--

INSERT INTO `stone_categories` (`id`, `name`, `is_active`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'Ruby', 1, 0, 1, NULL, NULL, '2024-12-23 18:48:55', '2024-12-23 18:48:55'),
(2, 'Sapphire', 1, 0, 1, NULL, NULL, '2024-12-23 18:49:01', '2024-12-23 18:49:01'),
(3, 'Emerald', 1, 0, 1, NULL, NULL, '2024-12-23 18:49:08', '2024-12-23 18:49:08'),
(4, 'Pearl', 1, 0, 1, NULL, NULL, '2024-12-23 18:49:17', '2024-12-23 18:49:17'),
(5, 'Torquise', 1, 0, 1, NULL, NULL, '2024-12-23 18:49:30', '2024-12-23 18:49:30'),
(6, 'Garnet', 1, 0, 1, NULL, NULL, '2024-12-23 18:49:42', '2024-12-23 18:49:42'),
(7, 'Red Coral', 1, 0, 1, NULL, NULL, '2024-12-23 18:50:42', '2024-12-23 18:50:42'),
(8, 'Cat Eye', 1, 0, 1, NULL, NULL, '2024-12-23 18:51:29', '2024-12-23 18:51:29'),
(9, 'Chinese Ruby', 1, 0, 1, NULL, NULL, '2024-12-23 18:51:40', '2024-12-23 18:51:40'),
(10, 'Chinese Sapphire', 1, 0, 1, NULL, NULL, '2024-12-23 18:51:50', '2024-12-23 18:51:50'),
(11, 'Chinese Emerald', 1, 0, 1, NULL, NULL, '2024-12-23 18:52:03', '2024-12-23 18:52:03'),
(12, 'American Zircon', 1, 0, 1, NULL, NULL, '2024-12-24 16:33:15', '2024-12-24 16:33:15'),
(13, 'Shampion Zircon', 1, 0, 1, 1, NULL, '2024-12-24 16:33:25', '2024-12-24 16:35:32'),
(14, 'Purple Zircon', 1, 0, 1, NULL, NULL, '2024-12-24 16:35:13', '2024-12-24 16:35:13');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` int NOT NULL DEFAULT '0' COMMENT '0 for supplier, 1 for karigar and 2 for both',
  `account_id` int DEFAULT NULL,
  `account_au_id` int DEFAULT NULL,
  `account_dollar_id` int DEFAULT NULL,
  `gold_waste` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT 'waste/tola',
  `stone_waste` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT 'Stone Studding Waste',
  `kaat` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT 'kaat/tola',
  `bank_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact`, `cnic`, `company`, `type`, `account_id`, `account_au_id`, `account_dollar_id`, `gold_waste`, `stone_waste`, `kaat`, `bank_name`, `account_title`, `account_no`, `is_active`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(4, 'Abdul Majeed', '03228006800', NULL, NULL, 2, 10, 11, 12, 6.00, 0.35, 6.00, NULL, NULL, NULL, 1, 1, 1, 1, 1, '2024-09-27 17:43:43', '2024-11-19 20:19:29'),
(5, 'Usman Khan', '03210000000', '3520200000', 'Usman Imran', 2, 10, 11, 12, 6.50, 0.30, 6.50, 'UBL', 'Usman Azam', '00000000000000000', 1, 1, 1, NULL, 1, '2024-10-17 15:39:24', '2024-11-19 20:19:42'),
(6, 'Abid Karigar', '03214000000', NULL, NULL, 1, 10, 11, 12, 4.00, 0.25, 6.00, 'MZN Bank', 'Shahzad', '02700000000000', 1, 1, 1, NULL, 1, '2024-10-28 00:35:59', '2024-11-19 20:19:34'),
(7, 'Sohail Gold Smith', '03210000001', NULL, 'Sohail Goldsmith', 2, 10, 11, 12, 6.50, 0.30, 5.00, 'MZN Bank', 'Sohail SB', '0200010005500', 1, 1, 1, NULL, 1, '2024-11-07 16:28:28', '2024-11-19 20:19:38'),
(8, 'Abdul Majeed', '03228006800', NULL, 'Abdul Majeed RangMahal', 2, 41, 40, 42, 6.00, 0.35, 6.00, 'N/A', 'N/A', 'N/A', 1, 0, 1, 1, NULL, '2024-11-19 20:20:56', '2024-12-18 21:20:37'),
(9, 'Usman Khan', '03215645579', NULL, 'Usman and Imran Company', 2, 41, 40, 42, 6.50, 0.35, 6.00, 'UBL', 'Umar Azam', '1921', 1, 0, 1, 1, NULL, '2024-11-19 20:24:02', '2024-12-18 21:20:10'),
(10, 'Atique KHI', '03212280439', NULL, NULL, 2, 41, 40, 42, 6.00, 0.40, 7.50, 'Meezan Bank', 'Khawar Nawaz Khan', 'PK58MEZN0099240103210460', 1, 0, 1, 1, NULL, '2024-12-17 20:46:41', '2024-12-18 21:21:05'),
(11, 'M Zeeshan', '03214670059', NULL, NULL, 2, 41, 40, 42, 6.00, 0.40, 4.00, 'Meezan Bank', 'Muhammad Zeeshan', '02400103195984', 1, 0, 1, 1, NULL, '2024-12-17 20:49:10', '2024-12-18 21:21:31'),
(12, 'Shahzad', '03144024898', NULL, NULL, 2, 41, 40, 42, 2.88, 0.00, 0.00, 'NA', 'NA', 'NA', 1, 0, 1, 1, NULL, '2024-12-17 20:52:25', '2024-12-18 21:22:00'),
(13, 'Zahid Rafique', '03214077382', NULL, 'AR', 2, 41, 40, 42, 5.00, 0.30, 11.50, 'NA', 'NA', 'NA', 1, 0, 1, 1, NULL, '2024-12-17 20:53:38', '2024-12-18 21:19:40'),
(14, 'Shahrukh Rizwan', '03234023321', NULL, 'Annu Bhai', 2, 41, 40, 42, 6.00, 0.40, 6.00, 'Meezan Bank', 'Daniyal Shuja', 'PK61MEZN0002900107350624', 1, 0, 1, NULL, NULL, '2024-12-18 21:24:04', '2024-12-18 21:24:04'),
(15, 'Azeem HMY', '03004292477', NULL, 'HMY Labortary', 2, 41, 40, 42, 0.00, 0.00, 0.00, NULL, NULL, NULL, 1, 0, 1, NULL, NULL, '2024-12-18 22:01:08', '2024-12-18 22:01:08'),
(16, 'Opening', '03214799833', NULL, NULL, 2, 41, 40, 42, 5.00, 0.30, 6.00, NULL, NULL, NULL, 1, 0, 1, NULL, NULL, '2024-12-19 15:33:27', '2024-12-19 15:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_payments`
--

CREATE TABLE `supplier_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `account_id` int DEFAULT NULL,
  `payment_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_purchase_id` int DEFAULT NULL,
  `cheque_ref` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` int NOT NULL DEFAULT '0' COMMENT '0 for PKR, 1 for AU and 2 for Dollar',
  `tax` decimal(18,3) DEFAULT NULL,
  `tax_amount` decimal(18,3) DEFAULT NULL,
  `tax_account_id` int DEFAULT NULL,
  `sub_total` decimal(18,3) DEFAULT NULL,
  `total` decimal(18,3) DEFAULT NULL,
  `jv_id` int DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '0 for purchase, 1 for other sale',
  `date` date DEFAULT NULL,
  `other_product_id` int DEFAULT NULL,
  `qty` decimal(18,3) DEFAULT NULL,
  `unit_price` decimal(18,3) DEFAULT NULL,
  `other_purchase_id` int DEFAULT NULL,
  `other_sale_id` int DEFAULT NULL,
  `stock_taking_id` int DEFAULT NULL,
  `stock_taking_link_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` tinyint NOT NULL DEFAULT '0',
  `supplier_id` int DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `supplier_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@admin.com', NULL, '$2y$10$my0ITJsPEi//k75reS1dve8V8LlWQtP0hn/r2bsMbZYDCWwlYsU/i', 0, NULL, 'by9DFIJpPyj9duyFRZx2pD04ypDSABh5taJEprMibZ7OYgnxArVAekHb1X4B', NULL, '2024-09-03 18:50:18'),
(2, 'Accountant', 'hsuleman.acca@gmail.com', NULL, '$2y$10$XA/0bopiUKuAJn2oVDTyhOPuwfjH3.P/R0ZBLo2CZfB4ZsTdTZwoy', 0, NULL, NULL, '2024-11-14 20:31:14', '2024-11-14 20:31:14'),
(3, 'Majeed User', 'majeed@gmail.com', NULL, '$2y$10$a6v5vmS.EN3F6oTLKr1l8.1nk9R7QAAB7OzPj8BURWtBJk1WvKnOO', 0, 8, NULL, '2024-11-26 17:54:32', '2024-12-16 16:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `createdby_id` int DEFAULT NULL,
  `updatedby_id` int DEFAULT NULL,
  `deletedby_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `email`, `phone`, `address`, `is_deleted`, `createdby_id`, `updatedby_id`, `deletedby_id`, `created_at`, `updated_at`) VALUES
(1, 'Liberty Market', '', '', '', 0, 1, NULL, NULL, '2024-10-04 17:26:21', '2024-10-04 17:26:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bead_types`
--
ALTER TABLE `bead_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diamond_clarities`
--
ALTER TABLE `diamond_clarities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diamond_colors`
--
ALTER TABLE `diamond_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diamond_cuts`
--
ALTER TABLE `diamond_cuts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diamond_types`
--
ALTER TABLE `diamond_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dollar_rates`
--
ALTER TABLE `dollar_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `finish_products`
--
ALTER TABLE `finish_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `finish_product_beads`
--
ALTER TABLE `finish_product_beads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `finish_product_diamonds`
--
ALTER TABLE `finish_product_diamonds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `finish_product_stones`
--
ALTER TABLE `finish_product_stones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gold_impurity_purchases`
--
ALTER TABLE `gold_impurity_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gold_impurity_purchase_details`
--
ALTER TABLE `gold_impurity_purchase_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gold_rates`
--
ALTER TABLE `gold_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gold_rate_types`
--
ALTER TABLE `gold_rate_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_purchases`
--
ALTER TABLE `job_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_purchase_details`
--
ALTER TABLE `job_purchase_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_purchase_detail_beads`
--
ALTER TABLE `job_purchase_detail_beads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_purchase_detail_diamonds`
--
ALTER TABLE `job_purchase_detail_diamonds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_purchase_detail_stones`
--
ALTER TABLE `job_purchase_detail_stones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_tasks`
--
ALTER TABLE `job_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_task_activities`
--
ALTER TABLE `job_task_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_task_details`
--
ALTER TABLE `job_task_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journals`
--
ALTER TABLE `journals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_entry_details`
--
ALTER TABLE `journal_entry_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `other_products`
--
ALTER TABLE `other_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_product_units`
--
ALTER TABLE `other_product_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_purchases`
--
ALTER TABLE `other_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_purchase_details`
--
ALTER TABLE `other_purchase_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_sales`
--
ALTER TABLE `other_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_sale_details`
--
ALTER TABLE `other_sale_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratti_kaats`
--
ALTER TABLE `ratti_kaats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratti_kaat_beads`
--
ALTER TABLE `ratti_kaat_beads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratti_kaat_details`
--
ALTER TABLE `ratti_kaat_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratti_kaat_diamonds`
--
ALTER TABLE `ratti_kaat_diamonds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratti_kaat_stones`
--
ALTER TABLE `ratti_kaat_stones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_details`
--
ALTER TABLE `sale_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_detail_beads`
--
ALTER TABLE `sale_detail_beads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_detail_diamonds`
--
ALTER TABLE `sale_detail_diamonds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_detail_stones`
--
ALTER TABLE `sale_detail_stones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_orders`
--
ALTER TABLE `sale_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_order_details`
--
ALTER TABLE `sale_order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_takings`
--
ALTER TABLE `stock_takings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_taking_details`
--
ALTER TABLE `stock_taking_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stone_categories`
--
ALTER TABLE `stone_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `bead_types`
--
ALTER TABLE `bead_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `diamond_clarities`
--
ALTER TABLE `diamond_clarities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diamond_colors`
--
ALTER TABLE `diamond_colors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diamond_cuts`
--
ALTER TABLE `diamond_cuts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diamond_types`
--
ALTER TABLE `diamond_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dollar_rates`
--
ALTER TABLE `dollar_rates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finish_products`
--
ALTER TABLE `finish_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `finish_product_beads`
--
ALTER TABLE `finish_product_beads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `finish_product_diamonds`
--
ALTER TABLE `finish_product_diamonds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finish_product_stones`
--
ALTER TABLE `finish_product_stones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gold_impurity_purchases`
--
ALTER TABLE `gold_impurity_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gold_impurity_purchase_details`
--
ALTER TABLE `gold_impurity_purchase_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gold_rates`
--
ALTER TABLE `gold_rates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `gold_rate_types`
--
ALTER TABLE `gold_rate_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `job_purchases`
--
ALTER TABLE `job_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_purchase_details`
--
ALTER TABLE `job_purchase_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_purchase_detail_beads`
--
ALTER TABLE `job_purchase_detail_beads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_purchase_detail_diamonds`
--
ALTER TABLE `job_purchase_detail_diamonds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_purchase_detail_stones`
--
ALTER TABLE `job_purchase_detail_stones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_tasks`
--
ALTER TABLE `job_tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job_task_activities`
--
ALTER TABLE `job_task_activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_task_details`
--
ALTER TABLE `job_task_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `journals`
--
ALTER TABLE `journals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `journal_entry_details`
--
ALTER TABLE `journal_entry_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `other_products`
--
ALTER TABLE `other_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_product_units`
--
ALTER TABLE `other_product_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `other_purchases`
--
ALTER TABLE `other_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_purchase_details`
--
ALTER TABLE `other_purchase_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_sales`
--
ALTER TABLE `other_sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_sale_details`
--
ALTER TABLE `other_sale_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ratti_kaats`
--
ALTER TABLE `ratti_kaats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `ratti_kaat_beads`
--
ALTER TABLE `ratti_kaat_beads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ratti_kaat_details`
--
ALTER TABLE `ratti_kaat_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `ratti_kaat_diamonds`
--
ALTER TABLE `ratti_kaat_diamonds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratti_kaat_stones`
--
ALTER TABLE `ratti_kaat_stones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sale_details`
--
ALTER TABLE `sale_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sale_detail_beads`
--
ALTER TABLE `sale_detail_beads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_detail_diamonds`
--
ALTER TABLE `sale_detail_diamonds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_detail_stones`
--
ALTER TABLE `sale_detail_stones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_orders`
--
ALTER TABLE `sale_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sale_order_details`
--
ALTER TABLE `sale_order_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_takings`
--
ALTER TABLE `stock_takings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_taking_details`
--
ALTER TABLE `stock_taking_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stone_categories`
--
ALTER TABLE `stone_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
