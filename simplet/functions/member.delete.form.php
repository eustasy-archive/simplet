<?php

////	Account Delete Form Function
//
// Form for deleting an account.

function Member_Delete_Form() {

	global $Error, $Key, $Success;

	if ( !empty($Success) ) {
		echo $Success;
	} else {
		echo '
		<form class="col span_1_of_1" action="" method="post">
			<h2>Are you sure you want to delete your account?</h2>';
				if ( !empty($Error) ) {
					echo $Error;
				}
			?>
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
							<?php echo Runonce_CSRF_Form(); ?>
							<input type="hidden" name="delete_key" value="<?php echo $Key['Key']; ?>" />
							<input type="submit" class="button red textcenter" value="Yes, delete." />
						</div>
					</div>
				</div>
				<div class="col span_1_of_8"><br></div>
			</div>
		</form>
		<div class="clear"></div>
		<?php
	}

}