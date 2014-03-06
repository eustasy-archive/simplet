<?php

function Categories($Caller = 'index.php', $Exclude = false) {

	// Set some Globals so the required scripts don't error.
	global $Place, $Request_Path_Entities;

	// List all the files
	$Items = glob('*.php', GLOB_NOSORT);

	$Categories_Return = array();

	// Order them by time
	array_multisort(array_map('filemtime', $Items), SORT_NUMERIC, SORT_DESC, $Items);

	// FOREACH: For each Item
	foreach ($Items as $Item) {

		// IFNOTTHIS: So long as it isn't this file
		if ($Item != $Caller) {

			// Require it
			require $Item;

			// IFPOST
			if ($Post_Type === 'Blog Post') {

				// Add Category or Increment
				if (isset($Categories_Return[$Post_Category])) {
					$Categories_Return[$Post_Category] += 1;
				} else {
					$Categories_Return[$Post_Category] = 1;
				}

			} // IFPOST

		} // IFNOTTHIS

	} // FOREACH

	// Unset current page to avoid require problems
	if ($Exclude) unset($Categories_Return['']);
	if ($Exclude) unset($Categories_Return[$Exclude]);

	// Order by Count
	arsort($Categories_Return);

	// Return Array
	return $Categories_Return;

}