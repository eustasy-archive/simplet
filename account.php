<?php

$Page['Title']['Plain'] = 'Account';
$Page['Description']['Plain'] = 'Account';
$Page['Keywords'] = 'account password email';
$Page['Type'] = 'Page';
require_once __DIR__.'/_simplet/request.php';
$Canonical = $Sitewide['Account'];

if ( $Request['Path'] === $Canonical ) {

	// IFDATABASEMEMBERS
	if ( // If it is possible for them to be logged in.
		$Database['Connection'] &&
		$Database['Exists']['Members'] &&
		$Database['Exists']['Failures'] &&
		$Database['Exists']['Sessions']
	) {

		$Success = false;
		$Error = false;

		// IF Login
		if ( isset($_GET['login']) ) {

			// Meta Information
			$Page['Title']['Plain'] = 'Log In';
			$Page['Description']['Plain'] = 'Log In';
			$Page['Keywords'] = 'log in account';
			$Canonical = $Sitewide['Account'].'?login';

			// Handle redirection nicely.
			if ( isset($_GET['redirect']) ) {
				$Redirect = $_GET['redirect'];
				// Stop a potential loop.
				if ( $Redirect == '/account?logout' ) {
					$Redirect = $Sitewide['Account'];
				}
			}

			// IF Already authenticated.
			if ( $Member['Auth'] ) {
				if ( isset($Redirect) ) {
					header('Location: '.$Sitewide['Root'].urldecode($Redirect), true, 302);
				} else {
					header('Location: '.$Sitewide['Account'], true, 302);
				}
			// END IF Already authenticated.

			// IF TFA Authenticating.
			} else if ( isset($_POST['code']) ) {
				// Get key from post.
				foreach($_POST as $Key => $Value) {
					if ( strpos($Key, 'key-') === 0 ) {
						$TFA['Key'] = substr($Key, 4);
					}
				}
				// Fetch RunOnce with MemberID
				$Runonce = Runonce_Check($TFA['Key'], '*');
				Runonce_Used($TFA['Key'], '*');
				// Get Members Secret
				$Member_TFA_Current = Member_TFA_Current($Runonce['Member_ID']);
				if ( $Member_TFA_Current['Success'] ) {
					$TFA['Secret'] = $Member_TFA_Current['Result'];
				} else {
					// TODO Error Better
					echo 'Could not find the secret for the given member.';
				}

				// IF TFA Auth
				$Member_TFA_Auth = Member_TFA_Auth();
				if ( $Member_TFA_Auth['Auth'] ) {

					$Member['ID'] = $Runonce['Member_ID'];

					// TODO $Login_Mail Not defined
					// Get mail from id

					$Session_New = Member_Session_New();

					if ( $Session_New != true ) {
						$Error = $Session_New;
					} else {
						Runonce_Delete($TFA['Key'], '*');
						// Login Successful
						return true;
					}
				// END IF TFA Auth

				// IF NOT TFA Auth
				} else {
					// TODO Error Better
					echo 'Error:';
					foreach ( $Member_TFA_Auth['Error'] as $Error ) {
						echo $Error;
					}
				} // END IF NOT TFA Auth

			// END IF TFA Authenticating.

			// IF Authenticating.
			} else if (
				isset($_POST['mail']) ||
				isset($_POST['pass'])
			) {

				$Login_Result = Member_Login();

				// IF Needs TFA
				if ( $Login_Result == '__NEEDS_TFA__' ) {
					require $Templates['Header'];
					echo '<h2>Two-Factor Authentication</h2>';
					echo '<p class="text-center">Please enter your two-factor authentication code.</p>';
					$TFA['Key'] = Generator_String();
					Runonce_Create('15mins', 3, 'Two-Factor Authentication', $TFA['Key'], $Member['ID']);
					Member_TFA_Auth_Form('key-'.$TFA['Key'], false);
					require $Templates['Footer'];
				// END IF Needs TFA

				// IF Login Error
				} else if ( $Login_Result != true ) {
					require $Templates['Header'];
					echo '<h2>Login Error</h2>';
					echo '<h3 class="text-left">'.$Login_Result.' <a class="floatright" href="?login';
					if ( isset($Redirect) ) {
						echo '&redirect='.$Redirect;
					}
					echo '">Try Again</a></h3>';
					require $Templates['Footer'];
				} // END IF Login Error

			// END IF Authenticating.

			// IF Not logged in or logging in.
			} else {
				require $Templates['Header'];
				Member_Login_Form();
				require $Templates['Footer'];
			} // END IF Not logged in or logging in.

		// END IF Login

		// IF Logout
		} else if (isset($_GET['logout'])) {

			if ( !$Member['Auth'] ) {
				$Error = 'We can\'t log you out, you aren\'t logged in.';

			} else if ( empty($_POST['csrf_protection']) ) {
				$Error = 'CSRF Protection token not set.';
			} else if ( !Runonce_CSRF_Check($_POST['csrf_protection']) ) {
				$Error = 'CSRF Protection token failed checks.';

			} else {
				// TODO Function
				$Session_End = 'UPDATE `'.$Database['Prefix'].'Sessions` SET `Active`=\'0\', `Modified`=\''.$Time['Now'].'\' WHERE `Member_ID`=\''.$Member['ID'].'\' AND `Cookie`=\''.$Member['Cookie'].'\' AND `IP`=\''.$User['IP'].'\'';
				$Session_End = mysqli_query($Database['Connection'], $Session_End, MYSQLI_STORE_RESULT);
				if ( !$Session_End ) {
					if ( $Sitewide['Debug'] ) {
						echo 'Invalid Query (Session_End): '.mysqli_error($Database['Connection']);
					}
					// TODO Handle Error
				} else {
					$Bye = $Member['Name'];
					Member_Auth_False(true);
				}
			}

			$Page['Title']['Plain'] = 'Log Out';
			$Page['Description']['Plain'] = 'Log Out';
			$Page['Keywords'] = 'log out account';
			$Canonical = $Sitewide['Account'].'?logout';

			require $Templates['Header'];
			if ( !empty($Error) ) {
				echo '<h2>Logout Error</h2>';
				echo '<h3>'.$Error.'</h3>';
			} else {
				echo '<h2>See you soon, '.$Bye.'.</h2>';
			}
			require $Templates['Footer'];

		// Register
		} else if (isset($_GET['register'])) {

			$Page['Title']['Plain'] = 'Register';
			$Page['Description']['Plain'] = 'Register';
			$Page['Keywords'] = 'register account';
			$Canonical = $Sitewide['Account'].'?register';

			// Register Redirect
			if ( $Member['Auth'] ) {
				header('Location: '.$Sitewide['Account'], true, 302);
				exit;

			// Register Check
			} else if ( !$Sitewide_Signups ) {
				require $Templates['Header'];
				echo '<h2>Sorry, new Registrations are not allowed at this time.</h2>';
				require $Templates['Footer'];

			// Register Process
			} else if (
				isset($_POST['name']) ||
				isset($_POST['mail']) ||
				isset($_POST['pass'])
			) {

				$Error = Member_Register();

				// Register Error
				require $Templates['Header'];
				if ( !empty($Error) ) {
					echo '<h2>Registration Error</h2>';
					echo '<h3 class="text-left">'.$Error.' <a class="floatright" href="?register">Try Again</a></h3>';
				} else {
					echo '<h2>All Signed Up.</h2>';
					echo '<h3>You can now <a href="?login">Log In</a>.</h3>';
					// TODO Prompt for two-factor
				}
				require $Templates['Footer'];

			} else {
				require $Templates['Header'];
				Member_Register_Form();
				require $Templates['Footer'];
			}

		// Change
		} else if (!empty($_GET['change'])) {

			// Change Name
			if (Input_Prepare($_GET['change']) == 'name') {

				$Page['Title']['Plain'] = 'Change Name';
				$Page['Description']['Plain'] = 'Change Name';
				$Page['Keywords'] = 'change name account';
				$Canonical = $Sitewide['Account'].'?change=name';

				if ( !$Member['Auth'] ) {
					header('Location: ?login&redirect='.urlencode($Sitewide['Account'].'?change=name'), true, 302);
					exit;

				} else {

					if ( isset($_POST['name']) ) {
						Member_Change_Name();
					}

					if ( !$Success ) {
						require $Templates['Header'];
						Member_Change_Name_Form();
						require $Templates['Footer'];
					}

				}

			// Change Pass
			} else if ( Input_Prepare($_GET['change']) == 'pass' ) {

				$Page['Title']['Plain'] = 'Change Pass';
				$Page['Description']['Plain'] = 'Change Pass';
				$Page['Keywords'] = 'change pass account';
				$Canonical = $Sitewide['Account'].'?change=pass';

				if ( !$Member['Auth'] ) {
					header('Location: ?login&redirect='.urlencode($Sitewide['Account'].'?change=pass'), true, 302);
					exit;

				} else {

					if ( isset($_POST['pass']) ) {
						Member_Change_Pass();
					}

					if ( !$Success ) {
						require $Templates['Header'];
						Member_Change_Pass_Form();
						require $Templates['Footer'];
					}

				}

			// Change Mail
			} else if ( Input_Prepare($_GET['change']) == 'mail' ) {

				$Page['Title']['Plain'] = 'Change Mail';
				$Page['Description']['Plain'] = 'Change Mail';
				$Page['Keywords'] = 'change mail account';
				$Canonical = $Sitewide['Account'].'?change=mail';

				if ( !$Member['Auth'] ) {
					header('Location: ?login&redirect='.urlencode($Sitewide['Account'].'?change=mail'), true, 302);
					exit;

				} else {

					$Success = false;
					$Error = false;

					if ( isset($_POST['mail']) ) {
						Member_Change_Mail();
					}

					if ( !$Success ) {
						require $Templates['Header'];
						Member_Change_Mail_Form();
						require $Templates['Footer'];
					}

				}

			} else {
				$Error = 'Invalid Change Variable';
			}

		// Sessions
		} else if (isset($_GET['sessions'])) {

			// Session Redirect
			if (!$Member['Auth']) {
				header('Location: ?login&redirect='.urlencode($Sitewide['Account'].'?sessions'), true, 302);
				exit;

			} else {

				$Page['Title']['Plain'] = 'Sessions';
				$Page['Description']['Plain'] = 'Sessions';
				$Page['Keywords'] = 'sessions account';
				$Canonical = $Sitewide['Account'].'?sessions';

				require $Templates['Header'];
				echo '<h2>Sessions</h2>';
				Member_Sessions();
				require $Templates['Footer'];

			}

		// Reset Password
		} else if ( isset($_GET['reset']) ) {

			$Page['Title']['Plain'] = 'Password Reset';
			$Page['Description']['Plain'] = 'Password Reset';
			$Page['Keywords'] = 'password reset account';
			$Canonical = $Sitewide['Account'].'?reset';

			if ( $Member['Auth'] ) {
				header('Location: '.$Sitewide['Account'], true, 302);
				exit;

			// TODO Expand mail system support.
			} else if (
				isset($Libs['Browning']) &&
				$Libs['Browning'] &&
				$Browning['Key']
			) {

				$Success = false;
				$Error = false;

				// Reset Process
				if ( isset($_GET['key']) ) {

					$Key = Input_Prepare($_GET['key']);

					$Key_Info = Runonce_Check($Key, '*', 'Password Reset');

					if ( $Key_Info ) {

						$Override_Member_ID = $Key_Info['Member_ID'];

						// TODO Member_Exists

						if ( isset($_POST['pass']) ) {
							Member_Change_Pass(false, $Override_Member_ID);
						}

						require $Templates['Header'];
						if ( !$Success ) {
							Member_Reset_Pass_Form();
						} else {
							Runonce_Delete($Key, $Key_Info['Member_ID']);
							echo '<h2>Password Reset Successfully</h2>';
							echo '<h3>You should probably go <a href="?login">login</a>.</h3>';
						}
						require $Templates['Footer'];

					} else {
						require $Templates['Header'];
						echo '
						<h2>Error: Invalid Key</h2>
						<h3><a href="?reset">Try again</a></h3>';
						require $Templates['Footer'];
					}

				} else {

					// Reset Mail Process
					if (isset($_POST['mail'])) {
						if ( !$Sitewide['AutoLoad']['Libs'] ) {
							require_once $Backend['libs'].'config.browning.php';
							require_once $Backend['libs'].'function.browning.php';
						}
						Member_Reset_Mail();
					}

					// Reset Form
					// Also handles successes and errors.
					require $Templates['Header'];
					Member_Reset_Mail_Form();
					require $Templates['Footer'];

				}

			} else {
				require $Templates['Header'];
				?>
				<h2>Sorry, this installation of Simplet does not support reseting passwords.</h2>
				<h4>If you are the owner of this site, you need to set the Mailgun API URL and Key for your site.</h4>
				<?php
				require $Templates['Footer'];
			}

		} else if ( isset($_GET['twofactorauth']) ) {

			if ( !$Member['Auth'] ) {
				header('Location: ?login&redirect='.urlencode($Sitewide['Account'].'?twofactorauth'), true, 302);
				exit;

			} else {

				require $Templates['Header'];
				echo '<h2>Two-Factor Authentication</h2>';

				$Error = [];
				$Success = false;

				// IF TFA ENABLE
				if (
					isset($_POST['enable'])
				) {
					Member_TFA_Enable();
				} // END IF TFA ENABLE

				// Fetch Current TFA Secret
				$TFA_Current = Member_TFA_Current();
				if ( !$TFA_Current['Error'] ) {
					$TFA['Secret'] = $TFA_Current['Result'];
				} else {
					$TFA['Secret'] = false;
				}

				// IF TFA AUTH
				if (
					$TFA['Secret'] &&
					isset($_POST['auth'])
				) {
					$Member_TFA_Auth = Member_TFA_Auth();
					if ( !empty($Member_TFA_Auth['Error']) ) {
						$Error = array_merge($Error, $Member_TFA_Auth['Error']);
					} else {
						$TFA['Auth'] = $Member_TFA_Auth['Auth'];
					}
				} // END IF TFA AUTH

				// IF TFA ERROR
				if ( !empty($Error) ) {
					echo '
						<div class="error color-pomegranate">
							<h4 class="text-left error-title color-pomegranate">Error(s):</h4>';
					foreach ( $Error as $Issue ) {
						echo '<p>'.$Issue.'</p>';
					}
					echo '<hr>
						</div>';
				} // END IF TFA ERROR

				// Add a Device
				if (
					$TFA['Secret'] &&
					isset($_POST['add_device'])
				) {
					// Scan on new Device
					if (
						isset($TFA['Auth']) &&
						$TFA['Auth']
					) {
						$Authenticatron_QR = Authenticatron_QR(Authenticatron_URL($Member['Name'], $TFA['Secret']));
						echo '
							<h3>Scan this QR code with your new Device</h3>
							<p class="text-center"><img src="'.$Authenticatron_QR.'"></p>';
						// TODO Continue / Back / Account buttons

					// Enter Code
					} else {
						echo '
							<h3>Enter your current code to add a Device</h3>';
						Member_TFA_Auth_Form('add_device');
					}

				// Disable TFA
				} else if (
					$TFA['Secret'] &&
					isset($_POST['disable'])
				) {
					// Disabled
					if (
						isset($TFA['Auth']) &&
						$TFA['Auth']
					) {
						$TFA['Disable'] = Member_TFA_Disable();
						if ( $TFA['Disable'] ) {
							echo '
								<h3>Two-Factor Authentication has been disabled.</h3>';
						} else {
							echo '
								<h3>Sorry, Two-Factor Authentication could not be disabled.</h3>';
						}
						// TODO Continue / Back / Account buttons

					// Enter Code
					} else {
						echo '
							<h3>Enter your current code to disable two-factor authentication</h3>';
						Member_TFA_Auth_Form('disable');
					}

				// Already Enabled
				} else if ( $TFA['Secret'] ) {
					echo '
					<img class="float-left" src="'.$Sitewide['Root'].'/assets/images/locked_128.png">';
					if ( $Success ) {
						echo '
							<h3 class="text-left color-nephritis">Your account is now secured with two-factor authentication.</h3>';
					} else {
						echo '
							<h3 class="text-left">Your account is currently secured with two-factor authentication.</h3>';
					}
					?>

					<div class="clear-both group">
						<div class="col span_1_of_4">
							<p class="like-input"><a href="<?php echo $Sitewide['Root'].$Sitewide['Account']; ?>">Back to Account</a></p>
						</div>
						<div class="col span_1_of_4"><br></div>
						<form method="POST" action="" class="col span_1_of_4">
							<input type="hidden" name="disable" required>
							<input type="submit" class="button-link color-pomegranate" value="Disable">
						</form>
						<form method="POST" action="" class="col span_1_of_4">
							<input type="hidden" name="add_device" required>
							<input type="submit" value="Add Device">
						</form>
					</div>

					<?php

				// First Time
				// Or error, who knows, we dealt with those.
				} else {
					if (
						!empty($TFA['Secret']) &&
						$TFA['Secret Length'] == 16
					) {
						$Authenticatron_QR = Authenticatron_QR(Authenticatron_URL($Member['Name'], $TFA['Secret']));
					} else {
						$Authenticatron_New = Authenticatron_New($Member['Name']);
						$Authenticatron_QR = $Authenticatron_New['QR'];
						$TFA['Secret'] = $Authenticatron_New['Secret'];
					}
					?>

					<div class="group">
						<div class="col span_1_of_2">
							<p>1. Scan this code with <a href="https://m.google.com/authenticator" target="_blank">Google Authenticator</a></p>
							<p><img src="<?php echo $Authenticatron_QR; ?>"></p>
						</div>
						<div class="col span_1_of_2">
							<p>2. Confirm with the 6-digit code your phone generates.</p>
							<form method="POST" action="">
								<div class="group">
									<div class="col span_5_of_11">
										<input type="tel" name="code" autocomplete="off" autofocus required>
									</div>
									<div class="col span_1_of_11"><br></div>
									<div class="col span_5_of_11">
										<input type="hidden" name="enable" required>

										<?php echo Runonce_CSRF_Form(); ?>

										<input type="hidden" name="secret" value="<?php echo $TFA['Secret']; ?>" required>
										<input type="submit" value="Confirm">
									</div>
								</div>
							</form>
						</div>
					</div>

					<?php
				}
				require $Templates['Footer'];

			}

		} else if ( isset($_GET['delete']) ) {

			if ( !$Member['Auth'] ) {
				header('Location: ?login&redirect='.urlencode($Sitewide['Account'].'?delete'), true, 302);
				exit;

			} else {

				if ( isset($_POST['delete_key']) ) {
					Member_Delete();
				}

				require $Templates['Header'];
				if ( !$Success ) {
					$Key = Runonce_Create($Time['Now']+(60*3), 1, 'Account Deletion');
				}
				Member_Delete_Form();
				require $Templates['Footer'];

			}

		} else {

			if ( !$Member['Auth'] ) {
				header('Location: ?login&redirect='.urlencode($Sitewide['Account']), true, 302);
				exit;

			} else {
				$TFA_Current = Member_TFA_Current();
				if ( !$TFA_Current['Error'] ) {
					$TFA['Secret'] = $TFA_Current['Result'];
				} else {
					$TFA['Secret'] = false;
				}
				require $Templates['Header'];
				?>
				<div class="section group">
					<div class="col span_1_of_8"><br></div>
					<div class="col span_6_of_8">
						<h2 class="text-left margin-0">Hello <?php echo $Member['Name']; ?>. <a class="floatright" href="?change=name">Change Name</a></h2>
						<h3 class="text-left margin-0"><?php echo $Member['Mail']; ?> <a class="floatright" href="?change=mail">Change Mail</a></h3>
						<h3 class="text-left margin-0">Your password is encrypted. <a class="floatright" href="?change=pass">Change Pass</a></h3>

						<?php
							if ( $TFA['Secret'] ) {
								echo '<p class="text-left">Your account is secured with two-factor authentication. <a class="floatright" href="?twofactorauth">Add Devices or Disable</a></p>';
							} else {
								echo '<p class="text-left color-pomegranate">Your account is not secured with two-factor authentication. <a class="floatright" href="?twofactorauth">Enable Now</a></p>';
							}
						?>

						<p>Your Member ID is <?php echo $Member['ID']; ?>. This cannot be changed. <a class="floatright color-pomegranate" href="?delete">Delete Account</a></p>
					</div>
					<div class="col span_1_of_8"><br></div>
				</div>
				<?php
				require $Templates['Footer'];
			}
		}

	// IFDATABASEMEMBERS
	// Members are not enabled.
	} else {
		// TODO ERROR
	} // IFDATABASEMEMBERS

}