<?php

////	Trending Function
// TODO Description

// WARNING:
// A high Trend_Limit coupled with Trend_Strict
// will result in a large and complex query.

function Views_Trending($Canonical, $Trend_Type = 'Blog Post', $Trend_Limit = 10, $Trend_Strict = false) {

	global $Database, $Sitewide_Root, $Post_Types, $Sitewide_Debug;

	// Make sure $Trend_Type is sensible
	if ( !in_array($Trend_Type, $Post_Types) ) $Trend_Type = 'Blog Post';

	// Assemble query based on Strict setting.
	if ( $Trend_Strict ) $Query = 'SELECT COUNT(DISTINCT `Member_ID`) AS `Count`,';
	else $Query = 'SELECT COUNT(*) AS `Count`,';
	
	// Finish query.
	$Query .= ' `Canonical` FROM `Views` WHERE `Post_Type`=\''.$Trend_Type.'\' GROUP BY `Canonical` ORDER BY `Count` DESC LIMIT 0, '.$Trend_Limit;

	$Trending = mysqli_query($Database['Connection'], $Query, MYSQLI_STORE_RESULT);
	if ( !$Trending && $Sitewide_Debug ) echo 'Invalid Query (View): '.mysqli_error($Database['Connection']);

	// Set an empty array to append to.
	$Trending_Return = array();

	// Append each item to the array.
	while ( $Trending_Fetch = mysqli_fetch_assoc($Trending) ) $Trending_Return[$Trending_Fetch['Canonical']] = $Trending_Fetch['Count'];

	// Unset current page to avoid require problems.
	unset($Trending_Return[$Canonical]);

	return $Trending_Return;

}