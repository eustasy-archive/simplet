<?php

function runonceCreate($Key = '', $Key_Owner = '', $Notes = '') {

	// Set some Globals so the required scripts don't error.
	global $MySQL_Connection, $Member_ID, $Time, $User_IP;

	if (empty($Key)) $Key = stringGenerator();
	if (empty($Key_Owner)) $Key_Owner = $Member_ID;

	$Key_New = mysqli_query($MySQL_Connection, 'INSERT INTO `Runonce` (`Member_ID`, `Key`, `Status`, `IP`, `Created`, `Modified`, `Notes`) VALUES (\''.$Member_ID.'\', \''.$Key.'\', \'Active\', \''.$User_IP.'\', \''.$Time.'\', \''.$Time.'\', \''.$Notes.'\')', MYSQLI_STORE_RESULT);
	if (!$Key_New) return array('error' => 'Invalid Query (Key_New): '.mysqli_error($MySQL_Connection));

	return array('Key' => $Key, 'Key_Owner' => $Key_Owner, 'Key_Notes' => $Notes, 'Key_Time' => $Time, 'Key_IP' => $User_IP);

}
