<?php

// We will need the IP to handle logins, regardless of Cookie Status. Catch it every time.
$User_IP = Input_Prepare($_SERVER['REMOTE_ADDR']);

// TODO Move to Functions folder and require.
function Member_Auth_False($ClearCookie = false) {
	global $Cookie_Session, $Member_Admin, $Member_Auth, $Member_ID, $Member_Name;
	if ( $ClearCookie ) {
		setcookie ($Cookie_Session, '', 1, '/', $Request['host'], $Request['Secure']);
		setcookie ($Cookie_Session, false, 1, '/', $Request['host'], $Request['Secure']);
		unset($_COOKIE[$Cookie_Session]);
	}
	$Member_Auth = false;
	$Member_ID = false;
	$Member_Name = false;
	$Member_Admin = false;
	return true;
}

if ( // If it is possible for them to be logged in.
	isset($_COOKIE[$Cookie_Session]) &&
	$Database['Connection'] &&
	$Database['Exists']['Members'] &&
	$Database['Exists']['Sessions']
) {

	// Make a note of their Cookie
	$User_Cookie = Input_Prepare($_COOKIE[$Cookie_Session]);

	// Check if the Cookie and IP have an active session in the database
	// Database Existence has already been checked.
	$Session_Check = mysqli_query($Database['Connection'], 'SELECT * FROM `'.$Database['Prefix'].'Sessions` WHERE `Cookie`=\''.$User_Cookie.'\' AND `Active`=\'1\' LIMIT 0, 1', MYSQLI_STORE_RESULT);
	if ( !$Session_Check ) {
		if ( $Sitewide_Debug ) echo 'Invalid Query (Session_Check): ' . mysqli_error($Database['Connection']);
		$Session_Count = 0;
	} else $Session_Count = mysqli_num_rows($Session_Check);

	// That Cookie doesn't exist or isn't active.
	if ($Session_Count === 0) Member_Auth_False(true);

	// Or maybe you are
	else {

		$Session_Fetch = mysqli_fetch_assoc($Session_Check);
		$Session_IP = $Session_Fetch['IP'];

		// IP Check
		// TODO This block needs better code commenting.
		if ( $IP_Checking ) {
			// WARNING: Potential Security Issue
			// Anyone without an IP skips IP checking.
			if( empty($Session_IP) ) $IP_Check = true;
			else if ( $IP_Checking === 'Partial' ) {
				if ( strpos($Session_IP, ':') === false && strpos($User_IP, ':') === false ) {
					$Session_IP_Pieces = explode('.', $Session_IP);
					$User_IP_Pieces = explode('.', $User_IP);
				} else {
					$Session_IP_Pieces = explode(':', $Session_IP);
					$User_IP_Pieces = explode(':', $User_IP);
				}
				if ( $User_IP_Pieces[0] == $Session_IP_Pieces[0] && $User_IP_Pieces[1] == $Session_IP_Pieces[1] ) $IP_Check = true;
				else $IP_Check = false;
			} else {
				if ( $User_IP === $Session_IP ) $IP_Check = true;
				else $IP_Check = false;
			}
		} else $IP_Check = true;

		// If the user passed the IP Check.
		if ($IP_Check) {

			$Member_ID = $Session_Fetch['Member_ID'];

			// Check their membership status
			$Member_Check = mysqli_query($Database['Connection'], 'SELECT * FROM `'.$Database['Prefix'].'Members` WHERE ID=\''.$Member_ID.'\' AND `Status`=\'Active\' LIMIT 0, 1', MYSQLI_STORE_RESULT);
			if ( !$Member_Check ) {
				if ( $Sitewide_Debug ) echo 'Invalid Query (Member_Check): ' . mysqli_error($Database['Connection']);
				$Member_Count = 0;
			} else $Member_Count = mysqli_num_rows($Member_Check);

			// If they're not a member, that Session can be ended.
			if ( $Member_Count === 0 ) {
				$Session_End = mysqli_query($Database['Connection'], 'UPDATE `'.$Database['Prefix'].'Sessions` SET `Active`=\'0\' WHERE `Member_ID`=\''.$Member_ID.'\' AND `Cookie`=\''.$User_Cookie.'\'', MYSQLI_STORE_RESULT);
				if ( !$Session_End && $Sitewide_Debug ) echo 'Invalid Query (Session_End): ' . mysqli_error($Database['Connection']);

			// They are authenticated as a valid member.
			} else {
				$Member_Fetch = mysqli_fetch_assoc($Member_Check);
				$Member_Auth = true; // Truly
				$Member_Name = $Member_Fetch['Name']; // Do they have a name?
				$Member_Mail = $Member_Fetch['Mail'];
				$Member_Admin = $Member_Fetch['Admin']; // Are they a VIP?
			}

		// IP Check Failed
		} else Member_Auth_False(true);

	}

// They don't have a Cookie or the database is not suitably set up.
} else Member_Auth_False();