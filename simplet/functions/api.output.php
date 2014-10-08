<?php

////	API Output Function
//
// Return an array as in a requested format.
// Useful for APIs.

function API_Output($Array) {

	// IFXML If they request XML and SimpleXML is loaded.
	if ( isset($_GET['xml']) && extension_loaded('simplexml') ) return API_Output_XML($Array);
	// IFPRETTY If they request pretty printing and it is available.
	else if ( isset($_GET['pretty']) && version_compare(PHP_VERSION, '5.4.0', '>=') ) return json_encode($Array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	else return json_encode($Array);

}