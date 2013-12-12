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

		$Topic_Fetch = mysqli_fetch_assoc($Topic_Check);
		$Topic_Status = $Topic_Fetch['Status'];
		$Topic_Title = $Topic_Fetch['Title'];
		$Topic_Description = $Topic_Fetch['Description'];
		$Topic_Created = $Topic_Fetch['Created'];
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
		$Description = ''; // TODO Add Topic Description
		$Keywords = 'topic forum'; // TODO Add Topic Keywords


		require '../../header.php';

		echo '
		<h2>'.$Topic_Title.'</h2>
		'.$Topic_Description;
		// TODO List Topic Posts
		echo '
		<div class="section group darkrow">
			<div class="col span_2_of_12 textcenter"><p>Lewis Goddard</p></div>
			<div class="col span_10_of_12  faded"><p>22 Oct, 2013 22:15</p></div>
		</div>
		<div class="section group reply">
			<div class="col span_2_of_12"><img class="avatar" src="http://lewisgoddard.eustasy.org/images/faces/circular-blue-small-cropped-compressed.png"></div>
			<div class="col span_10_of_12">
				<p>Lorem ipsum dolor sit amet, <a href="#">test link</a> adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p>
			</div>
		</div>
		<div class="section group darkrow">
			<div class="col span_2_of_12 textcenter"><p>Lewis Goddard</p></div>
			<div class="col span_10_of_12 faded"><p>22 Oct, 2013 22:15</p></div>
		</div>
		<div class="section group reply">
			<div class="col span_2_of_12"><img class="avatar" src="http://lewisgoddard.eustasy.org/images/faces/circular-red-small-compressed.png"></div>
			<div class="col span_10_of_12">
				<p>Lorem ipsum dolor sit amet, emphasis consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p>
			</div>
		</div>';

		require '../../footer.php';
	}
} ?>
