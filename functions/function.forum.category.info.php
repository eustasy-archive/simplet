<?php

// ### Forum Category Info Function ###
//
// Checks whether or not a category exists.
//
// Forum_Category_Info('slug');

function Forum_Category_Info($Category_Slug) {

	// Set some Globals
	global $MySQL_Connection, $Member_Auth;

	// Count things first
	$Forum_Category_Info_Query_Select = 'SELECT * FROM `Categories` WHERE `Slug`=\''.$Category_Slug.'\' AND';

	// Limit by Status
	if ($Member_Auth) $Forum_Category_Info_Query_Status = ' (`Status`=\'Public\' OR `Status`=\'Private\')';
	else $Forum_Category_Info_Query_Status = ' `Status`=\'Public\'';

	// Order by Creation
	$Forum_Category_Info_Query_Order = ' ORDER BY `Modified` DESC';

	// Build Responses_Query
	$Forum_Category_Info_Query = $Forum_Category_Info_Query_Select.$Forum_Category_Info_Query_Status.$Forum_Category_Info_Query_Order;

	// Get Responses
	$Forum_Category_Info = mysqli_query($MySQL_Connection, $Forum_Category_Info_Query, MYSQLI_STORE_RESULT);
	if (!$Forum_Category_Info) exit('Invalid Query (Forum_Category_Info): '.mysqli_error($MySQL_Connection));

	$Forum_Category_Info_Count = mysqli_num_rows($Forum_Category_Info);

	if ($Forum_Category_Info_Count === 0) return false;
	else {
		$Forum_Category_Info_Fetch = mysqli_fetch_assoc($Forum_Category_Info);
		return $Forum_Category_Info_Fetch;
	}

}