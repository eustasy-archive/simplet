<?php

////	Runonce CSRF Create
//
// Create a new CSRF Key for the current session.
//
// Runonce_CSRF_Create();

function Runonce_CSRF_Create() {

	// Set some Globals so the required scripts don't error.
	global $Cookie_Prefix, $Member_ID, $Request, $Time;

	if ( $Member_ID ) $Owner = $Member_ID;
	else $Owner = '*';

	$Key = Generator_String();

	$Timeout = $Time + 2419200;

	// Create a 28 day CSRF Protection Key
	$Runonce_CSRF_Create = Runonce_Create($Timeout, 0, 'CSRF Protection', $Key, $Owner);

	setcookie($Cookie_Prefix.'_csrf_protection', $Runonce_CSRF_Create['Key'], time()+60*60*24*28, '/', $Request['host'], $Request['Secure'], $Request['HTTPOnly']);

	return $Runonce_CSRF_Create;

}