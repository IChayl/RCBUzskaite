-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: Mar 29, 2026 at 08:23 AM
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
(2, '2026-02-23', 83, 7, 2, 1, 1, 'safdsgdgfdasdfsadasdasdasdasdasdasd', 'idk'),
(13, '2024-01-01', 44, 4, 5, 10, 9, 'Pārvietots uz citu telpu', NULL),
(14, '2024-01-02', 45, 5, 1, 9, 1, 'Pārvietots uz lasītavu', ''),
(15, '2024-01-03', 46, 6, 5, 1, 1, 'Nosūtīts remontā', ''),
(16, '2024-01-04', 47, 5, 1, 5, 3, 'Pārvietots uz administrāciju', ''),
(17, '2024-01-05', 48, 7, 2, 3, 3, 'Izsniegts darbiniekam', NULL),
(18, '2024-01-06', 49, 7, 2, 3, 3, 'Izsniegts darbiniekam', ''),
(19, '2024-01-07', 50, 8, 1, 9, 5, 'Pārvietots uz konferenču zāli', ''),
(20, '2024-01-08', 51, 9, 1, 1, 8, 'Pārvietots uz krātuvi', ''),
(21, '2024-01-09', 52, 10, 4, 6, 6, 'Saņemts un uzstādīts serveru telpā', NULL),
(22, '2024-01-10', 53, 11, 4, 8, 8, 'Inventarizācijas pārbaude', NULL);

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
(44, 'Personālais dators Dell OptiPl', 1, 10, 10, 'INV044', '2023-01-01'),
(45, 'Personālais dators HP ProDesk', 1, 9, 13, 'INV045', '2023-02-01'),
(46, 'Printeris HP LaserJet Pro M404', 1, 1, 13, 'INV046', '2022-05-01'),
(47, 'Projektors Epson EB-X49', 3, 5, 13, 'INV047', '2021-03-01'),
(48, 'Biroja krēsls Nowy Styl Comfor', 2, 3, 7, 'INV048', '2020-01-01'),
(49, 'Rakstāmgalds IKEA BEKANT 160x8', 2, 3, 7, 'INV049', '2020-02-01'),
(50, 'Monitors LG 24MP60G-B 24 collu', 1, 9, 8, 'INV050', '2023-04-01'),
(51, 'Skeneris Canon CanoScan LiDE 3', 1, 1, 9, 'INV051', '2022-06-01'),
(52, 'Maršrutētājs TP-Link Archer C6', 5, 6, 10, 'INV052', '2023-07-01'),
(53, 'Grāmatu plaukts IKEA BILLY 80x', 2, 8, 11, 'INV053', '2019-01-01'),
(54, 'Personālais dators Dell OptiPl', 1, 10, 4, 'INV054', '2023-01-01'),
(55, 'Personālais dators HP ProDesk ', 1, 9, 5, 'INV055', '2023-02-01'),
(56, 'Printeris HP LaserJet Pro M404', 1, 1, 6, 'INV056', '2022-05-01'),
(57, 'Projektors Epson EB-X49', 3, 5, 5, 'INV057', '2021-03-01'),
(58, 'Biroja krēsls Nowy Styl Comfor', 2, 3, 7, 'INV058', '2020-01-01'),
(59, 'Rakstāmgalds IKEA BEKANT 160x8', 2, 3, 7, 'INV059', '2020-02-01'),
(60, 'Monitors LG 24MP60G-B 24 collu', 1, 9, 8, 'INV060', '2023-04-01'),
(61, 'Skeneris Canon CanoScan LiDE 3', 1, 1, 9, 'INV061', '2022-06-01'),
(62, 'Maršrutētājs TP-Link Archer C6', 5, 6, 10, 'INV062', '2023-07-01'),
(63, 'Grāmatu plaukts IKEA BILLY 80x', 2, 8, 11, 'INV063', '2019-01-01'),
(64, 'Personālais dators Dell OptiPl', 1, 10, 4, 'INV064', '2023-01-01'),
(65, 'Personālais dators HP ProDesk ', 1, 9, 5, 'INV065', '2023-02-01'),
(66, 'Printeris HP LaserJet Pro M404', 1, 1, 6, 'INV066', '2022-05-01'),
(67, 'Projektors Epson EB-X49', 3, 5, 5, 'INV067', '2021-03-01'),
(68, 'Biroja krēsls Nowy Styl Comfor', 2, 3, 7, 'INV068', '2020-01-01'),
(69, 'Rakstāmgalds IKEA BEKANT 160x8', 2, 3, 7, 'INV069', '2020-02-01'),
(70, 'Monitors LG 24MP60G-B 24 collu', 1, 9, 8, 'INV070', '2023-04-01'),
(71, 'Skeneris Canon CanoScan LiDE 3', 1, 1, 9, 'INV071', '2022-06-01'),
(72, 'Maršrutētājs TP-Link Archer C6', 5, 6, 10, 'INV072', '2023-07-01'),
(73, 'Grāmatu plaukts IKEA BILLY 80x', 2, 8, 11, 'INV073', '2019-01-01'),
(74, 'Personālais dators Dell OptiPl', 1, 10, 4, 'INV074', '2023-01-01'),
(75, 'Personālais dators HP ProDesk ', 1, 9, 5, 'INV075', '2023-02-01'),
(76, 'Printeris HP LaserJet Pro M404', 1, 1, 6, 'INV076', '2022-05-01'),
(77, 'Projektors Epson EB-X49', 3, 5, 5, 'INV077', '2021-03-01'),
(78, 'Biroja krēsls Nowy Styl Comfor', 2, 3, 7, 'INV078', '2020-01-01'),
(79, 'Rakstāmgalds IKEA BEKANT 160x8', 2, 3, 7, 'INV079', '2020-02-01'),
(80, 'Monitors LG 24MP60G-B 24 collu', 1, 9, 8, 'INV080', '2023-04-01'),
(81, 'Skeneris Canon CanoScan LiDE 3', 1, 1, 9, 'INV081', '2022-06-01'),
(82, 'Maršrutētājs TP-Link Archer C6', 5, 6, 10, 'INV082', '2023-07-01'),
(83, 'Grāmatu plaukts IKEA BILLY 80x', 2, 8, 11, 'INV083', '2019-01-01');

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
(1, 'Datortehnika', 'Datori, portatīvie datori, printeri, skeneri un cita IT tehnika'),
(2, 'Mēbeles', 'Galdi, krēsli, skapji, plaukti un citas mēbeles'),
(3, 'Biroja tehnika', 'Kopētāji, laminatori, projektori un cita biroja tehnika'),
(4, 'Multimediju tehnika', 'Televizori, skaļruņi, mikrofoni, video tehnika'),
(5, 'Tīkla aprīkojums', 'Maršrutētāji, komutatori, kabeļi, WiFi ierīces'),
(6, 'Elektronika', 'UPS, pagarinātāji, lādētāji, barošanas bloki'),
(7, 'Telpu aprīkojums', 'Pulksteņi, lampas, ventilatori, sildītāji'),
(8, 'Drošības aprīkojums', 'Videokameras, signalizācija, piekļuves kontrole');

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
(1, 'Iegāde', 'Inventārs iegādāts'),
(2, 'Izsniegšana', 'Inventārs izsniegts lietošanai'),
(3, 'Atgriešana', 'Inventārs atgriezts noliktavā'),
(4, 'Remonts', 'Inventārs nodots remontā'),
(5, 'Pārvietošana', 'Inventārs pārvietots uz citu telpu');

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
  `amats` enum('Direktors','Dir.Vietnieks','Vecākais bibliotekārs','Bibliotekārs') DEFAULT NULL,
  `aktivs` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `lietotajs`
