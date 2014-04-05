<?php

// ### Forum Topic Check Function ###
//
// Checks whether or not a category exists.
//
// Forum_Topic_Check('slug');

function Forum_Topic_Check($Topic_Slug, $Status_Check = false) {

	// Set some Globals
	global $MySQL_Connection, $Member_Auth;

	// Count things first
	$Forum_Topic_Check_Query = 'SELECT COUNT(`Slug`) AS `Count` FROM `Topics` WHERE `Slug`=\''.$Topic_Slug.'\'';

	if ($Status_Check) {
		// Limit by Status
		if ($Member_Auth) $Forum_Topic_Check_Query .= ' AND (`Status`=\'Public\' OR `Status`=\'Private\')';
		else $Forum_Topic_Check_Query .= ' AND `Status`=\'Public\'';
	}

	// Order by Creation
	$Forum_Topic_Check_Query .= ' ORDER BY `Modified` DESC';

	// Get Responses
	$Forum_Topic_Check = mysqli_query($MySQL_Connection, $Forum_Topic_Check_Query, MYSQLI_STORE_RESULT);
	if (!$Forum_Topic_Check) exit('Invalid Query (Forum_Topic_Check): '.mysqli_error($MySQL_Connection));

	$Forum_Topic_Check_Fetch = mysqli_fetch_assoc($Forum_Topic_Check);

	if ($Forum_Topic_Check_Fetch['Count'] > 0) return true;
	else return false;

}
