<?php

////	Runonce Create
// 
// Create a runonce key by Key and Owner
// 
// Runonce_Create();
// Runonce_Create('key');
// Runonce_Create('key', 'member_id');
// Runonce_Create('key', 'member_id', 'Notes');

function Runonce_Create($Key = '', $Key_Owner = '', $Notes = '') {

	// Set some Globals so the required scripts don't error.
	global $Database, $Member_ID, $Time, $User_IP;

	if (empty($Key)) $Key = Generator_String();
	if (empty($Key_Owner)) $Key_Owner = $Member_ID;

	$Key_New = mysqli_query($Database['Connection'], 'INSERT INTO `'.$Database['Prefix'].'Runonce` (`Member_ID`, `Key`, `Status`, `IP`, `Created`, `Modified`, `Notes`) VALUES (\''.$Member_ID.'\', \''.$Key.'\', \'Active\', \''.$User_IP.'\', \''.$Time.'\', \''.$Time.'\', \''.$Notes.'\')', MYSQLI_STORE_RESULT);
	if (!$Key_New) return array('error' => 'Invalid Query (Key_New): '.mysqli_error($Database['Connection']));

	return array('Key' => $Key, 'Key_Owner' => $Key_Owner, 'Key_Notes' => $Notes, 'Key_Time' => $Time, 'Key_IP' => $User_IP);

}