<?php

////	Runonce Check
// 
// Check a runonce key exists and is not expired by Key and Owner
// Key_Owner defaults to Member_ID if not set.
// Set to '*' to avoid checking.
// 
// Runonce_Create('key');
// Runonce_Create('key', 'member_id');
// Runonce_Create('key', 'member_id', 3600); // 1 Hour Timeout

function Runonce_Check($Key, $Key_Owner = '', $Timeout = 0) {
	
	// Set some Globals so the required scripts don't error.
	global $Database, $Member_ID, $Time;
	
	// IFEXISTSRUNONCE
	if ( !$Database['Exists']['Runonce'] ) return false;
	else {
		
		if (empty($Key_Owner)) $Key_Owner = $Member_ID;
		
		$Key_Query = 'SELECT * FROM `'.$Database['Prefix'].'Runonce` WHERE';
		$Key_Query .= ' `Key`=\''.$Key.'\' AND `Status`=\'Active\'';
		if ($Key_Owner !== '*') $Key_Query .= ' AND `Member_ID`=\''.$Key_Owner.'\'';
		if ( $Timeout ) $Key_Query .= ' AND `Created` > \''.$Timeout.'\'';
		$Key_Query .= ' LIMIT 0, 1';
		
		$Key_Check = mysqli_query($Database['Connection'], $Key_Query, MYSQLI_STORE_RESULT);
		if (!$Key_Check) exit('Invalid Query (Key_Check): '.mysqli_error($Database['Connection']));
		
		$Key_Count = mysqli_num_rows($Key_Check);
		
		if ($Key_Count > 0) {
			
			$Key_Check_Fetch = mysqli_fetch_assoc($Key_Check);
			if ($Key_Check_Fetch['Status'] == 'Active') return $Key_Check_Fetch;
			// IF NOT Active
			else return false;
			
		}
		
		// IF NOT EXIST
		return false;
		
	} // IFEXISTSRUNONCE
	
}