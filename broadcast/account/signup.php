<?php

	$TextTitle = 'Signup';
	$WebTitle = 'Signup &nbsp;&middot;&nbsp; Account';
	$Canonical = 'account/signup';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'signup account';

	require '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	if ($Member_Auth) { // Are you logged in already?

		header('Location: /account/', TRUE, 302);
		die();

	} elseif(isset($_POST['name']) || isset($_POST['mail']) || isset($_POST['pass'])) {

		if(!isset($_POST['name']) || empty($_POST['name'])) {
			$Error = 'We really need an name.';

		} elseif(!isset($_POST['mail']) || empty($_POST['mail'])) {
			$Error = 'We really need an email.';

		} elseif(!isset($_POST['pass']) || empty($_POST['pass'])) {
			$Error = 'You really need a password.';

		} else {

			$Signup_Name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
			$Signup_Mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');
			$Signup_Pass = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8');

			$Member_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Members` WHERE `Mail`='$Signup_Email' LIMIT 0, 1", MYSQLI_STORE_RESULT);
			if (!$Member_Check) exit('Invalid Query (Member_Check): ' . mysqli_error($MySQL_Connection));

			$Member_Count = mysqli_num_rows($Member_Check);
			if ($Member_Count == 0) { // Not a member. Signup.

				$Member_ID_Characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
				$Member_ID_Characters_Count = strlen( $Member_ID_Characters );
				for( $i = 0; $i < 12; $i++ ) {
					$Member_ID .= $Member_ID_Characters[ rand( 0, $Member_ID_Characters_Count - 1 ) ];
				}

				$Salt_Characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
				$Salt_Characters_Count = strlen( $Salt_Characters );
				for( $i = 0; $i < 64; $i++ ) {
					$Salt .= $Salt_Characters[ rand( 0, $Salt_Characters_Count - 1 ) ];
				}

				$Hash_Method = 'sha512'; // Could also use sha1, sha512 etc, etc
				$Pass_Hash = hash($Hash_Method, hash($Hash_Method, $Signup_Pass) . hash($Hash_Method, $Salt));

				$Member_New = mysqli_query($MySQL_Connection, "INSERT INTO `Members` (`ID`, `Mail`, `Name`, `Pass`, `Salt`, `Status`) VALUES ('$Member_ID', '$Signup_Mail', '$Signup_Name', '$Pass_Hash', '$Salt', 'Active')", MYSQLI_STORE_RESULT);
				if (!$Member_New) exit('Invalid Query (Member_New): ' . mysqli_error($MySQL_Connection));

				require '../../header.php';

				echo '<h2>All Signed Up.</h2>';
				echo '<h3>You can now <a href="login">login</a>.</h3>';

				require '../../footer.php';

			} else { // Already a member. Sorry..?
				$Error = 'Sorry, you seem to already be registered. <a href="login">Login</a>?';
			}
		}

		if($Error) {

			require '../../header.php';

			echo '<h2>Signup Error</h2>';
			echo '<h3>' . $Error . '</h3>';

			require '../../footer.php';

		}

	} else {

		require '../../header.php'; ?>

		<form class="col span_1_of_1" action="" method="post">
			<h2>Sign Up</h2>
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
					<p>Already one of us? &nbsp; <a class="floatright" href="login">Login</a></p>
					<p>Forgot password? &nbsp; <a class="floatright" href="reset">Reset</a></p>
				</div>
				<div class="col span_1_of_6"><br></div>
				<div class="col span_1_of_2"><input type="submit" value="Sign Up" /></div>
			</div>
		</form>
		<div class="clear"></div>

<?php

		require '../../footer.php';

	}

} ?>
