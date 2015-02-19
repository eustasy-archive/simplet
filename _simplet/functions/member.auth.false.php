<?php

function Member_Auth_False($ClearCookie = false) {
	global $Cookie, $Member_Admin, $Member_Auth, $Member_ID, $Member_Name, $Request;
	if ( $ClearCookie ) {
		setcookie ($Cookie['Session'], '', 1, '/', $Request['Host'], $Request['Secure'], $Request['HTTPOnly']);
		setcookie ($Cookie['Session'], false, 1, '/', $Request['Host'], $Request['Secure'], $Request['HTTPOnly']);
		unset($_COOKIE[$Cookie['Session']]);
	}
	$Member_Auth = false;
	$Member_ID = false;
	$Member_Name = false;
	$Member_Admin = false;
	return true;
}