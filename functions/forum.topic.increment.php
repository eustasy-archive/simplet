<?php

// ### Forum Topic Increment Function ###
//
// Increment the post cache
//
// Forum_Topic_Increment('slug');

function Forum_Topic_Increment($Topic_Slug) {

	// Set some Globals
	global $MySQL_Connection, $Time;

	$Forum_Topic_Count = Forum_Topic_Count($Topic_Slug);

	// Count things first
	$Forum_Topic_Increment_Query = 'UPDATE `Topics` SET `Responses`=\''.$Forum_Topic_Count.'\', `Modified`=\''.$Time.'\' WHERE `Slug`=\''.$Topic_Slug.'\'';

	// Get Responses
	$Forum_Topic_Increment = mysqli_query($MySQL_Connection, $Forum_Topic_Increment_Query, MYSQLI_STORE_RESULT);
	if (!$Forum_Topic_Increment) echo 'Invalid Query (Forum_Topic_Increment): '.mysqli_error($MySQL_Connection);

	return true;

}
