SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `Simplet` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Simplet`;
-- Create Table for Members
CREATE TABLE IF NOT EXISTS `Members` (
  `ID` varchar(12) NOT NULL,
  `Mail` varchar(250) NOT NULL,
  `Name` varchar(250) NOT NULL,
  `Pass` varchar(1000) NOT NULL,
  `Salt` varchar(64) NOT NULL,
  `Admin` int(1) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  `Status` varchar(100) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
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
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
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
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- Create Table for Categories
CREATE TABLE IF NOT EXISTS `Categories` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Slug` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Title` (`Title`),
  KEY `Slug` (`Slug`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
-- Create Table for Topics
CREATE TABLE IF NOT EXISTS `Topics` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `Title` varchar(500) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- Create Table for Replies
CREATE TABLE IF NOT EXISTS `Replies` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Topic_ID` varchar(255) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Post` MEDIUMTEXT NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Topic_ID` (`Topic_ID`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- Create Table for Comments
CREATE TABLE IF NOT EXISTS `Comments` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Canonical` varchar(255) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Post` MEDIUMTEXT NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `Member_ID` (`Member_ID`),
  KEY `Canonical` (`Canonical`),
  KEY `Created` (`Created`),
  KEY `Modified` (`Modified`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
