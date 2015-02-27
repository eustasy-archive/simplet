<?php

////	Views Trending Function
//
// Returns an array of the current trending pages, minus the current one.
//
// WARNING:
// A high Trend_Limit coupled with Trend_Strict
// will result in a large and complex query.
//
// Views_Trending('page');

function Views_Trending($Canonical, $Trend_Type = 'Blog Post', $Trend_Limit = 10, $Trend_Strict = false) {

	global $Database, $Post_Types, $Sitewide_Debug, $Sitewide_Root;

	// IFEXISTSVIEWS
	if ( $Database['Exists']['Views'] ) {

		// Make sure $Trend_Type is sensible
		if ( !in_array($Trend_Type, $Post_Types) ) {
			$Trend_Type = 'Blog Post';
		}

		// Assemble query based on Strict setting.
		if ( $Trend_Strict ) $Query = 'SELECT COUNT(DISTINCT `Member_ID`) AS `Count`,';
		else $Query = 'SELECT COUNT(*) AS `Count`,';

		// Finish query.
		$Query .= ' `Canonical` FROM `'.$Database['Prefix'].'Views` WHERE `Post_Type`=\''.$Trend_Type.'\' GROUP BY `Canonical` ORDER BY `Count` DESC LIMIT 0, '.$Trend_Limit;

		$Trending = mysqli_query($Database['Connection'], $Query, MYSQLI_STORE_RESULT);
		if (
			!$Trending &&
			$Sitewide['Debug']
		) {
			return array('Error' => 'Invalid Query (Views_Trending): '.mysqli_error($Database['Connection']));
		}

		// Set an empty array to append to.
		$Trending_Return = array();

		// Append each item to the array.
		while ( $Trending_Fetch = mysqli_fetch_assoc($Trending) ) {
			$Trending_Return[$Trending_Fetch['Canonical']] = $Trending_Fetch['Count'];
		}

		// Unset current page to avoid require problems.
		unset($Trending_Return[$Canonical]);

		return $Trending_Return;

	// IFEXISTSVIEWS
	} else return false;

}