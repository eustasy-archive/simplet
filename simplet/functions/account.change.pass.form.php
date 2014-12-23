<?php

////	Account Change Pass Form Function
//
// Form for changing an accounts Pass.
//
////	TODO
// No "Cancel" Button

function Member_Change_Pass_Form() {

	global $Error;

	?>
		<form class="col span_1_of_1" action="" method="post">
			<h2>Change your Pass</h2>
			<?php
				if ( !empty($Error) ) {
					echo $Error;
				}
			?>
			<div class="section group">
				<div class="col span_1_of_3"><label for="pass"><h3>Pass</h3></label></div>
				<div class="col span_1_of_6"><br><?php echo Runonce_CSRF_Form(); ?></div>
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

}