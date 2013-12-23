<?php

// Set a default timezone.
date_default_timezone_set('UTC');
// Note: GMT is deprecated. Use UTC instead.

// We will need the IP to handle logins. Catch it every time.
$User_IP = htmlentities($_SERVER['REMOTE_ADDR'], ENT_QUOTES, 'UTF-8');

if (isset($_COOKIE['l'])) { // If they might be logged in

	// Make a note of their Cookie
	$User_Cookie = htmlentities($_COOKIE['l'], ENT_QUOTES, 'UTF-8');

	// Check if the Cookie and IP have an active session in the database
	$Session_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Sessions` WHERE `Cookie`='$User_Cookie' AND `Active`='1' LIMIT 0, 1", MYSQLI_STORE_RESULT);

	if (!$Session_Check) exit('Invalid Query (Session_Check): ' . mysqli_error($MySQL_Connection));

	$Session_Count = mysqli_num_rows($Session_Check);

	if ($Session_Count === 0) { // That Cookie doesn't exist or isn't active.

		setcookie('l', '', 1); // Clear the Cookie
		setcookie('l', false); // Definitely
		unset($_COOKIE['l']); // Absolutely

		$Member_Auth = false; // You shall not pass.
		$Member_ID = false;
		$Member_Name = false;
		$Member_Admin = false;

	} else { // Or maybe you are

		$Session_Fetch = mysqli_fetch_assoc($Session_Check);
		$Session_IP = $Session_Fetch['IP'];

		if(empty($Session_IP) || $User_IP == $Session_IP) {

			$Member_ID = $Session_Fetch['Member_ID'];

			// Check their membership status
			$Member_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Members` WHERE ID='$Member_ID' AND `Status`='Active' LIMIT 0, 1", MYSQLI_STORE_RESULT);
			if (!$Member_Check) exit('Invalid Query (Member_Check): ' . mysqli_error($MySQL_Connection));

			$Member_Count = mysqli_num_rows($Member_Check);
			if ($Member_Count === 0) {

				$Session_End = mysqli_query($MySQL_Connection, "UPDATE `Sessions` SET `Active`='0' WHERE `Member_ID`='$Member_ID' AND `Cookie`='$User_IP' AND IP='$User_IP'", MYSQLI_STORE_RESULT);
				if (!$Session_End) exit('Invalid Query (Session_End): ' . mysqli_error($MySQL_Connection));

			} else {

				$Member_Fetch = mysqli_fetch_assoc($Member_Check);

				$Member_Auth = true; // Truthful

				$Member_Name = $Member_Fetch['Name']; // Do they have a name?
				$Member_Mail = $Member_Fetch['Mail']; // No-one uses numbers anymore

				$Member_Admin = $Member_Fetch['Admin']; // Are they a VIP?

			}

		} else { // Not even close

			setcookie('l', '', 1);
			setcookie('l', false);
			unset($_COOKIE['l']);

			$Member_Auth = false;
			$Member_ID = false;
			$Member_Name = false;
			$Member_Admin = false;

		}

	}

} else { // Even they don't think they're logged in

	$Member_Auth = false;
	$Member_ID = false;
	$Member_Name = false;
	$Member_Admin = false;

}

function stringGenerator($n=64) {
	$String_Characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$String_Characters_Count = strlen( $String_Characters );
	$String = '';
	for( $i = 0; $i < $n; $i++ ) {
		$String .= $String_Characters[ rand( 0, $String_Characters_Count - 1 ) ];
	}
	return $String;
}

function passHash($Pass, $Salt) {
	$Hash_Method = 'sha512'; // Could also use sha1, sha512 etc, etc
	return hash($Hash_Method, hash($Hash_Method, $Pass) . hash($Hash_Method, $Salt));
}
