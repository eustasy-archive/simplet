<?php
	
require '../functions/database.table.exists.php';

if (!Database_Table_Exists('Members')) {
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
	$Create_Table_Members = mysqli_query($MySQL_Connection, $Create_Table_Members, MYSQLI_STORE_RESULT);
	if ($Sitewide_Debug && !$Create_Table_Members) echo 'Invalid Query ($Create_Table_Members): '.mysqli_error($MySQL_Connection);
}

if (!Database_Table_Exists('Sessions')) {
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

// TODO
// Add Creation for Other Tables
// Add Appropriate Indexes for Other Tables
