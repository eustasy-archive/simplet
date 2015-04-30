<?php

function Member_Register_Form() {

	global $Sitewide, $Sitewide_Security_Password_Length;

	if ( $Sitewide['Signups'] ) {
		?>
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
						<div class="col span_1_of_2">

						<?php
						echo '<input type="password" name="pass" placeholder="Qwerty1234" required pattern=".{',$Sitewide_Security_Password_Length,',}" title="Passwords must be at least ',$Sitewide_Security_Password_Length,' characters in length." /></div>';
						?>

					</div>
					<div class="section group">
						<div class="col span_1_of_3">
							<p>Already one of us? &nbsp; <a class="floatright" href="?login">Login</a></p>
							<p>Forgot password? &nbsp; <a class="floatright" href="?reset">Reset</a></p>
						</div>
						<div class="col span_1_of_6"><br><?php echo Runonce_CSRF_Form(); ?></div>
						<div class="col span_1_of_2"><input type="submit" value="Register" /></div>
					</div>
				</form>
				<div class="clear"></div>
		<?php
	} else {
		echo '
		<h2>Sorry, new registrations are not available at this time.</h2>';
	}

}