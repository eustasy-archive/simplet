<?php

////	Forum Topic Modified Function
//
// Set the Topic Modification Time to Now
//
// Forum_Topic_Modified('slug');

function Forum_Topic_Modified($Topic_Slug) {
	
	global $Database, $Time;
	
	// Count things first
	$Forum_Topic_Modified = 'UPDATE `'.$Database['Prefix'].'Topics` SET `Modified`=\''.$Time.'\' WHERE `Slug`=\''.$Topic_Slug.'\'';
	
	// Execute Query
	$Forum_Topic_Modified = mysqli_query($Database['Connection'], $Forum_Topic_Modified, MYSQLI_STORE_RESULT);
	
	// IFQUERY
	if ( !$Forum_Topic_Modified ) {
		if ( $Sitewide_Debug ) echo 'Invalid Query (Forum_Topic_Modified): '.mysqli_error($Database['Connection']);
		return false;
		
	// IFQUERY
	} else return true;
	
}