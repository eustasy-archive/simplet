<?php

////	Runonce Delete

// Runone_Delete

function Runone_Delete($Key, $Key_Owner = '') {

	global $Database, $Member_ID, $Time;

	if (empty($Key_Owner)) $Key_Owner = $Member_ID;

	$Key_Delete = mysqli_query($Database['Connection'], 'UPDATE `Runonce` SET `Status`=\'Used\', `Modified`=\''.$Time.'\' WHERE `Key`=\''.$Key.'\' AND `Member_ID`=\''.$Key_Owner.'\'', MYSQLI_STORE_RESULT);
	if (!$Key_Delete) exit('Error: Invalid Query (Key_Delete): '.mysqli_error($Database['Connection']));

	return true;

}
