<?php

function Member_TFA_Current() {
	global $Backend, $Database, $Member;

	$TFA_Current = mysqli_query($Database['Connection'], 'SELECT `2fa` FROM `'.$Database['Prefix'].'Members` WHERE `ID`=\''.$Member['ID'].'\' AND `Status`=\'Active\' LIMIT 0, 1', MYSQLI_STORE_RESULT);
	if ( !$TFA_Current ) {
		if ( $Backend['Debug'] ) {
			echo 'Invalid Query (TFA_Current): ' . mysqli_error($Database['Connection']);
		}
		return array('Error' => 'Could not select second factor key.', 'Success' => false);
	} else {
		$TFA_Current_Fetch = mysqli_fetch_assoc($TFA_Current);
		return array('Error' => false, 'Success' => true, 'Result' => $TFA_Current_Fetch['2fa']);
	}
}