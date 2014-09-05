<?php

////	Forum Topic Check Function
//
// Checks whether or not a Topic exists.
//
// Forum_Topic_Check('slug');

function Forum_Topic_Check($Topic_Slug, $Status_Check = false) {
	
	global $Database, $Member_Auth;
	
	// Count Topics with a matching slug
	$Forum_Topic_Check_Query = 'SELECT COUNT(`Slug`) AS `Count` FROM `'.$Database['Prefix'].'Topics` WHERE `Slug`=\''.$Topic_Slug.'\'';
	
	// IFSTATUS
	if ( $Status_Check ) {
		if ($Member_Auth) $Forum_Topic_Check_Query .= ' AND (`Status`=\'Public\' OR `Status`=\'Private\')';
		else $Forum_Topic_Check_Query .= ' AND `Status`=\'Public\'';
	} // IFSTATUS
	
	// Execute Query
	$Forum_Topic_Check = mysqli_query($Database['Connection'], $Forum_Topic_Check_Query, MYSQLI_STORE_RESULT);
<<<<<<< HEAD
	if (!$Forum_Topic_Check) exit('Invalid Query (Forum_Topic_Check): '.mysqli_error($Database['Connection']));

	$Forum_Topic_Check_Fetch = mysqli_fetch_assoc($Forum_Topic_Check);

	if ($Forum_Topic_Check_Fetch['Count'] > 0) return true;
	else return false;

=======
	
	// IFQUERY Unsuccessful
	if ( !$Forum_Topic_Check ) {
		if ( $Sitewide_Debug) echo 'Invalid Query (Forum_Topic_Check): '.mysqli_error($Database['Connection']);
		return false;
		
	// IFQUERY Successful
	} else {
		$Forum_Topic_Check_Fetch = mysqli_fetch_assoc($Forum_Topic_Check);
		if ($Forum_Topic_Check_Fetch['Count'] > 0) return true;
		else return false;
		
	} // IFQUERY
	
>>>>>>> origin/table-check-and-prefix
}