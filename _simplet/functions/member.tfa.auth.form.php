<?php

// TODO Fallback: Add Optional E-Mail backup.

function Member_TFA_Auth_Form($Additional, $Cancel = true, $AllowEmail = false) {
	?>

	<div class="group">

		<?php
			if ( $Cancel ) {
				echo '
		<div class="col span_3_of_11">
			<p class="like-input text-center"><a class="color-pomegranate" href="?twofactorauth">Cancel</a></p>
		</div>
		<div class="col span_1_of_11"><br></div>';
			} else {
				echo '
		<div class="col span_2_of_11"><br></div>';
			}
		?>

		<form method="POST" action="">
			<div class="col span_3_of_11">
				<input type="tel" name="code" autocomplete="off" autofocus required>
			</div>
			<div class="col span_1_of_11"><br></div>
			<div class="col span_3_of_11">

			<?php
				if ( $Additional ) {
					echo '<input type="hidden" name="',$Additional,'" required>';
				}
			?>

				<input type="hidden" name="auth" required>

				<?php echo Runonce_CSRF_Form(); ?>

				<input type="submit" value="Confirm">
			</div>
		</form>
	</div>

	<?php

	if ( $AllowEmail ) {
		// TODO
		// Button to initiate a bypass.
		// Recreate the runonce and use it like a one time login key that is emailed to the user.
		// $Sitewide['Root'].$Sitewide['Account'].'?once='.$NewKey
		// Delete the original runonce.
		?>

		<div class="group">
			<form method="POST" action="">
				<div class="col span_3_of_11">

				<?php
					if ( $Additional ) {
						echo '<input type="hidden" name="',$Additional,'" required>';
					}
				?>

					<input type="hidden" name="auth" required>

					<?php echo Runonce_CSRF_Form(); ?>

					<input type="submit" value="Confirm">
				</div>
			</form>
		</div>

		<?php
	}
}