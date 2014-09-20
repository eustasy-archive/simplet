<?php

////	Member Group List Function
// 
// Lists members in a given group.
// 
// Member_Group_List('group');
// Member_Group_List('group', true);

function Member_Group_List($Group, $Status_Check = false) {
	
	global $Database;
	
	// TODO Add Optional Status Checking
	
	// IFEXISTSMEMBERS
	if ( !$Database['Exists']['Members'] ) return false;
	else {
		
		// Find the Groups for a given Member_ID
		$Member_Group_List = 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE `Groups` LIKE \'%|'.$Group.'|%\'';
		
		// Execute Query
		$Member_Group_List = mysqli_query($Database['Connection'], $Member_Group_List, MYSQLI_STORE_RESULT);
		
		// IFQUERY
		if ( !$Member_Group_List ) {
			if ( $Sitewide_Debug ) echo 'Invalid Query (Member_Group_List): '.mysqli_error($Database['Connection']);
			return false;
			
		// IFQUERY Query Successful
		} else {
			
			// Fetch Results
			return mysqli_fetch_assoc($Member_Group_List);
			
		} // IFQUERY
		
	} // IFEXISTSMEMBERS
	
}