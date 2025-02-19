-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2025 at 11:19 AM
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
  `edit_date` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `masjid_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `date`, `time`, `place`, `status_code`, `comment`, `reg_date`, `edit_date`, `masjid_id`) VALUES
(9, 2003, '2025-02-18', '00:00:00.000000', 'Bilik Meeting', 0, 'test', '2025-02-18 12:56:08.780402', '2025-02-19 09:54:22.066631', 1),
(12, 2003, '2025-02-19', '00:00:00.000000', 'sini', 1, '', '2025-02-19 18:00:30.954835', '2025-02-19 10:00:42.098955', 1);

-- --------------------------------------------------------

--
-- Table structure for table `daerah`
--

CREATE TABLE `daerah` (
  `daerah_id` varchar(20) NOT NULL,
  `daerah_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daerah`
--

INSERT INTO `daerah` (`daerah_id`, `daerah_name`) VALUES
('1', 'Daerah Seberang Perai Selatan'),
('2', 'Butterworth'),
('3', 'Seberang Perai Tengah'),
('4', 'Timor Laut'),
('5', 'Barat Daya'),
('6', 'Seberang Perai Utara (Kepala Batas)');

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
  `edit_date` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `verify_id_1` int(8) DEFAULT NULL,
  `verify_id_2` int(8) DEFAULT NULL,
  `verify_id_3` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form`
--

INSERT INTO `form` (`form_id`, `date`, `name`, `ic`, `phone_num`, `address`, `job`, `total_vote`, `role`, `status_code`, `reg_date`, `edit_date`, `verify_id_1`, `verify_id_2`, `verify_id_3`) VALUES
(98, '2025-02-19', 'mira dila', '980212024544', 1166548799, '599 jalan nuri', 'bartender', 32, 'Setiausaha', 4, '2025-02-19 18:02:40.039133', '2025-02-19 10:18:41.151318', 2005, 2007, 2008),
(99, '2025-02-19', 'afnan adil', '031125025111', 1166879546, 'taman lumbar', 'cikgu', 64, 'Pengerusi', 5, '2025-02-19 18:02:40.059820', '2025-02-19 10:18:40.209303', 2005, 2007, NULL),
(100, '2025-02-19', 'adam aidil', '020305020444', 1166587454, '559 jalan test', 'Freelancer', 0, 'Pengerusi', 4, '2025-02-19 18:04:33.792099', '2025-02-19 10:18:41.160631', 2005, 2007, 2008);

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
(2, 'Pejabat Tinggi Agama'),
(3, 'JHEPP');

-- --------------------------------------------------------

--
-- Table structure for table `masjid`
--

