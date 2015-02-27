<?php

////	Views Count Function
// Count a view for the current page.
// Used for generating Trending.
//
// TODO
// Instead, split URL into components and store in database like that.
// protocol	=> http
// domain	=> example.com
// path		=> product
// get		=> array('id' => '2', 'utm_source' => 'twitter');

function View_Count() {

	global $Backend, $Canonical, $Database, $Member, $Page, $Request, $Time, $User;

	// IFEXISTSVIEWS
	if ( $Database['Exists']['Views'] ) {

		// Encode and Assemble the requested URL.
		$Requested = $Request['Scheme'].'://'.$Request['Host'].Input_Prepare($Request['Path']);

		// Assemble the Query
		$Query = 'INSERT INTO `'.$Database['Prefix'].'Views` '.
		'(`Request`, `Canonical`, `Post_Type`, `IP`, `Cookie`, `Auth`, `Member_ID`, `Admin`, `Time`) VALUES '.
		'(\''.$Requested.'\', \''.$Canonical.'\', \''.$Page['Type'].'\', \''.$User['IP'].'\', \''.$Member['Cookie'].'\', \''.$Member['Auth'].'\', \''.$Member['ID'].'\', \''.$Member['Admin'].'\', \''.$Time['Now'].'\')';

		// Run the Query
		$Views_Count = mysqli_query($Database['Connection'], $Query, MYSQLI_STORE_RESULT);

		// If it fails, return false.
		if (
			!$Views_Count &&
			$Backend['Debug']
		) {
			return array('Error' => 'Invalid Query (Views_Count): '.mysqli_error($Database['Connection']));
		} else {
			return true;
		}

	// IFEXISTSVIEWS
	} else {
		return false;
	}

}