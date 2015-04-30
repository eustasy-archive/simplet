<?php

////	Forum Topic Modified Function
//
// Set the Topic Modification Time to Now
//
// Forum_Topic_Modified('slug');

function Forum_Topic_Modified($Topic_Slug) {

	global $Backend, $Database, $Time;

	// IFEXISTSTOPICS
	if ( !$Database['Exists']['Topics'] ) {
		return false;
	} else {

		// Count things first
		$Forum_Topic_Modified = 'UPDATE `'.$Database['Prefix'].'Topics` SET `Modified`=\''.$Time['Now'].'\' WHERE `Slug`=\''.$Topic_Slug.'\'';

		// Execute Query
		$Forum_Topic_Modified = mysqli_query($Database['Connection'], $Forum_Topic_Modified, MYSQLI_STORE_RESULT);

		// IFQUERY
		if ( !$Forum_Topic_Modified ) {
			if ( $Backend['Debug'] ) {
				echo 'Invalid Query (Forum_Topic_Modified): ',mysqli_error($Database['Connection']);
			}
			return false;

		// IFQUERY
		} else {
			return true;
		}

	} // IFEXISTSTOPICS

}