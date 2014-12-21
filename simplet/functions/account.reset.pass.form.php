<?php

////	Account Reset Pass Form Function
//
// Form for changing an accounts Pass.
//
////	TODO
// No "Cancel" Button

function Account_Reset_Pass_Form() {

	global $Error;

	?>
		<form class="col span_1_of_1" action="" method="post">
			<h2>Reset Password</h2>
			<?php
				if ( !empty($Error) ) {
					echo $Error;
				}
			?>
			<div class="section group">
				<div class="col span_1_of_3"><label for="pass"><h3>Pass</h3></label></div>
				<div class="col span_1_of_6"><br><?php echo Runonce_CSRF_Form(); ?></div>
				<div class="col span_1_of_2"><input type="password" name="pass" placeholder="Qwerty1234" required /></div>
			</div>
			<div class="section group">
				<div class="col span_1_of_3">
					<p>No account? &nbsp; <a href="?signup">Sign Up</a></p>
					<p>Remembered it? &nbsp; <a href="?login">Login</a></p>
				</div>
				<div class="col span_1_of_6"><br></div>
				<div class="col span_1_of_2">
					<input type="submit" value="Reset Pass" />
				</div>
			</div>
		</form>
		<div class="clear"></div>
	<?php

}

