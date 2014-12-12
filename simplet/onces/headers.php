<?php

// X-Frame-Options
// Stops pages being displayed in iFrames.
// Default: DENY
// Options:
// - SAMEORIGIN
// - ALLOW-FROM $var
header('X-Frame-Options: DENY');

// Strict-Transport-Security
// Disallows connections over insecure channels.
// Defaults to on for https, off for http.
if ( $Request['Secure'] ) {
	header('Strict-Transport-Security: max-age=31536000');
}