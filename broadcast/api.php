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
	// api?helpfulness&fetch&canonical=   URL_HERE   &id=   #

	// Set
	// api?helpfulness&set&canonical=   URL_HERE   &id=   #   &vote=   #

	if (isset($_GET['helpfulness'])) {

		if (!$Member_Auth) {
			echo 'false';

		} else if (!isset($_GET['canonical']) || empty($_GET['canonical'])) {
			echo 'Error: No Canonical Defined.';

		} else if (!isset($_GET['id']) || empty($_GET['id'])) {
			echo 'Error: No Response ID Defined.';

		} else if ( isset($_GET['fetch']) || ( isset($_GET['set']) && isset($_POST['vote']) && !empty($_POST['vote']) ) ) {

			$Response_Canonical = trim(htmlentities(urldecode($_GET['canonical']), ENT_QUOTES, 'UTF-8'));
			$Response_ID = strval(htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8'));

			// Check Response is Viewable
			$Responses = mysqli_query($MySQL_Connection, 'SELECT * FROM `Responses` WHERE `Canonical`=\''.$Response_Canonical.'\' AND `ID`=\''.$Response_ID.'\' AND (`Status`=\'Public\' OR `Status`=\'Private\')', MYSQLI_STORE_RESULT);
			if (!$Responses) exit('error');
			$Responses_Count = mysqli_num_rows($Responses);

			if($Responses_Count === 0) {
				echo 'Error: Response not found.';

			} else {

				// Fetch Current Helpfulness Value from Response
				$Responses_Fetch = mysqli_fetch_assoc($Responses);
				$Responses_Helpfulness = $Responses_Fetch['Helpfulness'];

				$Helpfulness = mysqli_query($MySQL_Connection, "SELECT * FROM `Helpfulness` WHERE `Response_Canonical`='$Response_Canonical' AND `Response_ID`='$Response_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
				if (!$Helpfulness) exit('error');
				$Helpfulness_Count = mysqli_num_rows($Helpfulness);
				if($Helpfulness_Count == 0) {

					if (isset($_GET['fetch'])) {
						// No Vote
						echo 'none';
					} else if (isset($_GET['set'])) {
						$Response_Vote = strval(htmlentities($_POST['vote'], ENT_QUOTES, 'UTF-8'));
						if ($Response_Vote === 'up' || $Response_Vote === 'down'  || $Response_Vote === 'none') {
							$Helpfulness_Insert = mysqli_query($MySQL_Connection, "INSERT INTO `Helpfulness` (`Response_Canonical`, `Response_ID`, `Member_ID`, `Helpfulness`, `Created`, `Modified`) VALUES ('$Response_Canonical', '$Response_ID', '$Member_ID', '$Response_Vote', '$Time', '$Time')", MYSQLI_STORE_RESULT);
							if (!$Helpfulness_Insert) exit('Error: Vote Failed.');
							if ($Response_Vote == 'up') {
								$Helpfulness_Change = 1;
							} else if ($Response_Vote == 'down') {
								$Helpfulness_Change = -1;
							} else if ($Response_Vote == 'none') {
								$Helpfulness_Change = 0;
							} else {
								echo 'Error: Unknown Change';
							}
						} else {
							// Invalid
							echo 'Error: Invalid Vote.';
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
							echo 'invalid';
						}
					} else if (isset($_GET['set'])) {
						$Response_Vote = strval(htmlentities($_POST['vote'], ENT_QUOTES, 'UTF-8'));
						if ($Response_Vote === 'up' || $Response_Vote === 'down'  || $Response_Vote === 'none') {
							$Helpfulness_Update = mysqli_query($MySQL_Connection, "UPDATE `Helpfulness` SET `Helpfulness`='$Response_Vote', `Modified`='$Time' WHERE `Response_Canonical`='$Response_Canonical' AND `Response_ID`='$Response_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
							if (!$Helpfulness_Update) exit('Error: Vote Update Failed.');

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
								echo 'Error: Unknown Change';
							}
						} else {
							// Invalid
							echo 'Error: Invalid Vote.';
						}
					}
				}

				if (isset($_GET['set'])) {
					if (isset($_POST['vote']) && !empty($_POST['vote'])) {
						// On Vote Helpfulness Change Helpfulness on Response
						$Responses_Helpfulness_New = $Responses_Helpfulness + $Helpfulness_Change;

						$Helpfulness_Modify = mysqli_query($MySQL_Connection, "UPDATE `Responses` SET `Helpfulness`='$Responses_Helpfulness_New', `Modified`='$Time' WHERE `Canonical`='$Response_Canonical' AND `ID`='$Response_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
						if (!$Helpfulness_Modify) exit('Error: Helpfulness Update Failed.');
						echo 'true';
					}
				}
			}

		} else {
			if (isset($_GET['set']) && ( !isset($_POST['vote']) || empty($_POST['vote']) ) ) {
				// Error: Vote not set
				echo 'Error: Vote not set';
			} else {
				// Error: Undefined
				echo 'Error: No Valid Helpfulness API Action Defined.';
			}
		}

	// Response API
	//  true	= posted
	//  double	= declined, double post
	//  error	= error

	// New
	// api?response
	//

	} else if (isset($_GET['respond'])) {

		if(!$Member_Auth) {
			echo 'Error: Not Authenticated.';
		} else {
			// TODO Should Canonical and Type be post?
			// Based on previous, they could be get.
			if(!isset($_POST['canonical'])) {
				echo 'Error: Could not determine which post you wanted to leave a response to.';
			} else if(!isset($_POST['type'])) {
				echo 'Error: Response type was not set correctly.';
			} else if(!isset($_POST['post'])) {
				echo 'Error: You didn\'t enter a post.';
			} else {
				$Response_Canonical = trim(htmlentities($_POST['canonical'], ENT_QUOTES, 'UTF-8'));
				$Response_Type = trim(htmlentities($_POST['type'], ENT_QUOTES, 'UTF-8'));
				$Response_Post = trim(htmlentities($_POST['post'], ENT_QUOTES, 'UTF-8'));
				if ($Response_Type == 'review') {
					if(!isset($_POST['rating']) || empty($_POST['rating'])) {
						echo 'Error: You didn\'t choose a rating.';
					} else {
						$Response_Rating = strval(htmlentities($_POST['rating'], ENT_QUOTES, 'UTF-8'));
					}
				} else {
					$Response_Rating = 0;
				}

				// TODO Default Status by Type and Config
				// May require fetching status for Topics
				$Response_Status = 'Public';

				$Response_New = mysqli_query($MySQL_Connection, "INSERT INTO `Responses` (`Member_ID`, `Canonical`, `Type`, `Status`, `Helpfulness`, `Rating`, `Post`, `Created`, `Modified`) VALUES ('$Member_ID', '$Response_Canonical', '$Response_Type', '$Response_Status', '0', '$Response_Rating', '$Response_Post', '$Time', '$Time')", MYSQLI_STORE_RESULT);
				if (!$Response_New) exit('Invalid Query (Review_New): '.mysqli_error($MySQL_Connection));
				$Response_ID = mysqli_insert_id($MySQL_Connection);
				$Response_Parsed = Parsedown::instance()->parse(htmlentities($Response_Post, ENT_QUOTES, 'UTF-8'));
				$Response_Return = array('id' => $Response_ID, 'post' => $Response_Parsed);
				echo json_encode($Response_Return, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

			}
		}

	} else {
		// Error: Undefined
		echo 'Error: No Valid API Action Defined.';
	}
}