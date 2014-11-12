<?php

////	Runonce CSRF Create
//
// Create a new CSRF Key for the current session.
//
// Runonce_CSRF_Create();

function Runonce_CSRF_Create() {

	// Set some Globals so the required scripts don't error.
	global $Member_ID, $Time;

	if ( $Member_ID ) $Owner = $Member_ID;
	else $Owner = '*';

	$Key = Generator_String();

	$Timeout = $Time + 2419200;

	// Create a 28 day CSRF Protection Key
	return Runonce_Create($Timeout, 0, 'CSRF Protection', $Key, $Owner);

}