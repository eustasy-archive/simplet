<?php

function runonceDelete($Key, $Key_Owner = '') {

	// Set some Globals so the required scripts don't error.
	global $MySQL_Connection, $Member_ID, $Time;

	if (empty($Key_Owner)) $Key_Owner = $Member_ID;

	$Key_Delete = mysqli_query($MySQL_Connection, 'UPDATE `Runonce` SET `Status`=\'Used\', `Modified`=\''.$Time.'\' WHERE `Key`=\''.$Key.'\' AND `Member_ID`=\''.$Key_Owner.'\'', MYSQLI_STORE_RESULT);
	if (!$Key_Delete) exit('Error: Invalid Query (Key_Delete): '.mysqli_error($MySQL_Connection));

	return true;

}
