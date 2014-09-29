<?php

////	Glob Recursive Function
//
// Glob Recursively to a Pattern

function globRecursive($Pattern, $Flags = 0) {

	// Search in the Current Directory
	$Return = glob($Pattern, $Flags);

	// FOREACHDIRECTORY
	// Search in ALL sub-directories.
	foreach (glob(dirname($Pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $Directory) {
		// This is a recursive function.
		// Usually, THIS IS VERY BAD.
		// For searching recursively however,
		// it does make some sense.
		$Return = array_merge($Return, globRecursive($Directory.'/'.basename($Pattern), $Flags));
	} // FOREACHDIRECTORY

	return $Return;

}