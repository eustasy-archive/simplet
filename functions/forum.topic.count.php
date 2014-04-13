<?php

// ### Forum Topic Check Function ###
//
// Checks whether or not a category exists.
//
// Forum_Topic_Count('slug');

function Forum_Topic_Count($Topic_Slug, $Status_Check = false) {

	// Set some Globals
	global $MySQL_Connection;

	// Get Responses
	$Forum_Topic_Count = mysqli_query($MySQL_Connection, 'SELECT COUNT(`Canonical`) AS `Count` FROM `Responses` WHERE `Canonical`=\''.$Topic_Slug.'\' AND (`Status`=\'Public\' OR `Status`=\'Private\')', MYSQLI_STORE_RESULT);
	if (!$Forum_Topic_Count) exit('Invalid Query (Forum_Topic_Count): '.mysqli_error($MySQL_Connection));

	$Forum_Topic_Count_Fetch = mysqli_fetch_assoc($Forum_Topic_Count);

	return $Forum_Topic_Count_Fetch['Count'];

}
