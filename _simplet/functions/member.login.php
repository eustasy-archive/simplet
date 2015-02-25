<?php

////	Member Login Function
//
// Logs in as a given member.

function Member_Login() {

	global $Cookie, $Database, $Login_Mail, $Redirect, $Request, $Sitewide, $Time, $User;

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

			$Member_Check = 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE `Mail`=\''.$Login_Mail.'\' AND `Status`=\'Active\'';
			$Member_Check = mysqli_query($Database['Connection'], $Member_Check, MYSQLI_STORE_RESULT);
			if ( !$Member_Check ) {
				if ( $Sitewide['Debug'] ) {
					echo 'Invalid Query (Member_Check): '.mysqli_error($Database['Connection']);
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
					unset($Member_Fetch);

					$Login_Hash = Pass_Hash($Login_Pass, $Member['Salt'], $Member['PassV']);
					unset($Login_Pass);

					if ( $Login_Hash === $Member['Pass'] ) {

						$Member['Cookie'] = Generator_String();

						setcookie($Cookie['Session'], $Member['Cookie'], time()+60*60*24*28, '/', $Request['Host'], $Request['Secure'], $Request['HTTPOnly']);

						$Session_New = 'INSERT INTO `'.$Database['Prefix'].'Sessions` (`Member_ID`, `Mail`, `Cookie`, `IP`, `Active`, `Created`, `Modified`) VALUES (\''.$Member['ID'].'\', \''.$Login_Mail.'\', \''.$Member['Cookie'].'\', \''.$User['IP'].'\', \'1\', \''.$Time['Now'].'\', \''.$Time['Now'].'\')';
						$Session_New = mysqli_query($Database['Connection'], $Session_New, MYSQLI_STORE_RESULT);
						if ( !$Session_New ) {
							if ( $Sitewide_Debug ) {
								echo 'Invalid Query (Session_New): '.mysqli_error($Database['Connection']);
							}
							$Error = 'Login Error: Could not create session.';

						// Login Successful
						} else {
							if ( isset($Redirect) ) {
								header('Location: '.$Sitewide_Root.urldecode($Redirect), true, 302);
							} else {
								header('Location: '.$Sitewide_Account, true, 302);
							}
						}

					} else {

						$Failures_New = 'INSERT INTO `'.$Database['Prefix'].'Failures` (`Member_ID`, `Mail`, `IP`, `Created`) VALUES (\''.$Member['ID'].'\', \''.$Login_Mail.'\', \''.$User_IP.'\', \''.$Time.'\')';
						$Failures_New = mysqli_query($Database['Connection'], $Failures_New, MYSQLI_STORE_RESULT);
						if ( !$Failures_New ) {
							if ( $Sitewide['Debug'] ) {
								echo 'Invalid Query (Failures_New): '.mysqli_error($Database['Connection']);
							}
							// Silently Fail
						} else {

							// TODO Use/Make Session Function
							setcookie ($Cookie['Session'], '', time() - 3600, '/', $Request['host'], $Request['Secure'], $Request['HTTPOnly']);
							setcookie ($Cookie['Session'], false, time() - 3600, '/', $Request['host'], $Request['Secure'], $Request['HTTPOnly']);
							unset($_COOKIE[$Cookie['Session']]);

							$Member_Auth = false;
							$Member['ID'] = false;

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