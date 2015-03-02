<?php

$Browning_Example = $Backend['libs'].'config.browning.example.php';
if ( is_readable($Browning_Example) ) {
	require_once $Browning_Example;
}

// TODO Don't load examples
if ( $Sitewide['AutoLoad']['Libs'] ) {
	foreach (glob($Backend['libs'].'*.php') as $Lib) {
		require_once $Lib;
	} unset($Lib);
}