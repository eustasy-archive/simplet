<?php

if (
	$Database['Connection'] &&
	$Database['Exists']['Runonce']
) {

	require_once $Backend['functions'].'input.prepare.php';
	require_once $Backend['functions'].'runonce.csrf.create.php';
	require_once $Backend['functions'].'runonce.csrf.check.php';

	// If there is no CSRF Protection cookie
	if ( !isset($_COOKIE[$Cookie['CSRF']]) ) {
		$User_CSRF = Runonce_CSRF_Create();
		$User_CSRF['Cookie'] = false;

	// If there is an existing CSRF Protection cookie
	} else {

		$User_CSRF['Cookie'] = Input_Prepare($_COOKIE[$Cookie['CSRF']]);
		$User_CSRF['Check'] = Runonce_CSRF_Check($User_CSRF['Cookie'], true);

		if ( $User_CSRF['Check'] ) {
			$User_CSRF['Key'] = $User_CSRF['Cookie'];

		} else {
			$User_CSRF = Runonce_CSRF_Create();
			$User_CSRF['Cookie'] = false;
		}

	}

} else {
	$User_CSRF = false;
}