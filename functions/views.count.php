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

function Views_Count() {
	
	global $Canonical, $Database, $Member_Admin, $Member_Auth, $Member_ID, $Place, $Post_Type, $Sitewide_Debug, $Sitewide_Root, $Time, $User_Cookie, $User_IP;
	
	// IFEXISTSVIEWS
	if ( $Database['Exists']['Views'] ) {
		
		// Encode and Assemble the requested URL.
		$Requested = $Place['scheme'].'://'.$Place['host'].htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
		
		// Assemble the Query
		$Query = 'INSERT INTO `'.$Database['Prefix'].'Views` (`Request`, `Canonical`, `Post_Type`, `IP`, `Cookie`, `Auth`, `Member_ID`, `Admin`, `Time`) VALUES (\''.$Requested.'\', \''.$Canonical.'\', \''.$Post_Type.'\', \''.$User_IP.'\', \''.$User_Cookie.'\', \''.$Member_Auth.'\', \''.$Member_ID.'\', \''.$Member_Admin.'\', \''.$Time.'\')';
		
		// Run the Query
		$View = mysqli_query($Database['Connection'], $Query, MYSQLI_STORE_RESULT);
		
		// If it fails, return false.
		if ( !$View &&  $Sitewide_Debug) {
			echo 'Invalid Query (View): '.mysqli_error($Database['Connection']);
			return false;
			
		// Otherwise return true.
		} else return true;
		
	// IFEXISTSVIEWS
	} else return false;
	
}