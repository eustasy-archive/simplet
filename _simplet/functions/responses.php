<?php

////	Responses
//
// List Comments, Reviews, or Posts
//
// Responses();
// Responses('Review');
// Responses('Review', 'product?id=1');

function Responses($Type = 'Comment', $Response_Canonical = '') {

	global $Canonical, $Database, $Member, $Request, $Sitewide, $Sitewide_Comments_Helpful, $Sitewide_Posts_Helpful, $User;

	// IFEXISTSRESPONSES
	if (
		$Database['Exists']['Topics'] &&
		$Database['Exists']['Responses']
	) {

		// CATCHRESPOND Catch any responses that didn't go to the API
		if (
			isset($_GET['respond']) ||
			(
				isset($_POST['action']) &&
				$_POST['action']=='reply'
			)
		) {

			if ( !$Member['Auth'] ) {
				// Handle Not Authenticated Error on POST without JavaScript
				echo '
					<h3 class="warning red">Error: You cannot post a response as you are not <a href="'.$Sitewide['Account'].'?login&redirect='.urlencode($Canonical).'">logged in</a>.</h3>';

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
		// CATCHRESPOND

		// Set Response_Canonical for use later
		if ( empty($Response_Canonical) ) {
			$Response_Canonical = $Canonical;
		}
		$Response_Canonical = urlencode($Response_Canonical);

		// If the helpfulness should be shown.
		if (
			$Type === 'Review' ||
			(
				$Type === 'Comment' &&
				$Sitewide_Comments_Helpful === true
			) ||
			(
				$Type === 'Post' &&
				$Sitewide_Posts_Helpful === true
			)
		) {
			$Helpfulness_Show = true;
		} else {
			$Helpfulness_Show = false;
		}

		// Count things first
		$Responses_Query_Select = 'SELECT COUNT(*) AS `Count`';
		if ($Type === 'Review') {
			$Responses_Query_Select .= ', SUM(`Rating`) AS `Sum`';
		}

		// Get Responses by Type and Publicity
		$Responses_Query_Where = ' FROM `'.$Database['Prefix'].'Responses` WHERE `Canonical`=\''.$Response_Canonical.'\' AND `Type`=\''.$Type.'\'';

		// Limit by Status
		if ( $Member['Auth'] ) {
			$Responses_Query_Status = ' AND (`Status`=\'Public\' OR `Status`=\'Private\')';
		} else {
			$Responses_Query_Status = ' AND `Status`=\'Public\'';
		}

		// Build Responses_Query
		$Responses_Query = $Responses_Query_Select.$Responses_Query_Where.$Responses_Query_Status;

		// Get Responses
		$Responses = mysqli_query($Database['Connection'], $Responses_Query, MYSQLI_STORE_RESULT);
		if ( !$Responses ) {
			if ( $Sitewide['Debug'] ) {
				echo 'Invalid Query (Responses): '.mysqli_error($Database['Connection']);
			}
			// TODO Handle Error
		} else {

			// Count and Average
			$Responses_Fetch = mysqli_fetch_assoc($Responses);
			$Responses_Count = $Responses_Fetch['Count'];
			if ($Type === 'Review') {
				$Responses_Rating_Sum = $Responses_Fetch['Sum'];
				if ($Responses_Count != 0) {
					$Responses_Rating_Average = round($Responses_Rating_Sum/$Responses_Count);
				}
			}

			$Responses_None = '
				<hr>
				<div id="no-responses">
					<h3>No '.$Type.'s to Display.</h3>
					<hr>
				</div>
				<div id="responses"></div>';

			// Check Count
			if ($Responses_Count === 0) {
				// If none, tell us.
				echo $Responses_None;
			} else {

				// Select Everything
				$Responses_Query_Select = 'SELECT *';

				// Order in a helpful way
				if ($Type == 'Review') $Responses_Query_Order = ' ORDER BY `Helpfulness` DESC, `Created` ASC';
				else $Responses_Query_Order = ' ORDER BY `Created` ASC';

				// Pagination
				$Pagination = Pagination_Pre($Responses_Count);

				// Honor pagination
				if ($Pagination['Page'] === 1) $Responses_Query_Limit = ' LIMIT '.$Pagination['Show'];
				else $Responses_Query_Limit = ' LIMIT '.$Pagination['Start'].', '.$Pagination['Show'];

				// Preserve Query Strings
				$PreserveQueryStrings = Pagination_PreserveQueryStrings();

				// Build Responses_Query
				$Responses_Query = $Responses_Query_Select.$Responses_Query_Where.$Responses_Query_Status.$Responses_Query_Order.$Responses_Query_Limit;

				// Get Responses
				$Responses = mysqli_query($Database['Connection'], $Responses_Query, MYSQLI_STORE_RESULT);
				if ( !$Responses ) {
					if ( $Sitewide['Debug'] ) {
						echo 'Invalid Query (Responses): '.mysqli_error($Database['Connection']);
					}
					// TODO Handle Error
				} else {
					// TODO Wrap
				}

				// Count Responses
				$Responses_Check = mysqli_num_rows($Responses);
				if ($Responses_Check === 0) {

					// If none, tell us.
					echo $Responses_None;

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
					while ( $Responses_Fetch = mysqli_fetch_assoc($Responses) ) {

						// Get all the information you can.
						$Responses_ID = $Responses_Fetch['ID'];
						$Responses_Member_ID = $Responses_Fetch['Member_ID'];
						$Responses_Post = Output_Parse($Responses_Fetch['Post']);
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

							$Query = <<<QUERY
SELECT *
FROM `{$Database['Prefix']}Members`
WHERE `ID`='{$Responses_Member_ID}' AND `Status`='Active'
QUERY;

							$Responses_Member = mysqli_query($Database['Connection'], $Query, MYSQLI_STORE_RESULT);
							if (!$Responses_Member) {
								if ( $Sitewide['Debug'] ) {
									echo 'Invalid Query (Responses_Member): '.mysqli_error($Database['Connection']);
								}
								// TODO Handle Error
							} else {
								// TODO WRap
							}
							$Responses_Member_Count = mysqli_num_rows($Responses_Member);
							if ($Responses_Member_Count == 0) {
								$Responses_Store_Name = 'Deactivated';
								$Responses_Store_Avatar = 'https://www.gravatar.com/avatar/deactivated?s=128&d=mm';
							} else {
								$Responses_Member_Fetch = mysqli_fetch_assoc($Responses_Member);
								$Responses_Store_Name = $Responses_Member_Fetch['Name'];
								$Responses_Store_Avatar = 'https://www.gravatar.com/avatar/'.md5($Responses_Member_Fetch['Mail']).'?s=128&d=identicon';
							}
							$Responses_Members_ID[] = $Responses_Member_ID;
							$Responses_Members_Names[] = $Responses_Store_Name;
							$Responses_Members_Avatar[] = $Responses_Store_Avatar;
						}

						echo '
			<div class="section group darkrow" id="header_'.$Responses_ID.'">
				<div class="col span_2_of_12 textcenter';
						if ( $Responses_Store_Name === 'Deactivated' ) {
							echo ' faded';
						}
						echo '"><p>'.$Responses_Store_Name.'</p></div>
				<div class="col span_10_of_12 textright"><p>';
						if ($Responses_Modified > $Responses_Created) {
							$Responses_Modified = Time_Readable_Difference($Responses_Modified);
							echo '<span class="faded edited-time">edited '.$Responses_Modified['Prefered'].' &nbsp;&middot;&nbsp; </span>';
						}
						$Responses_Created = Time_Readable_Difference($Responses_Created);
						echo $Responses_Created['Prefered'].'</p></div>
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
					<div class="helpfulness hidden" id="helpfulness_'.$Responses_ID.'">
						<p>'.number_format($Responses_Helpfulness).' Helpful</p>
						<div><img class="down faded" alt="Unhelpful" title="Unhelpful" src="'.$Sitewide['Root'].'/assets/images/thumbs_down.png"><img class="up faded" alt="Helpful" title="Helpful" src="'.$Sitewide['Root'].'/assets/images/thumbs_up.png"></div>
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

					// Paginate if necessary
					if ($Pagination['Page Max'] > 1) {
						echo '<div class="breaker"></div>';
						Pagination_Links($Pagination, $PreserveQueryStrings);
					}

				}

				if ($Member['Auth']) {
					?>
		<div class="breaker"></div>
		<div class="clear"></div>

		<?php
			echo '<form action="?respond'.$PreserveQueryStrings['Miscellaneous'].$PreserveQueryStrings['Topic'].'&page='.$Pagination['Page'].'&show='.$Pagination['Show'].'" method="post" id="respond">';
		?>

			<div class="section group">
				<div class="col span_1_of_12"><br></div>
				<div class="col span_10_of_12">
					<h3>Leave a <?php echo $Type; ?></h3>
					<textarea name="post" required></textarea>
					<input type="hidden" name="type" value="<?php echo $Type; ?>" />
					<input type="hidden" name="canonical" value="<?php echo $Response_Canonical; ?>" />
				</div>
				<div class="col span_1_of_12"><br></div>
			</div>
			<div class="section group">
				<div class="col span_1_of_12"><br></div>
				<div class="col span_7_of_12">
					<p class="floatleft"><small>If you wish, you can use Markdown for formatting.<br>
					Markdown can be used to make [<a href="#">links</a>](https://example.com),<br>
					<strong>**bold text**</strong>, <em>_italics_</em> and <code>`code`</code>.</small></p>

				<?php
					if ($Type === 'Review') {
						echo '
					<select name="rating" class="floatright">
						<option value="5" selected="selected">5</option>
						<option value="4">4</option>
						<option value="3">3</option>
						<option value="2">2</option>
						<option value="1">1</option>
					</select>';
					}
				?>

					<div class="clear"></div>
				</div>
				<div class="col span_1_of_12"><br></div>
				<div class="col span_2_of_12">
					<input type="submit" value="<?php echo $Type; ?>" />
				</div>
				<div class="col span_1_of_12"><br></div>
				</div>
		</form>
		<script>
			$(function(){
			<?php
				if ($Helpfulness_Show) { ?>
				$('.helpfulness').each(function() {
					var Response_ID = $(this).attr('id').substring(12);
					$.post(
						'<?php echo $Sitewide['Root']; ?>/api?helpfulness&fetch&canonical=<?php echo $Response_Canonical; ?>&id=' + Response_ID,
						{
							csrf_protection: '<?php echo $User['CSRF']['Key']; ?>',
						},
						function(data) {
							if (data.vote == 'none') {
								console.log('Has No Vote on ' + Response_ID);
								$('.response.' + Response_ID + ' .helpfulness').removeClass('hidden');
							} else if (data.vote == 'up') {
								console.log('Has Up Vote on ' + Response_ID);
								$('.response.' + Response_ID + ' .up').removeClass('faded');
								$('.response.' + Response_ID + ' .down').addClass('faded');
								$('.response.' + Response_ID + ' .helpfulness').removeClass('hidden');
							} else if (data.vote == 'down') {
								console.log('Has Down Vote on ' + Response_ID);
								$('.response.' + Response_ID + ' .up').addClass('faded');
								$('.response.' + Response_ID + ' .down').removeClass('faded');
								$('.response.' + Response_ID + ' .helpfulness').removeClass('hidden');
							} else if (data.vote == 'invalid') console.log('Invalid on ' + Response_ID);
							else console.log('Error on ' + Response_ID + ': ' + data);
						},
						'json'
					).fail(function() {
						console.log('Failed to Load Helpfulness for ' + Response_ID);
					})
				});
				function HelpfulnessClick() {
					$('.helpfulness .up').click(function() {
						var Response_ID = $(this).parent().parent().attr('id').substring(12);
						// TODO Bug: Voting does not get ID for appended Responses
						console.log('Clicked Up on ' + Response_ID);
						if ($(this).hasClass('faded')) {
							console.log('Voted Up on ' + Response_ID);
							$.post(

								<?php
									echo '\''.$Sitewide['Root'].'/api?helpfulness&set&canonical='.$Response_Canonical.'&id=\' + Response_ID,';
								?>

								{
									vote: 'up',
									csrf_protection: '<?php echo $User['CSRF']['Key']; ?>',
								},
								function( data ) {
									if (data.vote == 'confirm') {
										console.log('Complete Up ' + Response_ID);
										$('.response.' + Response_ID + ' .up').removeClass('faded');
										$('.response.' + Response_ID + ' .down').addClass('faded');
									} else console.log(data + data.vote);
								},
								'json'
							);
						} else {
							console.log('Cleared Up on ' + Response_ID);
							$.post(

								<?php
									echo '\''.$Sitewide['Root'].'/api?helpfulness&set&canonical='.$Response_Canonical.'&id=\' + Response_ID,';
								?>

								{
									vote: 'none',
									csrf_protection: '<?php echo $User['CSRF']['Key']; ?>',
								},
								function( data ) {
									if (data.vote == 'confirm') {
										console.log('Complete Clear ' + Response_ID);
										$('.response.' + Response_ID + ' .up').addClass('faded');
										$('.response.' + Response_ID + ' .down').addClass('faded');
									} else console.log(data + data.vote);
								},
								'json'
							);
						}
					});
					$('.helpfulness .down').click(function() {
						var Response_ID = $(this).parent().parent().attr('id').substring(12);
						console.log('Clicked Down on ' + Response_ID);
						if ($(this).hasClass('faded')) {
							console.log('Voted Down on ' + Response_ID);
							$.post(

								<?php
									echo '\''.$Sitewide['Root'].'/api?helpfulness&set&canonical='.$Response_Canonical.'&id=\' + Response_ID,';
								?>

								{
									vote: 'down',
									csrf_protection: '<?php echo $User['CSRF']['Key']; ?>',
								},
								function( data ) {
									if (data.vote == 'confirm') {
										console.log('Complete Down ' + Response_ID);
										$('.response.' + Response_ID + ' .down').removeClass('faded');
										$('.response.' + Response_ID + ' .up').addClass('faded');
									} else console.log(data + data.vote);
								},
								'json'
							);
						} else {
							console.log('Cleared Down on ' + Response_ID);
							$.post(

								<?php
									echo '\''.$Sitewide['Root'].'/api?helpfulness&set&canonical='.$Response_Canonical.'&id=\' + Response_ID,';
								?>

								{
									vote: 'none',
									csrf_protection: '<?php echo $User['CSRF']['Key']; ?>',
								},
								function( data ) {
									if (data.vote == 'confirm') {
										console.log('Complete Clear ' + Response_ID);
										$('.response.' + Response_ID + ' .down').addClass('faded');
										$('.response.' + Response_ID + ' .up').addClass('faded');
									} else console.log(data + data.vote);
								},
								'json'
							);
						}
					});
					console.log('Helpfulness Click Ready');
				}
				HelpfulnessClick();
			<?php } ?>
				$('#respond').submit(function(event){
					event.preventDefault();
					$('#respond input[type="submit"]').attr('disabled','disabled');
					var postType = '<?php echo $Type; ?>';
					if (postType == 'Review') var rating = $('#respond select[name="rating"]').val();
					else var rating = 0;
					var post = $('#respond textarea[name="post"]').val();
					var respond = $.post(
						'<?php echo $Sitewide['Root']; ?>/api?respond',
						{
							type: postType,
							canonical: '<?php echo $Response_Canonical; ?>',
							rating: rating,
							post: post,
							csrf_protection: '<?php echo $User['CSRF']['Key']; ?>',
						}
					);
					respond.done(function(data) {
						var data = $.parseJSON(data)

						// TODO Bug: Show Error on Error
						// if data.error is not empty then error
						if ( data.error.length ) {
							console.log(data.error.length);
							console.log(data.error);
							$('#respond input[type="submit"]').removeAttr('disabled');
							for (index = 0; index < data.error.length; ++index) {
								$('#responses').append('<div class="warning"><h3>'+data.error[index]+'</h3></div>');
							}
						} else {

							var toAppend = '\
			<div class="section group darkrow" id="header_' + data.id + '">\
				<div class="col span_2_of_12 textcenter"><p><?php echo $Member['Name']; ?></p></div>\
				<div class="col span_10_of_12 textright"><p>Now</p></div>\
			</div>\
			<div class="section group response ' + data.id + '" id="response_' + data.id + '">\
				<div class="col span_2_of_12"><img class="avatar" src="https://www.gravatar.com/avatar/<?php echo md5($Member['Mail']); ?>?s=128&d=identicon"></div>\
				<?php
					if ($Helpfulness_Show) {
						echo '<div class="col span_8_of_12">\
					\' + data.post + \'\
				</div>\
				<div class="col span_2_of_12">\
';
							if ( $Type === 'Review' ) {
								echo '					<p>\' + data.rating + \' Stars</p>\
';
							}
							echo '\
					<div class="helpfulness" id="helpfulness_\' + data.id + \'">\
						<p>0 Helpful</p>\
						<div><img class="down faded" alt="Unhelpful" title="Unhelpful" src="'.$Sitewide['Root'].'/assets/images/thumbs_down.png"><img class="up faded" alt="Helpful" title="Helpful" src="'.$Sitewide['Root'].'/assets/images/thumbs_up.png"></div>\
					</div>\
				</div>\
			</div>\';
';
						} else {
							echo '<div class="col span_10_of_12">\
					\' + data.post + \'\
				</div>\
			</div>\';
';
						}
						?>

							// Remove any "no-responses" text.
							$('#no-responses').remove();

							// Append the new Post
							$('#responses').append(toAppend);

							// Re-Initialize Helpfulness Click Catching
							if (typeof(HelpfulnessClick) === 'function') HelpfulnessClick();

							// Reset the Form
							$('#respond input[type="text"]').val('');
							$('#respond textarea').val('');
							$('#respond select').val([]);

							// Re-Enable the form
							$('#respond input[type="submit"]').removeAttr('disabled');

						}

					});

					respond.error(function() {
						$('#respond input[type="submit"]').removeAttr('disabled');
						// TODO Bug: Show Error on Error
						$('#responses').append('<div class="warning"><h3>Sorry, posting failed. Please try again in a moment.</h3></div>');
					});

				});
			});
		</script>

			<?php
				} else {
					echo '
		<h3>You must <a href="'.$Sitewide['Account'].'?login&redirect='.$Response_Canonical.'">Log In</a> to '.$Type.'.</h3>';
				}
			}

			return true;

		}

	// IFEXISTSRESPONSES
	} else {
		// TODO ERROR
		return false;
	} // IFEXISTSRESPONSES
}