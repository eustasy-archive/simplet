<?php

////	String Generator Function
//
// A Random String Generator for Salts, Cookies, and other Uniques (remember to check uniqueness).
//
// Generator_String() gives a 64 character random string of lower-case letters and numbers
// Generator_String(n) gives a "n" character random string of lower-case letters and numbers
// Generator_String(n, true, true) gives a "n" character random string of both lower-case and upper-case letters, numbers and special characters

function Generator_String($Length = 64, $Caps = false, $Special = false, $Letters = true, $Numbers = true) {

	// Set some empty strings so warnings aren't thrown around like silly string.
	$String_Characters = '';
	$String = '';

	// Add character options according to selection
	if ( $Caps ) {
		$String_Characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}
	if ( $Special ) {
		$String_Characters .= '`¬!"£$%^&*()_+-={}[]:@~;\'#<>?,./|\\';
	}
	if ( $Letters ) {
		$String_Characters .= 'abcdefghijklmnopqrstuvwxyz';
	}
	if ( $Numbers ) {
		$String_Characters .= '0123456789';
	}

	// Count the number available
	$String_Characters_Count = strlen($String_Characters);

	// Iterate the number of letters needed
	for ( $Iterate = 0; $Iterate < $Length; $Iterate++ ) {
		// $String .= $String_Characters[rand( 0, $String_Characters_Count )];
		$String .= $String_Characters[hexdec(bin2hex(openssl_random_pseudo_bytes(1))) % $String_Characters_Count];
	}

	return $String;

}