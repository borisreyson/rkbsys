-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2018 at 03:57 AM
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
('HRGA', 'IT', 'ZAIN', '2018-03-02 01:47:32'),
('HRGA', 'SHIPPING', 'ZAIN', '2018-03-03 08:12:27'),
('HRGA', 'LOGISTIK', 'admin', '2018-03-05 02:41:37'),
('HRGA', 'SECURITY', 'ZAIN', '2018-03-05 05:57:13'),
('HRGA', 'PURCHASING', 'admin', '2018-03-05 06:03:16');

-- --------------------------------------------------------

--
-- Table structure for table `e_rkb_detail`
--

CREATE TABLE `e_rkb_detail` (
  `no_rkb` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `part_name` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `part_number` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_entry` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `timelog` datetime NOT NULL,
  `remarks` varchar(100) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `e_rkb_detail`
--

INSERT INTO `e_rkb_detail` (`no_rkb`, `part_name`, `part_number`, `quantity`, `user_entry`, `timelog`, `remarks`) VALUES
('00001/ABP/RKB/IT/2018', 'laptop', 'type 008', 2, 'admin', '2018-06-20 07:05:45', 'warna gold'),
('00002/ABP/RKB/IT/2018', 'laptop', 'type 008', 2, 'admin', '2018-06-20 07:09:33', 'red'),
('00002/ABP/RKB/IT/2018', 'laptop', 'type 0083', 4, 'admin', '2018-06-20 07:09:33', 'warna gold');

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
('00001/ABP/RKB/IT/2018', 'HRGA', 'IT', '2018-06-20'),
('00002/ABP/RKB/IT/2018', 'HRGA', 'IT', '2018-06-20');

-- --------------------------------------------------------

--
-- Table structure for table `e_rkb_temp`
--

CREATE TABLE `e_rkb_temp` (
  `id_rkb` int(11) NOT NULL,
  `part_name` varchar(50) NOT NULL,
  `part_number` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `timelog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_entry` varchar(30) NOT NULL,
  `void` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 'HRGA', 'IT', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`sect`);

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
  MODIFY `id_rkb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `no` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
