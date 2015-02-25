<?php

// Scheme & Security
if (
	isset($_SERVER['HTTPS']) &&
	$_SERVER['HTTPS'] != 'off'
) {
	$Request['Scheme'] = 'https';
	$Request['Secure'] = true;
} else {
	$Request['Scheme'] = 'http';
	$Request['Secure'] = false;
}

// Host
$Request['Host'] = $_SERVER['SERVER_NAME'];

// Paths, Queries and Fragments
$Request['Path'] = explode('#', $_SERVER['REQUEST_URI']);
if ( isset($Request['Path'][1]) ) {
	$Request['Fragment'] = $Request['Path'][1];
}
$Request['Path'] = explode('?', $Request['Path'][0]);
if ( isset($Request['Path'][1]) ) {
	$Request['Query'] = $Request['Path'][1];
}
$Request['Path'] = $Request['Path'][0];

// TODO
// Cookie Setting
// A little odd this is here...
$Request['HTTPOnly'] = true;