<?php

// ### Blog Function ###
//
// Echo out titles and descriptions of posts in the directory
// this is called from. Should be called as
// Blog($Caller, $Category, $PerLine);
// Where $Caller is the file that would cause recursion,
// and $Category is a Category or false.
// $PerLine should be an integer: 1, 2, or 3.
//
// Blog(basename(__FILE__), $Category);
// Blog(basename(__FILE__), $Category, 1);

function Blog($Caller, $Category = false, $PerLine = 2) {

	// Set some Globals so the required scripts don't error.
	global $Sitewide_Root, $Place, $Request_Path_Entities;

	// Make an empty array
	$Posts_Return = array();

	// Set Looper to 0
	$Looper = 0;

	// List all the files
	$Items = glob('*.php', GLOB_NOSORT);

	// Order them by time
	array_multisort(array_map('filemtime', $Items), SORT_NUMERIC, SORT_DESC, $Items);

	// FOREACH: For each Item
	foreach ($Items as $Item) {

		// IFNOTTHIS: So long as it isn't this file
		if ($Item != $Caller) {

			// Require it
			require $Item;

			// IFPOST If it is a post (and hence has a time)
			if ($Post_Type == 'Blog Post') {

				// IFCHECKCATEGORY If no category or category matches
				if (!$Category || ( $Category == $Post_Category )) {

					// Make the link
					$Post_Link = $Sitewide_Root.$Canonical;

					// Echo out the Item
					if ($PerLine == 1) echo '
			<div class="col span_1_of_1">';
					if ($PerLine == 2) echo '
			<div class="col span_5_of_11">';
					if ($PerLine == 3) echo '
			<div class="col span_3_of_11">';
					echo '
				<h2><a href="'.$Post_Link.'">' . $Title_HTML . '</a></h2>
				<p class="textright faded"><small>' . date ('d/m/Y', filemtime($Item)) .'</small></p>
				<p>' . $Description_HTML . '</p>
			</div>';

					// Increment Looper and echo a break every other post.
					$Looper += 1;
					if (is_int($Looper/$PerLine)) {
						echo '
		</div>
		<div class="breaker"></div>
		<div class="section group">';
					} else {
						echo '
			<div class="col span_1_of_11"><br></div>';
					}

				} // IFCHECKCATEGORY

			} // IFPOST

		} // IFNOTTHIS

	} // FOREACH

	// IFNOPOSTS
	if ($Looper === 0) {
		// IFNOPOSTSCATEGORY
		if ($Category) {
			echo '<h2>Sorry, no posts found in the Category &ldquo;'.$Category.'&rdquo;.</h2>';
		} else {
			echo '<h2>Sorry, no posts found.</h2>';
		} // IFNOPOSTSCATEGORY
		return false;
	} // IFNOPOSTS

	// Return Array
	return true;

}
