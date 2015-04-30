<?php

////	Account Change Name Function
//
// Changes an accounts name.

function Member_Change_Name() {

	global $Backend, $Database, $Error, $Member, $Sitewide, $Success, $Time;

	// IF CSRF Okay
	if ( Runonce_CSRF_Check($_POST['csrf_protection']) ) {

		// Sanitize Name
		$Member_Name_New = Input_Prepare($_POST['name']);

		// IF Name is Empty
		if ( empty($Member_Name_New) ) {
			$Error = '<h3 class="color-pomegranate">Your new Name cannot be empty.</h3>';
		// END IF Name is Empty

		// IF Name is not Empty
		} else {
			// Construct Query
			$Name_Change = 'UPDATE `'.$Database['Prefix'].'Members`';
			$Name_Change .= ' SET `Name`=\''.$Member_Name_New.'\', `Modified`=\''.$Time['Now'].'\'';
			$Name_Change .= ' WHERE `ID`=\''.$Member['ID'].'\' AND `Status`=\'Active\'';

			// Execute Query
			$Name_Change = mysqli_query($Database['Connection'], $Name_Change, MYSQLI_STORE_RESULT);

			// IF Name not Changed
			if ( !$Name_Change ) {
				if ( $Backend['Debug'] ) {
					echo 'Invalid Query (Name_Change): ',mysqli_error($Database['Connection']);
				}
				$Error = '<h3 class="color-pomegranate">Name could not be changed.</h3>';
			// END IF Name not Changed

			// IF Name Changed
			} else {
				// Redirect
				header('Location: '.$Sitewide['Account'], true, 302);
				$Success = true;
				// exit;
			} // IF Name Changed

		} // END IF Name is not Empty
	// END IF CSRF Okay

	// IF CSRF Not Okay
	} else {
		$Error = '<h3 class="color-pomegranate margin-0">Your Name could not be changed.</h3>';
		$Error .= '<p class="text-center">Your security token did not match. Please try again.</p><br>';
	} // END IF CSRF Not Okay

}