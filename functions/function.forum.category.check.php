<?php

// ### Forum Category Check Function ###
//
// Checks whether or not a category exists.
//
// Forum_Category_Check('slug');

function Forum_Category_Check($Category_Slug) {

	// Set some Globals
	global $MySQL_Connection, $Member_Auth;

	// Count things first
	$Forum_Category_Check_Query_Select = 'SELECT COUNT(`Slug`) AS `Count` FROM `Categories` WHERE `Slug`=\''.$Category_Slug.'\' AND';

	// Limit by Status
	if ($Member_Auth) $Forum_Category_Check_Query_Status = ' (`Status`=\'Public\' OR `Status`=\'Private\')';
	else $Forum_Category_Check_Query_Status = ' `Status`=\'Public\'';

	// Order by Creation
	$Forum_Category_Check_Query_Order = ' ORDER BY `Modified` DESC';

	// Build Responses_Query
	$Forum_Category_Check_Query = $Forum_Category_Check_Query_Select.$Forum_Category_Check_Query_Status.$Forum_Category_Check_Query_Order;

	// Get Responses
	$Forum_Category_Check = mysqli_query($MySQL_Connection, $Forum_Category_Check_Query, MYSQLI_STORE_RESULT);
	if (!$Forum_Category_Check) exit('Invalid Query (Forum_Category_Check): '.mysqli_error($MySQL_Connection));

	$Forum_Category_Check_Fetch = mysqli_fetch_assoc($Forum_Category_Check);

	if ($Forum_Category_Check_Fetch['Count'] > 0) return true;
	else return false;

}
