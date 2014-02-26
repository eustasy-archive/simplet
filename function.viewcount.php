<?php

// ### View Count Function ###
//
//

function ViewCount() {

	// Set some Globals
	global $Canonical, $Member_Auth, $Member_Name, $Member_Mail, $Member_Admin, $Time, $Request, $MySQL_Connection, $Sitewide_Root, $User_IP, $User_Cookie;

	// Build Query
	// TODO Expand Request to Include Query Strings
	$Query = 'INSERT INTO `Views` (`Request`, `Canonical`, `IP`, `Cookie`, `Auth`, `Mail`, `Admin`, `Time`) VALUES (\''.$Request['scheme'].'://'.$Request['host'].$Request['path'].'\', \''.$Canonical.'\', \''.$User_IP.'\', \''.$User_Cookie.'\', \''.$Member_Auth.'\', \''.$Member_Mail.'\', \''.$Member_Admin.'\', \''.$Time.'\')';
	// echo $Query;

	$View = mysqli_query($MySQL_Connection, $Query, MYSQLI_STORE_RESULT);
	if (!$View) exit('Invalid Query (View): '.mysqli_error($MySQL_Connection));


}
