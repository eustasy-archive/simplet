<?php

// ### Trending Function ###
//
//

function Trending($Canonical, $Trend_Type = 'Post', $Trend_Limit = 10, $Trend_Strict = false) {

	// Set some Globals
	global $MySQL_Connection, $Sitewide_Root;

	if (!isset($Canonical) || empty($Canonical)) return array();

	// Make sure $Trend_Type is sensible
	if ($Trend_Type != 'Post' && $Trend_Type != 'Page' && $Trend_Type != 'Forum Topic' && $Trend_Type != 'Forum Category' && $Trend_Type != 'Blog Category' && $Trend_Type != 'Blog Index') $Trend_Type = 'Post';

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
		$Trending_Return[htmlentities($Trending_Fetch['Canonical'], ENT_QUOTES, 'UTF-8')] = $Trending_Fetch['Count'];
	}

	// Unset current page to avoid require problems
	unset($Trending_Return[$Canonical]);

	// Return Array
	return $Trending_Return;

}
