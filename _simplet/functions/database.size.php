<?php

////	Database Size Function
//
// Returns an array of sizes of the database.
//
//	[
//		'Data' => ####
//		'Indexes' => ####
//		'Total' => ####
//		'Free' => ####
//	]
//
// Database_Size();

function Database_Size() {

	global $Backend, $Database;

	// Assemble the Query
	require $Backend['simplet'].'config.database.php';
	$Database_Size_Query = 'SELECT data_length AS `Data`, index_length AS `Indexes`, sum( data_length + index_length ) AS `Total`, sum( data_free ) AS `Free` FROM information_schema.TABLES WHERE table_schema = \''.$Database['Name'].'\' GROUP BY table_schema';
	$Database['Host'] = $Database['User'] = $Database['Pass'] = $Database['Name'] = true;

	// Execute the Query
	// SPECIAL CASE: This query doesn't need to be inside a table exists check because it is querying a table from outside Simplet.
	$Database_Size_Query = mysqli_query($Database['Connection'], $Database_Size_Query, MYSQLI_STORE_RESULT);

	// IFNOTQUERY: The query failed.
	if ( !$Database_Size_Query ) {
		if ( $Backend['Debug'] ) {
			echo 'Invalid Query (Database_Size_Query): '.mysqli_error($Database['Connection']);
		}
		return false;

	// IFNOTQUERY The Query was executed successfully.
	} else {
		return mysqli_fetch_assoc($Database_Size_Query);
	}



}
