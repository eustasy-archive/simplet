<?php

$Database = array();

include_once __DIR__.'/../config.database.php';

if(
	!empty($Database['Host']) &&
	!empty($Database['User']) &&
	!empty($Database['Pass']) &&
	!empty($Database['Name'])
) {

	$Database['Connection'] = mysqli_connect($Database['Host'], $Database['User'], $Database['Pass'], $Database['Name']);

	if ( !$Database['Connection'] ) {
		$Database['Error'] = 'Connection Failed. Check your configuration is correct. '.mysqli_connect_error($Database['Connection']);
		$Database['Host'] = $Database['User'] = $Database['Pass'] = $Database['Name'] = false;
	} else {

		$Database['Error'] = false;

		require __DIR__.'/../functions/database.table.exists.php';
		$Database['Exists'] = array();
		$Database['Exists']['Members'] = Database_Table_Exists('Members');
		$Database['Exists']['Sessions'] = Database_Table_Exists('Sessions');
		$Database['Exists']['Failures'] = Database_Table_Exists('Failures');
		$Database['Exists']['Runonce'] = Database_Table_Exists('Runonce');
		$Database['Exists']['Settings'] = Database_Table_Exists('Settings');
		$Database['Exists']['Categories'] = Database_Table_Exists('Categories');
		$Database['Exists']['Topics'] = Database_Table_Exists('Topics');
		$Database['Exists']['Responses'] = Database_Table_Exists('Responses');
		$Database['Exists']['Helpfulness'] = Database_Table_Exists('Helpfulness');
		$Database['Exists']['Views'] = Database_Table_Exists('Views');

		if (
			$Database['AutoInstall'] &&
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