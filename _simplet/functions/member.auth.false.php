<?php

function Member_Auth_False($ClearCookie = false) {
	global $Cookie, $Member, $Request;
	if ( $ClearCookie ) {
		setcookie ($Cookie['Session'], '', 1, '/', $Request['Host'], $Request['Secure'], $Request['HTTPOnly']);
		setcookie ($Cookie['Session'], false, 1, '/', $Request['Host'], $Request['Secure'], $Request['HTTPOnly']);
		unset($_COOKIE[$Cookie['Session']]);
	}
	$Member['Auth'] = false;
	$Member['ID'] = false;
	$Member['Name'] = false;
	$Member['Admin'] = false;
	$Member['Cookie'] = false;
	return true;
}