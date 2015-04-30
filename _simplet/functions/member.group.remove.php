<?php

////	Member Group Remove Function
//
// Removes a member to a group.
// Semantically, it adds a group to a member.
//
// Member_Group_Remove('group');
// Member_Group_Remove('group', 'member_id');

function Member_Group_Remove($Group, $Member_ID_Override = false) {

	global $Backend, $Database, $Member, $Time;

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
		$Member_Group_Check = mysqli_query($Database['Connection'], $Member_Group_Check, MYSQLI_STORE_RESULT);

		// IFCHECK
		if ( !$Member_Group_Check ) {
			if ( $Backend['Debug'] ) {
				echo 'Invalid Query (Member_Group_Check): ',mysqli_error($Database['Connection']);
			}
			return false;

		// IFCHECK Query Successful
		} else {

			// Fetch Results
			$Member_Group_Check_Fetch = mysqli_fetch_assoc($Member_Group_Check);

			// IFGROUP
			// WARNING: !== false is necessary because `strpos` will return `0`,
			// a false-y value, if it is the first group in the list.
			if ( strpos($Member_Group_Check_Fetch['Groups'], '|'.$Group.'|') !== false ) {

				// Replace the group with nothing.
				$Groups_New = str_replace('|'.$Group.'|', '', $Member_Group_Check_Fetch['Groups']);
				$Member_Group_Remove = 'UPDATE `'.$Database['Prefix'].'Members` SET `Groups`=\''.$Groups_New.'\', `Modified`=\''.$Time['Now'].'\' WHERE `ID`=\''.$Check_Member.'\'';
				$Member_Group_Remove = mysqli_query($Database['Connection'], $Member_Group_Remove, MYSQLI_STORE_RESULT);

				// IFREMOVE
				if ( !$Member_Group_Remove ) {
					if ( $Backend['Debug'] ) {
						echo 'Invalid Query (Member_Group_Remove): ',mysqli_error($Database['Connection']);
					}
					return false;

				// IFREMOVE Query Successful
				} else {
					return array('success' => '\''.$Check_Member.'\' was removed from \''.$Group.'\'');
				}

			// IFGROUP
			} else {
				return array('success' => '\''.$Check_Member.'\' wasn\'t in \''.$Group.'\'');
			}

		} // IFCHECK

	} // IFEXISTSMEMBERS

}