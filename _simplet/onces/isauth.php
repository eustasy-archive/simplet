<?php

require_once $Backend['functions'].'member.auth.false.php';

if ( // If it is possible for them to be logged in.
	isset($_COOKIE[$Cookie['Session']]) &&
	$Database['Connection'] &&
	$Database['Exists']['Members'] &&
	$Database['Exists']['Sessions']
) {

	require_once $Backend['functions'].'input.prepare.php';

	// Make a note of their Cookie
	$Member['Cookie'] = Input_Prepare($_COOKIE[$Cookie['Session']]);

	// Check if the Cookie and IP have an active session in the database
	// Database Existence has already been checked.
	$Session_Check = mysqli_query($Database['Connection'], 'SELECT * FROM `'.$Database['Prefix'].'Sessions` WHERE `Cookie`=\''.$Member['Cookie'].'\' AND `Active`=\'1\' LIMIT 0, 1', MYSQLI_STORE_RESULT);
	if ( !$Session_Check ) {
		if ( $Backend['Debug'] ) {
			echo 'Invalid Query (Session_Check): ' , mysqli_error($Database['Connection']);
		}
		$Session_Count = 0;
	} else {
		$Session_Count = mysqli_num_rows($Session_Check);
	}

	// That Cookie doesn't exist or isn't active.
	if ($Session_Count === 0) {
		Member_Auth_False(true);
	} else {

		$Session_Fetch = mysqli_fetch_assoc($Session_Check);
		$Session_IP = $Session_Fetch['IP'];

		// IP Check
		// TODO This block needs better code commenting.
		if ( $IP_Checking ) {
			// WARNING: Potential Security Issue
			// Anyone without an IP skips IP checking.
			if ( empty($Session_IP) ) {
				$IP_Check = true;
			} else if ( $IP_Checking === 'Partial' ) {
				if (
					strpos($Session_IP, ':') === false &&
					strpos($User['IP'], ':') === false
				) {
					$Session_IP_Pieces = explode('.', $Session_IP);
					$User['IP Pieces'] = explode('.', $User['IP']);
				} else {
					$Session_IP_Pieces = explode(':', $Session_IP);
					$User['IP Pieces'] = explode(':', $User['IP']);
				}
				if (
					$User['IP Pieces'][0] == $Session_IP_Pieces[0] &&
					$User['IP Pieces'][1] == $Session_IP_Pieces[1]
				) {
					$IP_Check = true;
				} else {
					$IP_Check = false;
				}
			} else {
				if ( $User['IP'] === $Session_IP ) {
					$IP_Check = true;
				} else {
					$IP_Check = false;
				}
			}
		} else {
			$IP_Check = true;
		}

		// If the user passed the IP Check.
		if ( $IP_Check ) {

			$Member['ID'] = $Session_Fetch['Member_ID'];

			// Check their membership status
			$Member_Check = mysqli_query($Database['Connection'], 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE ID=\''.$Member['ID'].'\' AND `Status`=\'Active\' LIMIT 0, 1', MYSQLI_STORE_RESULT);
			if ( !$Member_Check ) {
				if ( $Backend['Debug'] ) {
					echo 'Invalid Query (Member_Check): ' , mysqli_error($Database['Connection']);
				}
				$Member_Count = 0;
			} else {
				$Member_Count = mysqli_num_rows($Member_Check);
			}

			// If they're not a member, that Session can be ended.
			if ( $Member_Count === 0 ) {
				$Session_End = mysqli_query($Database['Connection'], 'UPDATE `'.$Database['Prefix'].'Sessions` SET `Active`=\'0\' WHERE `Member_ID`=\''.$Member['ID'].'\' AND `Cookie`=\''.$Member['Cookie'].'\'', MYSQLI_STORE_RESULT);
				if ( !$Session_End ) {
					if ( $Backend['Debug'] ) {
						echo 'Invalid Query (Session_End): ' , mysqli_error($Database['Connection']);
					}
				}

			// They are authenticated as a valid member.
			} else {
				$Member_Fetch = mysqli_fetch_assoc($Member_Check);
				$Member['Auth'] = true; // Truly
				$Member['Name'] = $Member_Fetch['Name'];
				$Member['Mail'] = $Member_Fetch['Mail'];
				$Member['Admin'] = $Member_Fetch['Admin'];
				$Member['Groups'] = $Member_Fetch['Groups'];
			}

		// IP Check Failed
		} else {
			Member_Auth_False(true);
		}

	}

// They don't have a Cookie or the database is not suitably set up.
} else {
	Member_Auth_False();
}