<?php

	$Title_HTML = 'Forum';
	$Title_Plain = 'Forum';

	$Description_HTML = 'Forum';
	$Description_Plain = 'Forum';

	$Keywords = 'forum';

	$Featured_Image = '';

	$Canonical = 'forum';

	$Post_Type = 'Forum';
	$Post_Category = '';

	require_once __DIR__.'/../simplet/request.php';

if (substr($Request['path'], 0, strlen($Place['path'].$Canonical)) === $Place['path'].$Canonical) {
	$Header = '../header.php';
	$Footer = '../footer.php';

	// START FEED
	if (isset($_GET['feed'])) {
		Feed_Forum();
	// END FEED

	// START ACTION
	} else if (isset($_POST['action'])) {

		if (!$Member_Auth) {
    		header('HTTP/1.1 401 Unauthorized');
			$Error = 'You are not logged in.';

		} else if ($_POST['action'] == 'topic') {

			if (empty($_POST['title'])) {
				$Error = 'No Topic Set.';

			} else if (empty($_POST['category'])) {
				$Error = 'No Category Set.';



			} else if (
				$Database['Connection'] &&
				$Database['Exists']['Categories'] &&
				$Database['Exists']['Topics'] &&
				$Database['Exists']['Responses']
			) {

				$Topic_Category = trim(Input_Prepare($_POST['category']));

				// TODO Caterogry Info Function
				$Category = mysqli_query($Database['Connection'], "SELECT * FROM `".$Database['Prefix']."Categories` WHERE `Slug`='$Topic_Category' AND NOT `Status`='Hidden' ORDER BY `Modified` DESC LIMIT 1", MYSQLI_STORE_RESULT);
				if (!$Category) {
					if ( $Sitewide_Debug ) echo 'Invalid Query (Category): '.mysqli_error($Database['Connection']);
					// TODO Handle error
				} else {

					$Category_Count = mysqli_num_rows($Category);
					if ($Category_Count == 0) {
						$Error = 'Not a valid Category.';
					} else {
						$Category_Fetch = mysqli_fetch_assoc($Category);
						$Category_Status = $Category_Fetch['Status'];

						$Topic_Title = trim(Input_Prepare($_POST['title']));

						if (isset($_POST['post'])) $Topic_Post = trim(Input_Prepare($_POST['post']));
						else $Topic_Post = false;

						if ($Forum_Topic_Inherit) $Topic_Status = $Category_Status;
						else $Topic_Status = $Forum_Topic_Default;

						$Topic_Slug = Forum_Topic_Slug($_POST['title']);

						$Topic_New ='INSERT INTO `'.$Database['Prefix'].'Topics` (`Member_ID`, `Status`, `Category`, `Slug`, `Title`, `Responses`, `Created`, `Modified`) VALUES (\''.$Member_ID.'\', \''.$Topic_Status.'\', \''.$Topic_Category.'\', \''.$Topic_Slug.'\', \''.$Topic_Title.'\', ';
						if ($Topic_Post) $Topic_New .= '\'1\', ';
						else $Topic_New .= '\'0\', ';
						$Topic_New .= '\''.$Time.'\', \''.$Time.'\')';

						$Topic_New = mysqli_query($Database['Connection'], $Topic_New, MYSQLI_STORE_RESULT);
						if (!$Topic_New ) {
							if ( $Sitewide_Debug ) echo 'Invalid Query (Topic_New): '.mysqli_error($Database['Connection']);
							// TODO Handle error
						} else {

							if ($Topic_Post) {
								if ($Forum_Reply_Inherit) {
									$Reply_Status = $Topic_Status;
								} else {
									$Reply_Status = $Forum_Reply_Default;
								}
								$Topic_First = mysqli_query($Database['Connection'], "INSERT INTO `".$Database['Prefix']."Responses` (`Member_ID`, `Canonical`, `Type`, `Status`, `Post`, `Created`, `Modified`) VALUES ('$Member_ID', '$Topic_Slug', 'Post', '$Reply_Status', '$Topic_Post', '$Time', '$Time')", MYSQLI_STORE_RESULT);
								if (!$Topic_First ) {
									if ( $Sitewide_Debug ) echo 'Invalid Query (Topic_First): '.mysqli_error($Database['Connection']);
									// TODO Handle error
								}
							}

							Forum_Category_Modified($Topic_Category);
							Forum_Category_Increment($Topic_Category);

							header('Location: ?topic='.$Topic_Slug, TRUE, 302);
							die();

						}

					}

				}

			} else {
				// TODO ERROR No Database
			}

		}

		if (isset($Error) && $Error) {
			require $Header;
			echo '<h2>Error: '.$Error.'</h2>';
			echo '<h3>Simplet encountered an error processing your reply.</h3>';
			require $Footer;
		}

	} else if (isset($_GET['new'])) {

		if (!$Member_Auth) {
    		header('HTTP/1.1 401 Unauthorized');
			require $Header;
			echo '<h2>Error: You are not logged in.</h2>';
			echo '<h3 class="textleft">You cannot post a topic if you are not logged in. <a class="floatright" href="'.$Sitewide_Account.'?login">Log In</a></h3>';
			require $Footer;
		} else {

			if (isset($_GET['category'])) {

				$Category_Slug = Input_Prepare($_GET['category']);

				require $Header;
				echo '
					<h2>Post a new Topic</h2>
					<form action="" method="post">
						<input type="hidden" name="action" value="topic" required />
						<input type="hidden" name="category" value="'.$Category_Slug.'" required />
						<div class="section group">
							<div class="col span_1_of_12"><br></div>
							<div class="col span_10_of_12">
								<input type="text" name="title" value="" placeholder="What is a Forum?" required />
								<textarea name="post" placeholder="This bit is optional, but guarantees you get first post."></textarea>
							</div>
							<div class="col span_1_of_12"><br></div>
						</div>
						<div class="section group">
							<div class="col span_1_of_12"><br></div>
							<div class="col span_8_of_12">
								<p><small>If you wish, you can use Markdown for formatting.<br>
								Markdown can be used to make [<a href="#">links</a>](http://example.com),<br>
								<strong>**bold text**</strong>, <em>_italics_</em> and <code>`code`</code>.</small></p>
							</div>
							<div class="col span_2_of_12">
								<input type="submit" value="Create">
							</div>
							<div class="col span_1_of_12"><br></div>
						</div>
					</form>';

				require $Footer;
			} else {
				require $Header; // TODO Check Category Existence / Privileges
				echo '<h2>Error: No Category Set.</h2>';
				echo '<h3 class="textleft">You cannot post to no category.</h3>';
				require $Footer;
			}


		}

	} else if (!empty($_GET['topic'])) {
		// TODO Move to Function

		if (
			$Database['Connection'] &&
			$Database['Exists']['Topics']
		) {

			$Topic_Slug = Input_Prepare($_GET['topic']);

			// IFINDEX
			if (substr($Topic_Slug, 0, strlen($Sitewide_Forum)+1) == '/'.$Sitewide_Forum) {

				// TODO What..?
				$Topic_Slug = substr($Topic_Slug, 7);

				if (empty($Topic_Slug)) { // HOME
					require $Header;
					echo '
					<h2>Forum</h2>';
					Forum_Categories();
					require $Footer;

				}

			} // IFINDEX

			// TODO Topic Check Function
			$Topic_Check = 'SELECT * FROM `'.$Database['Prefix'].'Topics` WHERE `Slug`=\''.$Topic_Slug.'\' LIMIT 0, 1';
			$Topic_Check = mysqli_query($Database['Connection'], $Topic_Check, MYSQLI_STORE_RESULT);
			if (!$Topic_Check ) {
				if ( $Sitewide_Debug ) echo 'Invalid Query (Topic_Check): '.mysqli_error($Database['Connection']);
				// TODO Handle error
			} else {

				$Topic_Count = mysqli_num_rows($Topic_Check);
				if ($Topic_Count==0) {
					header('HTTP/1.1 404 Not Found');
					require $Header;
					echo '
					<h2>Error: Topic does not exist</h2>
					<p class="textcenter">Try the forum, or search for something like '.$Topic_Slug.'.</p>';
					require $Footer;
				} else {

					// TODO Topic Info Function
					$Topic_Fetch = mysqli_fetch_assoc($Topic_Check);
					$Topic_Status = $Topic_Fetch['Status'];
					$Topic_Title = $Topic_Fetch['Title'];
					$Topic_Created = $Topic_Fetch['Created'];
					$Topic_Modified = $Topic_Fetch['Modified'];

					if ($Topic_Status=='Public' || $Topic_Status=='Locked' || $Topic_Status=='Private' && $Member_Auth) {

						$Title_HTML = $Topic_Title;
						$Title_Plain = $Topic_Title;

						$Description_HTML = $Topic_Title;
						$Description_Plain = $Topic_Title;

						$Keywords = $Topic_Title.' topic forum';

						$Featured_Image = '';

						$Canonical = 'forum';

						$Post_Type = 'Forum Topic';

						Views_Count();

						require $Header;
						echo '
						<h2>'.$Topic_Title.'</h2>';

						Responses('Post', $Topic_Slug);

						require $Footer;
					} else if ($Topic_Status=='Private' && !$Member_Auth) {
						header('HTTP/1.1 401 Unauthorized');
						require $Header;
						echo '
						<h2>Error: Topic is private</h2>
						<p class="textcenter">You need to login.</p>';
						require $Footer;
					} else if ($Topic_Status=='Pending') {
						header('HTTP/1.1 403 Forbidden');
						require $Header;
						echo '
						<h2>Error: Topic is Pending</h2>
						<p class="textcenter">This topic is pending approval by moderators.</p>';
						require $Footer;
					} else if ($Topic_Status=='Hidden') {
						header('HTTP/1.1 403 Forbidden');
						require $Header;
						echo '
						<h2>Error: Topic is Hidden</h2>
						<p class="textcenter">You may never know what is here...</p>';
						require $Footer;
					} else {
						header('HTTP/1.1 403 Forbidden');
						require $Header;
						echo '
						<h2>Unknown Error: Topic Status is Unknown</h2>
						<p class="textcenter">Please contact the site owner if possible.</p>';
						require $Footer;
					}
				}

			}

		} else {
			// TODO ERROR No Topic Table
		}

	} else if (!empty($_GET['category'])) {

		Forum_Topics();

	} else {

		require $Header;

		echo '
		<h2>Forum</h2>';

		Forum_Categories();

		require $Footer;

	}

}
