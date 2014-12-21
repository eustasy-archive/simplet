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
	$Lib_Browning_Config = __DIR__.'/../libs/Browning_Config.php';
	$Lib_Browning_Send = __DIR__.'/../libs/Browning_Send.php';

if ($Request['path'] === $Place['path'].$Canonical) {

	// IFDATABASEMEMBERS
	if ( // If it is possible for them to be logged in.
		$Database['Connection'] &&
		$Database['Exists']['Members'] &&
		$Database['Exists']['Failures'] &&
		$Database['Exists']['Sessions']
	) {

		// LOGIN
		if (isset($_GET['login'])) {

			// Handle redirection nicely.
			if ( isset($_GET['redirect']) ) {
				$Redirect = $_GET['redirect'];
				if ( $Redirect == 'account?logout' ) $Redirect = 'account';
			}

			// IFAUTH
			if ( $Member_Auth ) {
				if (isset($Redirect)) header('Location: '.$Sitewide_Root.urldecode($Redirect), TRUE, 302);
				else header('Location: '.$Sitewide_Account, TRUE, 302);
				die();

			// IFAUTH Post
			} else if (isset($_POST['mail']) || isset($_POST['pass'])) {

				if (empty($_POST['pass'])) $Error = 'No Pass received.';
				else if (empty($_POST['mail'])) $Error = 'No Mail received.';
				else {

					$Login_Mail = Input_Prepare(strtolower($_POST['mail']));
					$Login_Pass = Input_Prepare($_POST['pass']);

					// IFBLOCK
					// TODO: Make configurable.
					if ( Member_Block_Check($Login_Mail) < 3 ) {

						$Member_Check = 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE `Mail`=\''.$Login_Mail.'\' AND `Status`=\'Active\'';
						$Member_Check = mysqli_query($Database['Connection'], $Member_Check, MYSQLI_STORE_RESULT);
						if ( !$Member_Check ) {
							if ( $Sitewide_Debug ) echo 'Invalid Query (Member_Check): '.mysqli_error($Database['Connection']);
							// TODO Handle Error
						} else {

							$Member_Count = mysqli_num_rows($Member_Check);
							if ($Member_Count == 0) {
								// ERROR: Incorrect mail, or mail not registered.
								$Error = 'Wrong email or password.';
							} else {

								$Member_Fetch = mysqli_fetch_assoc($Member_Check);
								$Member_ID = $Member_Fetch['ID'];
								$Member_Name = $Member_Fetch['Name'];
								$Member_Pass = $Member_Fetch['Pass'];
								$Member_Salt = $Member_Fetch['Salt'];

								$Login_Hash = Pass_Hash($Login_Pass, $Member_Salt);

								if ($Login_Hash === $Member_Pass) {

									$Member_Cookie = Generator_String();

									setcookie($Cookie_Session, $Member_Cookie, time()+60*60*24*28, '/', $Request['host'], $Request['Secure'], $Request['HTTPOnly']);

									$Session_New = 'INSERT INTO `'.$Database['Prefix'].'Sessions` (`Member_ID`, `Mail`, `Cookie`, `IP`, `Active`, `Created`, `Modified`) VALUES (\''.$Member_ID.'\', \''.$Login_Mail.'\', \''.$Member_Cookie.'\', \''.$User_IP.'\', \'1\', \''.$Time.'\', \''.$Time.'\')';
									$Session_New = mysqli_query($Database['Connection'], $Session_New, MYSQLI_STORE_RESULT);
									if ( !$Session_New ) {
										if ( $Sitewide_Debug ) echo 'Invalid Query (Session_New): '.mysqli_error($Database['Connection']);
										// TODO Handle Error
									} else {

										// Login Successful
										if (isset($Redirect)) header('Location: '.$Sitewide_Root.urldecode($Redirect), TRUE, 302);
										else header('Location: '.$Sitewide_Account, TRUE, 302);
										die(); // As in go away.

									}

								} else {

									$Failures_New = 'INSERT INTO `'.$Database['Prefix'].'Failures` (`Member_ID`, `Mail`, `IP`, `Created`) VALUES (\''.$Member_ID.'\', \''.$Login_Mail.'\', \''.$User_IP.'\', \''.$Time.'\')';
									$Failures_New = mysqli_query($Database['Connection'], $Failures_New, MYSQLI_STORE_RESULT);
									if ( !$Failures_New ) {
										if ( $Sitewide_Debug ) echo 'Invalid Query (Failures_New): '.mysqli_error($Database['Connection']);
										// TODO Handle Error
									} else {

										// TODO Use/Make Session Function
										setcookie ($Cookie_Session, '', time() - 3600, '/', $Request['host'], $Request['Secure'], $Request['HTTPOnly']);
										setcookie ($Cookie_Session, false, time() - 3600, '/', $Request['host'], $Request['Secure'], $Request['HTTPOnly']);
										unset($_COOKIE[$Cookie_Session]);

										$Member_Auth = false;
										$Member_ID = false;
										$Error = 'Incorrect Pass.';

									}

								}
							}

						}

					// IFBLOCK
					} else $Error = 'Too many login attempts. Please wait 3 minutes.';

				}

				if (!empty($Error)) { // Login Error

					$Title_HTML = 'Log In';
					$Title_Plain = 'Log In';

					$Keywords = 'log in account';

					$Canonical = $Sitewide_Account.'?login';

					require $Header;

					echo '<h2>Login Error</h2>';
					echo '<h3 class="textleft">'.$Error.' <a class="floatright" href="?login';
					if (isset($Redirect)) echo '&redirect='.$Redirect;
					echo '">Try Again</a></h3>';

					require $Footer;

				}

			} else { // IFAUTH Nothing
				$Title_HTML = 'Log In';
				$Title_Plain = 'Log In';
				$Keywords = 'log in account';
				$Canonical = $Sitewide_Account.'?login';
				require $Header;
				Member_Login_Form();
				require $Footer;
			} // LOGIN

		// LOGIN
		} else if (isset($_GET['logout'])) {
		// LOGOUT

			if (!$Member_Auth) { // Are you logged out already?
				$Error = 'We can\'t log you out, you aren\'t logged in.';

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

			if (!empty($Error)) {
				echo '<h2>Logout Error</h2>';
				echo '<h3>'.$Error.'</h3>';
			} else {
				echo '<h2>See you soon '.$Member_Name.'</h2>';
			}

			require $Footer;

		} else if (isset($_GET['register'])) { // Register

			if ($Member_Auth) { // Register Redirect
				header('Location: '.$Sitewide_Account, TRUE, 302);
				die();

			} else if (!$Sitewide_Signups) { // Register Check
				require $Header;
				echo '<h2>Sorry, new Registrations are not allowed at this time.</h2>';
				require $Footer;

			} else if (isset($_POST['name']) || isset($_POST['mail']) || isset($_POST['pass'])) { // Register Process

				if (empty($_POST['name'])) {
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

					// TODO Member_Check Function
					$Member_Check = 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE `Mail`=\''.$Signup_Mail.'\' LIMIT 0, 1';
					$Member_Check = mysqli_query($Database['Connection'], $Member_Check, MYSQLI_STORE_RESULT);
					if ( !$Member_Check ) {
						if ( $Sitewide_Debug ) echo 'Invalid Query (Member_Check): '.mysqli_error($Database['Connection']);
						// TODO Handle Error
					} else {

						$Member_Count = mysqli_num_rows($Member_Check);
						if ($Member_Count == 0) { // Not a member. Register.

							$Member_ID  = Generator_String(12);

							$Salt = Generator_String();

							$Pass_Hash = Pass_Hash($Signup_Pass, $Salt);

							$Member_New = 'INSERT INTO `'.$Database['Prefix'].'Members` (`ID`, `Mail`, `Name`, `Pass`, `Salt`, `Status`, `Created`, `Modified`) VALUES (\''.$Member_ID.'\', \''.$Signup_Mail.'\', \''.$Signup_Name.'\', \''.$Pass_Hash.'\', \''.$Salt.'\', \'Active\', \''.$Time.'\', \''.$Time.'\')';
							$Member_New = mysqli_query($Database['Connection'], $Member_New, MYSQLI_STORE_RESULT);
							if ( !$Member_New ) {
								if ( $Sitewide_Debug ) echo 'Invalid Query (Member_New): '.mysqli_error($Database['Connection']);
								// TODO Handle Error
							} else {

								require $Header;

								echo '<h2>All Signed Up.</h2>';
								echo '<h3>You can now <a href="?login">Log In</a>.</h3>';

								require $Footer;

							}

						} else { // Already a member. Sorry..?
							$Error = 'Sorry, you seem to already be registered. <a href="?login">Log In</a>?';
						}
					}
				}

				if (!empty($Error)) { // Register Error

					$Title_HTML = 'Register';
					$Title_Plain = 'Register';

					$Keywords = 'register account';

					$Canonical = $Sitewide_Account.'?register';

					require $Header;
					echo '<h2>Registration Error</h2>';
					echo '<h3 class="textleft">'.$Error.' <a class="floatright" href="?register">Try Again</a></h3>';
					require $Footer;
				}

			} else {
				require $Header; ?>
				<form class="col span_1_of_1" action="" method="post">
					<h2>Register</h2>
					<div class="section group">
						<div class="col span_1_of_3"><label for="name"><h3>Name</h3></label></div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="text" name="name" placeholder="John Smith" required /></div>
					</div>
					<div class="section group">
						<div class="col span_1_of_3"><label for="mail"><h3>Mail</h3></label></div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="email" name="mail" placeholder="johnsmith@example.com" required /></div>
					</div>
					<div class="section group">
						<div class="col span_1_of_3"><label for="pass"><h3>Pass</h3></label></div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="password" name="pass" placeholder="Qwerty1234" required /></div>
					</div>
					<div class="section group">
						<div class="col span_1_of_3">
							<p>Already one of us? &nbsp; <a class="floatright" href="?login">Login</a></p>
							<p>Forgot password? &nbsp; <a class="floatright" href="?reset">Reset</a></p>
						</div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="submit" value="Register" /></div>
					</div>
				</form>
				<div class="clear"></div>
				<?php
				require $Footer;
			}
		} else if (!empty($_GET['change'])) { // Change

			if (Input_Prepare($_GET['change']) == 'name') { // Change Name

				$Title_HTML = 'Change Name';
				$Title_Plain = 'Change Name';

				$Keywords = 'change name account';

				$Canonical = $Sitewide_Account.'?change=name';

				if (!$Member_Auth) {
					header('Location: ?login&redirect='.urlencode($Sitewide_Account.'?change=name'), TRUE, 302);
					die();

				} else {

					$Success = false;
					$Error = false;

					if ( isset($_POST['name']) ) {
						Account_Change_Name();
					}

					if ( !$Success ) {
						require $Header;
						Account_Change_Name_Form();
						require $Footer;
					}

				}

			} else if (Input_Prepare($_GET['change']) == 'pass') { // Change Pass

				$Title_HTML = 'Change Pass';
				$Title_Plain = 'Change Pass';

				$Keywords = 'change pass account';

				$Canonical = $Sitewide_Account.'?change=pass';

				if (!$Member_Auth) {
					header('Location: ?login&redirect='.urlencode($Sitewide_Account.'?change=pass'), TRUE, 302);
					die();

				} else {

					$Success = false;
					$Error = false;

					if ( isset($_POST['pass']) ) {
						Account_Change_Pass();
					}

					if ( !$Success ) {
						require $Header;
						Account_Change_Pass_Form();
						require $Footer;
					}

				}

			} else if (Input_Prepare($_GET['change']) == 'mail') { // Change Mail

				$Title_HTML = 'Change Mail';
				$Title_Plain = 'Change Mail';

				$Keywords = 'change mail account';

				$Canonical = $Sitewide_Account.'?change=mail';

				if (!$Member_Auth) {
					header('Location: ?login&redirect='.urlencode($Sitewide_Account.'?change=mail'), TRUE, 302);
					die();

				} else {

					$Success = false;
					$Error = false;

					if ( isset($_POST['mail']) ) {
						Account_Change_Mail();
					}

					if ( !$Success ) {
						require $Header;
						Account_Change_Mail_Form();
						require $Footer;
					}

				}

			} else {
				$Error = 'Invalid Change Variable';
			}

		} else if (isset($_GET['sessions'])) { // Sessions

			if (!$Member_Auth) { // Session Redirect
				header('Location: ?login&redirect='.urlencode($Sitewide_Account.'?sessions'), TRUE, 302);
				die();

			} else {

				$Title_HTML = 'Sessions';
				$Title_Plain = 'Sessions';

				$Keywords = 'sessions account';

				$Canonical = $Sitewide_Account.'?sessions';

				require $Header;

				echo '<h2>Sessions</h2>';

				Account_Sessions();

				require $Footer;

			}

		} else if (isset($_GET['reset'])) { // Reset

			$Title_HTML = 'Password Reset';
			$Title_Plain = 'Password Reset';

			$Keywords = 'password teset account';

			$Canonical = $Sitewide_Account.'?reset';

			if ($Member_Auth) { // Reset Redirect
				header('Location: '.$Sitewide_Account, TRUE, 302);
				die();

			} else if (isset($Browning) && $Browning) {

				require $Header;

				if (isset($_GET['key'])) { // Reset Process

					$Key = Input_Prepare($_GET['key']);

					$Key_Info = Runonce_Check($Key, '*', 'Password Reset');

					if ($Key_Info) {

						if (isset($_POST['pass'])) { // Reset Password Process

							$Pass_New = Input_Prepare($_POST['pass']);

							$Salt = Generator_String();
							$Pass_Hash = Pass_Hash($Pass_New, $Salt);

							// TODO Member_Exists
							// TODO Member_Change_Pass

							$Reset = 'UPDATE `'.$Database['Prefix'].'Members` SET `Pass`=\''.$Pass_Hash.'\', `Salt`=\''.$Salt.'\', `Modified`=\''.$Time.'\' WHERE `ID`=\''.$Key_Info['Member_ID'].'\' AND `Status`=\'Active\'';
							$Reset = mysqli_query($Database['Connection'], $Reset, MYSQLI_STORE_RESULT);
							if ( !$Reset ) {
								if ( $Sitewide_Debug ) echo 'Invalid Query (Reset): '.mysqli_error($Database['Connection']);
								// TODO Handle Error
							} else {

								Runonce_Delete($Key, $Key_Info['Member_ID']);

								echo '<h2>Password Reset Successfully</h2>';
								echo '<h3>You should probably go <a href="?login">login</a>.</h3>';

							}

						} else { // Reset Password Form
							?>
							<form class="col span_1_of_1" action="" method="post">
								<h2>Reset Password</h2>
								<div class="section group">
									<div class="col span_1_of_3"><label for="pass"><h3>Pass</h3></label></div>
									<div class="col span_1_of_6"><br></div>
									<div class="col span_1_of_2"><input type="password" name="pass" placeholder="Qwerty1234" required /></div>
								</div>
								<div class="section group">
									<div class="col span_1_of_3">
										<p>No account? &nbsp; <a href="?signup">Sign Up</a></p>
										<p>Remembered it? &nbsp; <a href="?login">Login</a></p>
									</div>
									<div class="col span_1_of_6"><br></div>
									<div class="col span_1_of_2">
										<input type="submit" value="Reset" />
									</div>
								</div>
							</form>
							<div class="clear"></div>
							<?php
						}

					} else {
						echo '
						<h2>Error: Invalid Key</h2>
						<h3><a href="?reset">Try again</a></h3>';
					}

				} else if (isset($_POST['mail'])) { // Reset Mail Process

					$Reset_Mail = Input_Prepare(strtolower($_POST['mail']));

					// TODO Member_Check Function
					$Member_Check = 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE `Mail`=\''.$Reset_Mail.'\' AND `Status`=\'Active\'';
					$Member_Check = mysqli_query($Database['Connection'], $Member_Check, MYSQLI_STORE_RESULT);
					if ( !$Member_Check ) {
						if ( $Sitewide_Debug ) echo 'Invalid Query (Member_Check): '.mysqli_error($Database['Connection']);
						// TODO Handle Error
					} else {

						$Member_Count = mysqli_num_rows($Member_Check);
						if ($Member_Count == 0) {
							echo '
							<h2>Error: There is no user registered with that mail.</h2>
							<h3><a href="?reset">Try again</a></h3>';
						} else {

							$Fetch_Member = mysqli_fetch_assoc($Member_Check); // Bring them to me. Alive.
							$Member_ID = $Fetch_Member['ID'];; // Number
							$Member_Name = $Fetch_Member['Name'];; // Do they have a name?

							if (isset($Browning) && $Browning) {

								// Create a single-use key with a 1 hour timeout for resetting the password.
								$Key = Runonce_Create($Time+(60*60), 1, 'Password Reset');

								require_once $Lib_Browning_Send;

								$Mail_Response = Browning_Send(
									$Reset_Mail,
									'Password Reset',
									'Hello '.$Member_Name.', you wanted to reset your password? '.$Sitewide_Root.$Sitewide_Account.'?reset&key='.$Key['Key']
								);

								if ($Mail_Response) {
									echo '
									<h2>A Password Reset has been initiated.</h2>
									<h3>Please check your email.</h3>';
								} else {
									echo '
									<h2>Error: Unable to send reset mail.</h2>
									<h3><a href="?reset">Try again</a></h3>';
								}
							} else {
								echo '
								<h2>Sorry, the administrator has not configured password resets.</h2>';
							}

						}

					}

				} else { // Reset Form
					?>
					<form class="col span_1_of_1" action="" method="post">
						<h2>Reset Password</h2>
						<div class="section group">
							<div class="col span_1_of_3"><label for="mail"><h3>Mail</h3></label></div>
							<div class="col span_1_of_6"><br></div>
							<div class="col span_1_of_2"><input type="email" name="mail" placeholder="johnsmith@example.com" required /></div>
						</div>
						<div class="section group">
							<div class="col span_1_of_3">
								<p>No account? <a class="floatright" href="?signup">Sign Up</a></p>
								<p>Remembered it? <a class="floatright" href="?login">Login</a></p>
							</div>
							<div class="col span_1_of_6"><br></div>
							<div class="col span_1_of_2"><input type="submit" value="Reset" /></div>
						</div>
					</form>
					<div class="clear"></div>
					<?php
				}

				require $Footer;

			} else {
				require $Header;
				?>
				<h2>Sorry, this installation of Simplet does not support reseting passwords.</h2>
				<h4>If you are the owner of this site, you need to set the Mailgun API URL and Key for your site.</h4>
				<?php
				require $Footer;
			}

		} else if (isset($_GET['delete'])) { // Delete

			if (!$Member_Auth) { // Login Redirect
				header('Location: ?login&redirect='.urlencode($Sitewide_Account.'?delete'), TRUE, 302);
				die();

			} else if (isset($_GET['key'])) {
				require $Header;

				$Key = Input_Prepare($_GET['key']);
				if (Runonce_Check($Key, $Member_ID, 'Account Deletion')) {

					$Member_Delete = 'UPDATE `'.$Database['Prefix'].'Members` SET `Status`=\'Deactivated\', `Modified`=\''.$Time.'\' WHERE `ID`=\''.$Member_ID.'\'';
					$Member_Delete = mysqli_query($Database['Connection'], $Member_Delete, MYSQLI_STORE_RESULT);
					if ( !$Member_Delete ) {
						if ( $Sitewide_Debug ) echo 'Invalid Query (Member_Delete): '.mysqli_error($Database['Connection']);
						// TODO Handle Error
					} else {

						Runonce_Delete($Key, $Member_ID);

						echo '
						<h2>User Deleted</h2>
						<h3>You no longer exist. Bye.</h3>';

					}

				} else {
					echo '
					<h2>Error: Invalid Key</h2>
					<h3>User deletion keys are only valid for three minutes. <a href="?delete">Try again</a></h3>';
				}

				require $Footer;

			} else {
				require $Header;

				$Key = Runonce_Create($Time+(60*3), 1, 'Account Deletion');
				?>

				<h2>Are you sure you want to delete your account?</h2>
				<div class="section group">
					<div class="col span_1_of_8"><br></div>
					<div class="col span_6_of_8">
						<p>This won't remove any of your posts, replies, reviews or comments, nor your helpfulness votes, but it will stop displaying your name and picture next to them.</p>
						<p>You can change your <a href="?change=name">name</a> or <a href="?change=mail">mail</a> if you'd prefer.</p>
						<br>
						<div class="section group">
							<div class="col span_5_of_11">
								<a href="<?php echo $Sitewide_Account; ?>" class="button blue textcenter">No, go back.</a>
							</div>
							<div class="col span_1_of_11"><br></div>
							<div class="col span_5_of_11">
								<a href="?delete&key=<?php echo $Key['Key']; ?>" class="button red textcenter">Yes, delete.</a>
							</div>
						</div>
					</div>
					<div class="col span_1_of_8"><br></div>
				</div>

				<?php
				require $Footer;
			}

		} else {

			if (!$Member_Auth) {
				header('Location: ?login&redirect='.urlencode($Sitewide_Account), TRUE, 302);
				die();

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
	} else { // Members are not enabled
		// TODO ERROR
	} // IFDATABASEMEMBERS

}