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

	if ( isset($_COOKIE[$Cookie_Prefix.'_csrf_protection']) ) $Runonce_Info['Cookie'] = Input_Prepare($_COOKIE[$Cookie_Prefix.'_csrf_protection']);

	return $Runonce_Info;

}