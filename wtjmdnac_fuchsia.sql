-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2025 at 09:55 AM
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
-- Database: `wtjmdnac_fuchsia`
--

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `albumID` int(11) NOT NULL,
  `albumName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `UPCNum` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `storeID` varchar(255) DEFAULT '[]',
  `userID` int(11) NOT NULL,
  `artID` varchar(255) DEFAULT NULL,
  `createdDate` datetime DEFAULT current_timestamp(),
  `relDate` date DEFAULT NULL,
  `compLine` varchar(255) DEFAULT NULL,
  `publishLine` varchar(255) DEFAULT NULL,
  `trackID` varchar(255) DEFAULT '[]',
  `versionLine` varchar(255) DEFAULT NULL,
  `cyear` int(11) DEFAULT NULL,
  `pyear` int(11) DEFAULT NULL,
  `orgReldate` date DEFAULT NULL,
  `beatport` varchar(255) DEFAULT NULL,
  `artPrev` varchar(255) DEFAULT NULL,
  `staffID` int(11) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL COMMENT 'Reason for rejection when status is 2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`albumID`, `albumName`, `UPCNum`, `status`, `storeID`, `userID`, `artID`, `createdDate`, `relDate`, `compLine`, `publishLine`, `trackID`, `versionLine`, `cyear`, `pyear`, `orgReldate`, `beatport`, `artPrev`, `staffID`, `rejection_reason`) VALUES
(2, 'sadas', '5063248317359', 2, '[]', 1, 'https://i.ibb.co/Ltkbn1N/4d818baf946b.jpg', '2025-01-27 21:46:28', '2025-03-28', 'asd', 'das', '[]', 'asdas', 2025, 2025, '2025-01-27', NULL, 'https://i.ibb.co/r67T4Hd/4d818baf946b.jpg', 1, 'gfhhfghgf'),
(3, '', NULL, 0, '[]', 1, NULL, '2025-03-04 21:37:33', NULL, NULL, NULL, '[]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '', NULL, 0, '[]', 1, NULL, '2025-05-06 18:26:30', NULL, NULL, NULL, '[]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `albums_stream`
--

CREATE TABLE `albums_stream` (
  `id` int(11) NOT NULL,
  `associated` varchar(255) NOT NULL,
  `album_name` varchar(255) NOT NULL,
  `release_date` date NOT NULL,
  `album_image` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `albumID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `albums_stream`
--

INSERT INTO `albums_stream` (`id`, `associated`, `album_name`, `release_date`, `album_image`, `artist`, `albumID`) VALUES
(18, 'sr341_memories-14', 'Memories', '2025-03-28', 'https://static.found.ee/user/264079/res-b240fdf4-afa3-438c-9927-13e8843e359e-b3490c3c-23ed-41d6-b8be-ef872812e454', 'SR341', 2);

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE `analytics` (
  `upc` int(11) NOT NULL,
  `isrc` text NOT NULL,
  `date` int(11) NOT NULL,
  `raw_view` text NOT NULL,
  `raw_revenue` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Catalog Analytics';

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `authorID` int(11) NOT NULL,
  `authorName` varchar(255) NOT NULL,
  `spotifyID` varchar(255) NOT NULL,
  `amID` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `isRestricted` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`authorID`, `authorName`, `spotifyID`, `amID`, `email`, `userID`, `isRestricted`) VALUES
(1, 'yasuo', '21qe21', '21ewq2', '2211@21e1.eee', 1, '[1]'),
(2, 'yasussy', '21qe21', '21ewq2', '2211@21e1.eee', 1, '[1]');

-- --------------------------------------------------------

--
-- Table structure for table `dsp_urls`
--

