<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['functions'].'database.size.php';
$Return['Name'] = 'Database Size';
$Return['Status'] = 'Failure';

$Database_Size = Database_Size();

if ( $Database_Size ) {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Database_Size;
} else {
	$Return['Errors'] = 'Function returned false.';
}

echo API_Output($Return);