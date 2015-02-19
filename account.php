<?php

	$Title_HTML = 'Account';
	$Title_Plain = 'Account';

	$Description_HTML = 'Account';
	$Description_Plain = 'Account';

	$Keywords = 'account password email';

	$Featured_Image = '';

	$Canonical = 'account';

	$Post_Type = 'Page';
	$Post_Category = '';

	require_once __DIR__.'/../simplet/request.php';

	$Header = '../header.php';
	$Footer = '../footer.php';

	$Lib_Browning_Config = __DIR__.'/../libs/config.browning.php';
	$Lib_Browning_Send = __DIR__.'/../libs/function.browning.php';

if ($Request['path'] === $Place['path'].$Canonical) {

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
			$Title_HTML = 'Log In';
			$Title_Plain = 'Log In';
			$Keywords = 'log in account';
			$Canonical = $Sitewide_Account.'?login';

			// Handle redirection nicely.
			if ( isset($_GET['redirect']) ) {
				$Redirect = $_GET['redirect'];
				// Stop a potential loop.
				if ( $Redirect == 'account?logout' ) $Redirect = 'account';
			}

			// IF Already authenticated.
			if ( $Member_Auth ) {
				if ( isset($Redirect) ) {
					header('Location: '.$Sitewide_Root.urldecode($Redirect), true, 302);
				} else {
					header('Location: '.$Sitewide_Account, true, 302);
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
					require $Header;
					echo '<h2>Login Error</h2>';
					echo '<h3 class="textleft">'.$Error.' <a class="floatright" href="?login';
					if ( isset($Redirect) ) {
						echo '&redirect='.$Redirect;
					}
					echo '">Try Again</a></h3>';
					require $Footer;
				}

			// END IF Authenticating.
			// IF Not logged in or logging in.
			} else {
				require $Header;
				Member_Login_Form();
				require $Footer;
			} // END IF Not logged in or logging in.

		// END IF Login
		// IF Logout
		} else if (isset($_GET['logout'])) {

			// Are you logged out already?
			if (!$Member_Auth) {
				$Error = 'We can\'t log you out, you aren\'t logged in.';

			} else if ( empty($_POST['csrf_protection']) ) {
				$Error = 'CSRF Protection token not set.';
			} else if ( !Runonce_CSRF_Check($_POST['csrf_protection']) ) {
				$Error = 'CSRF Protection token failed checks.';

			} else {
				$Session_End = 'UPDATE `'.$Database['Prefix'].'Sessions` SET `Active`=\'0\', `Modified`=\''.$Time.'\' WHERE `Member_ID`=\''.$Member_ID.'\' AND `Cookie`=\''.$User_Cookie.'\' AND `IP`=\''.$User_IP.'\'';
				$Session_End = mysqli_query($Database['Connection'], $Session_End, MYSQLI_STORE_RESULT);
				if ( !$Session_End ) {
					if ( $Sitewide_Debug ) echo 'Invalid Query (Session_End): '.mysqli_error($Database['Connection']);
					// TODO Handle Error
				} else {
					// TODO Function
					setcookie ($Cookie_Session, '', time() - 3600, '/', $Request['host'], $Request['Secure'], $Request['HTTPOnly']);
					setcookie ($Cookie_Session, false, time() - 3600, '/', $Request['host'], $Request['Secure'], $Request['HTTPOnly']);
					unset($_COOKIE[$Cookie_Session]);
					$Member_Auth = $Member_ID = $Member_Admin = false;
				}
			}

			$Title_HTML = 'Log Out';
			$Title_Plain = 'Log Out';

			$Keywords = 'log out account';

			$Canonical = $Sitewide_Account.'?logout';

			require $Header;

			if ( !empty($Error) ) {
				echo '<h2>Logout Error</h2>';
				echo '<h3>'.$Error.'</h3>';
			} else {
				echo '<h2>See you soon '.$Member_Name.'</h2>';
			}

			require $Footer;

		// Register
		} else if (isset($_GET['register'])) {

			$Title_HTML = 'Register';
			$Title_Plain = 'Register';

			$Keywords = 'register account';

			$Canonical = $Sitewide_Account.'?register';

			// Register Redirect
			if ( $Member_Auth ) {
				header('Location: '.$Sitewide_Account, true, 302);

			// Register Check
			} else if (!$Sitewide_Signups) {
				require $Header;
				echo '<h2>Sorry, new Registrations are not allowed at this time.</h2>';
				require $Footer;

			// Register Process
			} else if (
				isset($_POST['name']) ||
				isset($_POST['mail']) ||
				isset($_POST['pass'])
			) {

				$Error = Member_Register();

				// Register Error
				if ( !empty($Error) ) {
					require $Header;
					echo '<h2>Registration Error</h2>';
					echo '<h3 class="textleft">'.$Error.' <a class="floatright" href="?register">Try Again</a></h3>';
					require $Footer;
				} else {
					require $Header;
					echo '<h2>All Signed Up.</h2>';
					echo '<h3>You can now <a href="?login">Log In</a>.</h3>';
					require $Footer;
				}

			} else {
				require $Header;
				Member_Register_Form();
				require $Footer;
			}

		// Change
		} else if (!empty($_GET['change'])) {

			// Change Name
			if (Input_Prepare($_GET['change']) == 'name') {

				$Title_HTML = 'Change Name';
				$Title_Plain = 'Change Name';

				$Keywords = 'change name account';

				$Canonical = $Sitewide_Account.'?change=name';

				if (!$Member_Auth) {
					header('Location: ?login&redirect='.urlencode($Sitewide_Account.'?change=name'), true, 302);
					exit;

				} else {

					if ( isset($_POST['name']) ) {
						Member_Change_Name();
					}

					if ( !$Success ) {
						require $Header;
						Member_Change_Name_Form();
						require $Footer;
					}

				}

			// Change Pass
			} else if (Input_Prepare($_GET['change']) == 'pass') {

				$Title_HTML = 'Change Pass';
				$Title_Plain = 'Change Pass';

				$Keywords = 'change pass account';

				$Canonical = $Sitewide_Account.'?change=pass';

				if (!$Member_Auth) {
					header('Location: ?login&redirect='.urlencode($Sitewide_Account.'?change=pass'), true, 302);
					// exit;

				} else {

					if ( isset($_POST['pass']) ) {
						Member_Change_Pass();
					}

					if ( !$Success ) {
						require $Header;
						Member_Change_Pass_Form();
						require $Footer;
					}

				}

			// Change Mail
			} else if (Input_Prepare($_GET['change']) == 'mail') {

				$Title_HTML = 'Change Mail';
				$Title_Plain = 'Change Mail';

				$Keywords = 'change mail account';

				$Canonical = $Sitewide_Account.'?change=mail';

				if (!$Member_Auth) {
					header('Location: ?login&redirect='.urlencode($Sitewide_Account.'?change=mail'), true, 302);
					exit;

				} else {

					$Success = false;
					$Error = false;

					if ( isset($_POST['mail']) ) {
						Member_Change_Mail();
					}

					if ( !$Success ) {
						require $Header;
						Member_Change_Mail_Form();
						require $Footer;
					}

				}

			} else {
				$Error = 'Invalid Change Variable';
			}

		// Sessions
		} else if (isset($_GET['sessions'])) {

			// Session Redirect
			if (!$Member_Auth) {
				header('Location: ?login&redirect='.urlencode($Sitewide_Account.'?sessions'), true, 302);
				exit;

			} else {

				$Title_HTML = 'Sessions';
				$Title_Plain = 'Sessions';

				$Keywords = 'sessions account';

				$Canonical = $Sitewide_Account.'?sessions';

				require $Header;

				echo '<h2>Sessions</h2>';

				Member_Sessions();

				require $Footer;

			}

		// Reset Password
		} else if ( isset($_GET['reset']) ) {

			$Title_HTML = 'Password Reset';
			$Title_Plain = 'Password Reset';

			$Keywords = 'password reset account';

			$Canonical = $Sitewide_Account.'?reset';

			if ( $Member_Auth ) {
				header('Location: '.$Sitewide_Account, true, 302);
				// exit;

			// TODO Expand mail system support.
			} else if (
				isset($Sitewide_Browning) &&
				$Sitewide_Browning &&
				is_readable($Lib_Browning_Config)
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

						require $Header;
						if ( !$Success ) {
							Member_Reset_Pass_Form();
						} else {
							Runonce_Delete($Key, $Key_Info['Member_ID']);
							echo '<h2>Password Reset Successfully</h2>';
							echo '<h3>You should probably go <a href="?login">login</a>.</h3>';
						}
						require $Footer;

					} else {
						require $Header;
						echo '
						<h2>Error: Invalid Key</h2>
						<h3><a href="?reset">Try again</a></h3>';
						require $Footer;
					}

				} else {

					// Reset Mail Process
					if (isset($_POST['mail'])) {
						require_once $Lib_Browning_Config;
						require_once $Lib_Browning_Send;
						Member_Reset_Mail();
					}

					require $Header;

					// Reset Form
					// Also handles successes and errors.
					Member_Reset_Mail_Form();

					require $Footer;

				}

			} else {
				require $Header;
				?>
				<h2>Sorry, this installation of Simplet does not support reseting passwords.</h2>
				<h4>If you are the owner of this site, you need to set the Mailgun API URL and Key for your site.</h4>
				<?php
				require $Footer;
			}

		} else if (isset($_GET['delete'])) {

			if (!$Member_Auth) {
				header('Location: ?login&redirect='.urlencode($Sitewide_Account.'?delete'), true, 302);
				exit;

			} else {

				if ( isset($_POST['delete_key']) ) {
					Member_Delete();
				}

				require $Header;
				if ( !$Success) {
					$Key = Runonce_Create($Time+(60*3), 1, 'Account Deletion');
				}
				Member_Delete_Form();
				require $Footer;

			}

		} else {

			if (!$Member_Auth) {
				header('Location: ?login&redirect='.urlencode($Sitewide_Account), true, 302);
				// exit;

			} else {
				require $Header;
				?>

				<div class="section group">
					<div class="col span_1_of_8"><br></div>
					<div class="col span_6_of_8">
						<h2 class="textleft">Hello <?php echo $Member_Name; ?>. <a class="floatright" href="?change=name">Change Name</a></h2>
						<h3 class="textleft"><?php echo $Member_Mail; ?> <a class="floatright" href="?change=mail">Change Mail</a></h3>
						<h3 class="textleft">Your password is encrypted. <a class="floatright" href="?change=pass">Change Pass</a></h3>
						<br>
						<p>Your Member ID is <?php echo $Member_ID; ?>. This cannot be changed. <a class="floatright red" href="?delete">Delete Account</a></p>
					</div>
					<div class="col span_1_of_8"><br></div>
				</div>

				<?php
				require $Footer;
			}
		}

	// IFDATABASEMEMBERS
	// Members are not enabled.
	} else {
		// TODO ERROR
	} // IFDATABASEMEMBERS

}