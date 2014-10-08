<?php

////	Runonce Delete
//
// Delete a runonce key by Key and Owner
//
// Runonce_Delete('key');
// Runonce_Delete('key', 'member_id');

function Runonce_Delete($Key, $Key_Owner = '') {

	global $Database, $Member_ID, $Time;

	// IFEXISTSRUNONCE
	if ( !$Database['Exists']['Runonce'] ) return false;
	else {

		if (empty($Key_Owner)) $Key_Owner = $Member_ID;

		$Key_Delete = 'UPDATE `'.$Database['Prefix'].'Runonce` SET `Status`=\'Used\', `Used`=`Used`+1, `Modified`=\''.$Time.'\' WHERE `Key`=\''.$Key.'\' AND `Member_ID`=\''.$Key_Owner.'\'';
		$Key_Delete = mysqli_query($Database['Connection'], $Key_Delete, MYSQLI_STORE_RESULT);
		if (!$Key_Delete) return array('Error' => 'Invalid Query (Key_Delete): '.mysqli_error($Database['Connection']));

		return true;

	} // IFEXISTSRUNONCE

}