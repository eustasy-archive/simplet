<?php

////	Member Group Add Function
// 
// Adds a member to a group.
// Semantically, it adds a group to a member.
// 
// Member_Group_Add('group');
// Member_Group_Add('group', 'member_id');

function Member_Group_Add($Group, $Member_ID_Override = false) {
	
	global $Database, $Member_ID, $Time;
	
	$Return = array();
	$Return['Status'] = 'Success';
	
	// If there is a Member_ID_Override, then override.
	if ( $Member_ID_Override ) $Check_Member = $Member_ID_Override;
	else $Check_Member = $Member_ID;
	
	// IFEXISTSMEMBERS
	if ( !$Database['Exists']['Members'] ) {
		$Return['Status'] = 'Failure';
		
	// IFEXISTSMEMBERS
	} else {
		
		// IFINGROUP
		if ( Member_Group_Check($Group, $Check_Member) ) {
			// NOTE: Not actually a failure.
			array_push($Return['actions'], '\''.$Check_Member.'\' is already in \''.$Group.'\'');
			
		// IFINGROUP
		} else {
			
			$Member_Group_Add = 'UPDATE `'.$Database['Prefix'].'Members` SET `Groups`=`Groups` + \'|'.$Group.'|\', `Modified`=\''.$Time.'\' WHERE `Member_ID`=\''.$Check_Member.'\'';
			
			// Execute Query
			$Member_Group_Add = mysqli_query($Database['Connection'], $Member_Group_Add, MYSQLI_STORE_RESULT);
			
			// IFQUERY
			if ( !$Member_Group_Add ) {
				$Return['Status'] = 'Failure';
				array_push($Return['actions'], '\''.$Check_Member.'\' couldn\'t be added to \''.$Group.'\'');
				if ( $Sitewide_Debug ) echo 'Invalid Query (Member_Group_Add): '.mysqli_error($Database['Connection']);
				
			// IFQUERY Query Successful
			} else {
				array_push($Return['actions'], '\''.$Check_Member.'\' added to \''.$Group.'\'');
				
			} // IFQUERY
			
		} // IFINGROUP
		
	} // IFEXISTSMEMBERS
	
	return $Return;
	
}