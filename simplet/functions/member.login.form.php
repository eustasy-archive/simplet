<?php

function Member_Login_Form() {
	?>
				<form class="col span_1_of_1" action="" method="post">
					<h2>Log In</h2>
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
							<?php if ($Sitewide_Signups) echo '
							<p>No account? <a class="floatright" href="?register">Register</a></p>'; ?>
							<p>Forgot password? <a class="floatright" href="?reset">Reset</a></p>
						</div>
						<div class="col span_1_of_6"><br></div>
						<div class="col span_1_of_2"><input type="submit" value="Log In" /></div>
					</div>
				</form>
				<div class="clear"></div>
	<?php
}