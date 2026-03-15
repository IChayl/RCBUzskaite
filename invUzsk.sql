-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: Mar 15, 2026 at 02:15 PM
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
-- Table structure for table `inventara_kustiba`
--

CREATE TABLE `inventara_kustiba` (
  `kustiba_id` int(11) NOT NULL,
  `datums` date NOT NULL,
  `inventars_id` int(11) NOT NULL,
  `atbildigais_lietotajs_id` int(11) NOT NULL,
  `kustibas_veids_id` int(11) DEFAULT NULL,
  `veca_telpa_id` int(11) DEFAULT NULL,
  `jauna_telpa_id` int(11) DEFAULT NULL,
  `piezimes` varchar(255) DEFAULT NULL,
  `dokuments` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `inventara_kustiba`
--

INSERT INTO `inventara_kustiba` (`kustiba_id`, `datums`, `inventars_id`, `atbildigais_lietotajs_id`, `kustibas_veids_id`, `veca_telpa_id`, `jauna_telpa_id`, `piezimes`, `dokuments`) VALUES
(2, '2026-02-23', 3, 1, 1, NULL, NULL, NULL, NULL);

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
  `atbildigais_id` int(11) DEFAULT NULL,
  `inventara_numurs` varchar(50) DEFAULT NULL,
  `iegades_datums` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `inventars`
--

INSERT INTO `inventars` (`inventars_id`, `nosaukums`, `apraksts`, `statuss`, `kategorija_id`, `telpas_id`, `atbildigais_id`, `inventara_numurs`, `iegades_datums`) VALUES
(3, 'ghkjdsh', 'dsadasd', 'dsad', 2, 1, 1, NULL, NULL);

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
(4, 'Grāmatas', 'Dotajā kategorijā ietilps grāmatas no bibiliotēkas'),
(6, 'juris', 'jjj');

-- --------------------------------------------------------

--
-- Table structure for table `kustibas_veidi`
--

CREATE TABLE `kustibas_veidi` (
  `kustibas_veids_id` int(11) NOT NULL,
  `nosaukums` varchar(50) NOT NULL,
  `apraksts` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `kustibas_veidi`
--

INSERT INTO `kustibas_veidi` (`kustibas_veids_id`, `nosaukums`, `apraksts`) VALUES
(1, 'Norakstīšana', 'Tieada'),
(2, 'Pārvietošana uz servisu', 'Ja tiek lauzta');

-- --------------------------------------------------------

--
-- Table structure for table `lietotajs`
--

CREATE TABLE `lietotajs` (
  `lietotajs_id` int(11) NOT NULL,
  `lietotajvards` varchar(20) NOT NULL,
  `parole` varchar(35) NOT NULL,
  `admina_tiesibas` tinyint(1) DEFAULT 0,
  `avatar` varchar(255) DEFAULT NULL,
  `vards` varchar(50) DEFAULT NULL,
  `uzvards` varchar(50) DEFAULT NULL,
  `epasts` varchar(100) DEFAULT NULL,
  `telefons` varchar(20) DEFAULT NULL,
  `amats` varchar(50) DEFAULT NULL,
  `aktivs` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `lietotajs`
--

INSERT INTO `lietotajs` (`lietotajs_id`, `lietotajvards`, `parole`, `admina_tiesibas`, `avatar`, `vards`, `uzvards`, `epasts`, `telefons`, `amats`, `aktivs`) VALUES
(1, 'Chay', '12345', 1, 'avatars/Evnimt82XvFHnYFbE4oWTemwKXWOE0v6lW2rSc72.jpg', NULL, NULL, NULL, NULL, NULL, 1),
(2, 'Test1', 'Test123', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(3, 'Juris', '1234', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1);

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
(1, 'Galvenā telpa', '20x40', 201, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventara_kustiba`
--
ALTER TABLE `inventara_kustiba`
  ADD PRIMARY KEY (`kustiba_id`),
  ADD KEY `fk_kustiba_inventars` (`inventars_id`),
  ADD KEY `fk_kustiba_lietotajs` (`atbildigais_lietotajs_id`),
  ADD KEY `fk_kustibas_veids` (`kustibas_veids_id`),
  ADD KEY `fk_veca_telpa` (`veca_telpa_id`),
  ADD KEY `fk_jauna_telpa` (`jauna_telpa_id`);

--
-- Indexes for table `inventars`
--
ALTER TABLE `inventars`
  ADD PRIMARY KEY (`inventars_id`),
  ADD KEY `fk_inventars_kategorija` (`kategorija_id`),
  ADD KEY `atbildigais_id` (`atbildigais_id`),
  ADD KEY `telpa` (`telpas_id`);

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
-- Indexes for table `telpa`
--
ALTER TABLE `telpa`
  ADD PRIMARY KEY (`telpas_id`);

--
-- AUTO_INCREMENT for dumped tables
--

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
-- AUTO_INCREMENT for table `kategorija`
--
ALTER TABLE `kategorija`
  MODIFY `kategorija_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kustibas_veidi`
--
ALTER TABLE `kustibas_veidi`
  MODIFY `kustibas_veids_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lietotajs`
--
ALTER TABLE `lietotajs`
  MODIFY `lietotajs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `telpa`
--
ALTER TABLE `telpa`
  MODIFY `telpas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventara_kustiba`
--
ALTER TABLE `inventara_kustiba`
  ADD CONSTRAINT `fk_jauna_telpa` FOREIGN KEY (`jauna_telpa_id`) REFERENCES `telpa` (`telpas_id`),
  ADD CONSTRAINT `fk_kustibas_veids` FOREIGN KEY (`kustibas_veids_id`) REFERENCES `kustibas_veidi` (`kustibas_veids_id`),
  ADD CONSTRAINT `fk_veca_telpa` FOREIGN KEY (`veca_telpa_id`) REFERENCES `telpa` (`telpas_id`),
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
