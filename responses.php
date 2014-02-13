<?php

function Responses($Type='Comment', $Number=10, $Page=1, $Response_Canonical='') {

	// Set some Globals
	global $Member_Auth, $MySQL_Connection, $Canonical, $Sitewide_Root, $Comment_Helpful, $Forum_Reply_Helpful;

	if ( !isset($Response_Canonical) || empty($Response_Canonical) ) $Response_Canonical = $Canonical;

	if ($Type === 'Review' || ($Type === 'Comment' && $Comment_Helpful === true) || ($Type === 'Post' && $Forum_Reply_Helpful === true) ) {
		$Helpfulness_Show = true;
	} else {
		$Helpfulness_Show = false;
	}

	// Get Responses by Type and Publicity
	$Responses_Query_Select = 'SELECT * FROM `Responses` WHERE `Canonical`=\''.$Response_Canonical.'\' AND `Type`=\''.$Type.'\'';
	if ($Member_Auth) {
		$Responses_Query_Status = ' AND (`Status`=\'Public\' OR `Status`=\'Private\')';
	} else {
		$Responses_Query_Status = ' AND `Status`=\'Public\'';
	}
	if ($Type == 'Review') {
		$Responses_Query_Order = ' ORDER BY `Helpfulness` DESC';
	} else {
		$Responses_Query_Order = ' ORDER BY `Created` ASC';
	}
	$Responses_Query = $Responses_Query_Select.$Responses_Query_Status.$Responses_Query_Order;

	// Stop Number being ridiculously large.
	if ($Number > 100) $Number = 100;

	// Honor pagination
	if ($Page === 1) {
		$Responses_Query .= ' LIMIT '.$Number;
	} else {
		$Number_Start = $Page*$Number;
		$Responses_Query .= ' LIMIT '.$Number_Start.', '.$Number;
	}

	// Get Responses
	$Responses = mysqli_query($MySQL_Connection, $Responses_Query, MYSQLI_STORE_RESULT);
	if (!$Responses) exit('Invalid Query (Responses): '.mysqli_error($MySQL_Connection));

	// Count Responses
	$Responses_Count = mysqli_num_rows($Responses);
	if ($Responses_Count === 0) {

		// If none, tell us.
		echo '
			<hr>
			<h3>No '.$Type.'s to Display.</h3>';

	} else {

		// If some, tell us how many
		if ($Responses_Count === 1) {
			echo '
			<hr>
			<h3>'.$Responses_Count.' '.$Type.'</h3>';
		} else {
			if ($Type === 'Review') {
				// Average Rating for Reviews
				$Rating_Average = mysqli_query($MySQL_Connection, 'SELECT SUM(`Rating`) AS `Sum` FROM `Responses` WHERE `Canonical`=\''.$Response_Canonical.'\''.$Responses_Query_Status, MYSQLI_STORE_RESULT);
				if (!$Rating_Average) exit('Invalid Query (Rating_Average): '.mysqli_error($MySQL_Connection));
				$Rating_Fetch = mysqli_fetch_assoc($Rating_Average);
				$Rating_Average = round($Rating_Fetch['Sum']/$Responses_Count);
				echo '
				<hr>
				<h3>'.$Responses_Count.' Reviews &nbsp;&mdash;&nbsp; '.$Rating_Average.' Stars Average</h3>';
			} else {
				echo '
				<hr>
				<h3>'.$Responses_Count.' '.$Type.'s</h3>';
			}
		}

		// We'll need parsedown soon
		require 'libs/Parsedown.php';

		// Set up some arrays to act as a rudimentary user cache
		$Responses_Members_IDs = array();
		$Responses_Members_Names = array();
		$Responses_Members_Avatar = array();

		// While there are responses
		while($Responses_Fetch = mysqli_fetch_assoc($Responses)) {

			// Get all the information you can.
			$Responses_ID = $Responses_Fetch['ID'];
			$Responses_Member_ID = $Responses_Fetch['Member_ID'];
			$Responses_Post = Parsedown::instance()->parse(html_entity_decode($Responses_Fetch['Post'], ENT_QUOTES, 'UTF-8'));
			$Responses_Created = $Responses_Fetch['Created'];
			$Responses_Modified = $Responses_Fetch['Modified'];
			if ($Helpfulness_Show) {
				$Responses_Rating = $Responses_Fetch['Rating'];
				$Responses_Helpfulness = $Responses_Fetch['Helpfulness'];
			}

			// Users might be repeated, so use a set of arrays like a cache
			if (in_array($Responses_Member_ID, $Responses_Members_IDs)) {
				$Responses_Members_Num = array_search($Responses_Member_ID, $Responses_Members_IDs);
				$Responses_Store_Name = $Responses_Members_Names[$Responses_Members_Num];
				$Responses_Store_Avatar = $Responses_Members_Avatar[$Responses_Members_Num];
			} else {
				$Responses_Member = mysqli_query($MySQL_Connection, "SELECT * FROM `Members` WHERE `ID`='$Responses_Member_ID' AND `Status`='Active'", MYSQLI_STORE_RESULT);
				if (!$Responses_Member) exit('Invalid Query (Responses_Member): '.mysqli_error($MySQL_Connection));
				$Responses_Member_Count = mysqli_num_rows($Responses_Member);
				if ($Responses_Member_Count == 0) {
					$Responses_Store_Name = 'Deactivated';
					$Responses_Store_Avatar = 'http://www.gravatar.com/avatar/deactivated?s=128&d=mm';
				} else {
					$Responses_Member_Fetch = mysqli_fetch_assoc($Responses_Member);
					$Responses_Store_Name = $Responses_Member_Fetch['Name'];
					$Responses_Store_Avatar = 'http://www.gravatar.com/avatar/'.md5($Responses_Member_Fetch['Mail']).'?s=128&d=identicon';
				}
				$Responses_Members_ID[] = $Responses_Member_ID;
				$Responses_Members_Names[] = $Responses_Store_Name;
				$Responses_Members_Avatar[] = $Responses_Store_Avatar;
			}

			echo '
				<div class="section group darkrow" id="header_'.$Responses_ID.'">
					<div class="col span_2_of_12 textcenter';
			if ($Responses_Store_Name === 'Deactivated') echo ' faded';
			echo '"><p>'.$Responses_Store_Name.'</p></div>
					<div class="col span_8_of_12"><br></div>
					<div class="col span_2_of_12 textcenter"><p>';
			if ($Responses_Modified > $Responses_Created) echo '<span class="faded edited-time">edited '.date('d M, Y H:i', $Responses_Modified).' &nbsp;&middot;&nbsp; </span>';
			echo date('d M, Y H:i', $Responses_Created).'</p></div>
				</div>
				<div class="section group response '.$Responses_ID.'" id="response_'.$Responses_ID.'">
					<div class="col span_2_of_12"><img class="avatar" src="'.$Responses_Store_Avatar.'"></div>';
			if ($Helpfulness_Show) {
				echo '
					<div class="col span_8_of_12">
						'.$Responses_Post.'
					</div>
					<div class="col span_2_of_12">';
				if ($Type === 'Review') echo '
						<p>'.$Responses_Rating.' Stars</p>';
				echo '
						<p>'.number_format($Responses_Helpfulness).' Helpful</p>
						<div class="helpfulness"><img class="down faded" alt="Unhelpful" title="Unhelpful" src="'.$Sitewide_Root.'assets/images/thumbs_down.png"><img class="up faded" alt="Helpful" title="Helpful" src="'.$Sitewide_Root.'assets/images/thumbs_up.png"></div>
					</div>
				</div>';
			} else {
				echo '
					<div class="col span_10_of_12">
						'.$Responses_Post.'
					</div>
				</div>';
			}
		} // END While there is another Response

		if ($Helpfulness_Show) {
			echo '
				<script async>
					$(function(){
						var Current_Votes = [];
						$(\'.helpfulness\').each(function() {
							var Response_ID = $(this).parent().parent().attr(\'id\').substring(9);
							Current_Votes[Response_ID] = \'none\';
							$.get(\''.$Sitewide_Root.'api?helpfulness&fetch&canonical='.$Response_Canonical.'&id=\' + Response_ID, function(data) {
								if (data == \'none\') {
									console.log(\'Has No Vote on \' + Response_ID);
									Current_Votes[Response_ID] = \'none\';
								} else if (data == \'up\') {
									console.log(\'Has Up Vote on \' + Response_ID);
									$(\'.response.\' + Response_ID + \' .up\').removeClass(\'faded\');
									$(\'.response.\' + Response_ID + \' .down\').addClass(\'faded\');
									Current_Votes[Response_ID] = \'up\';
								} else if (data == \'down\') {
									console.log(\'Has Down Vote on \' + Response_ID);
									$(\'.response.\' + Response_ID + \' .up\').addClass(\'faded\');
									$(\'.response.\' + Response_ID + \' .down\').removeClass(\'faded\');
									Current_Votes[Response_ID] = \'down\';
								} else if (data == \'invalid\') {
									console.log(\'Invalid on \' + Response_ID);
									Current_Votes[Response_ID] = \'none\';
								} else {
									console.log(\'Error on \' + Response_ID);
									Current_Votes[Response_ID] = \'none\';
								}
							});
						});
						$(\'.helpfulness .up\').click(function() {
							var Response_ID = $(this).parent().parent().parent().attr(\'id\').substring(9);
							console.log(\'Initiate Up \' + Response_ID);
							if (Current_Votes[Response_ID] == \'up\') {
								console.log(\'Clear \' + Response_ID);
								$.post(\''.$Sitewide_Root.'api?helpfulness&set&canonical='.$Response_Canonical.'&id=\' + Response_ID, { vote: \'none\' }).done(function(data) {
									console.log(data);
									if (data == \'true\') {
										Current_Votes[Response_ID] = \'none\';
										console.log(\'Complete Clear \' + Response_ID);
										$(\'.response.\' + Response_ID + \' .up\').addClass(\'faded\');
										$(\'.response.\' + Response_ID + \' .down\').addClass(\'faded\');
									}
								});
							} else {
								console.log(\'Up \' + Response_ID);
								$.post(\''.$Sitewide_Root.'api?helpfulness&set&canonical='.$Response_Canonical.'&id=\' + Response_ID, { vote: \'up\' }).done(function(data) {
									console.log(data);
									if (data == \'true\') {
										Current_Votes[Response_ID] = \'up\';
										console.log(\'Complete Up \' + Response_ID);
										$(\'.response.\' + Response_ID + \' .up\').removeClass(\'faded\');
										$(\'.response.\' + Response_ID + \' .down\').addClass(\'faded\');
									}
								});
							}
						});
						$(\'.helpfulness .down\').click(function() {
							var Response_ID = $(this).parent().parent().parent().attr(\'id\').substring(9);
							console.log(\'Initiate Down \' + Response_ID);
							if (Current_Votes[Response_ID] == \'down\') {
								console.log(\'Clear \' + Response_ID);
								$.post(\''.$Sitewide_Root.'api?helpfulness&set&canonical='.$Response_Canonical.'&id=\' + Response_ID, { vote: \'none\' }).done(function(data) {
									console.log(data);
									if (data == \'true\') {
										Current_Votes[Response_ID] = \'none\';
										console.log(\'Complete Clear \' + Response_ID);
										$(\'.response.\' + Response_ID + \' .down\').addClass(\'faded\');
										$(\'.response.\' + Response_ID + \' .up\').addClass(\'faded\');
									}
								});
							} else {
								console.log(\'Down \' + Response_ID);
								$.post(\''.$Sitewide_Root.'api?helpfulness&set&canonical='.$Response_Canonical.'&id=\' + Response_ID, { vote: \'down\' }).done(function(data) {
									console.log(data);
									if (data == \'true\') {
										Current_Votes[Response_ID] = \'down\';
										console.log(\'Complete Down \' + Response_ID);
										$(\'.response.\' + Response_ID + \' .down\').removeClass(\'faded\');
										$(\'.response.\' + Response_ID + \' .up\').addClass(\'faded\');
									}
								});
							}
						});
					});
				</script>';
		}

		// TODO Pagination

	}

	if ($Member_Auth) {
		if ($Responses_Count > 0) echo '
			<hr>';
		echo '
			<div class="clear"></div>
			<form action="" method="post">
				<div class="section group">
					<input type="hidden" name="response" value="'.$Type.'" />
					<input type="hidden" name="canonical" value="'.$Response_Canonical.'" />
					<div class="col span_1_of_12"><br></div>
					<div class="col span_10_of_12">
						<h3>Leave a '.$Type.'</h3>
						<textarea name="post" required></textarea>
					</div>
					<div class="col span_1_of_12"><br></div>
				</div>
				<div class="section group">
					<div class="col span_1_of_12"><br></div>
					<div class="col span_7_of_12">
						<p class="floatleft"><small>If you wish, you can use Markdown for formatting.<br>
						Markdown can be used to make [<a href="#">links</a>](http://example.com),<br>
						<strong>**bold text**</strong>, <em>_italics_</em> and <code>`code`</code>.</small></p>';
		if ($Type === 'Review') {
			echo '
						<select name="rating" class="floatright">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>';
		}
		echo '
						<div class="clear"></div>
					</div>
					<div class="col span_1_of_12"><br></div>
					<div class="col span_2_of_12">
						<input type="submit" value="'.$Type.'" />
					</div>
					<div class="col span_1_of_12"><br></div>
				</div>
			</form>';
	} else {
		echo '
				<h3>You must <a href="'.$Sitewide_Root.'account?login">Log In</a> to '.$Type.'.</h3>';
	}


	return true;
}
