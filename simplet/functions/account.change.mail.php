<?php

////	Account Change Mail Function
//
// Changes an accounts mail.

function Account_Change_Mail() {

	global $Database, $Error, $Member_ID, $Sitewide_Account, $Sitewide_Debug, $Success, $Time;

	// IF CSRF Okay
	if ( Runonce_CSRF_Check($_POST['csrf_protection']) ) {
		// Sanitize Mail
		$Member_Mail_New = Input_Prepare(strtolower($_POST['mail']));
		// IF Mail is Empty
		if ( empty($Member_Mail_New) ) {
			$Error = '<h3 class="color-pomegranate">Your new Mail cannot be empty.</h3>';
		// END IF Mail is Empty
		// IF Mail is not Empty
		} else {
			// Construct Query
			$Mail_Change = 'UPDATE `'.$Database['Prefix'].'Members`';
			$Mail_Change .= ' SET `Mail`=\''.$Member_Mail_New.'\', `Modified`=\''.$Time.'\'';
			$Mail_Change .= ' WHERE `ID`=\''.$Member_ID.'\' AND `Status`=\'Active\'';
			// Execute Query
			$Mail_Change = mysqli_query($Database['Connection'], $Mail_Change, MYSQLI_STORE_RESULT);
			// IF Mail not Changed
			if ( !$Mail_Change ) {
				if ( $Sitewide_Debug ) echo 'Invalid Query (Mail_Change): '.mysqli_error($Database['Connection']);
				$Error = '<h3 class="color-pomegranate">Mail could not be changed.</h3>';
			// END IF Mail not Changed
			// IF Mail Changed
			} else {
				// Redirect
				header('Location: '.$Sitewide_Account, TRUE, 302);
				$Success = true;
				// exit;
			} // IF Mail Changed

		} // END IF Mail is not Empty

	// END IF CSRF Okay
	// IF CSRF Not Okay
	} else {
		$Error = '<h3 class="color-pomegranate margin-0">Your Mail could not be changed.</h3>';
		$Error .= '<p class="text-center">Your security token did not match. Please try again.</p><br>';
	} // END IF CSRF Not Okay

}