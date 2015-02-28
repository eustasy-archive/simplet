<?php

$Page['Title']['Plain'] = 'API';
$Page['Description']['Plain'] = 'API';
$Page['Keywords'] = 'api';
$Page['Type'] = 'API';
$Canonical = '/api';

require_once __DIR__.'/_simplet/request.php';
if ($Request['Path'] === $Canonical) {

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
		$Helpfulness_Return = array();
		$Helpfulness_Return['error'] = array();

		// IFDATABASEHELPFULLNESS
		if ( // If it is possible for them to be logged in.
			!$Database['Connection'] ||
			!$Database['Exists']['Responses'] ||
			!$Database['Exists']['Helpfulness']
		) {
			array_push($Helpfulness_Return['error'], 'Database not available.');

		} else if (!$Member_Auth) {
			$Helpfulness_Return['vote'] = 'false';

		} else if (empty($_GET['canonical'])) {
			array_push($Helpfulness_Return['error'], 'No Response Canonical Defined.');

		} else if (empty($_GET['id'])) {
			array_push($Helpfulness_Return['error'], 'No Response ID Defined.');

		} else if ( empty($_POST['csrf_protection']) ) {
			array_push($Helpfulness_Return['error'], 'No CSRF Token Defined.');

		} else if ( !Runonce_CSRF_Check($_POST['csrf_protection']) ) {
			array_push($Helpfulness_Return['error'], 'CSRF Protection failed.');

		} else if ( isset($_GET['set']) && empty($_POST['vote']) ) {
			// Error: Vote not set
			array_push($Helpfulness_Return['error'], 'Vote not set.');

		} else if ( isset($_GET['fetch']) || isset($_GET['set']) ) {

			$Response_ID = strval(Input_Prepare($_GET['id']));
			$Response_Canonical = Input_Prepare($_GET['canonical']);

			// Check Response is Viewable
			$Responses = mysqli_query($Database['Connection'], 'SELECT * FROM `'.$Database['Prefix'].'Responses` WHERE `ID`=\''.$Response_ID.'\' AND (`Status`=\'Public\' OR `Status`=\'Private\')', MYSQLI_STORE_RESULT);
			if ( !$Responses ) {
				array_push($Helpfulness_Return['error'], 'Responses Query Error.');
			}
			$Responses_Count = mysqli_num_rows($Responses);

			if ( $Responses_Count === 0 ) {
				array_push($Helpfulness_Return['error'], 'Response not found.');

			} else {

				// Fetch Current Helpfulness Value from Response
				$Responses_Fetch = mysqli_fetch_assoc($Responses);
				$Responses_Helpfulness = $Responses_Fetch['Helpfulness'];

				$Helpfulness = mysqli_query($Database['Connection'], 'SELECT * FROM `'.$Database['Prefix'].'Helpfulness` WHERE `Response_ID`=\''.$Response_ID.'\' AND `Member_ID`=\''.$Member['ID'].'\' ORDER BY `Created` DESC LIMIT 1', MYSQLI_STORE_RESULT);
				if ( !$Helpfulness ) {
					array_push($Helpfulness_Return['error'], 'Helpfulness Query Error.');
				}
				$Helpfulness_Count = mysqli_num_rows($Helpfulness);
				if ( $Helpfulness_Count == 0 ) {

					if ( isset($_GET['fetch']) ) {
						// No Vote
						$Helpfulness_Return['vote'] =  'none';

					} else if ( isset($_GET['set']) ) {
						$Response_Vote = strval(Input_Prepare($_POST['vote']));
						if (
							$Response_Vote === 'up' ||
							$Response_Vote === 'down'  ||
							$Response_Vote === 'none'
						) {
							$Helpfulness_Insert = mysqli_query($Database['Connection'], 'INSERT INTO `'.$Database['Prefix'].'Helpfulness` (`Response_Canonical`, `Response_ID`, `Member_ID`, `Helpfulness`, `Created`, `Modified`) VALUES (\''.$Response_Canonical.'\', \''.$Response_ID.'\', \''.$Member['ID'].'\', \''.$Response_Vote.'\', \''.$Time['Now'].'\', \''.$Time['Now'].'\')', MYSQLI_STORE_RESULT);
							if ( !$Helpfulness_Insert ) {
								array_push($Helpfulness_Return['error'], 'Vote Insert Failed.');
							} else {
								$Helpfulness_Return['vote'] = 'confirm';
							}

							if ($Response_Vote == 'up') {
								$Helpfulness_Return['change'] = 1;
							} else if ($Response_Vote == 'down') {
								$Helpfulness_Return['change'] = -1;
							} else if ($Response_Vote == 'none') {
								$Helpfulness_Return['change'] = 0;
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
						$Response_Vote = strval(Input_Prepare($_POST['vote']));
						if (
							$Response_Vote === 'up' ||
							$Response_Vote === 'down' ||
							$Response_Vote === 'none'
						) {
							$Helpfulness_Update = mysqli_query($Database['Connection'], 'UPDATE `'.$Database['Prefix'].'Helpfulness` SET `Helpfulness`=\''.$Response_Vote.'\', `Modified`=\''.$Time['Now'].'\' WHERE `Response_ID`=\''.$Response_ID.'\' AND `Member_ID`=\''.$Member['ID'].'\' ORDER BY `Created` DESC LIMIT 1', MYSQLI_STORE_RESULT);
							if ( !$Helpfulness_Update ) {
								array_push($Helpfulness_Return['error'], 'Vote Update Failed.');
							} else {
								$Helpfulness_Return['vote'] = 'confirm';
							}

							if ( $Helpfulness_Vote == $Response_Vote ) {
								$Helpfulness_Return['change'] = 0;
							} else if (
								(
									$Helpfulness_Vote == 'up' &&
									$Response_Vote == 'none'
								) || (
									$Helpfulness_Vote == 'none' &&
									$Response_Vote == 'down'
								)
							) {
								$Helpfulness_Return['change'] = -1;
							} else if (
								(
									$Helpfulness_Vote == 'down' &&
									$Response_Vote == 'none'
								) || (
									$Helpfulness_Vote == 'none' &&
									$Response_Vote == 'up'
								)
							) {
								$Helpfulness_Return['change'] = 1;
							} else if (
								$Helpfulness_Vote == 'down' &&
								$Response_Vote == 'up'
							) {
								$Helpfulness_Return['change'] = 2;
							} else if (
								$Helpfulness_Vote == 'up' &&
								$Response_Vote == 'down'
							) {
								$Helpfulness_Return['change'] = -2;
							} else {
								array_push($Helpfulness_Return['error'], 'Unknown Change.');
							}
						} else {
							// Invalid
							array_push($Helpfulness_Return['error'], 'Invalid Vote Returned from Database.');
						}
					}
				}
			}
		} else {
			array_push($Helpfulness_Return['error'], 'Unknown error.');
		}
		echo API_Output($Helpfulness_Return);



	// Response API
	//  true	= posted
	//  double	= declined, double post
	//  error	= error

	// New
	// api?response
	//

	} else if (isset($_GET['respond'])) {

		echo API_Output(Respond());



	// Trending API
	//
	// api?trending
	// "api?trending&type=Blog Category"

	} else if (isset($_GET['trending'])) {

		if (isset($_GET['type'])) echo API_Output(Trending('', $_GET['type']));
		else echo API_Output(Trending(''));




	} else {
		// Error: Undefined
		echo API_Output(array('error' => array('No Valid API Action Defined.')));
	}
}