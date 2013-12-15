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

	if(isset($_GET['category']) && !empty($_GET['category'])) {

		$Category_Slug = trim(htmlentities($_GET['category'], ENT_QUOTES, 'UTF-8'));

		if($Member_Auth) {
			$Categories = mysqli_query($MySQL_Connection, "SELECT * FROM `Categories` WHERE NOT `Status`='Hidden' AND `Slug`='$Category_Slug' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
		} else {
			$Categories = mysqli_query($MySQL_Connection, "SELECT * FROM `Categories` WHERE NOT `Status`='Hidden' AND NOT `Status`='Private' AND `Slug`='$Category_Slug' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
		}

		if (!$Categories) exit('Invalid Query (Categories): '.mysqli_error($MySQL_Connection));

		$Categories_Count = mysqli_num_rows($Categories);
		if ($Categories_Count == 0) {
			if($Member_Auth) {
				require '../../header.php';
				echo '<h3>There is no such Category: "'.$Category_Slug.'".</h3>';
				require '../../footer.php';
			} else {
				require '../../header.php';
				echo '<h3>There is no such public Category: "'.$Category_Slug.'".</h3>';
				require '../../footer.php';
			}
		} else {

			$Category_Fetch = mysqli_fetch_assoc($Categories);
			$Category_Title = $Category_Fetch['Title'];
			$Category_Description = $Category_Fetch['Description'];

			$TextTitle = $Category_Title;
			$WebTitle = $Category_Title.' &nbsp;&middot;&nbsp; Forum';
			$Canonical = 'forum/?category='.$Category_Slug;
			$PostType = 'Forum';
			$FeaturedImage = '';
			$Description = $Category_Description;
			$Keywords = $Category_Title.' category topics forum '.$Category_Description;

			require '../../header.php';

			if($Member_Auth) {
				$Topics = mysqli_query($MySQL_Connection, "SELECT * FROM `Topics` WHERE NOT `Status`='Hidden' AND `Category`='$Category_Slug' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
			} else {
				$Topics = mysqli_query($MySQL_Connection, "SELECT * FROM `Topics` WHERE NOT `Status`='Hidden' AND NOT `Status`='Private' AND `Category`='$Category_Slug' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
			}

			if (!$Topics) exit('Invalid Query (Topics): '.mysqli_error($MySQL_Connection));

			$Topics_Count = mysqli_num_rows($Topics);
			if ($Topics_Count == 0) {
				if($Member_Auth) {
					echo '<h3>There are no Topics in the Category '.$Category_Title.'.</h3>';
				} else {
					echo '<h3>There are no Public Topics in the Category '.$Category_Title.'.</h3>';
				}
			} else {

				require '../../parsedown.php';

				echo '
				<h2>'.$Category_Title.'</h2>';

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
					$Topics_Created = date('d M, Y', $Topics_Fetch['Created']);
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

	} else {

		require '../../header.php';

		echo '
		<h2>Forum</h2>';

		if($Member_Auth) {
			$Categories = mysqli_query($MySQL_Connection, "SELECT * FROM `Categories` WHERE NOT `Status`='Hidden' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
		} else {
			$Categories = mysqli_query($MySQL_Connection, "SELECT * FROM `Categories` WHERE NOT `Status`='Hidden' AND NOT `Status`='Private' ORDER BY `Created` DESC", MYSQLI_STORE_RESULT);
		}

		if (!$Categories) exit('Invalid Query (Categories): '.mysqli_error($MySQL_Connection));

		$Categories_Count = mysqli_num_rows($Categories);
		if ($Categories_Count == 0) {
			if($Member_Auth) {
				echo '
				<h3>There are no Categories.</h3>';
			} else {
				echo '
				<h3>There are no public Categories.</h3>';
			}
		} else {

			echo '
				<div class="section group darkrow">
					<div class="col span_1_of_12"><br></div>
					<div class="col span_7_of_12"><p>Category</p></div>
					<div class="col span_2_of_12 textcenter faded"><p>Topics</p></div>
					<div class="col span_2_of_12 textcenter faded"><p>Most Recent</p></div>
				</div>';

			while($Category_Fetch = mysqli_fetch_assoc($Categories)) {

				$Category_Slug = $Category_Fetch['Slug'];
				$Category_Title = html_entity_decode($Category_Fetch['Title'], ENT_QUOTES, 'UTF-8');
				$Category_Description = html_entity_decode($Category_Fetch['Description'], ENT_QUOTES, 'UTF-8');
				$Category_Status = $Category_Fetch['Status'];

				if($Category_Status == 'Public') {
					echo '
				<a href="?category='.$Category_Slug.'" class="section group topic">
					<div class="col span_1_of_12"><li class="icon unread"></li></div>
					<div class="col span_7_of_12">
						<p class="title">'.$Category_Title.'</p>
						<p>'.$Category_Description.'</p>
					</div>
					<div class="col span_2_of_12 textcenter"><p><span>14<span></p></div>
					<div class="col span_2_of_12 textcenter"><p><span>11 Dec, 2014</span></p></div>
				</a>'; // TODO Unread/Read, Topics Count, Most Recent

				} else if($Category_Status == 'Private' && $Member_Auth) {
					echo '
				<a href="?category='.$Category_Slug.'" class="section group topic">
					<div class="col span_1_of_12"><li class="icon unread"></li></div>
					<div class="col span_7_of_12">
						<p class="title">'.$Category_Title.'</p>
						<p>'.$Category_Description.'</p>
					</div>
					<div class="col span_2_of_12 textcenter"><p><span>14<span></p></div>
					<div class="col span_2_of_12 textcenter"><p><span>11 Dec, 2014</span></p></div>
				</a>'; // TODO Unread/Read, Topics Count, Most Recent
				}
			}
		}

		require '../../footer.php';

	}

}
