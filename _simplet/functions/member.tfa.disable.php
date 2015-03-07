<?php

function Member_TFA_Disable() {
	global $Backend, $Database, $Member, $Time;

	$TFA_Disable = 'UPDATE `'.$Database['Prefix'].'Members` SET `2fa`=\'\', `Modified`=\''.$Time['Now'].'\' WHERE `ID`=\''.$Member['ID'].'\' AND `Status`=\'Active\' LIMIT 1';
	$TFA_Disable = mysqli_query($Database['Connection'], $TFA_Disable, MYSQLI_STORE_RESULT);
	if ( !$TFA_Disable ) {
		if ( $Backend['Debug'] ) {
			echo 'Invalid Query (TFA_Disable): ' . mysqli_error($Database['Connection']);
		}
		return false;
	} else {
		return true;
	}
}