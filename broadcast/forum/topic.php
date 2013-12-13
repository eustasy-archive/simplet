<?php

	$TextTitle = 'Topic';
	$WebTitle = 'Topic';
	$Canonical = 'forum/topic';
	$PostType = 'Topic';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'topic forum';

	require_once '../../request.php';

if(htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	$Topic_ID = htmlentities($_GET['topic'], ENT_QUOTES, 'UTF-8');

	$Topic_Check = mysqli_query($MySQL_Connection, "SELECT * FROM `Topics` WHERE `ID`='$Topic_ID' LIMIT 0, 1", MYSQLI_STORE_RESULT);
	if(!$Topic_Check) exit('Invalid Query (Topic_Check): '.mysqli_error($MySQL_Connection));

	$Topic_Count = mysqli_num_rows($Topic_Check);
	if($Topic_Count==0) {
		// TODO Topic does no exist
		require '../../header.php';
		echo '
		<h2>Error: Topic does not exist</h2>
		<p class="textcenter">Try the forum.</p>';
		require '../../footer.php';
	} else {

		require '../../parsedown.php';

		$Topic_Fetch = mysqli_fetch_assoc($Topic_Check);
		$Topic_Status = $Topic_Fetch['Status'];
		$Topic_Title = html_entity_decode($Topic_Fetch['Title'], ENT_QUOTES, 'UTF-8');
		$Topic_Created = date('d M, Y H:i', $Topic_Fetch['Created']);
		$Topic_Modified = $Topic_Fetch['Modified'];

		if($Topic_Status=='Hidden'){
			// TODO Topic is Hidden
			require '../../header.php';
			echo '
			<h2>Error: Topic is Hidden</h2>
			<p class="textcenter">You may never know what is here...</p>';
			require '../../footer.php';
		} else if($Topic_Status=='Private' && !$Member_Auth) {
			// TODO Topic is private
			require '../../header.php';
			echo '
			<h2>Error: Topic is private</h2>
			<p class="textcenter">You need to login.</p>';
			require '../../footer.php';

		}

		$TextTitle = $Topic_Title; // TODO Add Topic Title
		$WebTitle = $Topic_Title; // TODO Add Topic Title
		$Canonical = 'forum/topic?topic='.$Topic_ID; // TODO New Topic
		$Description = $Topic_Title; // TODO Add Topic Description
		$Keywords = 'topic forum'; // TODO Add Topic Keywords


		require '../../header.php';

		echo '
		<h2>'.$Topic_Title.'</h2>';

		$Replies = mysqli_query($MySQL_Connection, "SELECT * FROM `Replies` WHERE `Topic_ID`='$Topic_ID' AND `Status`='Public' ORDER BY `Created` ASC", MYSQLI_STORE_RESULT);
		if (!$Replies) exit('Invalid Query (Replies): '.mysqli_error($MySQL_Connection));

		$Replies_Count = mysqli_num_rows($Replies);
		if ($Replies_Count == 0) {
			echo '<h3>No replies.</h3>';
		} else {

			while($Replies_Fetch = mysqli_fetch_assoc($Replies)) {
				$Reply_Member_ID = $Replies_Fetch['Member_ID'];
				$Reply_Post = Parsedown::instance()->parse(html_entity_decode($Replies_Fetch['Post'], ENT_QUOTES, 'UTF-8'));
				$Reply_Created = date('d M, Y H:i', $Replies_Fetch['Created']);
				$Reply_Modified = $Replies_Fetch['Modified'];
				echo '
		<div class="section group darkrow">
			<div class="col span_2_of_12 textcenter"><p>'.$Reply_Member_ID.'</p></div>
			<div class="col span_10_of_12  faded"><p>'.$Reply_Created.'</p></div>
		</div>
		<div class="section group reply">
			<div class="col span_2_of_12"><img class="avatar" src="http://lewisgoddard.eustasy.org/images/faces/circular-blue-small-cropped-compressed.png"></div>
			<div class="col span_10_of_12">
				'.$Reply_Post.'
			</div>
		</div>'; // TODO Gravatars, Markdown Post
			}
		}

		require '../../footer.php';
	}
} ?>
