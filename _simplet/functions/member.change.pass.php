<?php

////	Account Change Pass Function
//
// Changes an accounts pass.

function Member_Change_Pass($Redirect = true, $Override_Member_ID = false) {

	global $Backend, $Database, $Error, $Member, $Sitewide, $Sitewide_Security_Hash_Current, $Sitewide_Security_Password_Length, $Success, $Time;

	// IF CSRF Okay
	if ( Runonce_CSRF_Check($_POST['csrf_protection']) ) {

		// Sanitize Pass
		$Member_Pass_New = Input_Prepare($_POST['pass']);
		if ( !$Override_Member_ID ) {
			$Override_Member_ID = $Member['ID'];
		}

		// IF Pass is Empty
		if ( empty($Member_Pass_New) ) {
			$Error = '<h3 class="color-pomegranate">Your new Pass cannot be empty.</h3>';
		// END IF Pass is Empty

		// IF Pass is too Short
		} else if ( strlen($Member_Pass_New) < $Sitewide_Security_Password_Length ) {
			$Error = 'Your password must be at least '.$Sitewide_Security_Password_Length.' characters in length.';
		// END IF Pass is too Short

		// IF Pass is good
		} else {

			// Generate a new Salt.
			$Member['Salt'] = Generator_String();
			// Hash the new Pass.
			$Member['Pass'] = Pass_Hash($Member_Pass_New, $Member['Salt']);
			// Now forget the Pass immediately.
			unset($Member_Pass_New);

			// Construct Query
			$Pass_Change = 'UPDATE `'.$Database['Prefix'].'Members`';
			$Pass_Change .= ' SET `PassV`=\''.$Sitewide_Security_Hash_Current.'\', `Pass`=\''.$Member['Pass'].'\', `Salt`=\''.$Member['Salt'].'\', `Modified`=\''.$Time['Now'].'\'';
			$Pass_Change .= ' WHERE `ID`=\''.$Override_Member_ID.'\' AND `Status`=\'Active\'';
			// Execute Query
			$Pass_Change = mysqli_query($Database['Connection'], $Pass_Change, MYSQLI_STORE_RESULT);

			// IF Pass not Changed
			if ( !$Pass_Change ) {
				if ( $Backend['Debug'] ) {
					echo 'Invalid Query (Pass_Change): ',mysqli_error($Database['Connection']);
				}
				$Error = '<h3 class="color-pomegranate">Pass could not be changed.</h3>';
			// END IF Pass not Changed

			// IF Pass Changed
			} else {
				// Redirect
				if ( $Redirect ) {
					header('Location: '.$Sitewide['Account'], true, 302);
				}
				$Success = true;
			} // IF Pass Changed

		} // END IF Pass is good
	// END IF CSRF Okay

	// IF CSRF Not Okay
	} else {
		$Error = '<h3 class="color-pomegranate margin-0">Your Pass could not be changed.</h3>';
		$Error .= '<p class="text-center">Your security token did not match. Please try again.</p><br>';
	} // END IF CSRF Not Okay

}