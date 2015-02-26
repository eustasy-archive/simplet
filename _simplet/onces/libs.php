<?php

// TODO Don't load examples
if ( $Sitewide['AutoLoad']['Libs'] ) {
	foreach (glob($Backend['libs'].'*.php') as $Lib) {
		require_once $Lib;
	} unset($Lib);
}