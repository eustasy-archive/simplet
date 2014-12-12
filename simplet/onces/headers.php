<?php

// Strict-Transport-Security
// Disallows connections over insecure channels.
// Defaults to on for https, off for http.
if ( $Request['Secure'] ) {
	header('Strict-Transport-Security: max-age=31536000');
}

// X-Content-Type-Options
// Stop content loading as a different MIME Type.
// Default: NOSNIFF
header('X-Content-Type-Options: NOSNIFF');

// X-Frame-Options
// Stops pages being displayed in iFrames.
// Default: DENY
// Options:
// - SAMEORIGIN
// - ALLOW-FROM $var
header('X-Frame-Options: DENY');

// X-XSS-Protection
// Stops pages being compromised by cross-site scripting.
// Default: 1
// Alternative: 0
header('X-XSS-Protection: 1');