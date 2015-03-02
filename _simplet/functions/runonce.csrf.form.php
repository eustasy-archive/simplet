<?php

////	Runonce CSRF Form
//
//
//
// Runonce_CSRF_Form();
// Runonce_CSRF_Form('Key');

function Runonce_CSRF_Form($Key = false) {

	// Set some Globals so the required scripts don't error.
	global $User;

	if ( !$Key ) {
		$Key = $User['CSRF']['Key'];
	}

	return '<input type="hidden" name="csrf_protection" value="'.$Key.'">';

}