<?php

include_once __DIR__.'/initialize.php';
include_once __DIR__.'/../database.table.exists.php';
$Return['Name'] = 'Database Table Exists';
$Return['Status'] = 'Failure';

$Database_Table_Exists = Database_Table_Exists('Topics');
if ( $Database_Table_Exists ) {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Database_Table_Exists;
} else array_push($Return['Errors'], 'Database_Table_Exists Function returned false.');

API_Output($Return);