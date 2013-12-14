<?php

	$TextTitle = 'Forum';
	$WebTitle = 'Forum';
	$Canonical = 'forum/';
	$PostType = 'Forum';
	$FeaturedImage = '';
	$Description = '';
	$Keywords = 'forum';

	require_once '../../request.php';

if (htmlentities($Request['path'], ENT_QUOTES, 'UTF-8') == '/' . $Canonical) {

	require '../../header.php';

	echo '
	<h2>Forum</h2>';

	if($Member_Auth) {
		$Topics = mysqli_query($MySQL_Connection, "SELECT * FROM `Topics` WHERE NOT `Status`='Hidden'", MYSQLI_STORE_RESULT);
	} else {
		$Topics = mysqli_query($MySQL_Connection, "SELECT * FROM `Topics` WHERE NOT `Status`='Hidden' AND NOT `Status`='Private'", MYSQLI_STORE_RESULT);
	}

	if (!$Topics) exit('Invalid Query (Topics): '.mysqli_error($MySQL_Connection));

	$Topics_Count = mysqli_num_rows($Topics);
	if ($Topics_Count == 0) {
		if($Member_Auth) {
			echo '<h3>No topics.</h3>';
		} else {
			echo '<h3>No public topics.</h3>';
		}
	} else {

		require '../../parsedown.php';

		echo '
		<div class="section group darkrow">
			<div class="col span_1_of_12"><br></div>
			<div class="col span_7_of_12"><p>Topic</p></div>
			<div class="col span_2_of_12 textcenter faded"><p>Replies</p></div>
			<div class="col span_2_of_12 textcenter faded"><p>Posted</p></div>
		</div>';

		while($Topics_Fetch = mysqli_fetch_assoc($Topics)) {

			$Topics_ID = $Topics_Fetch['ID'];
			$Topics_Status = $Topics_Fetch['Status'];
			$Topics_Title = html_entity_decode($Topics_Fetch['Title'], ENT_QUOTES, 'UTF-8');
			$Topics_Created = date('d M, Y H:i', $Topics_Fetch['Created']);
			$Topics_Modified = $Topics_Fetch['Modified'];

			if($Topics_Status == 'Public') {
				echo '
		<a href="topic?topic='.$Topics_ID.'" class="section group topic">
			<div class="col span_1_of_12"><li class="icon unread"></li></div>
			<div class="col span_7_of_12"><p class="title">'.$Topics_Title.'</p></div>
			<div class="col span_2_of_12 textcenter"><p><span>14<span></p></div>
			<div class="col span_2_of_12 textcenter"><p><span>'.$Topics_Created.'</span></p></div>
		</a>'; // TODO Unread/Read, Reply Count

			} else if($Topics_Status == 'Private' && $Member_Auth) {
				echo '
		<a href="topic?topic='.$Topics_ID.'" class="section group topic private">
			<div class="col span_1_of_12"><li class="icon unread"></li></div>
			<div class="col span_7_of_12"><p class="title">'.$Topics_Title.'</p></div>
			<div class="col span_2_of_12 textcenter"><p><span>14<span></p></div>
			<div class="col span_2_of_12 textcenter"><p><span>'.$Topics_Created.'</span></p></div>
		</a>'; // TODO Unread/Read, Reply Count
			}

		}
	}


	require '../../footer.php';

}