--

INSERT INTO `lietotajs` (`lietotajs_id`, `lietotajvards`, `parole`, `admina_tiesibas`, `avatar`, `vards`, `uzvards`, `epasts`, `telefons`, `amats`, `aktivs`) VALUES
(4, 'admin', '12345', 1, '', 'Janis', 'Berzins', 'janis@rcb.lv', '20000001', '', 1),
(5, 'anna', '12345', 0, '', 'Anna', 'Kalnina', 'anna@rcb.lv', '20000002', 'Bibliotekārs', 1),
(6, 'peteris', '12345', 0, '', 'Peteris', 'Ozols', 'peteris@rcb.lv', '20000003', 'Bibliotekārs', 1),
(7, 'liga', '12345', 0, '', 'Liga', 'Liepa', 'liga@rcb.lv', '20000004', 'Bibliotekārs', 1),
(8, 'maris', '12345', 0, '', 'Maris', 'Krumins', 'maris@rcb.lv', '20000005', '', 1),
(9, 'eva', '12345', 0, '', 'Eva', 'Bite', 'eva@rcb.lv', '20000045', 'Bibliotekārs', 1),
(10, 'dace', '12345', 0, '', 'Dace', 'Ziedina', 'dace@rcb.lv', '20000007', '', 1),
(11, 'gatis', '12345', 0, '', 'Gatis', 'Vilks', 'gatis@rcb.lv', '20000008', '', 1),
(12, 'inese', '12345', 0, '', 'Inese', 'Egle', 'inese@rcb.lv', '20000009', 'Bibliotekārs', 1),
(13, 'Test1', '12345', 0, '', 'Test', 'Test', 'test@rcb.lv', '20000010', NULL, 1),
(28, 'Chay', '12345', 1, 'avatars/LUyXXlleF4W5uuPs8bgHYGiF3uclhgAMRJ6X0NpI.png', 'Mareks', 'Rumjancevs', 'chay2007@inbox.lv', '25885030', 'Direktors', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Norakstishana`
--

CREATE TABLE `Norakstishana` (
  `norakstishana_id` int(11) NOT NULL,
  `inventara_id` int(11) NOT NULL,
  `norDatums` date NOT NULL,
  `iemesls` varchar(30) NOT NULL,
  `talaka_riciba` varchar(50) NOT NULL,
  `pieteikshanas_dat` date NOT NULL,
  `apstiprinashanas_dat` date NOT NULL,
  `akceptets` tinyint(1) NOT NULL,
  `pieteica_lietotajs_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `Norakstishana`
--

INSERT INTO `Norakstishana` (`norakstishana_id`, `inventara_id`, `norDatums`, `iemesls`, `talaka_riciba`, `pieteikshanas_dat`, `apstiprinashanas_dat`, `akceptets`, `pieteica_lietotajs_id`) VALUES
(1, 44, '2024-02-01', 'Nolietots dators, neatbilst pr', 'Utilizēt', '2024-01-25', '2024-02-02', 1, 1),
(2, 48, '2024-02-05', 'Salauzts krēsls', 'Izmest', '2024-02-01', '2024-02-06', 1, 1),
(3, 53, '2024-02-10', 'Vecs grāmatu plaukts, bojāts', 'Nodot utilizācijai', '2024-02-05', '2024-02-11', 1, 1),
(4, 46, '2024-02-15', 'Printeris bojāts, remonts nere', 'Utilizēt', '2024-02-10', '2024-02-16', 1, 1),
(5, 50, '2024-02-20', 'Monitors ar bojātu ekrānu', 'Likvidēt', '2024-02-18', '2024-02-21', 1, 1),
(6, 52, '2024-02-22', 'Maršrutētājs novecojis', 'Nomainīt pret jaunu', '2024-02-20', '2024-02-23', 1, 1),
(7, 49, '2024-02-25', 'Rakstāmgalds bojāts', 'Nodot pārstrādei', '2024-02-22', '2024-02-26', 1, 1),
(8, 47, '2024-03-01', 'Projektors nedarbojas', 'Izņemt no lietošanas', '2024-02-27', '2024-03-02', 1, 1),
(9, 51, '2024-03-05', 'Skeneris bojāts', 'Izmest', '2024-03-01', '2024-03-06', 1, 1),
(10, 45, '2024-03-10', 'Dators novecojis', 'Nodot utilizācijai', '2024-03-05', '2024-03-11', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `telpa`
--

CREATE TABLE `telpa` (
  `telpas_id` int(11) NOT NULL,
  `nosaukums` varchar(50) NOT NULL,
  `platiba` varchar(10) DEFAULT NULL,
  `numurs` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `telpa`
--

INSERT INTO `telpa` (`telpas_id`, `nosaukums`, `platiba`, `numurs`) VALUES
(1, 'Galvenā telpa', '800', 201),
(2, 'Lasītava', '30', 101),
(3, 'Abonements', '20', 102),
(4, 'Administrācija', '16', 201),
(5, 'Bērnu nodaļa', '36', 103),
(6, 'Konferenču zāle', '48', 202),
(7, 'Serveru telpa', '9', 203),
(8, 'Arhīvs', '25', 204),
(9, 'Grāmatu krātuve', '42', 104),
(10, 'Datoru klase', '30', 105),
(11, 'Direktora kabinets', '16', 205);

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
  MODIFY `inventars_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `kategorija`
--
ALTER TABLE `kategorija`
  MODIFY `kategorija_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lietotajs`
--
ALTER TABLE `lietotajs`
  MODIFY `lietotajs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `Norakstishana`
--
ALTER TABLE `Norakstishana`
  MODIFY `norakstishana_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  ADD CONSTRAINT `fk_veca_telpa` FOREIGN KEY (`veca_telpa_id`) REFERENCES `telpa` (`telpas_id`),
  ADD CONSTRAINT `inventara_kustiba_ibfk_1` FOREIGN KEY (`inventars_id`) REFERENCES `inventars` (`inventars_id`),
  ADD CONSTRAINT `inventara_kustiba_ibfk_2` FOREIGN KEY (`atbildigais_lietotajs_id`) REFERENCES `lietotajs` (`lietotajs_id`),
  ADD CONSTRAINT `inventara_kustiba_ibfk_3` FOREIGN KEY (`kustibas_veids_id`) REFERENCES `kustibas_veidi` (`kustibas_veids_id`);

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
