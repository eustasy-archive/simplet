<?php

if ( $Sitewide['AutoLoad']['Functions'] ) {
	foreach (glob($Backend['functions'].'*.php') as $Function) {
		require_once $Function;
	} unset($Function);
}