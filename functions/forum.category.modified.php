<?php

////	Forum Category Modified Function
//
// Update the Category Modified Time
//
// Forum_Category_Modified('slug');

function Forum_Category_Modified($Category_Slug) {
	
	global $Database, $Time;
	
	// IFEXISTSCATEGORIES
	if ( !$Database['Exists']['Categories'] ) return false;
	else {
		
		// Update the Modified Time
		$Forum_Category_Modified_Query = 'UPDATE `'.$Database['Prefix'].'Categories` SET `Modified`=\''.$Time.'\' WHERE `Slug`=\''.$Category_Slug.'\'';
		
		// Execute Query
		$Forum_Category_Modified = mysqli_query($Database['Connection'], $Forum_Category_Modified_Query, MYSQLI_STORE_RESULT);
		
		// IFQUERY
		if ( !$Forum_Category_Modified ) {
			
			if ( $Sitewide_Debug) echo 'Invalid Query (Forum_Category_Modified): '.mysqli_error($Database['Connection']);
			return false;
			
		// IFQUERY
		} else return true;
		// IFQUERY
		
	} // IFEXISTSCATEGORIES
	
}