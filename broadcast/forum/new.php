<?php

	$TextTitle = 'New';
	$WebTitle = 'New &nbsp;&middot;&nbsp; Forum';
	$Canonical = 'forum/new';
	$PostType = 'Processor';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'new topic forum';

	require_once '../../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/'.$Canonical) {

	if(!$Member_Auth) {
		$Error = 'You are not logged in.';

	} else if(isset($_POST['title']) || isset($_POST['category'])) {

		if(!isset($_POST['title']) || empty($_POST['title'])) {
			$Error = 'No Topic Set.';

		} else if(!isset($_POST['category']) || empty($_POST['category'])) {
			$Error = 'No Category Set.';

		} else {

			$Topic_Title = trim(htmlentities($_POST['title'], ENT_QUOTES, 'UTF-8'));
			$Topic_Category = trim(htmlentities($_POST['category'], ENT_QUOTES, 'UTF-8'));

			if(!isset($_POST['post']) || empty($_POST['post'])) {
				$Topic_Post = trim(htmlentities($_POST['post'], ENT_QUOTES, 'UTF-8'));
			} else {
				$Topic_Post = false;
			}

			$Time = time();
			$Topic_Status = 'Public';

			$Topic_New = mysqli_query($MySQL_Connection, "INSERT INTO `Topics` (`Member_ID`, `Status`, `Category`, `Title`, `Created`, `Modified`) VALUES ('$Member_ID', '$Topic_Status', '$Topic_Category', '$Topic_Title', '$Time', '$Time')", MYSQLI_STORE_RESULT);
			if (!$Topic_New) exit('Invalid Query (Topic_New): '.mysqli_error($MySQL_Connection));
			
			// TODO $Decision = generatedSlugs OR IDs (?)
			// TODO Fetch $Decision

			// TODO If $Topic_Post add Post to Topic

			header('Location: /forum/topic?topic='.$Topic_ID, TRUE, 302);
			die();

		}

	} else {

		require '../../header.php';

		echo '
		<h2>Post a new Topic</h2>
		<form action="" method="post">
			<input type="hidden" name="category" value="Plans" required />
			<input type="text" name="title" value="" placeholder="What is a Forum?" required />
			<textarea name="post" placeholder="This bit is optional, but guarantees you get first post."></textarea>
			<div class="section group">
				<div class="col span_10_of_12">
					<p><small>If you wish, you can use Markdown for formatting.<br>
					Markdown can be used to make [<a href="#">links</a>](http://example.com),<br>
					<strong>**bold text**</strong>, <em>_italics_</em> and <code>`code`</code>.</small></p>
				</div>
				<div class="col span_2_of_12">
					<input type="submit" value="Create	">
				</div>
			</div>
		</form>';

		require '../../footer.php';

	}

	if(isset($Error) && $Error) {

		require '../../header.php';

		echo '<h2>Error: '.$Error.'</h2>';
		echo '<h3>Simplet encountered an error processing your reply.</h3>';

		require '../../footer.php';

	}

}
