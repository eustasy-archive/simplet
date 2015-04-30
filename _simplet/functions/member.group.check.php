<?php

////	Member Group Check Function
//
// Checks whether or not a member is in a given group.
//
// Member_Group_Check('group');
// Member_Group_Check('group', 'member_id');

function Member_Group_Check($Group, $Member_ID_Override = false) {

	global $Backend, $Database, $Member;

	// If there is a Member_ID_Override, then override.
	if ( $Member_ID_Override ) {
		$Check_Member = $Member_ID_Override;
	} else {
		$Check_Member = $Member['ID'];
	}

	// IFEXISTSMEMBERS
	if ( !$Database['Exists']['Members'] ) {
		return false;
	} else {

		// Find the Groups for a given Member_ID
		$Member_Group_Check = 'SELECT `Groups` FROM `'.$Database['Prefix'].'Members` WHERE `ID`=\''.$Check_Member.'\'';
		// TODO Security: Discuss implications of not checking status.

		// Execute Query
		$Member_Group_Check = mysqli_query($Database['Connection'], $Member_Group_Check, MYSQLI_STORE_RESULT);

		// IFQUERY
		if ( !$Member_Group_Check ) {
			if ( $Backend['Debug'] ) {
				echo 'Invalid Query (Member_Group_Check): ',mysqli_error($Database['Connection']);
			}
			return false;

		// IFQUERY Query Successful
		} else {

			// Fetch Results
			$Member_Group_Check_Fetch = mysqli_fetch_assoc($Member_Group_Check);

			// IFGROUP
			// WARNING: !== false is necessary because `strpos` will return `0`,
			// a false-y value, if it is the first group in the list.
			if ( strpos($Member_Group_Check_Fetch['Groups'], '|'.$Group.'|') !== false ) {
				return array('success' => '\''.$Check_Member.'\' is in a member of \''.$Group.'\'');
			} else {
				return false;
			} // IFGROUP

		} // IFQUERY

	} // IFEXISTSMEMBERS

}