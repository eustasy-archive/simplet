<?php

////	Database Table Exists Function
//
// Checks whether or not a table exists.
//
// Database_Table_Exists('Topics');

function Database_Table_Exists($Table_Name, $AddPrefix = true) {

	global $Database;

	// Assemble the Query
	$Database_Table_Exists_Query = 'SELECT * FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA`=\''.$Database['Name'].'\' AND `TABLE_NAME`=\'';
	if ( $AddPrefix ) $Database_Table_Exists_Query .= $Database['Prefix'];
	$Database_Table_Exists_Query .= $Table_Name.'\'';

	// Execute the Query
	// SPECIAL CASE: This query doesn't need to be inside a table exists check because it is querying a table from outside Simplet.
	$Database_Table_Exists_Query = mysqli_query($Database['Connection'], $Database_Table_Exists_Query, MYSQLI_STORE_RESULT);

	// IFNOTQUERY: The query failed.
	if ( !$Database_Table_Exists_Query ) {
		if ( $Sitewide_Debug ) echo 'Invalid Query (Key_Check): '.mysqli_error($Database['Connection']);
		return false;

	// The Query was executed successfully.
	} else {
		// Fetch the count.
		$Database_Table_Exists_Query_Count = mysqli_num_rows($Database_Table_Exists_Query);
		// If the count is positive it exists.
		if ( $Database_Table_Exists_Query_Count > 0 ) return true;
		// If the count is zero it does not.
		else return false;

	} // IFNOTQUERY

}