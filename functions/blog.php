<?php

////	Blog Function
//
// Echo out titles and descriptions of posts in the directory
// this is called from. Should be called as
// Blog($Caller, $Category, $Posts_PerLine);
// Where $Caller is the file that would cause recursion,
// and $Category is a Category or false.
// $Posts_PerLine should be an integer: 1, 2, or 3.
//
// Blog(__DIR__, $Category);
// Blog(__DIR__, $Category, 1);

function Blog($Caller, $Category = false, $Posts_PerLine = 2, $Show = 10) {

	global $Sitewide_Root, $Place, $Request;
	
	// Start a section for the Blog to appear in.
	echo '
		<div class="section group posts">';

	// Make an empty array to append to.
	$Posts_Return = array();
	
	// Set Looper to 0 so it can be incremented later.
	$Looper = 0;

	// List all the files
	$Posts_Return = glob('*.php', GLOB_NOSORT);

	// FORITEM
	foreach ($Posts_Return as $Key => $Item) {

		// IFCALLER If it is the page being loaded (stops require recursion).
		if ($Item == $Caller) unset($Posts_Return[$Key]);
		else {

			// Require the Item.
			require $Item;

			// IFPOST If it is a Blog Post.
			if ($Post_Type == 'Blog Post') {

				// IFCHECKCATEGORY If no category or category matches
				if ( $Category && ( $Category != $Post_Category ) ) {
				
					// Remove from the List.
					unset($Posts_Return[$Key]);
					
				} // IFCHECKCATEGORY

			// If it isn't a Blog Post, remove it from the list.
			} else unset($Posts_Return[$Key]);
			// IFPOST

			// Set Post Type to Invalid to override posts or files with one.
			$Post_Type = 'INVALID';

		} // IFCALLER

	} // FORITEM

	// Stop Page being further than possible
	// (Show the last if it's over, first if negative)
	$Posts_Count = count($Posts_Return);

	// Prepare Pagination.
	$Pagination = Pagination_Pre($Posts_Count, $Show);

	// Order them by time
	array_multisort(array_map('filemtime', $Posts_Return), SORT_NUMERIC, SORT_DESC, $Posts_Return);

	// Only get the part of the list of posts for this page.
	$Posts_Return = array_slice($Posts_Return, $Pagination['Start'], $Pagination['Show'], true);
	
	// NOTE: The last three blocks could be re-written to stop looping once there are enough posts and we're sure they are posts.
	// This would reduce IO and speed things up considerably for larger blogs.

	// FOREACH: For each Item
	foreach ($Posts_Return as $Item) {

		// Require it
		require $Item;

		// Make the link
		$Post_Link = $Sitewide_Root.$Canonical;

		// Echo out the Item
		if ($Posts_PerLine == 1) echo '
			<div class="col span_1_of_1">';
		if ($Posts_PerLine == 2) echo '
			<div class="col span_5_of_11">';
		if ($Posts_PerLine == 3) echo '
			<div class="col span_3_of_11">';
		echo '
				<h2><a href="'.$Post_Link.'">' . $Title_HTML . '</a></h2>
				<p class="textright faded"><small>' . date ('d/m/Y', filemtime($Item)) .'</small></p>
				<p>' . $Description_HTML . '</p>
			</div>';

		// Increment Looper and echo a break where needed.
		$Looper += 1;
		if (is_int($Looper/$Posts_PerLine)) {
			echo '
		</div>
		<div class="breaker"></div>
		<div class="section group">';
		} else {
			echo '
			<div class="col span_1_of_11"><br></div>';
		}

	} // FOREACH

	// Preserve Query Strings
	$PreserveQueryStrings = Pagination_PreserveQueryStrings();

	// Paginate if necessary
	if ($Pagination['Page Max'] > 1) {
		echo '<div class="breaker"></div>';
		Pagination_Links($Pagination, $PreserveQueryStrings);
	}

	// IFNOPOSTS
	if ($Looper === 0) {
		// IFNOPOSTSCATEGORY
		if ($Category) echo '<h2>Sorry, no posts found in the Category &ldquo;'.$Category.'&rdquo;.</h2>';
		else echo '<h2>Sorry, no posts found.</h2>';
		// IFNOPOSTSCATEGORY
		echo '
		</div>';
		return false;
	} // IFNOPOSTS

	// End Blog Section.
	echo '
		</div>';

	return true;

}
