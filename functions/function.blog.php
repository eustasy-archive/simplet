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
// Blog(__DIR__, $Category);
// Blog(__DIR__, $Category, 1);

function Blog($Caller, $Category = false, $PerLine = 2, $Show = 10, $Page = 1) {

	// Set some Globals so the required scripts don't error.
	global $Sitewide_Root, $Place, $Request;

	echo '
		<div class="section group posts">';

	// Make an empty array
	$Posts_Return = array();

	// Set Looper to 0
	$Looper = 0;

	// List all the files
	$Posts_Return = glob('*.php', GLOB_NOSORT);

	// FORITEM
	foreach ($Posts_Return as $Key => $Item) {

		// IFCALLER
		if ($Item == $Caller) unset($Posts_Return[$Key]);
		else {

			// Require it
			require $Item;

			// IFPOST If it is a post (and hence has a time)
			if ($Post_Type == 'Blog Post') {

				// IFCHECKCATEGORY If no category or category matches
				if ($Category && ( $Category != $Post_Category )) {

					unset($Posts_Return[$Key]);

				} // IFCHECKCATEGORY

			} else unset($Posts_Return[$Key]);
			// IFPOST

		} // IFCALLER

	} // FORITEM

	// PAGINATION

	// Fetch Values
	if (isset($_GET['page'])) $Page = intval($_GET['page']);
	if (isset($_GET['show'])) $Show = intval($_GET['show']);

	// Sanitize Values
	if ($Show > 100) $Show = 100;
	if ($Show < 1) $Show = 10;
	if ($Page < 1) $Page = 1;

	// Stop Page being further than possible
	// (Show the last if it's over, first if negative)
	$Posts_Count = count($Posts_Return);
	$Page_Max = ceil($Posts_Count/$Show);

	// Honor pagination
	if ($Page === 1) {
		$Show_Start = 0;
	} else {
		if ($Page > $Page_Max) {
			if ($Page_Max < 1) {
				$Page = 1;
			} else {
				$Page = $Page_Max;
			}
		}
		$Show_Start = ($Page-1)*$Show;
	}
	$Posts_Return = array_slice($Posts_Return, $Show_Start, $Show, true);

	// Order them by time
	array_multisort(array_map('filemtime', $Posts_Return), SORT_NUMERIC, SORT_DESC, $Posts_Return);

	// FOREACH: For each Item
	foreach ($Posts_Return as $Item) {

		// Require it
		require $Item;

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

	} // FOREACH

	// Preserve Query Stings
	$Preserve_Query_Strings = '';
	$Preserve_Pagination = '';
	if (isset($_GET)) {
		foreach($_GET as $Get_Key => $Get_Value) {
			// Ignore old page and show variables
			if (strtolower($Get_Key) != 'page' && strtolower($Get_Key) != 'show' && strtolower($Get_Key) != 'topic') {
				$Preserve_Query_Strings .= '&'.$Get_Key.'='.$Get_Value;
			} else if (strtolower($Get_Key) == 'topic') {
				// Preserve Topic if Necessary
				if (substr($Get_Value, 0, 1) != '/') $Preserve_Query_Strings .= '&'.$Get_Key.'='.$Get_Value;
			} else {
				$Preserve_Pagination .= '&'.$Get_Key.'='.$Get_Value;
			}
		}
	}

	// Pagination Links
	if ($Page_Max != 1) {

		$Page_Wayback = $Page-2;
		$Page_Previous = $Page-1;
		$Page_Next = $Page+1;
		$Page_Far = $Page+2;

		echo '
		<div class="clear breaker"></div>
		<p class="textcenter">';

		$Paginate_End = '&show='.$Show.$Preserve_Query_Strings;

		if ($Page > 3) echo '<span class="floatleft"><a href="?page=1'.$Paginate_End.'">1</a> &emsp; &hellip; &emsp; </span>';

		if ($Page >= 3) echo '<a href="?page='.$Page_Wayback.$Paginate_End.'">'.$Page_Wayback.'</a> &emsp; ';
		if ($Page >= 2) echo '<a href="?page='.$Page_Previous.$Paginate_End.'">'.$Page_Previous.'</a> &emsp; ';

		echo $Page;

		if ($Page_Next <= $Page_Max) echo ' &emsp; <a href="?page='.$Page_Next.$Paginate_End.'">'.$Page_Next.'</a>';
		if ($Page_Far <= $Page_Max) echo ' &emsp; <a href="?page='.$Page_Far.$Paginate_End.'">'.$Page_Far.'</a>';

		if ($Page_Far < $Page_Max) echo '<span class="floatright"> &emsp; &hellip; &emsp; <a href="?page='.$Page_Max.$Paginate_End.'">'.$Page_Max.'</a></span>';

		echo '</p>
		<div class="clear breaker"></div>';

	}

	// IFNOPOSTS
	if ($Looper === 0) {
		// IFNOPOSTSCATEGORY
		if ($Category) {
			echo '<h2>Sorry, no posts found in the Category &ldquo;'.$Category.'&rdquo;.</h2>';
		} else {
			echo '<h2>Sorry, no posts found.</h2>';
		} // IFNOPOSTSCATEGORY
		echo '
		</div>';
		return false;
	} // IFNOPOSTS

	echo '
		</div>';
	return true;

}
