<?php

// We will need the IP to handle logins. Catch it every time.
$User_IP = htmlentities($_SERVER['REMOTE_ADDR'], ENT_QUOTES, 'UTF-8');

if ($MySQL_Connection && isset($_COOKIE[$Cookie_Session])) { // If they might be logged in

	// Make a note of their Cookie
	$User_Cookie = htmlentities($_COOKIE[$Cookie_Session], ENT_QUOTES, 'UTF-8');

	// Check if the Cookie and IP have an active session in the database
	$Session_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Sessions` WHERE `Cookie`='$User_Cookie' AND `Active`='1' LIMIT 0, 1", MYSQLI_STORE_RESULT);

	if (!$Session_Check) exit('Invalid Query (Session_Check): ' . mysqli_error($MySQL_Connection));

	$Session_Count = mysqli_num_rows($Session_Check);

	if ($Session_Count === 0) { // That Cookie doesn't exist or isn't active.

		setcookie($Cookie_Session, '', 1); // Clear the Cookie
		setcookie($Cookie_Session, false); // Definitely
		unset($_COOKIE[$Cookie_Session]); // Absolutely

		$Member_Auth = false; // You shall not pass.
		$Member_ID = false;
		$Member_Name = false;
		$Member_Admin = false;

	} else { // Or maybe you are

		$Session_Fetch = mysqli_fetch_assoc($Session_Check);
		$Session_IP = $Session_Fetch['IP'];

		if ($IP_Checking) {
			if(empty($Session_IP)) $IP_Check = true;
			else if ($IP_Checking === 'Partial') {
				if (strpos($Session_IP, ':') === false && strpos($User_IP, ':') === false ) {
					$Session_IP_Pieces = explode('.', $Session_IP);
					$User_IP_Pieces = explode('.', $User_IP);
				} else {
					$Session_IP_Pieces = explode(':', $Session_IP);
					$User_IP_Pieces = explode(':', $User_IP);
				}
				if ($User_IP_Pieces[0] == $Session_IP_Pieces[0] && $User_IP_Pieces[1] == $Session_IP_Pieces[1]) $IP_Check = true;
				else $IP_Check = false;
			} else {
				if ($User_IP === $Session_IP) $IP_Check = true;
				else $IP_Check = false;
			}
		} else $IP_Check = true;

		if ($IP_Check) {

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

			setcookie($Cookie_Session, '', 1);
			setcookie($Cookie_Session, false);
			unset($_COOKIE[$Cookie_Session]);

			$Member_Auth = false;
			$Member_ID = false;
			$Member_Name = false;
			$Member_Admin = false;

		}

	}

} else { // Even they don't think they're logged in

	$User_Cookie = false;
	$Member_Auth = false;
	$Member_ID = false;
	$Member_Name = false;
	$Member_Admin = false;

}