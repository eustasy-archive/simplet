<?php

function Responses($Type='Comment', $Show=10, $Page=1, $Response_Canonical='') {

	// Set some Globals
	global $Canonical, $Comment_Helpful, $Forum_Reply_Helpful, $Member_Auth, $Member_Name, $Member_Mail, $Time, $Request, $MySQL_Connection, $Sitewide_Root;

	// Catch any responses that didn't go to the API
	if (isset($_GET['respond']) || (isset($_POST['action']) && $_POST['action']=='reply')) {
		if(!$Member_Auth) {
			// Handle Not Authenticated Error on POST without JavaScript
			echo '
			<h3 class="warning red">Error: You cannot post a response as you are not logged in.</h3>';
		} else {
			// Handle Response (an array, not JSON) without JavaScript
			$Response_Submit = Respond();
			if (!empty($Response_Submit['error'])) {
				foreach ($Response_Submit['error'] as $Response_Submit_Errors) {
					echo '
			<h3 class="warning red">Error: '.$Response_Submit_Errors.'</h3>';
				}
			}
		}
	}

	if ( !isset($Response_Canonical) || empty($Response_Canonical) ) $Response_Canonical = $Canonical;
	$Response_Canonical = urlencode($Response_Canonical);

	if ($Type === 'Review' || ($Type === 'Comment' && $Comment_Helpful === true) || ($Type === 'Post' && $Forum_Reply_Helpful === true) ) {
		$Helpfulness_Show = true;
	} else {
		$Helpfulness_Show = false;
	}

	// Count things first
	$Responses_Query_Select = 'SELECT COUNT(*) AS `Count`';
	if ($Type === 'Review') $Responses_Query_Select .= ', SUM(`Rating`) AS `Sum`';

	// Get Responses by Type and Publicity
	$Responses_Query_Where = ' FROM `Responses` WHERE `Canonical`=\''.$Response_Canonical.'\' AND `Type`=\''.$Type.'\'';

	// Limit by Status
	if ($Member_Auth) {
		$Responses_Query_Status = ' AND (`Status`=\'Public\' OR `Status`=\'Private\')';
	} else {
		$Responses_Query_Status = ' AND `Status`=\'Public\'';
	}

	// Build Responses_Query
	$Responses_Query = $Responses_Query_Select.$Responses_Query_Where.$Responses_Query_Status;

	// Get Responses
	$Responses = mysqli_query($MySQL_Connection, $Responses_Query, MYSQLI_STORE_RESULT);
	if (!$Responses) exit('Invalid Query (Responses): '.mysqli_error($MySQL_Connection));

	// Count and Average
	$Responses_Fetch = mysqli_fetch_assoc($Responses);
	$Responses_Count = $Responses_Fetch['Count'];
	if ($Type === 'Review') {
		$Responses_Rating_Sum = $Responses_Fetch['Sum'];
		if ($Responses_Count != 0) {
			$Responses_Rating_Average = round($Responses_Rating_Sum/$Responses_Count);
		}
	}

	// Preserve Query Stings
	$Preserve_Query_Strings = '';
	$Preserve_Pagination = '';
	if (isset($_GET)) {
		foreach($_GET as $Get_Key => $Get_Value) {
			// Ignore old page and show variables
			if (strtolower($Get_Key) != 'page' && strtolower($Get_Key) != 'show' && strtolower($Get_Key) != 'topic') {
				$Preserve_Query_Strings .= '&'.$Get_Key.'='.$Get_Value;
			} else if (strtolower($Get_Key) == 'topic') {
				// Preserve Topic if Necessary
				if (substr($Get_Value, 0, 1) != '/') $Preserve_Query_Strings .= '&'.$Get_Key.'='.$Get_Value;
			} else {
				$Preserve_Pagination .= '&'.$Get_Key.'='.$Get_Value;
			}
		}
	}

	// Check Count
	if ($Responses_Count === 0) {
		// If none, tell us.
		echo '
		<hr>
		<h3>No '.$Type.'s to Display.</h3>
		<hr>
		<div id="responses"></div>';
	} else {

		// Select Everything
		$Responses_Query_Select = 'SELECT *';

		// Order in a helpful way
		if ($Type == 'Review') {
			$Responses_Query_Order = ' ORDER BY `Helpfulness` DESC, `Created` ASC';
		} else {
			$Responses_Query_Order = ' ORDER BY `Created` ASC';
		}

		// Catch Page and Show
		if (isset($_GET['page'])) $Page = intval($_GET['page']);
		if (isset($_GET['show'])) $Show = intval($_GET['show']);

		// Stop Number being ridiculously large.
		if ($Show > 100) $Show = 100;
		if ($Show < 1) $Show = 10;
		if ($Page < 1) $Page = 1;

		// Stop Page being further than possible
		// (Show the last if it's over, first if negative)
		$Page_Max = ceil($Responses_Count/$Show);

		// Honor pagination
		if ($Page === 1) {
			$Responses_Query_Limit = ' LIMIT '.$Show;
		} else {
			 if ($Page > $Page_Max) {
				if ($Page_Max < 1) {
					$Page = 1;
				} else {
					$Page = $Page_Max;
				}
			}
			$Show_Start = ($Page-1)*$Show;
			$Responses_Query_Limit = ' LIMIT '.$Show_Start.', '.$Show;
		}

		// Build Responses_Query
		$Responses_Query = $Responses_Query_Select.$Responses_Query_Where.$Responses_Query_Status.$Responses_Query_Order.$Responses_Query_Limit;

		// Get Responses
		$Responses = mysqli_query($MySQL_Connection, $Responses_Query, MYSQLI_STORE_RESULT);
		if (!$Responses) exit('Invalid Query (Responses): '.mysqli_error($MySQL_Connection));

		// Count Responses
		$Responses_Check = mysqli_num_rows($Responses);
		if ($Responses_Check === 0) {

			// If none, tell us.
			echo '
		<hr>
		<h3>No '.$Type.'s to Display.</h3>
		<hr>
		<div id="responses"></div>';

		} else {

			// If some, tell us how many
			if ($Responses_Count === 1) {
				echo '
		<hr>
		<h3>1 '.$Type.'</h3>';
			} else {
				if ($Type === 'Review') {
					echo '
		<hr>
		<h3>'.$Responses_Count.' Reviews &nbsp;&mdash;&nbsp; '.$Responses_Rating_Average.' Stars Average</h3>';
				} else {
					echo '
		<hr>
		<h3>'.$Responses_Count.' '.$Type.'s</h3>';
				}
			}

			echo '
		<div id="responses">';

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
				<div class="col span_10_of_12 textright"><p>';
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
					<p class="rating">'.$Responses_Rating.' Stars</p>';
					echo '
					<div class="helpfulness hidden">
						<p>'.number_format($Responses_Helpfulness).' Helpful</p>
						<div><img class="down faded" alt="Unhelpful" title="Unhelpful" src="'.$Sitewide_Root.'assets/images/thumbs_down.png"><img class="up faded" alt="Helpful" title="Helpful" src="'.$Sitewide_Root.'assets/images/thumbs_up.png"></div>
					</div>
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

			echo '
		</div>';

			$Response_Canonical_Encoded = urlencode($Response_Canonical);

			if ($Helpfulness_Show) {
				echo '
		<script>
			$(function(){
				var Current_Votes = [];
				$(\'.helpfulness\').each(function() {
					var Response_ID = $(this).parent().parent().attr(\'id\').substring(9);
					Current_Votes[Response_ID] = \'none\';
					$.get(\''.$Sitewide_Root.'api?helpfulness&fetch&canonical='.$Response_Canonical_Encoded.'&id=\' + Response_ID, function(data) {
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
							console.log(\'Error on \' + Response_ID + \' \' + data);
							Current_Votes[Response_ID] = \'none\';
						}
						$(\'.response.\' + Response_ID + \' .helpfulness\').removeClass(\'hidden\');
					});
				});
				$(\'.helpfulness .up\').click(function() {
					var Response_ID = $(this).parent().parent().parent().parent().attr(\'id\').substring(9);
					console.log(\'Initiate Up \' + Response_ID);
					if (Current_Votes[Response_ID] == \'up\') {
						console.log(\'Clear \' + Response_ID);
						$.post(\''.$Sitewide_Root.'api?helpfulness&set&canonical='.$Response_Canonical_Encoded.'&id=\' + Response_ID, { vote: \'none\' }).done(function(data) {
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
						$.post(\''.$Sitewide_Root.'api?helpfulness&set&canonical='.$Response_Canonical_Encoded.'&id=\' + Response_ID, { vote: \'up\' }).done(function(data) {
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
					var Response_ID = $(this).parent().parent().parent().parent().attr(\'id\').substring(9);
					console.log(\'Initiate Down \' + Response_ID);
					if (Current_Votes[Response_ID] == \'down\') {
					console.log(\'Clear \' + Response_ID);
						$.post(\''.$Sitewide_Root.'api?helpfulness&set&canonical='.$Response_Canonical_Encoded.'&id=\' + Response_ID, { vote: \'none\' }).done(function(data) {
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
						$.post(\''.$Sitewide_Root.'api?helpfulness&set&canonical='.$Response_Canonical_Encoded.'&id=\' + Response_ID, { vote: \'down\' }).done(function(data) {
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

			// Pagination
			if ($Page_Max != 1) {

				$Page_Wayback = $Page-2;
				$Page_Previous = $Page-1;
				$Page_Next = $Page+1;
				$Page_Far = $Page+2;

				echo '
		<div class="breaker"></div>
		<p class="textcenter">';

				$Paginate_End = '&show='.$Show.$Preserve_Query_Strings;

				if ($Page > 3) echo '<span class="floatleft"><a href="?page=1'.$Paginate_End.'">1</a> &emsp; &hellip; &emsp; </span>';

				if ($Page >= 3) echo '<a href="?page='.$Page_Wayback.$Paginate_End.'">'.$Page_Wayback.'</a> &emsp; ';
				if ($Page >= 2) echo '<a href="?page='.$Page_Previous.$Paginate_End.'">'.$Page_Previous.'</a> &emsp; ';

				echo $Page;

				if ($Page_Next <= $Page_Max) echo ' &emsp; <a href="?page='.$Page_Next.$Paginate_End.'">'.$Page_Next.'</a>';
				if ($Page_Far <= $Page_Max) echo ' &emsp; <a href="?page='.$Page_Far.$Paginate_End.'">'.$Page_Far.'</a>';

				if ($Page_Far < $Page_Max) echo '<span class="floatright"> &emsp; &hellip; &emsp; <a href="?page='.$Page_Max.$Paginate_End.'">'.$Page_Max.'</a></span>';

				echo '</p>
		<div class="breaker"></div>';

			}

		}

		if ($Member_Auth) {
			echo '
		<div class="clear"></div>
		<form action="?respond'.$Preserve_Query_Strings.$Preserve_Pagination.'" method="post" id="respond">
			<div class="section group">
				<div class="col span_1_of_12"><br></div>
				<div class="col span_10_of_12">
					<h3>Leave a '.$Type.'</h3>
					<textarea name="post" required></textarea>
					<input type="hidden" name="type" value="'.$Type.'" />
					<input type="hidden" name="canonical" value="'.$Response_Canonical.'" />
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
		</form>
		<script>
			$(function(){
				$(\'#respond\').submit(function(event){
					event.preventDefault();';
			if ($Type === 'Review') echo '
					var rating = $(\'#respond select[name="rating"]\').val();';
			echo '
					var post = $(\'#respond textarea[name="post"]\').val();
					var respond = $.post(
						\''.$Sitewide_Root.'api?respond\',
						{
							type: \''.$Type.'\',
							canonical: \''.$Response_Canonical.'\',';
			if ($Type === 'Review') echo '
							rating: rating,';
			echo '
							post: post
						}
					);
					respond.done(function(data) {
						console.log(data);
						var json = $.parseJSON(data);
						var toAppend = \'\
			<div class="section group darkrow" id="header_\' + json.id + \'">\
				<div class="col span_2_of_12 textcenter"><p>'.$Member_Name.'</p></div>\
				<div class="col span_10_of_12 textright"><p>'.date('d M, Y H:i', $Time).'</p></div>\
			</div>\
			<div class="section group response \' + json.id + \'" id="response_\' + json.id + \'">\
				<div class="col span_2_of_12"><img class="avatar" src="http://www.gravatar.com/avatar/'.md5($Member_Mail).'?s=128&d=identicon"></div>';
				if ($Helpfulness_Show) {
					echo '\
				<div class="col span_8_of_12">\
					\' + json.post + \'\
				</div>\
				<div class="col span_2_of_12">';
					if ($Type === 'Review') echo '\
					<p>\' + rating + \' Stars</p>';
					echo '\
					<p>0 Helpful</p>\
					<div class="helpfulness"><img class="down faded" alt="Unhelpful" title="Unhelpful" src="'.$Sitewide_Root.'assets/images/thumbs_down.png"><img class="up faded" alt="Helpful" title="Helpful" src="'.$Sitewide_Root.'assets/images/thumbs_up.png"></div>\
				</div>\
			</div>';
				} else {
					echo '\
			<div class="col span_10_of_12">\
				\' + json.post + \'\
			</div>\
		</div>';
				}
				echo '\';
						$(\'#responses\').append(toAppend);
					});
					respond.error(function() {
					});
				});
			});
		</script>';
			// TODO Make Submit un-clickable to prevent double-posts.
			// TODO Show Error on Error (and Re-instate Submit).
			// TODO Clear (Reset) Form and Re-instate Submit.
		} else {
			echo '
		<h3>You must <a href="'.$Sitewide_Root.'account?login">Log In</a> to '.$Type.'.</h3>';
		}
	}
	return true;
}
