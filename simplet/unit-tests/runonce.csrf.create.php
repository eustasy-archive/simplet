<?php

include_once __DIR__.'/../auto-test_initialize.php';
include_once __DIR__.'/../functions/generator.string.php';
include_once __DIR__.'/../functions/runonce.create.php';
include_once __DIR__.'/../functions/runonce.csrf.create.php';
include_once __DIR__.'/../functions/runonce.delete.php';
$Return['Name'] = 'RunOnce CSRF Create';
$Return['Status'] = 'Failure';

$Runonce_CSRF_Create = Runonce_CSRF_Create('1hour', 1, 'RUNONCE_CREATE');

if ( empty($Runonce_CSRF_Create['error']) ) {
	$Return['Status'] = 'Success';
	$Return['Result'] = $Runonce_CSRF_Create;
} else {
	$Return['Status'] = 'Failure';
	array_push($Return['Errors'], $Runonce_CSRF_Create['error']);
}

$Runonce_Delete = 'DELETE FROM `'.$Database['Prefix'].'Runonce` WHERE `Key`=\''.$Runonce_CSRF_Create['Key'].'\'';
$Runonce_Delete = mysqli_query($Database['Connection'], $Runonce_Delete, MYSQLI_STORE_RESULT);
if (!$Runonce_Delete) 'Error: Invalid Query (Key_Delete): '.mysqli_error($Database['Connection']);

echo API_Output($Return);