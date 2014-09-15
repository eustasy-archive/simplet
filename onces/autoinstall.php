<?php

if (!$Database['Exists']['Members']) {
	$Create_Table_Members = '
		CREATE TABLE IF NOT EXISTS `'.$Database['Prefix'].'Members` (
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
	$Create_Table_Members = mysqli_query($Database['Connection'], $Create_Table_Members, MYSQLI_STORE_RESULT);
	if (!$Create_Table_Members) {
		if ($Sitewide_Debug) echo 'Invalid Query ($Create_Table_Members): '.mysqli_error($Database['Connection']);
	} else $Database['Exists']['Members'] = true;
}

if (!$Database['Exists']['Sessions']) {
	$Create_Table_Sessions = '
		CREATE TABLE IF NOT EXISTS `'.$Database['Prefix'].'Sessions` (
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
			KEY `Mail` (`Mail`),
			KEY `Cookie` (`Cookie`),
			KEY `Active` (`Active`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1;';
	$Create_Table_Sessions = mysqli_query($Database['Connection'], $Create_Table_Sessions, MYSQLI_STORE_RESULT);
	if (!$Create_Table_Sessions) {
		if ($Sitewide_Debug) echo 'Invalid Query ($Create_Table_Sessions): '.mysqli_error($Database['Connection']);
	} else $Database['Exists']['Sessions'] = true;
}

if (!$Database['Exists']['Failures']) {
	$Create_Table_Failures = '
		CREATE TABLE IF NOT EXISTS `'.$Database['Prefix'].'Failures` (
			`ID` int(255) NOT NULL AUTO_INCREMENT,
			`Member_ID` varchar(12) NOT NULL,
			`Mail` varchar(255) NOT NULL,
			`IP` varchar(255) NOT NULL,
			`Created` int(11) NOT NULL,
			UNIQUE KEY `ID` (`ID`),
			PRIMARY KEY `Member_ID` (`Member_ID`),
			KEY `Mail` (`Mail`),
			KEY `IP` (`IP`)
		) ENGINE=InnoDB	DEFAULT CHARSET=latin1;';
	$Create_Table_Failures = mysqli_query($Database['Connection'], $Create_Table_Failures, MYSQLI_STORE_RESULT);
	if (!$Create_Table_Failures) {
		if ($Sitewide_Debug) echo 'Invalid Query ($Create_Table_Failures): '.mysqli_error($Database['Connection']);
	} else $Database['Exists']['Failures'] = true;
}

if (!$Database['Exists']['Runonce']) {
	$Create_Table_Runonce = '
		CREATE TABLE IF NOT EXISTS `'.$Database['Prefix'].'Runonce` (
			`Member_ID` varchar(12) NOT NULL,
			`Key` varchar(64) NOT NULL,
			`Status` varchar(12) NOT NULL,
			`IP` varchar(64) NOT NULL,
			`Timeout` int(11) NOT NULL,
			`Uses` int(12) NOT NULL,
			`Used` int(12) NOT NULL,
			`Created` int(11) NOT NULL,
			`Modified` int(11) NOT NULL,
			`Notes` mediumtext NOT NULL,
			UNIQUE KEY `Key` (`Key`),
			PRIMARY KEY `Member_ID` (`Member_ID`),
			KEY `Status` (`Status`),
			KEY `IP` (`IP`),
			KEY `Created` (`Created`),
			KEY `Timeout` (`Timeout`),
			KEY `Uses` (`Uses`),
			KEY `Used` (`Used`)
		) ENGINE=InnoDB	DEFAULT CHARSET=latin1;';
	$Create_Table_Runonce = mysqli_query($Database['Connection'], $Create_Table_Runonce, MYSQLI_STORE_RESULT);
	if (!$Create_Table_Runonce) {
		if ($Sitewide_Debug) echo 'Invalid Query ($Create_Table_Runonce): '.mysqli_error($Database['Connection']);
	} else $Database['Exists']['Runonce'] = true;
}

if (!$Database['Exists']['Settings']) {
	$Create_Table_Settings = '
		CREATE TABLE IF NOT EXISTS `'.$Database['Prefix'].'Settings` (
			`Name` varchar(64) NOT NULL,
			`Value` varchar(64) NOT NULL,
			`Created` int(11) NOT NULL,
			`Modified` int(11) NOT NULL,
			UNIQUE KEY `Name` (`Name`),
			PRIMARY KEY `Value` (`Value`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Settings = mysqli_query($Database['Connection'], $Create_Table_Settings, MYSQLI_STORE_RESULT);
	if (!$Create_Table_Settings) {
		if ($Sitewide_Debug) echo 'Invalid Query ($Create_Table_Settings): '.mysqli_error($Database['Connection']);
	} else $Database['Exists']['Settings'] = true;
}

if (!$Database['Exists']['Categories']) {
	$Create_Table_Categories = '
		CREATE TABLE IF NOT EXISTS `'.$Database['Prefix'].'Categories` (
			`Member_ID` varchar(12) NOT NULL,
			`Status` varchar(12) NOT NULL,
			`Title` varchar(255) NOT NULL,
			`Slug` varchar(255) NOT NULL,
			`Description` varchar(255) NOT NULL,
			`Topics` int(10) NOT NULL,
			`Created` int(11) NOT NULL,
			`Modified` int(11) NOT NULL,
			UNIQUE KEY `Slug` (`Slug`),
			KEY `Member_ID` (`Member_ID`),
			KEY `Status` (`Status`),
			KEY `Topics` (`Topics`),
			KEY `Created` (`Created`),
			KEY `Modified` (`Modified`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Categories = mysqli_query($Database['Connection'], $Create_Table_Categories, MYSQLI_STORE_RESULT);
	if (!$Create_Table_Categories) {
		if ($Sitewide_Debug) echo 'Invalid Query ($Create_Table_Categories): '.mysqli_error($Database['Connection']);
	} else $Database['Exists']['Categories'] = true;
}

if (!$Database['Exists']['Topics']) {
	$Create_Table_Topics = '
		CREATE TABLE IF NOT EXISTS `'.$Database['Prefix'].'Topics` (
			`Member_ID` varchar(12) NOT NULL,
			`Status` varchar(12) NOT NULL,
			`Category` varchar(255) NOT NULL,
			`Slug` varchar(500) NOT NULL,
			`Title` varchar(500) NOT NULL,
			`Responses` int(10) NOT NULL,
			`Created` int(11) NOT NULL,
			`Modified` int(11) NOT NULL,
			UNIQUE KEY `Slug` (`Slug`),
			KEY `Member_ID` (`Member_ID`),
			KEY `Status` (`Status`),
			KEY `Category` (`Category`),
			KEY `Responses` (`Responses`),
			KEY `Created` (`Created`),
			KEY `Modified` (`Modified`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Topics = mysqli_query($Database['Connection'], $Create_Table_Topics, MYSQLI_STORE_RESULT);
	if (!$Create_Table_Topics) {
		if ($Sitewide_Debug) echo 'Invalid Query ($Create_Table_Topics): '.mysqli_error($Database['Connection']);
	} else $Database['Exists']['Topics'] = true;
}

if (!$Database['Exists']['Responses']) {
	$Create_Table_Responses = '
		CREATE TABLE IF NOT EXISTS `'.$Database['Prefix'].'Responses` (
			`ID` int(255) NOT NULL AUTO_INCREMENT,
			`Member_ID` varchar(12) NOT NULL,
			`Canonical` varchar(500) NOT NULL,
			`Type` varchar(12) NOT NULL,
			`Status` varchar(12) NOT NULL,
			`Helpfulness` int(11) NOT NULL DEFAULT \'0\',
			`Rating` int(12) NOT NULL,
			`Post` mediumtext NOT NULL,
			`Created` int(11) NOT NULL,
			`Modified` int(11) NOT NULL,
			UNIQUE KEY `ID` (`ID`),
			KEY `Member_ID` (`Member_ID`),
			KEY `Canonical` (`Canonical`),
			KEY `Type` (`Type`),
			KEY `Status` (`Status`),
			KEY `Helpfulness` (`Helpfulness`),
			KEY `Rating` (`Rating`),
			KEY `Created` (`Created`),
			KEY `Modified` (`Modified`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Responses = mysqli_query($Database['Connection'], $Create_Table_Responses, MYSQLI_STORE_RESULT);
	if (!$Create_Table_Responses) {
		if ($Sitewide_Debug) echo 'Invalid Query ($Create_Table_Responses): '.mysqli_error($Database['Connection']);
	} else $Database['Exists']['Responses'] = true;
}

if (!$Database['Exists']['Helpfulness']) {
	$Create_Table_Helpfulness = '
		CREATE TABLE IF NOT EXISTS `'.$Database['Prefix'].'Helpfulness` (
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
			KEY `Helpfulness` (`Helpfulness`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Helpfulness = mysqli_query($Database['Connection'], $Create_Table_Helpfulness, MYSQLI_STORE_RESULT);
	if (!$Create_Table_Helpfulness) {
		if ($Sitewide_Debug) echo 'Invalid Query ($Create_Table_Helpfulness): '.mysqli_error($Database['Connection']);
	} else $Database['Exists']['Helpfulness'] = true;
}

if (!$Database['Exists']['Views']) {
	$Create_Table_Views = '
		CREATE TABLE IF NOT EXISTS `'.$Database['Prefix'].'Views` (
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
			KEY `Request` (`Request`),
			KEY `Canonical` (`Canonical`),
			KEY `Post_Type` (`Post_Type`),
			KEY `IP` (`IP`),
			KEY `Cookie` (`Cookie`),
			KEY `Auth` (`Auth`),
			KEY `Member_ID` (`Member_ID`),
			KEY `Admin` (`Admin`),
			KEY `Time` (`Time`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
	$Create_Table_Views = mysqli_query($Database['Connection'], $Create_Table_Views, MYSQLI_STORE_RESULT);
	if (!$Create_Table_Views) {
		if ($Sitewide_Debug) echo 'Invalid Query ($Create_Table_Views): '.mysqli_error($Database['Connection']);
	} else $Database['Exists']['Views'] = true;
}