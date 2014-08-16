<?php

// ### View Count Function ###
// Count a view for the current page.
// Used for generating Trending.

function ViewCount() {
	
	// Set some Globals
	global $Database, $Canonical, $Member_Auth, $Member_ID, $Member_Admin, $Time, $Place, $Post_Type, $Sitewide_Root, $User_IP, $User_Cookie, $Sitewide_Debug;
	
	if ( $Database['Exists']['Views'] ) {
		
		$Server_Request_URI_Entities = htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
		
		$Query = 'INSERT INTO `'.$Database['Prefix'].'Views` (`Request`, `Canonical`, `Post_Type`, `IP`, `Cookie`, `Auth`, `Member_ID`, `Admin`, `Time`) VALUES (\''.$Place['scheme'].'://'.$Place['host'].$Server_Request_URI_Entities.'\', \''.$Canonical.'\', \''.$Post_Type.'\', \''.$User_IP.'\', \''.$User_Cookie.'\', \''.$Member_Auth.'\', \''.$Member_ID.'\', \''.$Member_Admin.'\', \''.$Time.'\')';
		$View = mysqli_query($Database['Connection'], $Query, MYSQLI_STORE_RESULT);
		
		if ( !$View &&  $Sitewide_Debug) {
			echo 'Invalid Query (View): '.mysqli_error($Database['Connection']);
			return false;
		} else return true;
		
	} else return false;
	
}