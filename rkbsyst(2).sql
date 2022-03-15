-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2018 at 11:58 AM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rkbsyst`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dept` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `sect` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `user_entry` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `timelog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept`, `sect`, `user_entry`, `timelog`) VALUES
('HRGA', 'IT', 'ZAIN', '2018-06-21 22:59:49'),
('HRGA', 'SHIPPING', 'ZAIN', '2018-03-03 08:12:27'),
('HRGA', 'LOGISTIK', 'admin', '2018-06-21 22:09:59'),
('HRGA', 'SECURITY', 'ZAIN', '2018-03-05 05:57:13'),
('HRGA', 'PURCHASING', 'admin', '2018-03-05 06:03:16');

-- --------------------------------------------------------

--
-- Table structure for table `e_rkb_approve`
--

CREATE TABLE `e_rkb_approve` (
  `no_rkb` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `disetujui` char(1) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `tgl_disetujui` timestamp NULL DEFAULT NULL,
  `diketahui` char(1) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `tgl_diketahui` timestamp NULL DEFAULT NULL,
  `cancel_user` char(1) NOT NULL DEFAULT '0',
  `tgl_cancel_user` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `e_rkb_approve`
--

INSERT INTO `e_rkb_approve` (`no_rkb`, `disetujui`, `tgl_disetujui`, `diketahui`, `tgl_diketahui`, `cancel_user`, `tgl_cancel_user`) VALUES
('00001/ABP/RKB/IT/2018', '0', NULL, '0', NULL, '0', NULL),
('00002/ABP/RKB/IT/2018', '0', NULL, '0', NULL, '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `e_rkb_detail`
--

CREATE TABLE `e_rkb_detail` (
  `no_rkb` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `part_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `part_number` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `satuan` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `user_entry` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `timelog` datetime NOT NULL,
  `remarks` varchar(100) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `e_rkb_detail`
--

INSERT INTO `e_rkb_detail` (`no_rkb`, `part_name`, `part_number`, `quantity`, `satuan`, `user_entry`, `timelog`, `remarks`) VALUES
('00001/ABP/RKB/IT/2018', 'Mikrotik', 'RB1100AH', 3, 'PCS', 'admin', '2018-06-22 07:08:27', 'Mikrotik'),
('00001/ABP/RKB/IT/2018', 'HUB', 'HUB', 2, 'PCS', 'admin', '2018-06-22 07:08:27', 'HUB'),
('00002/ABP/RKB/IT/2018', 'SWITCH', 'MIKROTIK SWITCH', 6, 'PCS', 'admin', '2018-06-22 07:10:52', 'gdgdgd'),
('00002/ABP/RKB/IT/2018', 'Laptop', 'Asus', 1, 'PCS', 'admin', '2018-06-22 07:10:52', 'ASUS');

-- --------------------------------------------------------

--
-- Table structure for table `e_rkb_header`
--

CREATE TABLE `e_rkb_header` (
  `no_rkb` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `dept` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `section` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `tgl_order` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `e_rkb_header`
--

INSERT INTO `e_rkb_header` (`no_rkb`, `dept`, `section`, `tgl_order`) VALUES
('00001/ABP/RKB/IT/2018', 'HRGA', 'IT', '2018-06-22'),
('00002/ABP/RKB/IT/2018', 'HRGA', 'IT', '2018-06-22');

-- --------------------------------------------------------

--
-- Table structure for table `e_rkb_history`
--

CREATE TABLE `e_rkb_history` (
  `part_name` varchar(50) NOT NULL,
  `part_number` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `satuan` varchar(25) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `timelog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_entry` varchar(30) NOT NULL,
  `void` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `e_rkb_history`
--

INSERT INTO `e_rkb_history` (`part_name`, `part_number`, `quantity`, `satuan`, `remarks`, `timelog`, `user_entry`, `void`) VALUES
('Mikrotik', 'RB1100AH', 2, 'PCS', 'ROUTER BOARD', '2018-06-21 23:07:17', 'admin', 2),
('SWITCH', 'MIKROTIK SWITCH', 4, 'PCS', 'MIKROTIK', '2018-06-21 23:07:20', 'admin', 2),
('Mikrotik', 'RB1100AH', 3, 'PCS', 'Mikrotik', '2018-06-21 23:10:17', 'admin', 2),
('HUB', 'HUB', 2, 'PCS', 'HUB', '2018-06-21 23:10:19', 'admin', 2),
('Mikrotik', 'MIKROTIK SWITCH', 1, 'PCS', 'gfhhfghf', '2018-06-22 01:10:26', 'admin', 2),
('Mikrotik', 'MIKROTIK SWITCH', 3, 'PCS', 'trthrhr', '2018-06-22 01:10:26', 'admin', 2),
('Mikrotik', 'MIKROTIK SWITCH', 1, 'PCS', 'dadsa', '2018-06-22 01:10:26', 'admin', 2),
('Mikrotik', 'MIKROTIK SWITCH', 1, 'PCS', 'dadsa', '2018-06-22 01:10:27', 'admin', 2),
('Mikrotik', 'MIKROTIK SWITCH', 1, 'PCS', 'dadsa', '2018-06-22 01:10:27', 'admin', 2),
('Mikrotik', 'MIKROTIK SWITCH', 1, 'PCS', 'dss', '2018-06-22 01:10:27', 'admin', 2),
('SWITCH', 'fsdfs', 1, 'PCS', 'fsfdsfs', '2018-06-22 01:10:27', 'admin', 2),
('SWITCH', 'MIKROTIK SWITCH', 1, 'PCS', 'zxxccdz', '2018-06-22 01:13:59', 'admin', 2);

-- --------------------------------------------------------

--
-- Table structure for table `e_rkb_penawaran`
--

CREATE TABLE `e_rkb_penawaran` (
  `no_rkb` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `part_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `file` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `user_entry` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `timelog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `e_rkb_penawaran`
--

INSERT INTO `e_rkb_penawaran` (`no_rkb`, `part_name`, `file`, `user_entry`, `timelog`) VALUES
(NULL, 'Mikrotik', 'Mikrotik_0.png', 'admin', '2018-06-22 01:01:33'),
(NULL, 'Mikrotik', 'Mikrotik_0.png', 'admin', '2018-06-22 01:01:57'),
(NULL, 'Mikrotik', 'Mikrotik_0.png', 'admin', '2018-06-22 01:03:07'),
(NULL, 'Mikrotik', 'Mikrotik_0.png', 'admin', '2018-06-22 01:04:12'),
(NULL, 'SWITCH', 'SWITCH_0.png', 'admin', '2018-06-22 01:05:06'),
(NULL, 'Mikrotik', 'Mikrotik_0.png', 'admin', '2018-06-22 01:10:05'),
(NULL, 'SWITCH', 'SWITCH_0.png', 'admin', '2018-06-22 01:12:35'),
(NULL, 'Mikrotik', 'Mikrotik_0.png', 'admin', '2018-06-22 01:14:34'),
(NULL, 'Mikrotik', 'Mikrotik_1.png', 'admin', '2018-06-22 01:14:34');

-- --------------------------------------------------------

--
-- Table structure for table `e_rkb_temp`
--

CREATE TABLE `e_rkb_temp` (
  `id_rkb` bigint(20) NOT NULL,
  `part_name` varchar(50) NOT NULL,
  `part_number` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `satuan` varchar(25) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `timelog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_entry` varchar(30) NOT NULL,
  `void` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `e_rkb_temp`
--

INSERT INTO `e_rkb_temp` (`id_rkb`, `part_name`, `part_number`, `quantity`, `satuan`, `remarks`, `timelog`, `user_entry`, `void`) VALUES
(15, 'Mikrotik', 'MIKROTIK SWITCH', 1, 'PCS', 'hkh', '2018-06-22 01:14:34', 'admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `no` bigint(20) NOT NULL,
  `satuannya` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `user_entry` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `timelog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`no`, `satuannya`, `user_entry`, `timelog`) VALUES
(1, 'KG', 'admin', '2018-03-05 08:35:44'),
(2, 'PCS', 'admin', '2018-03-05 08:37:30'),
(3, 'METER', 'admin', '2018-03-05 08:37:44'),
(4, 'UNIT', 'admin', '2018-03-05 08:38:00'),
(5, 'BOX', 'admin', '2018-03-05 08:39:13'),
(6, 'RIM', 'admin', '2018-03-05 08:39:42'),
(7, 'LTR', 'admin', '2018-03-05 08:40:07'),
(8, 'SET', 'admin', '2018-03-05 08:40:35'),
(9, 'BAL', 'admin', '2018-03-05 08:40:39'),
(10, 'ROLL', 'admin', '2018-03-05 08:41:01'),
(11, 'AMPUL', 'admin', '2018-03-05 08:41:28'),
(12, 'BAG', 'admin', '2018-03-05 08:41:44'),
(13, 'BOTOL', 'admin', '2018-03-05 08:41:50'),
(14, 'PERANGKAT', 'admin', '2018-03-05 08:42:55'),
(15, 'KALENG', 'admin', '2018-03-05 09:26:41');

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `id_user` int(5) NOT NULL,
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `nama_lengkap` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `department` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `section` varchar(15) COLLATE latin1_general_ci NOT NULL COMMENT 'section',
  `level` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `id_session` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `status` varchar(30) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`id_user`, `username`, `password`, `nama_lengkap`, `department`, `section`, `level`, `id_session`, `status`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 'HRGA', 'IT', '', '', ''),
(2, 'jetty', '164c88b302622e17050af52c89945d44', 'Jetty', 'HRGA', 'SHIPPING', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`sect`);

--
-- Indexes for table `e_rkb_approve`
--
ALTER TABLE `e_rkb_approve`
  ADD PRIMARY KEY (`no_rkb`);

--
-- Indexes for table `e_rkb_header`
--
ALTER TABLE `e_rkb_header`
  ADD PRIMARY KEY (`no_rkb`);

--
-- Indexes for table `e_rkb_temp`
--
ALTER TABLE `e_rkb_temp`
  ADD PRIMARY KEY (`id_rkb`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `e_rkb_temp`
--
ALTER TABLE `e_rkb_temp`
  MODIFY `id_rkb` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
