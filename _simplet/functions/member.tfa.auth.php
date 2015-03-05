<?php

function Member_TFA_Auth() {
	global $Backend, $Database, $Member, $Time, $TFA, $Sitewide_Security_Authenticatron_Variance;

	$Runonce_CSRF_Check = Runonce_CSRF_Check($_POST['csrf_protection']);
	$TFA['Code'] = Input_Prepare($_POST['code']);
	$TFA['Code Length'] = strlen($TFA['Code']);

	if (
		!$Runonce_CSRF_Check ||
		!is_numeric($TFA['Code']) ||
		$TFA['Code Length'] != 6
	) {
		if ( !$Runonce_CSRF_Check ) {
			$Return['Error'][] = 'CSRF Protection token failed checks.';
		}
		if ( !is_numeric($TFA['Code']) ) {
			$Return['Error'][] = 'The code contains non-numerical characters. It should be 6 numerical digits..';
		}
		if ( $TFA['Code Length'] != 6 ) {
			$Return['Error'][] = 'The code you entered was not 6 digits long. The code should be 6 numerical digits.';
		}
	} else if ( !Authenticatron_Check($TFA['Code'], $TFA['Secret'], $Sitewide_Security_Authenticatron_Variance) ) {
		$Return['Error'][] = 'Sorry, that\'s not the right code.';
	} else {
		$Return['Auth'] = true;
	}
	return $Return;
}