-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 26, 2014 at 06:13 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inewsarchives`
--

-- --------------------------------------------------------

--
-- Table structure for table `kfor_contacts`
--

CREATE TABLE IF NOT EXISTS `wgn_legacy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(100) NOT NULL,
  `fullpath` varchar(100) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `script` longtext,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`script`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
