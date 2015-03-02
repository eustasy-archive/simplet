<?php

////	Member Group List Function
//
// Lists members in a given group.
//
// Member_Group_List('group');
// Member_Group_List('group', true);

function Member_Group_List($Group, $Status_Check = false) {

	global $Backend, $Database;

	// IFEXISTSMEMBERS
	if ( !$Database['Exists']['Members'] ) {
		return false;
	} else {

		// Find the Groups for a given Member_ID
		$Member_Group_List = 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE `Groups` LIKE \'%|'.$Group.'|%\'';
		if ( $Status_Check ) {
			$Member_Group_List .= ' AND `Status`=\'Active\'';
		}

		// Execute Query
		$Member_Group_List = mysqli_query($Database['Connection'], $Member_Group_List, MYSQLI_STORE_RESULT);

		// IFQUERY
		if ( !$Member_Group_List ) {
			if ( $Backend['Debug'] ) {
				echo 'Invalid Query (Member_Group_List): '.mysqli_error($Database['Connection']);
			}
			return false;

		// IFQUERY Query Successful
		} else {

			$Return = array();

			// Fetch Results
			while ($Member_Group_List_Result = mysqli_fetch_assoc($Member_Group_List)) {
				$Return[$Member_Group_List_Result['ID']] = array();
				foreach ($Member_Group_List_Result as $Key => $Value) {
					$Return[$Member_Group_List_Result['ID']][$Key] = $Value;
				}
			}

			return $Return;

		} // IFQUERY

	} // IFEXISTSMEMBERS

}