CREATE TABLE `masjid` (
  `masjid_id` int(20) NOT NULL,
  `masjid_name` varchar(30) NOT NULL,
  `daerah_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `masjid`
--

INSERT INTO `masjid` (`masjid_id`, `masjid_name`, `daerah_id`) VALUES
(1, 'Masjid  Tasek Cempedak', '1'),
(2, 'Masjid Zahir', '2'),
(3, 'Masjid Bukit Tambun', '2'),
(4, 'MASJID TASEK GELUGOR', '6'),
(5, 'MASJID AT-TAQWA, TAMAN BERTAM ', '6'),
(6, 'MASJID IBADUR RAHMAN SERI SERD', '6'),
(7, 'MASJID LAHAR TABUT', '6'),
(8, 'MASJID JAMEK AR-RAHMANI KAMPUN', '6'),
(9, 'MASJID AL-JAMIUL BADAWI', '6'),
(10, 'MASJID DATO SHEIKH ADNAN', '6'),
(11, 'MASJID AL-MUTTAQIN BAKAU TUA', '6'),
(12, 'MASJID SIMPANG TIGA TASEK GELU', '6'),
(13, 'MASJID AR-RAHMAH, KAMPONG SELA', '6'),
(14, 'MASJID JAMEK AL-ABIDIN PINANG ', '6'),
(15, 'MASJID NURUL IMAN KUBANG MENER', '6'),
(16, 'MASJID KAMPUNG TOK BEDU', '6'),
(17, 'MASJID JAMEK PONGSU SERIBU', '6'),
(18, 'MASJID JAMIUL HASANI PERMATANG', '6'),
(19, 'MASJID AL-ARIFI PAYA KELADI', '6'),
(20, 'MASJID JAMEK KAMPUNG BAHARU', '6'),
(21, 'MASJID JAMIUL SHARIF POKOK MAC', '6'),
(22, 'MASJID AR RAHMAN PERMATANG RAM', '6'),
(23, 'MASJID KUALA MUDA', '6'),
(24, 'MASJID RANTAU PANJANG', '6'),
(25, 'MASJID PERMATANG KERIANG', '6'),
(26, 'MASJID JAMI\'UL JALAL PAJAK SON', '6'),
(27, 'MASJID BAKAR KAPOR', '6'),
(28, 'MASJID BUMBUNG LIMA', '6'),
(29, 'MASJID KAMPUNG LEMBAH', '6'),
(30, 'MASJID KAMPUNG PADANG', '6'),
(31, 'MASJID KOTA AUR', '6'),
(32, 'MASJID LAHAR KEPAR', '6'),
(33, 'MASJID LAHAR MINYAK', '6'),
(34, 'MASJID LAHAR TAMBUN', '6'),
(35, 'MASJID LAHAR TIANG', '6'),
(36, 'MASJID PADANG BENGGALI', '6'),
(37, 'MASJID PANTAI KAMLOON', '6'),
(38, 'MASJID PERMATANG BENDAHARI', '6'),
(39, 'MASJID PERMATANG BOGAK', '6'),
(40, 'MASJID PERMATANG JANGGUS', '6'),
(41, 'MASJID PERMATANG KUANG', '6'),
(42, 'MASJID PERMATANG TINGGI A', '6'),
(43, 'MASJID PERMATANG TOK LABU', '6'),
(44, 'MASJID PULAU MERTAJAM', '6'),
(45, 'MASJID SUNGAI KEDAK', '6'),
(46, 'MASJID AL-ISLAH POKOK TAMPANG', '6'),
(47, 'MASJID PERMATANG SINTOK', '6'),
(48, 'MASJID PADANG BENGGALI', '6'),
(49, 'MASJID BANDAR PUTRA BERTAM', '6'),
(50, 'MASJID JAMEK AL-HIDAYAH JALAN ', '3'),
(51, 'MASJID JAMEK SEBERANG JAYA', '3'),
(52, 'MASJID TIMAH', '3'),
(53, 'MASJID KARIAH TAMAN PELANGI', '3'),
(54, 'MASJID KAMPUNG SETOL', '3'),
(55, 'MASJID ABU BAKAR AS-SIDDIQ, TA', '3'),
(56, 'MASJID HAJI SAAD', '3'),
(57, 'MASJID MENGKUANG SUNGAI LEMBU', '3'),
(58, 'MASJID TOKUN BAWAH', '3'),
(59, 'MASJID KAMPUNG PELET', '3'),
(60, 'MASJID BUKIT INDERA MUDA', '3'),
(61, 'MASJID BUKIT MINYAK DALAM (LEB', '3'),
(62, 'MASJID BUKIT MINYAK TEPI JALAN', '3'),
(63, 'MASJID BUKIT TEH', '3'),
(64, 'MASJID JAMEK AN-NUR GUAR PERAH', '3'),
(65, 'MASJID KAMPUNG BAHARU ALMA', '3'),
(66, 'MASJID BATU KAMPUNG PERTAMA', '3'),
(67, 'MASJID PAPAN KAMPUNG PERTAMA', '3'),
(68, 'MASJID ALMA', '3'),
(69, 'MASJID KEBUN SIREH', '3'),
(70, 'MASJID KUALA MENGKUANG', '3'),
(71, 'MASJID PERAI', '3'),
(72, 'MASJID PERMATANG JANGGUS', '3'),
(73, 'MASJID PERMATANG PAUH', '3'),
(74, 'MASJID TASEK JUNJUNG', '3'),
(75, 'MASJID TENGAH', '3'),
(76, 'MASJID PERMATANG BATU', '3'),
(77, 'MASJID SEMBILANG', '3'),
(78, 'MASJID KAMPUNG SEKOLAH JURU', '3'),
(79, 'MASJID PADANG LALANG', '3'),
(80, 'MASJID TOKUN ATAS', '3'),
(81, 'MASJID BAGAN NYIUR JURU', '3'),
(82, 'MASJID PERMATANG NIBONG', '3'),
(83, 'MASJID AL-MUSTAQIM (SEKOLAH SA', '3'),
(84, 'MASJID BALIK BUKIT', '3'),
(85, 'MASJID KUALA JURU', '3'),
(86, 'MASJID KUALA TASEK', '3'),
(87, 'MASJID KUBANG ULU', '3'),
(88, 'MASJID KUBANG SEMANG', '3'),
(89, 'MASJID MACHANG BUBUK', '3'),
(90, 'MASJID PADANG IBU', '3'),
(91, 'MASJID PERMATANG PASIR', '3'),
(92, 'MASJID SAMA GAGAH', '3'),
(93, 'MASJID SUNGAI RAMBAI', '3'),
(94, 'MASJID TUAN ABDULLAH', '3'),
(95, 'MASJID PENGKALAN TAMBANG', '3'),
(96, 'MASJID JAMEK ALMA JAYA', '3'),
(97, 'MASJID PERMATANG TINGGI', '3'),
(98, 'MASJID MENGKUANG TITI', '3');

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE `meeting` (
  `meeting_id` int(11) NOT NULL,
  `meeting_date` date NOT NULL,
  `meeting_time` time(6) NOT NULL,
  `meeting_place` varchar(50) NOT NULL,
  `meeting_part` varchar(50) NOT NULL,
  `form_id` int(8) NOT NULL,
  `masjid_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting`
--

INSERT INTO `meeting` (`meeting_id`, `meeting_date`, `meeting_time`, `meeting_place`, `meeting_part`, `form_id`, `masjid_id`) VALUES
(48, '2025-02-19', '18:16:00.000000', 'sini', 'amar', 99, 0),
(49, '2025-02-19', '18:16:00.000000', 'sini', 'adli', 99, 0),
(50, '2025-02-19', '18:16:00.000000', 'sini', 'rizal', 99, 0),
(51, '2025-02-19', '18:16:00.000000', 'sini', 'amar', 98, 0),
(52, '2025-02-19', '18:16:00.000000', 'sini', 'adli', 98, 0),
(53, '2025-02-19', '18:16:00.000000', 'sini', 'rizal', 98, 0),
(54, '2025-02-19', '18:16:00.000000', 'sini', 'amar', 100, 0),
(55, '2025-02-19', '18:16:00.000000', 'sini', 'adli', 100, 0),
(56, '2025-02-19', '18:16:00.000000', 'sini', 'rizal', 100, 0);

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
(2005, 'mira', 'mira2', 'mira dila', '1', '980212024544', 1166548799, '599 jalan nuri', 'bartender', 2, '2025-02-09 05:20:16.000000', '2025-02-11 06:27:30.105107'),
(2007, 'hakimi', 'hakimi2', 'hakimi alif', '1', '020534030555', 165578945, '604 jalan indah', NULL, 3, '0000-00-00 00:00:00.000000', '2025-02-15 14:45:30.865472'),
(2008, 'ammar', 'ammar2', 'ammar razali', '2', '02092302454', 185527634, '604 jalan aman', 'mengail', 4, '2025-02-18 18:37:20.000000', '2025-02-18 10:37:20.000000');

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
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`meeting_id`);

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
  MODIFY `booking_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `form`
--
ALTER TABLE `form`
  MODIFY `form_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `masjid`
--
ALTER TABLE `masjid`
  MODIFY `masjid_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `meeting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
