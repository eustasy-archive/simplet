<?php

// ### JSON Do Function ###
//
//

function JSONDo($Array) {

	if (version_compare(PHP_VERSION, '5.4.0', '>=')) echo json_encode($Array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	else echo json_encode($Array);

}
