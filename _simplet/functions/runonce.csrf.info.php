<?php

////	Runonce CSRF Info
//
//
//
// Runonce_CSRF_Info('Key');

function Runonce_CSRF_Info($Key) {

	global $Cookie, $Member, $Time;

	if ( $Member_ID ) {
		$Owner = $Member['ID'];
	} else {
		$Owner = '*';
	}

	$Runonce_Info['Info'] = Runonce_Info($Key, $Owner, 'CSRF Protection');
	if ( isset($_COOKIE[$Cookie['CSRF']]) ) {
		$Runonce_Info['Cookie'] = Input_Prepare($_COOKIE[$Cookie['CSRF']]);
	}
	return $Runonce_Info;

}