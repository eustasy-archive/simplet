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
	$Header = '../header.php';
	$Footer = '../footer.php';
	$Lib_Browning_Config = '../libs/Browning_Config.php';
	$Lib_Browning_Send = '../libs/Browning_Send.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == $Place['path'].$Canonical) {

	$Time = time();



	// Helpfulness API
	//  false	= no vote
	//  1		= up voted
	// -1		= down voted

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
			$Helpfulness = mysqli_query($MySQL_Connection, "SELECT * FROM `Helpfulness` WHERE `Review_Canonical`='$Review_Canonical' AND `Review_ID`='$Review_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
			if (!$Helpfulness) exit('Invalid Query (Helpfulness): '.mysqli_error($MySQL_Connection));
			$Helpfulness_Count = mysqli_num_rows($Helpfulness);
			if($Helpfulness_Count == 0) {
				// No Vote
				echo 'false';
			} else {
				// Echo Vote
				$Helpfulness_Fetch = mysqli_fetch_assoc($Helpfulness);
				$Helpfulness_Vote = $Helpfulness_Fetch['Helpfulness'];
				echo $Helpfulness_Vote;
			}

		} else if (isset($_GET['set'])) {
			$Review_Canonical = htmlentities($_GET['canonical'], ENT_QUOTES, 'UTF-8');
			$Review_ID = htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8');
			$Helpfulness = mysqli_query($MySQL_Connection, "SELECT * FROM `Helpfulness` WHERE `Review_Canonical`='$Review_Canonical' AND `Review_ID`='$Review_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
			if (!$Helpfulness) exit('Invalid Query (Helpfulness): '.mysqli_error($MySQL_Connection));
			$Helpfulness_Count = mysqli_num_rows($Helpfulness);
			if($Helpfulness_Count == 0) {
				// No Vote
				echo 'false';
			} else {
				// Echo Vote
				$Helpfulness_Fetch = mysqli_fetch_assoc($Helpfulness);
				$Helpfulness_Vote = $Helpfulness_Fetch['Helpfulness'];
				echo $Helpfulness_Vote;
			}

		} else {
			// Something went wrong
			// Let's pretend it didn't
			echo 'false';
		}



	} else {
		// Error: Undefined
		echo 'Error: No Valid Action Defined.';
	}
}
