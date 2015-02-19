<?php

////	Runonce CSRF Info
//
//
//
// Runonce_CSRF_Info('Key');

function Runonce_CSRF_Info($Key) {

	global $Cookie_Prefix, $Member_ID, $Time;

	if ( $Member_ID ) $Owner = $Member_ID;
	else $Owner = '*';

	$Runonce_Info['Info'] = Runonce_Info($Key, $Owner, 'CSRF Protection');

	if ( isset($_COOKIE[$Cookie['CSRF']]) ) $Runonce_Info['Cookie'] = Input_Prepare($_COOKIE[$Cookie['CSRF']]);

	return $Runonce_Info;

}