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
			// IF Authenticating.
			} else if (
				isset($_POST['mail']) ||
				isset($_POST['pass'])
			) {

				$Error = Member_Login();

				// Login Error
				if ( !empty($Error) ) {
					require $Templates['Header'];
					echo '<h2>Login Error</h2>';
					echo '<h3 class="textleft">'.$Error.' <a class="floatright" href="?login';
					if ( isset($Redirect) ) {
						echo '&redirect='.$Redirect;
					}
					echo '">Try Again</a></h3>';
					require $Templates['Footer'];
				}

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
					echo '<h3 class="textleft">'.$Error.' <a class="floatright" href="?register">Try Again</a></h3>';
				} else {
					echo '<h2>All Signed Up.</h2>';
					echo '<h3>You can now <a href="?login">Log In</a>.</h3>';
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
						// TODO Conditionally load based on LibLoad
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

				$Error = false;
				$Success = false;
				if (
					!empty($_POST['csrf_protection']) &&
					!empty($_POST['secret']) &&
					!empty($_POST['code'])
				) {

					if ( !Runonce_CSRF_Check($_POST['csrf_protection']) ) {
						$Error = 'CSRF Protection token failed checks. Please try again.';
					} else if ( !Authenticatron_Check(Input_Prepare($_POST['code']), Input_Prepare($_POST['secret']), $Sitewide_Security_Authenticatron_Variance) ) {
						$Error = 'Sorry, that\'s not the right code. Please try again.';
					} else {
						$Success = true;
					}
				}

				if ( $Error ) {
					echo '<h3 class="error color-pomegranate">'.$Error.'</h3>';
				}

				if ( !$Success ) {
					$Authenticatron_New = Authenticatron_New($Member['Name']);
					var_dump(Authenticatron_Acceptable($Authenticatron_New['Secret'], 3));
					?>

					<div class="group">
						<div class="col span_1_of_2">
							<p>1. Scan this code with <a href="https://m.google.com/authenticator" target="_blank">Google Authenticator</a></p>
							<p><img src="<?php echo $Authenticatron_New['QR']; ?>"></p>
							<p><?php echo $Authenticatron_New['URL']; ?></p>
						</div>
						<div class="col span_1_of_2">
							<p>2. Confirm with the 6-digit code your phone generates.</p>
							<form method="POST" action="">
								<div class="group">
									<div class="col span_5_of_11">
										<input type="tel" name="code">
									</div>
									<div class="col span_1_of_11"><br></div>
									<div class="col span_5_of_11">

										<?php echo Runonce_CSRF_Form(); ?>

										<input type="hidden" name="secret" value="<?php echo $Authenticatron_New['Secret']; ?>">
										<input type="submit" value="Confirm">
									</div>
								</div>
							</form>
						</div>
					</div>

				<?php
				} else {
					echo 'OK';
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
				require $Templates['Header'];
				?>
				<div class="section group">
					<div class="col span_1_of_8"><br></div>
					<div class="col span_6_of_8">
						<h2 class="textleft">Hello <?php echo $Member['Name']; ?>. <a class="floatright" href="?change=name">Change Name</a></h2>
						<h3 class="textleft"><?php echo $Member['Mail']; ?> <a class="floatright" href="?change=mail">Change Mail</a></h3>
						<h3 class="textleft">Your password is encrypted. <a class="floatright" href="?change=pass">Change Pass</a></h3>
						<br>
						<p>Your Member ID is <?php echo $Member['ID']; ?>. This cannot be changed. <a class="floatright red" href="?delete">Delete Account</a></p>
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