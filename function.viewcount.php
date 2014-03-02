<?php

// ### View Count Function ###
//
//

function ViewCount() {

	// Set some Globals
	global $Canonical, $Member_Auth, $Member_ID, $Member_Admin, $Time, $Place, $Post_Type, $MySQL_Connection, $Sitewide_Root, $User_IP, $User_Cookie;

	$Server_Request_URI_Entities = htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');

	// Build Query
	$Query = 'INSERT INTO `Views` (`Request`, `Canonical`, `Post_Type`, `IP`, `Cookie`, `Auth`, `Member_ID`, `Admin`, `Time`) VALUES (\''.$Place['scheme'].'://'.$Place['host'].$Server_Request_URI_Entities.'\', \''.$Canonical.'\', \''.$Post_Type.'\', \''.$User_IP.'\', \''.$User_Cookie.'\', \''.$Member_Auth.'\', \''.$Member_ID.'\', \''.$Member_Admin.'\', \''.$Time.'\')';

	$View = mysqli_query($MySQL_Connection, $Query, MYSQLI_STORE_RESULT);
	if (!$View) exit('Invalid Query (View): '.mysqli_error($MySQL_Connection));

}
