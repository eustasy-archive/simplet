<?php

if (
	$Database['Connection'] &&
	$Database['Exists']['Runonce']
) {

	// If there is no CSRF Protection cookie
	if ( !isset($_COOKIE[$Cookie_Prefix.'_csrf_protection']) ) {
		$User_CSRF = RunOnce_CSRF_Create();
		$User_CSRF['Cookie'] = $User_CSRF['Key'];

	// If there is an existing CSRF Protection cookie
	} else {

		$User_CSRF['Cookie'] = Input_Prepare($_COOKIE[$Cookie_Prefix.'_csrf_protection']);
		$User_CSRF = Runonce_CSRF_Check($User_CSRF['Cookie']);

		if ( $User_CSRF['Check'] ) {
			$User_CSRF['Cookie'] = Input_Prepare($_COOKIE[$Cookie_Prefix.'_csrf_protection']);

		} else {
			$User_CSRF = RunOnce_CSRF_Create();
			$User_CSRF['Cookie'] = $User_CSRF['Key'];
		}

	}

} else {
	$User_CSRF = false;
}