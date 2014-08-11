<?php

$Database = array();

////	START Database Configuration

// Database Host
// 'localhost' or an IP Address
$Database['Host'] = 'localhost';

// Database User
// You probably shouldn't use 'Simplet' here.
$Database['User'] = 'Simplet';

// Database Pass
$Database['Pass'] = 'Password1';

// Database Name
// You might want to change the Database Name too.
$Database['Name'] = 'Simplet';

// Database Name
// You might want to change the Database Name too.
$Database['Prefix'] = 'Simplet_';

// Fatal on Database Error
$Database['FatalOnError'] = false;

// AutoInstall Database Tables
$Database['AutoInstall'] = false;

////	END Database Configuration

if(
	!empty($Database['Host']) &&
	!empty($Database['User']) &&
	!empty($Database['Pass']) &&
	!empty($Database['Name'])
	) {
	
	$Database['Connection'] = mysqli_connect($Database['Host'], $Database['User'], $Database['Pass'], $Database['Name']);
	
	if (!$Database['Connection']) {
		$Database['Error'] = 'Connection Failed. Check your configuration is correct. <!-- Simplet MySQL Error: '.mysqli_connect_error($Database['Connection']).' -->';
		$Database['Host'] = $Database['User'] = $Database['Pass'] = $Database['Name'] = false;
	} else {
		
		$Database['Error'] = false;
		
		require '../functions/database.table.exists.php';
		$Database['Exists'] = array();
		$Database['Exists'][$Database['Prefix'].'Members'] = Database_Table_Exists($Database['Prefix'].'Members');
		$Database['Exists'][$Database['Prefix'].'Sessions'] = Database_Table_Exists($Database['Prefix'].'Sessions');
		$Database['Exists'][$Database['Prefix'].'Failures'] = Database_Table_Exists($Database['Prefix'].'Failures');
		$Database['Exists'][$Database['Prefix'].'Runonce'] = Database_Table_Exists($Database['Prefix'].'Runonce');
		$Database['Exists'][$Database['Prefix'].'Settings'] = Database_Table_Exists($Database['Prefix'].'Settings');
		$Database['Exists'][$Database['Prefix'].'Categories'] = Database_Table_Exists($Database['Prefix'].'Categories');
		$Database['Exists'][$Database['Prefix'].'Topics'] = Database_Table_Exists($Database['Prefix'].'Topics');
		$Database['Exists'][$Database['Prefix'].'Responses'] = Database_Table_Exists($Database['Prefix'].'Responses');
		$Database['Exists'][$Database['Prefix'].'Helpfulness'] = Database_Table_Exists($Database['Prefix'].'Helpfulness');
		$Database['Exists'][$Database['Prefix'].'Views'] = Database_Table_Exists($Database['Prefix'].'Views');
		
		if (
			$Database['AutoInstall'] &&
			(
				!$Database['Exists'][$Database['Prefix'].'Members'] ||
				!$Database['Exists'][$Database['Prefix'].'Sessions'] ||
				!$Database['Exists'][$Database['Prefix'].'Failures'] ||
				!$Database['Exists'][$Database['Prefix'].'Runonce'] ||
				!$Database['Exists'][$Database['Prefix'].'Categories'] ||
				!$Database['Exists'][$Database['Prefix'].'Topics'] ||
				!$Database['Exists'][$Database['Prefix'].'Responses'] ||
				!$Database['Exists'][$Database['Prefix'].'Helpfulness'] ||
				!$Database['Exists'][$Database['Prefix'].'Views']
			)
		) require 'autoinstall.php';
		
	}
	
} else {
	
	$Database['Connection'] = false;
	$Database['Error'] = 'Error(s): ';
	if (empty($Database['Host'])) $Database['Error'] .= 'No Database Host Configured. ';
	if (empty($Database['User'])) $Database['Error'] .= 'No Database User Configured. ';
	if (empty($Database['Pass'])) $Database['Error'] .= 'No Database Pass Configured. ';
	if (empty($Database['Name'])) $Database['Error'] .= 'No Database Name Configured. ';
	
	$Database['Host'] = $Database['User'] = $Database['Pass'] = $Database['Name'] = false;
	
}

// TODO
// Different Error for unconfigured. Suggest editing.
// Also, suggest auto-install if fatal and no tables.
if (!$Database['Connection'] && $Database['FatalOnError']) {
	echo '<!DocType html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Simplet: Fatal Error</title>
	</head>
	<body>
		<h1>Simplet: Fatal Error</h1>
		<p>Simplet has encountered a fatal error and cannot continue. Don\'t worry, it\'s nothing you did, it\'s the owner of the site. Unless you are the owner of the site. (Shame on you.)</p>
		<h3>'.$Database['Error'].'</h3>
	</body>
</html>';
	exit;
}

$Database['Host'] = $Database['User'] = $Database['Pass'] = $Database['Name'] = true;