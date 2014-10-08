<?php

////	Runonce Used
//
// Mark a runonce key by as Used
//
// Runonce_Used('key');
// Runonce_Used('key', 'member_id');

function Runonce_Used($Key, $Key_Owner = '') {

	global $Database, $Member_ID, $Time;

	// IFEXISTSRUNONCE
	if ( !$Database['Exists']['Runonce'] ) return false;
	else {

		if (empty($Key_Owner)) $Key_Owner = $Member_ID;

		$Key_Used = 'UPDATE `'.$Database['Prefix'].'Runonce` SET `Used`=`Used`+1, `Modified`=\''.$Time.'\' WHERE `Key`=\''.$Key.'\' AND `Member_ID`=\''.$Key_Owner.'\'';
		$Key_Used = mysqli_query($Database['Connection'], $Key_Used, MYSQLI_STORE_RESULT);
		if (!$Key_Used) return array('Error' => 'Invalid Query (Key_Used): '.mysqli_error($Database['Connection']));

		return true;

	} // IFEXISTSRUNONCE

}