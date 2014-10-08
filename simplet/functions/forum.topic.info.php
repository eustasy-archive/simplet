<?php

////	Forum Topic Info Function
// 
// Returns an array of all the available information on a given Topic
// 
// Forum_Topic_Info('slug');

function Forum_Topic_Info($Topic_Slug) {
	
	global $Database, $Member_Auth;
	
	// IFEXISTSTOPICS
	if ( !$Database['Exists']['Topics'] ) return false;
	else {
		
		// Create Query
		$Forum_Topic_Info_Query = 'SELECT * FROM `'.$Database['Prefix'].'Topics` WHERE `Slug`=\''.$Topic_Slug.'\' AND';
		// Limit by Status
		if ($Member_Auth) $Forum_Topic_Info_Query .= ' (`Status`=\'Public\' OR `Status`=\'Private\')';
		else $Forum_Topic_Info_Query .= ' `Status`=\'Public\'';
		// Order by Creation
		$Forum_Topic_Info_Query .= ' ORDER BY `Modified` DESC';
		
		// Execute Query
		$Forum_Topic_Info = mysqli_query($Database['Connection'], $Forum_Topic_Info_Query, MYSQLI_STORE_RESULT);
		
		// IFQUERY Unsuccessful
		if (!$Forum_Topic_Info) {
			if ( $Sitewide_Debug ) echo 'Invalid Query (Forum_Topic_Info): '.mysqli_error($Database['Connection']);
			return false;
			
		// IFQUERY Successful
		} else {
			
			$Forum_Topic_Info_Count = mysqli_num_rows($Forum_Topic_Info);
			
			// IFCOUNT Successful
			if ( $Forum_Topic_Info_Count > 0 ) {
				$Forum_Topic_Info_Fetch = mysqli_fetch_assoc($Forum_Topic_Info);
				return $Forum_Topic_Info_Fetch;
				
			// IFCOUNT Unsuccessful
			} else return false;
			
		} // IFQUERY
		
	} // IFEXISTSTOPICS
	
}