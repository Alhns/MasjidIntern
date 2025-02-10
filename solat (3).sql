-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 11:05 AM
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
-- Database: `solat`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(8) NOT NULL,
  `user_id` int(8) NOT NULL,
  `date` date NOT NULL,
  `time` time(6) NOT NULL,
  `place` varchar(50) NOT NULL,
  `status_code` int(1) NOT NULL,
  `comment` varchar(100) NOT NULL,
  `reg_date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `edit_date` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `date`, `time`, `place`, `status_code`, `comment`, `reg_date`, `edit_date`) VALUES
(6, 2003, '2025-02-10', '11:33:00.000000', 'Bilik Meeting', 1, '', '2025-02-10 11:34:07.166997', '2025-02-10 03:39:27.187375');

-- --------------------------------------------------------

--
-- Table structure for table `daerah`
--

CREATE TABLE `daerah` (
  `daerah_id` varchar(20) NOT NULL,
  `daerah_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daerah`
--

INSERT INTO `daerah` (`daerah_id`, `daerah_name`) VALUES
('1', 'Daerah Seberang Perai Selatan'),
('2', 'Kota Setar');

-- --------------------------------------------------------

--
-- Table structure for table `form`
--

CREATE TABLE `form` (
  `form_id` int(8) NOT NULL,
  `date` date NOT NULL,
  `name` varchar(50) NOT NULL,
  `ic` varchar(12) NOT NULL,
  `phone_num` int(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `job` varchar(30) NOT NULL,
  `total_vote` int(4) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `status_code` int(1) DEFAULT NULL,
  `reg_date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `edit_date` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form`
--

INSERT INTO `form` (`form_id`, `date`, `name`, `ic`, `phone_num`, `address`, `job`, `total_vote`, `role`, `status_code`, `reg_date`, `edit_date`) VALUES
(7, '2025-02-08', 'amir haiqal', '011557854588', 1166587452, '', '', 37, NULL, 1, '2025-02-08 23:22:07.854847', '2025-02-08 16:05:56.264830'),
(8, '2025-02-08', 'fairus', '024457956548', 1166587453, '556 jalan atira', '', 42, 'Naib Pengerusi', 1, '2025-02-08 23:22:07.864371', '2025-02-09 21:38:48.207227'),
(9, '2025-02-08', 'amir haiqal', '011557854588', 1166587452, '', '', 32, NULL, 1, '2025-02-08 23:23:24.076983', '2025-02-08 16:06:00.254877'),
(10, '2025-02-08', 'amir haiqal', '011557854588', 1166587452, '', '', 42, NULL, 1, '2025-02-08 23:42:45.925413', '2025-02-08 16:06:01.386171'),
(11, '2025-02-08', 'amir haiqal', '011557854588', 1166587452, '', '', 42, NULL, 1, '2025-02-08 23:45:16.125549', '2025-02-08 16:06:02.585942'),
(12, '2025-02-08', 'afnan adil', '031125025111', 1166879546, 'taman lumbar', 'cikgu', 2, 'Naib Pengerusi', 1, '2025-02-08 23:45:16.143881', '2025-02-10 03:06:21.309567'),
(35, '2025-02-10', 'afnan adil', '031125025111', 1166879546, 'taman lumbar', 'cikgu', 12, 'Naib Pengerusi', NULL, '2025-02-10 11:40:34.026963', '2025-02-10 03:55:51.934877'),
(36, '2025-02-10', 'adam aidil', '020305020444', 1166587454, '559 jalan test', 'Freelancer', 42, NULL, NULL, '2025-02-10 11:40:34.037796', '2025-02-10 03:40:34.037796'),
(37, '2025-02-10', 'afnan adil', '031125025111', 1166879546, 'taman lumbar', 'cikgu', 16, 'Naib Pengerusi', NULL, '2025-02-10 11:44:06.629207', '2025-02-10 03:55:51.934877'),
(38, '2025-02-10', 'adam aidil', '020305020444', 1166587454, '559 jalan test', 'Freelancer', 18, NULL, NULL, '2025-02-10 11:44:06.639835', '2025-02-10 03:44:06.639835');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `level_id` int(1) NOT NULL,
  `level_desc` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`level_id`, `level_desc`) VALUES
(1, 'Masjid'),
(2, 'Pejabat Tinggi Agama');

-- --------------------------------------------------------

--
-- Table structure for table `masjid`
--

CREATE TABLE `masjid` (
  `masjid_id` varchar(20) NOT NULL,
  `masjid_name` varchar(30) NOT NULL,
  `daerah_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `masjid`
--

INSERT INTO `masjid` (`masjid_id`, `masjid_name`, `daerah_id`) VALUES
('1', 'Masjid  Tasek Cempedak', '1'),
('2', 'Masjid Zahir', '2'),
('3', 'Masjid Bukit Tambun', '2');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(8) NOT NULL,
  `username` varchar(20) NOT NULL,
  `pswd` varchar(20) NOT NULL,
  `name` varchar(60) NOT NULL,
  `masjid_id` varchar(20) NOT NULL,
  `ic` varchar(30) NOT NULL,
  `phone` int(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `job` varchar(30) DEFAULT NULL,
  `level_id` int(1) NOT NULL,
  `reg_date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `edit_date` timestamp(6) NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `pswd`, `name`, `masjid_id`, `ic`, `phone`, `address`, `job`, `level_id`, `reg_date`, `edit_date`) VALUES
(2002, 'fairus', 'fairus2', 'fairus', '2', '024457956548', 1166587453, '556 jalan atira', NULL, 2, '0000-00-00 00:00:00.000000', '2025-02-09 18:07:37.556156'),
(2003, 'afnan', 'afnan2', 'afnan adil', '1', '031125025111', 1166879546, 'taman lumbar', 'cikgu', 1, '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000'),
(2004, 'adam', 'adam2', 'adam aidil', '1', '020305020444', 1166587454, '559 jalan test', 'Freelancer', 1, '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000'),
(2005, 'mira', 'mira2', 'mira dila', '2', '98021202454', 1166548799, '599 jalan nuri', 'bartender', 2, '2025-02-09 05:20:16.000000', '2025-02-09 21:21:52.962218');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `daerah`
--
ALTER TABLE `daerah`
  ADD PRIMARY KEY (`daerah_id`);

--
-- Indexes for table `form`
--
ALTER TABLE `form`
  ADD PRIMARY KEY (`form_id`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `masjid`
--
ALTER TABLE `masjid`
  ADD PRIMARY KEY (`masjid_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `form`
--
ALTER TABLE `form`
  MODIFY `form_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
