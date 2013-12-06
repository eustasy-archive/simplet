<?php

	$TextTitle = 'Login';
	$WebTitle = 'Login &nbsp;&middot;&nbsp; Account';
	$Canonical = 'account/login';
	$PostType = 'Page';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'login account';

	require_once '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	if ($Member_Auth) { // Are you logged in already?

		header('Location: /account/', TRUE, 302);
		die();

	} elseif(isset($_POST['mail']) || isset($_POST['pass'])) {

		if(!isset($_POST['pass']) || empty($_POST['pass'])) {
			$Error = 'No Pass received.';

		} elseif(!isset($_POST['mail']) || empty($_POST['mail'])) {
			$Error = 'No Mail received.';

		} else {

			$Login_Mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');
			$Login_Pass = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8');

			$Block_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Failures` WHERE `Mail`='$Login_Mail' AND `Timestamp`>NOW()-900", MYSQLI_STORE_RESULT);
			if (!$Block_Check) exit('Invalid Query (MC): ' . mysqli_error($MySQL_Connection));

			$Block_Count = mysqli_num_rows($Block_Check);

			if ($Block_Count < 3) {

				$Member_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Members` WHERE `Mail`='$Login_Mail' AND `Status`='Active'", MYSQLI_STORE_RESULT);
				if (!$Member_Check) exit('Invalid Query (Member_Check): ' . mysqli_error($MySQL_Connection));

				$Member_Count = mysqli_num_rows($Member_Check);
				if ($Member_Count == 0) {
					$Error = 'There is no user registered with that ail.';
				} else {

					$Member_Fetch = mysqli_fetch_assoc($Member_Check); // Bring them to me. Alive.
					$Member_ID = $Member_Fetch['ID'];; // Number
					$Member_Name = $Member_Fetch['Name'];; // Do they have a name?
					$Member_Pass = $Member_Fetch['Pass'];; // Number
					$Member_Salt = $Member_Fetch['Salt'];; // Do they have a name?

					$Login_Hash = passHash($Login_Pass, $Member_Salt);

					if ($Login_Hash === $Member_Pass) {

						$Member_Cookie = stringGenerator();

						setcookie ('l', $Member_Cookie, time()+60*60*24*28, '/', '.eustasy.org');

						$Session_New = mysqli_query($MySQL_Connection, "INSERT INTO `Sessions` (`Member_ID`, `Mail`, `Cookie`, `IP`, `Active`) VALUES ('$Member_ID', '$Login_Mail', '$Member_Cookie', '$User_IP', '1')", MYSQLI_STORE_RESULT);
						if (!$Session_New) exit('Invalid Query (Session_New): ' . mysqli_error($MySQL_Connection));

						// If this is true, the login was successful.
						header('Location: /account/', TRUE, 302); // As in go away.
						die(); // Now.

					} else { // Failed Login

						$Failures_New = mysqli_query($MySQL_Connection, "INSERT INTO `Failures` (`Mail`, `IP`) VALUES ('$Login_Mail', '$User_IP')", MYSQLI_STORE_RESULT);
						if (!$Failures_New) exit('Invalid Query (Failures_New): ' . mysqli_error($WriteConnection));

						setcookie ('l', '', time() - 3600, '/', '.eustasy.org', 1); // Clear the Cookie
						setcookie ('l', false, time() - 3600, '/', '.eustasy.org', 1); // Definitely
						unset($_COOKIE['l']); // Absolutely

						$Member_Auth = false;
						$Member_ID = false;
						$Error = 'Incorrect Pass.';
					}

				}

			} else {
				$Error = 'Too many login attempts.';
			}

		}

		if(isset($Error) && !empty($Error)) {

			require '../../header.php';

			echo '<h2>Login Error</h2>';
			echo '<h3 class="textleft">' . $Error . ' <a class="floatright" href="login">Try Again</a></h3>';

			require '../../footer.php';

		}

	} else {

		require '../../header.php';

		?>

		<form class="col span_1_of_1" action="" method="post">
			<h2>Login</h2>
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
					<p>No account? <a class="floatright" href="signup">Sign Up</a></p>
					<p>Forgot password? <a class="floatright" href="reset">Reset</a></p>
				</div>
				<div class="col span_1_of_6"><br></div>
				<div class="col span_1_of_2"><input type="submit" value="Login" /></div>
			</div>
		</form>
		<div class="clear"></div>

<?php require '../../footer.php'; } } ?>
