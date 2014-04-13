<?php

// ### Forum Category Increment Function ###
//
// Increment the topic cache
//
// Forum_Category_Increment('slug');

function Forum_Category_Increment($Category_Slug) {

	// Set some Globals
	global $MySQL_Connection, $Time;

	$Forum_Category_Count = Forum_Category_Count($Category_Slug);

	// Count things first
	$Forum_Category_Increment_Query = 'UPDATE `Categories` SET `Topics`=\''.$Forum_Category_Count.'\', `Modified`=\''.$Time.'\' WHERE `Slug`=\''.$Category_Slug.'\'';

	// Get Responses
	$Forum_Category_Increment = mysqli_query($MySQL_Connection, $Forum_Category_Increment_Query, MYSQLI_STORE_RESULT);
	if (!$Forum_Category_Increment) {
		echo 'Invalid Query (Forum_Category_Increment): '.mysqli_error($MySQL_Connection);
		return false;
	}

	// Presume it went okay.
	return true;

}
