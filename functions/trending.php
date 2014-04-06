<?php

// ### Trending Function ###
//
//

function Trending($Canonical, $Trend_Type = 'Blog Post', $Trend_Limit = 10, $Trend_Strict = false) {

	// Set some Globals
	global $MySQL_Connection, $Sitewide_Root, $Post_Types;

	// Make sure $Trend_Type is sensible
	if (!in_array($Trend_Type, $Post_Types)) $Trend_Type = 'Blog Post';

	// Build Query
	if ($Trend_Strict) $Query = 'SELECT COUNT(DISTINCT `Member_ID`) AS `Count`,';
	else $Query = 'SELECT COUNT(*) AS `Count`,';
	$Query .= ' `Canonical` FROM `Views` WHERE `Post_Type`=\''.$Trend_Type.'\' GROUP BY `Canonical` ORDER BY `Count` DESC LIMIT 0, '.$Trend_Limit;

	// Run the Query
	$Trending = mysqli_query($MySQL_Connection, $Query, MYSQLI_STORE_RESULT);
	if (!$Trending) exit('Invalid Query (View): '.mysqli_error($MySQL_Connection));

	// Set an array
	$Trending_Return = array();

	// Add each item.
	while($Trending_Fetch = mysqli_fetch_assoc($Trending)) {
		$Trending_Return[$Trending_Fetch['Canonical']] = $Trending_Fetch['Count'];
	}

	// Unset current page to avoid require problems
	unset($Trending_Return[$Canonical]);

	// Return Array
	return $Trending_Return;

}
