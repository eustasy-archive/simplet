<?php

////	Forum Category Increment Function ###
//
// Increment the Topic count cache for a given Category
//
// Forum_Category_Increment('slug');

function Forum_Category_Increment($Category_Slug) {

	global $Database, $Time;

	// IFEXISTSTOPICS
	if ( !$Database['Exists']['Topics'] ) return false;
	else {

		// Get a Count of the Topics in this Category
		$Forum_Category_Count = Forum_Category_Count($Category_Slug);

		// Update the Cached Count
		$Forum_Category_Increment_Query = 'UPDATE `'.$Database['Prefix'].'Categories` SET `Topics`=\''.$Forum_Category_Count.'\', `Modified`=\''.$Time.'\' WHERE `Slug`=\''.$Category_Slug.'\'';
		$Forum_Category_Increment = mysqli_query($Database['Connection'], $Forum_Category_Increment_Query, MYSQLI_STORE_RESULT);

		// IFQUERY Handle the response
		if ( !$Forum_Category_Increment ) {
			if ( $Sitewide_Debug ) echo 'Invalid Query (Forum_Category_Increment): '.mysqli_error($Database['Connection']);
			return false;

		// IFQUERY
		} else return true;

	} // IFEXISTSTOPICS

}