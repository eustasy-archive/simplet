<?php

////	Runonce CSRF Check
//
//
//
// Runonce_CSRF_Check('Key');

function Runonce_CSFR_Check($Key) {

	// Set some Globals so the required scripts don't error.
	global $Cookie_Prefix, $Member_ID, $Time;

	if ( $Member_ID ) $Owner = $Member_ID;
	else $Owner = '*';

	$Runonce_Check['Check'] = Runonce_Check($Key, $Owner, 'CSRF Protection');

	if ( isset($_COOKIE[$Cookie_Prefix.'_csrf_protection']) ) $Runonce_Check['Cookie'] = Input_Prepare($_COOKIE[$Cookie_Prefix.'_csrf_protection']);

	return $Runonce_Check;

}