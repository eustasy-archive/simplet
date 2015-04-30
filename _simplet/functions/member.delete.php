<?php

////	Account Delete Function
//
// Deletes a members account.

function Member_Delete() {

	global $Backend, $Database, $Error, $Member, $Success, $Time;

	// IF CSRF Not Okay
	if ( !Runonce_CSRF_Check($_POST['csrf_protection']) ) {
		$Error = '<h3 class="color-pomegranate margin-0">Your Name could not be changed.</h3>';
		$Error .= '<p class="text-center">Your security token did not match. Please try again.</p><br>';
	// END IF CSRF not Okay

	// IF CSRF Okay
	} else {

		// Sanitize Key
		$Key = Input_Prepare($_POST['delete_key']);

		// IF Delete Key not Okay
		if ( !Runonce_Check($Key, $Member['ID'], 'Account Deletion') ) {
			$Error = '
					<h2>Error: Invalid Key</h2>
					<h3>User deletion keys are only valid for three minutes.</h3>';
		// END IF Delete Key not Okay

		// IF Delete Key is Okay
		} else {

			// Construct Query
			$Member_Delete = 'UPDATE `'.$Database['Prefix'].'Members` SET `Status`=\'Deactivated\', `Modified`=\''.$Time['Now'].'\' WHERE `ID`=\''.$Member['ID'].'\'';
			// Execute Query
			$Member_Delete = mysqli_query($Database['Connection'], $Member_Delete, MYSQLI_STORE_RESULT);

			// IF Not Deleted
			if ( !$Member_Delete ) {
				if ( $Backend['Debug'] ) {
					echo 'Invalid Query (Member_Delete): ',mysqli_error($Database['Connection']);
				}
				$Error = '<h3 class="color-pomegranate">Sorry, your account could not be deleted.</h3>';
			// END IF Not Deleted

			// IF Deleted
			} else {
				Runonce_Delete($Key, $Member['ID']);
				$Success = '
					<h2>Account Deleted</h2>
					<h3>You no longer exist. Bye.</h3>';
			} // END IF Deleted

		} // END IF Delete Key is Okay

	} // END IF CSRF Okay

}