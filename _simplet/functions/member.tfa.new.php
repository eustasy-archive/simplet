<?php

function Member_TFA_New() {
	global $Backend, $Database, $Error, $Member, $Time, $TFA, $Sitewide_Security_Authenticatron_Variance, $Success;

	$Runonce_CSRF_Check = Runonce_CSRF_Check($_POST['csrf_protection']);
	$TFA['Secret'] = Input_Prepare($_POST['secret']);
	$TFA['Secret Length'] = strlen($TFA['Secret']);
	$TFA['Code'] = Input_Prepare($_POST['code']);
	$TFA['Code Length'] = strlen($TFA['Code']);

	if (
		!$Runonce_CSRF_Check ||
		$TFA['Secret Length'] != 16 ||
		!is_numeric($TFA['Code']) ||
		$TFA['Code Length'] != 6
	) {
		if ( !$Runonce_CSRF_Check ) {
			$Error[] = 'CSRF Protection token failed checks.';
		}
		if ( $TFA['Secret Length'] != 16 ) {
			$Error[] = 'The secret appears to be malformed.';
		}
		if ( !is_numeric($TFA['Code']) ) {
			$Error[] = 'The code contains non-numerical characters. It should be 6 numerical digits..';
		}
		if ( $TFA['Code Length'] != 6 ) {
			$Error[] = 'The code you entered was not 6 digits long. The code should be 6 numerical digits.';
		}
	} else if ( !Authenticatron_Check($TFA['Code'], $TFA['Secret'], $Sitewide_Security_Authenticatron_Variance) ) {
		$Error[] = 'Sorry, that\'s not the right code.';
	} else {
		$TFA_Update = 'UPDATE `'.$Database['Prefix'].'Members` SET `2fa`=\''.$TFA['Secret'].'\', `Modified`=\''.$Time['Now'].'\' WHERE `ID`=\''.$Member['ID'].'\' AND `Status`=\'Active\' LIMIT 1';
		$TFA_Update = mysqli_query($Database['Connection'], $TFA_Update, MYSQLI_STORE_RESULT);
		if ( !$TFA_Update ) {
			if ( $Backend['Debug'] ) {
				echo 'Invalid Query (TFA_Update): ' . mysqli_error($Database['Connection']);
			}
			$Error[] = 'Sorry, we were unable to save you code to the database.';
		} else {
			$Success = true;
		}
	}
}