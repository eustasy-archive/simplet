<?php

if (!$Database['Exists']['Members']) {
	$Create_Table_Members = '
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
			PRIMARY KEY `Mail` (`Mail`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
		// TODO ^ Indexes for Members
	$Create_Table_Members = mysqli_query($MySQL_Connection, $Create_Table_Members, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Members) echo 'Invalid Query ($Create_Table_Members): '.mysqli_error($MySQL_Connection);
}

if (!$Database['Exists']['Sessions']) {
	$Create_Table_Sessions = '
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
			PRIMARY KEY `Member_ID` (`Member_ID`),
			INDEX `Mail` (`Mail`),
			INDEX `Cookie` (`Cookie`),
			INDEX `Active` (`Active`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1;';
	$Create_Table_Sessions = mysqli_query($MySQL_Connection, $Create_Table_Sessions, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Sessions) echo 'Invalid Query ($Create_Table_Sessions): '.mysqli_error($MySQL_Connection);
}

if (!$Database['Exists']['Failures']) {
	$Create_Table_Failures = '
		CREATE TABLE IF NOT EXISTS `Failures` (
			`ID` int(255) NOT NULL AUTO_INCREMENT,
			`Member_ID` varchar(12) NOT NULL,
			`Mail` varchar(255) NOT NULL,
			`IP` varchar(255) NOT NULL,
			`Created` int(11) NOT NULL,
			UNIQUE KEY `ID` (`ID`),
			PRIMARY KEY `Member_ID` (`Member_ID`),
			INDEX `Mail` (`Mail`),
			INDEX `IP` (`IP`)
		) ENGINE=InnoDB	DEFAULT CHARSET=latin1;';
	$Create_Table_Failures = mysqli_query($MySQL_Connection, $Create_Table_Failures, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Failures) echo 'Invalid Query ($Create_Table_Failures): '.mysqli_error($MySQL_Connection);
}

if (!$Database['Exists']['Runonce']) {
	$Create_Table_Runonce = '
		CREATE TABLE IF NOT EXISTS `Runonce` (
			`Member_ID` varchar(12) NOT NULL,
			`Key` varchar(64) NOT NULL,
			`Status` varchar(12) NOT NULL,
			`IP` varchar(64) NOT NULL,
			`Created` int(11) NOT NULL,
			`Modified` int(11) NOT NULL,
			`Notes` mediumtext NOT NULL,
			UNIQUE KEY `Key` (`Key`),
			PRIMARY KEY `Member_ID` (`Member_ID`),
			INDEX `Status` (`Status`),
			INDEX `IP` (`IP`),
			INDEX `Created` (`Created`)
		) ENGINE=InnoDB	DEFAULT CHARSET=latin1;';
	$Create_Table_Runonce = mysqli_query($MySQL_Connection, $Create_Table_Runonce, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Runonce) echo 'Invalid Query ($Create_Table_Runonce): '.mysqli_error($MySQL_Connection);
}

if (!$Database['Exists']['Settings']) {
	$Create_Table_Settings = '
		CREATE TABLE IF NOT EXISTS `Settings` (
			`Name` varchar(64) NOT NULL,
			`Value` varchar(64) NOT NULL,
			`Created` int(11) NOT NULL,
			`Modified` int(11) NOT NULL,
			UNIQUE KEY `Name` (`Name`),
			PRIMARY KEY `Value` (`Value`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Settings = mysqli_query($MySQL_Connection, $Create_Table_Settings, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Settings) echo 'Invalid Query ($Create_Table_Settings): '.mysqli_error($MySQL_Connection);
}

if (!$Database['Exists']['Categories']) {
	$Create_Table_Categories = '
		CREATE TABLE IF NOT EXISTS `Categories` (
			`Member_ID` varchar(12) NOT NULL,
			`Status` varchar(12) NOT NULL,
			`Title` varchar(255) NOT NULL,
			`Slug` varchar(255) NOT NULL,
			`Description` varchar(255) NOT NULL,
			`Topics` int(10) NOT NULL,
			`Created` int(11) NOT NULL,
			`Modified` int(11) NOT NULL,
			UNIQUE KEY `Slug` (`Slug`),
			INDEX `Member_ID` (`Member_ID`),
			INDEX `Status` (`Status`),
			INDEX `Topics` (`Topics`),
			INDEX `Created` (`Created`),
			INDEX `Modified` (`Modified`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Categories = mysqli_query($MySQL_Connection, $Create_Table_Categories, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Categories) echo 'Invalid Query ($Create_Table_Categories): '.mysqli_error($MySQL_Connection);
}

if (!$Database['Exists']['Topics']) {
	$Create_Table_Topics = '
		CREATE TABLE IF NOT EXISTS `Topics` (
			`Member_ID` varchar(12) NOT NULL,
			`Status` varchar(12) NOT NULL,
			`Category` varchar(255) NOT NULL,
			`Slug` varchar(500) NOT NULL,
			`Title` varchar(500) NOT NULL,
			`Responses` int(10) NOT NULL,
			`Created` int(11) NOT NULL,
			`Modified` int(11) NOT NULL,
			UNIQUE KEY `Slug` (`Slug`),
			INDEX `Member_ID` (`Member_ID`),
			INDEX `Status` (`Status`),
			INDEX `Category` (`Category`),
			INDEX `Responses` (`Responses`),
			INDEX `Created` (`Created`),
			INDEX `Modified` (`Modified`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Topics = mysqli_query($MySQL_Connection, $Create_Table_Topics, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Topics) echo 'Invalid Query ($Create_Table_Topics): '.mysqli_error($MySQL_Connection);
}

if (!$Database['Exists']['Responses']) {
	$Create_Table_Responses = '
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
			UNIQUE KEY `ID` (`ID`),
			INDEX `Member_ID` (`Member_ID`),
			INDEX `Canonical` (`Canonical`),
			INDEX `Type` (`Type`),
			INDEX `Status` (`Status`),
			INDEX `Helpfulness` (`Helpfulness`),
			INDEX `Rating` (`Rating`),
			INDEX `Created` (`Created`),
			INDEX `Modified` (`Modified`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Responses = mysqli_query($MySQL_Connection, $Create_Table_Responses, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Responses) echo 'Invalid Query ($Create_Table_Responses): '.mysqli_error($MySQL_Connection);
}

if (!$Database['Exists']['Helpfulness']) {
	$Create_Table_Helpfulness = '
		CREATE TABLE IF NOT EXISTS `Helpfulness` (
			`ID` int(255) NOT NULL AUTO_INCREMENT,
			`Response_Canonical` varchar(500) NOT NULL,
			`Response_ID` int(255) NOT NULL,
			`Member_ID` varchar(12) NOT NULL,
			`Helpfulness` varchar(4) NOT NULL,
			`Created` int(12) NOT NULL,
			`Modified` int(12) NOT NULL,
			UNIQUE KEY `ID` (`ID`),
			INDEX `Response_Canonical` (`Response_Canonical`),
			INDEX `Response_ID` (`Response_ID`),
			INDEX `Member_ID` (`Member_ID`),
			INDEX `Helpfulness` (`Helpfulness`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Helpfulness = mysqli_query($MySQL_Connection, $Create_Table_Helpfulness, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Helpfulness) echo 'Invalid Query ($Create_Table_Helpfulness): '.mysqli_error($MySQL_Connection);
}

if (!$Database['Exists']['Views']) {
	$Create_Table_Views = '
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
			UNIQUE KEY `ID` (`ID`),
			INDEX `Request` (`Request`),
			INDEX `Canonical` (`Canonical`),
			INDEX `Post_Type` (`Post_Type`),
			INDEX `IP` (`IP`),
			INDEX `Cookie` (`Cookie`),
			INDEX `Auth` (`Auth`),
			INDEX `Member_ID` (`Member_ID`),
			INDEX `Admin` (`Admin`),
			INDEX `Time` (`Time`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Views = mysqli_query($MySQL_Connection, $Create_Table_Views, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Views) echo 'Invalid Query ($Create_Table_Views): '.mysqli_error($MySQL_Connection);
}

