<?php

////	Member Block Check Function
//
// Checks whether or not a member is in a given group.
//
// Member_Block_Check($Login_Mail);

function Member_Block_Check($Login_Mail) {

	global $Database, $Sitewide_Debug;

	// IFEXISTSFAILURES
	if ( !$Database['Exists']['Failures'] ) return false;
	else {

		$Block_Check = 'SELECT * FROM `'.$Database['Prefix'].'Failures` WHERE `Mail`=\''.$Login_Mail.'\' AND `Created` > UNIX_TIMESTAMP()-900';
		$Block_Check = mysqli_query($Database['Connection'], $Block_Check, MYSQLI_STORE_RESULT);
		if ( !$Block_Check ) {
			if ( $Sitewide_Debug ) echo 'Invalid Query (Block_Check): '.mysqli_error($Database['Connection']);
			return false;
		} else return  mysqli_num_rows($Block_Check);

	} // IFEXISTSFAILURES

}