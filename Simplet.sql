SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `Simplet` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Simplet`;

-- Create Table for Members
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
  UNIQUE KEY `ID` (`ID`),
  KEY `Mail` (`Mail`),
  KEY `Name` (`Name`),
  KEY `Admin` (`Name`),
  KEY `Status` (`Name`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create Table for Sessions
CREATE TABLE IF NOT EXISTS `Sessions` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Mail` varchar(255) NOT NULL,
  `IP` varchar(255) NOT NULL,
  `Cookie` varchar(64) NOT NULL,
  `Active` int(1) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Member_ID` (`Member_ID`),
  KEY `IP` (`IP`),
  KEY `Cookie` (`Cookie`),
  KEY `Active` (`Active`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- Create Table for Failed Logins
CREATE TABLE IF NOT EXISTS `Failures` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Mail` varchar(255) NOT NULL,
  `IP` varchar(255) NOT NULL,
  `Created` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Created` (`Created`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- Create Table for Password Resets
CREATE TABLE IF NOT EXISTS `Resets` (
  `Member_ID` varchar(12) NOT NULL,
  `Mail` varchar(255) NOT NULL,
  `Key` varchar(64) NOT NULL,
  `Active` int(1) NOT NULL,
  `IP` varchar(255) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `Key` (`Key`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Active` (`Active`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create Table for Categories
CREATE TABLE IF NOT EXISTS `Categories` (
  `Member_ID` varchar(12) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Slug` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  PRIMARY KEY (`Slug`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Title` (`Title`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create Table for Topics
CREATE TABLE IF NOT EXISTS `Topics` (
  `Member_ID` varchar(12) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `Slug` varchar(500) NOT NULL,
  `Title` varchar(500) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  PRIMARY KEY (`Slug`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create Table for Responses
CREATE TABLE IF NOT EXISTS `Responses` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Canonical` varchar(500) NOT NULL,
  `Type` varchar(12) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Rating` int(12) NOT NULL,
  `Post` mediumtext NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Canonical` (`Canonical`),
  KEY `Type` (`Type`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Creat Table for Helpfulness
CREATE TABLE IF NOT EXISTS `Helpfulness` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Response_Canonical` varchar(500) NOT NULL,
  `Response_ID` int(255) NOT NULL,
  `Member_ID` varchar(12) NOT NULL,
  `Helpfulness` varchar(4) NOT NULL,
  `Created` int(12) NOT NULL,
  `Modified` int(12) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Response_Canonical` (`Response_Canonical`),
  KEY `Response_ID` (`Response_ID`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Helpfulness` (`Helpfulness`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- Create Table for Replies
CREATE TABLE IF NOT EXISTS `Replies` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Topic_Slug` varchar(500) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Post` mediumtext NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Topic_ID` (`Topic_Slug`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
