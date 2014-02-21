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
	// api?helpfulness&fetch&id=   #

	// Set
	// api?helpfulness&set&id=   #
	// post { vote: # }

	if (isset($_GET['helpfulness'])) {

		// TODO Move Responses to JSON

		$Helpfulness_Return = array();
		$Helpfulness_Return['error'] = array();

		if (!$Member_Auth) {
			echo 'false';

		} else if (!isset($_GET['id']) || empty($_GET['id'])) {
			array_push($Helpfulness_Return['error'], 'No Response ID Defined.');

		} else if ( isset($_GET['fetch']) || ( isset($_GET['set']) && isset($_POST['vote']) && !empty($_POST['vote']) ) ) {

			$Response_ID = strval(htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8'));

			// Check Response is Viewable
			$Responses = mysqli_query($MySQL_Connection, 'SELECT * FROM `Responses` WHERE `ID`=\''.$Response_ID.'\' AND (`Status`=\'Public\' OR `Status`=\'Private\')', MYSQLI_STORE_RESULT);
			if (!$Responses) array_push($Helpfulness_Return['error'], 'Responses Query Error.');
			$Responses_Count = mysqli_num_rows($Responses);

			if($Responses_Count === 0) {
				array_push($Helpfulness_Return['error'], 'Response not found.');

			} else {

				// Fetch Current Helpfulness Value from Response
				$Responses_Fetch = mysqli_fetch_assoc($Responses);
				$Responses_Helpfulness = $Responses_Fetch['Helpfulness'];

				$Helpfulness = mysqli_query($MySQL_Connection, "SELECT * FROM `Helpfulness` WHERE `Response_ID`='$Response_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
				if (!$Helpfulness) array_push($Helpfulness_Return['error'], 'Helpfulness Query Error.');
				$Helpfulness_Count = mysqli_num_rows($Helpfulness);
				if($Helpfulness_Count == 0) {

					if (isset($_GET['fetch'])) {
						// No Vote
						echo 'none';
					} else if (isset($_GET['set'])) {
						$Response_Vote = strval(htmlentities($_POST['vote'], ENT_QUOTES, 'UTF-8'));
						if ($Response_Vote === 'up' || $Response_Vote === 'down'  || $Response_Vote === 'none') {
							$Helpfulness_Insert = mysqli_query($MySQL_Connection, "INSERT INTO `Helpfulness` (`Response_ID`, `Member_ID`, `Helpfulness`, `Created`, `Modified`) VALUES ('$Response_ID', '$Member_ID', '$Response_Vote', '$Time', '$Time')", MYSQLI_STORE_RESULT);
							if (!$Helpfulness_Insert) array_push($Helpfulness_Return['error'], 'Vote Insert Failed.');
							if ($Response_Vote == 'up') {
								$Helpfulness_Change = 1;
							} else if ($Response_Vote == 'down') {
								$Helpfulness_Change = -1;
							} else if ($Response_Vote == 'none') {
								$Helpfulness_Change = 0;
							} else {
								array_push($Helpfulness_Return['error'], 'Unknown Helpfulness Change.');
							}
						} else {
							// Invalid
							array_push($Helpfulness_Return['error'], 'Invalid Vote Sent.');
						}
					}

				} else {
					$Helpfulness_Fetch = mysqli_fetch_assoc($Helpfulness);
					$Helpfulness_Vote = $Helpfulness_Fetch['Helpfulness'];

					if (isset($_GET['fetch'])) {
						// Echo Vote
						if ($Helpfulness_Vote === 'up') {
							echo 'up';
						} else if ($Helpfulness_Vote === 'down') {
							echo 'down';
						} else if ($Helpfulness_Vote === 'none') {
							echo 'none';
						} else {
							array_push($Helpfulness_Return['error'], 'Invalid Vote in Database.');
						}
					} else if (isset($_GET['set'])) {
						$Response_Vote = strval(htmlentities($_POST['vote'], ENT_QUOTES, 'UTF-8'));
						if ($Response_Vote === 'up' || $Response_Vote === 'down'  || $Response_Vote === 'none') {
							$Helpfulness_Update = mysqli_query($MySQL_Connection, "UPDATE `Helpfulness` SET `Helpfulness`='$Response_Vote', `Modified`='$Time' WHERE `Response_ID`='$Response_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
							if (!$Helpfulness_Update) array_push($Helpfulness_Return['error'], 'Vote Update Failed.');

							if ($Helpfulness_Vote == $Response_Vote) {
								$Helpfulness_Change = 0;
							} else if ( ( $Helpfulness_Vote == 'up' && $Response_Vote == 'none' ) || ( $Helpfulness_Vote == 'none' && $Response_Vote == 'down' ) ) {
								$Helpfulness_Change = -1;
							} else if ( ( $Helpfulness_Vote == 'down' && $Response_Vote == 'none' ) || ( $Helpfulness_Vote == 'none' && $Response_Vote == 'up' ) ) {
								$Helpfulness_Change = 1;
							} else if ( $Helpfulness_Vote == 'down' && $Response_Vote == 'up' ) {
								$Helpfulness_Change = 2;
							} else if ( $Helpfulness_Vote == 'up' && $Response_Vote == 'down' ) {
								$Helpfulness_Change = -2;
							} else {
								array_push($Helpfulness_Return['error'], 'Unknown Change.');
							}
						} else {
							// Invalid
							array_push($Helpfulness_Return['error'], 'Invalid Vote Returned from Database.');
						}
					}
				}

				if (isset($_GET['set'])) {
					if (isset($_POST['vote']) && !empty($_POST['vote'])) {
						// On Vote Helpfulness Change Helpfulness on Response
						$Responses_Helpfulness_New = $Responses_Helpfulness + $Helpfulness_Change;

						$Helpfulness_Modify = mysqli_query($MySQL_Connection, "UPDATE `Responses` SET `Helpfulness`='$Responses_Helpfulness_New', `Modified`='$Time' WHERE `Canonical`='$Response_Canonical' AND `ID`='$Response_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
						if (!$Helpfulness_Modify) array_push($Helpfulness_Return['error'], 'Helpfulness Update Failed.');
						echo 'true';
					}
				}
			}

		} else {
			if (isset($_GET['set']) && ( !isset($_POST['vote']) || empty($_POST['vote']) ) ) {
				// Error: Vote not set
				array_push($Helpfulness_Return['error'], 'Vote not set.');
			} else {
				// Error: Undefined
				array_push($Helpfulness_Return['error'], 'No Valid Helpfulness API Action Defined.');
			}
		}

		if (!empty($Helpfulness_Return['error'])) echo json_encode($Helpfulness_Return, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

	// Response API
	//  true	= posted
	//  double	= declined, double post
	//  error	= error

	// New
	// api?response
	//

	} else if (isset($_GET['respond'])) {

		if(!$Member_Auth) {
			echo json_encode(array('error' => array('Not Authenticated.')), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		} else {
			echo Respond();
		}

	} else {
		// Error: Undefined
		echo 'Error: No Valid API Action Defined.';
	}
}
