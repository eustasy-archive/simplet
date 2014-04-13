<?php

function Respond($Status_Override = false) {

	// Set some Globals
	global $Forum_Reply_Inherit, $Forum_Reply_Default, $Member_ID, $Time, $MySQL_Connection;

	// Prepare an array to be returned as JSON.
	$Response_Return = array();
	$Response_Return['error'] = array();

	if(isset($_POST['canonical']) && isset($_POST['type']) && isset($_POST['post'])) {

		// Set Variables
		$Response_Canonical = htmlentities($_POST['canonical'], ENT_QUOTES, 'UTF-8');
		$Response_Type = htmlentities($_POST['type'], ENT_QUOTES, 'UTF-8');
		$Response_Post = trim(htmlentities($_POST['post'], ENT_QUOTES, 'UTF-8'));

		// Response Rating
		if ($Response_Type == 'Review') {
			if(isset($_POST['rating'])) {
				$Response_Rating = strval(htmlentities($_POST['rating'], ENT_QUOTES, 'UTF-8'));
			} else {
				array_push($Response_Return['error'], 'You didn\'t choose a rating.');
			}
		} else {
			$Response_Rating = 0;
		}

		// Response Status
		if (isset($Status_Override) && $Status_Override) {
			$Response_Status = $Status_Override;
		} else if ($Response_Type == 'Post') {
			if ($Forum_Reply_Inherit === true) {

				// Fetch Status of Topic
				$Topic_Status_Query = mysqli_query($MySQL_Connection, 'SELECT `Status` FROM `Topics` WHERE `Slug`=\''.$Response_Canonical.'\' AND (`Status`=\'Public\' OR `Status`=\'Private\')', MYSQLI_STORE_RESULT);
				if (!$Topic_Status_Query) array_push($Response_Return['error'], 'Topic Status Query Error.');
				$Topic_Status_Count = mysqli_num_rows($Topic_Status_Query);
				if($Topic_Status_Count === 0) {
					array_push($Response_Return['error'], 'Topic Status Check Error. Using Fallback.');
					$Response_Status = $Forum_Reply_Default;
				} else {
					$Topic_Status_Fetch = mysqli_fetch_assoc($Topic_Status_Query);
					$Response_Status = $Topic_Status_Fetch['Status'];
				}

			} else {
				$Response_Status = $Forum_Reply_Default;
			}

		} else if ($Response_Type == 'Reviews') {
			$Response_Status = $Response_Status_Reviews;
		} else if ($Response_Type == 'Comments') {
			$Response_Status = $Response_Status_Comments;
		} else {
			$Response_Status = 'Public';
		}

		// Query
		$Response_Query = 'INSERT INTO `Responses` (`Member_ID`, `Canonical`, `Type`, `Status`, `Helpfulness`, `Rating`, `Post`, `Created`, `Modified`) VALUES (\''.$Member_ID.'\', \''.$Response_Canonical.'\', \''.$Response_Type.'\', \''.$Response_Status.'\', \'0\', \''.$Response_Rating.'\', \''.$Response_Post.'\', \''.$Time.'\', \''.$Time.'\')';
		$Response_New = mysqli_query($MySQL_Connection, $Response_Query, MYSQLI_STORE_RESULT);
		if (!$Response_New) array_push($Response_Return['error'], 'Invalid Query (Review_New): '.mysqli_error($MySQL_Connection));

		// Prepare statements to be returned.
		$Response_ID = mysqli_insert_id($MySQL_Connection);
		$Response_Parsed = Parsedown::instance()->parse($Response_Post, ENT_QUOTES, 'UTF-8');
		$Response_Return['id'] = $Response_ID;
		$Response_Return['post'] = $Response_Parsed;
		$Response_Return['rating'] = $Response_Rating;

		// If the response is (Public or Private) and is a Forum Post
		if (($Response_Status === 'Public' || $Response_Status === 'Private') && $Response_Type == 'Post') {
			// Update Responses Count for Topic
			// Also updates Modified Time for Topic
			Forum_Topic_Increment($Response_Canonical);
			// Get the Category Name from Topic
			$Topic_Info = Forum_Topic_Info($Response_Canonical);
			// Use the Category Name to update the Modified Time
			Forum_Category_Modified($Topic_Info['Category']);
		}

	} else {
		// Catch errors
		if(!isset($_POST['canonical'])) {
			array_push($Response_Return['error'], 'Could not determine which post you wanted to leave a response to.');
		} if(!isset($_POST['type'])) {
			array_push($Response_Return['error'], 'Response type was not set correctly.');
		} if(!isset($_POST['post'])) {
			array_push($Response_Return['error'], 'You didn\'t enter a post.');
		}
	}

	return $Response_Return;
}
