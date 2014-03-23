<?php

if(
	isset($Database_Host) &&
	isset($Database_User) &&
	isset($Database_Pass) &&
	isset($Database_Name) &&
	!empty($Database_Host) &&
	!empty($Database_User) &&
	!empty($Database_Pass) &&
	!empty($Database_Name)
	) {
	$MySQL_Connection = mysqli_connect($Database_Host, $Database_User, $Database_Pass, $Database_Name);
	if (!$MySQL_Connection) exit('<!DocType html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Fatal Error &nbsp;&middot;&nbsp; Simplet</title>
	</head>
	<body>
		<h1>Fatal Error</h1>
		<p>Simplet was unable to connect to the database using the options defined in the configuration file.</p>
		<p>'.mysqli_connect_error($MySQL_Connection).'</p>
	</body>
</html>');
} else {
	$Error = 'Error(s): ';
	if(!isset($Database_Host) || !empty($Database_Host)) $Error .= 'No Database Host Configured. ';
	if(!isset($Database_User) || !empty($Database_User)) $Error .= 'No Database User Configured. ';
	if(!isset($Database_Pass) || !empty($Database_Pass)) $Error .= 'No Database Pass Configured. ';
	if(!isset($Database_Name) || !empty($Database_Name)) $Error .= 'No Database Name Configured. ';
	exit('<!DocType html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Fatal Error &nbsp;&middot;&nbsp; Simplet</title>
	</head>
	<body>
		<h1>Fatal Error</h1>
		<p>Simplet was unable to connect to the database using the options defined in the configuration file.</p>
		<p>'.$Error.'</p>
	</body>
</html>');
}
