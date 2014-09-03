<?php

////	JSON Do Function
// 
// Return an array as JSON.
// Useful for APIs.
// 
// TODO BREAKING
// Consider also allowing a GET flag to request XML.
// (Change function name as appropriate.)

function JSONDo($Array) {
	
	// IFPRETTY If they request pretty printing and it is available.
	if ( isset($_GET['pretty']) && version_compare(PHP_VERSION, '5.4.0', '>=') ) echo json_encode($Array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	else echo json_encode($Array);
	
}