-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 03, 2012 at 04:41 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `radian_jobs`
--

CREATE TABLE IF NOT EXISTS `radian_jobs` (
  `jobnumber` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(255) NOT NULL,
  `client` varchar(255) NOT NULL,
  `date` varchar(11) NOT NULL,
  `description` varchar(500) NOT NULL,
  `translation` int(1) NOT NULL,
  `input` int(1) NOT NULL,
  `pagemakeup` int(1) NOT NULL,
  `invoicenumber` varchar(255) NOT NULL,
  `jobdoneby` varchar(255) NOT NULL,
  `bi` int(1) NOT NULL,
  `datedue` varchar(11) NOT NULL,
  `wordcount` varchar(11) NOT NULL,
  `done` int(1) NOT NULL,
  PRIMARY KEY (`jobnumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