CREATE TABLE `dsp_urls` (
  `id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resetpwd`
--

CREATE TABLE `resetpwd` (
  `email` text NOT NULL,
  `code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `resetpwd`
--

INSERT INTO `resetpwd` (`email`, `code`) VALUES
('test@test.test', '2192192');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `ip` varchar(255) DEFAULT NULL,
  `secret` varchar(255) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `timeAdded` time DEFAULT NULL,
  `iv` varchar(255) DEFAULT 'taoolabochungmay'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`ip`, `secret`, `userID`, `timeAdded`, `iv`) VALUES
('14.191.51.188', 'l2cOSCIuS', 2, NULL, 'taoolabochungmay'),
('14.255.93.103', 'x6upP2HNb', 1, NULL, 'taoolabochungmay'),
('14.166.255.173', '9q4VOrq', 1, NULL, 'taoolabochungmay'),
('171.236.56.172', 'miaHeM0W', 1, NULL, 'taoolabochungmay'),
('14.190.177.65', 'nAopf9epER', 1, NULL, 'taoolabochungmay'),
('206.189.90.164', '8EC9o0RLD', 1, NULL, 'taoolabochungmay'),
('58.186.14.138', 'lWEnq5YSv1', 1, NULL, 'taoolabochungmay'),
('104.28.254.75', 'Fe8uGM', 1, NULL, 'taoolabochungmay'),
('123.17.1.185', 'hwN3nqATrGELhd', 1, NULL, 'taoolabochungmay'),
('42.116.127.103', 'dH4jEQHpL8', 1, NULL, 'taoolabochungmay'),
('125.235.236.26', 'yxJbB4', 1, NULL, 'taoolabochungmay'),
('112.197.170.152', '2miO5uxquI', 1, NULL, 'taoolabochungmay'),
('14.188.85.237', 'jGoKrLeHpVx', 1, NULL, 'taoolabochungmay'),
('171.236.57.88', '82KqR7xJonB', 1, NULL, 'taoolabochungmay'),
('113.186.17.190', 'hB0coC08mg', 1, NULL, 'taoolabochungmay'),
('14.178.144.96', '338XXVV', 1, '07:01:00', 'zUrYnQ3mZ2AosInIx'),
('171.228.132.89', 'IDjqotnW9LS3k', 1, '06:01:00', 'aWtoxMJG9qVgqAxQN'),
('188.166.161.53', 'N24vPzCesUr23R', 1, '09:01:00', 'F37nBeUxs2lUfHkJ2'),
('123.17.65.40', 'YFcRWVwi', 1, '16:01:00', 'G2hgTGmB1pB7emAsj'),
('14.163.121.192', 'xFqJEG7Qrv78Hf', 1, '02:01:00', '0DtLBcCn8ih1eNPS6'),
('14.166.252.151', 'LObbBEv', 1, '09:01:00', 'hxvizRI6x2OeH78g4'),
('113.163.17.200', 'GclHnPxKJPPX', 1, '18:02:00', 'FbXeGXQ3q5dDeFPem'),
('127.0.0.1', 'YTdkJv0xw', 1, '12:02:00', '5VrAcySb6l9RuA5Y4');

-- --------------------------------------------------------

--
-- Table structure for table `storage`
--

CREATE TABLE `storage` (
  `fileID` int(11) NOT NULL,
  `gID` varchar(255) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `fName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `storage`
--

INSERT INTO `storage` (`fileID`, `gID`, `userID`, `fName`) VALUES
(1, '121e', 1, '2121'),
(2, 'undefined', 1, 'rapmegame.mp3'),
(3, 'undefined', 1, 'Dance with Romance - Maclit & Or(instrumental.ver).mp3'),
(4, 'undefined', 1, 'Dance with Romance - Maclit & Or(instrumental.ver).mp3'),
(5, 'undefined', 1, 'Dance with Romance - Maclit & Or(instrumental.ver).mp3'),
(6, 'undefined', 1, 'Dance with Romance - Maclit & Or(instrumental.ver).mp3'),
(7, 'undefined', 1, 'Dance with Romance - Maclit & Or(instrumental.ver).mp3'),
(8, 'undefined', 1, 'DYNASTY - SILVER SMOKE REMIX x MIRAI YATOGAMI.mp3'),
(9, 'undefined', 1, 'DYNASTY - SILVER SMOKE REMIX x MIRAI YATOGAMI.mp3'),
(10, '132tIaL_hZ-fhMR2uyG9EExz4GDw_7ivX', 1, 'DYNASTY - SILVER SMOKE REMIX x MIRAI YATOGAMI.mp3'),
(11, '1IUDjbXzKM0Ooqk3D2kshDY7wT3tmdGvh', 1, 'Nước Mắt Cá Sấu (RelaxFilming Rmix).mp3'),
(12, '1hCnV8X5-0CFHidrEz_mCAPjj6kq-j7Xk', 1, 'Nước Mắt Cá Sấu (RelaxFilming Rmix).mp3'),
(13, '1woqQZuTLm_nqQvfjh4kMc4neHAVN4QG2', 1, 'Nước Mắt Cá Sấu (RelaxFilming Rmix).mp3'),
(14, '1xGfSyHLkl5Yi74P8FiOYUw4uafZvw1hR', 1, 'Xlazee - Sleep Away.mp3');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `storeID` int(11) NOT NULL,
  `storeName` varchar(220) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`storeID`, `storeName`) VALUES
(1, 'Amazon'),
(2, 'Anghami'),
(3, 'Audiomack'),
(4, 'AWA'),
(5, 'Boomplay'),
(6, 'Deezer'),
(7, 'Facebook Audio Library'),
(8, 'Flo'),
(9, 'iHeartRadio'),
(10, 'iMusica'),
(11, 'iTunes'),
(12, 'JOOX'),
(13, 'KKBOX'),
(14, 'Lickd'),
(15, 'Mixcloud'),
(16, 'Rhapsody/Napster'),
(17, 'Netease'),
(18, 'Nuuday'),
(19, 'Pandora'),
(20, 'Peloton'),
(21, 'Saavn'),
(22, 'Shazam'),
(23, 'Snap'),
(24, 'Spotify'),
(25, 'Tencent'),
(26, 'Tidal'),
(27, 'Tiktok'),
(28, 'Youtube Music'),
(29, 'Youtube Content ID'),
(30, 'Facebook Rights Manager');

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

CREATE TABLE `track` (
  `id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `artist` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `role` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `fID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `albumID` int(11) DEFAULT NULL,
  `isrc` text DEFAULT NULL,
  `ver` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `track`
--

INSERT INTO `track` (`id`, `name`, `artist`, `role`, `fID`, `userID`, `albumID`, `isrc`, `ver`, `duration`) VALUES
(0, 'rapmegame.mp3', NULL, NULL, 2, 1, NULL, NULL, NULL, 5),
(1, '1234', '[1,2]', '[1,2]', 1, 1, 2, NULL, NULL, 5),
(2, 'Dance with Romance - Maclit & Or(instrumental.ver).mp3', NULL, NULL, 7, 1, 2, NULL, NULL, 5),
(4, 'DYNASTY - SILVER SMOKE REMIX x MIRAI YATOGAMI.mp3', NULL, NULL, 8, 1, 3, NULL, NULL, 5),
(5, 'DYNASTY - SILVER SMOKE REMIX x MIRAI YATOGAMI.mp3', NULL, NULL, 9, 1, 3, NULL, NULL, 5),
(6, 'DYNASTY - SILVER SMOKE REMIX x MIRAI YATOGAMI.mp3', NULL, NULL, 10, 1, 3, NULL, NULL, 5),
(7, 'Nước Mắt Cá Sấu (RelaxFilming Rmix).mp3', NULL, NULL, 13, 1, 2, NULL, NULL, 194),
(8, 'Xlazee - Sleep Away.mp3', NULL, NULL, 14, 1, NULL, NULL, NULL, 191);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `labelName` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `regdate` date DEFAULT NULL,
  `imgavt` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `type` int(11) DEFAULT NULL,
  `coverimg` text DEFAULT NULL,
  `endDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `name`, `email`, `username`, `pwd`, `labelName`, `regdate`, `imgavt`, `type`, `coverimg`, `endDate`) VALUES
(1, 'Hoshizumi Tankaon', 'test@test.test', 'admin', 'ae85e2540b864ba07c22770403a269fe', 'admin', '2024-10-08', 'https://i.ibb.co/WcLvS3Y/e9e373275f73.jpg', 0, NULL, '2025-05-19'),
(3, 'Lê Đức Trí', 't52music@gmail.com', 'zrtee', 'ee73aff2e4fa5a59d248442bdd760d8b', 'T52 Music', '2025-02-08', 'https://i.ibb.co/SXGwMRND/470538576-584951000893230-3584206290861968020-n.jpg', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`albumID`),
  ADD UNIQUE KEY `UPCNum` (`UPCNum`),
  ADD KEY `userID` (`userID`),
  ADD KEY `fileID` (`trackID`),
  ADD KEY `storeID` (`storeID`);

--
-- Indexes for table `albums_stream`
--
ALTER TABLE `albums_stream`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `associated` (`associated`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`authorID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `dsp_urls`
--
ALTER TABLE `dsp_urls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `album_id` (`album_id`);

--
-- Indexes for table `storage`
--
ALTER TABLE `storage`
  ADD PRIMARY KEY (`fileID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`storeID`);

--
-- Indexes for table `track`
--
ALTER TABLE `track`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `fk_album` (`albumID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `albumID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `albums_stream`
--
ALTER TABLE `albums_stream`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `authorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dsp_urls`
--
ALTER TABLE `dsp_urls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `storage`
--
ALTER TABLE `storage`
  MODIFY `fileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `storeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=487;

--
-- AUTO_INCREMENT for table `track`
--
ALTER TABLE `track`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `album_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `author`
--
ALTER TABLE `author`
  ADD CONSTRAINT `author_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `dsp_urls`
--
ALTER TABLE `dsp_urls`
  ADD CONSTRAINT `dsp_urls_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums_stream` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `storage`
--
ALTER TABLE `storage`
  ADD CONSTRAINT `storage_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `track`
--
ALTER TABLE `track`
  ADD CONSTRAINT `fk_album` FOREIGN KEY (`albumID`) REFERENCES `album` (`albumID`),
  ADD CONSTRAINT `track_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
