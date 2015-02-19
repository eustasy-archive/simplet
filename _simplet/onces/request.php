<?php

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

$Request['Host'] = $_SERVER['SERVER_NAME'];
$Request['Path'] = $_SERVER['REQUEST_URI'];

$Request['HTTPOnly'] = true;