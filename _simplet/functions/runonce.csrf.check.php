<?php

////	Runonce CSRF Check
//
// Returns a boolean value for a given key after comparing it to the database and current cookie.
// Fails if a new cookie has been set in the time or not a valid key.
// $Initial skips database comparison. Should only be set for the first run in onces/csrfprotect.php
//
// Runonce_CSRF_Check('Key');

function Runonce_CSRF_Check($Submitted, $Initial = false) {

	global $User_CSRF;

	if (
		$Submitted == $User_CSRF['Cookie'] &&
		(
			$Initial ||
			$Submitted == $User_CSRF['Key']
		)
	) {
		return true;
	} else {
		return false;
	}

}