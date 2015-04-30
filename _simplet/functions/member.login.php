<?php

////	Member Login Function
//
// Logs in as a given member.

function Member_Login() {

	global $Backend, $Cookie, $Database, $Login_Mail, $Member, $Redirect, $Request, $Sitewide, $Time, $TFA, $User;

	$Error = false;

	if ( empty($_POST['pass']) ) {
		$Error = 'Your password cannot be blank.';
	} else if ( empty($_POST['mail']) ) {
		$Error = 'Your e-mail cannot be blank.';
	} else if ( !Runonce_CSRF_Check($_POST['csrf_protection']) ) {
		$Error = 'Your CSRF Protection failed.';
	} else {

		$Login_Mail = Input_Prepare(strtolower($_POST['mail']));
		$Login_Pass = Input_Prepare($_POST['pass']);

		// IFBLOCK
		// TODO: Make configurable.
		if ( Member_Login_Block_Check($Login_Mail) < 3 ) {

			// TODO Member_Check() Function
			$Member_Check = 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE `Mail`=\''.$Login_Mail.'\' AND `Status`=\'Active\'';
			$Member_Check = mysqli_query($Database['Connection'], $Member_Check, MYSQLI_STORE_RESULT);
			if ( !$Member_Check ) {
				if ( $Sitewide['Debug'] ) {
					echo 'Invalid Query (Member_Check): ',mysqli_error($Database['Connection']);
				}
				$Error = 'Login Error: Could not check member existence.';
			} else {

				$Member_Count = mysqli_num_rows($Member_Check);
				if ($Member_Count == 0) {
					// ERROR: Incorrect mail, or mail not registered.
					$Error = 'Wrong email or password.';
				} else {

					$Member_Fetch = mysqli_fetch_assoc($Member_Check);
					$Member['ID'] = $Member_Fetch['ID'];
					$Member['Name'] = $Member_Fetch['Name'];
					$Member['PassV'] = $Member_Fetch['PassV'];
					$Member['Pass'] = $Member_Fetch['Pass'];
					$Member['Salt'] = $Member_Fetch['Salt'];
					$TFA['Secret'] = $Member_Fetch['2fa'];
					unset($Member_Fetch);

					$Login_Hash = Pass_Hash($Login_Pass, $Member['Salt'], $Member['PassV']);
					unset($Login_Pass);

					if ( $Login_Hash === $Member['Pass'] ) {

						if ( $TFA['Secret'] ) {
							unset($TFA['Secret']);
							return '__NEEDS_TFA__';
						} else {
							$Session_New = Member_Session_New();
							if ( $Session_New != true ) {
								$Error = $Session_New;
							} else {
								// Login Successful
								return true;
							}
						}

					} else {

						$Failures_New = 'INSERT INTO `'.$Database['Prefix'].'Failures` (`Member_ID`, `Mail`, `IP`, `Created`) VALUES (\''.$Member['ID'].'\', \''.$Login_Mail.'\', \''.$User['IP'].'\', \''.$Time['Now'].'\')';
						$Failures_New = mysqli_query($Database['Connection'], $Failures_New, MYSQLI_STORE_RESULT);
						if ( !$Failures_New ) {
							if ( $Backend['Debug'] ) {
								echo 'Invalid Query (Failures_New): ',mysqli_error($Database['Connection']);
							}
							// Silently Fail

						} else {
							Member_Auth_False();
							$Error = 'Wrong email or password.';
						}

					}
				}
			}

		// IFBLOCK
		} else {
			$Error = 'Too many login attempts. Please wait 3 minutes.';
		}

	}

	return $Error;

}