<?php

////	Forum Category Info Function
//
// Returns an array of all the information on a given Category
//
// Forum_Category_Info('slug');

function Forum_Category_Info($Category_Slug) {

	global $Database, $Member_Auth;

	// IFEXISTSCATEGORIES
	if ( !$Database['Exists']['Categories'] ) return false;
	else {

		// Select everything we know about the Category
		$Forum_Category_Info_Query = 'SELECT * FROM `'.$Database['Prefix'].'Categories` WHERE `Slug`=\''.$Category_Slug.'\' AND';

		// Limit by Status
		if ( $Member_Auth ) $Forum_Category_Info_Query .= ' (`Status`=\'Public\' OR `Status`=\'Private\')';
		else $Forum_Category_Info_Query .= ' `Status`=\'Public\'';

		// Execute Query
		$Forum_Category_Info = mysqli_query($Database['Connection'], $Forum_Category_Info_Query, MYSQLI_STORE_RESULT);

		// IFQUERY If Query Failed
		if ( !$Forum_Category_Info ) {
			if ( $Sitewide_Debug ) echo 'Invalid Query (Forum_Category_Info): '.mysqli_error($Database['Connection']);
			return false;

		// IFQUERY If 0 Results
		} else if ( !mysqli_num_rows($Forum_Category_Info) ) return false;

		// IFQUERY If Query Successful
		else {

			// Return Results
			return mysqli_fetch_assoc($Forum_Category_Info);

		} // IFQUERY

	} // IFEXISTSCATEGORIES

}