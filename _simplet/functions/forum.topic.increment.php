<?php

////	Forum Topic Increment Function
//
// Increment the Responses count cache for a given Topic.
//
// Forum_Topic_Increment('slug');

function Forum_Topic_Increment($Topic_Slug) {

	global $Database, $Sitewide_Debug, $Time;

	// IFEXISTSTOPICS
	if ( !$Database['Exists']['Topics'] ) return false;
	else {

		// Count the Responses to the Topic
		$Forum_Topic_Count = Forum_Topic_Count($Topic_Slug);

		// Set Responses
		$Forum_Topic_Increment_Query = 'UPDATE `'.$Database['Prefix'].'Topics` SET `Responses`=\''.$Forum_Topic_Count.'\', `Modified`=\''.$Time.'\' WHERE `Slug`=\''.$Topic_Slug.'\'';

		// Execute Query
		$Forum_Topic_Increment = mysqli_query($Database['Connection'], $Forum_Topic_Increment_Query, MYSQLI_STORE_RESULT);

		// IFQUERY
		if ( !$Forum_Topic_Increment ) {
			if ( $Sitewide_Debug ) echo 'Invalid Query (Forum_Topic_Increment): '.mysqli_error($Database['Connection']);
			return false;

		// IFQUERY
		} else return true;

	} // IFEXISTSTOPICS

}