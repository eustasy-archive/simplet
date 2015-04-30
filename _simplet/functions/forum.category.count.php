<?php

////	Forum Category Count Function
//
// Counts Topics for a given Forum Category
//
// Forum_Category_Count('slug');

function Forum_Category_Count($Category_Slug, $Status_Check = false) {

	global $Backend, $Database;

	// IFEXISTSTOPICS
	if ( !$Database['Exists']['Topics'] ) {
		return false;
	} else {

		// Count the Topics that have the Slug as the Category
		$Forum_Category_Count_Query = 'SELECT COUNT(`Category`) AS `Count` FROM `'.$Database['Prefix'].'Topics` WHERE `Category`=\''.$Category_Slug.'\' AND (`Status`=\'Public\' OR `Status`=\'Private\')';
		// TODO Implement Status Check
		$Forum_Category_Count = mysqli_query($Database['Connection'], $Forum_Category_Count_Query, MYSQLI_STORE_RESULT);

		// IFCOUNT
		if ( !$Forum_Category_Count ) {
			if ( $Backend['Debug'] ) {
				echo 'Invalid Query (Forum_Category_Count): ',mysqli_error($Database['Connection']);
			}
			return false;

		// IFCOUNT
		} else {
			// Fetch the Count and Return it
			$Forum_Category_Count_Fetch = mysqli_fetch_assoc($Forum_Category_Count);
			return $Forum_Category_Count_Fetch['Count'];

		} // IFCOUNT
	} // IFEXISTSTOPICS
}