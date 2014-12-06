<?php

function Account_Sessions() {

	global $Database, $Member_ID, $Session, $Sitewide_Debug, $Time, $User_Cookie;

	if (isset($_POST['cookie'])) {
		if ( Runonce_CSRF_Check($_POST['csrf_protection']) ) {
			$Post_Cookie = Input_Prepare($_POST['cookie']);
			$Session_End = 'UPDATE `'.$Database['Prefix'].'Sessions` SET `Active`=\'0\', `Modified`=\''.$Time.'\' WHERE `Member_ID`=\''.$Member_ID.'\' AND `Cookie`=\''.$Post_Cookie.'\'';
			$Session_End = mysqli_query($Database['Connection'], $Session_End, MYSQLI_STORE_RESULT);
			if ( !$Session_End ) {
				if ( $Sitewide_Debug ) echo 'Invalid Query (Session_End): '.mysqli_error($Database['Connection']);
				// TODO Error
			} else {
				echo '<h3>Session Terminated</h3>';
			}
		} else {
			echo '<h3>Session could not be Terminated</h3>';
			echo '<h4>Your security token did not match. Please try again.</h4>';
		}

	}

	$Sessions = 'SELECT * FROM `'.$Database['Prefix'].'Sessions` WHERE `Member_ID`=\''.$Member_ID.'\' AND `Active`=\'1\' AND NOT `Cookie`=\''.$User_Cookie.'\'';
	$Sessions = mysqli_query($Database['Connection'], $Sessions, MYSQLI_STORE_RESULT);
	if ( !$Sessions ) {
		if ( $Sitewide_Debug ) echo 'Invalid Query ($Sessions): '.mysqli_error($Database['Connection']);
		// TODO Handle Error
	} else {
		$Sessions_Count = mysqli_num_rows($Sessions);
		if ($Sessions_Count == 0) {
			echo '<h3>No other active sessions.</h3>';
		} else {
			while ($Sessions_Fetch = mysqli_fetch_assoc($Sessions)) {
				echo '<p class="floatleft">Login';
				if ( !empty($Sessions_Fetch['IP']) ) {
					echo ' from ';
					if ( function_exists('geoip_country_name_by_name') ) {
						echo geoip_country_name_by_name($Sessions_Fetch['IP']);
					} else {
						echo $Sessions_Fetch['IP'];
					}
				}
				echo ' at '.date('G:i, jS F Y', $Sessions_Fetch['Created']).'</p>';
				echo '
				<form class="likelink floatright" method="POST" action="">
					'.Runonce_CSRF_Form().'
					<input type="hidden" name="cookie" value="'.$Sessions_Fetch['Cookie'].'" />
					<input type="submit" value="Terminate">
				</form>
				<style>
				.likelink {
					display: inline-block !important;
				}
				.likelink input {
					background: transparent !important;
					border: none !important;
					color: #3a8ee6 !important;
					width: 100% !important;
				}
				</style>';
			}
		}
	}
}