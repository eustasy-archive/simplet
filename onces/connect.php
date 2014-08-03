<?php

if(
	!empty($Database_Host) &&
	!empty($Database_User) &&
	!empty($Database_Pass) &&
	!empty($Database_Name)
	) {
	
	$MySQL_Connection = mysqli_connect($Database_Host, $Database_User, $Database_Pass, $Database_Name);
	
	if (!$MySQL_Connection) $MySQL_Connection_Error = 'Connection Failed. Check your configuration is correct. <!-- Simplet MySQL Error: '.mysqli_connect_error($MySQL_Connection).' -->';
	
	else {
		
		$MySQL_Connection_Error = false;
		
		require '../functions/database.table.exists.php';
		
		if ($Sitewide_Database_AutoInstall && !Database_Table_Exists('Members')) {
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
	// TODO Add Key to Mail
			$Create_Table_Members = mysqli_query($MySQL_Connection, $Create_Table_Members, MYSQLI_STORE_RESULT);
			if ($Sitewide_Debug && !$Create_Table_Members) echo 'Invalid Query ($Create_Table_Members): '.mysqli_error($MySQL_Connection);
		}
		
		if ($Sitewide_Database_AutoInstall && !Database_Table_Exists('Sessions')) {
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
	// TODO Add Key to Member_ID and Mail and Cookie and Active
			$Create_Table_Sessions = mysqli_query($MySQL_Connection, $Create_Table_Sessions, MYSQLI_STORE_RESULT);
			if ($Sitewide_Debug && !$Create_Table_Sessions) echo 'Invalid Query ($Create_Table_Sessions): '.mysqli_error($MySQL_Connection);
		}
		
		// TODO Add Creation for Other Tables
		
	}
	
} else {
	
	$MySQL_Connection = false;
	$MySQL_Connection_Error = 'Error(s): ';
	if (empty($Database_Host)) $MySQL_Connection_Error .= 'No Database Host Configured. ';
	if (empty($Database_User)) $MySQL_Connection_Error .= 'No Database User Configured. ';
	if (empty($Database_Pass)) $MySQL_Connection_Error .= 'No Database Pass Configured. ';
	if (empty($Database_Name)) $MySQL_Connection_Error .= 'No Database Name Configured. ';
	
}

// TODO Different Error for unconfigured.
if (!$MySQL_Connection && $Sitewide_Database_FatalOnError) {
	echo '<!DocType html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Simplet: Fatal Error</title>
	</head>
	<body>
		<h1>Simplet: Fatal Error</h1>
		<p>Simplet has encountered a fatal error and cannot continue. Don\'t worry, it\'s nothing you did, it\'s the owner of the site. Unless you are the owner of the site. (Shame on you.)</p>
		<h3>'.$MySQL_Connection_Error.'</h3>
	</body>
</html>';
}
