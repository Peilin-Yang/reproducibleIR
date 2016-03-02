-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 19, 2016 at 03:49 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rires`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` bigint(12) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` varchar(255) NOT NULL COMMENT 'md5 in the activation URL',
  `regAt` datetime DEFAULT NULL,
  `activateAt` datetime DEFAULT NULL,
  `firstLoginAt` datetime DEFAULT NULL,
  `resetToken` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `resetComplete` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `isTest` tinyint(1) NOT NULL DEFAULT '0',
  `firstname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `middlename` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `institute` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `email`, `password`, `active`, `regAt`, `activateAt`, `firstLoginAt`, `resetToken`, `resetComplete`, `isAdmin`, `isTest`, `firstname`, `middlename`, `lastname`, `institute`) VALUES
(2, 'franklyn', 'yangpeilyn@gmail.com', '$2y$10$dgQ5JVnD3rRziKko3aNhiO3JEIQsovVTugN.YjklbakWV/0caJRaa', 'Yes', '2016-02-19 01:16:03', '2016-02-19 01:22:12', '2016-02-19 01:36:01', '52ab114080be0275a7909086332b7ae7', 'Yes', 0, 0, 'Peilin', '', 'Yang', 'University of Delware');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
