-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: Mar 18, 2026 at 03:39 AM
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
(2, '2026-02-23', 83, 1, 1, 1, 1, 'safdsgdgfdasdfsadasdasdasdasdasdasd', 'idk'),
(13, '2024-01-01', 44, 4, 1, 10, 9, 'Pārvietots uz citu telpu', ''),
(14, '2024-01-02', 45, 5, 1, 9, 1, 'Pārvietots uz lasītavu', ''),
(15, '2024-01-03', 46, 6, 5, 1, 1, 'Nosūtīts remontā', ''),
(16, '2024-01-04', 47, 5, 1, 5, 3, 'Pārvietots uz administrāciju', ''),
(17, '2024-01-05', 48, 7, 2, 3, 3, 'Izsniegts darbiniekam', ''),
(18, '2024-01-06', 49, 7, 2, 3, 3, 'Izsniegts darbiniekam', ''),
(19, '2024-01-07', 50, 8, 1, 9, 5, 'Pārvietots uz konferenču zāli', ''),
(20, '2024-01-08', 51, 9, 1, 1, 8, 'Pārvietots uz krātuvi', ''),
(21, '2024-01-09', 52, 10, 6, 6, 6, 'Saņemts un uzstādīts serveru telpā', ''),
(22, '2024-01-10', 53, 11, 7, 8, 8, 'Inventarizācijas pārbaude', '');

-- --------------------------------------------------------

--
-- Table structure for table `inventars`
--

