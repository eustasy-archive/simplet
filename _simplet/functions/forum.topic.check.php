<?php

////	Forum Topic Check Function
//
// Checks whether or not a Topic exists.
//
// Forum_Topic_Check('slug');

function Forum_Topic_Check($Topic_Slug, $Status_Check = false) {

	global $Backend, $Database, $Member;

	// IFEXISTSTOPICS
	if ( !$Database['Exists']['Topics'] ) {
		return false;
	} else {

		// Count Topics with a matching slug
		$Forum_Topic_Check_Query = 'SELECT COUNT(`Slug`) AS `Count` FROM `'.$Database['Prefix'].'Topics` WHERE `Slug`=\''.$Topic_Slug.'\'';

		// IFSTATUS
		if ( $Status_Check ) {
			if ( $Member['Auth'] ) {
				$Forum_Topic_Check_Query .= ' AND (`Status`=\'Public\' OR `Status`=\'Private\')';
			} else {
				$Forum_Topic_Check_Query .= ' AND `Status`=\'Public\'';
			}
		} // IFSTATUS

		// Execute Query
		$Forum_Topic_Check = mysqli_query($Database['Connection'], $Forum_Topic_Check_Query, MYSQLI_STORE_RESULT);

		// IFQUERY Unsuccessful
		if ( !$Forum_Topic_Check ) {
			if ( $Backend['Debug'] ) {
				echo 'Invalid Query (Forum_Topic_Check): ',mysqli_error($Database['Connection']);
			}
			return false;

		// IFQUERY Successful
		} else {
			$Forum_Topic_Check_Fetch = mysqli_fetch_assoc($Forum_Topic_Check);
			if ( $Forum_Topic_Check_Fetch['Count'] > 0 ) {
				return true;
			} else {
				return false;
			}

		} // IFQUERY

	} // IFEXISTSTOPICS

}