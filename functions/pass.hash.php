<?php

////	Password Hash Function
// 
// A basic password-and-salt hasher.
// 
// Pass_Hash($Pass, $Salt) returns a hash.
// Compare to one stored to determine authenticity.

function Pass_Hash($Pass, $Salt) {
	
	global $Sitewide_Security_HashMethod;
	
	// Return the triple-hashed result
	return hash($Sitewide_Security_HashMethod, hash($Sitewide_Security_HashMethod, $Pass) . hash($Sitewide_Security_HashMethod, $Salt));
	
}