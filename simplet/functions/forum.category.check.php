<?php

////	Forum Category Check Function
//
// Checks whether or not a category exists.
//
// Forum_Category_Check('slug');

function Forum_Category_Check($Category_Slug, $Status_Check = false) {

	global $Database, $Member_Auth;

	// IFEXISTSCATEGORIES
	if ( !$Database['Exists']['Categories'] ) return false;
	else {

		// Count the number of Categories with a matching slug
		$Forum_Category_Check_Query = 'SELECT COUNT(`Slug`) AS `Count` FROM `'.$Database['Prefix'].'Categories` WHERE `Slug`=\''.$Category_Slug.'\'';

		// IFSTATUS
		if ( $Status_Check ) {
			// Limit by Status
			if ( $Member_Auth ) $Forum_Category_Check_Query .= ' AND(`Status`=\'Public\' OR `Status`=\'Private\')';
			else $Forum_Category_Check_Query .= ' AND `Status`=\'Public\'';
		} // IFSTATUS

		// Execute Query
		$Forum_Category_Check = mysqli_query($Database['Connection'], $Forum_Category_Check_Query, MYSQLI_STORE_RESULT);

		// IFQUERY
		if ( !$Forum_Category_Check ) {
			if ( $Sitewide_Debug ) echo 'Invalid Query (Forum_Category_Check): '.mysqli_error($Database['Connection']);
			return false;

		// IFQUERY Query Successful
		} else {

			// Fetch Results
			$Forum_Category_Check_Fetch = mysqli_fetch_assoc($Forum_Category_Check);

			// Return Results
			if ($Forum_Category_Check_Fetch['Count'] > 0) return true;
			else return false;

		} // IFQUERY

	} // IFEXISTSCATEGORIES

}