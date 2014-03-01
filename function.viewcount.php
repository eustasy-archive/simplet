<?php

// ### View Count Function ###
//
//

function ViewCount() {

	// Set some Globals
	global $Canonical, $Member_Auth, $Member_ID, $Member_Admin, $Time, $Place, $Post_Type, $MySQL_Connection, $Sitewide_Root, $User_IP, $User_Cookie;

	// Build Query
	$Query = 'INSERT INTO `Views` (`Request`, `Canonical`, `Post_Type`, `IP`, `Cookie`, `Auth`, `Member_ID`, `Admin`, `Time`) VALUES (\''.$Place['scheme'].'://'.$Place['host'].$_SERVER['REQUEST_URI'].'\', \''.$Canonical.'\', \''.$Post_Type.'\', \''.$User_IP.'\', \''.$User_Cookie.'\', \''.$Member_Auth.'\', \''.$Member_ID.'\', \''.$Member_Admin.'\', \''.$Time.'\')';

	$View = mysqli_query($MySQL_Connection, $Query, MYSQLI_STORE_RESULT);
	if (!$View) exit('Invalid Query (View): '.mysqli_error($MySQL_Connection));

}
