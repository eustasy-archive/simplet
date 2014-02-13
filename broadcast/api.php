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
			echo 'Error: No Response ID Defined.';

		} else if (isset($_GET['fetch'])) {
			$Response_Canonical = htmlentities($_GET['canonical'], ENT_QUOTES, 'UTF-8');
			$Response_ID = htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8');
			// TODO Check Response is Viewable
			$Helpfulness = mysqli_query($MySQL_Connection, "SELECT * FROM `Helpfulness` WHERE `Response_Canonical`='$Response_Canonical' AND `Response_ID`='$Response_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
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
			$Response_Canonical = htmlentities($_GET['canonical'], ENT_QUOTES, 'UTF-8');
			$Response_ID = htmlentities($_GET['id'], ENT_QUOTES, 'UTF-8');
			if (isset($_POST['vote']) && !empty($_POST['vote'])) {
				$Response_Vote = strval(htmlentities($_POST['vote'], ENT_QUOTES, 'UTF-8'));
				if ($Response_Vote === 'up' || $Response_Vote === 'down'  || $Response_Vote === 'none') {
					// TODO Check Response is Viewable
					$Helpfulness_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Helpfulness` WHERE `Response_Canonical`='$Response_Canonical' AND `Response_ID`='$Response_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
					if (!$Helpfulness_Check) exit('error');
					$Helpfulness_Check_Count = mysqli_num_rows($Helpfulness_Check);
					if($Helpfulness_Check_Count == 0) {
						$Helpfulness_Insert = mysqli_query($MySQL_Connection, "INSERT INTO `Helpfulness` (`Response_Canonical`, `Response_ID`, `Member_ID`, `Helpfulness`, `Created`, `Modified`) VALUES ('$Response_Canonical', '$Response_ID', '$Member_ID', '$Response_Vote', '$Time', '$Time')", MYSQLI_STORE_RESULT);
						if (!$Helpfulness_Insert) exit('Error: Vote Failed.');
					} else {
						$Helpfulness_Update = mysqli_query($MySQL_Connection, "UPDATE `Helpfulness` SET `Helpfulness`='$Response_Vote', `Modified`='$Time' WHERE `Response_Canonical`='$Response_Canonical' AND `Response_ID`='$Response_ID' AND `Member_ID`='$Member_ID' ORDER BY `Created` DESC LIMIT 1", MYSQLI_STORE_RESULT);
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

/*
if(isset($_POST['action']) && $_POST['action']=='review') {

		if(!$Member_Auth) {
			$Error = 'You cannot leave a review, you are not logged in.';
		} else {
			if(!isset($_POST['canonical']) || empty($_POST['canonical'])) {
				$Error = 'Could not determine which post you wanted to review on.';
			} else if(!isset($_POST['rating']) || empty($_POST['rating'])) {
				$Error = 'You didn\'t choose a rating.';
			} else if(!isset($_POST['post']) || empty($_POST['post'])) {
				$Error = 'You didn\'t enter a review.';
			} else {

				$Review_Canonical = trim(htmlentities($_POST['canonical'], ENT_QUOTES, 'UTF-8'));
				$Review_Rating = trim(htmlentities($_POST['rating'], ENT_QUOTES, 'UTF-8'));
				$Review_Post = trim(htmlentities($_POST['post'], ENT_QUOTES, 'UTF-8'));

				if(empty($Review_Canonical)) {
					$Error = 'Could not determine which post you wanted to leave a review on.';
				} else if(empty($Review_Post)) {
					$Error = 'You didn\'t enter a comment.';
				} else {

					$Time = time();
					$Review_Status = 'Public';

					$Review_New = mysqli_query($MySQL_Connection, "INSERT INTO `Reviews` (`Member_ID`, `Canonical`, `Status`, `Rating`, `Post`, `Created`, `Modified`) VALUES ('$Member_ID', '$Review_Canonical', '$Review_Status', '$Review_Rating', '$Review_Post', '$Time', '$Time')", MYSQLI_STORE_RESULT);
					if (!$Review_New) exit('Invalid Query (Review_New): '.mysqli_error($MySQL_Connection));

				}

			}
		}
	}
*/