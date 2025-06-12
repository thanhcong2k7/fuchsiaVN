-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 05, 2025 at 12:36 AM
-- Server version: 5.7.41-cll-lve
-- PHP Version: 7.2.34

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
  `createdDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `relDate` date DEFAULT NULL,
  `compLine` varchar(255) DEFAULT NULL,
  `publishLine` varchar(255) DEFAULT NULL,
  `trackID` varchar(255) DEFAULT '[]',
  `versionLine` varchar(255) DEFAULT NULL,
  `cyear` int(11) DEFAULT NULL,
  `pyear` int(11) DEFAULT NULL,
  `orgReldate` date DEFAULT NULL,
  `ytcid` int(11) DEFAULT NULL,
  `scloud` int(11) DEFAULT NULL,
  `soundx` int(11) DEFAULT NULL,
  `juno` int(11) DEFAULT NULL,
  `tracklib` int(11) DEFAULT NULL,
  `beatport` varchar(255) DEFAULT NULL,
  `artPrev` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`albumID`, `albumName`, `UPCNum`, `status`, `storeID`, `userID`, `artID`, `createdDate`, `relDate`, `compLine`, `publishLine`, `trackID`, `versionLine`, `cyear`, `pyear`, `orgReldate`, `ytcid`, `scloud`, `soundx`, `juno`, `tracklib`, `beatport`, `artPrev`) VALUES
(2, 'CÃ´ng :)))', '', 0, '[]', 1, 'https://i.ibb.co/Ltkbn1N/4d818baf946b.jpg', '2025-01-27 21:46:28', '2025-01-27', 'asd', 'das', '[{\"id\":\"0\",\"name\":\"undefined\",\"version\":null,\"role\":null,\"artist\":null,\"artistname\":\"\",\"file\":\"2\",\"isrc\":null},{\"id\":\"1\",\"name\":\"1234\",\"version\":null,\"role\":[1,2],\"artist\":[1,2],\"artistname\":\"yasuo, yasussy\",\"file\":\"1\",\"isrc\":null}]', 'asdas', 2025, 2025, '2025-01-27', NULL, NULL, NULL, NULL, 1, NULL, 'https://i.ibb.co/r67T4Hd/4d818baf946b.jpg'),
(3, '', NULL, 0, '[]', 2, NULL, '2025-02-21 20:08:55', NULL, NULL, NULL, '[]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`authorID`, `authorName`, `spotifyID`, `amID`, `email`, `userID`, `isRestricted`) VALUES
(1, 'yasuo', '21qe21', '21ewq2', '2211@21e1.eee', 1, '[1]'),
(2, 'yasussy', '21qe21', '21ewq2', '2211@21e1.eee', 1, '[1]'),
(3, '', '', '', '', 2, NULL),
(4, 'HoÃ ng Anh', '', '', 'prmedia.contact@gmail.com', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resetpwd`
--

