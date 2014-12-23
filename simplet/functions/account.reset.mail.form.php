<?php

////	Account Reset Mail Form Function
//
// Form for changing an accounts Mail.
//
////	TODO
// No "Cancel" Button

function Member_Reset_Mail_Form() {

	global $Error, $Success;

	if ( !empty($Success) ) {
		echo $Success;
	} else {
		echo '
		<form class="col span_1_of_1" action="" method="post">
			<h2>Reset Password</h2>';
				if ( !empty($Error) ) {
					echo $Error;
				}
			?>
			<div class="section group">
				<div class="col span_1_of_3"><label for="mail"><h3>Mail</h3></label></div>
				<div class="col span_1_of_6"><br><?php echo Runonce_CSRF_Form(); ?></div>
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

}