<?php

////	String Generator Function
// A Random String Generator for Salts, Cookies, and other Uniques (remember to check uniqueness).

// stringGenerator() gives a 64 character random string of lower-case letters and numbers
// stringGenerator(n) gives a "n" character random string of lower-case letters and numbers
// stringGenerator(n, true, true) gives a "n" character random string of both lower-case and upper-case letters, numbers and special characters

function stringGenerator( $Length = 64, $Caps = false, $Special = false, $Letters = true, $Numbers = true ) {

	// Set some empty strings so warnings aren't thrown around like silly string.
	$String_Characters = '';
	$String = '';

	// Add character options according to selection
	if ($Caps) $String_Characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	if ($Special) $String_Characters .= '`¬!"£$%^&*()_+-={}[]:@~;\'#<>?,./|\\';
	if ($Letters) $String_Characters .= 'abcdefghijklmnopqrstuvwxyz';
	if ($Numbers) $String_Characters .= '0123456789';

	// Count the number available
	$String_Characters_Count = strlen( $String_Characters ) - 1	;

	// Iterate the number of letters needed
	for( $Iterate = 0; $Iterate < $Length; $Iterate++ ) $String .= $String_Characters[ rand( 0, $String_Characters_Count ) ];

	return $String;

}