CREATE TABLE `resetpwd` (
  `email` text NOT NULL,
  `code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
('14.188.85.237', 'jGoKrLeHpVx', 1, NULL, 'taoolabochungmay'),
('171.236.57.88', '82KqR7xJonB', 1, NULL, 'taoolabochungmay'),
('113.186.17.190', 'hB0coC08mg', 1, NULL, 'taoolabochungmay'),
('171.228.132.89', 'IDjqotnW9LS3k', 1, '06:01:00', 'aWtoxMJG9qVgqAxQN'),
('188.166.161.53', 'N24vPzCesUr23R', 1, '09:01:00', 'F37nBeUxs2lUfHkJ2'),
('123.17.65.40', 'YFcRWVwi', 1, '16:01:00', 'G2hgTGmB1pB7emAsj'),
('14.163.121.192', 'xFqJEG7Qrv78Hf', 1, '02:01:00', '0DtLBcCn8ih1eNPS6'),
('14.166.252.151', 'LObbBEv', 1, '09:01:00', 'hxvizRI6x2OeH78g4'),
('170.64.237.214', 'TxfmwmO4DN', 1, '09:02:00', 'cLJdtteFoW6iXukmM'),
('42.117.79.202', 'BG0lRPF9vt', 3, '08:02:00', 'KfmuOiyE2qvxKzTOc'),
('59.153.225.12', 'dl3A4POSk5', 1, '10:03:00', 'rKxmsXEQ29fqblvY2'),
('113.185.43.53', 'v2qzcpVi70', 1, '08:03:00', '0KTjz6V9xTO0ioUVp'),
('112.197.170.152', '8SbSlYUZMqQyOd', 1, '19:03:00', 'kjUi1z8DPlxZ4ZZaf'),
('118.70.191.126', 'ke1iAOW', 1, '18:03:00', '0Zbr2UruLnToAeHvQ'),
('118.70.191.50', 'wDAmCmHf', 1, '04:03:00', 'LAyMHtrUO9lZ00NDR'),
('14.178.144.96', 'mngqs3xeaFn', 1, '09:03:00', 'cqo0VnWBxq3pIamV9'),
('14.163.132.42', '99eW1yHo5o', 1, '22:03:00', 'ZetVfkcOd5o0qKT7k'),
('14.191.31.212', 'iWEIX8J4zxPQ', 1, '14:03:00', '0AckVeaQKlNsie8Ct'),
('59.153.251.189', '3yVB6APUPN', 1, '15:03:00', 'wY6HR0BsautsAOniy'),
('14.252.40.144', '2EsGkH', 1, '16:03:00', 'ElMUA6QQrltLXkgrN'),
('59.153.231.19', '1zyBAFtP', 1, '06:04:00', 'TADOZyFzW24CV1ptJ'),
('27.67.40.163', 'mh0OD7Rs', 1, '19:04:00', 'tR2XiP8BcteSfW5cs'),
('171.236.57.147', '4mwleB9E', 1, '22:05:00', 'E2wt7Wymxrfg1XFFl'),
('27.65.23.125', '8sDj9VerjQi', 1, '22:05:00', 'hoS9lNcFVaO6uDfbQ'),
('14.228.172.226', 'UGKyejfBqtm', 1, '22:05:00', 'pPoQU68LFNeCofCcB');

-- --------------------------------------------------------

--
-- Table structure for table `storage`
--

CREATE TABLE `storage` (
  `fileID` int(11) NOT NULL,
  `gID` varchar(255) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `fName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `storage`
--

INSERT INTO `storage` (`fileID`, `gID`, `userID`, `fName`) VALUES
(1, '121e', 1, '2121'),
(2, 'undefined', 1, 'undefined'),
(3, 'undefined', 1, 'undefined'),
(4, 'undefined', 1, 'undefined'),
(5, 'undefined', 1, 'undefined'),
(6, '1qRl6XNKBasDIJXICm-Az0uQGZtv2D219', 1, 'undefined');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `storeID` int(11) NOT NULL,
  `storeName` varchar(220) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`storeID`, `storeName`) VALUES
