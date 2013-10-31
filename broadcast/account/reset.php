<?php

	$TextTitle = 'Reset';
	$WebTitle = 'Reset &nbsp;&middot;&nbsp; Account';
	$Canonical = 'account/reset';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'reset account';

	require '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	if ($Member_Auth) { // Logged In

		header('Location: /account/', TRUE, 302);
		die();

	} elseif(isset($Mail_URL) && isset($Mail_Key) && !empty($Mail_URL) && !empty($Mail_Key)) {

		if(isset($_GET['key'])) { // Enter Password

			$Reset_Key = htmlentities($_GET['key'], ENT_QUOTES, 'UTF-8');

			$Key_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Resets` WHERE `Key`='$Reset_Key' AND `Active`='1' LIMIT 0, 1", MYSQLI_STORE_RESULT);
			if (!$Key_Check) exit('Invalid Query (Key_Check): ' . mysqli_error($MySQL_Connection));
			$Key_Count = mysqli_num_rows($Key_Check);
			if ($Key_Count == 0) {
				$Error = 'Invalid Key.';
			} else {

				if(isset($_POST['pass'])) { // New Password

					$Pass_New = htmlentities($_POST['pass'], ENT_QUOTES, 'UTF-8');

					$Key_Fetch = mysqli_fetch_assoc($Key_Check); // Bring them to me. Alive.
					$Member_Mail = $Key_Fetch['Mail'];; // Number

					$Salt_Characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
					$Salt_Characters_Count = strlen( $Salt_Characters );
					$Salt = '';
					for( $i = 0; $i < 64; $i++ ) {
						$Salt .= $Salt_Characters[ rand( 0, $Salt_Characters_Count - 1 ) ];
					}

					$Hash_Method = 'sha512'; // Could also use sha1, sha512 etc, etc
					$Pass_Hash = hash($Hash_Method, hash($Hash_Method, $Pass_New) . hash($Hash_Method, $Salt));

					$Reset = mysqli_query($MySQL_Connection, "UPDATE `Members` SET `Pass`='$Pass_Hash', `Salt`='$Salt' WHERE `Mail`='$Member_Mail' AND `Status`='Active'", MYSQLI_STORE_RESULT);
					if (!$Reset) exit('Invalid Query (Reset): ' . mysqli_error($MySQL_Connection));

					$Key_Remove = mysqli_query($MySQL_Connection, "UPDATE `Resets` SET `Active`='0' WHERE `Key`='$Key'", MYSQLI_STORE_RESULT);
					if (!$Key_Remove) exit('Invalid Query (Key_Remove): ' . mysqli_error($Connection_write));

					require '../../header.php';

					echo '<h2>Pass Reset Sucessfully</h2>';
					echo '<h3>You should probably go <a href="login">login</a>.</h3>';

					require '../../footer.php';


				} else {

					require '../../header.php'; ?>

					<form class="col span_1_of_1" action="" method="post">
						<h2>Reset Password</h2>
						<div class="section group">
							<div class="col span_1_of_3"><label for="pass"><h3>Pass</h3></label></div>
							<div class="col span_1_of_6"><br></div>
							<div class="col span_1_of_2"><input type="password" name="pass" placeholder="Qwerty1234" required /></div>
						</div>
						<div class="section group">
							<div class="col span_1_of_3">
								<p>No account? &nbsp; <a href="signup">Sign Up</a></p>
								<p>Remembered it? &nbsp; <a href="login">Login</a></p>
							</div>
							<div class="col span_1_of_6"><br></div>
							<div class="col span_1_of_2"><input type="hidden" name="key" value="<?php echo $Key; ?>" /><input type="submit" value="Reset" /></div>
						</div>
					</form>
					<div class="clear"></div>

	<?php 			require '../../footer.php';

				}

			}

			if(isset($Error)) {

				require '../../header.php';

				echo '<h2>Reset Error</h2>';
				echo '<h3>' . $Error . '</h3>';

				require '../../footer.php';

			}

		} elseif(isset($_POST['mail'])) { // Send Mail

			$Reset_Mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');

			$Member_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Members` WHERE `Mail`='$Reset_Mail' AND `Status`='Active'", MYSQLI_STORE_RESULT);
			if (!$Member_Check) exit('Invalid Query (Member_Check): ' . mysqli_error($MySQL_Connection));

			$Member_Count = mysqli_num_rows($Member_Check);
			if ($Member_Count == 0) {
				$Error = 'There is no user registered with that email.';
			} else {

				$Fetch_Member = mysqli_fetch_assoc($Member_Check); // Bring them to me. Alive.
				$Member_ID = $Fetch_Member['ID'];; // Number
				$Member_Name = $Fetch_Member['Name'];; // Do they have a name?

				$Reset_Characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
				$Reset_Characters_Count = strlen( $Reset_Characters );
				$Reset_Key = '';
				for( $i = 0; $i < 64; $i++ ) {
					$Reset_Key .= $Reset_Characters[ rand( 0, $Reset_Characters_Count - 1 ) ];
				}

				$Reset_New = mysqli_query($MySQL_Connection, "INSERT INTO `Resets` (`Mail`, `Key`, `Active`, `IP`) VALUES ('$Reset_Mail', '$Reset_Key', '1', '$User_IP');", MYSQLI_STORE_RESULT);
				if (!$Reset_New) exit('Invalid Query (Reset_New): ' . mysqli_error($WriteConnection));

				$Mail_Curl = curl_init();

				curl_setopt($Mail_Curl, CURLOPT_URL, $Mail_URL);
				curl_setopt($Mail_Curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($Mail_Curl, CURLOPT_USERPWD, 'api:'.$Mail_Key);
				curl_setopt($Mail_Curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt_array($Mail_Curl, array(
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => array(
						'from' => $Mail_From.' <'.$Mail_Reply.'>',
						'to' => $Reset_Mail,
						'subject' => 'Password Reset',
						'text' => 'Hello ' . $Member_Name . ', you wanted to reset your password? '.$Request['scheme'].'://'.$Request['host']'/account/reset?key=' . $Reset_Key,
					)
				));

				$Mail_Response = curl_exec($Mail_Curl);
				$Mail_Info = curl_getinfo($Mail_Curl);

				if(curl_errno($Mail_Curl)) exit(curl_errno($Mail_Curl) . ' Error: ' . curl_error($Mail_Curl));

				if($Mail_Response) {
					$Reset_Message = 'A Password Reset has been initiated. Please check your email.';
				} else {
					$Error = 'Unable to send mail.';
				}
				curl_close($Mail_Curl);

			}

			require '../../header.php';

			if(isset($Error)) {
				echo '<h2>Password Reset Failed</h2>';
				echo '<h3>' . $Error . '</h3>';
			} else {
				echo '<h2>Password Reset Initiated</h2>';
				echo '<h3>An email has been sent to ' . $Reset_Mail . '</h3>';
			}

			require '../../footer.php';

		} else { // Ask for Mail

			require '../../header.php';

			?>

			<form class="col span_1_of_1" action="" method="post">
				<h2>Reset Password</h2>
				<div class="section group">
					<div class="col span_1_of_3"><label for="mail"><h3>Mail</h3></label></div>
					<div class="col span_1_of_6"><br></div>
					<div class="col span_1_of_2"><input type="mail" name="mail" placeholder="johnsmith@example.com" required /></div>
				</div>
				<div class="section group">
					<div class="col span_1_of_3">
						<p>No account? <a class="floatright" href="signup">Sign Up</a></p>
						<p>Remembered it? <a class="floatright" href="login">Login</a></p>
					</div>
					<div class="col span_1_of_6"><br></div>
					<div class="col span_1_of_2"><input type="submit" value="Reset" /></div>
				</div>
			</form>
			<div class="clear"></div>

<?php		require '../../footer.php';

		}

	} else { ?>

		<h2>Sorry, this installation of Simplet does not support reseting passwords.</h2>
		<h3>If you are the owner of this site, you need to set the Mailgun API URL and Key for your site.</h3>

<?php

	}

} ?>
