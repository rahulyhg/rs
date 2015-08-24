-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2015 at 05:53 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `roadshare`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('25f127b441866d2eea44fb01e674c184', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0', 1440388194, '');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
`id` int(11) NOT NULL,
  `action` text NOT NULL,
  `action_id` int(11) NOT NULL,
  `line` text NOT NULL,
  `created_id` int(11) NOT NULL,
  `updated_id` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=11 ;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`id`, `action`, `action_id`, `line`, `created_id`, `updated_id`, `created_time`, `updated_time`) VALUES
(1, 'Product#4 (Latest configure laptop) record has been created.', 4, 'product', 8, 8, '2015-04-06 13:26:40', '2015-04-06 07:56:40'),
(2, 'Product#5 (Water bottle for students) record has been created.', 5, 'product', 8, 8, '2015-04-06 13:27:42', '2015-04-06 07:57:42'),
(3, 'Product#6 (4G mobile latest) record has been created.', 6, 'product', 8, 8, '2015-04-06 13:29:21', '2015-04-06 07:59:21'),
(4, 'Product#7 (Sample product) record has been created.', 7, 'product', 8, 8, '2015-04-06 13:37:47', '2015-04-06 08:07:47'),
(5, 'Product#8 (Party wear) record has been created.', 8, 'product', 8, 8, '2015-04-06 13:38:27', '2015-04-06 08:08:27'),
(6, 'Product#9 (Cotton clothings) record has been created.', 9, 'product', 8, 8, '2015-04-06 13:39:09', '2015-04-06 08:09:09'),
(7, 'Product#10 (Mens wear) record has been created.', 10, 'product', 8, 8, '2015-04-06 13:41:40', '2015-04-06 08:11:40'),
(8, 'Product#11 (Cool wear for Mens) record has been created.', 11, 'product', 8, 8, '2015-04-06 13:42:23', '2015-04-06 08:12:23'),
(9, 'Product#12 (Kids wear for summer) record has been created.', 12, 'product', 8, 8, '2015-04-06 13:46:34', '2015-04-06 08:16:34'),
(10, 'Role#2 (Customer) record has been updated.', 2, 'role', 8, 8, '2015-04-09 14:22:57', '2015-04-09 08:52:57');

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE IF NOT EXISTS `rides` (
`id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `origin_name` varchar(255) NOT NULL,
  `origin_address` varchar(255) NOT NULL,
  `origin_latlng` varchar(255) NOT NULL,
  `dest_name` varchar(255) NOT NULL,
  `dest_address` varchar(255) NOT NULL,
  `dest_latlng` varchar(255) NOT NULL,
  `type` enum('OT','R') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ride_schedules`
--

CREATE TABLE IF NOT EXISTS `ride_schedules` (
`id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `time` time NOT NULL,
  `until` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ride_waypoints`
--

CREATE TABLE IF NOT EXISTS `ride_waypoints` (
`id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL,
  `wp_name` varchar(255) NOT NULL,
  `wp_address` varchar(255) NOT NULL,
  `wp_latlng` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_id` int(11) NOT NULL,
  `updated_id` int(11) NOT NULL DEFAULT '0',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_id`, `updated_id`, `created_date`, `updated_date`) VALUES
(1, 'Admin', 0, 0, '2015-03-13 09:01:46', '0000-00-00 00:00:00'),
(2, 'Customer', 8, 8, '2015-03-13 09:01:46', '2015-04-09 14:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `password` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `dob` date NOT NULL,
  `role` int(11) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `news_letter` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `bio` text NOT NULL,
  `profile_img` varchar(255) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `password`, `email`, `dob`, `role`, `gender`, `news_letter`, `phone`, `bio`, `profile_img`) VALUES
(1, 'Ram', 'Krish', 'c51d2b8b90dc1eb9fff456d6a28655ca', 'ram.izaap@gmail.com', '1990-06-12', 2, 'M', 1, '1234567890222', '', 'images-2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
 ADD PRIMARY KEY (`session_id`), ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_sales_channel_user1` (`created_id`), ADD KEY `fk_sales_channel_user2` (`updated_id`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ride_schedules`
--
ALTER TABLE `ride_schedules`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ride_waypoints`
--
ALTER TABLE `ride_waypoints`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ride_schedules`
--
ALTER TABLE `ride_schedules`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ride_waypoints`
--
ALTER TABLE `ride_waypoints`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
