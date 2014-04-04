<?php

// ### Database Table Exists Function ###
//
// Checks whether or not a table exists.
//
// Database_Table_Exists('Topics');

function Database_Table_Exists($Table_Name) {

	// Set some Globals
	global $MySQL_Connection;
	
	$Database_Table_Exists_Query = mysqli_query($MySQL_Connection, 'SELECT COUNT(`TABLE_NAME`) AS `Count` FROM `information_schema`.`TABLES` WHERE `TABLE_NAME`=\''.$Table_Name.'\'', MYSQLI_STORE_RESULT);
	if (!$Database_Table_Exists_Query) {
		echo 'Invalid Query (Key_Check): '.mysqli_error($MySQL_Connection);
		return false;
	}

	$Database_Table_Exists_Query_Fetch = mysqli_fetch_assoc($Database_Table_Exists_Query);
	if ($Database_Table_Exists_Query_Fetch['Count'] > 0) return true;
	else return false;

}