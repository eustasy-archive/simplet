<?php

$Database = array();

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
		$Database['Exists'] = array();
		$Database['Exists']['Members'] = Database_Table_Exists('Members');
		$Database['Exists']['Sessions'] = Database_Table_Exists('Sessions');
		$Database['Exists']['Failures'] = Database_Table_Exists('Failures');
		$Database['Exists']['Runonce'] = Database_Table_Exists('Runonce');
		$Database['Exists']['Categories'] = Database_Table_Exists('Categories');
		$Database['Exists']['Topics'] = Database_Table_Exists('Topics');
		$Database['Exists']['Responses'] = Database_Table_Exists('Responses');
		$Database['Exists']['Helpfulness'] = Database_Table_Exists('Helpfulness');
		$Database['Exists']['Views'] = Database_Table_Exists('Views');
		
		if (
			$Sitewide_Database_AutoInstall &&
			(
				!$Database['Exists']['Members'] ||
				!$Database['Exists']['Sessions'] ||
				!$Database['Exists']['Failures'] ||
				!$Database['Exists']['Runonce'] ||
				!$Database['Exists']['Categories'] ||
				!$Database['Exists']['Topics'] ||
				!$Database['Exists']['Responses'] ||
				!$Database['Exists']['Helpfulness'] ||
				!$Database['Exists']['Views']
			)
		) require 'autoinstall.php';
		
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