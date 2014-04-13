<?php

// ### Forum Categories Function ###
//
// Echos the Forum Categories available to the user.
//
// Forum_Categories();

function Forum_Categories() {

	// Set some Globals
	global $MySQL_Connection, $Member_Auth;

	// Count things first
	$Categories_Query_Select = 'SELECT COUNT(`Slug`) AS `Count` FROM `Categories` WHERE';

	// Limit by Status
	if ($Member_Auth) $Categories_Query_Status = ' (`Status`=\'Public\' OR `Status`=\'Private\')';
	else $Categories_Query_Status = ' `Status`=\'Public\'';

	// Order by Creation
	$Categories_Query_Order = ' ORDER BY `Created` DESC';

	// Build Responses_Query
	$Categories_Query = $Categories_Query_Select.$Categories_Query_Status.$Categories_Query_Order;

	// Get Responses
	$Categories = mysqli_query($MySQL_Connection, $Categories_Query, MYSQLI_STORE_RESULT);
	if (!$Categories) exit('Invalid Query (Categories): '.mysqli_error($MySQL_Connection));

	$Categories_Fetch = mysqli_fetch_assoc($Categories);
	$Categories_Count = $Categories_Fetch['Count'];

	if ($Categories_Count == 0) {
		if ($Member_Auth) echo '
			<h3>There are no Categories.</h3>';
		else echo '
			<h3>There are no public Categories.</h3>';

	} else {
		echo '
		<div id="categories">
			<div class="section group darkrow">
				<div class="col span_1_of_12"><br></div>
				<div class="col span_7_of_12"><p>Category</p></div>
				<div class="col span_2_of_12 textcenter faded"><p>Topics</p></div>
				<div class="col span_2_of_12 textcenter faded"><p>Most Recent</p></div>
			</div>';

		$Pagination = Pagination_Pre($Categories_Count);

		$Categories_Query_Select = 'SELECT * FROM `Categories` WHERE';
		if ($Pagination['Page'] === 1) $Categories_Query_Limit = ' LIMIT '.$Pagination['Show'];
		else $Categories_Query_Limit = ' LIMIT '.$Pagination['Start'].', '.$Pagination['Show'];

		$Categories_Query = $Categories_Query_Select.$Categories_Query_Status.$Categories_Query_Order.$Categories_Query_Limit;

		// Get Categories
		$Categories = mysqli_query($MySQL_Connection, $Categories_Query, MYSQLI_STORE_RESULT);
		if (!$Categories) exit('Invalid Query (Categories): '.mysqli_error($MySQL_Connection));

		while($Category_Fetch = mysqli_fetch_assoc($Categories)) {
			$Category_Status = $Category_Fetch['Status'];
			// TODO Unread/Read, Most Recent
			// Both will probably require changing the topics modified time for every reply.
			// Another option is a new field called something more descriptive like `Last Activity`
			// This should not change the modified time.

			if ($Category_Status == 'Public' || ( $Category_Status == 'Private' && $Member_Auth ) ) {
				echo '
				<a href="?category='.$Category_Fetch['Slug'].'" class="section group category topic';
				if ($Category_Status == 'Private') echo ' private';
				echo '">
					<div class="col span_1_of_12"><li class="icon unread"></li></div>
					<div class="col span_7_of_12">
						<p class="title">'.$Category_Fetch['Title'].'</p>
						<p>'.$Category_Fetch['Description'].'</p>
					</div>
					<div class="col span_2_of_12 textcenter"><p><span>'.number_format($Category_Fetch['Topics']).'<span></p></div>
					<div class="col span_2_of_12 textcenter"><p><span>'.date('d M, Y', $Category_Fetch['Modified']).'</span></p></div>
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
			echo '<div class="breaker"></div>';
		}

		echo '
			</div>';
	}

}
