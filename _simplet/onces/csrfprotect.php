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
		$User['CSRF'] = Runonce_CSRF_Create();
		$User['CSRF']['Cookie'] = false;

	// If there is an existing CSRF Protection cookie
	} else {

		$User['CSRF']['Cookie'] = Input_Prepare($_COOKIE[$Cookie['CSRF']]);
		$User['CSRF']['Check'] = Runonce_CSRF_Check($User['CSRF']['Cookie'], true);

		if ( $User['CSRF']['Check'] ) {
			$User['CSRF']['Key'] = $User['CSRF']['Cookie'];

		} else {
			$User['CSRF'] = Runonce_CSRF_Create();
			$User['CSRF']['Cookie'] = false;
		}

	}

} else {
	$User['CSRF'] = false;
}