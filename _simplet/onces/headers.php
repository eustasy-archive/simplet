<?php

// Strict-Transport-Security
// Disallows connections over insecure channels.
// Defaults to on for https, off for http.
if ( $Request['Secure'] ) {
	header('Strict-Transport-Security: max-age=31536000');
}

// Access-Control-Allow-Origin
// CORS: Cross-Origin Resource Sharing
header('Access-Control-Allow-Origin: origin');

// Content-Security-Policy
// X-Content-Security-Policy
// X-WebKit-CSP
$CSP = 'default-src \'self\'; script-src \'self\' \'unsafe-inline\' cdn.jsdelivr.net 1.2.3.8 *.google-analytics.com; style-src \'self\' \'unsafe-inline\' cdn.jsdelivr.net; font-src \'self\' \'unsafe-inline\' cdn.jsdelivr.net; img-src *; media-src * mediastream:; frame-src *; object-src *; child-src *; frame-ancestors \'none\'; form-action \'self\'; connect-src \'self\' cdn.jsdelivr.net 1.2.3.8 *.google-analytics.com; report-uri \'//'.$Sitewide['Root'].'/api?csp-report\';';
header('X-WebKit-CSP: '.$CSP);
header('X-Content-Security-Policy: '.$CSP);
header('Content-Security-Policy: '.$CSP);

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