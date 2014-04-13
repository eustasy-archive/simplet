-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 13, 2014 at 03:04 PM
-- Server version: 5.5.36
-- PHP Version: 5.5.10-1+deb.sury.org~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `Simplet`
--

-- --------------------------------------------------------

--
-- Table structure for table `Members`
--

CREATE TABLE IF NOT EXISTS `Members` (
  `ID` varchar(12) NOT NULL,
  `Mail` varchar(250) NOT NULL,
  `Name` varchar(250) NOT NULL,
  `Admin` int(1) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Pass` varchar(1000) NOT NULL,
  `Salt` varchar(64) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Sessions`
--

CREATE TABLE IF NOT EXISTS `Sessions` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Mail` varchar(255) NOT NULL,
  `IP` varchar(255) NOT NULL,
  `Cookie` varchar(64) NOT NULL,
  `Active` int(1) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Failures`
--

CREATE TABLE IF NOT EXISTS `Failures` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Mail` varchar(255) NOT NULL,
  `IP` varchar(255) NOT NULL,
  `Created` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Runonce`
--

CREATE TABLE IF NOT EXISTS `Runonce` (
  `Member_ID` varchar(12) NOT NULL,
  `Key` varchar(64) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `IP` varchar(64) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  `Notes` mediumtext NOT NULL,
  UNIQUE KEY `Key` (`Key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE IF NOT EXISTS `Categories` (
  `Member_ID` varchar(12) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Slug` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Topics` int(10) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  PRIMARY KEY (`Slug`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Topics`
--

CREATE TABLE IF NOT EXISTS `Topics` (
  `Member_ID` varchar(12) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `Slug` varchar(500) NOT NULL,
  `Title` varchar(500) NOT NULL,
  `Responses` int(10) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  PRIMARY KEY (`Slug`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Responses`
--

CREATE TABLE IF NOT EXISTS `Responses` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Canonical` varchar(500) NOT NULL,
  `Type` varchar(12) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Helpfulness` int(11) NOT NULL DEFAULT '0',
  `Rating` int(12) NOT NULL,
  `Post` mediumtext NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Helpfulness`
--

CREATE TABLE IF NOT EXISTS `Helpfulness` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Response_Canonical` varchar(500) NOT NULL,
  `Response_ID` int(255) NOT NULL,
  `Member_ID` varchar(12) NOT NULL,
  `Helpfulness` varchar(4) NOT NULL,
  `Created` int(12) NOT NULL,
  `Modified` int(12) NOT NULL,
  UNIQUE KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Views`
--

CREATE TABLE IF NOT EXISTS `Views` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Request` varchar(2500) NOT NULL,
  `Canonical` varchar(2500) NOT NULL,
  `Post_Type` varchar(255) NOT NULL,
  `IP` varchar(255) NOT NULL,
  `Cookie` varchar(64) NOT NULL,
  `Auth` varchar(5) NOT NULL,
  `Member_ID` varchar(12) NOT NULL,
  `Admin` varchar(5) NOT NULL,
  `Time` int(12) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
