<?php

// ### Forum Topic Increment Function ###
//
// Increment the post cache
//
// Forum_Category_Modified('slug');

function Forum_Category_Modified($Category_Slug) {

	// Set some Globals
	global $MySQL_Connection, $Time;

	// Count things first
	$Forum_Category_Modified_Query = 'UPDATE `Categories` SET `Modified`=\''.$Time.'\' WHERE `Slug`=\''.$Category_Slug.'\'';

	// Get Responses
	$Forum_Category_Modified = mysqli_query($MySQL_Connection, $Forum_Category_Modified_Query, MYSQLI_STORE_RESULT);
	if (!$Forum_Category_Modified) echo 'Invalid Query (Forum_Category_Modified): '.mysqli_error($MySQL_Connection);

	return true;

}
