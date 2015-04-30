<?php

////	Account Change Name Form Function
//
// Form for changing an accounts Name.
//
////	TODO
// No "Cancel" Button

function Member_Change_Name_Form() {

	global $Error, $Member;

	?>
		<form class="col span_1_of_1" action="" method="post">
			<h2>Change your Name</h2>
			<?php
				if ( !empty($Error) ) {
					echo $Error;
				}
			?>
			<div class="section group">
				<div class="col span_1_of_3"><label for="name"><h3>Name</h3></label></div>
				<div class="col span_1_of_6"><br><?php echo Runonce_CSRF_Form(); ?></div>
				<div class="col span_1_of_2">
					<?php echo '<input type="text" name="name" placeholder="',$Member['Name'],'" value="',$Member['Name'],'" required />'; ?>
				</div>
			</div>
			<div class="section group">
				<div class="col span_1_of_3"><br></div>
				<div class="col span_1_of_6"><br></div>
				<div class="col span_1_of_2"><input type="submit" value="Change Name" /></div>
			</div>
		</form>
		<div class="clear"></div>
	<?php

}