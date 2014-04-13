<?php

function runonceCheck($Key, $Key_Owner = '', $Timeout = '', $Timecount = false) {

	// Set some Globals so the required scripts don't error.
	global $MySQL_Connection, $Member_ID, $Time;

	if (empty($Key_Owner)) $Key_Owner = $Member_ID;
	if (empty($Timeout)) $Timeout = 0;
	if ($Timecount) $Timeout = $Timeout - $Time;

	$Key_Query = 'SELECT * FROM `Runonce` WHERE';
	if ($Key_Owner !== '*') $Key_Query .= ' `Member_ID`=\''.$Key_Owner.'\' AND';
	$Key_Query .= '`Key`=\''.$Key.'\' AND `Status`=\'Active\' AND `Created` > \''.$Timeout.'\' LIMIT 0, 1';

	$Key_Check = mysqli_query($MySQL_Connection, $Key_Query, MYSQLI_STORE_RESULT);
	if (!$Key_Check) exit('Invalid Query (Key_Check): '.mysqli_error($MySQL_Connection));

	$Key_Count = mysqli_num_rows($Key_Check);

	if ($Key_Count > 0) {

		$Key_Check_Fetch = mysqli_fetch_assoc($Key_Check);
		if ($Key_Check_Fetch['Status'] == 'Active') return $Key_Check_Fetch;
		// IF NOT Active
		else return false;

	}

	// IF NOT EXIST
	return false;

}
