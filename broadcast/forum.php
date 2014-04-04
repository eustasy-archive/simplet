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

	require_once __DIR__.'/../request.php';

if (substr($Request['path'], 0, strlen($Place['path'].$Canonical)) === $Place['path'].$Canonical) {

	$Header = '../header.php';
	$Footer = '../footer.php';

	if (isset($_POST['action'])) {

		if (!$Member_Auth) {
    		header('HTTP/1.1 401 Unauthorized');
			$Error = 'You are not logged in.';

		} else if ($_POST['action']=='topic') {

			if (!isset($_POST['title']) || empty($_POST['title'])) {
				$Error = 'No Topic Set.';

			} else if (!isset($_POST['category']) || empty($_POST['category'])) {
				$Error = 'No Category Set.';

			} else {

				$Topic_Category = trim(htmlentities($_POST['category'], ENT_QUOTES, 'UTF-8'));

				$Category = mysqli_query($MySQL_Connection, "SELECT * FROM `Categories` WHERE `Slug`='$Topic_Category' AND NOT `Status`='Hidden' ORDER BY `Modified` DESC LIMIT 1", MYSQLI_STORE_RESULT);
				if (!$Category) exit('Invalid Query (Category): '.mysqli_error($MySQL_Connection));

				$Category_Count = mysqli_num_rows($Category);
				if ($Category_Count == 0) {
					$Error = 'Not a valid Category.';
				} else {
					$Category_Fetch = mysqli_fetch_assoc($Category);
					$Category_Status = $Category_Fetch['Status'];

					$Topic_Title = trim(htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8'));

					if (isset($_POST['post'])) {
						$Topic_Post = trim(htmlentities($_POST['post'], ENT_QUOTES, 'UTF-8'));
					} else {
						$Topic_Post = false;
					}

					$Topic_Slug = strtolower($_POST['title']);
					$Topic_Slug = htmlentities($Topic_Slug, ENT_QUOTES, 'UTF-8');
					$Topic_Slug = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $Topic_Slug);
					$Topic_Slug = html_entity_decode($Topic_Slug, ENT_QUOTES, 'UTF-8');
					$Topic_Slug = strip_tags($Topic_Slug);
					$Topic_Slug = stripslashes($Topic_Slug);
					$Topic_Slug = str_replace('\'', '', $Topic_Slug);
					$Topic_Slug = str_replace('"', '', $Topic_Slug);
					$Topic_Slug = preg_replace('/[^a-z0-9]+/', '-', $Topic_Slug);
					$Topic_Slug = strtr($Topic_Slug, 'àáâãäåòóôõöøèéêëðçìíîïùúûüñšž', 'aaaaaaooooooeeeeeciiiiuuuunsz');
					$Topic_Slug = preg_replace(array('/\s/', '/--+/', '/---+/'), '-', $Topic_Slug);
					$Topic_Slug = trim($Topic_Slug, '-');

					if ($Forum_Topic_Inherit) {
						$Topic_Status = $Category_Status;
					} else {
						$Topic_Status = $Forum_Topic_Default;
					}

					// TODO: Uniqueness check $Topic_Slug before inserting.
					//
					// Increment as:
					// slug
					// slug-1
					// slug-2
					//
					// Note: Original left unscathed,
					// subsequent duplicates numbered from one.

					$Topic_New = mysqli_query($MySQL_Connection, "INSERT INTO `Topics` (`Member_ID`, `Status`, `Category`, `Slug`, `Title`, `Created`, `Modified`) VALUES ('$Member_ID', '$Topic_Status', '$Topic_Category', '$Topic_Slug', '$Topic_Title', '$Time', '$Time')", MYSQLI_STORE_RESULT);
					if (!$Topic_New) exit('Invalid Query (Topic_New): '.mysqli_error($MySQL_Connection));

					if ($Topic_Post) {
						if ($Forum_Reply_Inherit) {
							$Reply_Status = $Topic_Status;
						} else {
							$Reply_Status = $Forum_Reply_Default;
						}
						$Topic_First = mysqli_query($MySQL_Connection, "INSERT INTO `Responses` (`Member_ID`, `Canonical`, `Type`, `Status`, `Post`, `Created`, `Modified`) VALUES ('$Member_ID', '$Topic_Slug', 'Post', '$Reply_Status', '$Topic_Post', '$Time', '$Time')", MYSQLI_STORE_RESULT);
						if (!$Topic_First) exit('Invalid Query (Topic_First): '.mysqli_error($MySQL_Connection));
					}

					header('Location: ?topic='.$Topic_Slug, TRUE, 302);
					die();
				}

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

				$Category_Slug = htmlentities($_GET['category'], ENT_QUOTES, 'UTF-8');

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

	} else if (isset($_GET['topic']) && !empty($_GET['topic'])) {

		$Topic_Slug = htmlentities($_GET['topic'], ENT_QUOTES, 'UTF-8');
		if (substr($Topic_Slug, 0, strlen($Forum)+1) == '/'.$Forum) {
			$Topic_Slug = substr($Topic_Slug, 7);
			if (empty($Topic_Slug)) { // HOME

				require $Header;

				echo '
				<h2>Forum</h2>';

				Forum_Categories();

				require $Footer;

			}
		}

		$Topic_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Topics` WHERE `Slug`='$Topic_Slug' LIMIT 0, 1", MYSQLI_STORE_RESULT);
		if (!$Topic_Check) exit('Invalid Query (Topic_Check): '.mysqli_error($MySQL_Connection));

		$Topic_Count = mysqli_num_rows($Topic_Check);
		if ($Topic_Count==0) {
			header('HTTP/1.1 404 Not Found');
			require $Header;
			echo '
			<h2>Error: Topic does not exist</h2>
			<p class="textcenter">Try the forum, or search for something like '.$Topic_Slug.'.</p>';
			require $Footer;
		} else {

			$Topic_Fetch = mysqli_fetch_assoc($Topic_Check);
			$Topic_Status = $Topic_Fetch['Status'];
			$Topic_Title = html_entity_decode($Topic_Fetch['Title'], ENT_QUOTES, 'UTF-8');
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

				ViewCount();

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

	} else if (isset($_GET['category']) && !empty($_GET['category'])) {

		Forum_Topics();

	} else {

		require $Header;

		echo '
		<h2>Forum</h2>';

		Forum_Categories();

		require $Footer;

	}

}
