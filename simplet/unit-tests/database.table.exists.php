<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once __DIR__.'/../functions/database.table.exists.php';
$Return['Name'] = 'Database Table Exists';
$Return['Status'] = 'Failure';

$Check_True = Database_Table_Exists('Topics');
$Check_False = Database_Table_Exists('NOTATABLE');

if ( $Check_True && !$Check_False ) {
	$Return['Status'] = 'Success';
	$Return['Result'] = array();
	$Return['Result']['Topics'] = $Check_True;
	$Return['Result']['NOTATABLE'] = $Check_False;
} else {
	if ( !$Check_True ) $Return['Errors']['Topics'] = 'Database_Table_Exists Function returned false when it should be true.';
	if ( $Check_False ) $Return['Errors']['NOTATABLE'] = 'Database_Table_Exists Function returned true when it should be false.';
}

echo API_Output($Return);