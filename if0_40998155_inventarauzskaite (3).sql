-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: Mar 09, 2026 at 10:39 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40998155_inventarauzskaite`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `updated_at`) VALUES
(1, 'dfg', 'dfg', 'dfg', 'dfg', '2025-03-12 11:56:23', '2025-03-12 11:56:23'),
(2, 'dfg', 'dfg', 'dfg', 'dfg', '2025-03-12 11:58:26', '2025-03-12 11:58:26'),
(3, 'tyu', 'tyu', 'tyu', 'tyu', '2025-03-12 12:06:04', '2025-03-12 12:06:04'),
(4, '56756', '567', '567', '567', '2025-03-14 05:10:07', '2025-03-14 05:10:07'),
(5, 'fgh', 'fgh', 'fgh', 'fgh', '2025-03-14 05:12:09', '2025-03-14 05:12:09'),
(6, 'sdf', 'sdf', 'sdf', 'sdf', '2025-03-14 05:20:55', '2025-03-14 05:20:55'),
(7, 'ery', 'ery', 'ery', 'ery', '2025-03-14 05:26:20', '2025-03-14 05:26:20'),
(8, 'dfg', 'dfg', 'dfg', 'dfg', '2025-03-14 05:29:16', '2025-03-14 05:29:16'),
(9, 'ert', 'ert', 'ert', 'ert', '2025-03-14 05:29:37', '2025-03-14 05:29:37'),
(10, 'sdfg', 'wer', 'wer', 'wer', '2025-03-14 05:31:26', '2025-03-14 05:31:26'),
(11, 'wer', 'wer', 'wer', 'wer', '2025-03-14 05:32:03', '2025-03-14 05:32:03'),
(12, 'wsedf', 'wer', 'wer', 'wer', '2025-03-14 05:35:09', '2025-03-14 05:35:09'),
(13, 'sf', 'wer', 'wer', 'wer', '2025-03-14 05:36:54', '2025-03-14 05:36:54'),
(14, 'sdf', 'sdf', 'sdf', 'sdf', '2025-03-18 10:01:35', '2025-03-18 10:01:35'),
(15, 'wer', 'wer', 'wer', 'wer', '2025-03-18 10:19:27', '2025-03-18 10:19:27'),
(16, 'dfg', 'dfg', 'dfg', 'dfg', '2025-09-23 04:29:47', '2025-09-23 04:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `updated_at`, `type_id`) VALUES
(17, 'fghAAAAABBBBBCCCCCzzzzzzzzzzzzz', 'fgh@.llCCCCCsssaaaaa', 'sdfsdfsdAAAAA', 'sdfsdfsdAAAAAA', NULL, '2025-10-10 06:23:03', 2),
(20, 'sdfsdf', 'dfg@dd.ll', 'dfgh', 'dfg', '2025-10-07 05:12:51', '2025-10-07 05:12:51', 1),
(22, 'dfgdfgdf', 'sdf@fff.ll', 'cfgdcfgb', 'dfgdfg', '2025-10-07 05:15:40', '2025-10-07 05:15:40', 1),
(23, 'wewer', 'sdfsd@dfgdfg.ll', 'sdfsdf', 'sdfsdf', '2025-10-07 05:20:49', '2025-10-07 05:20:49', 3),
(24, 'asdasd', 'asdfsa@ff.ll', 'sdfgsdfsd', 'sdfsdf', '2025-10-07 05:23:55', '2025-10-07 05:23:55', 2),
(25, 'werwe', 'werwe@gg.ll', 'qwqwer', 'werwer', '2025-10-07 05:26:33', '2025-10-07 05:26:33', 2),
(26, 'sdfsdf', 'dfg@dd.ll', 'dfgh', 'dfg', '2025-10-07 06:01:28', '2025-10-07 06:01:28', 1),
(27, 'fgh', 'fgh@fgh.ll', 'fgfgh', 'fghfgh', '2025-10-07 06:04:23', '2025-10-07 06:04:23', 2),
(28, 'fgh', 'fgh@fgh.ll', 'fgfgh', 'fghfgh', '2025-10-07 06:05:11', '2025-10-07 06:05:11', 1),
(29, 'zzz', 'zzzz@ss.ll', 'ssss', 'ssss', '2025-10-10 05:22:32', '2025-10-10 05:22:32', 3),
(30, 'ssss', 'sss@ss.ll', 'sssss', 'ssss', '2025-10-10 05:24:38', '2025-10-10 05:24:38', 3),
(31, 'qqqq', 'qqqq@qq.xx', 'xxxxx', 'xxxx', '2025-10-10 05:25:44', '2025-10-10 05:25:44', 4),
(32, 'asd', 'asd@gg.ll', 'asd', 'asd', '2025-10-10 06:51:10', '2025-10-10 06:51:10', 3),
(33, 'dfgdfg', 'fgh@ggtg.ll', 'dfg', 'dfg', '2025-10-10 07:22:18', '2025-10-10 07:22:18', 3);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventara_kustiba`
--

