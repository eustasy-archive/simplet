<?php

////	Password Hash Function
//
// A basic password-and-salt hasher.
//
// Pass_Hash($Pass, $Salt) returns a hash.
// Compare to one stored to determine authenticity.

function Pass_Hash($Pass, $Salt, $Version = 2) {

	global $Sitewide_Security_Hash_Iterations, $Sitewide_Security_Hash_Method;

	if ( strlen($Pass) > 512 ) {
		$Pass = substr($Pass, 0, 512);
	}

	// Version 1, also Version 0
	// Defaulted to sha512 and 3 hashes.
	if ( $Version <= 1 ) {
		return hash($Sitewide_Security_Hash_Method, hash($Sitewide_Security_Hash_Method, $Pass) . hash($Sitewide_Security_Hash_Method, $Salt));

	// Version 2
	// Defaulted to sha512 and 1000 hashes.
	} else {
		$Hash = '';
		for( $i = 0; $i < $Sitewide_Security_Hash_Iterations; $i++ ) {
			$Hash = hash(
				$Sitewide_Security_Hash_Method,
				$Pass.$Salt.$Hash,
				false
			);
		}
		return $Hash;
	}

}