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

	require_once __DIR__.'/../request.php';

	$Account = $Canonical; // Canonical may change later, Account won't
	$Header = '../header.php';
	$Footer = '../footer.php';
	$Lib_Browning_Config = '../libs/Browning_Config.php';
	$Lib_Browning_Send = '../libs/Browning_Send.php';

if ($Request['path'] === $Place['path'].$Canonical) {

	if (isset($_GET['login'])) { // Login

		if ($Member_Auth) { // Login Redirect
			header('Location: '.$Place['path'].$Canonical, TRUE, 302);
			die();

		} else if (isset($_POST['mail']) || isset($_POST['pass'])) { // Login Process

			if (!isset($_POST['pass']) || empty($_POST['pass'])) {
				$Error = 'No Pass received.';

			} else if (!isset($_POST['mail']) || empty($_POST['mail'])) {
				$Error = 'No Mail received.';

			} else {

				$Login_Mail = htmlentities($_POST['mail'], ENT_QUOTES, 'UTF-8');
				$Login_Pass = htmlentities($_POST['pass'], ENT_QUOTES, 'UTF-8');

				$Block_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Failures` WHERE `Mail`='$Login_Mail' AND `Created`>UNIX_TIMESTAMP()-900", MYSQLI_STORE_RESULT);
				if (!$Block_Check) exit('Invalid Query (MC): '.mysqli_error($MySQL_Connection));

				$Block_Count = mysqli_num_rows($Block_Check);

				if ($Block_Count < 3) {

					$Member_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Members` WHERE `Mail`='$Login_Mail' AND `Status`='Active'", MYSQLI_STORE_RESULT);
					if (!$Member_Check) exit('Invalid Query (Member_Check): '.mysqli_error($MySQL_Connection));

					$Member_Count = mysqli_num_rows($Member_Check);
					if ($Member_Count == 0) {
						$Error = 'There is no user registered with that mail.';
					} else {

						$Member_Fetch = mysqli_fetch_assoc($Member_Check);
						$Member_ID = $Member_Fetch['ID'];
						$Member_Name = $Member_Fetch['Name'];
						$Member_Pass = $Member_Fetch['Pass'];
						$Member_Salt = $Member_Fetch['Salt'];

						$Login_Hash = passHash($Login_Pass, $Member_Salt);

						if ($Login_Hash === $Member_Pass) {

							$Member_Cookie = stringGenerator();

							setcookie('l', $Member_Cookie, time()+60*60*24*28, '/', $Place['host']);

							$Session_New = mysqli_query($MySQL_Connection, "INSERT INTO `Sessions` (`Member_ID`, `Mail`, `Cookie`, `IP`, `Active`, `Created`, `Modified`) VALUES ('$Member_ID', '$Login_Mail', '$Member_Cookie', '$User_IP', '1', '$Time', '$Time')", MYSQLI_STORE_RESULT);
							if (!$Session_New) exit('Invalid Query (Session_New): '.mysqli_error($MySQL_Connection));

							// Login Successful
							if (isset($_GET['redirect'])) header('Location: /'.urldecode($_GET['redirect']), TRUE, 302);
							else header('Location: '.$Account, TRUE, 302);
							die(); // As in go away.

						} else {

							$Failures_New = mysqli_query($MySQL_Connection, "INSERT INTO `Failures` (`Member_ID`, `Mail`, `IP`, `Created`) VALUES ('$Member_ID', '$Login_Mail', '$User_IP', '$Time')", MYSQLI_STORE_RESULT);
							if (!$Failures_New) exit('Invalid Query (Failures_New): '.mysqli_error($MySQL_Connection));

							setcookie ('l', '', time() - 3600, '/', $Place['host'], 1); // Clear the Cookie
							setcookie ('l', false, time() - 3600, '/', $Place['host'], 1); // Definitely
							unset($_COOKIE['l']); // Absolutely

							$Member_Auth = false;
							$Member_ID = false;
							$Error = 'Incorrect Pass.';

						}
					}

				} else {
					$Error = 'Too many login attempts. You get three every 15 minutes.';
				}

			}

			if (isset($Error) && !empty($Error)) { // Login Error
				$TextTitle = 'Log In';
				$WebTitle = 'Log In &nbsp;&middot;&nbsp; Account';
				$Canonical = $Account.'?login';
				$Keywords = 'log in account';
				require $Header;
				echo '<h2>Login Error</h2>';
				echo '<h3 class="textleft">'.$Error.' <a class="floatright" href="?login';
				if (isset($_GET['redirect'])) echo $_GET['redirect'];
				echo '">Try Again</a></h3>';
				require $Footer;
			}

		} else { // Login Form
			$TextTitle = 'Log In';
			$WebTitle = 'Log In &nbsp;&middot;&nbsp; Account';
			$Canonical = $Account.'?login';
			$Keywords = 'log in account';
			require $Header;
			?>
			<form class="col span_1_of_1" action="" method="post">
				<h2>Log In</h2>
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
						<?php if ($Sitewide_Signups) echo '
						<p>No account? <a class="floatright" href="?register">Register</a></p>'; ?>
						<p>Forgot password? <a class="floatright" href="?reset">Reset</a></p>
					</div>
					<div class="col span_1_of_6"><br></div>
					<div class="col span_1_of_2"><input type="submit" value="Log In" /></div>
				</div>
			</form>
			<div class="clear"></div>
			<?php
			require $Footer;
		}
	} else if (isset($_GET['logout'])) { // Log Out

		if (!$Member_Auth) { // Are you logged out already?
			$Error = 'We can\'t log you out, you aren\'t logged in.';

		} else {

			$Session_End = mysqli_query($MySQL_Connection, "UPDATE `Sessions` SET `Active`='0', `Modified`='$Time' WHERE `Member_ID`='$Member_ID' AND `Cookie`='$User_Cookie' AND `IP`='$User_IP'", MYSQLI_STORE_RESULT);
			if (!$Session_End) exit('Invalid Query (Session_End): '.mysqli_error($MySQL_Connection));

			setcookie ('l', '', time() - 3600, '/', $Place['host'], 1); // Clear the Cookie
			setcookie ('l', false, time() - 3600, '/', $Place['host'], 1); // Definitely
			unset($_COOKIE['l']); // Absolutely

			$Member_Auth = false; // Not at all
			$Member_ID = false; // You're not even human

		}

		$TextTitle = 'Log Out';
		$WebTitle = 'Log Out &nbsp;&middot;&nbsp; Account';
		$Canonical = 'account?logout';
		$PostType = 'Page';
		$FeaturedImage = '';
		$Description = '';
		$Keywords = 'log out account';

		require $Header;

		if (isset($Error) && !empty($Error)) {
			echo '<h2>Logout Error</h2>';
			echo '<h3>'.$Error.'</h3>';
		} else {
			echo '<h2>See you soon '.$Member_Name.'</h2>';
		}

		require $Footer;

	} else if (isset($_GET['register'])) { // Register

		if ($Member_Auth) { // Register Redirect
			header('Location: '.$Account, TRUE, 302);
			die();

		} else if (!$Sitewide_Signups) { // Register Check
			require $Header;
			echo '<h2>Sorry, new Registrations are not allowed at this time.</h2>';
			require $Footer;

		} else if (isset($_POST['name']) || isset($_POST['mail']) || isset($_POST['pass'])) { // Register Process

			if (!isset($_POST['name']) || empty($_POST['name'])) {
				$Error = 'We really need an name.';

			} else if (!isset($_POST['mail']) || empty($_POST['mail'])) {
				$Error = 'We really need an email.';

			} else if (!isset($_POST['pass']) || empty($_POST['pass'])) {
				$Error = 'You really need a password.';

			} else {

				$Signup_Name = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
				$Signup_Mail = htmlentities($_POST['mail'], ENT_QUOTES, 'UTF-8');
				$Signup_Pass = htmlentities($_POST['pass'], ENT_QUOTES, 'UTF-8');

				$Member_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Members` WHERE `Mail`='$Signup_Mail' LIMIT 0, 1", MYSQLI_STORE_RESULT);
				if (!$Member_Check) exit('Invalid Query (Member_Check): '.mysqli_error($MySQL_Connection));

				$Member_Count = mysqli_num_rows($Member_Check);
				if ($Member_Count == 0) { // Not a member. Register.

					$Member_ID  = stringGenerator(12);

					$Salt = stringGenerator();

					$Pass_Hash = passHash($Signup_Pass, $Salt);

					$Member_New = mysqli_query($MySQL_Connection, "INSERT INTO `Members` (`ID`, `Mail`, `Name`, `Pass`, `Salt`, `Status`, `Created`, `Modified`) VALUES ('$Member_ID', '$Signup_Mail', '$Signup_Name', '$Pass_Hash', '$Salt', 'Active', '$Time', '$Time')", MYSQLI_STORE_RESULT);
					if (!$Member_New) exit('Invalid Query (Member_New): '.mysqli_error($MySQL_Connection));

					require $Header;

					echo '<h2>All Signed Up.</h2>';
					echo '<h3>You can now <a href="?login">Log In</a>.</h3>';

					require $Footer;

				} else { // Already a member. Sorry..?
					$Error = 'Sorry, you seem to already be registered. <a href="?login">Log In</a>?';
				}
			}

			if (isset($Error) && !empty($Error)) { // Register Error
				$TextTitle = 'Register';
				$WebTitle = 'Register &nbsp;&middot;&nbsp; Account';
				$Canonical = $Account.'?register';
				$Keywords = 'register account';
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
	} else if (isset($_GET['change']) && !empty($_GET['change'])) { // Change

		if (htmlentities($_GET['change'], ENT_QUOTES, 'UTF-8') == 'name') { // Change Name

			$TextTitle = 'Change Name';
			$WebTitle = 'Change Name &nbsp;&middot;&nbsp; Account';
			$Canonical = $Account.'?change=name';
			$Keywords = 'change name account';

			if (!$Member_Auth) { // Change Name Redirect
				header('Location: ?login&redirect='.urlencode($Account.'?change=name'), TRUE, 302);
				die();

			} else if (isset($_POST['name'])) { // Change Name Process

				$Name_New = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');

				if (empty($Name_New)) {
					$Error = 'Name cannot be empty.';
				} else {

					$Name_Change = mysqli_query($MySQL_Connection, "UPDATE `Members` SET `Name`='$Name_New', `Modified`='$Time' WHERE `ID`='$Member_ID'", MYSQLI_STORE_RESULT);
					if (!$Name_Change) exit('Invalid Query (Name_Change): '.mysqli_error($MySQL_Connection));

					header('Location: '.$Account, TRUE, 302);
					die();

				}
			} else { // Change Name Form
				require $Header;
				?>
				<form class="col span_1_of_1" action="" method="post">
					<h2>Change your Name</h2>
					<div class="section group">
						<div class="col span_1_of_3"><label for="name"><h3>Name</h3></label></div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="text" name="name" placeholder="<?php echo $Member_Name; ?>" value="<?php echo $Member_Name; ?>" required /></div>
					</div>
					<div class="section group">
						<div class="col span_1_of_3"><br></div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="submit" value="Change Name" /></div>
					</div>
				</form>
				<div class="clear"></div>
				<?php
				require $Footer;
			}
		} else if (htmlentities($_GET['change'], ENT_QUOTES, 'UTF-8') == 'pass') { // Change Pass

			$TextTitle = 'Change Pass';
			$WebTitle = 'Change Pass &nbsp;&middot;&nbsp; Account';
			$Canonical = $Account.'?change=pass';
			$Keywords = 'change pass account';

			if (!$Member_Auth) { // Change Pass Redirect
				header('Location: ?login&redirect='.urlencode($Account.'?change=pass'), TRUE, 302);
				die();

			} else if (isset($_POST['pass'])) { // Change Pass Process

				$Pass_New = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8');

				if (empty($Pass_New)) {
					$Error = 'Pass cannot be empty.';
				} else {

					$Salt = stringGenerator();
					$Pass_Hash = passHash($Pass_New, $Salt);

					$Pass_Change = mysqli_query($MySQL_Connection, "UPDATE `Members` SET `Pass`='$Pass_Hash', `Salt`='$Salt', `Modified`='$Time' WHERE `ID`='$Member_ID'", MYSQLI_STORE_RESULT);
					if (!$Pass_Change) exit('Invalid Query (Pass_Change): '.mysqli_error($MySQL_Connection));

					header('Location: '.$Account, TRUE, 302);
					die();

				}
			} else { // Change Pass Form
				require $Header;
				?>
				<form class="col span_1_of_1" action="" method="post">
					<h2>Change your Pass</h2>
					<div class="section group">
						<div class="col span_1_of_3"><label for="pass"><h3>Pass</h3></label></div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="password" name="pass" placeholder="Qwerty12345" required /></div>
					</div>
					<div class="section group">
						<div class="col span_1_of_3"><br></div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="submit" value="Change Pass" /></div>
					</div>
				</form>
				<div class="clear"></div>
				<?php
				require $Footer;
			}
		} else if (htmlentities($_GET['change'], ENT_QUOTES, 'UTF-8') == 'mail') { // Change Mail

			$TextTitle = 'Change Mail';
			$WebTitle = 'Change Mail &nbsp;&middot;&nbsp; Account';
			$Canonical = $Account.'?change=mail';
			$Keywords = 'change mail account';

			if (!$Member_Auth) { // Change Mail Redirect
				header('Location: ?login&redirect='.urlencode($Account.'?change=mail'), TRUE, 302);
				die();

			} else if (isset($_POST['mail'])) { // Change Mail Process

				$Mail_New = htmlentities($_POST['mail'], ENT_QUOTES, 'UTF-8');

				if (empty($Mail_New)) {
					$Error = 'Mail cannot be empty.';
				} else {

					$Mail_Change = mysqli_query($MySQL_Connection, "UPDATE `Members` SET `Mail`='$Mail_New', `Modified`='$Time' WHERE `ID`='$Member_ID'", MYSQLI_STORE_RESULT);
					if (!$Mail_Change) exit('Invalid Query (Mail_Change): '.mysqli_error($MySQL_Connection));

					header('Location: '.$Account, TRUE, 302);
					die();

				}
			} else { // Change Mail Form
				require $Header;
				?>
				<form class="col span_1_of_1" action="" method="post">
					<h2>Change your Mail</h2>
					<div class="section group">
						<div class="col span_1_of_3"><label for="mail"><h3>Mail</h3></label></div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="email" name="mail" placeholder="<?php echo $Member_Mail; ?>" value="<?php echo $Member_Mail; ?>" required /></div>
					</div>
					<div class="section group">
						<div class="col span_1_of_3"><br></div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="submit" value="Change Mail" /></div>
					</div>
				</form>
				<div class="clear"></div>
				<?php
				require $Footer;
			}
		} else {
			$Error = 'Invalid Change Variable';
		}

		if (isset($Error) && !empty($Error)) { // Change Error
			require $Header;
			echo '<h2>Change Error</h2>';
			echo '<h3 class="textleft">'.$Error.' <a class="floatright" href="">Try Again</a></h3>';
			require $Footer;
		}

	} else if (isset($_GET['sessions'])) { // Sessions

		if (!$Member_Auth) { // Session Redirect
			header('Location: ?login&redirect='.urlencode($Account.'?sessions'), TRUE, 302);
			die();

		} else {

			$TextTitle = 'Sessions';
			$WebTitle = 'Sessions &nbsp;&middot;&nbsp; Account';
			$Canonical = $Account.'?sessions';
			$Keywords = 'sessions manage account';

			require $Header;

			echo '<h2>Sessions</h2>';

			if (isset($_GET['cookie'])) {

				$Get_Cookie = htmlspecialchars($_GET['cookie'], ENT_QUOTES, 'UTF-8');

				$Session_End = mysqli_query($MySQL_Connection, "UPDATE `Sessions` SET `Active`='0', `Modified`='$Time' WHERE `Member_ID`='$Member_ID' AND `Cookie`='$Get_Cookie'", MYSQLI_STORE_RESULT);
				if (!$Session_End) exit('Invalid Query (Session_End): ' . mysqli_error($MySQL_Connection));

				echo '<h3>Session Terminated</h3>';

			}

			$Sessions = mysqli_query($MySQL_Connection, "SELECT * FROM `Sessions` WHERE `Member_ID`='$Member_ID' AND `Active`='1' AND NOT `Cookie`='$User_Cookie'", MYSQLI_STORE_RESULT);
			if (!$Sessions) exit('Invalid Query (Sessions): ' . mysqli_error($MySQL_Connection));

			$Sessions_Count = mysqli_num_rows($Sessions);
			if ($Sessions_Count == 0) {
				echo '<h3>No other active sessions.</h3>';
			} else {
				while ($Sessions_Fetch = mysqli_fetch_assoc($Sessions)) {
				 	echo '<p>Login';
				 	if (isset($Sessions_Fetch['IP']) && !empty($Sessions_Fetch['IP'])) {
				 		echo ' from ';
				 		if (function_exists('geoip_country_name_by_name')) {
				 			echo geoip_country_name_by_name($Sessions_Fetch['IP']);
				 		} else {
				 			echo $Sessions_Fetch['IP'];
				 		}
				 	}
				 	echo ' at ' . $Sessions_Fetch['Created'] . ' <a class="floatright" href="?sessions&cookie=' . $Sessions_Fetch['Cookie'] . '">Terminate</a></p>';
				}
			}

			require $Footer;

		}

	} else if (isset($_GET['reset'])) { // Reset

		$TextTitle = 'Password Reset';
		$WebTitle = 'Password &nbsp;&middot;&nbsp; Account';
		$Canonical = $Account.'?reset';
		$Keywords = 'reset password account';

		if ($Member_Auth) { // Reset Redirect
			header('Location: '.$Account, TRUE, 302);
			die();

		} else if (isset($Browning) && $Browning) {

			require $Header;

			if (isset($_GET['key'])) { // Reset Process

				$Key = htmlentities($_GET['key'], ENT_QUOTES, 'UTF-8');

				if (runonceCheck($Key, '*')) {

					if (isset($_POST['pass'])) { // Reset Password Process

						$Pass_New = htmlentities($_POST['pass'], ENT_QUOTES, 'UTF-8');

						$Key_Fetch = mysqli_fetch_assoc($Key_Check); // Bring them to me. Alive.
						$Member_ID = $Key_Fetch['Member_ID'];

						$Salt = stringGenerator();

						$Pass_Hash = passHash($Pass_New, $Salt);

						// TODO memberExists
						// TODO memberChangePass
						// TODO runonceDestroy
						$Reset = mysqli_query($MySQL_Connection, "UPDATE `Members` SET `Pass`='$Pass_Hash', `Salt`='$Salt', `Modified`='$Time' WHERE `ID`='$Member_ID' AND `Status`='Active'", MYSQLI_STORE_RESULT);
						if (!$Reset) exit('Invalid Query (Reset): '.mysqli_error($MySQL_Connection));

					///	$Key_Remove = mysqli_query($MySQL_Connection, "UPDATE `Resets` SET `Active`='0', `Modified`='$Time' WHERE `Key`='$Reset_Key'", MYSQLI_STORE_RESULT);
					//	if (!$Key_Remove) exit('Invalid Query (Key_Remove): '.mysqli_error($MySQL_Connection));

						echo '<h2>Password Reset Successfully</h2>';
						echo '<h3>You should probably go <a href="?login">login</a>.</h3>';

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

				$Reset_Mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');

				$Member_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Members` WHERE `Mail`='$Reset_Mail' AND `Status`='Active'", MYSQLI_STORE_RESULT);
				if (!$Member_Check) exit('Invalid Query (Member_Check): '.mysqli_error($MySQL_Connection));

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

						$Key = runonceCreate('', '*');

						require_once $Lib_Browning_Send;

						$Mail_Response = Browning_Send(
							$Reset_Mail,
							'Password Reset',
							'Hello '.$Member_Name.', you wanted to reset your password? '.$Sitewide_Root.$Account.'?reset&key='.$Key['Key']
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
		require $Header;

		if (isset($_GET['key'])) {

			$Key = htmlentities($_GET['key'], ENT_QUOTES, 'UTF-8');
			if (runonceCheck($Key, $Member_ID)) {

				// TODO Delete.
				// Delete Record from `Members`
				// Make sure others fallback to deactivated

				echo '
				<h2>User Deleted</h2>
				<h3>You no longer exist. Bye.</h3>';

			} else {
				echo '
				<h2>Error: Invalid Key</h2>
				<h3><a href="?delete">Try again</a></h3>';
			}

		} else {

			$Key = runonceCreate();
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
							<a href="<?php echo $Account; ?>" class="button blue textcenter">No, go back.</a>
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
		}

		require $Footer;
	} else {

		if (!$Member_Auth) {
			header('Location: ?login&redirect='.urlencode($Account), TRUE, 302);
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
}
