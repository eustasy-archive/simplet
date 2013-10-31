<?php

	$TextTitle = 'Logout';
	$WebTitle = 'Logout &nbsp;&middot;&nbsp; Account';
	$Canonical = 'account/logout';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'logout account';

	require '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	if (!$Member_Auth) { // Are you logged out already?
		$Error = 'We can\'t log you out, you weren\'t logged in.';

	} else {

		$Session_End = mysqli_query($MySQL_Connection, "UPDATE `Sessions` SET `Active`='0' WHERE `Member_ID`='$Member_ID' AND `Cookie`='$User_Cookie' AND `IP`='$User_IP'", MYSQLI_STORE_RESULT);
		if (!$Session_End) exit('Invalid Query (Session_End): ' . mysqli_error($MySQL_Connection));

		setcookie ('l', '', time() - 3600, '/', 'eustasy.org', 1); // Clear the Cookie
		setcookie ('l', false, time() - 3600, '/', 'eustasy.org', 1); // Definitely
		unset($_COOKIE['l']); // Absolutely

		$Member_Auth = false; // Not at all
		$Member_ID = false; // You're not even human

	}

	require '../../header.php';

	if (isset($Error)) {

		echo '<h2>Logout Error</h2>';
		echo '<h3>' . $Error . '</h3>';

	} else {

		echo '<h2>See you soon ' . $Member_Name . '</h2>';

	}

	require '../../footer.php';

} ?>
