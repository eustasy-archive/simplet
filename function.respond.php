<?php

function Respond($Status_Override = false) {

	// Set some Globals
	global $Forum_Reply_Inherit, $Forum_Reply_Default, $Member_ID, $Time, $MySQL_Connection;

	// Prepare an array to be returned as JSON.
	$Response_Return = array();
	$Response_Return['error'] = array();

	if(isset($_POST['canonical']) && isset($_POST['type']) && isset($_POST['post'])) {

		// Set Variables
		$Response_Canonical = urlencode(htmlentities($_POST['canonical'], ENT_QUOTES, 'UTF-8'));
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
				// TODO Fetch Status of Topic
				$Response_Status = 'Public';
			} else {
				$Response_Status = $Forum_Reply_Default;
			}
		} else {
			$Response_Status = 'Public';
		}

		// Query
		$Response_Query = 'INSERT INTO `Responses` (`Member_ID`, `Canonical`, `Type`, `Status`, `Helpfulness`, `Rating`, `Post`, `Created`, `Modified`) VALUES (\''.$Member_ID.'\', \''.$Response_Canonical.'\', \''.$Response_Type.'\', \''.$Response_Status.'\', \'0\', \''.$Response_Rating.'\', \''.$Response_Post.'\', \''.$Time.'\', \''.$Time.'\')';
		$Response_New = mysqli_query($MySQL_Connection, $Response_Query, MYSQLI_STORE_RESULT);
		if (!$Response_New) array_push($Response_Return['error'], 'Invalid Query (Review_New): '.mysqli_error($MySQL_Connection));

		// Prepare statements to be returned.
		$Response_ID = mysqli_insert_id($MySQL_Connection);
		$Response_Parsed = Parsedown::instance()->parse(htmlentities($Response_Post, ENT_QUOTES, 'UTF-8'));
		$Response_Return['id'] = $Response_ID;
		$Response_Return['post'] = $Response_Parsed;

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

	return json_encode($Response_Return, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}