(1, '7Digital'),
(2, 'ACRCloud'),
(3, 'Alibaba'),
(4, 'AliGenie'),
(5, 'Amazon Music'),
(6, 'Anghami'),
(7, 'AGEDI'),
(8, 'Akazoo'),
(9, 'Apple Music'),
(10, 'iTunes'),
(11, 'AMI Entertainment'),
(12, 'Audible Magic - Fulfillment360'),
(13, 'Audible Magic - Rights360'),
(14, 'Audiomack'),
(15, 'Ambients App'),
(16, 'AWA'),
(17, 'Boomplay'),
(18, 'Beatsource'),
(19, 'BMAT'),
(20, 'Claro Musica'),
(21, 'ClickNClear'),
(22, 'Deezer'),
(23, 'Dubset'),
(24, 'Facebook'),
(25, 'DISCO'),
(26, 'Instagram'),
(27, 'Curve'),
(28, 'PEX Attribution Engine'),
(29, 'Gaana'),
(30, 'Gracenote'),
(31, 'FLO'),
(32, 'Hungama'),
(33, 'iHeart'),
(34, 'IMI Mobile'),
(35, 'Jaxsta'),
(36, 'JioSaavn'),
(37, 'Discogs'),
(38, 'JOOX'),
(39, 'Kanjian'),
(40, 'KDigital Media'),
(41, 'KKBOX'),
(42, 'LINE Music'),
(43, 'Mixcloud'),
(44, 'MonkingMe'),
(45, 'Medianet'),
(46, 'MoodAgent'),
(47, 'MusicToday'),
(48, 'MELON'),
(49, 'NetEase Cloud Music'),
(50, 'Pandora'),
(51, 'Peloton'),
(52, 'PEX'),
(53, 'Play Network'),
(54, 'Pretzel Rocks'),
(55, 'Qobuz'),
(56, 'Qub Musique'),
(57, 'Rebelation'),
(58, 'Rockbot'),
(59, 'Roxi'),
(60, 'Resso'),
(61, 'Rhapsody'),
(62, 'Napster'),
(63, 'Rakuten'),
(64, 'Shazam'),
(65, 'Rakuten Music'),
(66, 'Slacker Radio'),
(67, 'Snapchat'),
(68, 'Spinlet'),
(69, 'Soundtrack Your Brand'),
(70, 'Sirius XM'),
(71, 'Soundtrack by Twitch'),
(72, 'Twitch'),
(73, 'Spotify'),
(74, 'Tencent'),
(75, 'Tidal'),
(76, 'Styngr'),
(77, 'Tesla Music'),
(78, 'TouchTunes'),
(79, 'Jazzed'),
(80, 'MyMelo'),
(81, 'Fan Label'),
(82, 'Soundhound'),
(83, 'Soundmouse'),
(84, 'Kuaishou'),
(85, 'Supernatural'),
(86, 'Grandpad'),
(87, 'Traxsource'),
(88, 'Triller'),
(89, 'TikTok'),
(90, 'Trackdrip'),
(91, 'United Media Agency UMA'),
(92, 'Yandex'),
(93, 'VEVO'),
(94, 'YouTube Music'),
(95, 'Zvooq'),
(96, 'VL Group'),
(97, 'eMusic'),
(98, 'Beatno'),
(99, 'Clone Digital'),
(100, 'Music Reports'),
(101, 'Mythical Games'),
(102, 'JPay'),
(103, 'Broadtime'),
(104, 'Keefe Group'),
(105, 'ViaPath'),
(106, 'Turnkey Corrections'),
(107, 'Adaptr'),
(108, 'Artie'),
(109, 'Oga Dance MoveWorld'),
(110, 'DanceFight'),
(111, 'Lickd'),
(112, 'Pinterest'),
(113, 'Samsung Music'),
(114, 'Hyundaicard Music'),
(115, 'Melon Kt'),
(116, 'OllehGenie'),
(117, 'Mnet'),
(118, 'Soribada'),
(119, 'Bugs'),
(120, 'Dalkom'),
(121, 'Kakao'),
(122, 'Cyworld'),
(123, 'Daum'),
(124, 'Monkey3'),
(125, 'Iguplus'),
(126, 'Acer'),
(127, 'Artistxite'),
(128, 'Audiojungle'),
(129, 'B612'),
(130, 'China Unicom'),
(131, 'Wo Music'),
(132, 'Bilibili'),
(133, 'Bleep'),
(134, 'CCA'),
(135, 'CDON'),
(136, 'Celcom'),
(137, 'Changba'),
(138, 'Chunghwa Mobile'),
(139, 'Coolvox'),
(140, 'CTS Eventim AG'),
(141, 'Dance All Day'),
(142, 'DiGi'),
(143, 'Digitally Imported'),
(144, 'Douban Music'),
(145, 'Douyin'),
(146, 'Dragonfly FM'),
(147, 'Echo'),
(148, 'ExLibris'),
(149, 'FETnet'),
(150, 'Findspire'),
(151, 'Flipagram'),
(152, 'FNAC'),
(153, 'Forest Incentives'),
(154, 'Friday Music Omusic'),
(155, 'FUHU'),
(156, 'Gismart'),
(157, 'Global Radio'),
(158, 'Hardstylecom'),
(159, 'High Res Audio'),
(160, 'Himalayan FM'),
(161, 'HMV'),
(162, 'hmv PLAY Soliton'),
(163, 'HTC One'),
(164, 'Huawei Music'),
(165, 'Huawei Music - Hi res'),
(166, 'Huawei South Africa'),
(167, 'Huawei Middle Eeast'),
(168, 'iam Dial'),
(169, 'iFeng'),
(170, 'Jeou Tai'),
(171, 'Jinli Music'),
(172, 'Kanjian Music'),
(173, 'Koala FM'),
(174, 'Kobo'),
(175, 'Kuyin Ringtone'),
(176, 'La Musica'),
(177, 'Last FM'),
(178, 'Lenovo Music'),
(179, 'Lenovo Sound'),
(180, 'Lequ Music'),
(181, 'Lizhi FM'),
(182, 'Love Music China Telecom'),
(183, 'Mach by Mariposa'),
(184, 'Maxis'),
(185, 'Me2u Music'),
(186, 'Media Markt'),
(187, 'Medion'),
(188, 'Mi Tracks'),
(189, 'Migu Music China Mobile'),
(190, 'Mixette'),
(191, 'Mixpixie'),
(192, 'Mixup Digital'),
(193, 'Moi Mir'),
(194, 'MOOV'),
(195, 'MQA'),
(196, 'MusicQubed'),
(197, 'MyMusic'),
(198, 'Naver'),
(199, 'nMusic'),
(200, 'Odnoklassniki'),
(201, 'Onkyo'),
(202, 'Open Live'),
(203, 'Overmax'),
(204, 'Pledge'),
(205, 'Pono'),
(206, 'Prodigium'),
(207, 'Infogo'),
(208, 'Psonar'),
(209, 'Pubbles'),
(210, 'Pulselocker'),
(211, 'QNX CAR'),
(212, 'Raku'),
(213, 'ROK mobile'),
(214, 'Sainsburys'),
(215, 'Saturn'),
(216, 'Stellar'),
(217, 'TDC'),
(218, 'T Star'),
(219, 'Qianqian Music'),
(220, 'TaiHua'),
(221, 'Taiwan Mobile'),
(222, 'TDCTDC Mobile'),
(223, 'Technics Tracks'),
(224, 'The Overflow'),
(225, 'TingTing FM'),
(226, 'Triplay'),
(227, 'Tunewiki'),
(228, 'Tuzhan'),
(229, 'U-Mobile'),
(230, 'Ucmba'),
(231, 'Vibe'),
(232, 'VUE'),
(233, 'Walkgame'),
(234, 'Wasu TV'),
(235, 'Weibo Music'),
(236, 'Weltbild DE'),
(237, 'We Sing'),
(238, 'WEYV'),
(239, 'Xiaomi Music'),
(240, 'Xiaoying'),
(241, 'Xiumi'),
(242, 'Xiutang'),
(243, 'Yiqixiu'),
(244, 'friDay'),
(245, 'Alibaba Group'),
(246, 'Migu Music'),
(247, 'Netease Music'),
(248, 'Kugou'),
(249, 'Kuwo'),
(250, 'QQ Music'),
(251, 'Love Music'),
(252, 'China Telecom'),
(253, 'HUAWEI Hi-Res'),
(254, '24-7 Entertainment'),
(255, 'Huawei Global Radio'),
(256, 'MediaNet'),
(257, 'Orange France'),
(258, 'Tigo'),
(259, 'Deutsche Telekom Austria and Netherlands'),
(260, 'Swisscom'),
(261, 'Fastweb'),
(262, 'Belgacom'),
(263, 'Tango'),
(264, 'Ex Libris'),
(265, 'Vibo Telecom'),
(266, 'Huawei Middle East'),
(267, 'HighResAudio'),
(268, 'Line Music JP'),
(269, 'Lyricfind'),
(270, 'TIM Music'),
(271, 'Kuack Media'),
(272, 'Boomplay Music'),
(273, 'Bugs Music'),
(274, 'Naver Vibe'),
(275, 'Slacker'),
(276, 'Melon'),
(277, 'ClicknClear'),
(278, 'Sunrise'),
(279, 'Rhino'),
(280, 'Bucherde'),
(281, 'Telmore'),
(282, 'Yousee Music'),
(283, 'mobilcom-debitel'),
(284, 'iTunes Ringtone'),
(285, 'MePlaylist'),
(286, 'CCI Distribution'),
(287, 'Tonspion'),
(288, 'Sonos'),
(289, 'TELE2'),
(290, 'Vodafone'),
(291, '3DK'),
(292, 'Bose'),
(293, 'AudioJungle'),
(294, 'In8 Mobile'),
(295, 'GHH Commerce'),
(296, 'QNX CAR'),
(297, 'Music Worx'),
(298, 'SberZvuk'),
(299, 'Astro'),
(300, 'T-Mobile'),
(301, 'Spanish Broadcasting System'),
(302, 'Turntablefm'),
(303, 'Tradebit'),
(304, 'ShopTo'),
(305, 'Senzari'),
(306, 'RBS'),
(307, 'NatWest'),
(308, 'Gravity Mobile'),
(309, 'doubleTwist'),
(310, 'Clip Interactive'),
(311, 'Pure Music'),
(312, 'Cyberlink'),
(313, 'Huawei Ringtone'),
(314, 'VIVO Hi-Res'),
(315, 'KTV'),
(316, 'Toutiao'),
(317, 'ooopic'),
(318, 'yunting'),
(319, 'yesoul'),
(320, 'CIG'),
(321, 'Kids'),
(322, 'CoSleepHeartide'),
(323, 'wondershare'),
(324, 'tingxi'),
(325, 'VCG'),
(326, '58pic'),
(327, 'Qianku'),
(328, 'Daily Yoga'),
(329, 'hidooro'),
(330, 'mangguofm'),
(331, 'kelakela'),
(332, 'Jinshan'),
(333, 'gaoding'),
(334, 'DOOLUU'),
(335, 'Duitang'),
(336, 'dajiang'),
(337, 'Bijian'),
(338, 'baotu'),
(339, 'babytree'),
(340, 'aiyinsitanFM'),
(341, 'epub360'),
(342, 'PRO Agency'),
(343, 'Litup'),
(344, 'lrts'),
(345, 'Keep'),
(346, 'xiaohongshu'),
(347, 'Audio Stock'),
(348, 'Trebel'),
(349, 'SoundtrackYourBrand'),
(350, 'NEC'),
(351, 'My Melo'),
(352, 'Momople'),
(353, 'Genie Music'),
(354, 'WeSing'),
(355, 'Tencent Video'),
(356, 'Sohu Video'),
(357, 'Sina Video'),
(358, 'RAKU'),
(359, 'MGTV'),
(360, 'Xplore Music Stores'),
(361, 'Claro Msica'),
(362, 'PPTV'),
(363, 'YoukuTudou'),
(364, 'iQiyi  PPS'),
(365, 'RabbitPre'),
(366, '5sing'),
(367, 'China Airlines'),
(368, 'Jetstar'),
(369, 'MOO'),
(370, 'Nile Air'),
(371, 'Qantas'),
(372, 'Stardtb'),
(373, 'UC'),
(374, 'Viper HIFI'),
(375, 'babubabu'),
(376, 'Rainbow Music'),
(377, 'Duanku'),
(378, 'Fliggy'),
(379, 'Haitu'),
(380, 'Hema'),
(381, 'Huoshan'),
(382, 'Viamaker'),
(383, 'Whales'),
(384, 'Juhuasuan'),
(385, 'Ku FM'),
(386, 'Kuwo Boom'),
(387, 'Kuwo Juxing'),
(388, 'Kuwo KTV'),
(389, 'Kuwo Vedio'),
(390, 'Kuwo Tingshu'),
(391, 'QQ FM'),
(392, 'Qingting'),
(393, 'Virgin Australia'),
(394, 'Kugou Sing'),
(395, 'Kugou KTV'),
(396, 'Kugou Ringtone'),
(397, 'Kugou Live'),
(398, 'kugouzhibobanlv'),
(399, 'Top Music'),
(400, 'Meitu'),
(401, 'Taobao'),
(402, 'Tmall'),
(403, 'Weishi'),
(404, 'WeChat'),
(405, 'Xigua Video'),
(406, 'Intime'),
(407, 'Intoo'),
(408, 'Alipay'),
(409, 'AirAsia'),
(410, 'AirAsiaX'),
(411, 'Air Vanuatu'),
(412, 'Beijing Eye'),
(413, 'Puxin'),
(414, 'SUPERMONKEY'),
(415, 'Alibaba Cloud Computing Co Ltd'),
(416, 'Fanxian'),
(417, 'Hym'),
(418, 'Hunanzhangyue'),
(419, 'SunExpress'),
(420, 'VISTOPIA'),
(421, 'kuaijianji'),
(422, 'Shiliu'),
(423, 'mylafe'),
(424, 'Reload'),
(425, 'Lutong'),
(426, 'Manbo'),
(427, 'Agora'),
(428, 'Shipingyingye'),
(429, 'Taomailang'),
(430, 'Tuokeshangmao'),
(431, 'Xinhua Zhiyun Technology CoLtd'),
(432, 'Xinpianchang'),
(433, 'Xinxun'),
(434, 'Epub360'),
(435, 'DroitVision'),
(436, 'Perfect World CoLtd'),
(437, 'iSHEJI'),
(438, 'Pikbest'),
(439, 'HELLORF'),
(440, 'Bandcamp'),
(441, 'eMUSIC'),
(442, 'Onmobile US'),
(443, 'iMusica'),
(444, 'ROXI'),
(445, 'VKontakte'),
(446, 'Qishui Music'),
(447, 'WAYZ'),
(448, 'Jiadeyin'),
(449, 'Wasu'),
(450, 'SENYU'),
(451, 'Shishuo'),
(452, 'Aobai'),
(453, 'Motie'),
(454, 'Lefit'),
(455, 'Wxduoduo'),
(456, 'Perfectpark'),
(457, 'Meishe'),
(458, 'Videoshow'),
(459, 'Xiquduoduo'),
(460, 'Bzstech'),
(461, 'Dianyin'),
(462, 'Yingyintonghua'),
(463, 'Thai Airways'),
(464, 'Philippine Airlines'),
(465, 'GMF AeroAsia'),
(466, 'Kwai'),
(467, 'Muserk'),
(468, 'Snack Video'),
(469, 'fizy'),
(470, 'Canva'),
(471, 'Barstool Sports'),
(472, 'Psycle'),
(473, 'Plern'),
(474, 'Deedo'),
(475, 'PURE ENERGY GO'),
(476, 'PortalDisc'),
(477, 'Delta'),
(478, 'Pizza Hut'),
(479, 'Lululemon'),
(480, 'Sona'),
(481, 'BUBUKA'),
(482, 'Beeline Music'),
(483, 'Audioclub'),
(484, 'Fonmix'),
(485, 'Muztube'),
(486, 'Tuned Global');

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

