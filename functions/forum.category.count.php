<?php

// ### Forum Category Count Function ###
//
// Counts Topics for a given Forum Category
//
// Forum_Category_Count('slug');

function Forum_Category_Count($Category_Slug, $Status_Check = false) {

	// Set some Globals
	global $MySQL_Connection;

	// Get Responses
	$Forum_Category_Count = mysqli_query($MySQL_Connection, 'SELECT COUNT(`Category`) AS `Count` FROM `Topics` WHERE `Category`=\''.$Category_Slug.'\' AND (`Status`=\'Public\' OR `Status`=\'Private\')', MYSQLI_STORE_RESULT);
	if (!$Forum_Category_Count) exit('Invalid Query (Forum_Category_Count): '.mysqli_error($MySQL_Connection));

	$Forum_Category_Count_Fetch = mysqli_fetch_assoc($Forum_Category_Count);

	return $Forum_Category_Count_Fetch['Count'];

}
