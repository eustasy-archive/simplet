<?php

	$TextTitle = 'Reply';
	$WebTitle = 'Reply &nbsp;&middot;&nbsp; Forum';
	$Canonical = 'forum/reply';
	$PostType = 'Processor';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'reply topic forum';

	require_once '../../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/'.$Canonical) {

	if(!$Member_Auth) {
		$Error = 'You are not logged in.';

	} elseif(!isset($_POST['topic_id']) || empty($_POST['topic_id'])) {
		$Error = 'No Topic ID Set.';

	} elseif(!isset($_POST['post']) || empty($_POST['post'])) {
		$Error = 'You didn\'t enter a reply.';

	} else {

		$Topic_ID = trim(htmlentities($_POST['topic_id'], ENT_QUOTES, 'UTF-8'));
		$Reply_Post = trim(htmlentities($_POST['post'], ENT_QUOTES, 'UTF-8'));

		if(empty($Topic_ID)) {
			$Error = 'No Topic ID Set.';

		} elseif(empty($Reply_Post)) {
			$Error = 'You didn\'t enter a reply.';

		} else {

			$Time = time();
			$Reply_Status = 'Public';

			$Reply_New = mysqli_query($MySQL_Connection, "INSERT INTO `Replies` (`Member_ID`, `Topic_ID`, `Status`, `Post`, `Created`, `Modified`) VALUES ('$Member_ID', '$Topic_ID', '$Reply_Status', '$Reply_Post', '$Time', '$Time')", MYSQLI_STORE_RESULT);
			if (!$Reply_New) exit('Invalid Query (Reply_New): '.mysqli_error($MySQL_Connection));

			header('Location: /forum/topic?topic='.$Topic_ID, TRUE, 302);
			die();

		}

	}

	require '../../header.php';

	echo '<h2>Error: ';
	if(isset($Error) && $Error) echo $Error;
	else echo 'Simplet can\'t tell what went wrong.';
	echo '</h2>';
	echo '<h3>Simplet encountered an error processing your reply.</h3>';

	require '../../footer.php';

}
