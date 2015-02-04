<?php

////	String Generator Function
//
// A Random String Generator for Salts, Cookies, and other Uniques (remember to check uniqueness).
//
// Generator_String_Secure() gives a 64 character random string of lower-case letters and numbers
// Generator_String_Secure(n) gives a "n" character random string of lower-case letters and numbers

function Generator_String_Secure($Length = 64) {
	$String = substr(
		bin2hex(
			openssl_random_pseudo_bytes(
				ceil(
					$Length / 2
				)
			)
		),
		0,
		$Length
	);
	return $String;
}