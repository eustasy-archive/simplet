<?php

// ### Forum Topics Function ###
//
// Echos the Forum Topics available to the user.
//
// Forum_Topics();

function Forum_Topics() {

	// Set some Globals
	global $MySQL_Connection, $Member_Auth, $Header, $Footer, $Sitewide_Title, $Sitewide_Root;

	// Category to get Topic for
	$Forum_Topics_Category_Slug = htmlentities($_GET['category'], ENT_QUOTES, 'UTF-8');

	$Forum_Topics_Category_Info = Forum_Category_Info($Forum_Topics_Category_Slug);

	if ($Forum_Topics_Category_Info === false) {
		require $Header;
		if ($Member_Auth) echo '
			<h2>There is no such Category: "'.$Forum_Topics_Category_Slug.'".</h2>
			<p><a href="?category=">Return to the Forum Index</a></p>';
		else echo '
			<h2>There is no such public Category: "'.$Forum_Topics_Category_Slug.'".</h2>
			<p><a href="?category=">Return to the Forum Index</a></p>';
		require $Footer;
	} else {

		//
		$Category_Title = $Forum_Topics_Category_Info['Title'];
		$Category_Description = $Forum_Topics_Category_Info['Description'];

		$Title_HTML = $Category_Title;
		$Title_Plain = $Category_Title;
		$Description_HTML = $Category_Description;
		$Description_Plain = $Category_Description;
		$Canonical = '?category='.$Forum_Topics_Category_Slug;
		$Post_Type = 'Forum Category';
		$Featured_Image = '';
		$Keywords = $Category_Title.' category topics forum '.$Category_Description;

		ViewCount();

		require $Header;

		// Select Topics
		$Topics_Query_Select = 'SELECT * FROM `Topics` WHERE `Category`=\''.$Forum_Topics_Category_Slug.'\' AND';

		// Limit by Status
		if ($Member_Auth) $Topics_Query_Status = ' (`Status`=\'Public\' OR `Status`=\'Private\')';
		else $Topics_Query_Status = ' `Status`=\'Public\'';

		// Order by Creation
		$Topics_Query_Order = ' ORDER BY `Modified` DESC';

		// Build Topics_Query
		$Topics_Query = $Topics_Query_Select.$Topics_Query_Status.$Topics_Query_Order;

		// Get Topics
		$Topics = mysqli_query($MySQL_Connection, $Topics_Query, MYSQLI_STORE_RESULT);
		if (!$Topics) exit('Invalid Query (Category_Check): '.mysqli_error($MySQL_Connection));

		$Topics_Count = mysqli_num_rows($Topics);
		if ($Topics_Count == 0) {
			if ($Member_Auth) echo '<h3 class="textleft">There are no Topics in the Category '.$Category_Title.' <a class="floatright" href="?new&category='.$Forum_Topics_Category_Slug.'">New Topic</a></h3>';
			else echo '<h3>There are no Public Topics in the Category '.$Category_Title.'</h3>';
		} else {

			$Responses_Prefetch_Query_Select = 'SELECT `Canonical`, MAX(`Modified`) AS `Modified`, COUNT(*) AS `Count` FROM `Responses` WHERE `Type`=\'Post\' AND ';
			$Responses_Prefetch_Query_Group = ' GROUP BY `Canonical`';
			$Responses_Prefetch_Query = $Responses_Prefetch_Query_Select.$Topics_Query_Status.$Responses_Prefetch_Query_Group;
			$Responses_Prefetch = mysqli_query($MySQL_Connection, $Responses_Prefetch_Query, MYSQLI_STORE_RESULT);
			if (!$Responses_Prefetch) exit('Invalid Query (Responses_Prefetch): '.mysqli_error($MySQL_Connection));

			$Responses_Prefetch_Count = array();
			$Responses_Prefetch_Modified = array();

			while($Responses_Prefetch_Fetch = mysqli_fetch_assoc($Responses_Prefetch)) {
				$Responses_Prefetch_Count[$Responses_Prefetch_Fetch['Canonical']] = $Responses_Prefetch_Fetch['Count'];
				$Responses_Prefetch_Modified[$Responses_Prefetch_Fetch['Canonical']] = $Responses_Prefetch_Fetch['Modified'];
			}

			$Pagination = Pagination_Pre($Topics_Count);

			echo '
				<h2>'.$Category_Title.'</h2>
				<p>'.$Category_Description;
			if ($Member_Auth) echo '<a class="floatright" href="?new&category='.$Forum_Topics_Category_Slug.'">New Topic</a>';
			echo '</p>
				<div id="topics">
					<div class="section group darkrow">
						<div class="col span_1_of_12"><br></div>
						<div class="col span_7_of_12"><p>Topic</p></div>
						<div class="col span_2_of_12 textcenter faded"><p>Replies</p></div>
						<div class="col span_2_of_12 textcenter faded"><p>Last Activity</p></div>
					</div>';

			$Topics_Query_Select = 'SELECT * FROM `Topics` WHERE `Category`=\''.$Forum_Topics_Category_Slug.'\' AND';
			if ($Pagination['Page'] === 1) $Topics_Query_Limit = ' LIMIT '.$Pagination['Show'];
			else $Topics_Query_Limit = ' LIMIT '.$Pagination['Start'].', '.$Pagination['Show'];

			$Topics_Query = $Topics_Query_Select.$Topics_Query_Status.$Topics_Query_Order.$Topics_Query_Limit;

			$Topics = mysqli_query($MySQL_Connection, $Topics_Query, MYSQLI_STORE_RESULT);
			if (!$Topics) exit('Invalid Query (Topics): '.mysqli_error($MySQL_Connection));

			while($Topics_Fetch = mysqli_fetch_assoc($Topics)) {
				$Topics_Status = $Topics_Fetch['Status'];

				if ($Topics_Status == 'Public' || $Topics_Status == 'Locked' || $Topics_Status == 'Private' && $Member_Auth) {

					$Topics_Slug = $Topics_Fetch['Slug'];
					$Topics_Modified = $Topics_Fetch['Modified']; // TODO Use $Topics_Modified and Cookies to label Unread/Read
					$Topics_Title = html_entity_decode($Topics_Fetch['Title'], ENT_QUOTES, 'UTF-8');

					echo '
					<a href="?topic='.$Topics_Slug.'" class="section group topic';
					if ($Topics_Status == 'Private') echo ' private';
					echo '">
						<div class="col span_1_of_12"><li class="icon unread"></li></div>
						<div class="col span_7_of_12"><p class="title">'.$Topics_Title.'</p></div>
						<div class="col span_2_of_12 textcenter"><p><span>';
					if (isset($Responses_Prefetch_Count[$Topics_Slug])) echo $Responses_Prefetch_Count[$Topics_Slug];
					else echo '0';
					echo '<span></p></div>
						<div class="col span_2_of_12 textcenter"><p><span>';
					if (isset($Responses_Prefetch_Modified[$Topics_Slug]) && $Responses_Prefetch_Modified[$Topics_Slug] > $Topics_Modified) echo date('d M, Y', $Responses_Prefetch_Modified[$Topics_Slug]);
					else echo date('d M, Y', $Topics_Modified);
					echo '</span></p></div>
					</a>';

				}
			}

			// Preserve Query Strings
			$PreserveQueryStrings = Pagination_PreserveQueryStrings();

			// Paginate if necessary
			if ($Pagination['Page Max'] > 1) {
				echo '<div class="breaker"></div>';
				// TODO Why do these arrays need to be passed?
				// Global doesn't seem to allow them
				Pagination_Links($Pagination, $PreserveQueryStrings);
			}

			echo '
			</div>';

		}
		require $Footer;
	}
}
