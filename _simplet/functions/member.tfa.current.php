<?php

function Member_TFA_Current($Member_ID_Override = false) {
	global $Backend, $Database, $Member;

	if ( !$Member_ID_Override ) {
		$Member_ID_Override = $Member['ID'];
	}

	$TFA_Current = mysqli_query($Database['Connection'], 'SELECT `2fa` FROM `'.$Database['Prefix'].'Members` WHERE `ID`=\''.$Member_ID_Override.'\' AND `Status`=\'Active\' LIMIT 0, 1', MYSQLI_STORE_RESULT);
	if ( !$TFA_Current ) {
		if ( $Backend['Debug'] ) {
			echo 'Invalid Query (TFA_Current): ' . mysqli_error($Database['Connection']);
		}
		return array('Error' => 'Could not select second factor key.', 'Success' => false);
	// TODO handle zero rows
	} else {
		$TFA_Current_Fetch = mysqli_fetch_assoc($TFA_Current);
		return array('Error' => false, 'Success' => true, 'Result' => $TFA_Current_Fetch['2fa']);
	}
}