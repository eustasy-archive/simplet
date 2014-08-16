<?php

// ### Database Table Exists Function ###
//
// Checks whether or not a table exists.
//
// Database_Table_Exists('Topics');

function Database_Table_Exists($Table_Name, $AddPrefix = true) {

	global $Database;
	
	$Database_Table_Exists_Query = 'SELECT * FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA`=\''.$Database['Name'].'\' AND `TABLE_NAME`=\'';
	if ( $AddPrefix ) $Database_Table_Exists_Query .= $Database['Prefix'];
	$Database_Table_Exists_Query .= $Table_Name.'\'';
	
	$Database_Table_Exists_Query = mysqli_query($Database['Connection'], $Database_Table_Exists_Query, MYSQLI_STORE_RESULT);
	
	if ( !$Database_Table_Exists_Query ) {
		echo 'Invalid Query (Key_Check): '.mysqli_error($Database['Connection']);
		return false;
	}
	
	$Database_Table_Exists_Query_Count = mysqli_num_rows($Database_Table_Exists_Query);
	if ( $Database_Table_Exists_Query_Count > 0 ) return true;
	else return false;
	
}