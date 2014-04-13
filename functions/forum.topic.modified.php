<?php

// ### Forum Topic Modified Function ###
//
// Make Modified = now
//
// Forum_Topic_Modified('slug');

function Forum_Topic_Modified($Topic_Slug) {

	// Set some Globals
	global $MySQL_Connection, $Time;

	// Count things first
	$Forum_Topic_Modified_Query = 'UPDATE `Topics` SET `Modified`=\''.$Time.'\' WHERE `Slug`=\''.$Topic_Slug.'\'';

	// Get Responses
	$Forum_Topic_Modified = mysqli_query($MySQL_Connection, $Forum_Topic_Modified_Query, MYSQLI_STORE_RESULT);
	if (!$Forum_Topic_Modified) echo 'Invalid Query (Forum_Topic_Modified): '.mysqli_error($MySQL_Connection);

	return true;

}
