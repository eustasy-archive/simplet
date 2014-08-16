<?php

// ### Password Hash Function ###
//
// A basic password and salt hasher.
//
// Password_Hash($Pass, $Salt) returns a hash.
// Compare to one stored to determine authenticity.

function Password_Hash($Pass, $Salt) {

	// Choose a hash method.
	$Hash_Method = 'sha512';
	// Note: Could also use sha1, sha512 etc, etc
	// Note: See also https://github.com/eustasy/labs-hash-check

	// Return the triple-hashed result
	return hash($Hash_Method, hash($Hash_Method, $Pass) . hash($Hash_Method, $Salt));

}