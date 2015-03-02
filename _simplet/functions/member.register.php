<?php


function Member_Register() {

	global $Backend, $Database, $Sitewide_Security_Hash_Current, $Sitewide_Security_Password_Length, $Time;

	$Error = false;

	if ( !Runonce_CSRF_Check($_POST['csrf_protection']) ) {
		$Error = 'Your CSRF Protection token did not match.';

	} else if (empty($_POST['name'])) {
		$Error = 'We really need an name.';

	} else if (empty($_POST['mail'])) {
		$Error = 'We really need an email.';

	} else if (empty($_POST['pass'])) {
		$Error = 'You really need a password.';

	} else {

		$Signup_Name = Input_Prepare($_POST['name']);
		// TODO Unit Test
		$Signup_Mail = Input_Prepare(strtolower($_POST['mail']));
		$Signup_Pass = Input_Prepare($_POST['pass']);

		if ( strlen($Signup_Pass) < $Sitewide_Security_Password_Length ) {
			$Error = 'Your password must be at least '.$Sitewide_Security_Password_Length.' characters in lenght.';

		} else {

			// TODO Member_Check Function
			$Member_Check = 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE `Mail`=\''.$Signup_Mail.'\' LIMIT 0, 1';
			$Member_Check = mysqli_query($Database['Connection'], $Member_Check, MYSQLI_STORE_RESULT);
			if ( !$Member_Check ) {
				if ( $Backend['Debug'] ) {
					echo 'Invalid Query (Member_Check): '.mysqli_error($Database['Connection']);
				}
				// TODO Handle Error
			} else {

				$Member_Count = mysqli_num_rows($Member_Check);
				// Not a member. Register.
				if ($Member_Count == 0) {

					$Member_ID  = Generator_String(12);

					$Salt = Generator_String();

					$Pass_Hash = Pass_Hash($Signup_Pass, $Salt);

					$Member_New = 'INSERT INTO `'.$Database['Prefix'].'Members` (`ID`, `Mail`, `Name`, `PassV`, `Pass`, `Salt`, `Status`, `Created`, `Modified`) VALUES (\''.$Member_ID.'\', \''.$Signup_Mail.'\', \''.$Signup_Name.'\', \''.$Sitewide_Security_Hash_Current.'\', \''.$Pass_Hash.'\', \''.$Salt.'\', \'Active\', \''.$Time['Now'].'\', \''.$Time['Now'].'\')';
					$Member_New = mysqli_query($Database['Connection'], $Member_New, MYSQLI_STORE_RESULT);
					if ( !$Member_New ) {
						if ( $Backend['Debug'] ) {
							echo 'Invalid Query (Member_New): '.mysqli_error($Database['Connection']);
						}
						// TODO Handle Error
					}

				// Already a member. Sorry..?
				} else {
					$Error = 'Sorry, you seem to already be registered. <a href="?login">Log In</a>?';
				}
			}
		}
	}

	return $Error;
}