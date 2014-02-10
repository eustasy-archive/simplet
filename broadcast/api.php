<?php

	$TextTitle = 'API';
	$WebTitle = 'API';
	$Canonical = 'api';
	$PostType = 'Processor';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'api';

	require_once '../request.php';

	$Account = 'account';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == $Place['path'].$Canonical) {

	header('X-Frame-Options: SAMEORIGIN');

	$Time = time();

	// Helpfulness API
	//  false	= no vote
	//  1		= up voted
	//  0		= no vote
	// -1		= down voted
	//  error	= error

	// Fetch
	// /api?helpfulness&fetch&canonical=   URL_HERE   &id=   #

	// Set
	// /api?helpfulness&set&canonical=   URL_HERE   &id=   #   &vote=   #

	if (isset($_GET['helpfulness'])) {

		if (!$Member_Auth) {
			echo 'false';

		} else if (!isset($_GET['canonical']) || empty($_GET['canonical'])) {
			echo 'Error: No Canonical Defined.';

		} else if (!isset($_GET['id']) || empty($_GET['id'])) {
			echo 'Error: No Review ID Defined.';

		} else if (isset($_GET['fetch'])) {
			$Review_Canonical = htmlentities($_GET['canonical'], ENT_QUOTES, 'UTF-8');
			$Review_ID = htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8');
			// TODO Check Review is Viewable
			$Helpfulness = mysqli_query($MySQL_Connection, "SELECT * FROM `Helpfulness` WHERE `Review_Canonical`='$Review_Canonical' AND `Review_ID`='$Review_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
			if (!$Helpfulness) exit('error');
			$Helpfulness_Count = mysqli_num_rows($Helpfulness);
			if($Helpfulness_Count == 0) {
				// No Vote
				echo 'none';
			} else {
				// Echo Vote
				$Helpfulness_Fetch = mysqli_fetch_assoc($Helpfulness);
				$Helpfulness_Vote = $Helpfulness_Fetch['Helpfulness'];
				if ($Helpfulness_Vote === 'up') {
					echo 'up';
				} else if ($Helpfulness_Vote === 'down') {
					echo 'down';
				} else if ($Helpfulness_Vote === 'none') {
					echo 'none';
				} else {
					echo 'invalid';
				}
			}

		} else if (isset($_GET['set'])) {
			$Review_Canonical = htmlentities($_GET['canonical'], ENT_QUOTES, 'UTF-8');
			$Review_ID = htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8');
			if (isset($_POST['vote']) && !empty($_POST['vote'])) {
				$Review_Vote = strval(htmlentities($_POST['vote'], ENT_QUOTES, 'UTF-8'));
				if ($Review_Vote === 'up' || $Review_Vote === 'down'  || $Review_Vote === 'none') {
					// TODO Check Review is Viewable
					$Helpfulness_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Helpfulness` WHERE `Review_Canonical`='$Review_Canonical' AND `Review_ID`='$Review_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
					if (!$Helpfulness_Check) exit('error');
					$Helpfulness_Check_Count = mysqli_num_rows($Helpfulness_Check);
					if($Helpfulness_Check_Count == 0) {
						$Helpfulness_Insert = mysqli_query($MySQL_Connection, "INSERT INTO `Helpfulness` (`Review_Canonical`, `Review_ID`, `Member_ID`, `Helpfulness`, `Created`, `Modified`) VALUES ('$Review_Canonical', '$Review_ID', '$Member_ID', '$Review_Vote', '$Time', '$Time')", MYSQLI_STORE_RESULT);
						if (!$Helpfulness_Insert) exit('Error: Vote Failed.');
					} else {
						$Helpfulness_Update = mysqli_query($MySQL_Connection, "UPDATE `Helpfulness` SET `Helpfulness`='$Review_Vote', `Modified`='$Time' WHERE `Review_Canonical`='$Review_Canonical' AND `Review_ID`='$Review_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
						if (!$Helpfulness_Update) exit('Error: Update Failed.');
					}
					// TODO On Vote Helpfulness Change Helpfulness
					echo 'true';
				} else {
					// Invalid
					echo 'Error: Invalid Vote.';
				}
			} else {
				// No Vote Set
				echo 'Error: No Vote Set.';
			}

		} else {
			// Not using this properly.
			echo 'Error: No Valid "Helpful" API Action Defined.';
		}



	} else {
		// Error: Undefined
		echo 'Error: No Valid API Action Defined.';
	}
}
