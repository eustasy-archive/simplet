<?php

////	API Output XML Function
//
// Return an array as JSON.
// Useful for APIs.
//
// $XML = API_Output_XML($Array);

function API_Output_XML($Array) {

	// IFSIMPLEXML If SimpleXML is not available, error out now.
	if ( !extension_loaded('simplexml') ) return false;
	else {

		// Create New XML
		$Return = new SimpleXMLElement('<?xml version="1.0"?><root></root>');

		// FOREACHKEY
		foreach ( $Array as $Key => $Value ) {

			// IFARRAY
			if ( is_array($Value) ) {

				// Convert Numeric Keys
				$Key = is_numeric($Key) ? 'item_'.$Key : $Key;
				// Add to XML
				$Subnode = $Return->addChild($Key);
				// WARNING: Recursive
				API_Output_XML($Value, $Subnode);

			// IFARRAY
			} else {

				// Convert Numeric Keys
				$Key = is_numeric($Key) ? 'item_'.$Key : $Key;
				// Add to XML
				$Return->addChild($Key, $Value);

			} // IFARRAY

		} // FOREACHKEY

		return $Return;

	}

}