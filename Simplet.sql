SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE `Simplet` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
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
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
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
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
-- Create Table for Password Resets
CREATE TABLE IF NOT EXISTS `Resets` (
  `Mail` varchar(255) NOT NULL,
  `Key` varchar(64) NOT NULL,
  `Active` int(1) NOT NULL,
  `IP` varchar(255) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  UNIQUE KEY `Key` (`Key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- Create Table for Failed Logins
CREATE TABLE IF NOT EXISTS `Failures` (
  `Mail` varchar(255) NOT NULL,
  `IP` varchar(255) NOT NULL,
  `Created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- Create Table for Topics
CREATE TABLE IF NOT EXISTS `Topics` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `Member_ID` varchar(12) NOT NULL,
  `Status` varchar(12) NOT NULL,
  `Title` varchar(500) NOT NULL,
  `Description` varchar(5000) NOT NULL,
  `Created` int(11) NOT NULL,
  `Modified` int(11) NOT NULL,
  PRIMARY KEY (`ID`)m
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