CREATE TABLE `inventars` (
  `inventars_id` int(11) NOT NULL,
  `nosaukums` varchar(30) NOT NULL,
  `kategorija_id` int(11) NOT NULL,
  `telpas_id` int(11) NOT NULL,
  `atbildigais_id` int(11) DEFAULT NULL,
  `inventara_numurs` varchar(50) DEFAULT NULL,
  `iegades_datums` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `inventars`
--

INSERT INTO `inventars` (`inventars_id`, `nosaukums`, `kategorija_id`, `telpas_id`, `atbildigais_id`, `inventara_numurs`, `iegades_datums`) VALUES
(44, 'Dell dators', 7, 10, 4, 'INV001', '2023-01-01'),
(45, 'HP dators', 7, 9, 5, 'INV002', '2023-02-01'),
(46, 'Printeris HP', 8, 1, 6, 'INV003', '2022-05-01'),
(47, 'Projektors Epson', 10, 5, 5, 'INV004', '2021-03-01'),
(48, 'Krēsls', 9, 3, 7, 'INV005', '2020-01-01'),
(49, 'Galds', 9, 3, 7, 'INV006', '2020-02-01'),
(50, 'Monitors LG', 14, 9, 8, 'INV007', '2023-04-01'),
(51, 'Skeneris Canon', 12, 1, 9, 'INV008', '2022-06-01'),
(52, 'Maršrutētājs TP-Link', 11, 6, 10, 'INV009', '2023-07-01'),
(53, 'Plaukts', 13, 8, 11, 'INV010', '2019-01-01'),
(54, 'Dell dators', 7, 10, 4, 'INV001', '2023-01-01'),
(55, 'HP dators', 7, 9, 5, 'INV002', '2023-02-01'),
(56, 'Printeris HP', 8, 1, 6, 'INV003', '2022-05-01'),
(57, 'Projektors Epson', 10, 5, 5, 'INV004', '2021-03-01'),
(58, 'Krēsls', 9, 3, 7, 'INV005', '2020-01-01'),
(59, 'Galds', 9, 3, 7, 'INV006', '2020-02-01'),
(60, 'Monitors LG', 14, 9, 8, 'INV007', '2023-04-01'),
(61, 'Skeneris Canon', 12, 1, 9, 'INV008', '2022-06-01'),
(62, 'Maršrutētājs TP-Link', 11, 6, 10, 'INV009', '2023-07-01'),
(63, 'Plaukts', 13, 8, 11, 'INV010', '2019-01-01'),
(64, 'Dell dators', 7, 10, 4, 'INV001', '2023-01-01'),
(65, 'HP dators', 7, 9, 5, 'INV002', '2023-02-01'),
(66, 'Printeris HP', 8, 1, 6, 'INV003', '2022-05-01'),
(67, 'Projektors Epson', 10, 5, 5, 'INV004', '2021-03-01'),
(68, 'Krēsls', 9, 3, 7, 'INV005', '2020-01-01'),
(69, 'Galds', 9, 3, 7, 'INV006', '2020-02-01'),
(70, 'Monitors LG', 14, 9, 8, 'INV007', '2023-04-01'),
(71, 'Skeneris Canon', 12, 1, 9, 'INV008', '2022-06-01'),
(72, 'Maršrutētājs TP-Link', 11, 6, 10, 'INV009', '2023-07-01'),
(73, 'Plaukts', 13, 8, 11, 'INV010', '2019-01-01'),
(74, 'Dell dators', 7, 10, 4, 'INV001', '2023-01-01'),
(75, 'HP dators', 7, 9, 5, 'INV002', '2023-02-01'),
(76, 'Printeris HP', 8, 1, 6, 'INV003', '2022-05-01'),
(77, 'Projektors Epson', 10, 5, 5, 'INV004', '2021-03-01'),
(78, 'Krēsls', 9, 3, 7, 'INV005', '2020-01-01'),
(79, 'Galds', 9, 3, 7, 'INV006', '2020-02-01'),
(80, 'Monitors LG', 14, 9, 8, 'INV007', '2023-04-01'),
(81, 'Skeneris Canon', 12, 1, 9, 'INV008', '2022-06-01'),
(82, 'Maršrutētājs TP-Link', 11, 6, 10, 'INV009', '2023-07-01'),
(83, 'Plaukts', 13, 8, 11, 'INV010', '2019-01-01');

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
(6, 'juris', 'jjj'),
(7, 'Datori', 'Portatīvie un stacionārie'),
(8, 'Printeri', 'Drukas iekārtas'),
(9, 'Mēbeles', 'Galdi, krēsli'),
(10, 'Projektori', 'Prezentāciju tehnika'),
(11, 'Tīkls', 'Maršrutētāji, slēdži'),
(12, 'Skeneri', 'Dokumentu skeneri'),
(13, 'Plaukti', 'Grāmatu plaukti'),
(14, 'Monitori', 'Displeji'),
(15, 'Audio', 'Skaņas tehnika'),
(16, 'Cits', 'Pārējais inventārs'),
(17, 'Datori', 'Portatīvie un stacionārie'),
(18, 'Printeri', 'Drukas iekārtas'),
(19, 'Mēbeles', 'Galdi, krēsli'),
(20, 'Projektori', 'Prezentāciju tehnika'),
(21, 'Tīkls', 'Maršrutētāji, slēdži'),
(22, 'Skeneri', 'Dokumentu skeneri'),
(23, 'Plaukti', 'Grāmatu plaukti'),
(24, 'Monitori', 'Displeji'),
(25, 'Audio', 'Skaņas tehnika'),
(26, 'Cits', 'Pārējais inventārs');

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
(2, 'Pārvietošana uz servisu', 'Ja tiek lauzta'),
(3, 'Pārvietošana', 'Pārvietots uz citu telpu'),
(4, 'Izsniegts', 'Izsniegts darbiniekam'),
(5, 'Atgriezts', 'Atgriezts noliktavā'),
(6, 'Norakstīts', 'Izņemts no lietošanas'),
(7, 'Remonts', 'Nosūtīts remontā'),
(8, 'Saņemts', 'Jauns inventārs'),
(9, 'Inventarizācija', 'Pārbaude'),
(10, 'Mainīts', 'Nomainīta vieta'),
(11, 'Zudis', 'Pazudis'),
(12, 'Cits', 'Cita darbība');

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
  `amats` enum(' Direktors','Dir.Vietnieks','Vecākais bibliotekārs','Bibliotekārs') DEFAULT NULL,
  `aktivs` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `lietotajs`
--

INSERT INTO `lietotajs` (`lietotajs_id`, `lietotajvards`, `parole`, `admina_tiesibas`, `avatar`, `vards`, `uzvards`, `epasts`, `telefons`, `amats`, `aktivs`) VALUES
(1, 'Chay', '12345', 1, 'avatars/WGtFSi6NWSOWyMWxrFJQ1vx6ee4yLvGIXmqKEgPr.jpg', NULL, NULL, NULL, NULL, NULL, 1),
(2, 'Test1', '12345', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(4, 'admin', '12345', 1, '', 'Janis', 'Berzins', 'janis@rcb.lv', '20000001', '', 1),
(5, 'anna', '12345', 0, '', 'Anna', 'Kalnina', 'anna@rcb.lv', '20000002', 'Bibliotekārs', 1),
(6, 'peteris', '12345', 0, '', 'Peteris', 'Ozols', 'peteris@rcb.lv', '20000003', 'Bibliotekārs', 1),
(7, 'liga', '12345', 0, '', 'Liga', 'Liepa', 'liga@rcb.lv', '20000004', 'Bibliotekārs', 1),
(8, 'maris', '12345', 0, '', 'Maris', 'Krumins', 'maris@rcb.lv', '20000005', '', 1),
(9, 'eva', '12345', 0, '', 'Eva', 'Bite', 'eva@rcb.lv', '20000045', 'Bibliotekārs', 1),
(10, 'dace', '12345', 0, '', 'Dace', 'Ziedina', 'dace@rcb.lv', '20000007', '', 1),
(11, 'gatis', '12345', 0, '', 'Gatis', 'Vilks', 'gatis@rcb.lv', '20000008', '', 1),
(12, 'inese', '12345', 0, '', 'Inese', 'Egle', 'inese@rcb.lv', '20000009', 'Bibliotekārs', 1),
(13, 'test', '12345', 0, '', 'Test', 'Test', 'test@rcb.lv', '20000010', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Norakstishana`
--

CREATE TABLE `Norakstishana` (
  `norakstishana_id` int(11) NOT NULL,
  `inventara_id` int(11) NOT NULL,
  `norDatums` date NOT NULL,
  `iemesls` varchar(30) NOT NULL,
  `talaka_riciba` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telpa`
--

CREATE TABLE `telpa` (
  `telpas_id` int(11) NOT NULL,
  `nosaukums` varchar(50) NOT NULL,
  `platība` varchar(10) DEFAULT NULL,
  `numurs` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `telpa`
--

INSERT INTO `telpa` (`telpas_id`, `nosaukums`, `platība`, `numurs`) VALUES
(1, 'Galvenā telpa', '20x40', 201),
(2, 'Lasītava', '5x6', 101),
(3, 'Abonements', '4x5', 102),
(4, 'Administrācija', '4x4', 201),
(5, 'Bērnu nodaļa', '6x6', 103),
(6, 'Konferenču zāle', '8x6', 202),
(7, 'Serveru telpa', '3x3', 203),
(8, 'Arhīvs', '5x5', 204),
(9, 'Grāmatu krātuve', '7x6', 104),
(10, 'Datoru klase', '6x5', 105),
(11, 'Direktora kabinets', '4x4', 205),
(12, 'Lasītava', '5x6', 101),
(13, 'Abonements', '4x5', 102),
(14, 'Administrācija', '4x4', 201),
(15, 'Bērnu nodaļa', '6x6', 103),
(16, 'Konferenču zāle', '8x6', 202),
(17, 'Serveru telpa', '3x3', 203),
(18, 'Arhīvs', '5x5', 204),
(19, 'Grāmatu krātuve', '7x6', 104),
(20, 'Datoru klase', '6x5', 105),
(21, 'Direktora kabinets', '4x4', 205);

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
-- Indexes for table `Norakstishana`
--
ALTER TABLE `Norakstishana`
  ADD PRIMARY KEY (`norakstishana_id`),
  ADD KEY `inventara_id` (`inventara_id`);

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
  MODIFY `kustiba_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `inventars`
--
ALTER TABLE `inventars`
  MODIFY `inventars_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `kategorija`
--
ALTER TABLE `kategorija`
  MODIFY `kategorija_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `kustibas_veidi`
--
ALTER TABLE `kustibas_veidi`
  MODIFY `kustibas_veids_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lietotajs`
--
ALTER TABLE `lietotajs`
  MODIFY `lietotajs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `Norakstishana`
--
ALTER TABLE `Norakstishana`
  MODIFY `norakstishana_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `telpa`
--
ALTER TABLE `telpa`
  MODIFY `telpas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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

--
-- Constraints for table `Norakstishana`
--
ALTER TABLE `Norakstishana`
  ADD CONSTRAINT `Norakstishana_ibfk_1` FOREIGN KEY (`inventara_id`) REFERENCES `inventars` (`inventars_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