CREATE TABLE `inventara_kustiba` (
  `kustiba_id` int(11) NOT NULL,
  `datums` date NOT NULL,
  `inventars_id` int(11) NOT NULL,
  `atbildigais_lietotajs_id` int(11) NOT NULL,
  `kustibas_veids_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `inventara_kustiba`
--

INSERT INTO `inventara_kustiba` (`kustiba_id`, `datums`, `inventars_id`, `atbildigais_lietotajs_id`, `kustibas_veids_id`) VALUES
(2, '2026-02-23', 3, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventars`
--

CREATE TABLE `inventars` (
  `inventars_id` int(11) NOT NULL,
  `nosaukums` varchar(30) NOT NULL,
  `apraksts` varchar(200) DEFAULT NULL,
  `statuss` varchar(25) DEFAULT NULL,
  `kategorija_id` int(11) NOT NULL,
  `telpas_id` int(11) NOT NULL,
  `atbildigais_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `inventars`
--

INSERT INTO `inventars` (`inventars_id`, `nosaukums`, `apraksts`, `statuss`, `kategorija_id`, `telpas_id`, `atbildigais_id`) VALUES
(3, 'ghkjdsh', 'dsadasd', 'dsad', 2, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategorija`
--

CREATE TABLE `kategorija` (
  `kategorija_id` int(11) NOT NULL,
  `nosaukums` varchar(50) NOT NULL,
  `apraksts` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `kategorija`
--

INSERT INTO `kategorija` (`kategorija_id`, `nosaukums`, `apraksts`) VALUES
(2, 'Mēbeles', 'saja kategorija ietilpst dazada veida mebeles ka kresli galdi uc.'),
(4, 'Grāmatas', 'Dotajā kategorijā ietilps grāmatas no bibiliotēkas');

-- --------------------------------------------------------

--
-- Table structure for table `kustibas_veidi`
--

CREATE TABLE `kustibas_veidi` (
  `kustibas_veids_id` int(11) NOT NULL,
  `nosaukums` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `apraksts` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lietotajs`
--

CREATE TABLE `lietotajs` (
  `lietotajs_id` int(11) NOT NULL,
  `lietotajvards` varchar(20) NOT NULL,
  `parole` varchar(35) NOT NULL,
  `admina_tiesibas` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `lietotajs`
--

INSERT INTO `lietotajs` (`lietotajs_id`, `lietotajvards`, `parole`, `admina_tiesibas`) VALUES
(1, 'Chay', '12345', 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '0001_01_01_000000_create_users_table', 1),
(5, '0001_01_01_000001_create_cache_table', 1),
(6, '0001_01_01_000002_create_jobs_table', 1),
(7, '2025_03_12_124111_create_contacts_table', 2),
(10, '2025_09_25_072152_create_data_table', 3),
(11, '2025_10_02_065444_create_data_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5JdaiUhLqkkeOzyD8V9211rZ2mnsmJtFHluAiAlA', NULL, '94.30.166.157', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 OPR/127.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNFUxZ3F3cUxVMXYxT0hpb3U2djdxdGYxUm5uZGFybVF0Q3BmUFJwMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vcmNiaW52ZW50YXJzLnBhZ2UuZ2QvdGVscGEiO3M6NToicm91dGUiO3M6MTA6InRlbHBhLmxhcGEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1772910514),
('buBeVYJOg1nhN4DXZyqUxvo062ePOOgiyprbjoVy', NULL, '80.232.220.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNmx6dDNqVkVUZVlVSzRVSVo5VVppM01GRGNrYlZHbWJqUW5BVmhLayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHBzOi8vcmNiaW52ZW50YXJzLnBhZ2UuZ2QvaW52ZW50YXJzLzMvZGV0YWlscyI7czo1OiJyb3V0ZSI7czoxNzoiaW52ZW50YXJzLmRldGFpbHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771921554),
('HoPdbdwPCajKrFVrYmZYOM51nAsPyTAqJiRDXNrO', NULL, '80.232.220.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY3MyTFNNeFZ3UFVhbDBGVHJ2U2M0bHBDdkpOTmdNSFdMcEl5ZFdPNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vcmNiaW52ZW50YXJzLnBhZ2UuZ2QvYXRyYXNhbmFzX3ZpZXRhIjtzOjU6InJvdXRlIjtzOjIwOiJhdHJhc2FuYXNfdmlldGEubGFwYSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771920712),
('iNem4oU7oWN6o1xUlzNOgk0TPoWknDmSO7NP7r6e', NULL, '80.232.220.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiazdFWFd5TjZ2QmFzV2JNMVR3cEM1NUQyMHNSRHZCNURNYm00WTFRUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vcmNiaW52ZW50YXJzLnBhZ2UuZ2QvaW52ZW50YXJhX2t1c3RpYmEiO3M6NToicm91dGUiO3M6MjI6ImludmVudGFyYV9rdXN0aWJhLmxhcGEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1772434828),
('QvMNAF8BIhnueP20QUlZnOXKHEMAmCI8zx6hfvQ2', NULL, '80.89.76.59', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 OPR/127.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS1Jzd3dJZXJOZkNFbUlWQ3NEZE5WbHFuWGhZdXJTeW5XMVJreHZ3aCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9yY2JpbnZlbnRhcnMucGFnZS5nZC9saWV0b3RhanMiO3M6NToicm91dGUiO3M6MTQ6ImxpZXRvdGFqaS5sYXBhIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771938506),
('tIkHbRDRq67B86vVVOKW5XuXIwwGcRiYRvGY0zs1', NULL, '80.232.220.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidUpFYnpJM1VtTjA1QjNFWGJJdm5rdjdNSlBid2ttRjBzdGpwemlYNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHBzOi8vcmNiaW52ZW50YXJzLnBhZ2UuZ2QvaW52ZW50YXJzIjtzOjU6InJvdXRlIjtzOjE0OiJpbnZlbnRhcnMubGFwYSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1771921117),
('wc9j33GdPpgmszbuN4W1d5fx62zYP2hWHpwMVldt', NULL, '80.89.76.59', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 OPR/127.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibGNITGprRzFheG9PZlliSndVRGdOTlJyRmNFdU9QVjlZQ2VOSG5mViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9yY2JpbnZlbnRhcnMucGFnZS5nZC9saWV0b3RhanMiO3M6NToicm91dGUiO3M6MTQ6ImxpZXRvdGFqaS5sYXBhIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771913903);

-- --------------------------------------------------------

--
-- Table structure for table `telpa`
--

CREATE TABLE `telpa` (
  `telpas_id` int(11) NOT NULL,
  `nosaukums` varchar(50) NOT NULL,
  `izmeri` varchar(10) DEFAULT NULL,
  `numurs` int(11) DEFAULT NULL,
  `stavs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `telpa`
--

INSERT INTO `telpa` (`telpas_id`, `nosaukums`, `izmeri`, `numurs`, `stavs`) VALUES
(1, 'Galvenā telpa', '20x40', 201, 0);

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `type`) VALUES
(1, 'Info'),
(2, 'Cena'),
(3, 'Ziņas'),
(4, 'Piedāvajums');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(4, 's', 'aaa68eceb0abbc13@example.lv', NULL, '$2y$12$I6BMGsHJz7sw2LlWYgmQL.MInHas9Y.4I1Z5RYd44DLIQbMtdLo7m', NULL, '2025-10-13 09:05:30', '2025-10-13 09:05:30'),
(5, 'q', 'aaa68eceb0eb62ed@example.lv', NULL, '$2y$12$tCeSgCQw4Bf1QqyqOQVuo.1G/tA1/n6cttQAiMknYzYhKpcrT1t2.', NULL, '2025-10-13 09:05:34', '2025-10-13 09:05:34'),
(6, 'a', 'aaa68ecec76b749f@example.lv', NULL, '$2y$12$ltnL1sEg65jPFul3lwqx3eORvIJ17HjaIhhjwInl6mIBummU9HfXm', NULL, '2025-10-13 09:11:34', '2025-10-13 09:11:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventara_kustiba`
--
ALTER TABLE `inventara_kustiba`
  ADD PRIMARY KEY (`kustiba_id`),
  ADD KEY `fk_kustiba_inventars` (`inventars_id`),
  ADD KEY `fk_kustiba_lietotajs` (`atbildigais_lietotajs_id`),
  ADD KEY `fk_kustibas_veids` (`kustibas_veids_id`);

--
-- Indexes for table `inventars`
--
ALTER TABLE `inventars`
  ADD PRIMARY KEY (`inventars_id`),
  ADD KEY `fk_inventars_kategorija` (`kategorija_id`),
  ADD KEY `atbildigais_id` (`atbildigais_id`),
  ADD KEY `telpa` (`telpas_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategorija`
--
ALTER TABLE `kategorija`
  ADD PRIMARY KEY (`kategorija_id`);

--
-- Indexes for table `kustibas_veidi`
--
ALTER TABLE `kustibas_veidi`
  ADD PRIMARY KEY (`kustibas_veids_id`);

--
-- Indexes for table `lietotajs`
--
ALTER TABLE `lietotajs`
  ADD PRIMARY KEY (`lietotajs_id`),
  ADD UNIQUE KEY `lietotajvards` (`lietotajvards`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `telpa`
--
ALTER TABLE `telpa`
  ADD PRIMARY KEY (`telpas_id`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventara_kustiba`
--
ALTER TABLE `inventara_kustiba`
  MODIFY `kustiba_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventars`
--
ALTER TABLE `inventars`
  MODIFY `inventars_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategorija`
--
ALTER TABLE `kategorija`
  MODIFY `kategorija_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kustibas_veidi`
--
ALTER TABLE `kustibas_veidi`
  MODIFY `kustibas_veids_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lietotajs`
--
ALTER TABLE `lietotajs`
  MODIFY `lietotajs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `telpa`
--
ALTER TABLE `telpa`
  MODIFY `telpas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data`
--
ALTER TABLE `data`
  ADD CONSTRAINT `data_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inventara_kustiba`
--
ALTER TABLE `inventara_kustiba`
  ADD CONSTRAINT `fk_kustibas_veids` FOREIGN KEY (`kustibas_veids_id`) REFERENCES `kustibas_veidi` (`kustibas_veids_id`),
  ADD CONSTRAINT `inventara_kustiba_ibfk_1` FOREIGN KEY (`inventars_id`) REFERENCES `inventars` (`inventars_id`),
  ADD CONSTRAINT `inventara_kustiba_ibfk_2` FOREIGN KEY (`atbildigais_lietotajs_id`) REFERENCES `lietotajs` (`lietotajs_id`);

--
-- Constraints for table `inventars`
--
ALTER TABLE `inventars`
  ADD CONSTRAINT `inventars_ibfk_1` FOREIGN KEY (`kategorija_id`) REFERENCES `kategorija` (`kategorija_id`),
  ADD CONSTRAINT `inventars_ibfk_2` FOREIGN KEY (`atbildigais_id`) REFERENCES `lietotajs` (`lietotajs_id`),
  ADD CONSTRAINT `inventars_ibfk_3` FOREIGN KEY (`telpas_id`) REFERENCES `telpa` (`telpas_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
