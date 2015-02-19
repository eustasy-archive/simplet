<?php

if ( is_readable($Backend['libs'].'here-miss.php') ) {
	require_once $Backend['libs'].'here-miss.php';
} else {
	$trackme = true;
}

$User['Tracking'] = $trackme;