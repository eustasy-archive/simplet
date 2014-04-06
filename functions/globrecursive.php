<?php

// ### Glob Recursive Function ###
//
//

function globRecursive($Pattern, $Flags = 0){

	$Return = glob($Pattern, $Flags);

	foreach (glob(dirname($Pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $Directory) {
		// This is a recursive function.
		// Usually, THIS IS VERY BAD.
		// For searching recursively however,
		// it makes sense.
		$Return = array_merge($Return, globRecursive($Directory.'/'.basename($Pattern), $Flags));
	}

	return $Return;

}