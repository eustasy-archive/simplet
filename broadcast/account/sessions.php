<?php

	$TextTitle = 'Sessions';
	$WebTitle = 'Sessions &nbsp;&middot;&nbsp; Account';
	$Canonical = 'account/sessions';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'sessions account';

	require '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	if (!$Member_Auth) { // Are you logged in already?

		header('Location: /account/login', TRUE, 302);
		die();

	} else {

		require '../../header.php';

		echo '<h2>Sessions</h2>';

		if(isset($_GET['cookie'])) {

			$Get_Cookie = htmlspecialchars($_GET['cookie'], ENT_QUOTES, 'UTF-8');

			$Session_End = mysqli_query($MySQL_Connection, "UPDATE `Sessions` SET `Active`='0' WHERE `Member_ID`='$Member_ID' AND `Cookie`='$Get_Cookie'", MYSQLI_STORE_RESULT);
			if (!$Session_End) exit('Invalid Query (Session_End): ' . mysqli_error($MySQL_Connection));

			echo '<h3>Session Terminated</h3>';

		}

		$Sessions = mysqli_query($Connection_Read, "SELECT * FROM `Sessions` WHERE `Member_ID`='$Member_ID' AND `Active`='1' AND NOT `Cookie`='$User_Cookie'", MYSQLI_STORE_RESULT);
		if (!$Sessions) exit('Invalid Query (Sessions): ' . mysqli_error($Connection_Read));

		$Sessions_Count = mysqli_num_rows($Sessions);
		if ($Sessions_Count == 0) {
			echo '<h3>No other active sessions.</h3>';
		} else {
			while ($Sessions_Fetch = mysqli_fetch_assoc($Sessions)) {
			 	echo '<p>Login';
			 	if(isset($Sessions_Fetch['IP']) && !empty($Sessions_Fetch['IP'])) {
			 		echo ' from ' . geoip_country_name_by_name($Sessions_Fetch['IP']);
			 	}
			 	echo ' at ' . $Sessions_Fetch['Created'] . ' <a class="floatright" href="?cookie=' . $Sessions_Fetch['Cookie'] . '">Terminate</a></p>';
			}
		}

		require '../../footer.php';

	}

} ?>
