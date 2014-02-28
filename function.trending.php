<?php

// ### Trending Function ###
//
//

function Trending($Trend_Limit = 10, $Trend_Type = 'Post', $Trend_Strip = false, $Trend_Strict = false) {

	// Set some Globals
	global $MySQL_Connection, $Sitewide_Root, $Canonical;

	// Make sure $Trend_Type is sensible
	if ($Trend_Type != 'Post' && $Trend_Type != 'Page' && $Trend_Type != 'Forum Topic' && $Trend_Type != 'Forum Category' && $Trend_Type != 'Blog Category') $Trend_Type = 'Post';

	// Build Query
	if ($Trend_Strict) $Query = 'SELECT COUNT(DISTINCT `Member_ID`) AS `Count`,';
	else $Query = 'SELECT COUNT(*) AS `Count`,';
	$Query .= ' `Canonical` FROM `Views` WHERE `Post_Type`=\''.$Trend_Type.'\' GROUP BY `Canonical` ORDER BY `Count` DESC LIMIT 0, '.$Trend_Limit;

	$Trending = mysqli_query($MySQL_Connection, $Query, MYSQLI_STORE_RESULT);
	if (!$Trending) exit('Invalid Query (View): '.mysqli_error($MySQL_Connection));

	// TODO If $Trend_Strip remove own page or input

	// TODO Return Array (?)

}
