<?php

// ### Database Table Exists Function ###
//
// Checks whether or not a table exists.
//
// Database_Table_Exists('Topics');

function Database_Table_Exists($Table_Name) {
	
	// Set some Globals
	global $Database_Name, $MySQL_Connection;
	
 	$Database_Table_Exists_Query = 'SELECT * FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA`=\''.$Database_Name.'\' AND `TABLE_NAME`=\''.$Table_Name.'\'';
 	
	$Database_Table_Exists_Query = mysqli_query($MySQL_Connection, $Database_Table_Exists_Query, MYSQLI_STORE_RESULT);
	
	if (!$Database_Table_Exists_Query) {
		echo 'Invalid Query (Key_Check): '.mysqli_error($MySQL_Connection);
		return false;
	}
	
	$Database_Table_Exists_Query_Count = mysqli_num_rows($Database_Table_Exists_Query);
	if ($Database_Table_Exists_Query_Count > 0) return true;
	else return false;
	
}