<?php

////	Account Reset Mail Function
//
// Sends a reset password mail.

function Member_Reset_Mail() {

	global $Backend, $Database, $Error, $Sitewide, $Success, $Time;

	// IF CSRF Okay
	if ( Runonce_CSRF_Check($_POST['csrf_protection']) ) {

		// Sanitize Mail
		$Reset_Mail = Input_Prepare(strtolower($_POST['mail']));

		// IF Mail is Empty
		if ( empty($Reset_Mail) ) {
			$Error = '<h3 class="color-pomegranate">Error: Your e-mail address cannot be empty.</h3>';
		// END IF Mail is Empty

		// IF Mail is not Empty
		} else {
			// TODO Member_Check Function
			$Member_Check = 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE `Mail`=\''.$Reset_Mail.'\' AND `Status`=\'Active\'';
			$Member_Check = mysqli_query($Database['Connection'], $Member_Check, MYSQLI_STORE_RESULT);
			if ( !$Member_Check ) {
				if ( $Backend['Debug'] ) {
					echo 'Invalid Query (Member_Check): ',mysqli_error($Database['Connection']);
				}
			} else {
				$Member_Count = mysqli_num_rows($Member_Check);
				if ( $Member_Count == 0 ) {
					if ( $Backend['Debug'] ) {
						echo 'SIMPLET WARNING: No member with that email address.';
					}
					$Error = '<h3 class="color-pomegranate">Error: User does not exist.</h3>';
				} else {
					$Fetch_Member = mysqli_fetch_assoc($Member_Check);
					$Member_ID = $Fetch_Member['ID'];
					$Member_Name = $Fetch_Member['Name'];

					// Create a single-use key with a 1 hour timeout for resetting the password.
					$Key = Runonce_Create($Time['1hour'], 1, 'Password Reset', Generator_String(), $Member_ID);

					$Mail_Response = Browning(
						$Reset_Mail,
						'Password Reset',
						'Hello '.$Member_Name.', you wanted to reset your password? '.$Sitewide['Root'].$Sitewide['Account'].'?reset&key='.$Key['Key']
					);

					if ( $Mail_Response['Success'] ) {
						$Success = '
						<h2>A Password Reset has been initiated.</h2>
						<h3>Please check your email.</h3>';
					} else {
						$Error = '
						<h3 class="color-pomegranate">Error: Unable to send reset mail.</h3>
						<p>'.$Mail_Response['Error'].'</p>';
					}

				}

			}
		}

	// END IF CSRF Okay
	// IF CSRF Not Okay
	} else {
		$Error = '<h3 class="color-pomegranate margin-0">Your password could not be reset.</h3>';
		$Error .= '<p class="text-center">Your security token did not match. Please try again.</p><br>';
	} // END IF CSRF Not Okay

}