<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once $Backend['functions'].'generator.string.php';
include_once $Backend['functions'].'runonce.create.php';
include_once $Backend['functions'].'runonce.delete.php';
$Return['Name'] = 'RunOnce Create';
$Return['Status'] = 'Failure';

$Member_ID = 'RUNONCE_CREATE';

$Runonce_Create = Runonce_Create('1hour', 1, 'RUNONCE_CREATE');

if ( empty($Runonce_Create['error']) ) {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Runonce_Create;
} else {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], $Runonce_Create['error']);
}


$Runonce_Delete = 'DELETE FROM `'.$Database['Prefix'].'Runonce` WHERE `Key`=\''.$Runonce_Create['Key'].'\'';
$Runonce_Delete = mysqli_query($Database['Connection'], $Runonce_Delete, MYSQLI_STORE_RESULT);
if ( !$Runonce_Delete ) {
	if ( $Backend['Debug'] ) {
		echo 'Error: Invalid Query (Key_Delete): ',mysqli_error($Database['Connection']);
	}
}

echo API_Output($Return);