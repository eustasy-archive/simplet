<?php

////	Account Sessions Function
//
// Lists Sessions and terminates them if requested.
//
////	TODO
// It is possible to terminate the current session,
// even though there is not link to it.

function Account_Sessions() {

	global $Database, $Member_ID, $Session, $Sitewide_Debug, $Time, $User_Cookie;

	// IF Delete Cookie
	if (isset($_POST['cookie'])) {
		// IF CSRF Okay
		if ( Runonce_CSRF_Check($_POST['csrf_protection']) ) {
			// Sanitize Cookie
			$Post_Cookie = Input_Prepare($_POST['cookie']);
			// Construct Query
			$Session_End = 'UPDATE `'.$Database['Prefix'].'Sessions`';
			$Session_End .= ' SET `Active`=\'0\', `Modified`=\''.$Time.'\'';
			$Session_End .= ' WHERE `Member_ID`=\''.$Member_ID.'\' AND `Cookie`=\''.$Post_Cookie.'\'';
			// Execute Query
			$Session_End = mysqli_query($Database['Connection'], $Session_End, MYSQLI_STORE_RESULT);
			// IF Session Ended
			if ( !$Session_End ) {
				if ( $Sitewide_Debug ) echo 'Invalid Query (Session_End): '.mysqli_error($Database['Connection']);
				echo '<h3 class="error-title">Session could not be Terminated</h3>';
			// END IF Session Ended
			// IF Session Not Ended
			} else {
				echo '<h3 class="success-title">Session Terminated</h3>';
			} // END IF Session Not Ended
		// END IF CSRF Okay
		// IF CSRF Not Okay
		} else {
			echo '<h3 class="error-title">Session could not be Terminated</h3>';
			echo '<h4 class="error-description">Your security token did not match. Please try again.</h4>';
		} // END IF CSRF Not Okay
	} // END IF Delete Cookie

	$Sessions = 'SELECT * FROM `'.$Database['Prefix'].'Sessions` WHERE `Member_ID`=\''.$Member_ID.'\' AND `Active`=\'1\' AND NOT `Cookie`=\''.$User_Cookie.'\'';
	$Sessions = mysqli_query($Database['Connection'], $Sessions, MYSQLI_STORE_RESULT);
	// IF Sessions not available
	if ( !$Sessions ) {
		if ( $Sitewide_Debug ) echo 'Invalid Query ($Sessions): '.mysqli_error($Database['Connection']);
		echo '<h3 class="error-title">Sessions could not be listed</h3>';
	// END IF Sessions not available
	// IF Sessions available
	} else {
		$Sessions_Count = mysqli_num_rows($Sessions);
		// IF Zero Sessions
		if ($Sessions_Count == 0) {
			echo '<h3>No other active sessions.</h3>';
		// END IF Zero Sessions
		// IF Some Sessions
		} else {
			while ($Sessions_Fetch = mysqli_fetch_assoc($Sessions)) {
				echo '<p class="floatleft">Login';
				if ( !empty($Sessions_Fetch['IP']) ) {
					echo ' from ';
					if (
						function_exists('geoip_country_name_by_name') &&
						geoip_country_name_by_name($Sessions_Fetch['IP'])
					) {
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
		} // END IF Some Sessions
	} // END IF Sessions available

}