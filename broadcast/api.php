<?php

	$Title_HTML = 'API';
	$Title_Plain = 'API';

	$Description_HTML = 'API';
	$Description_Plain = 'API';

	$Keywords = 'api';

	$Featured_Image = '';

	$Canonical = 'api';

	$Post_Type = 'API';
	$Post_Category = '';

	require_once __DIR__.'/../request.php';

	$Account = 'account';

if ($Request['path'] === $Place['path'].$Canonical) {

	header('X-Frame-Options: SAMEORIGIN');

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
		// TODO Move to Function
	
		// IFDATABASEHELPFULLNESS
		if ( // If it is possible for them to be logged in.
			$Database['Connection'] &&
			$Database['Exists']['Responses'] &&
			$Database['Exists']['Helpfulness']
		) {

			$Helpfulness_Return = array();
			$Helpfulness_Return['error'] = array();

			if (!$Member_Auth) {
				$Helpfulness_Return['vote'] = 'false';

			} else if (empty($_GET['id'])) {
				array_push($Helpfulness_Return['error'], 'No Response ID Defined.');

			} else if ( isset($_GET['fetch']) || ( isset($_GET['set']) && !empty($_POST['vote']) ) ) {

				$Response_ID = strval(htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8'));

				// Check Response is Viewable
				$Responses = mysqli_query($Database['Connection'], 'SELECT * FROM `'.$Database['Prefix'].'Responses` WHERE `ID`=\''.$Response_ID.'\' AND (`Status`=\'Public\' OR `Status`=\'Private\')', MYSQLI_STORE_RESULT);
				if (!$Responses) array_push($Helpfulness_Return['error'], 'Responses Query Error.');
				$Responses_Count = mysqli_num_rows($Responses);

				if($Responses_Count === 0) {
					array_push($Helpfulness_Return['error'], 'Response not found.');

				} else {

					// Fetch Current Helpfulness Value from Response
					$Responses_Fetch = mysqli_fetch_assoc($Responses);
					$Responses_Helpfulness = $Responses_Fetch['Helpfulness'];

					$Helpfulness = mysqli_query($Database['Connection'], "SELECT * FROM `".$Database['Prefix']."Helpfulness` WHERE `Response_ID`='$Response_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
					if (!$Helpfulness) array_push($Helpfulness_Return['error'], 'Helpfulness Query Error.');
					$Helpfulness_Count = mysqli_num_rows($Helpfulness);
					if($Helpfulness_Count == 0) {

						if (isset($_GET['fetch'])) {
							// No Vote
							$Helpfulness_Return['vote'] =  'none';
						} else if (isset($_GET['set'])) {
							$Response_Vote = strval(htmlentities($_POST['vote'], ENT_QUOTES, 'UTF-8'));
							if ($Response_Vote === 'up' || $Response_Vote === 'down'  || $Response_Vote === 'none') {
								$Helpfulness_Insert = mysqli_query($Database['Connection'], "INSERT INTO `".$Database['Prefix']."Helpfulness` (`Response_ID`, `Member_ID`, `Helpfulness`, `Created`, `Modified`) VALUES ('$Response_ID', '$Member_ID', '$Response_Vote', '$Time', '$Time')", MYSQLI_STORE_RESULT);
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
							if ($Helpfulness_Vote === 'up') {
								$Helpfulness_Return['vote'] =  'up';
							} else if ($Helpfulness_Vote === 'down') {
								$Helpfulness_Return['vote'] =  'down';
							} else if ($Helpfulness_Vote === 'none') {
								$Helpfulness_Return['vote'] =  'none';
							} else {
								array_push($Helpfulness_Return['error'], 'Invalid Vote in Database.');
							}
						} else if (isset($_GET['set'])) {
							$Response_Vote = strval(htmlentities($_POST['vote'], ENT_QUOTES, 'UTF-8'));
							if ($Response_Vote === 'up' || $Response_Vote === 'down'  || $Response_Vote === 'none') {
								$Helpfulness_Update = mysqli_query($Database['Connection'], "UPDATE `".$Database['Prefix']."Helpfulness` SET `Helpfulness`='$Response_Vote', `Modified`='$Time' WHERE `Response_ID`='$Response_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
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
						if (!empty($_POST['vote'])) {
							// On Vote Helpfulness Change Helpfulness on Response
							$Responses_Helpfulness_New = $Responses_Helpfulness + $Helpfulness_Change;

							$Helpfulness_Modify = mysqli_query($Database['Connection'], "UPDATE `".$Database['Prefix']."Responses` SET `Helpfulness`='$Responses_Helpfulness_New', `Modified`='$Time' WHERE `ID`='$Response_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
							if (!$Helpfulness_Modify) array_push($Helpfulness_Return['error'], 'Helpfulness Update Failed.');
							$Helpfulness_Return['vote'] =  'confirm';
						}
					}
				}

			} else {
				if ( isset($_GET['set']) && empty($_POST['vote']) ) {
					// Error: Vote not set
					array_push($Helpfulness_Return['error'], 'Vote not set.');
				} else {
					// Error: Undefined
					array_push($Helpfulness_Return['error'], 'No Valid Helpfulness API Action Defined.');
				}
			}

			echo JSONDo($Helpfulness_Return);
			
		// IFDATABASEHELPFULLNESS
		} else {
			// TODO ERROR
		} // IFDATABASEHELPFULLNESS



	// Response API
	//  true	= posted
	//  double	= declined, double post
	//  error	= error

	// New
	// api?response
	//

	} else if (isset($_GET['respond'])) {

		if(!$Member_Auth) echo JSONDo(array('error' => array('Not Authenticated.')));
		else echo JSONDo(Respond());



	// Trending API
	//
	// api?trending
	// "api?trending&type=Blog Category"

	} else if (isset($_GET['trending'])) {

		if (isset($_GET['type'])) echo JSONDo(Trending('', $_GET['type']));
		else echo JSONDo(Trending(''));




	} else {
		// Error: Undefined
		echo JSONDo(array('error' => array('No Valid API Action Defined.')));
	}
}