CREATE TABLE `track` (
  `id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `artist` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `role` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `fID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `albumID` int(11) DEFAULT NULL,
  `isrc` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `track`
--

INSERT INTO `track` (`id`, `name`, `artist`, `role`, `fID`, `userID`, `albumID`, `isrc`) VALUES
(0, 'undefined', '', '', 2, 1, 2, NULL),
(1, '1234', '[1,2]', '[1,2]', 1, 1, 2, NULL);

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
  `coverimg` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `name`, `email`, `username`, `pwd`, `labelName`, `regdate`, `imgavt`, `type`, `coverimg`) VALUES
(1, 'Hoshizumi Tankaon', 'test@test.test', 'admin', 'ae85e2540b864ba07c22770403a269fe', 'admin', '2024-10-08', 'https://i.ibb.co/WcLvS3Y/e9e373275f73.jpg', NULL, NULL),
(2, 'Van Truong', 'huramusixgroup@gmail.com', 'huramusic', 'd41d8cd98f00b204e9800998ecf8427e', 'Hura Music Group', '2024-10-14', 'https://i.ibb.co/rKgLXHMK/675e8f8719a5.jpg', 1, NULL),
(3, 'Lê Đức Trí', 't52music@gmail.com', 'zrtee', 'ee73aff2e4fa5a59d248442bdd760d8b', 'T52 Music', '2025-02-08', 'https://i.ibb.co/SXGwMRND/470538576-584951000893230-3584206290861968020-n.jpg', 1, NULL);

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
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`authorID`),
  ADD KEY `userID` (`userID`);

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
  MODIFY `albumID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `authorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `storage`
--
ALTER TABLE `storage`
  MODIFY `fileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `storeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=487;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
